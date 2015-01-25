<?php

/**
 * StatisticsDatabase helper
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses viewHelper Pas_View_Helper
 * @version 1
 * @since 1
 * @uses Solarium_Client
 * @uses Zend_Registry
 * @uses Zend_Cache
 * @uses Zend_Config
 * @uses Zend_Controller_Front
 */
class Pas_View_Helper_StatisticsCountyPrecision extends Zend_View_Helper_Abstract
{
    /** The solr object
     * @access protected
     * @var object
     */
    protected $_solr;

    /** The index to query
     * @access protected
     * @var string
     */
    protected $_index;

    /** The limit
     * @access protected
     * @var int
     */
    protected $_limit;

    /** The cache object
     * @access protected
     * @var object
     */
    protected $_cache;

    /** The config object
     * @access protected
     * @var object
     */
    protected $_config;

    /** The solr config
     * @access protected
     * @var array
     */
    protected $_solrConfig;

    /** The start number to query
     * @access protected
     * @var int
     */
    protected $_start;

    /** The end number
     * @access protected
     * @var type
     */
    protected $_end;

    /** Which county
     * @access protected
     * @var type
     */
    protected $_county;

    /** The request object
     * @access protected
     * @var object
     */
    protected $_request;

    /** The front controller object
     * @access protected
     * @var object
     */
    protected $_front;

    /** Get the front controller
     * @access protected
     * @return object
     */
    public function getFront()
    {
        $this->_front = Zend_Controller_Front::getInstance()->getRequest();

        return $this->_front;
    }

    /** TYhe solr object
     * @access public
     * @return object
     */
    public function getSolr()
    {
        $this->_solr = new Solarium_Client($this->getSolrConfig());

        return $this->_solr;
    }

    /** Get the index to query
     * @access public
     * @return string
     */
    public function getIndex()
    {
        return $this->_index;
    }

    /** Get the limit
     * @access public
     * @return int
     */
    public function getLimit()
    {
        return $this->_limit;
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

    /** Get the config
     * @access public
     * @return object
     */
    public function getConfig()
    {
        $this->_config = Zend_Registry::get('config');

        return $this->_config;
    }

    /** Get the solr config array
     * @access public
     * @return array
     */
    public function getSolrConfig()
    {
        $this->_solrConfig = array(
            'adapteroptions' => $this->getConfig()->solr->toArray()
        );

        return $this->_solrConfig;
    }

    /** Get the start integer
     * @access public
     * @return type
     */
    public function getStart()
    {
        return $this->_start;
    }

    /** Get the end
     * @access public
     * @return type
     */
    public function getEnd()
    {
        return $this->_end;
    }

    /** Get the county
     * @access public
     * @return string
     */
    public function getCounty()
    {
        return $this->_county;
    }

    /** Get the request
     * @access public
     * @return object
     */
    public function getRequest()
    {
        $this->_request = $this->getFront()->getParams();

        return $this->_request;
    }

    /** Set the index
     * @access public
     * @param  string $index
     * @return \Pas_View_Helper_StatisticsCountyPrecision
     */
    public function setIndex($index)
    {
        $this->_index = $index;

        return $this;
    }

    /** Set the limit
     * @access public
     * @param  int $limit
     * @return \Pas_View_Helper_StatisticsCountyPrecision
     */
    public function setLimit($limit)
    {
        $this->_limit = $limit;

        return $this;
    }

    /** Set the start
     * @access public
     * @param  int $start
     * @return \Pas_View_Helper_StatisticsCountyPrecision
     */
    public function setStart($start)
    {
        $this->_start = $start;

        return $this;
    }

    /** Set the end
     * @access public
     * @param  int $end
     * @return \Pas_View_Helper_StatisticsCountyPrecision
     */
    public function setEnd($end)
    {
        $this->_end = $end;

        return $this;
    }

    /** Set the county
     * @access public
     * @param  string $county
     * @return \Pas_View_Helper_StatisticsCountyPrecision
     */
    public function setCounty($county)
    {
        $this->_county = $county;

        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_StatisticsCountyPrecision
     */
    public function statisticsCountyPrecision()
    {
        return $this;
    }

    /** Get the results from the index
     * @access public
     * @return array
     */
    private function getSolrResults()
    {
        $select = array(
            'query' => '*:*',
            'filterquery' => array(),
        );
        $query = $this->getSolr()->createSelect();
        $query->setRows(0);
        $request = $this->getRequest();
        if (array_key_exists('county', $request)) {
            $query->createFilterQuery('county')->setQuery('county:'
                . $request['county']);
        }
        if (!array_key_exists('datefrom', $request)) {
            $timespan = new Pas_Analytics_Timespan('thisyear');
            $dates = $timespan->getDates();
            $queryDateA = $dates['start'] . "T00:00:00.001Z";
            $queryDateB = $dates['end'] . "T23:59:59.99Z";
            $query->createFilterQuery('created')->setQuery('created:['
                . $queryDateA . ' TO ' . $queryDateB . ']');
        } else {
            $queryDateA = $request['datefrom'] . "T00:00:00.001Z";
            $queryDateB = $request['dateto'] . "T23:59:59.99Z";
            $query->createFilterQuery('created')->setQuery('created:['
                . $queryDateA . ' TO ' . $queryDateB . ']');
        }

        $stats = $query->getStats();
        $stats->createField('quantity');
        $stats->addFacet('precision');
        $resultset = $this->getSolr()->select($query);
        $data = $resultset->getStats();
        $stats = array();
        foreach ($data as $field) {
            foreach ($field->getFacets() as $field => $facet) {
                foreach ($facet AS $facetStats) {
                    $stats[] = array(
                        'precision' => $facetStats->getValue(),
                        'finds' => $facetStats->getSum(),
                        'records' => $facetStats->getCount()
                    );
                }
            }
        }
        $sort = array();
        foreach ($stats as $k => $v) {
            $sort['precision'][$k] = $v['precision'];
            $sort['finds'][$k] = $v['finds'];
        }
        array_multisort($sort['precision'], SORT_ASC, $sort['finds'], SORT_ASC, $stats);
        return $stats;
    }

    /** Build the html to return
     * @access public
     * @param  array $data
     * @return string
     */
    public function buildHtml(array $data)
    {
        $html = '';
        $html .= $this->view->partialLoop('partials/annual/precision.phtml', $data);

        return $html;
    }

    /** Return the string
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->buildHtml($this->getSolrResults());
    }
}
