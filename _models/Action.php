<?php

class _Action extends _App_Interface
{
	// Attributs
		/*	return_Method
			Nom de la méthode de retour (affichage ou resource)
			Modifiable via la commande _setReturnMethod()
		*/	private $return_Method = 'getReturn';
		
		/*	redirect
			actif si l'utilisateur est redirigé vers une autre action
			activable via la commande _redirect(act)
		*/	private $redirect = false;
		
		/*	actionParams
			Les paramètres de l'action passés via la route empruntée
		*/	private static $actionParams;
		
		/*	module
			Module de l'action
		*/	private $module;
	
	// Constructeur
		/*	constructeur
			Reçois en paramètre le module de l'action
		*/	public function __construct($mod)
			{
				$this->module = $mod;
			}
	
	// public functions
		/*	_setParams
			Ajoute des paramètres à l'action en cours
			Si $param est un tableau, le tableau est ajouté.
			Sinon, la valeur val est ajouté à la case $param.
		*/	public static function _setParams($param, $val=null)
			{
				if(self::$actionParams == null)self::$actionParams = [];
				if($param != null)
				{
					if(is_array($param))
					{
						foreach($param as $p => $v)
							self::$actionParams[$p] = $v;
					}
					else
						self::$actionParams[$param] = $val;
				}
			}
	
		/*	_getParam
			Retourn le ou les paramètres d'action demandé(s)
		*/	public static function _getParam($p= null)
			{
				if($p == null)
					return self::$actionParams;
				if(isset(self::$actionParams[$p]))
					return self::$actionParams[$p];
				else
					return false;
			}
	
	
		/*	_redirect
			Redirige l'utilisateur vers une autre action
		*/	protected function _redirect($act)
			{
				$this->redirect = true;
				return $act;
			}
	
		/*	_getReturn
			Retourne le résultat de la fonction de retour (la fonction pouvant varier)
		*/	public function _getReturn()
			{
				$met = $this->return_Method;
				return $this->$met();
			}
	
	public function load(){}
	public function POST(){}
	public function GET(){}
	public function PUT(){}
	public function DELETE(){}
	public function UPDATE(){}
	public function OPTIONS(){}
	public function execute(){}
	public function getReturn(){}
	public function _isRedirect(){return $this->redirect;}
	public function _getModule(){return $this->module;}
	public static function _clearParams(){self::$actionParams = [];}
	protected function _setReturnMethod($met){$this->return_Method = $met;}
	
	
		/*	loadAction
			Charge une action via son selecteur
		*/	public static function loadAction($select)
			{
				$int = new _App_Interface();
				$act = $int->_get($select);
				if(!$int->_valid($act))
				{
					$select = $int->_getApp()->getRouter()->getErrorAction('no action');
					$act = $int->_get($select);
				}
				return $act;
			}
}

?>