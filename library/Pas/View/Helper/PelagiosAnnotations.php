<?php

/**
 * PelagiosAnnotations helper
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @uses viewHelper Pas_View_Helper
 * @copyright (c) 2014, Daniel Pett
 * @license http://URL GNU
 * @version 1
 * @since 1
 * @category Pas
 * @package Pas_View_Helper
 */
class Pas_View_Helper_PelagiosAnnotations extends Zend_View_Helper_Abstract
{
    /** The base pelagios api url
     *
     */
    const BASEURI = 'http://pelagios.dme.ait.ac.at/api/places/';

    /** The suffix for the call
     *
     */
    const SUFFIX = '/datasets.json';

    /** The pleiades base uri
     *
     */
    const PLEIADESURI = 'http://pleiades.stoa.org/places/';

    /** The pelagios blog
     *
     */
    const PELAGIOS = 'http://pelagios-project.blogspot.com/';

    /** The cache object
     * @access protected
     * @var object
     */
    protected $_cache;

    /** The uri
     * @access protected
     * @var string
     */
    protected $_uri;

    /** Construct the cache
     *
     */
    public function __construct()
    {
        $this->_cache = Zend_Registry::get('cache');
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_PelagiosAnnotations
     */
    public function pelagiosAnnotations()
    {
        return $this;
    }

    /** set the Pleiades place
     * @access public
     * @param  int                                  $place
     * @return \Pas_View_Helper_PelagiosAnnotations
     * @throws Zend_Exception
     */
    public function setPleiadesPlace($place)
    {
        if (isset( $place )) {
            $this->_uri = urlencode(self::PLEIADESURI . $place);
        } else {
            throw new Zend_Exception('No uri has been provided to query');
        }

        return $this;
    }

    /** Get the data
     * @access public
     * @return type
     */
    public function _getData()
    {
        $key = md5($this->_uri . 'pelagios');
        if (!($this->_cache->test($key))) {
            $config = array(
                'adapter'   => 'Zend_Http_Client_Adapter_Curl',
                'curloptions' => array(
                    CURLOPT_POST =>  true,
                    CURLOPT_USERAGENT =>  'findsorguk',
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_RETURNTRANSFER => true,
                ),
        );
        $client = new Zend_Http_Client(self::BASEURI . $this->_uri
                . self::SUFFIX, $config);
        $response = $client->request();
        $data = $response->getBody();
        $json = json_decode($data);
        $newJson = array();
        foreach ($json as $js) {
            $js->pleiades = $this->_uri;
            $newJson[] = $js;
        }
        $this->_cache->save($newJson);
        } else {
            $newJson = $this->_cache->load($key);
        }

        return $newJson;
    }

    /** Build the html
     * @access public
     * @return string
     */
    public function html()
    {
        $html = '<h3>Other resources via Pelagios</h3>';
        if ($this->_getData()) {
            $html .= '<ul>';
            $html .= $this->view->partialLoop('partials/numismatics/pelagios.phtml',
                    $this->_getData());
            $html .= '</ul>';
            $html .= '<p>Data provided from the <a href="';
            $html .= self::PELAGIOS;
            $html .= '" title="read about Pelagios" >Pelagios Project</a></p>';
        } else {
            $html .= '<p>No annotations found</p>';
        }

        return $html;
    }

    /** To string method
     * @access public
     * @return type
     */
    public function __toString()
    {
        return $this->html();
    }
}
