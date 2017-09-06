<?php

class _Application extends _Sys_Files
{
	// Attributs
		/* Config */
			private $config;
		/* opérateurs */
			private $managersOperator;
			private $listenersOperator;
			private $modulesOperator;
			private $userPartsOperator;
		/* Composants */
			private $selectReader;
			private $router;
			private $request;
			private $session;
			private $user;
			private $action;
	
	// accesseurs
		
		public function getRouter(){return $this->router;}
		public function getSelectReader(){return $this->selectReader;}
		public function getListenersOperator(){return $this->listenersOperator;}
		public function getManagersOperator(){return $this->managersOperator;}
		public function getModulesOperator(){return $this->modulesOperator;}
		public function getUserPartsOperator(){return $this->userPartsOperator;}
		
		public function getModule(){return $this->action->_getModule();}
		
	// déroulement de l'application
	
		/*	run
			méthode principale de l'application, execute son déroulement
			crée les opérateurs, charges les composants
			execute l'action et affiche le résultat
		*/	public function run( $main_Command )
			{
				/* Initialize Lilith */
					$this->_addLog('Config App', 1);
					$this->_addLog('Dir Lilith : ['.DIR_LILITH.']');
					$this->_addLog('Dir App : ['.DIR_APP.']');
					$this->_addLog('Url App : ['.URL_APP.']');
					$this->_addLog('Dev mod : ['.((DEV_MOD)?'enabled':'disabled').']');
					
				/* save App for interface */
					_App_Interface::_saveApp($this);
				
				/* load config */
					$this->loadConfig();
					
				/* Create select reader */
					$this->selectReader = new _SelectReader($this);
				
				/* Create project for dev mod */
					if(DEV_MOD)$proj = new _Project($this, $this->config);
				
				/* Initialize session */
					$this->session = new _Session((isset($this->config['Session']))?$this->config['Session']:[]);
				
				/* Initialize request */
					$this->request = new _Request((isset($this->config['Request']))?$this->config['Request']:[]);
				
				/* Initialize router */
					$this->router = new _Router((isset($this->config['Router']))?$this->config['Router']:[]);
				
				/* Initialise user */
					$this->_addLog('Adapter l\'user', 3);
					//$this->user = _User::load($this->session->getUserID());
					$this->user = _User::load(0);
				
				/* Initialize managersOperator */
					$this->managersOperator =  new _ManagersOperator();
				
				/* Initialize listenersOperator */
					$this->listenersOperator =  new _ListenersOperator((isset($this->config['Listeners']))?$this->config['Listeners']:[]);
				
				/* Initialize modulesOperator */
					$this->modulesOperator =  new _UserPartsOperator();
				
				/* Initialize userPartsOperator */
					$this->userPartsOperator =  new _UserPartsOperator();
					
				/* Run main command */
					$ret = $main_Command->run();
				
				/* Listeners before load actions */
					$this->listenersOperator->initialize($this);
				
				/* dispatch */
					$this->_addLog('Router dispatch url', 3);
					//$selecteur = 'action:main:Accueil';
					$selecteur = $this->router->getActionForUrl($this->request->getRequestUrl(), $this->user);
				
				/* Chargement de l'action */
					$this->action = _Action::loadAction($selecteur);
				
				/* execute action */
					$this->executeAction();
				
				/* Send actions */
					$ret = $this->action->_getReturn();
				
				/* Listeners after sending actions */
					$ret = $this->listenersOperator->beforeSend($this, $ret);
				
				/* display */
					if(!DEV_MOD) echo $ret;
					else echo $proj->displayUI($ret);
			}
			
			
	// fonctions privées
	
		/*	executeAction
			Execute l'action définie par le routeur
			Vérifie a chaque itération si l'action a été redirigée
			Si oui, recommence l'execution
		*/	private function executeAction()
			{
				$met = $this->request->getRequestMethod();
				$listMethods = ['load', $met, 'execute'];
				$index = 0;
				$listenerdone = false;
				while($index < 3){
					$m = $listMethods[$index];
					$r = $this->action->$m();
					$index += 1;
					if($this->action->_isRedirect())
					{
						$this->_addLog('redirect to ['.$r.']');
						_Action::_clearParams();
						$this->action = _Action::loadAction($r);
						$index = 0;
					}
					if(!$listenerdone)
					{
						$this->listenersOperator->beforeExecute($this);
						$listenerdone = true;
					}
				}
			}
			
			private function loadConfig()
			{
					$configFile = $this->_openFile(DIR_APP . '/config/config.ini');
					if(!$this->_valid($configFile)) $this->config = [];
					else $this->config = $configFile->parseIni(true);
			}
			
}



?>