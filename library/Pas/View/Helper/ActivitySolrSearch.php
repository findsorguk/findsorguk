<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * LatestRecords helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_ActivitySolrSearch extends Zend_View_Helper_Abstract{
	
	protected $_solr;
	
	protected $_solrConfig;
	
	protected $_config;
	
	protected $_cache;
	
	
	public function __construct(){
		$this->_cache = Zend_Registry::get('rulercache');
		$this->_config = Zend_Registry::get('config');
		$config = $this->_config->solr->toArray();
		$config['path'] = '/solr/';
		$config['core'] = 'beopeople';
		$this->_solrConfig = array('adapteroptions' => $config );
   		$this->_solr = new Solarium_Client($this->_solrConfig);
	}
	
	/**
	 * 
	 */
	public function activitySolrSearch( $q = '*:*', $fields = '*', $start = 0, $limit = 4,  
		$sort = 'created', $direction = 'desc') {
	$select = array(
    'query'         => $q,
    'start'         => $start,
    'rows'          => $limit,
    'fields'        => array($fields),
    'sort'          => array($sort => $direction),
	'filterquery' => array(),
    );
	
	$cachekey = md5($q);
	if (!($this->_cache->test($cachekey))) {
	$query = $this->_solr->createSelect($select);
	$resultset = $this->_solr->select($query);
	$data = array();
	$data['numberFound'] = $resultset->getNumFound();
	foreach($resultset as $doc){
		$fields = array();
	    foreach($doc as $key => $value){
	    	$fields[$key] = $value;
	    }
	    $data['images'][] = $fields;
	}
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load($cachekey);
	}
	return $this->buildHtml($data);
	}
	
	
	public function buildHtml($data){
	if(array_key_exists('images', $data)) {
	$html = '<h3>Number of people assigned</h3>';
	$html .= '<p>We have recorded ' . $data['numberFound'] . ' people.</p>';
	return $html;
	} else {
		return false;
	}
	}
	
}

