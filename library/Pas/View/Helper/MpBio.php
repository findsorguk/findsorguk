<?php
/**
 * A view helper for MP bios via sparql
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Zend_Registry Zend Registry
 * @uses Arc2
 * @uses Zend_Cache
 * @todo Swap the ARC2 function for easyRdf
 */

class Pas_View_Helper_MpBio extends Zend_View_Helper_Abstract
{
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

    /** The fullname of the MP
     * @access protected
     * @var string
     */
    protected $_fullname;

    /** Get the Sparql object
     * @access public
     * @return object
     */
    public function getArc()
    {
        $this->_arc = new Arc2();

        return $this->_arc;
    }

    /** Get the cache object
     * @access public
     * @return object
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('cache');

        return $this->_cache;
    }

    /** Get the config for the store
     * @access public
     * @return array
     */
    public function getConfig()
    {
        $this->_config = array(
            'remote_store_endpoint' => 'http://dbpedia.org/sparql'
            );

        return $this->_config;
    }

    /** Get the fullname to query
     * @access public
     * @return string
     */
    public function getFullname()
    {
        return $this->_fullname;
    }

    /** Set the fullname to query
     * @access public
     * @param  string                 $fullname
     * @return \Pas_View_Helper_MpBio
     */
    public function setFullname(string $fullname)
    {
        $this->_fullname = $fullname;

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
     * @return null
     */
    public function __toString()
    {
        if (!is_null($this->_fullname)) {
            $data = $this->sparqlQuery($this->_fullname);
            if (count($data)) {
                $response = $this->parseResults($data);

                return $this->buildHtml($response);
            } else {
                return NULL;
            }
            } else {
                return NULL;
            }
    }

    /** Perform Sparql
     * @access public
     * @param string $fullname
     */
    public function sparqlQuery($fullname)
    {
        $key = md5($fullname . 'mpbio');
        if (!($this->_cache->test($key))) {
        $fullname = str_replace(array('Edward Vaizey','Nicholas Clegg'),
                array('Ed Vaizey', 'Nick Clegg'),$fullname);
        $store = $this->_arc->getRemoteStore($this->_config);
        $name = str_replace(' ','_',$fullname);
        $encoded = urlencode($name);
        $query = '
        PREFIX owl: <http://www.w3.org/2002/07/owl#>
        PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
        PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
        PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
        PREFIX foaf: <http://xmlns.com/foaf/0.1/>
        PREFIX dc: <http://purl.org/dc/elements/1.1/>
        PREFIX : <http://dbpedia.org/resource/>
        PREFIX dbpedia2: <http://dbpedia.org/property/>
        PREFIX dbpedia: <http://dbpedia.org/>
        PREFIX skos: <http://www.w3.org/2004/02/skos/core#>
        PREFIX dbpedia-owl: <http://dbpedia.org/ontology/>
        SELECT *
        WHERE {
        ?mp foaf:name ?name .
        ?mp dbpedia-owl:abstract ?abstract.
        ?mp dbpedia-owl:thumbnail ?thumb .
        ?mp dbpedia2:party ?party .
        ?mp dbpedia2:almaMater ?uni .
        FILTER (?name = "' . $fullname . '"@en)
        FILTER langMatches( lang(?abstract), "en")
        FILTER (?party != "")
        }
        LIMIT 1';

        $rows = $store->query($query, 'rows');
        $this->_cache->save($rows);
        } else {
        $rows = $this->_cache->load($key);
        }

        return $rows;
    }

    /** Create an array from results
     *
     * @param unknown_type $results
     */
    public function parseResults($results)
    {
        $mpdata = array();
        foreach ($results as $r) {
            $mpdata['thumbnail'] = $r['thumb'];
            $mpdata['depiction'] = $r['depiction'];
            $mpdata['abstract'] = $r['abstract'];
            $mpdata['caption'] = $r['caption'];
            $mpdata['uni']= $r['uni'];
        }

        return $mpdata;
    }

    /** Build the html
     * @access public
     * @param $response
     */
    public function buildHtml($response)
    {
        $chunks = split('\. ',$response['abstract']);
        foreach ($chunks as $key=>$c) {
        $chunks[$key] = ($key%3==0) ? ($c . '.</p><p>') : ($c.'. ');
        }
        $abs = '<p>' . join($chunks) . '</p>';
        $html = '<h3>Dbpedia sourced information</h3>';
        if (array_key_exists('thumbnail',$response)) {
            list($w, $h, $type, $attr) = getimagesize($response['thumbnail']);
            $html .= '<img src="'.$response['thumbnail'] . '" alt ="Wikipedia
                sourced picture" height="' . $h . '" width="'
            . $w . '" class="flow" />';
        }
        $html .= $abs;
        if (array_key_exists('uni',$response)) {
            $html .= '<p>Educated: '.rawurldecode(str_replace(array('_',
                'http://dbpedia.org/resource/'), array(' ',''),$response['uni']))
                    . '</p>';
        }

        return $html;
    }

}
