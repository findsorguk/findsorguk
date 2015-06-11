<?php

/** A view helper for determining whether a find is within a set distance
 * of a point.
 *
 * An example of use:
 *
 * <?php
 * echo $this->findsNearSmr()
 * ->setLat($lat)
 * ->setLon($lon)
 * ->setDistance($distance);
 * ?>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package View
 * @subpackage Helper
 * @version 1
 * @example path description
 *
 */
class Pas_View_Helper_FindNearSmr extends Zend_View_Helper_Abstract
{

    /** The latitude to query
     * @access protected
     * @var double
     */
    protected $_lat = NULL;

    /** The longitude to query
     * @access protected
     * @var double
     */
    protected $_lon = NULL;

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
    public function setLat($lat)
    {
        $this->_lat = $lat;
        return $this;
    }

    /** Set the longitude
     * @access public
     * @param double $_lon
     */
    public function setLon($lon)
    {
        $this->_lon = $lon;
        return $this;
    }

    /** Set the distance
     * @access public
     * @param integer $_distance
     */
    public function setDistance($distance)
    {
        $this->_distance = $distance;
        return $this;
    }

    /** Get the data from the model
     * @access public
     * @param float $lat
     * @param float $lon
     * @param integer $distance
     * @return boolean
     */
    public function getData()
    {
        $coords = array($this->getLat(), $this->getLon());
        if (array_filter($coords)) {
            $smr = new ScheduledMonuments();
            return $smr->getSMRSNearby($this->getLat(), $this->getLon(), $this->getDistance());
        } else {
            return false;
        }
    }

    /** The main function
     * @access public
     * @return \Pas_View_Helper_FindsNearSmr
     */
    public function findNearSmr()
    {
        return $this;
    }

    /** Build and return the html for display
     * @access public
     * @return string
     */
    public function buildHtml($data)
    {
        $html = '';
        if (!empty($data)) {
            $html .= '<h3 class="lead">Scheduled monuments within 250 metres of this find</h3>';
            $html .= '<ul>';
            $html .= $this->view->partialLoop('partials/database/structural/proximity.phtml', $data);
            $html .= '</ul>';
        }
        return $html;
    }


    /** Create html
     * @return string
     */
    public function __toString()
    {
        return $this->buildHtml($this->getData());
    }
}