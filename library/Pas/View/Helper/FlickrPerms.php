<?php
/**
 * A view helper for showing the permissions for a photo
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->flickrPerms()->setPerms($perms);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Pas_View_Helper
 * @version 1
 * @license
 * @example /app/modules/flickr/views/scripts/photos/details.phtml
 */
class Pas_View_Helper_FlickrPerms {

    /** Permissions array
     * @access protected
     * @var array
     */
    protected $_perms;

    /** Get the aray of permissions
     * @access public
     * @return array
     */
    public function getPerms() {
        return $this->_perms;
    }

    /** Set the array of permissions
     * @access public
     * @param array $perms
     * @return \Pas_View_Helper_FlickrPerms
     */
    public function setPerms(array $perms) {
        $this->_perms = $perms;
        return $this;
    }

    /** the function to return
     * @access public
     * @return \Pas_View_Helper_FlickrPerms
     */
    public function flickrPerms() {
        return $this;
    }

    /** Build html string
     * @access public
     * @param array $perms
     * @return string
     */
    public function buildHtml( array $perms) {
        $html = '';
        if(is_array($perms)) {
            foreach ($perms as $k => $v) {
                if ($v == 1) {
                    $html .= ucfirst(str_replace('can','',$k)) . ' ';
                }
            }
        }
        return $html;
    }

    /** To string
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->buildHtml($this->getPerms());
    }

}
