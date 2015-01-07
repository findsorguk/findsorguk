<?php

/**
 * A view helper for MP bios via sparql
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see  Zend_View_Helper_Abstract
 * @uses Zend_Registry Zend Registry
 * @uses Zend_Cache
 */
class Pas_View_Helper_MpBio extends Zend_View_Helper_Abstract
{
    const URI = 'https://dbpedia.org/resource/';

    /** The sparql class
     * @access protected
     * @var object
     */
    protected $_arc;

    /** The config object
     * @access protected
     * @var object
     */
    protected $_config;

    /** The cache object
     * @access protected
     * @var object
     */
    protected $_cache;

    /** Get the cache object
     * @access public
     * @return object
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    protected $_uri;

    /**
     * @return mixed
     */
    public function getUri()
    {
        $this->_uri = self::URI . $this->getFullname();
        return $this->_uri;
    }

    /** Get the full name to query
     * @access public
     * @return string
     */
    public function getFullname()
    {
        return $this->_fullname;
    }

    /** Set the fullname to query
     * @access public
     * @param  string $fullname
     * @return \Pas_View_Helper_MpBio
     */
    public function setFullname($fullname)
    {
        $this->_fullname = urlencode(str_replace(' ', '_', $fullname));
        return $this;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_MpBio
     */
    public function mpBio()
    {
        return $this;
    }

    /** The to string method
     * @access public
     * @return string|void
     */
    public function __toString()
    {

    }

    public function getData()
    {
        $graph = new EasyRdf_Graph( $this->getUri());
        $graph->load();
    }

}