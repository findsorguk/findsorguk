<?php

/**  A view helper for rendering linked data retrieved from the RDF nomisma graph
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category Pas
 * @package View
 * @subpackage Helper
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Pas_Rdf_Client
 * @uses viewHelper Pas_View_Helper
 * @uses EasyRdfGraph
 * @uses Zend_Cache
 */
class Pas_View_Helper_OcreData extends Zend_View_Helper_Abstract
{
    /** The language to parse
     *
     */
    const LANGUAGE = 'en';
    /**  The cache object
     * @returns \Zend_Cache
     * @var $_cache
     */
    protected $_cache;

    /** The URI to parse
     * @var $_uri
     */
    protected $_uri;

    /** The main class
     * @access public
     */
    public function OcreData()
    {
        return $this;
    }

    /** Render the html
     * @access public
     * @return string
     */
    public function __toString()
    {
        if ($this->getData()) {
            return $this->_render($this->getData());
        } else {
            return 'Nothing returned from Nomisma at this time';
        }
    }

    /** Get the data for rendering
     * @access public
     * @return
     * */
    public function getData()
    {
        $crro = new Ocre();
        return $crro->getInfo($this->getUri());
    }

    /** Get the uri
     * @access public
     * */
    public function getUri()
    {
        return $this->_uri;
    }

    /** Set the uri
     * @access public
     * @param string $uri
     * @todo add in validation of uri
     */
    public function setUri($uri)
    {
        if (!is_null($uri)) {
            $this->_uri = $uri;
        } else {
            return false;
        }
        return $this;
    }

    /** Get the cache object
     * @return mixed
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Render the data
     * @access protected
     * @param  EasyRdf_Resource $data
     */
    protected function _render(EasyRdf_Resource $data)
    {
        $html = '';
        if (is_object($data)) {
            foreach ($data->all('skos:prefLabel') as $labels) {
                $html .= $labels->getValue();
            }
        }
        return $html;
    }
}
