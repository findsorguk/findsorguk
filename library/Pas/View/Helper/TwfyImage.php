<?php

/**
 * A view helper for displaying image of a MP or Lord from theyworkforyou
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @since September 13 2011
 * @see http://www.theyworkforyou.com/api/ for documentation of their api.
 * @uses Zend_Config
 * @uses Zend_Cache
 * @uses Zend_Registry
 *
 */
class Pas_View_Helper_TwfyImage extends Zend_View_Helper_Abstract
{

    /** The cache object
     * @access public
     * @var \Zend_Cache
     */
    protected $_cache;

    /** The api key for twfy
     * @access protected
     * @var string
     */
    protected $_twfykey;

    /** The mp id number
     * @var int
     */
    protected $_id;

    /** Get the id key
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /** Set the id key
     * @access public
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /** Get the cache
     * @access public
     * @return \Zend_Cache
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Get the api key
     * @access public
     * @return string
     */
    public function getTwfykey()
    {
        $this->_twfykey = Zend_Registry::get('config')->webservice->twfy->apikey;
        return $this->_twfykey;
    }

    /** Set the api key
     * @param \Zend_Config $twfykey
     */
    public function setTwfykey($config)
    {
        $this->_twfykey = $config;
        return $this;
    }

    /** Call the they work for you api, caches response
     * @access public
     * @param integer $id The MP or Lord's ID number
     * @return array
     */

    public function callApi($id)
    {
        if (!($this->getCache()->test('mptwfy' . $id))) {
            $twfy = 'http://www.theyworkforyou.com/api/getPerson?key=' . $this->getTwfykey() . '&id=' . $id . '&output=js';
            $curl = new Pas_Curl();
            $curl->setUri($twfy);
            $curl->getRequest();
            $data = $curl->getJson();
            $this->getCache()->save($data);
        } else {
            $data = $this->getCache()->load('mptwfy' . $id);
        }
        return $data;
    }

    /** Create the image
     * @access public
     * @param integer $id The MP or Lord's ID number
     * @return string
     */
    public function twfyImage()
    {
        return $this;
    }

    public function __toString()
    {
        return $this->buildHtml($this->callapi($this->getId()));
    }

    /** Build the HTML for return
     * @param array $data
     * @return string The html to display
     */
    public function buildHtml($data)
    {
        $html = '';
        $data = array_slice($data, 0, 1);
        foreach ($data as $mp) {
            if (array_key_exists('image', $mp)) {
                $html .= '<img src="http://www.theyworkforyou.com/';
                $html .= $mp->image . '" class="img-circle pull-right" alt="Profile picture for ';
                $html .= $mp->full_name . '" height="' . $mp->image_height;
                $html .= '" width="' . $mp->image_width . '"/>';
            }

        }
        return $html;
    }
}