<?php

namespace Panda;

class SubStats
{
		public function getCurrentSubs()
		{
			$db = \XF::db();
			$results = $db->fetchAll('SELECT user_upgrade_id FROM xf_user_upgrade_active');
			
			return $results;
		}

		public function getCurrentSubValues()
		{
			$db = \XF::db();
			$subResponseData = $db->fetchAll('SELECT o.cost_amount FROM xf_user_upgrade o INNER JOIN xf_user_upgrade_active i on o.user_upgrade_id = i.user_upgrade_id');
			
			return $subResponseData;
		}

		public function calculateValue()
		{
			$subData = new SubStats();
			$totalRev = 0;
			$currentActiveSubs = $subData->getCurrentSubs();
			$subscriberTierCosts = $subData->getCurrentSubValues();

			foreach($subscriberTierCosts as $subs => $subArray) {
				foreach($subArray as $entity){
					$totalRev += intval($entity);
				}
			}
			
			return $totalRev;
		}

		public static function getSubData() 
		{
			$subValue = new SubStats();
			$currentSubsRevenue = $subValue->calculateValue();
			
			return $currentSubsRevenue;
		}

		public static function getSubCount() 
		{
			$subCount = new SubStats();
			$currentActiveSubs = count($subCount->getCurrentSubs());
			
			return $currentActiveSubs;
		}		
	}
?>
