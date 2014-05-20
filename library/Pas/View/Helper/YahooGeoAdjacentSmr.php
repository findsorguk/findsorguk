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
class Pas_View_Helper_YahooGeoAdjacentSmr extends Zend_View_Helper_Abstract 
{

     /** The woeid to query
     * @access protected
     * @var int
     */
    protected $_woeid;
    
    /** Get the woeid to query
     * @access public
     * @return int
     */
    public function getWoeid() {
        return $this->_woeid;
    }
    
    /** Set the woeid to query
     * @access public
     * @return int
     */
    public function setWoeid( int $woeid) {
        $this->_woeid = $woeid;
        return $this;
    }

    /** Get the places from the model
     * @access public
     * @return array
     */
    public function getPlaces() {
        $adjacent = new GeoPlaces();
	return $adjacent->getAdjacent($this->getWoeid());
    }
    
    /** The function to return
     * @access public
     * @return \Pas_View_Helper_YahooGeoAdjacent
     */
    public function yahooGeoAdjacentSmr() {
        return $this;
    }
    
     /** Build the html
     * @access public
     * @return string
     */
    public function buildHtml(){
        $html = '';
        $places = $this->getPlaces();
        if(count($places)){
            $html .= '<div id="adjacentsmr">';
            $html .= '<h3>Adjacent places</h3>';
            $html .= '<ul>';
            foreach($places as $p ) {
                $url = $this->view->url(array(
                    'module' => 'database',
                    'controller' => 'smr',
                    'action' => 'bywoeid',
                    'woeid' => $p['WOE_ID']),
                        null, true);
	
                $html .= '<li><a href="';
                $html .=  $url;
                $html .= '" title="Find all objects associated with this WOEID">';
                $html .= $p['Name'];
                $html .= '</a></li>';
            }
            $html .= '</ul>';
            $html .= '</div>';
	}
        return $html;
    }
}