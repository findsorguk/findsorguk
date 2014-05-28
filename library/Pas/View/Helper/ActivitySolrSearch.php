<?php
/**
 * A view helper for displaying activity from Solr
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @since 16/5/2014
 * @category Pas
 * @package Pas_View_Helper
 * @license GNU
 * @copyright Daniel Pett <dpett@britishmuseum.org>
 *
 */
class Pas_View_Helper_ActivitySolrSearch extends Zend_View_Helper_Abstract
{
    /** The solr object
     * @access protected
     * @var object
     */
    protected $_solr;

    /** Solr config array
     * @access protected
     * @var array
     */
    protected $_solrConfig;

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

    /** The query
     * @access protected
     * @var string
     */
    protected $_q = '*:*';

    /** The fields to query from
     * @access protected
     * @var string
     */
    protected $_fields = '*';

    /** The starting point for the index
     * @access protected
     * @var int
     */
    protected $_start = 0;

    /** The limit of results to return
     * @access protected
     * @var int
     */
    protected $_limit = 4;

    /** The sort field
     * @access protected
     * @var string
     */
    protected $_sort = 'created';

    /** The direction of sort
     * @access protected
     * @var string
     */
    protected $_direction = 'desc';

    /** The cache key
     * @access protected
     * @var string
     */
    protected $_key;

    /** Get the cache key to query
     * @access public
     * @return string
     */
    public function getKey()
    {
        return md5($this->getQ());
    }

    /** Get the Start number
     * @access public
     * @return int
     */
    public function getStart()
    {
        return $this->_start;
    }

    /** Get the limit
     * @access public
     * @return int
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /** Get the sort column
     * @access public
     * @return string
     */
    public function getSort()
    {
        return $this->_sort;
    }

    /** Get the direction of the sort
     * @access public
     * @return string
     */
    public function getDirection()
    {
        return $this->_direction;
    }

    /** Set the start number
     * @access public
     * @param  int                                 $start
     * @return \Pas_View_Helper_ActivitySolrSearch
     */
    public function setStart($start)
    {
        $this->_start = $start;

        return $this;
    }

    /** Set the limit of records to return
     * @access public
     * @param  int                                 $limit
     * @return \Pas_View_Helper_ActivitySolrSearch
     */
    public function setLimit($limit)
    {
        $this->_limit =  $limit;

        return $this;
    }

    /** Set the sort column
     * @access public
     * @param  string                              $sort
     * @return \Pas_View_Helper_ActivitySolrSearch
     */
    public function setSort( $sort)
    {
        $this->_sort = $sort;

        return $this;
    }

    /** Set the sort direction
     * @access public
     * @param  string                              $direction
     * @return \Pas_View_Helper_ActivitySolrSearch
     */
    public function setDirection( $direction)
    {
        $this->_direction = $direction;

        return $this;
    }

    /** Get the fields
     * @access public
     * @return type
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /** Set the fields to query
     * @access public
     * @param  string                              $fields
     * @return \Pas_View_Helper_ActivitySolrSearch
     */
    public function setFields( $fields)
    {
        $this->_fields = $fields;

        return $this;
    }

    /** Get the query
     * @access public
     * @return string
     */
    public function getQ()
    {
        return $this->_q;
    }

    /** Set the query
     * @access public
     * @param  string                              $q
     * @return \Pas_View_Helper_ActivitySolrSearch
     */
    public function setQ( $q)
    {
        $this->_q = $q;

        return $this;
    }

    /** get the solr object
     * @access public
     * @return object
     */
    public function getSolr()
    {
        $this->_solr = new Solarium_Client($this->_solrConfig);

        return $this->_solr;
    }

    /** Get the solr config array
     * @access public
     * @return type
     */
    public function getSolrConfig()
    {
        $config = $this->getConfig()->solr->toArray();
        $config['path'] = '/solr/';
        $config['core'] = 'beopeople';
        $this->_solrConfig = array(
            'adapteroptions' => $config
                );

        return $this->_solrConfig;
    }

    /** Get the config object
     * @access public
     * @return type
     */
    public function getConfig()
    {
        $this->_config = Zend_Registry::get('config');

        return $this->_config;
    }

    /** Get the cache object
     * @access public
     * @return object
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('rulercache');

        return $this->_cache;
    }

    /** The class to return
     * @access public
     * @return \Pas_View_Helper_ActivitySolrSearch
     */
    public function activitySolrSearch()
    {
        return $this;
    }

    /** Get the data from solr
     * @access public
     * @return array $data
     */
    public function getData()
    {
        $select = array(
            'query' => $this->getQ(),
            'start' => $this->getStart(),
            'rows'  => $this->getLimit(),
            'fields'    => array(
                $this->getFields()
                    ),
            'sort'  => array(
                $this->getSort()    => $this->getDirection()
                    ),
            'filterquery'   =>  array(),
        );
        if ( !( $this->getCache->test( $this->getKey() ) ) ) {
    $query = $this->getSolr()->createSelect($select);
    $resultset = $this->getSolr()->select($query);
    $data = array();
    $data['numberFound'] = $resultset->getNumFound();
    foreach ($resultset as $doc) {
            $data['images'][] = $this->parseData($doc);
    }
    $this->getCache()->save($data);
    } else {
            $data = $this->getCache()->load( $this->getKey() );
    }

    return $this->buildHtml($data);
    }

    /** Parse the array of docs
     * @access public
     * @param  array $doc
     * @return array
     */
    public function parseData(array $doc)
    {
        $fields = array();
        foreach ($doc as $key => $value) {
            $fields[$key] = $value;

        }

        return $fields;
    }

    /** Build the html to return
     * @access public
     * @return string
     */
    public function buildHtml()
    {
        $html = '';
        $data = $this->getData();
        if (array_key_exists('images', $data )) {
            $html = '<h3>Number of people assigned</h3>';
            $html .= '<p>We have recorded ' . $data['numberFound'];
            $html .= ' people.</p>';
        }

        return $html;
    }

    /** Magic string method
     * @access public
     * @return object
     */
    public function __toString()
    {
        return $this->buildHtml();
    }
}
