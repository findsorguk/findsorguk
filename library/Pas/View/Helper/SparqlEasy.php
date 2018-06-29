<?php
/**
 * A view helper for interfacing with Easy RDF and getting Roman data
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->sparqlEasy()->setId($this->dbpedia);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @version 1
 * @since 1
 * @category Pas
 * @package View
 * @subpackage Helper
 * @uses viewHelper Zend_View_Helper
 * @example /app/views/scripts/partials/numismatics/roman/emperor.phtml
 */
class Pas_View_Helper_SparqlEasy extends Zend_View_Helper_Abstract
{
    /** The endpoint
     * @access protected
     * @var object 
     */
    protected $_endpoint;
    
    /** The default endpoint Uri
     * @access protected
     * @var string
     */
    protected $_endpointUri = 'http://live.dbpedia.org/sparql';
    
    /** Get the endpoint uri
     * @access public
     * @return string
     */
    public function getEndpointUri() {
        return $this->_endpointUri;
    }

    /** Set the endpoint uri
     * @access public
     * @param string $endpointUri
     * @return \Pas_View_Helper_SparqlEasy
     */
    public function setEndpointUri( $endpointUri) {
        $this->_endpointUri = $endpointUri;
        return $this;
    }
    
    /** The client
     * @access protected
     * @var object
     */
    protected $_client;

    /** The id to query
     * @access protected
     * @var string
     */
    protected $_id;

    /** The cache object
     * @access protected
     * @var object
     */
    protected $_cache;
    
    /** Get the ID to query
     * @access protected
     * @return string
     */
    public function getId() {
        return $this->_id;
    }

    /** Set the ID to query
     * @access public
     * @param string $id
     * @return \Pas_View_Helper_SparqlEasy
     */
    public function setId( $id) {
        $this->_id = $id;
        return $this;
    }

        /** Get the endpoint
     * We're using the live sparql endpount
     * @access public
     * @return object
     */
    public function getEndpoint() {
        $this->_endpoint = new \EasyRdf\Sparql\Client($this->getEndpointUri());
        return $this->_endpoint;
    }

    /** Get the client 
     * @access public
     * @return object
     */
    public function getClient() {
        $this->_client = new Pas_RDF_Client();
        return $this->_client;
    }

    /** The array of namespaces to use
     * @access protected
     * @var array
     */
    protected $_nameSpaces = array(
            'category' => 'http://dbpedia.org/resource/Category:',
            'dbpedia' => 'http://dbpedia.org/resource/',
            'dbo' =>  'http://dbpedia.org/ontology/',
            'dbp' => 'http://dbpedia.org/property/'
        );
    
    /** Get the namespaces
     * @access public
     * @return array
     */
    public function getNameSpaces() {
        return $this->_nameSpaces;
    }

    /** Set new name spaces if so desired
     * @access public
     * @param array $nameSpaces
     * @return \Pas_View_Helper_SparqlEasy
     */
    public function setNameSpaces( array $nameSpaces) {
        $this->_nameSpaces = $nameSpaces;
        return $this;
    }
    
    /** Register the namespaces with EasyRdf
     * @access public
     * @return \Pas_View_Helper_SparqlEasy
     */
    public function registerNameSpaces() {
        foreach($this->getNameSpaces() as $k => $v){
            \EasyRdf\RdfNamespace::set($k, $v);
        }
        return $this;
    }
    
    /** Get the cache object
     * @access public
     * @return object
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_SparqlEasy
     */
    public function sparqlEasy() {
        $this->registerNameSpaces();
        return $this;
    }

    /** Get the sparql data
     * @access public
     * @return object
     */
    public function getSparqlData() {
        $query = 'SELECT DISTINCT * WHERE { ?x dbpedia-owl:commander dbpedia:';
        $query .= $this->getId();
        $query .= '  .  ?x dbpedia-owl:abstract ?abstract . ?x dbp:place ?place}';
        $key = md5($query);
        if (!($this->getCache()->test($key))) {
            $data = $this->getEndpoint()->query($query);
            $this->getCache()->save($data);
        } else {
            $data = $this->getCache()->load($key);
        }
        return $data;
    }

    /** Clean the string for dbpedia uri
     * @access protected
     * @param string $string
     * @return type
     */
    protected function _cleaner( $string) {
        $html = str_replace(array('http://dbpedia.org/resource/', 'Category:',
            '_'),array('','',' '), $string);
        return $html;
    }

    /** Clean out wikipedia link
     * @access public
     * @param string $string
     * @return string
     */
    protected function _wikiLink( $string) {
        $cleaned = str_replace(array('http://dbpedia.org/resource/'),
                array('http://en.wikipedia.org/wiki/'), $string);
        $html = '<a href="';
        $html .= $cleaned;
        $html .= '">';
        $html .= urldecode($this->_cleaner($string));
        $html .= '</a>';
        return $html;
    }

    /** Render the html
     * @access public
     * @return string
     */
    public function render() {
        $dataSparql = $this->getSparqlData();
        $html = '';
        if (sizeof($dataSparql) > 0) {
        $html .= '<h3 class="lead">Commander during battles</h3>';
        $html .= '<ul>';
        foreach ($dataSparql as $data) {
            $html .='<li>';
            $html .= $this->_wikiLink($data->x);
            $html .= ' : ';
            $html .= $this->_cleaner($data->place);
            $html .= '<br />';
            $html .= $data->abstract;
            $html .= '</li>';
        }
        $html .= '</ul>';
        }
        return $html;
    }

    /** Return the string
     * @access public
     * @return type
     */
    public function __toString() {
        return $this->render();
    }
}
