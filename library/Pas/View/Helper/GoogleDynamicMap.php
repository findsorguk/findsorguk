<?php

/**
 * GoogleDynamicMap helper for rendering javascript slippy maps
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @since 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @category Pas
 * @package View
 * @subpackage Helper
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_GoogleDynamicMap extends Zend_View_Helper_Abstract
{
    /** The api key
     * @access protected
     * @var string $key
     */
    protected $_key;

    /** The config object
     * @access protected
     * @var mixed
     */
    protected $_config;

    /** The region
     * @access protected
     * @var  string
     */
    protected $_region;

    /** The SSL string for the GMAPS api
     * @access protected
     * @var string
     */
    protected $_dynamicUrl = 'https://maps.googleapis.com/maps/api/js';

    /** The clustering api string
     * @var string
     * @access protected
     */
    protected $_clusterUrl = '/js/markerclusterer.js';

    /** The version number
     * @var string
     */
    protected $_version;

    /** The sensor value
     * @var string
     */
    protected $_sensor = 'false';

    /** String for text type for javascript
     * @var string
     * @access protected
     */
    protected $_type = 'text/javascript';

    /** Construct the class
     * @access public
     * */
    public function __construct()
    {
        $this->_config = Zend_Registry::get('config');
        if ($this->_key == null) {
            $this->_key = $this->_config->webservice->googlemaps->apikey;
        }
        if ($this->_region == null) {
            $this->_region = $this->_config->webservice->googlemaps->region;
        }
        if ($this->_version == null) {
            $this->_version = $this->_config->webservice->googlemaps->version;
        }
    }

    /** The class to return
     * @access public
     * @return string
     * @param string $sensor
     * @param string $clusterer
     */
    public function googleDynamicMap($sensor = null, $clusterer = null)
    {
        if (!is_null($sensor)) {
            $this->_sensor = 'true';
        }
        $url = $this->_dynamicUrl . '?version=' . $this->_version;
        $url .= '&key=' . $this->_key;
        $url .= '&sensor=' . $this->_sensor;
        $this->view->inlineScript()->appendFile($url, $this->_type);
        if (!is_null($clusterer)) {
            $this->view->inlineScript()->appendFile($this->_clusterUrl, $this->_type);
        }
    }

}
