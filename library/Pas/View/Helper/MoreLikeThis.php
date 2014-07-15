<?php
/**
 * MoreLikeThis view helper for compiling an html render of 4 objects that are similar to
 * the current one being viewed.
 * @category Pas
 * @package  Pas_View_
 * @subpackage Helper
 * @version  1
 * @copyright Daniel Pett <dpett@britishmuseum.org>
 * @license http://URL GNU
 * @uses Zend_Registry Zend Registry
 * @uses Zend_Cache Zend Cache
 * @uses Zend_Config Zend Config
 * @uses Solarium_Client Solarium client
 * @uses Pas_Solr_MoreLikeThis
 * @uses Pas_View_Helper_Ellipsisstring
 * @uses Pas_View_Helper_Workflow
 * @uses Pas_View_Helper_WorkflowStatus
 * @uses Pas_User_Details
 * @author Daniel Pett
 */
class Pas_View_Helper_MoreLikeThis extends Zend_View_Helper_Abstract
{
    /** The default role
     * @access protected
     * @var string
     */
    protected $_role = 'public';

    /** The Solr instance
     * @access protected
     * @var object
     */
    protected $_solr;

    /** The cache
     * @access protected
     * @var object
     */
    protected $_cache;

    /** The config object
     * @access protected
     * @var object
     */
    protected $_config;

    /** Solr config
     * @access protected
     * @var array
     */
    protected $_solrConfig;

    /** The default query string
     * @access protected
     * @var string
     */
    protected $_query = '*:*';

    /** Get the query
     * @access protected
     * @return string
     */
    public function getQuery() {
        return $this->_query;
    }

    /** Set the query
     * @access public
     * @param  string  $query
     * @return \Pas_View_Helper_MoreLikeThis
     */
    public function setQuery( $query ) {
        $this->_query = $query;
        return $this;
    }

    /** Return the config object
     * @access public
     * @return object
     */
    public function getConfig() {
        $this->_config = Zend_Registry::get('config');
        return $this->_config;
    }

    /** Get the cache object
    /** Construct all the objects
     *
     */
    public function getCache()  {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Get the solr config array
     * @access public
     * @todo might need deprecating as I think this is set elsewhere
     * @return type
     */
    public function getSolrConfig() {
        $this->_solrConfig = $this->_config->solr->toArray();
        return $this->_solrConfig;
    }

    /** Get the Solr class to use
     * @access public
     * @return type
     */
    public function getSolr() {
        $this->_solr = new Pas_Solr_MoreLikeThis();
        return $this->_solr;
    }

    /** Get the user's role
     * @access public
     * @return boolean
     */
    public function getRole()  {
        $user = new Pas_User_Details();
        $person = $user->getPerson();
        if ($person) {
            $this->_role = $person->role;
        }
        return $this->_role;
    }

    /** Set the base string for the key
     * @access protected
     * @var string
     */
    protected $_keyBase = 'mlt';

    /** The key string for the cache
     * @access protected
     * @var string
     */
    protected $_key;

    /** Get the key
     * @access public
     * @return string
     */
    public function getKey() {
        $this->_key = md5( $this->_keyBase . $this->getQuery() . $this->getRole());
        return $this->_key;
    }

    /** Set the class
     * @access public
     * @return \Pas_View_Helper_MoreLikeThis
     */
    public function moreLikeThis() {
        return $this;
    }

    /** Get the data from the solr instance
     * @access public
     * @return boolean
     */
    public function getData() {
       if (!($this->getCache()->test($this->getKey()))) {
           $mlt = $this->getSolr();
           $mlt->setFields(array('objecttype','broadperiod','description','notes'));
           $mlt->setQuery($this->getQuery());
           $solrResponse =  $mlt->executeQuery();
           $this->getCache()->save($solrResponse);

       } else {
           $solrResponse = $this->_cache->load($this->getKey() );
       }
       if ($solrResponse) {
           return $this->buildHtml($solrResponse);
        } else {
            return false;
        }
    }

    /** magic method to render string
     * @access public
     * @return type
     */
    public function __toString() {
        return $this->buildHtml( $this->getData() );
    }

    /** Build the html
     * @access private
     * @param  array  $solrResponse
     * @return string
     */
    private function buildHtml(array $solrResponse) {
        $html ='<div class="row-fluid"><h3>Similar objects</h3>';

        foreach ($solrResponse['results'] as $document) {
            if (($document->thumbnail)) {
                $html .= '<img class="flow img-polaroid" src="/images/thumbnails/';
                $html .= $document->thumbnail .'.jpg"/>';
            } else {
                $html .= '<img class="flow img-circle" src="';
                $html .= $this->view->baseUrl();
                $html .= '/assets/gravatar.png" />';
            }
                $html .= '<div class="caption"><p>Find number: ';
                $html .= '<a href="';
                $html .= $this->view->baseUrl();
                $html .= '/database/artefacts/record/id/';
                $html .= $document->id . '">';
                $html .= $document->old_findID;
                $html .= '</a><br />Object type: ' . $document->objecttype;
                $html .= '<br />Broadperiod: ' . $document->broadperiod;
                $html .= '<br/>';
                $html .= $this->view->ellipsisString()->setString($document->description)->setMax(150);
                $html .= '<br />Workflow: ';
                $html .= $this->view->workflowStatus()->setWorkflow($document->workflow);
                $html .= $this->view->workflow()->setWorkflow($document->workflow);
                $html .= '</p></div>';
                $html .= '</div>';
        }
        $html .= '</div>';
        return $html;
    }
}