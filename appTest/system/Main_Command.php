<?php

class Main_Command extends _App_Interface
{
	public function run()
	{
		$this->_addLog('Running main command', 1);
		$this->_addLog('TODO',1);
		$this->_addLog('Permission router/user par IP',3);
		
		$this->_addListener('listener:helloWorld:HelloListener');
		
		$this->_addRoute('mainAccueil', '/', 'action:main:Accueil');
		$this->_addRoute('route 1', '/truc', 'action:tests:IndexTests', ['p1'=>5]);
		$this->_addRoute('route 1', '/truc', [], ['p1'=>5]);
		$this->_addRoute('test_index', '/tests', 'action:tests:IndexTests');
		$this->_addRoute('test_actions', '/tests/actions', 'action:tests:TestActions');
		$this->_addRoute('test_models', '/tests/models', 'action:tests:TestModels');
		$this->_addRoute('test_user', '/tests/user', 'action:tests:TestUser');
		$this->_addRoute('permiss', '/tests/user', [['action:tests:TestUser','rank:ADMIN'],'action:tests:TestModels']);
		
	}
}

?>