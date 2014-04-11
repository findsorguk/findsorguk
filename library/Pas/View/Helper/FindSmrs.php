<?php
/**
 * A view helper for finding and displaying adjacent scheduled ancient monuments to a lat/lon pair
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_FindSmrs extends Zend_View_Helper_Abstract {

	/** Get the smrs using lat lon if not null
	 * 
	 * @param double $lat
	 * @param double $long
	 */
	public function FindSmrs($lat = NULL,$long =NULL) {
	if(isset($lat) && isset($long)){
	return $this->getMonuments($lat,$long);
	} else {
	return false;
	}
	}
	/** Get the monuments from the model using the lat/lon pair
	 * 
	 * @param double $lat
	 * @param double $long
	 */
	public function getMonuments($lat,$long){
	$mons = new ScheduledMonuments();
	$smrs = $mons->getSMRSNearby($lat,$long);
	if(count($smrs)){
	return $this->buildHtml($smrs);
	} else {
	return false;
	}
	}

	/** Build the html for display
	 * 
	 * @param array $smrs
	 */
	public function buildHtml($smrs) {
	$html = '<div id="smralert"><h4>Scheduled monument Alert</h4>';
	$html .= '<p>This find has been identified as being within 250 metres of the centre of a scheduled monument. Check gridreference!</p>';
	$html .= '<ul>';
	foreach($smrs as $s) {
	$url = $this->view->url(array(
	'module' => 'database', 
	'controller' => 'smr',
	'action' => 'record',
	'id' => $s['id']),
	NULL,
	true); 
	$html .= '<li><a href="'.$url.'" title="Scheduled monument details for ' . $s['monumentName'].'">';
	$html .= 'Scheduled monument: ' . $s['monumentName'] . ' is within ' 
	. number_format($s['distance']*1000,3) . ' metres.';
	$html .= '</a></li>';
	}
	$html .= '</ul></div>';
	return $html;
	}

}