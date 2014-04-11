<?php
/**
 * A view helper for constructing a flickr image from an array
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_FlickrImage extends Zend_View_Helper_Abstract {
   
	const SIZE_75PX = 's';
    /**
     * Thumbnail, 100px on longest side
     *
     * @link http://www.flickr.com/services/api/misc.urls.html
     * @see buildImgUrl(), getSizes()
     * @var string
    */
    const SIZE_100PX = 't';
    /**
     * Small, 240px on longest side
     *
     * @link http://www.flickr.com/services/api/misc.urls.html
     * @see buildImgUrl(), getSizes()
     * @var string
    */
    const SIZE_240PX = 'm';
    /**
     * Medium, 500px on longest side
     *
     * @link http://www.flickr.com/services/api/misc.urls.html
     * @see buildImgUrl(), getSizes()
     * @var string
    */
    const SIZE_500PX = '-';
    /**
     * Large, 1024px on longest side (only exists for very large original images)
     *
     * @link http://www.flickr.com/services/api/misc.urls.html
     * @see buildImgUrl(), getSizes()
     * @var string
    */
    const SIZE_1024PX = 'b';
    /**
     * Original image, either a jpg, gif or png, depending on source format.
     * Call getSizes() to find out the format.
     *
     * @link http://www.flickr.com/services/api/misc.urls.html
     * @see buildImgUrl(), getSizes()
     * @var string
    */
    const SIZE_ORIGINAL = 'o';
	
 	public function FlickrImage($farm,$server,$id,$secret,$size = self::SIZE_240PX) {
	$type = 'jpg';
	$sizeStr = "_$size";
	$url = sprintf("http://farm%d.static.flickr.com/%d/%s_%s%s.jpg",
	$farm, $server, $id, $secret, $sizeStr);
	return $url;
    }
}