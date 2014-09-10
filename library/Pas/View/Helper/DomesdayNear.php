<?php
/**
 * DomesdayNear helper
 *
 * A helper for finding out which entries in the Domesday book are near the
 * point of recording a PAS object.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->domesdayNear()->setLon(51.2)->setLat(-2.3)->setRadius(2);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @uses viewHelper Pas_View_Helper
 * @uses Zend_Cache
 * @uses Pas_Service_Domesday_Place
 * @version 1
 * @since 18/5/2014
 * @license GNU
 * @category Pas
 * @package Pas_View_Helper
 * @example /app/views/scripts/partials/database/findspot.phtml
 */
class Pas_View_Helper_DomesdayNear extends Zend_View_Helper_Abstract
{
    /** The base url for the service
     * @access protected
     * @var @string
     */
    protected $_url = 'http://domesdaymap.co.uk/';

    /** The url of the place for the html
     * @access protected
     * @var string
     */
    protected $_baseurl = 'http://domesdaymap.co.uk/place/';

    /** The class to call for Domesday data
     * @access protected
     * @var object
     */
    protected $_domesday;

    /** The cache object
     * @access protected
     * @var object
     */
    protected $_cache;

    /** The latitude to query
     * @access protected
     * @var string
     */
    protected $_lat;

    /** The longitude to query
     * @access protected
     * @var string
     */
    protected $_lon;

    /** the radius to query
     * @access protected
     * @var int
     */
    protected $_radius;

    /** Get the latitude to query
     * @access public
     * @return string
     */
    public function getLat() {
        return $this->_lat;
    }

    /** Get the longitude to query
     * @access public
     * @return string
     */
    public function getLon() {
        return $this->_lon;
    }

    /** Get the radius to query
     * @access public
     * @return int
     */
    public function getRadius() {
        return $this->_radius;
    }

    /** Set the latitude
     * @access public
     * @param  string $lat
     * @return \Pas_View_Helper_DomesdayNear
     */
    public function setLat( $lat )  {
        $this->_lat = $lat;
        return $this;
    }

    /** Set the longitude to query
     * @access public
     * @param  string $lon
     * @return \Pas_View_Helper_DomesdayNear
     */
    public function setLon( $lon) {
        $this->_lon = $lon;
        return $this;
    }

    /** Set the radius to query
     * @access public
     * @param  int $radius
     * @return \Pas_View_Helper_DomesdayNear
     * @throws Exception
     */
    public function setRadius( $radius ) {
        if (!is_int($radius)) {
            throw new Exception('Defined radius needs to be an integer');
    }
        $this->_radius = $radius;
        return $this;
    }

    /** Get the domesday service class
     * @access public
     * @return object
     */
    public function getDomesday() {
        $this->_domesday = new Pas_Service_Domesday_Place();
        return $this->_domesday;
    }

    /** Get the cache object
     * @access public
     * @return object
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** the function to call
     * @access public
     * @return \Pas_View_Helper_DomesdayNear
     */
    public function domesdayNear() {
        return $this;
    }

    /** get the data from the service
     * @access public
     * @return function
     */
    public function getManors() {
        $params = array(
            'lat' => $this->getLat(),
            'lng' => $this->getLon(),
            'radius' => $this->getRadius()
                );
        $key = md5($this->getLat() . $this->getLon() . $this->getRadius());
        $response = $this->getPlacesNear( $params, $key);
        return $this->buildHtml($response, $this->getRadius());
        }

    /** To string method
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->getManors();
    }

    /** Get the places near to point
     * @access public
     * @param  array $params
     * @param  type  $key
     * @return type
     */
    public function getPlacesNear(array $params, $key) {
        if (!($this->getCache()->test($key))) {
            $data = $this->getDomesday()->getData('placesnear', $params);
            $this->getCache()->save($data);
        } else {
            $data = $this->getCache()->load($key);
        }
        return $data;
    }

    /** Build html string
     * @access public
     * @param  array $response
     * @param  int    $radius
     * @return string
     */
    public function buildHtml( $response,  $radius) {
        $html = '';
        if ($response) {
            $html .= '<h3>Adjacent Domesday Book places</h3>';
            $html .= '<a  href="';
            $html .= $this->_url;
            $html .= '"><img class="dec flow"';
            $html .= 'src="http://domesdaymap.co.uk/media/images/lion1.gif"';
            $html .- 'width="67" height="93"/></a>';
            $html .= '<ul>';
            foreach ($response as $domesday) {
                $html .= '<li><a href="';
                $html .= $this->_baseurl . $domesday->grid;
                $html .= '/' . $domesday->vill_slug;
                $html .= '">'. $domesday->vill . '</a></li>';
            }
            $html .= '</ul>';
            $html .= '<p>Domesday data  within ';
            $html .= $radius;
            $html .= ' km of discovery point is surfaced via the excellent ';
            $html .= '<a href="http://domesdaymap.co.uk">Open Domesday</a> website.</p>';
            }
        return $html;
    }
}