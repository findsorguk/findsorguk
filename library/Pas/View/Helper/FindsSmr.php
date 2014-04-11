<?php
class Pas_View_Helper_FindsSmr extends Zend_View_Helper_Abstract
{

public function getData($lat,$lon,$distance)
	{
	$smr = new ScheduledMonuments();
	$smrs = $smr->getSMRSNearbyFinds($lat,$lon,$distance = 0.25);
	if(count($smrs)) {
	return $this->buildHtml($smrs);
	} else {
	return false;
	}
	
	}
public function FindsSmr($lat,$lon,$distance)
	{
	return $this->getData($lat,$lon,$distance);
	
	}

public function buildHtml($smrs)
	{
	$html = '';
	$html .= '<h3>Finds within 250 metres of centre of SMR</h3><ul>';
	foreach($smrs as $s){
	$html .= '<li><a href="';
	$html .= $this->view->url(array('module' => 'database','controller' => 'artefacts','action' => 'record','id' => $s['id']),NULL, true);
	$html .= '" title="View details for '.$s['old_findID'].'">';
	$html .= $s['old_findID'];
	$html .= '</a>';
	$html .= '-  a '.$s['objecttype'].' from '.$s['county'].' at a distance of '.number_format(($s['distance']*1000),3).' metres.';
	$html .= '</li>';
	}
	$html .= '</ul>';
	echo $html;
	}
}