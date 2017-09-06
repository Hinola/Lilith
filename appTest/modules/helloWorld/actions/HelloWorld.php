<?php

class HelloWorld extends _Action
{
	public function load()
	{
		$this->_addLog('Load Action', 4);
	}
	
	public function execute()
	{
		$this->_addLog('Execute Action', 4);
	}
	
	public function getReturn()
	{
		$this->_addLog('Return Action', 4);
		$display = '
		<html lang="fr">
		<head>
			<title>HelloWorld</title>
			<link rel="icon" href="/favicon.ico" />
			<meta  charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
			<style type="text/css">
				body, html {width:100%;height:100%;margin:0px;}
				body{background-color:#507992;background-image:url(/v8/Lilith/res/pic/lilithFull.png);background-repeat:no-repeat;background-position:center;background-size:contain}
				#bulle{background-color:#1F2F39;width:300px;height:100px;line-height:98px;text-align:center;border-radius:3px;
					position:absolute;left:50%;top:50%;margin:-50px -150px;font-size:20px;box-sizing:border-box;color:#FFFFFF;box-shadow:0px 7px 2px -6px #000000;}
				#bottom{position:absolute;bottom:0px;width:100%;background-color:#1F2F39;height:32px;line-height:30px;
					color:white;padding:0px 50px;box-sizing:border-box;}
				#bottom>a
	{color:white;margin-left:50px;}
			</style>
		</head>
		<body style="background-color:#507992">
			<div id="bulle">Hello world !</div>
			<div id="bottom">Lilith:Framework <a href="http://hindrasil.fr/v8/appTest/doc">Documentation</a></div>
		</body>
		</html>';
		return $display;
	}
}

?>