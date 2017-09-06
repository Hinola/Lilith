<?php

class _Project extends _Sys_Files
{
	private $app;
	
	// Fonctions publiques
	/*	Constructeur
	*/	public function __construct($app, $config)
		{
			$this->app = $app;
		}
		
	/*	displayUI
		Affiche l'interface du projet
	*/	public function displayUI($ret)
		{
			/* content */
				$tab = ['_pOpt'=>['Options', '3', 'cog', ''],
						'_pFil'=>['Fichiers', '3', 'folder', ''],
						'_pLog'=>['Logs de suivi', '2', 'list', ''],
						'_pReq'=>['Requête', '2', 'send', ''],
						'_pSes'=>['Session', '2', 'history', ''],
						'_pRot'=>['Routeur', '2', 'road', ''],
						'_pUse'=>['User', '2', 'user', ''],
						'_pMod'=>['Modules', '1', 'puzzle-piece', ''],
						'_pLis'=>['Listeners', '1', 'headphones', ''],
						'_pMan'=>['Managers', '1', 'microchip', ''],
						'_pUpa'=>['UserParts', '1', 'id-card-o', ''],
						'_pAct'=>['Actions', '1', 'forward', ''],
						'_pMdl'=>['Modèles', '1', 'list-alt', '']];
				/* Options */
				$tab['_pOpt'][3] = "Options";
				/* Logs */
				$tab['_pLog'][3] = $this->displayLogs();
				/* Routeur */
				$tab['_pRot'][3] = $this->displayRouter();
				/* Routeur */
				$tab['_pLis'][3] = $this->displayListenerOP();
			
			/* windows */
				$menu = '';
				$windows = '';
				foreach($tab as $k => $t)
				{
					$menu .='<div class="_panelCase _WOpener _pc'.$t[1].'" for="'.$k.'"><i class="fa fa-'.$t[2].'"></i>'.$t[0].'</div>';
					$windows .='<div class="_panelWindow" id="'.$k.'"><div class="_windowHead">'.$t[0].'</div><div class="_windowContent">'.$t[3].'</div></div>';
				}
			
			/* On cherche le premier <head> dans les 500 premiers caractères */
				$idsplay_possible = true;
				$posHead = strpos ($ret , '</head>');
				if(!$posHead)
				{
					echo 'Balises manquantes';
					return $ret;
				}
			
			/* On cherche le dernier </body> dans les 20 derniers caractères */
				$posBody = strripos ($ret , '</body>');
				if(!$posBody)
				{
					echo 'Balises manquantes';
					return $ret;
				}
				
				$filecss = DIR_LILITH . '/common/resources/html/cssPanel.html';
				$css = str_replace("<_Lilith_dir_>", URL_LILITH, file_get_contents($filecss));
				$panel = DIR_LILITH . '/common/resources/html/panel.html';
				$ret = substr_replace($ret, file_get_contents($panel), $posBody, 0);
				$ret = substr_replace($ret, $css, $posHead, 0);
				
				$logs = "";
				$ret = str_replace(["<_menu_>", "<_windows_>", "<_Lilith_dir_>"], [$menu, $windows, URL_LILITH], $ret);
				
			return $ret;
		}
	
	// Fonctions privées
	/*	displayLogs : 
		Affiche les logs de suivit
	*/	private function displayLogs( )
		{
			$valLilith = 5;
			$display='';
			foreach($this->app->_getLogs( ) as $l)
			{
				if($l[1]==0 && $l[2][0]=='_')$l[1]=$valLilith;
				$display .= '<div class="_log _l'.$l[1].'"><span>' . $l[2] . ' : </span>' . $l[0] . '</div>';
			}
			return $display;
		}
		
	/*	displayRouter : 
		Affiche les infos du routeur
	*/	private function displayRouter( )
		{
				$cat = $this->app->getRouter()->_define();
			/* Config */
				$cat['config'] = "CONFIG";
			/* Erreurs */
				$temp = "";
				foreach($cat['errors'] as $k => $err)
				{
					$temp .= '<div class="_item _b6"><div class="_b3 _c1">Erreur ['.$k.']</div><div class="_b3 _c1">'.$err.'</div></div>';
				}
				$cat['errors'] = $temp;
			/* Paramètres */
				$cat['params'] = "PARAMS";
			/* Routes */
				$temp = "";
				foreach($cat['list'] as $r)
				{
					$tab = $r->_define();
					$temp2 = '';
					foreach($tab['actions'] as $a)
					{
						$perm = '';
						if(!is_array($a[1]))
						{
							if($a[1] != '')
							{
								$perm = '<hr>';
							}
							$a[1] = [$a[1]];
						}
						foreach($a[1] as $p){$perm .= $p.'</br>';}
						
						$temp2.= '<div class="_b6 _c1">' . $a[0] . $perm .  '</div>';
					}
					$tab['actions'] = $temp2;
					$temp2 = '';
					if($tab['params'] != null){
					foreach($tab['params'] as $k => $p)
					{
						$temp2 .= '<div class="_b4 _c1">'.$k.'</div><div class="_b2 _c1">'.$p. '</div>';
					}}
					$tab['params'] = $temp2;
					$tab['valid'] = ($tab['valid'])? "_c3" : "_c2";
					$temp .= $this->createTemplate("templateRoute", $tab);
				}
				$cat['list'] = $temp;
			/* Templating */
				return $this->createTemplate("templateRouter", $cat);
		}
		
	/*	displayListenerOP : 
		Affiche les infos de l'opérateur de listeners
	*/	private function displayListenerOP( )
		{
				$cat = $this->app->getListenersOperator()->_define();
			/* Infos */
				$cat['enabled'] = ($cat['enabled'])? "actifs": "inactifs";
			/* Config */
				$cat['config'] = "CONFIG";
			/* liste de listeners */
				$temp = "";
				foreach($cat['list'] as $r)
				{
					$refl = new ReflectionClass($r);
					$class = get_class($r);
					$temp .= '<div class="_item _panelDeploy _b6">
						<div class="_b1 _c1 _headDeploy"><i class="fa fa-bars"></i> déployer
						</div><div class="_b3 _c1">'.$class.
						'</div><div class="_b2 _c1">'.$r->getModule().
						'</div><div class="_contentDeploy">';
					$methodes = $refl->getMethods(ReflectionMethod::IS_PUBLIC);
					foreach($methodes as $m)
					{
						if($m->class == $class)
							$temp .= '<div class="_b6 _c1">'.$m->name.'</div>';
					}
					$temp .= '</div></div>';
				}
				$cat['list'] = $temp;
				return $this->createTemplate("templateListenersOp", $cat);
		}
	
	/*  createTemplate
	*/	private function createTemplate($file, $cat)
		{
			$template = file_get_contents(DIR_LILITH . '/common/resources/html/'.$file.'.html');
			$s=[];$r=[];
			foreach($cat as $k => $c)
			{
				$s[] = '<_'.$k.'_>';
				$r[] = $c;
			}
			$s[] = '<_URL_APP_>';
			$r[] = URL_APP;
			$ret = str_replace($s, $r, $template);
			return $ret;
		}
		
}



?>