<?php

/** A view helper for determining whether a find is within a set distance
 * of a point.
 *
 * An example of use:
 *
 * <?php
 * echo $this->findsSmr()
 * ->setLat($lat)
 * ->setLon($lon)
 * ->setDistance($distance);
 * ?>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Pas_View_Helper
 * @version 1
 * @example path description
 *
 */
class Pas_View_Helper_FindsSmr extends Zend_View_Helper_Abstract
{

    /** The latitude to query
     * @access protected
     * @var double
     */
    protected $_lat;

    /** The longitude to query
     * @access protected
     * @var double
     */
    protected $_lon;

    /** The distance to query
     * @access protected
     * @var integer
     */
    protected $_distance = 0.25;

    /** Get the latitude
     * @access public
     * @return double
     */
    public function getLat()
    {
        return $this->_lat;
    }

    /** Get longitude
     * @access public
     * @return double
     */
    public function getLon()
    {
        return $this->_lon;
    }

    /** Get the distance
     * @access public
     * @return integer
     */
    public function getDistance()
    {
        return $this->_distance;
    }

    /** Set the latitude
     * @access public
     * @param double $_lat
     */
    public function setLat($_lat)
    {
        $this->_lat = $_lat;
    }

    /** Set the longitude
     * @access public
     * @param double $_lon
     */
    public function setLon($_lon)
    {
        $this->_lon = $_lon;
    }

    /** Set the distance
     * @access public
     * @param integer $_distance
     */
    public function setDistance($_distance)
    {
        $this->_distance = $_distance;
    }

    /** Get the data from the model
     * @access public
     * @param float $lat
     * @param float $lon
     * @param integer $distance
     * @return boolean
     */
    public function getData($lat, $lon, $distance)
    {
        $smr = new ScheduledMonuments();
        $smrs = $smr->getSMRSNearbyFinds($lat, $lon, $distance);
        if (!empty($smrs)) {
            return $this->buildHtml($smrs);
        } else {
            return false;
        }
    }

    /** The main function
     * @access public
     * @return \Pas_View_Helper_FindsSmr
     */
    public function findsSmr()
    {
        return $this;
    }

    /** Build and return the html for display
     * @access public
     * @return string
     */
    public function buildHtml()
    {
        $data = $this->getData($this->get_lat(), $this->get_lon(), $this->get_distance());
        $html = '';
        if ($data) {
            $html .= '<h3>Finds within 250 metres of centre of SMR</h3><ul>';
            foreach ($smrs as $s) {
                $html .= '<li><a href="';
                $html .= $this->view->url(array('module' => 'database', 'controller' => 'artefacts', 'action' => 'record', 'id' => $s['id']), NULL, true);
                $html .= '" title="View details for ' . $s['old_findID'] . '">';
                $html .= $s['old_findID'];
                $html .= '</a>';
                $html .= '-  a ' . $s['objecttype'] . ' from ' . $s['county'] . ' at a distance of ' . number_format(($s['distance'] * 1000), 3) . ' metres.';
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }
}