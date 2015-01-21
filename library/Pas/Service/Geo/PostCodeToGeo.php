<?php

/** A service class for getting postcode from geo data
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $postcode = new Pas_Service_Geo_PostCodeToGeo();
 * $geo = $postcode->getData('WC1B 3DG');
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Service
 * @subpackage Geo
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/contacts/controllers/StaffController.php
 *
 */
class Pas_Service_Geo_PostCodeToGeo
{

    /** The cache object
     * @access protected
     * @var \Zend_Cache
     */
    protected $_cache;

    /** The params to send
     * @access protected
     * @var array
     */
    protected $_params = array();

    /** The uri for the service
     * @access protected
     * @var string
     */
    protected $_uri = 'http://mapit.mysociety.org/postcode/';

    /** The validator
     * @access protected
     * @var \Pas_Validate_ValidPostCode
     */
    protected $_validator;

    /** The constructor function
     * @access public
     */
    public function __construct()
    {
        $this->_cache = Zend_Registry::get('cache');
        $this->_validator = new Pas_Validate_ValidPostCode();
    }

    /** Get the data for a postcode
     * @access public
     * @param string $postcode
     * @return array
     * @throws Pas_Geo_Exception
     */
    public function getData($postcode)
    {
        if ($this->_validator->isValid($postcode)) {
            $postcode = str_replace(' ', '', $postcode);
        } else {
            throw new Pas_Geo_Exception('Invalid postcode sent');
        }
        $key = md5($postcode);
        if (!($this->_cache->test($key))) {
            $response = $this->_get($postcode);
            $this->_cache->save($response);
        } else {
            $response = $this->_cache->load($key);
        }
        $geo = json_decode($response);
        return array('lat' => $geo->wgs84_lat, 'lon' => $geo->wgs84_lon);
    }

    /** Get the data via curl
     * @access protected
     * @param string $postcode
     * @return object
     */
    protected function _get($postcode)
    {
        $config = array(
            'adapter' => 'Zend_Http_Client_Adapter_Curl',
            'curloptions' => array(
                CURLOPT_POST => true,
                CURLOPT_USERAGENT => $_SERVER["HTTP_USER_AGENT"],
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_LOW_SPEED_TIME => 1
            ),
        );
        $client = new Zend_Http_Client($this->_uri . $postcode, $config);
        $response = $client->request();
        $code = $this->getStatus($response);
        if ($code == true) {
            return $response->getBody();
        } else {
            return null;
        }
    }

    /** Get the status for a response
     * @access public
     * @param Zend_Http_Response $response
     * @return boolean
     * @throws Exception
     */
    public function getStatus(Zend_Http_Response $response)
    {
        $code = $response->getStatus();
        switch ($code) {
            case ($code == 200):
                return true;
                break;
            case ($code == 400):
                throw new Exception('A valid appid parameter is required for this resource');
                break;
            case ($code == 404):
                throw new Exception('The resource could not be found');
                break;
            case ($code == 406):
                throw new Exception('You asked for an unknown representation');
                break;
            default;
                return false;
        }
    }

    /** Validate the postcode
     * @access public
     * @param string $postCode
     * @return boolean
     */
    public function validatePostcode($postCode)
    {
        if ($this->_validator->isValid($postCode)) {
            return true;
        } else {
            return false;
        }
    }
}