<?php

class Accueil extends _Action
{
	public function getReturn()
	{
		/*	TEST
			Ouverture fichier html pour affichage du panel projet */
			$this->_addLog('Chargement fichier HTML', 4);
			$f = DIR_APP . '/common/resources/html/accueil.html';
			return file_get_contents($f);
		/*	FIN DU TEST */
	}
}

?>