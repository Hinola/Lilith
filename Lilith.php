<?php

/* Define constant */
	define('DIR_LILITH',__dir__);
	define('URL_LILITH','/v9/Lilith');
	define('LILITH_VERSION','0.9.1');

/* Load system files */
	require_once('_system/_Sys_Errors.php');
	require_once('_system/_Sys_Files.php');
	require_once('_system/_Functions.php');

/* Load application files */
	/* main */
	require_once('_application/_Application.php');
	require_once('_application/_App_Interface.php');
	require_once('_application/_Project.php');
	/* operators */
	require_once('_application/_ManagersOperator.php');
	require_once('_application/_ListenersOperator.php');
	require_once('_application/_UserPartsOperator.php');
	require_once('_application/_ModulesOperator.php');
	/* components */
	require_once('_application/_SelectReader.php');
	require_once('_application/_Router.php');
	require_once('_application/_Session.php');
	require_once('_application/_Request.php');
	/* models */
	require_once('_models/Route.php');
	require_once('_models/Action.php');
	require_once('_models/Listener.php');
	require_once('_models/Model.php');
	require_once('_models/User.php');

/* Load Lilith models */

class _Lilith
{
	// Logs management
	/*	listLogs : 
		Liste des logs de suivit pour suivre le déroulement du site
	*/	private static $listLogs =[];
	
	/*	_addLog : 
		Ajoute un Log à la liste
		$l : chaine de description du log
		$n : niveau du log (0 pour un log normal)
	*/	protected function _addLog($l, $n=0) {self::$listLogs[]=[$l, $n, get_class($this)];}
	
	/*	_getLogs : 
		Retourn la liste des logs
	*/	protected function _getLogs() {return self::$listLogs;}
	
	// states management
	/*	listStates : 
		Liste des statut de function
	*/	private static $listStates =[];
	
	/*	_setState :
		Ajoute un statut à la liste pour la fonction actuelle
	*/	protected function _setState($s) {self::$listStates[]=$s;}
	
	/*	_lastState :
		Retourne le dernier statut, ou un statut précédent
	*/	protected function _getState($i=-1) {
			$index = ($i>=0)? $i : count(self::$listStates)+$i; 
			return (isset(self::$listStates[$index]))? self::$listStates[$index] :-1 ;
		}
}

?>