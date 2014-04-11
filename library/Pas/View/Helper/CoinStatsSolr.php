<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * CoinStatsSolr helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_CoinStatsSolr extends Zend_View_Helper_Abstract {
	
	protected $_solr;

    protected $_index;

    protected $_limit;

    protected $_cache;

    protected $_config;

    protected $_solrConfig;

    public function __construct(){
    $this->_cache = Zend_Registry::get('cache');
    $this->_config = Zend_Registry::get('config');
    $this->_solrConfig = array('adapteroptions' => $this->_config->solr->toArray());
    $this->_solr = new Solarium_Client($this->_solrConfig);
    }
	
	
	public function coinStatsSolr($denomination) {
	return $this->buildHtml($this->getSolrResults($denomination));
	}
	
	private function getSolrResults($denomination) {
	if (!($this->_cache->test('coinstats' . $denomination))) {
	$select = array(
	    'query'         => 'denomination:' . $denomination,
	    );
	$query = $this->_solr->createSelect($select);
	$stats = $query->getStats();
	$stats->createField('diameter');
	$stats->createField('weight');
	$stats->createField('thickness');
	$stats->createField('quantity');
	$resultset = $this->_solr->select($query);
	$data = $resultset->getStats();
	$statistics = array();
	foreach($data as $result){
	$statistics[$result->getName()] = array(
	'total' => $result->getSum(),
	'records' => $result->getCount(),
	'mean' => $result->getMean(),
	'maxima' => $result->getMax(),
	'minima' => $result->getMin()
	);
	} 
	Zend_Debug::dump($statistics);
	exit;
	$this->_cache->save($statistics);
	} else {
	$statistics = $this->_cache->load('coinstats' . $denomination);
	}

	return $statistics;
	}
	
	public function buildHtml($data){
	$html = '<h3>Statistics for coins recorded</h3>';
	$html .= '<p>This will possibly highlight a lot of mistakes in data entry.</p>';
	foreach($data as $key => $value){
		$html .= '<h4>' . ucfirst($key) . '</h4><ul>';
		if($key != 'quantity'){
			unset($value['total']);
		}
		foreach($value as $k => $v){
			$html .= '<li>' . ucfirst($k) . ': ' . number_format($v,2) . '</li>';
		}
		$html .= '</ul>';
	}
	return $html;
	}
	
}

