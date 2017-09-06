<?php

class _User extends _Model
{
	// Attributes
	
	/* Misc */
		private $lastSave;
		private $dateRegister;
		private $ranks;
	
	// constructor
	
		public function __construct()
		{
			
		}
		
	// public functions
	
		/*	isRanked : 
			Retourne true si l'utilisateur a les rangs necessaires
			$rank : rang(s) à vérifier
		*/	public function isRanked($rank)
		{
			/* Si le paramètre ne comprte aucun rang */
			if($rank == null || $rank == '')
				return true;
			
			/* Si le paramètre est un tableau de rangs * /
			if(is_array($rank))
			{
				foreach($rank as $r)
				{
					if(!$this->isRanked($r))
						return false;
				}
				return true;
			}
			
			/* Si le paramètre est un rang * /
			else
			{
				$ranked = false;
				if(isset($this->ranks[$rank]))
					$ranked = $this->ranks[$rank];
				return $ranked;
			}*/
			
			if(!is_array($rank))
			{
				$rank = [$rank];
			}
			
			foreach($rank as $r)
			{
				$ranked = false;
				if(isset($this->ranks[$r]))
					$ranked = $this->ranks[$r];
				if(!$ranked) return false;
			}
			return true;
		}
		
		public function setRank($rank, $val)
		{
			$this->ranks[$rank] = $val;
			return $this;
		}
		
		public static function load($id)
		{
			$user = new _User();
			$user->id = $id;
			$user->lastSave = '';
			$user->ranks = ['CONN' => true];
			$user->dateRegister = '';
			$user->_setState(0);
			return $user;
		}
		
		public function save()
		{
			/* verification des fichiers */
			$dir = DIR_APP . '/data/';
		}
}

?>