<?php
/** 
 * A view helper for rendering a static google map
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * echo $this->googleStaticMap()
 * ->setApiKey($key)
 * ->setParameters($parameters)
 * ->setCreateUrl(true);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @version 1
 * @license http://URL name
 * @example /app/modules/flickr/views/scripts/photos/details.phtml 
 * @category Pas
 * @package View_Helper
 * @see http://code.google.com/apis/maps/documentation/staticmaps/
 * 
 */
class Pas_View_Helper_GoogleStaticMap extends Zend_View_Helper_Abstract {
    
    /** The api key for google
     * @access prp
     * @var string 
     */
    protected $_apiKey;
    
    /** The base url for producing a map
     * @access protected
     * @var string
     */
    protected $_staticUrl = 'http://maps.google.com/maps/api/staticmap';
    
    /** The parameter array
     * @access protected
     * @var array
     */
    protected $_parameters;
    
    /** The alt tag to give the map
     * @access protected
     * @var string
     */
    protected $_alt;
    
    /** The title tag to give
     * @access protected
     * @var string
     */
    protected $_title;

    /** Create url rather than image
     * @access protected
     * @var boolean
     */
    protected $_createUrl = false;
    
    /** Get the api key
     * @access public
     * @return string
     */
    public function getApiKey() {
        return $this->_apiKey;
    }

    /** Set the api key
     * @access public
     * @param string $apiKey
     * @return \Pas_View_Helper_GoogleStaticMap
     */
    public function setApiKey($apiKey) {
        $this->_apiKey = $apiKey;
        return $this;
    }
   
    /** Get the parameters
     * @access public
     * @return array
     */
    public function getParameters() {
        return $this->_parameters;
    }

    /** Set the parameters up
     * @access public
     * @param array $parameters 
     *		center: {required if markers not present} [array(lat,lon),string address]
     *		zoom: {required if markers not present} [int]
     *		size: {required} [array(width,height)]
     *		format: {optional} [png8,png,png32,gif,jpg,jpg-baseling]
     *		maptype: {optional} [roadmap, satellite, hybrid, terrain]
     *		mobile: {optional} [true,false]
     *		language: {optional} [string]
     *		markers: {optional} [array(array(lat,lon),
     *							string markerStyles|markerLocation1|markerLocation2|...)]
     *		path: {optional} [array(array(lat,lon),string pathStyles|pathLocation1|pathLocation2|...)]
     *		visible: {optional} [array(array(lat,lon),string)]
     *		sensor: {optional} [true,false] (default will be false)
     * @return \Pas_View_Helper_GoogleStaticMap
     */
    public function setParameters(array $parameters) {
        $this->_parameters = $parameters;
        return $this;
    }

    /** Get the alt tag
     * @access public
     * @return string
     */
    public function getAlt() {
        return $this->_alt;
    }

    /** Set the alt tag
     * @access public
     * @param string $alt
     * @return \Pas_View_Helper_GoogleStaticMap
     */
    public function setAlt($alt) {
        $this->_alt = $alt;
        return $this;
    }

    /** Get the title
     * @access public
     * @return string
     */
    public function getTitle() {
        return $this->_title;
    }

    /** Set the title
     * @access public
     * @param string $title
     * @return \Pas_View_Helper_GoogleStaticMap
     */
    public function setTitle($title) {
        $this->_title = $title;
        return $this;
    }
    
    /** Get the create url boolean
     * @access public
     * @return type
     */
    public function getCreateUrl() {
        return $this->_createUrl;
    }

    /** Set the variable for creation of url only
     * @access public
     * @param boolean $createUrl
     * @return \Pas_View_Helper_GoogleStaticMap
     */
    public function setCreateUrl(boolean $createUrl) {
        if(is_bool($createUrl)){
        $this->_createUrl = $createUrl;
        }
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_GoogleStaticMap
     */
    public function googleStaticMap() {
        return $this;
    }
    
    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->buildHtml(
                $this->getParameters(), 
                $this->getAlt(), 
                $this->getTitle(), 
                $this->getCreateUrl()
                );
    }
    
    /** Build the html to return
     * @access public
     * @param array $parameters
     * @param string $alt
     * @param string $title
     * @param boolean $createUrl
     */
    public function buildHtml( array $parameters, $alt, $title, $createUrl) {
        $html = '';
        if(isset($parameters['size']) && (isset($parameters['center'], 
                $parameters['zoom']) || isset($parameters['markers']))){

            //Check for sensor
            if(!isset($parameters['sensor'])){
                $parameters['sensor'] = 'false';
            }
            
            $url = $this->_staticUrl . '?key=' . $this->getApiKey();
            $url .= '&sensor=';
            $url .= $parameters['sensor'];
            $url .= '&size=';
            $url .= $parameters['size'][0];
            $url .= 'x';
            $url .= $parameters['size'][1];
            
            //Iterate through the markers array
            if (isset($parameters['markers'])) {
                foreach ($parameters['markers'] as $marker) {
                    if(is_array($marker)){
                        $url .= '&markers=';
                        $url .= $marker[0];
                        $url .= ',';
                        $url .= $marker[1];
                    } else {
                        $url .= '&markers=';
                        $url .=  $marker;
                    }
                }
            }
            //Iterate through path parameter
            if (isset($parameters['path'])) {
                foreach($parameters['path'] as $path) {
                    if(is_array($path)) {
                        $url .= '&path=';
                        $url .= $path[0];
                        $url .= ',';
                        $url .= $path[1];
                    } else {
                        $url .= '&path=';
                        $url .= $path;
                    }
                }
            }
            //Iterate through path parameter
            if (isset($parameters['visible'])) {
                foreach ($parameters['visible'] as $visible) {
                    if(is_array($visible)) {
                        $url .= '&visible=';
                        $url .= $visible[0];
                        $url .= ',';
                        $url .= $visible[1];
                    } else {
                        $url .= '&visible=';
                        $url .= $visible;
                    }
                } 
            }
            
            if (isset($parameters['center'])) {
                $url .= '&center=';
                if(is_array($parameters['center'])) {
                    $url .= $parameters['center'][0];
                    $url .= ',';
                    $url .= $parameters['center'][1];
                } else {
                    $url .= $parameters['center'];
                }
            }
            //Check for format
            if (isset($parameters['format'])) {
                $url .= '&format=';
                $url .= $parameters['format'];
            }

            //Check for zoom
            if (isset($parameters['zoom'])) {
                $url .= '&zoom=';
                $url .= $parameters['zoom'];
            }
            //Check for create url
            if($createUrl) {
                $html .= '<img width="';
                $html .= $parameters['size'][0];
                $html .= '" height="';
                $html .= $parameters['size'][1];
                $html .= '" src="';
                $html .= $url;
                $html .= '" alt="';
                $html .= $alt;
                $html .= '" title="';
                $html .= $title;
                $html .= '">';
            } else {
                $html .= $url;
            }
        return $html;
    }
    }
}