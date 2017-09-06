<?php

class _Router extends _App_Interface
{
	// Attributs
	
		/*	listRoutes : 
			Liste des routes sauvegardées sous form ID => [url, actions]
		*/	private $listRoutes;
	
		/*	mapRoutes : 
			Mapping des url sauvegardées pour recherche par url
		*/	private $mapRoutes;
	
		/*	parameters : 
			Paramètres enregistrés dans l'url actuelle
		*/	private $parameters;
	
		/*	errors : 
			Liste d'actions pour les erreurs
		*/	private $errors = [];
	
		/*	config : 
			Configuration du routeur
		*/	private $config = [];
	
	// Constructor
		/*	constructeur
			Reçois en paramètre la config de l'application relative au routeur
		*/	public function __construct($config)
			{
					$this->listRoutes = [];
					$this->mapRoutes = [];
					$this->parameters = [];
					$this->config = $config;
					$this->errors = ['404'=>'_action:errors:_Error404', '403'=>'_action:errors:_Error403', 'no action'=>'_action:errors:_Error404'];
					
				/* Verif config */
					if(isset($config['error_404_action']))$this->errors['404'] = $config['error_404_action'];
					if(isset($config['error_403_action']))$this->errors['403'] = $config['error_403_action'];
					
				/* LOG */
					$this->_addLog('creating router', 1);
					$this->_addLog('error 403 action : ['.$this->errors['403'].']');
					$this->_addLog('error 404 action : ['.$this->errors['404'].']');
			}
	
	// public functions
		/*	addRoute : 
			Ajoute une route à la liste et au mapping
			$route : ID de la route
			$url : url de la route
			$array : liste des actions et conditions
			retourne le statut : 	[0] tout c'est bien passé
									[1] la route remplace une ancienne de même id
									[2] l'url existait déjà et a été remplacée
		*/	public function addRoute($route, $url, $array, $params = null)
			{
				$statut = 0;
				/* vérification des actions et permissions */
					if(!is_array($array))
						$array = [[$array,'']];
					else
					{
						foreach($array as $k => $r)
						{
							if(!is_array($r))
								$array[$k] = [$r, ''];
						}
					}
				
				/* On ajoute la route au mapping et à la liste */
					if(!$this->addMapRoute($this->mapRoutes, $this->convertUrl($url), $route))
					{
						$this->_addLog('Conflit : Conflit : url ['.$url.'] d&eacute;j&agrave; utilis&eacute;e (remplac&eacute;e)', 2);
						$statut = 2;
					}
				
				/* On vérifie si l'id de la route n'est pas déjà utilisé */
					if(isset($this->listRoutes[$route]))
					{
						$this->listRoutes['!'.$route] = $this->listRoutes[$route];
						$this->listRoutes['!'.$route]->notValid();
						$this->listRoutes['!'.$route]->setName('!'.$route);
						$this->_addLog('Rt01: Conflit : La route ['.$route.'] existe d&eacute;j&agrave; (remplac&eacute;e)', 2);
						$statut = 1;
					}
					$this->listRoutes[$route] = new _Route($route, $url, $array, $params);
				
				/* retour */
					$this->_setState($statut);
					return ($statut == 0)? true : false;
			}
	
		/*	getRoute : 
			Retourne une route, ou false, avec affichage d'une erreur 'route introuvable'
			$route : ID de la route à retourner
		*/	public function getRoute($route)
			{
				$res = false;
				if(isset($this->listRoutes[$route]))
					$res = $this->listRoutes[$route];
				else
					$res = $this->_setError('Rt02: La route ['.$route.'] est introuvable', 1);
				return $res;
			}
	
		/*	getRoutes : 
			Retourne la liste complète des routes
		*/	public function getRoutes(){return $this->listRoutes;}
	
		/*	getParameters : 
			Retourne les paramètres sauvegardés
		*/	public function getParameters(){return $this->parameters;}
	
		/*	getErrorAction : 
			Retourne l'action de l'erreur voulue
		*/	public function getErrorAction($err){return (isset($this->errors[$err]))? $this->errors[$err] : false;}
	
		/*	getActionForUrl : 
			Retourne l'action qui correspond à la route passée en paramètre
			$url : route recherchée
			$user : user pour la gestion des rangs
		*/	public function getActionForUrl($url, $user)
			{
				/* settings */
					$this->parameters = [];
					$a = $this->convertUrl($url);
					$m = $this->mapRoutes;
					$accept = false;
					$select = false;
				
				/* Lecture de l'url et du mapping */
					foreach($a as $c)
					{
						/* new part of parameters list */
						if($accept)
							$this->parameters[] = $c;
						/* section exist */
						elseif(isset($m[$c]))
							$m = $m[$c];
						/* section is a list of parameter */
						elseif(isset($m['**']))
						{
							$m = $m['**'];
							$this->parameters[] = $c;
							$accept = true;
						}
						/* section is a parameter */
						elseif(isset($m['*']))
						{
							$m = $m['*'];
							$this->parameters[] = $c;
						}
						/* section doesn't exists */
						else
							$m = false;
					}
				
				/* si le chemin ($m) est complet et qu'il comporte une liste d'actions $m['_route'] */
					if($m && isset($m['_route']))
					{
						$this->_addLog('route trouv&eacute;e pour l\'url ['.$url.']');
						$select = $this->getActionForRoute($m['_route'], $user);
						$log = 'Url param : ';
						foreach($this->parameters as $k => $p)
							$log .= '['.$k.':"'.$p.'"] ';
						$this->_addLog($log);
					}
					
				/* Sinon l'url demandée ne correspond à aucune action */
					if($select === false)
					{
						$this->_addLog('route non trouv&eacute;e pour l\'url ['.$url.']', 2);
						$select = $this->errors['404'];
					}
				
				return $select;
			}
	
		/*	getActionForRoute : 
			Retourne l'action qui correspond à l'utilisateur dans la route demandée
			$route : id de la route
			$user : utilisateur pour les droits
		*/	public function getActionForRoute($route, $user=null)
			{
					$res = false;
					if($user == null)
						$user = new _User();
				/* On récupère les actions et permissions de la route */
					if(isset($this->listRoutes[$route]))
					{
						if(!($res = $this->listRoutes[$route]->getAction($user)))
						{
							$this->_addLog('permissions non accord&eacute;es', 2);
							$res = $this->errors['403'];
						}
						else
						{
							_Action::_setParams($this->listRoutes[$route]->getParams());
						}
					}
				/* Si la route n'existe pas on affiche une erreur et retourne l'action définie par la config */
					else
					{
						$this->_setError('Rt02: La route ['.$route.'] est introuvable', 2);
						$res = $this->errors['404'];
					}
				return $res;
			}
	
	// privates functions
			
		/*	convertUrl : 
			Convertit une url en array (sans paramètres, séparé par les /)
		*/	private function convertUrl($url)
			{
				if($url == '')return [''];
				$a = explode('/', $url);
				if($a[0] == '')array_shift($a);
				if($a[count($a)-1] == '')array_pop($a);
				return $a;
			}
			
		/*	addMapRoute : 
			Ajoute un array d'action au mapping des routes
			Le mapping permet une navigation plus simple parmis les url
		*/	private function addMapRoute(&$map, $url, $route)
			{
				$res = true;
				if(empty($url))
				{
					if(isset($map['_route']))
					{
						$this->listRoutes[$map['_route']]->notValid();
						$res = false;
					}
					$map['_route'] = $route;
					return $res;
				}
				else
				{
					$index = array_shift($url);
					if(!isset($map[$index]))
						$map[$index] = [];
					return $this->addMapRoute($map[$index], $url, $route);
				}
			}
	
	// project functions
		/*	_define : 
			Retourne les informations du routeur pour une lecture du projet
		*/	public function _define()
			{
				return ['nbRoutes' => count($this->listRoutes),
						'config' => $this->config,
						'errors' => $this->errors,
						'list' => $this->listRoutes,
						'params' => $this->parameters];
			}
}

?>