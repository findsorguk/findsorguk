<?php
/**
 * A view helper for  creating an image based on political party
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_Politicalparty extends Zend_View_Helper_Abstract{
	
	/** List of valid political parties
	 * @todo needs expanding outside of the major ones
	 * @var unknown_type
	 */
	protected $_conservatives = '/images/logos/conservatives.png';
	protected $_labour = '/images/logos/labour.jpg';
	protected $_libdem = '/images/logos/libdem.jpg';
	/** Initiate a cache
	 */
	protected $_cache;
	/** Set up cache from registry
	 * 
	 */
	public function init() {
	$this->_cache = Zend_Registry::get('cache');
	}
	/** Build the image
	 * 
	 * @param string $image
	 * @param string $party
	 */
	public function buildImage($image, $party) {
		$party = str_replace(' ','_',$party);
		
		list($w, $h, $type, $attr) = getimagesize('./'.$image);
		$string = '<img src="' . $image . '" alt="Party political logo" width="' . $w 
		. '" height="' . $h .'" />';
		return $string;
	}
	/** Determine which image to build based on political party
	 * 
	 * @param string $party
	 */ 
	public function politicalparty($party) {
		if(!is_null($party) || $party != ""){
		switch ($party){
			case($party == 'Labour'):
				$partyImage = $this->buildImage($this->_labour,$party);
				break;
			case($party == 'Conservative'):
				$partyImage = $this->buildImage($this->_conservatives,$party);
				break;
			case($party == 'Liberal Democrat');
				$partyImage = $this->buildImage($this->_libdem,$party);
				break;
			default: 
				$partyImage = NULL;
		}
		return $partyImage;
		} 
	}
	
}

