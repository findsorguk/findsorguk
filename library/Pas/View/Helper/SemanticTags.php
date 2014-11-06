<?php

/**
 * A view helper to redisplay the semantic tags that are extracted via open calais.
 *
 * An example of use
 *
 * <?php
 * echo $this->semanticTags()->setQuery('id:3000');
 * ?>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 2
 * @license http://URL GNU
 * @category Pas
 * @package Pas_View_Helper
 * @uses \Zend_Registry
 * @uses \Zend_Cache
 * @uses \Solarium_Client
 */
class Pas_View_Helper_SemanticTags extends Zend_View_Helper_Abstract
{

    /** The solr object
     * @acccess protected
     * @var object
     */
    protected $_solr;

    /** The solr configuration options
     * @access protected
     * @var array
     */
    protected $_solrConfig;

    /** The config object
     * @acess protected
     * @var object
     */
    protected $_config;

    /** The cache object
     * @access protected
     * @var Zend_Cache
     */
    protected $_cache;


    /** The query to call
     * @access protected
     * @var string
     */
    protected $_query = '*:*';

    /** The default fields to search on
     * @access protected
     * @var string
     * @todo pare back fields needed
     */
    protected $_fields = 'id,type,position,term';
    /** The default sort
     * @access protected
     * @var string
     */
    protected $_sort = 'created';

    /** The default direction of sort
     * @access protected
     * @var string
     */
    protected $_direction = 'desc';

    /** The default sort nu,ber
     * @access protected
     * @var int
     */
    protected $_start = 0;

    /** The default limit of records to return
     * @access protected
     * @var int
     */
    protected $_limit = 50;

    /** The default role
     * @access protected
     * @var string
     */
    protected $_role = 'public';

    /** Get the query entered
     * @access public
     * @return string
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /** Set the query if need be
     * @access public
     * @param  string $query
     * @return \Pas_View_Helper_LatestRecords
     */
    public function setQuery($query)
    {
        $this->_query = $query;
        return $this;
    }

    /** Get the fields for searching on
     * @access public
     * @return type
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /** Set the fields for searching on
     * @access public
     * @param  string $fields
     * @return \Pas_View_Helper_LatestRecords
     */
    public function setFields($fields)
    {
        $this->_fields = $fields;

        return $this;
    }

    /** Get the direction for sorting
     * @access public
     * @return type
     */
    public function getDirection()
    {
        return $this->_direction;
    }

    /** Set the direction of the search
     * @todo make only two options available (desc|asc)
     * @access public
     * @param  type $direction
     * @return \Pas_View_Helper_LatestRecords
     */
    public function setDirection($direction)
    {
        $this->_direction = $direction;
        return $this;
    }

    /** Get the direction of the sort
     * @access public
     * @return string
     */
    public function getSort()
    {
        return $this->_sort;
    }

    /** Get the start number (must be positive)
     * @access public
     * @return int
     */
    public function getStart()
    {
        return $this->_start;
    }

    /** Get the limit (must be positive)
     * @access  public
     * @return int
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /** Set the sort order
     * @access public
     * @param  string $sort
     * @return \Pas_View_Helper_LatestRecords
     */
    public function setSort($sort)
    {
        $this->_sort = $sort;
        return $this;
    }

    /** Set the start number
     * @access public
     * @param  int $start
     * @return \Pas_View_Helper_LatestRecords
     */
    public function setStart($start)
    {
        $this->_start = $start;
        return $this;
    }

    /** set the limit
     * @access public
     * @param  int $limit
     * @return \Pas_View_Helper_LatestRecords
     */
    public function setLimit($limit)
    {
        $this->_limit = $limit;
        return $this;
    }

    /** Get the cache key to save
     * @access public
     * @return string
     */
    public function getCacheKey()
    {
        return md5($this->getQuery() . $this->getRole() . 'tags');
    }

    /** get the cache object
     * @access public
     * @return Zend_Cache
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Get the config object
     * @access public
     * @return Zend_Config
     */
    public function getConfig()
    {
        $this->_config = Zend_Registry::get('config');
        return $this->_config;
    }

    /** Get the solr config
     * @access public
     * @return object
     */
    public function getSolrConfig()
    {
        $this->_solrConfig = array(
            'adapteroptions' => $this->getConfig()->solr->toArray()
        );
        $this->_solrConfig['adapteroptions']['core'] = 'tags';
        return $this->_solrConfig;
    }

    /** get the solr object
     * @access public
     * @return object
     */
    public function getSolr()
    {
        $this->_solr = new Solarium_Client($this->getSolrConfig());
        return $this->_solr;
    }

    /** Get role from user
     * @access public
     * @return boolean
     */
    public function getRole()
    {
        $user = new Pas_User_Details();
        $person = $user->getPerson();
        if ($person) {
            $this->_role = $user->getPerson()->role;
        }
        return $this->_role;
    }

    /** Get the results from Solr
     * @access public
     * @return array
     */
    public function getResults()
    {
        $select = array(
            'query' => $this->getQuery(),
            'start' => $this->getStart(),
            'rows' => $this->getLimit(),
            'fields' => array($this->getFields()),
            'sort' => array(
                $this->getSort() => $this->getDirection()
            ),
            'filterquery' => array(),
        );

        if (!($this->getCache()->test($this->getCacheKey()))) {
        $query = $this->getSolr()->createSelect($select);

        $resultset = $this->getSolr()->select($query);
        $data = array();
        foreach ($resultset as $doc) {
            $data['tags'][] = $this->parseResults($doc);
        }
            $this->getCache()->save($data);
        } else {
            $data = $this->getCache()->load($this->getCacheKey());
        }
        return $data;
    }

    /** Parse the documents for field results
     * @access public
     * @param  Solarium_Document_ReadOnly $doc
     * @return array
     */
    public function parseResults(Solarium_Document_ReadOnly $doc)
    {
        $fields = array();
        foreach ($doc as $key => $value) {
            $fields[$key] = $value;
        }
        return $fields;
    }

    /** Get the latest records method
     * @access public
     * @return \Pas_View_Helper_LatestRecords
     */
    public function semanticTags()
    {
        return $this;
    }

    /** Build HTML to return as string
     * @access public
     * @return string
     * @param array $data
     */
    public function buildHtml(array $data)
    {
        $html = '';
        if (array_key_exists('tags',$data)) {
            $html .= '<h3 class="lead ">Semantic tags</h3><ul>';
            $html .= $this->view->partialLoop('partials/database/tags.phtml', $data['tags']);
        }
        $html .= '</ul>';
        return $html;
    }

    /** Magic method to create string
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->buildHtml($this->getResults());
    }
}