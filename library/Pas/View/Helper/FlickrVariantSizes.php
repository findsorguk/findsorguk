<?php
/**
 * A view helper for displaying the various sizes links for flickr photos
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->flickrVariantSizes()->setSizes( $sizes );
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package View
 * @subpackage Helper
 * @version 1
 * @license
 * @example /app/modules/flickr/views/scripts/photos/details.phtml
 */
class Pas_View_Helper_FlickrVariantSizes extends Zend_View_Helper_Abstract
{

    /** An array of sizes
     * @access protected
     * @var array
     */
    protected $_sizes;

    /** Get the sizes array
     * @access public
     * @return array
     */
    public function getSizes() {
        return $this->_sizes;
    }

    /** Set the sizes array
     * @access public
     * @param array $sizes
     * @return \Pas_View_Helper_FlickrVariantSizes
     */
    public function setSizes( array $sizes) {
        $this->_sizes = $sizes;
        return $this;
    }

    /** Build the html
     * @access public
     * @param array $sizes
     * @return type
     */
    public function buildHtml( array $sizes) {
        $sizesNew = array();
        $html = '';
        if(is_array($sizes)) {
        foreach ($sizes as $k) {
            $sizesNew[$k->label] = $k->url;
        }
        $links = array();
        foreach ($sizesNew as $k => $v) {
            $links[] = '<a href="' .  $v . '" title="View different size on flickr">' . $k  . '</a>';
        }
        $html .= implode(' | ', $links);
        }
    return $html;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->buildHtml($this->getSizes());
    }
}