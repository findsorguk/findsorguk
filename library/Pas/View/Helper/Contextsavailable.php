<?php
/**
 * A view helper for determining which contexts are available and displaying links 
 * to obtain them
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_Url
 * @uses Zend_View_Helper_Baseurl
 */ 
class Pas_View_Helper_Contextsavailable extends Zend_View_Helper_Abstract {
	
	
	protected $_response = array(
		'atom' 	=> 'application/atom+xml', 
		'rss' 	=> 'application/rss+xml', 
		'json' 	=> 'application/json',
		'vcf' 	=> 'text/v-card',
		'csv' 	=> 'application/csv',
		'rdf' 	=> 'application/rdf+xml',
		'xml' 	=> 'application/xml',
		'midas' => 'application/xml',
		'nuds'	=> 'application/xml',
		'ttl'	=> 'application/x-turtle',
		'n3'	=> 'application/rdf+n3',
		'qrcode'=> 'image/png',
		'zip' 	=> 'application/zip',
        'doc' 	=> 'application/msword',
        'xls' 	=> 'application/vnd.ms-excel',
        'ppt' 	=> 'application/vnd.ms-powerpoint',
		'pdf'	=> 'application/pdf',
        'gif' 	=> 'image/gif',
        'png' 	=> 'image/png',
        'jpeg' 	=> 'image/jpg',
        'jpg' 	=> 'image/jpg',
        'php' 	=> 'text/plain',
		'kml'	=> 'application/vnd.google-earth.kml+xml'
	);
	/** A list of contexts can be turned into urls
	 * 
	 * @param string $contexts
	 */
	public function contextsavailable($contexts) {
	if(sizeof($contexts) > 0) {
	$module = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();	
	$controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();	
	$action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();	
	
	$string = '<div id="contexts" class="row-fluid"><p>This page is available in: ';
	foreach($contexts as $key => $value) {
		
	$url = $this->view->url(array(
	'module' => $module,
	'controller' => $controller, 
	'action' => $action, 
	'format' => $value),null,false);
	$string .= '<a href="' . $url . '" title="Obtain data in ' . $value 
	. ' representation" ';
	if($value === 'kml'){
		$string .= ' rel="nofollow" ';
	}
	$string .=  '>' . $value . '</a> ';	
	if(array_key_exists($value, $this->_response)){
			$this->view->headLink()->appendAlternate($this->view->serverUrl() . $url, $this->_response[$value], 'Alternate representation as ' . $value);
		}
	}
	$string .=' representations.</p></div>';
	echo $string;
	}

	}
}