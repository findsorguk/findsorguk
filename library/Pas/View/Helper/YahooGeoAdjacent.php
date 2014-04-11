<?php
/** A view helper that queries the local geo planet database and returns html of adjacent places
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @version 1
 * @license GNU
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @since 30 September 2011
 * @uses Zend_View_Helper_Url
 */ 
class Pas_View_Helper_YahooGeoAdjacent 
	extends Zend_View_Helper_Abstract {
	
	/** Call database model and query for adjacencies
	 * @param int $woeid The WOEID to query
	 */
	public function YahooGeoAdjacent($woeid) {
		
	$adjacent = new GeoPlaces();
	$places = $adjacent->getAdjacent($woeid);
	if(count($places) && !is_null($places[0]['Name'])){
	$html = '<h3>Adjacent places</h3>';
	$html .= '<ul>';
	foreach($places as $p ) {
	$url = $this->view->url(array(
            'module' => 'database',
            'controller' => 'search',
            'action' => 'results',
            'woeid' => $p['WOE_ID']),
                null, true);
	$html .= '<li><a href="' . $url . '" title="Find all objects associated 
            with this WOEID">';
	$html .= $p['Name'];
	$html .= '</a></li>';
	}
	$html .= '</ul>';
	
	return $html;
	}
	}
	
}