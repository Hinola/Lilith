<?php

class _Sys_Errors extends _Lilith
{
	// Attributs
	
		/*	errorsCount : 
			Nombre d'erreurs relevées par la commande _error
		*/	private static $errorsCount = 0;
	
		/*	errorsList : 
			Liste des erreurs
		*/	private static $errorsList = [];
	
		/*	displayErrors : 
			Affichage des erreurs
		*/	private static $displayErrors = true;
		
		public function __call($name, $args)
		{
			return $this->_setError('The function ['.$name.'] does not exist', 1);
		}
	
	// Commandes
		
		/*	_setError : 
			Enregistre une erreur. Si le mode développeur est activé, l'erreur est affichée
			Le fichier, la classe et la ligne sont données à l'utilisateur.
			$d : description de l'erreur
			$l : niveau de declanchement de l'erreur
		*/	protected function _setError($d, $l=0){self::$errorsCount += 1;$this->_addLog($d,2);$id='_err:'.self::$errorsCount;return self::$errorsList[] = new _ErrorObject($id, $d, debug_backtrace()[$l], (DEV_MOD && self::$displayErrors));}
	
		/*	_setCriticalError : 
			Utilise les méthodes _setError et interrupt pour interrompre le script
		*/	protected function _setCriticalError($d, $l=0){$this->_setError($d, $l+1);$this->interrupt(_ERRSTR::CRIT());}
	
		/*	_getNbErrors : 
			Retourne le nombre d'erreurs
		*/	public static function _getNbErrors(){return self::$errorsCount;}

		/*	_valid : 
			Retourne false si le test est une erreur
		*/	protected function _valid($r){return !($r instanceof _ErrorObject);}
	
		/*	_critical : 
			Permet de rendre les erreurs critiques. Si la valeur de $ret est une erreur, le script s'arrête.
			$ret : le retour de la fonction testée
			$d : description de l'erreur
		*/	protected function _critical($ret, $d = null){if($ret instanceof _ErrorObject)$this->interrupt($d);else return $ret;}
		
	// Fonctions privées
	
		/*	interrupt : 
			Interrompt le scripte brutalement
			$d : description de l'interruption
		*/	private function interrupt($d = null)
			{
				if(DEV_MOD)
				{
					$add = ($d != null)? $d : 'Pas d\'information';
					echo '<div style="position:relative;box-sizing:border-box;width:800px;margin:auto;color:#4A1717;border:solid 1px #4A1717;
										border-radius:5px;box-shadow: 2px 2px 2px #000000;">
							<div style="background-color:#4A1717;color:#ffffff;padding:5px;">
								<b>Interruption du script</b>
							</div><div style="padding:5px;background-color:black;color:white">'
								.$add.'
							</div></div>';
				}
				exit();
			}
		
}

class _ErrorObject extends _Sys_Errors
{
	// Attributs
	
		/*	description : 
			Description et code de l'erreur
		*/	private $description;
		
		/*	debug : 
			Informations annexes de debug
		*/	private $debug;
		
		/*	ID : 
			Numero de l'erreur
		*/	private $ID;
		
	// Méthodes magiques
	
		/*	constructeur
			Affiche l'erreur à l'écran si le mode développeur est actif
		*/	public function __construct($id, $d, $debug, $dm)
			{
				$this->ID = $id;
				$this->description = $d;
				$this->debug = $debug;
				if($dm)$this->displayError();
			}
	
		/*	__call : 
			En cas d'erreur au chargement d'une classe, interrompt le script à l'appel d'une methode
		*/	public function __call($n, $a)
			{
				$this->_critical($this, '<b>Erreur ['.$this->ID.'] critique<br>Appel d\'une fonction sur une erreur</b><br>Rappel :<br><i>'.$this->description.
				'<br>Fichier [' .$this->debug['file'].']<br> Ligne [' .$this->debug['line'].']</i>');
			}
					
	// Fonctions publiques
	
		/*	_Error_Display : 
			Affiche l'erreur dès l'apparition de celle-ci
		*/	public function displayError()
			{
				echo '<div style="position:relative;box-sizing:border-box;width:800px;margin:auto;color:#4A1717;border:solid 1px #4A1717;
								border-radius:5px;box-shadow:0px 7px 2px -6px #000000;background-color:#cc5555;margin-bottom:20px;">
					<div style="background-color:#4A1717;color:#ffffff;padding:5px;"><b>['.
					$this->ID.'] '.$this->description.
					'</b></div><div style="padding:5px;">
					Fichier [' .$this->debug['file'].
					']<br> Ligne [' .$this->debug['line'].
					']</div></div>';
			}
			
		/*	__toString : 
			En cas de convertion en string
		*/	public function __toString()
			{
				return '<span style="background-color:#cc5555;color:#4A1717">Error ['.$this->ID.']</span>';
			}
}
?>