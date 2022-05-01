<?php

namespace Panda\SubStats;

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

		public function getTotalCostValue() 
		{
			$db = \XF::db();
			$costOverviewContent = $db->fetchOne('SELECT template FROM xf_template WHERE title="_page_node.29"');
			$htmlList = explode(PHP_EOL, $costOverviewContent);
			foreach($htmlList as $line) 
			{
				if (strpos(trim($line), '<b id="total-cost">') === 0)
				{
					$dollarAmount = explode(":", trim($line));
					$parsedString = $this->get_string_between(trim($dollarAmount[1]), '$', '</b>');
					return $parsedString;
				}
			}
		}

		public function get_string_between($string, $start, $end){
			$string = ' ' . $string;
			$ini = strpos($string, $start);
			if ($ini == 0) return '';
			$ini += strlen($start);
			$len = strpos($string, $end, $ini) - $ini;
			return substr($string, $ini, $len);
		}

		
		public static function getSubData() 
		{
			$subValue = new SubStats();
			$currentSubsRevenue = $subValue->calculateValue();

			$totalCostValue = $subValue->getTotalCostValue();
			
			$color = "";
			if ($currentSubsRevenue < $totalCostValue) 
			{
				$color = "color: #ff0000;";
			}
			else 
			{
				$color = "color: #00ff00;";
			}

			$formattedHtmlStart = '<span style="'.$color.'">$';
			$formattedHtmlEnd = '</span>';
			$formattedHtmlFinal = "$formattedHtmlStart$currentSubsRevenue$formattedHtmlEnd";
			
			return $formattedHtmlFinal;
		}

		public static function getSubCount() 
		{
			$subCount = new SubStats();
			$currentActiveSubs = count($subCount->getCurrentSubs());
			
			return $currentActiveSubs;
		}
		
		public static function getTotalCost() 
		{
			$totalCost = new SubStats();
			$totalCostValue = $totalCost->getTotalCostValue();
			
			return $totalCostValue;
		}
}
?>
