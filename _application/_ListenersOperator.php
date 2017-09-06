<?php

class _ListenersOperator extends _App_Interface
{
	// Attributs
	
		/*	listListeners : 
			Liste des listeners enregistrés
		*/	private $listListeners = [];
	
		/*	enabled : 
			Booléen de désactivation des listeners
		*/	private $enabled = true;
	
		/*	config : 
			Configuration du routeur
		*/	private $config = [];
	
	// Constructor
		/*	constructeur
			Reçois en paramètre la config de l'application relative au routeur
		*/	public function __construct($config)
			{
				$this->_addLog('vérifier qu\'un listener existe avant de l\'ajouter',3);
				$this->config = $config;
			}
	
	// Fonctions publiques
	
		/*	addListener : 
			Ajoute un listener à la liste
			$select : identifiant du listener
			$p : priorité, sera ajouté en début de liste si $p = true
		*/	public function addListener($select, $p=false)
			{
				/* lecture du selecteur */
					$listener = $this->_get($select);
					
				/* ajout du listener */
					if(!$p) array_push($this->listListeners, $listener);
					else array_unshift($this->listListeners, $listener);
				
				/* LOG */
					$this->_addLog('Ajout du listener ['.$select.']');
					return $this;
			}
	
		/*	toggleListeners : 
			Switch l'activation des listeners
		*/	public function toggleListeners($switch)
			{
				$this->enabled = $switch;
			}
	
	// Fonctions d'écoute
	
		/*	initialize : 
			Initialise le listener, se déroule avant le dispatch.
		*/	public function initialize($app)
			{
				if($this->enabled)
				{
					foreach($this->listListeners as $l)
					{
						$l->initialize();
					}
				}
			}
	
		/*	beforeExecute : 
			Execute la fonction des listeners avant l'execution de l'action
			Execute la fonction de module
		*/	public function beforeExecute($app)
			{
				if($this->enabled)
				{
					foreach($this->listListeners as $l)
					{
						$l->beforeExecute();
						if($l->getModule() == $app->getModule())
							$l->beforeExecute_ForMod();
					}
				}
			}
		
		/*	beforeSend : 
			Permet de modifier le retour de l'action.
			Execute la fonction de module
			$ret : le retour d'action actuel à modifier
			retourne le retour d'action modifié
		*/	public function beforeSend($app, $ret)
			{
				if($this->enabled)
				{
					foreach($this->listListeners as $l)
					{
						if(($r = $l->beforeSend($ret)) != null)
							$ret = $r;
						if($l->getModule() == $app->getModule())
						{
							if(($r = $l->beforeSend_ForMod($ret)) != null)
								$ret = $r;
						}

					}
				}
				return $ret;
			}
			
	// project functions
		/*	_define : 
			Retourne les informations du routeur pour une lecture du projet
		*/	public function _define()
			{
				return ['enabled' => $this->enabled,
						'config' => $this->config,
						'list' => $this->listListeners];
			}
}

?>