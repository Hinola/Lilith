<?php

class _Listener extends _App_Interface
{
	private $module;
	
	public function __construct($mod)
	{
		$this->module = $mod;
	}
	public function getModule(){return $this->module;}
	
	public function initialize(){}
	public function beforeExecute(){}
	public function beforeSend($ret){return $ret;}
	
	public function beforeExecute_ForMod(){}
	public function beforeSend_ForMod($ret){return $ret;}
}

?>