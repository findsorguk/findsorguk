<?php
/**
 * A view helper for finding and displaying adjacent scheduled ancient monuments
 *  to a lat/lon pair
 *
 * Example use:
 *
 * <code>
 * <?php
 * echo $this->findSmrs()->setLat($lat)->setLon($lon);
 * ?>
 * </code>
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @todo move data call to solr
 * @todo build in partial
 */
class Pas_View_Helper_FindSmrs extends Zend_View_Helper_Abstract
{

    /** The latitude
     * @access protected
     * @var double
     */
    protected $_lat;

    /** The longitude
     * @access protected
     * @var double
     */
    protected $_lon;

    /** Get the latitude
     * @access public
     * @return double
     */
    public function getLat() {
        return $this->_lat;
    }

    /** Get the longitude
     * @access public
     * @return double
     */
    public function getLon() {
        return $this->_lon;
    }

    /** Set the latitude
     * @access public
     * @param double $lat
     * @return \Pas_View_Helper_FindSmrs
     */
    public function setLat($lat) {
        $this->_lat = $lat;
        return $this;
    }

    /** Set the longitude
     * @access public
     * @param double $lon
     * @return \Pas_View_Helper_FindSmrs
     */
    public function setLon($lon) {
        $this->_lon = $lon;
        return $this;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_FindSmrs
     */
    public function findSmrs() {
        return $this;
    }

    /** To string
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->getMonuments();;
    }

    /** Get the data
     * @access public
     * @return object
     * @todo move data call to solr
     */
    public function getMonuments() {
        if($this->getLat() && $this->getLon()) {
            $mons = new ScheduledMonuments();
            $smrs = $mons->getSMRSNearby($lat,$long);
            return $this->buildHtml($smrs);
        }
    }

    /** Build the html for display
     * @access public
     * @param array $smrs
     * @return string The html to render
     * @todo Send html output to a partial
     */
    public function buildHtml( array $smrs) {
        $html = '';
        if(is_array($smrs)) {
            $html .= '<div id="smralert"><h4>Scheduled monument Alert</h4>';
            $html .= '<p>This find has been identified as being within 250;';
            $html .= ' metres of the centre of a scheduled monument.';
            $html .= 'Check gridreference!</p>';
            $html .= '<ul>';
            foreach ($smrs as $s) {
                $url = $this->view->url(array(
                'module' => 'database',
                'controller' => 'smr',
                'action' => 'record',
                'id' => $s['id']),
                NULL,
                true);
                $html .= '<li><a href="';
                $html .= $url;
                $html .= '" title="Scheduled monument details for ';
                $html .= $s['monumentName'];
                $html .= '">';
                $html .= 'Scheduled monument: ';
                $html .= $s['monumentName'];
                $html .= ' is within ';
                $html .= number_format($s['distance']*1000,3);
                $html .= ' metres.';
                $html .= '</a></li>';
            }

            $html .= '</ul></div>';

        }
    return $html;
    }

}
