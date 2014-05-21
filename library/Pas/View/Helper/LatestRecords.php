<?php
/**
 * LatestRecords helper
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 2
 * @license http://URL GNU
 * @category Pas
 * @package Pas_View_Helper
 * @uses registry Zend_Registry
 * @uses cache  Zend_Cache
 * @uses client Solarium_Client
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_LatestRecords extends Zend_View_Helper_Abstract
{

    /** The solr object
     *
     * @var object
     */
    protected $_solr;

    /** The solr configuration options
     *
     * @var array
     */
    protected $_solrConfig;

    /** The config object
     *
     * @var object
     */
    protected $_config;

    protected $_cache;

    protected $_allowed =  array('fa','flos','admin','treasure');

    protected $_query = '*:*';

    protected $_fields = 'id,old_findID,objecttype,imagedir,filename,thumbnail,broadperiod,description,workflow';

    protected $_sort = 'created';

    protected $_direction = 'desc';

    protected $_start = 0;

    protected $_limit = 5;

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
     * @param  string                         $query
     * @return \Pas_View_Helper_LatestRecords
     */
    public function setQuery(string $query)
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
     * @param  string                         $fields
     * @return \Pas_View_Helper_LatestRecords
     */
    public function setFields(string $fields)
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
     * @param  type                           $direction
     * @return \Pas_View_Helper_LatestRecords
     */
    public function setDirection(string $direction)
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
     * @param  string                         $sort
     * @return \Pas_View_Helper_LatestRecords
     */
    public function setSort(string $sort)
    {
        $this->_sort = $sort;

        return $this;
    }

    /** Set the start number
     *
     * @param  int                            $start
     * @return \Pas_View_Helper_LatestRecords
     */
    public function setStart(int $start)
    {
        $this->_start = $start;

        return $this;
    }

    /** set the limit
     *
     * @param  int                            $limit
     * @return \Pas_View_Helper_LatestRecords
     */
    public function setLimit(int $limit)
    {
        $this->_limit = $limit;

        return $this;
    }

    /** Get the cache key to save
     *
     * @return string
     */
    public function getCacheKey()
    {
       return md5( $this->getQuery() . $this->getRole() );
    }

    /** Construct options
     *
     */
    public function __construct()
    {
        $this->_cache = Zend_Registry::get('cache');
        $this->_config = Zend_Registry::get('config');
        $this->_solrConfig = array('adapteroptions' => $this->_config->solr->toArray());
        $this->_solr = new Solarium_Client($this->_solrConfig);
    }

    /** Get role from user
     *
     * @return boolean
     */
    public function getRole()
    {
        $user = new Pas_User_Details();
        $person = $user->getPerson();
        if ($person) {
            return $user->getPerson()->role;

        } else {
            return false;

        }
    }

    /** Get the results from Solr
     *
     * @return array
     */
    public function getResults()
    {
        $select = array(
            'query' =>  $this->getQuery(),
            'start' =>  $this->getStart(),
            'rows'  =>  $this->getLimit(),
            'fields'    => array($this->getFields()),
            'sort'          => array($this->getSort() => $this->getDirection()),
            'filterquery' => array(),
            );
        if (!in_array($this->getRole(),$this->_allowed)) {
            $select['filterquery']['workflow'] = array(
                'query' => 'workflow:[3 TO 4]');
        }
        $select['filterquery']['images'] = array('query' => 'thumbnail:[1 TO *]');

        if ( !( $this->_cache->test( $this->getCacheKey() ) ) ) {
            $query = $this->_solr->createSelect( $select );
            $resultset = $this->_solr->select( $query );
            $data = array();
            $data['numberFound'] = $resultset->getNumFound();
            foreach ($resultset as $doc) {
        $data['images'][] = $this->parseResults($doc);
            }
            $this->_cache->save($data);
            } else {

                $data = $this->_cache->load($this->getCacheKey());
            }

    return $data;
    }

    /** Parse the documents for field results
     *
     * @param  type $doc
     * @return type
     */
    public function parseResults($doc)
    {
        $fields = array();
        foreach ($doc as $key => $value) {
            $fields[$key] = $value;

        }

        return $fields;
    }

    /** Get the latest records method
     *
     * @return \Pas_View_Helper_LatestRecords
     */
    public function latestRecords()
    {
        return $this;
    }

    /** Build HTML to return as string
     *
     * @return string|boolean
     */

    public function buildHtml()
    {
        if (array_key_exists( 'images', $this->getResults() )) {
            $html = '<h3>Latest examples recorded with images</h3>';
            $html .= '<p>We have recorded ' . number_format($data['numberFound']);
            $html .= ' examples.</p>';
            $html .= '<div class="row-fluid ">';
            $html .= $this->view->partialLoop('partials/database/imagesPaged.phtml', $data['images']);
            $html .= '</div>';

            return $html;

        } else {
            return false;

        }
    }

    /** Magic method to create string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->buildHtml();
    }

}
