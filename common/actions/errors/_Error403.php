<?php

class _Error403 extends _Action
{
	public function load()
	{
		$this->_toggleListeners(false);
		$this->_requireFile(DIR_LILITH . '/common/actions/errors/_ErrorDisplay.php');
	}
	
	public function getReturn()
	{
		/* Logs */
			$this->_addLog('403', 2);
		
		/* header */
			header('HTTP/1.0 403 Forbidden');
		
		/* textes */
			$text = 'TEXT';
			$erreur = 'Erreur 403';
			
		/* retour */
			return _ErrorDisplay::createDisplay($erreur, $text);
	}
}

?>