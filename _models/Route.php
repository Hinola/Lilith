<?php

class _Route extends _Sys_Files
{
	// Attributs
		/*	name : 
			Nom de la route, attributs unique et clé de liste
		*/	private $name;
	
		/*	url : 
			url liée à la route
		*/	private $url;
	
		/*	actions : 
			liste des actions et permissions
		*/	private $actions;
	
		/*	params : 
			paramètres de la route
		*/	private $params;
	
		/*	params : 
			paramètres de la route
		*/	private $valid;
	
	// Constructor
		public function __construct($name, $url, $array, $para = null)
		{
			$this->name = $name;
			$this->url = $url;
			$this->actions = $array;
			$this->params = $para;
			$this->valid = true;
		}
	
	// public functions
	
		/*	getAction : 
			retourne l'action demandée en fonction des permissions de l'utilisateur
			$user : l'utilisateur demandant l'accès
		*/	public function getAction($user)
			{
				$res = false;
				foreach($this->actions as $a)
				{
					$ranks = $a[1];
					$action = $a[0];
					if($user->isRanked($ranks))
						$res = $action;
				}
				return $res;
			}
	
	public function getUrl(){return $this->url;}
	
	public function getParams(){return $this->params;}
	
	public function getName(){return $this->name;}
	public function setName($n){$this->name = $n;}
	
	public function notValid(){$this->valid = false;}
	
	public function _define()
	{
		return ['name' => $this->name,
				'valid' => $this->valid,
				'url' => $this->url,
				'actions' => $this->actions,
				'params' => $this->params];
	}
}

?>