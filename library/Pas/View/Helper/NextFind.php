<?php
/**
 * NextFind helper
 *
 * A view helper that interfaces with solr and presents a link to the next
 * object in the index. Results are cached and also load balanced.
 * 
 * To use this view helper is very simple:
 * <code>
 * <?php echo $this->nextFind()->setFindID($id);?>
 * </code>
 * 
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @license http://URL GNU
 * @category Pas
 * @package Pas_View_Helper
 * @copyright (c) 2014, Daniel Pett
 * @uses viewHelper Pas_View_Helper
 * @version 1
 * @since 1
 * @uses cache Zend_Cache
 * @uses registry Zend_Registry
 * @uses client Solarium_Client
 * @uses userDetails Pas_User_Details
 * @uses sensitive_fields Pas_Solr_SensitiveFields
 * @uses viewHelper Zend_View_Helper_Partial
 * @todo Swap name of the solr core when changes made
 */
class Pas_View_Helper_NextFind extends Zend_View_Helper_Abstract
{
   
    /** The fields to query
     * @access protected
     * @var array
     */
    protected $_fields =  array('id', 'old_findID', 'objecttype', 'broadperiod');
    /**
     * The cache object
     * @var object
     * @access protected
     */
    protected $_cache;

    /** The solr configuration array
     * @access protected
     * @var array
     */
    protected $_solrConfig;

    /** The solr object
     * @access protected
     * @var object
     */
    protected $_solr;

    /** The config object
     * @access protected
     * @var object
     */
    protected $_config;

    /** The query
     * @access protected
     * @var string
     */
    protected $_query;

    /** The core to query
     * @access protected
     * @var string
     */
    protected $_core = 'beowulf';

    /** Get the configuration from the config.ini file
     * @access public
     * @return array
     */
    public function getSolrConfig() {
        $this->_solrConfig = array(
            'adapteroptions' => $this->getConfig()->solr->toArray()
                );
        return $this->_solrConfig;
    }

    /** Get the solr object and configure
     * @access public
     * @return object
     */
    public function getSolr() {
        $this->_solr = new Solarium_Client($this->_solrConfig);
        $loadbalancer = $this->_solr->getPlugin('loadbalancer');
        $master = $this->getConfig()->solr->master->toArray();
        $slave  = $this->getConfig()->solr->slave->toArray();
        $loadbalancer->addServer('master', $master, 100);
        $loadbalancer->addServer('slave', $slave, 200);
        $loadbalancer->setFailoverEnabled(true);
        return $this->_solr;
    }

    /** Get the query
     * @access public
     * @return object
     */
    public function getQuery() {
        return $this->_query;
    }

    /** Set the core to query
     * @access public
     * @return string
     */
    public function getCore() {
        return $this->_core;
    }

    /** Get the cache object
     * @access public
     * @return object
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Get the config object
     * @access public
     * @return object
     *
     */
    public function getConfig() {
        $this->_config = Zend_Registry::get('config');
        return $this->_config;
    }

    /** Get the allowed roles
     * @access public
     * @return array
     */
    public function getAllowed() {
        return $this->_allowed;
    }

    /** The role by default
     * @access public
     * @var string
     */
    protected $_role = null;

    /** Get the role
     * @access public
     * @return string
     */
    public function getRole(){
        $user = new Pas_User_Details();
        $person = $user->getPerson();
        if($person) {
            $this->_role = $person->role;
        }
        return $this->_role;
    }

    /** Array of allowed roles
     * @access public
     * @var array
     */
    protected $_allowed =  array('fa','flos','admin','treasure');

    /** The default ID
     * @access public
     * @var int
     */
    protected $_findID = 1;

    /** Get the find ID
     * @access public
     * @return int
     */
    public function getFindID() {
        return $this->_findID;
    }

    /** Set the find ID
     * @access public
     * @param int $findID
     * @return \Pas_View_Helper_NextFind
     */
    public function setFindID( int $findID) {
        $this->_findID = $findID;
        return $this;
    }

    /** The cache key
     * @access public
     * @var string
     */
    protected $_key;

    /** Get the key for use with the cache
     * @access public
     * @return string
     */
    public function getKey() {
        $this->_key = md5('nextfind' . $this->getFindID() . $this->getRole());
        return $this->_key;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_NextFind
     */
    public function nextFind() {
        return $this;
    }

    /** To String method
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->getSolrData($this->getFindID());
    }

    /** Get data from solr via the ID
     *
     * This function queries the index for the next available record after
     * this one's id.
     *
     * @access public
     * @param int $findID
     * @return string
     */
    public function getSolrData(int $findID) {
        if (!($this->getCache()->test($this->getKey()))) {
            $query = 'id:[' . $findID . ' TO *]';
            $select = array(
                'query'         => $query,
                'filterquery' => array(),
                );

            $select['fields'] = $this->_fields;
            $select['sort'] = array('id' => 'asc');
            $select['start'] = 1;
            $select['rows'] = 1;
            $this->_query = $this->getSolr()->createSelect($select);
            if (!in_array($this->getRole(), $this->getAllowed())
                    || is_null($this->getRole()) ) {
                $this->_query->createFilterQuery('workflow')
                        ->setQuery('workflow:[3 TO 4]');
                    }
                $this->_resultset = $this->getSolr()->select($this->_query);
                $results = $this->_processResults($this->_resultset);
                $this->getCache()->save($results);
                } else {
                    $results = $this->getCache()->load($key);
                }

                if ($results) {
                    $html = $this->view->partial('partials/database/next.phtml', 
                            $results['0']);
                } else {
                    $html = '';
                }
                return $html;
    }

    /** Process the results and return the array for use in partial
     * @access public
     * @return array
     */
    public function _processResults() {
        $data = array();
        foreach ($this->_resultset as $doc) {
            $fields = array();
            foreach ($doc as $key => $value) {
                $fields[$key] = $value;
            }
            $data[] = $fields;
            }
            $processor = new Pas_Solr_SensitiveFields();
            $clean = $processor->cleanData($data, $this->getRole(), 
                    $this->getCore());
            return $clean;
    }
}
