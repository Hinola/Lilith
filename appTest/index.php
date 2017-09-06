<?php

$dir = str_replace('\\', '/', __dir__);

$path = str_replace('\\', '/', substr((strstr($dir, 'www')),3));

if(isset($_POST['urlProj']))
	$ret = createFile($_POST['urlProj'], $_POST['dirProjet']);
else
	$ret = showMessage($dir, $path);

echo '<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>Generation access</title>
		<link rel="icon" href="/favicon.ico" />
		<meta  charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<style type="text/css">
			h1{		display:block;text-align:center;color:#1F2F39;font-family:sans-serif;font-size:4em;margin-bottom:20px}
			h2{		display:block;text-align:center;color:#507992;font-family:sans-serif;font-size:2em;margin:0px;
					border-bottom:solid 1px #507992;margin-bottom:10px}
			a{		color:#FFFFFF;}
			.case{	position:relative;width:800px;margin:auto;min-height:150px;color:#FFFFFF;text-align:center;
					border-radius:5px;padding:20px;box-shadow: 0px 10px 10px -5px black;background-color:#1F2F39;margin-bottom:20px}
			input{width:500px;padding:10px;margin:10px;background-color:#507992;border-color:#507992;}
		</style>
	</head>
<html>
	<body style="background-color:#507992">
		<h1>génération des fichiers .htaccess</h1>
		<div class="case">'.$ret.'</div>
		<div class="case"><h2>Pourquoi cette page ?</h2>
		Il semblerait que vous n\'êtes pas redirigé vers l\'accueil de votre site.<br>
		Probablement par ce que celui-ci vient d\'être installé.<br>
		La génération de fichier ".htaccess" redirigera correctement l\'utilisateur vers le fichier php necessaire.</div>
	</body>
</html>';

function showMessage($dir, $path)
{
		return '<h2>Informations</h2>
		<form method="post">
		<label for="dirProjet">Emplacement du projet :</label><br>
		<input type="text" name="dirProjet" id="dirProjet" value="' . $dir . '"><br>
		<label for="urlProj">Url du projet :</label><br>
		<input type="text" name="urlProj" id="urlProj" value="' . $path . '"><br>
		<input type="submit" value="générer les fichiers .access" style="cursor:pointer">
		</form>';
}

function createFile($url, $dir)
{
	$content =
'RewriteEngine On
RewriteBase '.$url.'/system/
RewriteRule ^(.+)_*(\d+)*\.(js|css|png|jpg|gif|ogg|mp3|woff2|woff|ttf)$ $1.$3 [L]
RewriteRule ^ App.php [QSA,L]';
	$hta1 = fopen($dir . '/.htaccess', 'w');
	fwrite($hta1, $content);
	$hta2 = fopen($dir . '/system/.htaccess', 'w');
	fwrite($hta2, $content);
	
	return 'Fichiers créés.<br><a href="'.$url.'">Cliquez ici pour actualiser la page.</a>';
}



?>