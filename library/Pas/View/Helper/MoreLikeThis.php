<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * MoreLikeThis view helper for compiling an html render of 4 objects that are similar to
 * the current one being viewed.
 * @category Pas
 * @package  Pas_View_
 * @subpackage Helper
 * @version  1
 * @copyright DEJ PETT
 * @author Daniel Pett
 */
class Pas_View_Helper_MoreLikeThis extends Zend_View_Helper_Abstract {

    /** The Solr instance
     *
     * @var object
     */
    protected $_solr;

    /** The cache
     *
     * @var object
     */
    protected $_cache;

    /** The config object
     *
     * @var object
     */
    protected $_config;

    /** Solr config
     *
     * @var array
     */
    protected $_solrConfig;

    /** Construct all the objects
     *
     */
    public function __construct(){
    $this->_cache = Zend_Registry::get('rulercache');
    $this->_config = Zend_Registry::get('config');
    $this->_solrConfig = $this->_config->solr->toArray();
    $this->_solr = new Pas_Solr_MoreLikeThis();
    }

    /**
     * Get the role of the user
     */
    public function getRole(){
    $user = new Pas_User_Details();
    $person = $user->getPerson();
    if($person){
    $this->_role = $person->role;
    } else {
    	return false;
    }
    }
    
    /** Query the solr instance
     *
     * @param string $query
     */
    public function moreLikeThis($query){
    $key = md5('mlt' . $query . $this->getRole());
	if (!($this->_cache->test($key))) {
	$mlt = $this->_solr;
	$mlt->setFields(array('objecttype','broadperiod','description','notes'));
	$mlt->setQuery($query);
	$solrResponse =  $mlt->executeQuery();
	$this->_cache->save($solrResponse);
	} else {
	$solrResponse = $this->_cache->load($key);
	}
    if($solrResponse){
    return $this->buildHtml($solrResponse);
    } else {
    	return false;
    }
    }


    /** Build the HTML response
     *
     * @param array $solrResponse
     */
    private function buildHtml($solrResponse){
    $html ='<div class="row-fluid"><h3>Similar objects</h3>';
    foreach($solrResponse['results'] as $document){
      

   			$html .= '<div class="span3 well">';
   			 if(($document->thumbnail)){
			$html .= '<img class="flow img-polaroid" src="/images/thumbnails/';
			$html .= $document->thumbnail .'.jpg"/>';
   			 } else {
   			 	$html .= '<img class="flow img-circle" src="/assets/gravatar.png" />';
   			 }
			$html .= '<div class="caption"><p>Find number: ';
			$html .= '<a href="' . $this->view->serverUrl() . '/database/artefacts/record/id/'
                . $document->id . '">';
			$html .= $document->old_findID;
			$html .= '</a><br />Object type: ' . $document->objecttype;
			$html .= '<br />Broadperiod: ' . $document->broadperiod;
			$html .= '<br/>' . $this->view->ellipsisstring($document->description,150);
			$html .= '<br />Workflow: ' . $this->view->workflowStatus($document->workflow);
			$html .= $this->view->workflow($document->workflow);
            $html .= '</p></div>';
            $html .= '</div>';
       
    }

    $html .= '</div>';
    return $html;
    }

}


