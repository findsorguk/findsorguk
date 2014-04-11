<?php
class Pas_Analytics_Timespan {
	
	protected $_timespan;
	
	public function __construct($timespan){
		$this->_timespan = (string) $timespan;
	}
	public function getDates(  ){
		$d = new Pas_Analytics_Dates();
		
		switch($this->_timespan){
			case 'today':
				$dates = $d->today();
				break;
			case 'yesterday':
				$dates = $d->yesterday();
				break;
			case 'thisweek':
				$dates = $d->thisWeek();
				break;
			case 'lastweek':
				$dates = $d->lastweek();
				break;
			case 'thismonth':
				$dates = $d->thisMonth();
				break;
			case 'lastmonth':
				$dates = $d->lastMonth();
				break;
			case 'thisyear':
				$dates = $d->thisYear();
				break;
			case 'lastyear':
				$dates = $d->lastYear();
				break;
			default:
				$dates = $d->thisMonth();
				break;
		}
		return $dates;
	}
}
