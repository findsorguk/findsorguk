<?php
/**
 * A view helper for constructing a flickr image from an array
 *
 * A view helper that can take the parameters and return a url for the
 * different size of image available.
 *
 * An example of use:
 * <code>
 * <?php
 * echo $this->flickrImage()
 * ->setFarm($this->photos->photoset->farm)
 * ->setServer($this->photos->photoset->server)
 * ->setId($this->photos->photoset->primary)
 * ->setSecret($this->photos->photoset->secret)
 * ->setSize('b');
 * ?>
 * </code>
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @example /app/modules/flickr/views/scripts/photos/sets.phtml
 */
class Pas_View_Helper_FlickrImage extends Zend_View_Helper_Abstract
{
    /** Array of sizes
     * @access protected
     * @var array
     */
    protected $_sizes = array(
        self::SIZE_75PX,
        self::SIZE_100PX,
        self::SIZE_240PX,
        self::SIZE_500PX,
        self::SIZE_1024PX,
        self::SIZE_ORIGINAL
            );

    /**
     * Thumbnail, 75px on longest side
     *
     * @link http://www.flickr.com/services/api/misc.urls.html
     * @see buildImgUrl(), getSizes()
     * @var string
    */
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

    /** The farm number
     * @access protected
     * @var int
     */
    protected $_farm;

    /** The server number
     * @access public
     * @var int
     */
    protected $_server;

    /** The ID of the photo
     *
     * @var type
     */
    protected $_id;

    /** The photo secret
     * @access protected
     * @var int
     */
    protected $_secret;

    /** The default size
     * @access protected
     * @var type
     */
    protected $_size = 'm';

    /** Get the requested size
     * @access public
     * @return string
     */
    public function getSize() {
        return '_' . $this->_size;
    }

    /** Set the size to query
     * @access public
     * @param string $size
     * @return \Pas_View_Helper_FlickrImage
     */
    public function setSize($size) {
        if(in_array($size, $this->_sizes)) {
            $this->_size = $size;
        }
        return $this;
    }

    /** Get the image farm number
     * @access public
     * @return int
     */
    public function getFarm() {
        return $this->_farm;
    }

    /** Get the image server number
     * @access public
     * @return int
     */
    public function getServer() {
        return $this->_server;
    }

    /** Get the image ID
     * @access public
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

    /** Get the image secret
     * @access public
     * @return int
     */
    public function getSecret() {
        return $this->_secret;
    }

    /** Set the farm number
     * @access public
     * @param int $farm
     * @return \Pas_View_Helper_FlickrImage
     */
    public function setFarm($farm) {
        $this->_farm = $farm;
        return $this;
    }

    /** Set the server
     * @access public
     * @param int $server
     * @return \Pas_View_Helper_FlickrImage
     */
    public function setServer($server) {
        $this->_server = $server;
        return $this;
    }

    /** Set the ID number
     * @access public
     * @param int $id
     * @return \Pas_View_Helper_FlickrImage
     */
    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    /** Set the secret
     * @access public
     * @param int $secret
     * @return \Pas_View_Helper_FlickrImage
     */
    public function setSecret($secret) {
        $this->_secret = $secret;
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_FlickrImage
     */
    public function flickrImage(){
        return $this;
    }

    /** The url to return as a string
     * @access public
     * @return string
     */
    public function __toString() {
        $url = sprintf(
                "http://farm%d.static.flickr.com/%d/%s_%s%s.jpg",
                $this->getFarm(),
                $this->getServer(),
                $this->getId(),
                $this->getSecret(),
                $this->getSize()
                );
        return $url;
    }
}
