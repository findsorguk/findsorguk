<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * StatisticsDatabase helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_StatisticsDatabase {
	
	protected $_solr;

    protected $_index;

    protected $_limit;

    protected $_cache;

    protected $_config;

    protected $_solrConfig;

    public function __construct(){
    $this->_config = Zend_Registry::get('config');
    $this->_solrConfig = array('adapteroptions' => $this->_config->solr->toArray());
    $this->_solr = new Solarium_Client($this->_solrConfig);
    }
	
	public function statisticsDatabase() {
	return $this->buildHtml($this->getSolrResults());
	}
	
	private function getSolrResults() {
	$query = $this->_solr->createSelect();
	$query->setRows(0);
	$stats = $query->getStats();
	$stats->createField('quantity');
	$resultset = $this->_solr->select($query);
	$data = $resultset->getStats();
	$stats = array();
	foreach($data as $result){ 
	$stats['total']=  $result->getSum();
	$stats['records'] = $result->getCount();
	} 
	return $stats;
	}
	
	public function buildHtml($data){
	$html = '<div id="totals" class="hero-unit">'. number_format($data['total'])
	 . ' objects within ' .	number_format($data['records']) . ' records.</div>';
	return $html;	
	}
}

