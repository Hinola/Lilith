<?php

class _SelectReader extends _Sys_Files
{
	// Attributs
		/*	app : 
			Application en cour
		*/	private $app;
	
	// Constructor
		/*	app
			Reçois en paramètre l'application en cour
		*/	public function __construct($app)
			{
				$this->app = $app;
			}
	
		/*	readSelect
			Lit un selecteur et retourne l'objet correspondant
			$select : selecteur à lire
			retourne le statut : 	[0] tout c'est bien passé
									[1] Selecteur invalide
		*/	public function readSelect($select)
			{
				/* lecture et séparation du selecteur */
					$a = explode(':', $select);
					if(count($a)<3)
					{
						$this->_setError('Se01: le selecteur ['.$select.'] n\'est pas un selecteur valide', 1);
						$this->_setState(1);
						return false;
					}
				
				/* vérifie si la ressource vient de Lilith ou de l'app */
					$from_lilith = false;
					if($a[0]{0} == '_')
					{
						$from_lilith = true;
						$a[0] = substr($a[0],1);
					}
				
				/* action en fonction de la ressource */
					$ret = null;
					switch($a[0])
					{
						case 'action':
						case 'listener':
						case 'model':
						case 'manager':
							if(!$from_lilith)
							{
								if($a[1] == '') $d = DIR_APP;
								else $d = DIR_APP . '/modules/' . $a[1];
								$d .= '/'.$a[0].'s/' . $a[2] . '.php';
							}
							else
							{
								if($a[1] != '')$a[1] .= '/';
								$d = DIR_LILITH . '/common/'.$a[0].'s/'.$a[1].$a[2].'.php';
							}
							$test = $this->_requireFile($d);
							if(!$this->_valid($test)) return $test;
							else $ret =  new $a[2]($a[1]);
							break;
						case 'html':
						case 'css':
							if(!$from_lilith)
							{
								if($a[1] == '') $d = DIR_APP;
								else $d = DIR_APP . '/modules/' . $a[1];
								$d .= '/resources/'.$a[0].'/' . $a[2] . '.'.$a[0];
							}
							else
							{
								if($a[1] != '')$a[1] .= '/';
								$d = DIR_LILITH . '/common/resources/'.$a[0].'/'.$a[1].$a[2].'.'.$a[0];
							}
							$ret = $this->_openFile($d);
							break;
						case 'pic':
							if(!$from_lilith)
							{
								if($a[1] == '') $d = URL_APP;
								else $d = URL_APP . '/modules/' . $a[1];
								$d .= '/resources/'.$a[0].'/' . $a[2];
							}
							else
							{
								if($a[1] != '')$a[1] .= '/';
								$d = URL_LILITH . '/common/resources/'.$a[0].'/'.$a[1].$a[2];
							}
							$ret = $d;
							break;
					}
				if($ret != null)
					return $ret;
				else
					return $this->_setError('Se03: le selecteur ['.$select.'] ne correspond à aucune ressource');
			}
}

?>