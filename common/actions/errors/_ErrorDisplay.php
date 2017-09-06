<?php

class _ErrorDisplay extends _Sys_Errors
{
	public static function createDisplay($erreur, $text)
	{
		return '<html>
				<head>
					<title>'.$erreur.'</title>
					<meta  charset="utf-8">
					<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
					<style type="text/css">
						h1{		display:block;text-align:center;color:#1F2F39;font-family:sans-serif;font-size:4em;margin-bottom:20px}
						a{		color:#FFFFFF;}
						#case{	position:relative;width:800px;margin:auto;min-height:150px;color:#4A1717;
								border-radius:5px;padding:5px 5px 50px 150px;box-shadow: 0px 10px 10px -5px black;background-color:#cc5555;}
						#picL{	display:block;position:absolute;bottom:0px;left:0px;width:142px;height:200px;
								background-image:url(\''.URL_LILITH.'/res/pic/oops.png\')}
						#link{	position:absolute;display:block;bottom:10px;text-shadow:none}
						#link>a{color:white;padding:5px 20px 5px 20px;text-align:center;background-color:#4A1717;
								border-radius:5px;text-decoration:none;}
						#foot{	position:relative;width:800px;margin:auto;margin-top:20px;color:#1F2F39;}
					</style>
				</head>
				<body style="background-color:#507992">
					<h1>'.$erreur.'</h1>
					<div id="case">
						<h2>Oops !</h2>
						'.$text.'
						<div id="link">
							<a href="'.URL_APP.'">Retour &agrave; l\'accueil</a>
							<a href="'.URL_APP.'">Envois du rapport</a>
						</div>
						<div id="picL"></div>
					</div>
					<div id="foot">
						Framework Lilith v'.LILITH_VERSION.'<br>
						<a href="http://hindrasil.fr/lilith">Visiter le site</a>
					</div>
				</body></html>';
	}
}