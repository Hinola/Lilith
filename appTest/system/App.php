<?php

// DEBUG
// affiche le déroulement en cas de problème lors du déploiement sur le serveur
$debug = false;

// CONFIG

/*	$path_Lilith
	Chemin relatif au dossier racine de l'application vers le dossier racine du framework
	../lilith
*/	$path_Lilith	= '../';
	
/*	$url_App
	chemin de la ressource demandée (sans le nom de domaine/localhost)
	/app
*/	$url_App		= '/Lilith/appTest';

/*	$dev_mod
	mode développeur, affiche les erreurs et les logs de suivit
	false
*/	$dev_Mod		= true;
	
/*	$dir_App
	chemin absolu de l'application
	/app
*/	$dir_App		= substr(__dir__, 0, strlen(__dir__)-7);

if($debug)echo 'Define config : ok<br>';





// DO NOT TOUCH

define	('URL_APP',	$url_App);
define	('DIR_APP', $dir_App);
define	('DEV_MOD', $dev_Mod);
if($debug)echo 'Define constantes : ok<br>';

if($debug) echo(file_exists('../' . $path_Lilith . 'Lilith.php'))? "Fichier Lilith.php existant": "Fichier Lilith.php introuvable";

require_once('../' . $path_Lilith . 'Lilith.php');
if($debug)echo 'Require Lilith : ok<br>';
require_once($dir_App.'/system/Main_Command.php');
if($debug)echo 'Require Main command : ok<br>';

$com =	new Main_Command;
if($debug)echo 'Initialize Main command : ok<br>';
$app	=	new _Application();
if($debug)echo 'Initialize Application : ok<br>';
$app	->	run($com);

?>