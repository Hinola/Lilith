<?php

class _App_Interface extends _Sys_Files
{
	// Attributs
		/* Application */
		private static $app;
	
	// commandes
		/* Commandes de routeur */
			protected function _addRoute($route, $url, $actions, $params = null){return self::$app->getRouter()->addRoute($route, $url, $actions, $params);}
			protected function _getUrl($route){return URL_APP . self::$app->getRouter()->getRoute($route)->getUrl();}
		
		/*	Commandes de selecteur */
			protected function _get($select){return self::$app->getSelectReader()->readSelect($select);}
			
		/*	Commandes sur listeners */
			protected function _addListener($select){return self::$app->getListenersOperator()->addListener($select);}
			protected function _toggleListeners($switch){return self::$app->getListenersOperator()->toggleListeners($switch);}
		
		/* Actions sur l'application */
			protected function _getApp(){return self::$app;}
		/*	saveApp
			Vérifie que l'application n'existe pas encore dans l'attribut
			Sauvegarde l'app
		*/	public static function _saveApp($app)
			{
				if(self::$app == null)
				{
					self::$app = $app;
					$app->_addLog('App saved');
				}
				else
				{
					$app->_addLog('Can\'t save app (already exist)', 2);
				}
			}
}

?>