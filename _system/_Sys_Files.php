<?php

class _Sys_Files extends _Sys_Errors
{
	// Commandes
		
		/*	_openFile : 
			Crée un objet type _File pour interagir avec un fichier
			retourne une erreur si le fichier est inexistant
			$file : chemin du fichier à ouvrir
			$force : force l'ouverture du fichier même s'il n'existe pas
		*/	protected function _openFile($file, $force = false)
			{
				$exist = file_exists($file);
				if( $exist || $force )
					return new _File( $file, $exist );
				else
					return $this->_setError( 'Fl01: Le fichier ['.$file.'] est introuvable', 1 );
			}
		
		/*	_requireFile :
			utilise la commande require_once après avoir testé si le fichier existe
			$file : chemin absolu du fichier demandé
		*/	protected function _requireFile($file)
			{
				if(file_exists($file))
					require_once($file);
				else
					return $this->_setError('Fl01: Le fichier ['.$file.'] est introuvable', 1);
			}
}

class _File extends _Sys_Errors
{
	// Attributs
	
		/*	path : 
			Chemin du fichier
		*/	protected $path;
	
		/*	exist : 
			true si le fichier existe, false sinon
		*/	protected $exist;
	
		/*	flux : 
			Flux créé avec la commande fopen
		*/	protected $flux;
	
	// constructor
	
		public function __construct($p, $e)
		{
			$this->path = $p;
			$this->exist = $e;
		}
	
	// fonctions publiques
	
		/*	getContent : 
			Retourne le contenu du fichier s'il existe, ou une chaine vide dans le cas contraire
		*/	public function getContent()
			{
				if($this->exist)
					return file_get_contents($this->path);
				else
					return '';
			}
		
		/*	parseIni : 
			Retourne un array du fichier parsé
			$sect : mode secteur (tableau multidimensionnel avec les secteurs)
			$scanner : mode de scanner (voir manuel php)
		*/	public function parseIni($sect = false, $scanner = INI_SCANNER_NORMAL)
			{
				return parse_ini_file($this->path, $sect, $scanner);
			}
		
		/*	parseJson : 
			Retourne un array du fichier parsé
		*/	public function parseJson()
			{
				return json_decode($this->getContent(), true);
			}
		
		/*	parseLth : 
			Retourne un array du fichier parsé
		*/	public function parseLth()
			{
				$array = [];
				$current = [];
				$this->open('r');
				$line = fgets($this->flux);
				while($line)
				{
					/* Si la ligne est  */
					$line = fgets($this->flux);
				}
				$this->close();
				return parse_lth($this->getContent());
			}
			
	// fonctions sur les flux
			
		/* open :
			Ouvre un fichier et crée un flux via la fonction fopen
			$mode : mode d'ouverture (lecture : écriture + oprions, voir manuel php)
			$use_include_path : paramètre de fopen, voir manuel php
			$context : voir manuel php
		*/	public function open($mode = 'r+', $use_include_path = false , $context = null)
			{
				$this->flux = fopen($this->path, $mode, $use_include_path, $context);
			}
			
		/* close
		*/	public function close()
			{
				fclose($this->flux);
			}
			
		/* close
		*/	public function readLine()
			{
				return fgets($this->flux);
			}
}

?>