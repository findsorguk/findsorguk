<?php

/**  A view helper for rendering linked data retrieved from the RDF nomisma sparql endpoint for RRC types.
 *
 * This helper takes the moneyer name and injects it into a sparql query and formats the results.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->nomismaRrcTypes()->setUri('augustus');
 * ?>
 *
 * </code>
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
class Pas_View_Helper_NomismaRrcTypes extends Zend_View_Helper_Abstract
{
    /** The language to parse */
    const LANGUAGE = 'en';

    /** The nomisma endpoint */
    const NOMISMA = 'http://nomisma.org/query';

    /**  The cache object
     * @returns \Zend_Cache
     * @var $_cache
     */
    protected $_cache;

    /** The URI to parse
     * @access protected
     * @var $_uri
     */
    protected $_uri;


    protected $_data;
    protected $_query;

    /** The main class
     * @access public
     */
    public function nomismaRrcTypes()
    {
        return $this;
    }

    /** Render the html
     * @return string
     * @access public
     */

    public function __toString()
    {
        if ($this->getData()) {
            return $this->_render($this->getData());
        } else {
            return 'No RRC types available';
        }
    }

    /** Get the data for rendering
     * @access public
     * @return
     * */
    public function getData()
    {
        $key = md5($this->getUri() . 'rrcTypes');
//        if (!($this->getCache()->test($key))) {
        $client = new Zend_Http_Client(
            null,
            array(
                'adapter' => 'Zend_Http_Client_Adapter_Curl',
                'keepalive' => true,
                'useragent' => "EasyRdf/zendtest"
            )
        );
        EasyRdf_Http::setDefaultHttpClient($client);

        EasyRdf_Namespace::set('nm', 'http://nomisma.org/id/');
        EasyRdf_Namespace::set('nmo', 'http://nomisma.org/ontology#');
        EasyRdf_Namespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
        EasyRdf_Namespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
        $sparql = new EasyRdf_Sparql_Client(self::NOMISMA);
        $data = $sparql->query('SELECT * WHERE {' .
            '  ?type ?role nm:' . $this->getUri() . ' ;' .
            '   a nmo:TypeSeriesItem ;' .
            '  skos:prefLabel ?label' .
            '  OPTIONAL {?type nmo:hasStartDate ?startDate}' .
            '  OPTIONAL {?type nmo:hasEndDate ?endDate}' .
            '  FILTER(langMatches(lang(?label), "en"))' .
            ' } ORDER BY ?label');
//            $this->getCache()->save($data);
//        } else {
//            $data = $this->getCache()->load($key);
//        }

        return $sparql->dump();
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

    /** Get the query for sparql magic
     * @access public
     * @return string
     */
    public function getQuery()
    {
        $this->_query = 'SELECT * WHERE {' .
            '  ?type ?role nm:' . $this->getUri() . ' ;' .
            '   a nmo:TypeSeriesItem ;' .
            '  skos:prefLabel ?label' .
            '  OPTIONAL {?type nmo:hasStartDate ?startDate}' .
            '  OPTIONAL {?type nmo:hasEndDate ?endDate}' .
            '  FILTER(langMatches(lang(?label), "en"))' .
            ' } ORDER BY ?label';
        return $this->_query;
    }

    /** Render the data
     * @access protected
     * @param  array $data
     */
    public function _render($data)
    {
        $html = '';
        Zend_Debug::dump($data);
        $types = array();
        foreach ($data as $rrc) {
            $types[] = array(
                'type' => $rrc->type,
                'label' => $rrc->label,
                'startDate' => $rrc->startDate,
                'endDate' => $rrc->endDate
            );
        }
        Zend_Debug::dump($types);
        exit;
        if (!empty($types)) {
            $html .= '<h3 class="lead">Types issued</h3>';
            $html .= '<ul>';
            $html .= $this->partialLoop('partials/numismatics/roman/rrcTypes.phtml', $types);
            $html .= '</ul>';
        }

        return $html;
    }

    /** Get the cache object
     * @return mixed
     * @access public
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }
}
