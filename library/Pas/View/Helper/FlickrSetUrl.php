<?php
/** A view helper for displaying the flickr set url
 * Really unsure why this is needed! Specific for PAS photos
 *
 * A use case:
 *
 * <code>
 * <?php
 * echo $this->flickrSetUrl()->setId(1);
 * ?>
 * </code>
 * @category Pas
 * @package Pas_View_Helper
 * @version 1
 * @since 5 October 2011
 * @author Daniel Pett
 * @copyright DEJ PETT
 * @license GNU
 */
class Pas_View_Helper_FlickrSetUrl extends Zend_View_Helper_Abstract {

    protected $_base = 'http://flickr.com/photos/finds/sets/';

    protected $_id;

    /** Get the base url for flickr sets
     * @access public
     * @return type
     */
    public function getBase() {
        return $this->_base;
    }

    /** Get the ID
     * @access public
     * @return string
     */
    public function getId() {
        return $this->_id;
    }

    /** Set the set ID for flickr
     * @access public
     * @param string $id
     * @return \Pas_View_Helper_FlickrSetUrl
     */
    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_FlickrSetUrl
     */
    public function flickrSetUrl() {
        return $this;
    }

    /** To String function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->getBase() . $this->getID();
    }
}
