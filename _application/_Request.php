<?php

class _Request extends _Sys_Files
{
	// Attributs
	
		/*	requestUrl 
			L'url demandée
		*/	private $requestUrl;
		
		/*	requestMethod 
			Méthode http utilisée (GET, POST, DELETE ...)
		*/	private $requestMethod;
		
		/*	userIP 
			Adresse IP de l'utilisateur
		*/	private $userIP;
	
	// constructeur
	
		/*	constructeur
			Reçois en paramètre la config de l'application relative à la requête
		*/	public function __construct($config)
			{
				/* request Url */
					/* get full request url, substract base app url */
					$url = str_replace(URL_APP, '', $_SERVER['REQUEST_URI']);
					/* if the url contains parameters, substract them */
					$pos = strpos($url, '?');
					if($pos > 0){$url=substr($url, 0, $pos);}
					/* set requestUrl */
					$this->requestUrl = $url;
				
				/* Request method */
					$this->requestMethod = $_SERVER['REQUEST_METHOD'];
				
				/* User IP */
					/* IP si internet partagé */
					if (isset($_SERVER['HTTP_CLIENT_IP'])) $this->userIP = $_SERVER['HTTP_CLIENT_IP'];
					/* IP derrière un proxy */
					elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $this->userIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
					/* Sinon : IP normale */
					else $this->userIP = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
				
				/* LOG */
					$this->_addLog('creating Request', 1);
					$this->_addLog('Request URL : [' . $this->requestUrl .']');
					$this->_addLog('Request METHOD : [' . $this->requestMethod .']');
					$this->_addLog('Request IP : [' . $this->userIP .']');
			}
	
	// fonctions publiques
	
		/*	getRequestUrl 
			Retourne l'url demandée par l'utilisateur
		*/	public function getRequestUrl(){return $this->requestUrl;}
		
		/*	getRequestMethod 
			Retourne la requête http utilisée
		*/	public function getRequestMethod(){return $this->requestMethod;}
		
		/*	getUserIP 
			Retourne l'addresse IP de l'utilisateur
		*/	public function getUserIP(){return $this->userIP;}
}



?>