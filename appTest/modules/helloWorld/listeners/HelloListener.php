<?php

class HelloListener extends _Listener
{
	public function initialize()
	{
		$this->_addRoute('helloRoute', '/hello', 'action:helloWorld:HelloWorld');
	}
	
	public function beforeExecute()
	{
		
	}
}

?>