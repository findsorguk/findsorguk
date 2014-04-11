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
class Pas_View_Helper_LatestRecords extends Zend_View_Helper_Abstract{

	protected $_solr;

	protected $_solrConfig;

	protected $_config;

	protected $_cache;

	protected $_allowed =  array('fa','flos','admin','treasure');

	public function __construct(){
        $this->_cache = Zend_Registry::get('cache');
        $this->_config = Zend_Registry::get('config');
        $this->_solrConfig = array('adapteroptions' => $this->_config->solr->toArray());
        $this->_solr = new Solarium_Client($this->_solrConfig);
	}

	public function getRole(){
	$user = new Pas_User_Details();
	$person = $user->getPerson();
	if($person){
	return $user->getPerson()->role;
	} else {
		return false;
	}
	}
	/**
	 *
	 */
	public function latestRecords( $q = '*:*',
        $fields = 'id,old_findID,objecttype,imagedir,filename,thumbnail,broadperiod,description,workflow',
                $start = 0, $limit = 5,
		$sort = 'created', $direction = 'desc') {
	$select = array(
        'query'         => $q,
        'start'         => $start,
        'rows'          => $limit,
        'fields'        => array($fields),
        'sort'          => array($sort => $direction),
	'filterquery' => array(),
        );
	if(!in_array($this->getRole(),$this->_allowed)) {
	$select['filterquery']['workflow'] = array(
            'query' => 'workflow:[3 TO 4]'
        );
	}
	$select['filterquery']['images'] = array(
            'query' => 'thumbnail:[1 TO *]'
        );
	$cachekey = md5($q . $this->getRole());
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
	$html = '<h3>Latest examples recorded with images</h3>';
	$html .= '<p>We have recorded ' . number_format($data['numberFound'])
                . ' examples.</p>';
	$html .= '<div class="row-fluid ">';
	$html .= $this->view->partialLoop('partials/database/imagesPaged.phtml',
                $data['images']);
	$html .= '</div>';
	return $html;
	} else {
		return false;
	}
	}

}

