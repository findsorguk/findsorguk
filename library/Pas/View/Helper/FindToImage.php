<?php 
/**
 * Produce the finds to image html, might become obsolete when the solr comes online
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @todo add logging for missing images
 * @todo add caching
 * @todo fix the !file exists bit, it is wrong!
 */
class Pas_View_Helper_FindToImage extends Zend_View_Helper_Abstract {

	/** Build html and return it
	 * 
	 * @param array $imagedata
	 */
	public function buildHtml($imagedata){
	$image = '';
	foreach($imagedata as $data) {
	if(!is_null($data['i'])) { 
	$file = './images/thumbnails/'.$data['i'].'.jpg';
	if(file_exists($file)){
	list($w, $h, $type, $attr) = getimagesize($file);
	$image .= '<a href="/' . $data['imagedir'] . 'medium/' . strtolower($data['f']) 
	. '" rel="lightbox" title="Medium sized image of: ' . $data['old_findID'] . ' a ' 
	. $data['broadperiod'] . ' ' . $data['objecttype'] . '"><img src="' . $this->view->baseUrl() 
	. '/images/thumbnails/' . $data['i'] . '.jpg" class="tmb" width="' . $w . '" height="' . $h 
	. '" alt="' . ucfirst($data['objecttype']) 
	. '" rel="license" resource="http://creativecommons.org/licenses/by/2.0/"/></a>';
	echo $image;	
	} elseif(!file_exists($file)) {
	$location = './' . $data['imagedir'] . $data['f'];
	$phMagick = new phMagick($location, $file);
	$phMagick->resize(100,0);
	$phMagick->convert();
	} else {
	echo '<p>Image unavailable.</p>';
	}
	} 
	}
	}

	/** Look up the find to image and return it
	 * 
	 * @param integer $id The image ID number
	 */
	public function FindToImage($id) {
	$finds = new Finds();
	$imageData = $finds->getImageToFind($id);
	return $this->buildHtml($imageData);
	}

}