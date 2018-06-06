<?php
/**
 * A view helper for parsing Nomisma Rdf 
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
 * 
 */
class Pas_View_Helper_PleiadesMintRdf extends Zend_View_Helper_Abstract {
    
    /** TheRDF client to use
     * @access protected
     * @var \Pas_RDF_Client
     */
    protected $_client;

    /** The cache object
     * @access protected
     * @var \Zend_Cache
     */
    protected $_cache;

    /** The uri to query
     * @access protected
     * @var string
     */
    protected $_uri;

    /** The default language
     * 
     */
    const LANGUAGE = 'en';
    
    /** The uri for the endpoint
     * 
     */
    const URI = 'https://pleiades.stoa.org/places/';
    
    /** The suffix for format
     * 
     */
    const SUFFIX = '/rdf';

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_PleiadesMintRdf
     */
    public function pleiadesMintRdf()  {
        $this->_client = new Pas_RDF_Client();
        $this->_cache = Zend_Registry::get('cache');
        return $this;
    }

    /** Set the uri to query
     * @access public
     * @param string $uri
     * @return \Pas_View_Helper_PleiadesMintRdf
     * @throws Pas_Exception_Url
     */
    public function setUri($uri){
        if (isset($uri)) {
            $this->_uri = $uri;
        } else {
            throw new Pas_Exception_Url('No uri set', 500);
        }
        return $this;
    }

    /** Get data from the endpoint
     * @access protected
     * @return string
     */
    protected function getData() {
        $key = md5($this->_uri);
        if (!($this->_cache->test($key))) {
            $graph = new \EasyRdf\Graph( self::URI . $this->_uri . self::SUFFIX );
            $graph->load();
            $data = $graph->resource( self::URI . $this->_uri );
            $this->_cache->save($data);
        } else {
            $data = $this->_cache->load($key);
        }
        \EasyRdf\RdfNamespace::set('dcterms', 'http://purl.org/dc/terms/');
        \EasyRdf\RdfNamespace::set('pleiades', 'http://pleiades.stoa.org/places/vocab#');
        return $data;
    }

    /** Render the html
     * @access protected
     * @return string
     */
    protected function _render() {
        $pl = $this->getData();
        $html = '';
        $html .= '<ul>';
        $html .=  '<li>Place name: ' . $pl->get('dcterms:title') . '</li>';
        foreach ($pl->all('skos:altLabel','literal') as $term) {
            $html .= '<li>Alterative label: ' . $term->getValue() . '</li>';
        }
        $html .= '<li>Description: ' . $pl->get('dcterms:description') . '</li>';
        $html .= '<li>Subjects: ';
        $subjects = array();
        foreach ($pl->all('dcterms:subject','literal') as $term) {
            $subjects[] = $term->getValue() ;
        }
        $html .= implode(', ',$subjects);
        $html .='</li>';
        $html .= '<li>Referenced:';
        $html .= '<ul>';
        foreach ($pl->all('dcterms:bibliographicCitation','literal') as $term) {
            $html .= '<li>' . $term->getValue() . '</li>';
        }
        $html .= ' </ul>';
        $html .= '</li>';
        $html .= '</ul>';
        return $html;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->_render();
    }
}
