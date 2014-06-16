<?php
/**
 * A view helper for displaying exif tags for a specific photo from the flickr api.
 *
 * An example of use:
 * <code>
 * <?php
 * echo $thos->flickrTagExif()->setExif($exif);
 * ?>
 * </code>
 * 
 * @todo Could be abstracted to a flickr class
 * @version 1
 * @since 7 October 2011
 * @copyright 2014, Daniel Pett
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @category Pas
 * @package Pas_View_Helper
 * @subpackage Abstract
 * @uses Pas_Yql_Oauth
 * @example /app/modules/flickr/views/scripts/photos/details.phtml
 */
class Pas_View_Helper_FlickrTagExif extends Zend_View_Helper_Abstract {

    /** The exif array
     * @access protected
     * @var array
     */
    protected $_exif;

    /** Get the array
     * @access public
     * @return array
     */
    public function getExif() {
        return $this->_exif;
    }

    /** Set the exif array
     * @access public
     * @param array $exif
     * @return \Pas_View_Helper_FlickrTagExif
     */
    public function setExif( array $exif) {
        $this->_exif = $exif;
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_FlickrTagExif
     */
    public function flickrTagExif() {
        return $this;
    }

    /** Build the html string
     * @access public
     * @param array $exif
     * @return string
     */
    public function buildHtml( array $exif) {
        $html = '';
        foreach ($exif as $e) {
            $html .= '<li>' . $e->label . ':' . $e->raw . '</li>';
        }
        return $html;
    }

    /** return the string
     * @access public
     * @return type
     */
    public function __toString() {
        return $this->buildHtml($this->getExif());
    }

}
