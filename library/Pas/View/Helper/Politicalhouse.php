<?php
/**
 * A view helper for displaying an image based on political house assigned to
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_Politicalhouse extends Zend_View_Helper_Abstract{
	/** List of valid political houses
	 */	
	protected $_commons = '/images/logos/commons.jpg';
	protected $_lords = '/images/logos/lords.jpg';
	/** Set up the cache object
	 */
	protected $_cache;
	
	public function __construct() {
	$this->_cache = Zend_Registry::get('cache');
	}
	/** Build the image
	 * 
	 * @param string $image
	 * @param string $house
	 */	
	public function buildImage($image,$house) {
	list($w, $h, $type, $attr) = getimagesize('./' . $image);
	$string = '<img src="' . $image . '" alt="Political house logo" width="' . $w 
	. '" height="' . $h .'" />';
	return $string;
	}
	/** Build the correct image based on house 
	 * 
	 * @param string $house
	 */
	public function politicalhouse($house) {
	if (!($this->_cache->test('house' . $house))) {
		if(!is_null($house) || $house != ""){
		switch ($house){
		case($house == '1'):
			$houseImage = $this->buildImage($this->_commons,$house);
		break;
		case($house == '2'):
			$houseImage = $this->buildImage($this->_lords,$house);
		break;
		default: 
			$houseImage = NULL;
		}
		$this->_cache->save($houseImage);
	} 
	} else {
	$houseImage = $this->_cache->load('house' . $house);
	}
	return $houseImage; 
	}
	
}

