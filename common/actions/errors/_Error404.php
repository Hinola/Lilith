<?php

class _Error404 extends _Action
{
	public function load()
	{
		$this->_toggleListeners(false);
		$this->_requireFile(DIR_LILITH . '/common/actions/errors/_ErrorDisplay.php');
	}
	
	public function getReturn()
	{
		/* Logs */
			$local = false;
			$site = '/http:\/\/'.$_SERVER["SERVER_NAME"].'/';
			if(!isset($_SERVER["HTTP_REFERER"]))
				$this->_addLog('404 from direct url', 2);
			else
			{
				if (!preg_match($site, $_SERVER["HTTP_REFERER"]))
					$this->_addLog('404 from ' . $_SERVER["HTTP_REFERER"], 2);
				else
				{
					$this->_addLog('404 from local', 2);
					$local = true;
				}
			}
		
		/* header */
			header("HTTP/1.0 404 Not Found");
		
		/* textes */
			$text = 'Il semblerait que vous ayez &eacute;t&eacute; redirig&eacute; vers une page inexistante !<br>
							Vous pouvez essayer de contacter l\'administrateur du site';
			$erreur = 'Erreur 404';
		
		/* retour */
			return _ErrorDisplay::createDisplay($erreur, $text);
	}
}

?>