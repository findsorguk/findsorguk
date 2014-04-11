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
class Pas_View_Helper_AmazonDataDumpPublic extends Zend_View_Helper_Abstract {
	
	protected $_S3;
	protected $_awsKey;
	protected $_awsSecret;
	protected $_config;
	protected $_cache;
	
	
	/** Construct the object
	 *
	 */
	public function __construct(){
		$this->_config = Zend_Registry::get('config');
		$this->_awsKey = $this->_config->webservice->amazonS3->accesskey;
		$this->_awsSecret = $this->_config->webservice->amazonS3->secretkey;
		$this->_S3 = new Zend_Service_Amazon_S3($this->_awsKey, $this->_awsSecret);
		$this->_cache = Zend_Registry::get('rulercache');
	}

	public function amazonDataDumpPublic() {
	$key = md5('amazonpublic');
	if (!($this->_cache->test($key))) {
	$s3  = $this->_S3;
	$list = $s3->getObjectsByBucket("findsorguk-publicfinds");
	$data = array();
	foreach($list as $name) {
	$data[] = array(
	'filename' => $name, 
	'properties' => $s3->getInfo("findsorguk-publicfinds/$name"));
	}
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load($key);
	}
	return $this->_buildHtml($data);
	}
	
	protected function _buildHtml($data){
		return $this->view->partialLoop('partials/admin/fileListPublic.phtml', $data);
	}
}

