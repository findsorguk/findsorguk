<?php
/** A view helper for displaying a flickr licence
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    GNU
 * @version 1
 * @author Daniel Pett
 */
class Pas_View_Helper_FlickrLicense extends Zend_View_Helper_Abstract {
	
	const ALLRIGHTS = 'All Rights Reserved';
	
	const BASECREATIVE = 'http://creativecommons.org/licenses/';
	
	const VIEWLIC = 'View license restrictions';
	
	protected $_cache;
	
	public function __construct(){
	$this->_cache = Zend_Registry::get('cache');
	}
	
	/** Determine license string to display
	 * @param int $license
	 * @return string
	 */
	public function FlickrLicense($license) {
	if (!($this->_cache->test('cclicense' . $license))) {
	switch($license) {
	case 0:
	$licensetype = self::ALLRIGHTS;
	break;
	case 1:
	$licensetype = '<a href="' . self::BASECREATIVE . 'by-nc-sa/2.0/" title="' . self::VIEWLIC . '">Attribution-NonCommercial-ShareAlike License</a>';
	break;
	case 2:
	$licensetype ='<a href="' . self::BASECREATIVE . 'by-nc/2.0/" title="' . self::VIEWLIC . '">Attribution-NonCommercial License</a>';
	break;
	case 3:
	$licensetype = '<a href="' . self::BASECREATIVE . 'by-nc-nd/2.0/" title="' . self::VIEWLIC . '">Attribution-NonCommercial-NoDerivs License</a>';
	break;
	case 4:
	$licensetype = '<a href="' . self::BASECREATIVE . 'by/2.0/" title="' . self::VIEWLIC . '">Attribution License</a>';
	break;
	case 5:
	$licensetype = '<a href="' . self::BASECREATIVE . 'by-sa/2.0/" title="' . self::VIEWLIC . '">Attribution-ShareAlike License</a>';
	break;
	case 6:
	$licensetype = '<a href="' . self::BASECREATIVE . 'by-nd/2.0/" title="' . self::VIEWLIC . '">Attribution-NoDerivs License</a>';
	break;
	case 7: 
	$licensetype =  '<a href="http://www.flickr.com/commons/usage/" title="' . self::VIEWLIC . '">No known copyright restrictions</a>';
	break;
	case 8:
	$lciensetype =  '<a href="http://www.usa.gov/copyright.shtml" title="' . self::VIEWLIC . '">United States Government Work</a>';
	break;
	default:
	$licensetype = self::ALLRIGHTS;
	break;
	}
	$this->_cache->save($licensetype);
	} else {
	$licensetype = $this->_cache->load('cclicense' . $license);
	}
	return $licensetype;
	}
	


}