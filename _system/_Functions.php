<?php

function random_String($l)
{
	$string = "";
	$chaine = "abcdefghijklmnpqrstuvwxy0123456789";
	srand((double)microtime()*1000000);
	for($i=0; $i<$l; $i++)
	{
		$string .= $chaine[rand()%strlen($chaine)];
	}
	return $string;
}

?>