<?php
/**
 * A view helper for getting the next find from the index
 *
 * This view helper is used for interfacing with the SOLR indexes that
 * run the search engine for the site. It only queries the object core and
 * returns one single record prior to the one you are viewing.
 *
 * To use this view helper is very simple:
 * <code>
 * <?php
 * echo $this->nextFind()->setFindID($id);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @category Pas
 * @package Pas_View_Helper
 * @uses Zend_Config
 * @uses Zend_Cache
 * @uses Solarium_Client
 * @uses Zend_Registry
 * @uses Pas_User_Details
 * @uses Zend_View_Helper_Partial
 * @todo Solr core needs correcting when the names are changed
 *
 */
class Pas_View_Helper_NextFind extends Zend_View_Helper_Abstract
{
    /** The cache object
     * @access protected
     * @var object
     */
    protected $_cache;

    /** The configuration for solr
     * @access protected
     * @var array
     */
    protected $_solrConfig;

    /** The solr core
     * @access protected
     * @var object
     */
    protected $_solr;

    /** The config object
     * @access protected
     * @var object
     */
    protected $_config;

    /** The query to solr
     * @access protected
     * @var object
     */
    protected $_query;

    /** The core to query
     * @access protected
     * @var string
     * @todo update name of core when change made
     */
    protected $_core = 'objects';

    /** The default role
     * @access protected
     * @var string
     */
    protected $_role = null;

    /** Get the cache object
     * @access public
     * @return object
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Get the config
     * @access public
     * @return type
     */
    public function getSolrConfig() {
        $this->_solrConfig = array(
            'adapteroptions' => $this->getConfig()->solr->toArray()
                );
        return $this->_solrConfig;
    }

    /** Get the Solr instance
     * @access public
     * @return object
     */
    public function getSolr() {
        $this->_solr = new Solarium_Client($this->getSolrConfig());
        $loadbalancer = $this->_solr->getPlugin('loadbalancer');
        $master = $this->getConfig()->solr->asgard->toArray();
        $slave  = $this->getConfig()->solr->valhalla->toArray();
        $loadbalancer->addServer('master', $master, 100);
        $loadbalancer->addServer('slave', $slave, 200);
        $loadbalancer->setFailoverEnabled(true);
        return $this->_solr;
    }

    /** Get the config object
     * @access public
     * @return object
     */
    public function getConfig() {
        $this->_config = Zend_Registry::get('config');
        return $this->_config;
    }

    /** Get the user role
     * @access public
     * @return string
     */
    public function getRole() {
        $user = new Pas_User_Details();
        $person = $user->getPerson();
        if ($person) {
        $this->_role = $person->role;
        }
        return $this->_role;
    }

    /** The default ID
     * @access protected
     * @var int
     */
    protected $_findID = 1;

    /** The allowed roles
     * @access protected
     * @var array
     */
    protected $_allowed =  array(
        'fa', 'flos', 'admin',
        'treasure', 'hoard'
    );

    /** The key to use when accessing the cache
     * @access protected
     * @var string
     */
    protected $_key;

    /** The fields to query
     * @access public
     * @var array
     */
    protected $_fields = array('id', 'old_findID', 'objecttype', 'broadperiod');

    /** Get the key for accessing the cache
     * @access public
     * @return string
     */
    public function getKey() {
        $this->_key = md5('nextfind' . $this->getFindID() . $this->getRole());
        return $this->_key;
    }

    /** Get the findID
     * @access public
     * @return int
     */
    public function getFindID() {
        return $this->_findID;
    }

    /** Set the find ID
     * @access public
     * @param int $findID
     * @return \Pas_View_Helper_nextFind
     */
    public function setFindID( $findID) {
        $this->_findID = $findID;
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_nextFind
     */
    public function nextFind() {
        return $this;
    }

    /** The to string method
     * @access public
     * @return the string to the view
     */
    public function __toString() {
        return $this->getSolrData( $this->getFindID() );
    }

    /** Get the data from solr
     * @access public
     * @param int $findID
     * @return string
     */
    public function getSolrData( $findID) {
        if (!($this->getCache()->test($this->getKey()))) {
            $query = 'id:[' . $findID  . ' TO *]';
            $select = array(
                'query'         => $query,
                'filterquery' => array(),
                );
            $select['fields'] = $this->_fields;
            $select['sort'] = array('id' => 'asc');
            $select['start'] = 1;
            $select['rows'] = 1;
            $this->_query = $this->getSolr()->createSelect($select);
            if (!in_array($this->getRole(), $this->_allowed) ||
                    is_null($this->getRole()) ) {
                $this->_query->createFilterQuery('workflow')->setQuery('workflow:[3 TO 4]');
            }

            $this->_resultset = $this->getSolr()->select($this->_query);
            $results = $this->_processResults($this->_resultset);
            $this->getCache()->save($results);
            } else {
                $results = $this->getCache()->load($this->getKey());
            }

            if ($results) {
                $html = $this->view->partial('partials/database/next.phtml',
                        $results['0']);
            } else {
                $html = '';
            }
        return $html;
    }

    /** Process the results from solr to ensure safety
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
            $clean = $processor->cleanData($data, $this->getRole(), $this->_core);
        return $clean;
    }
}