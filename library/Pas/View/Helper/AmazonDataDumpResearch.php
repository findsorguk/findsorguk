<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * AmazonDataDump helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_AmazonDataDumpResearch extends Zend_View_Helper_Abstract {
	
	protected $_S3;
	protected $_awsKey;
	protected $_awsSecret;
	protected $_config;
	protected $_cache;
	protected $_role;
	protected $_roles = array('admin','research','flos','treasure', 'hero', 'fa');
	
	
	/** Construct the object
	 *
	 */
	public function __construct(){
		$this->_config = Zend_Registry::get('config');
		$this->_awsKey = $this->_config->webservice->amazonS3->accesskey;
		$this->_awsSecret = $this->_config->webservice->amazonS3->secretkey;
		$this->_S3 = new Zend_Service_Amazon_S3($this->_awsKey, $this->_awsSecret);
		$this->_cache = Zend_Registry::get('rulercache');
		$user = new Pas_User_Details();
		$this->_role = $user->getPerson()->role;
	}

	public function amazonDataDumpResearch() {
	if(in_array($this->_role, $this->_roles)){
	$key = md5('amazonresearch');
	if (!($this->_cache->test($key))) {
	$s3  = $this->_S3;
	$list = $s3->getObjectsByBucket("findsorguk-research");
	$data = array();
	foreach($list as $name) {
	$data[] = array(
	'filename' => $name, 
	'properties' => $s3->getInfo("findsorguk-research/$name"));
	}
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load($key);
	}
	return $this->_buildHtml($data);
	} else {
		return false;
	}
	}
	
	protected function _buildHtml($data){
		$html = '<h2>Daily research data dumps of entire database</h2>';
		$html .= '<p>These data are licensed under CC-BY and includes the following fields and objects from workflow stages validation and published. Other stages are not available to your login; this dump is generated at 5am GMT daily.</p>';
		$html .= '<blockquote><p>';
		$html .= 'id, objecttype, broadperiod, periodFromName, periodToName, fromdate, todate, description, notes, workflow, materialTerm, secondaryMaterialTerm, , subsequentActionTerm, discoveryMethod, datefound1, datefound2, TID, rallyName, weight, height, diameter, thickness, length, quantity, finder, identifier, recorder, denominationName, rulerName, mintName, obverseDescription, obverseLegend, reverseDescription, reverseLegend, tribeName, reeceID, cciNumber, mintmark, abcType, categoryTerm, typeTerm, moneyerName, reverseType, regionName, county, district, parish, knownas, gridref, gridSource, fourFigure, easting, northing, latitude, longitude, geohash, coordinates, fourFigureLat, fourFigureLon';
		$html .= '</blockquote></p>';
		$html .=$this->view->partialLoop('partials/admin/fileListResearch.phtml', $data);
		return $html;
	}
}

