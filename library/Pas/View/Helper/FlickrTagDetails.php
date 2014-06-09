<?php
/**
 * A view helper for displaying tags for a specific photo from the flickr api.
 *
 * An example of use:
 * <code>
 * <?php
 * echo $thos->flickrTagDetails()->setTags($tags);
 * ?>
 * </code>
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
class Pas_View_Helper_FlickrTagDetails extends Zend_View_Helper_Abstract {

    /** The tags array
     * @access protected
     * @var array
     */
    protected $_tags;

    /** Get the tags array
     * @access public
     * @return array
     */
    public function getTags() {
        return $this->_tags;
    }

    /** Set the array of tags
     * @access public
     * @param array $tags
     * @return \Pas_View_Helper_FlickrTagDetails
     */
    public function setTags( array $tags) {
        $this->_tags = $tags;
        return $this;
    }

    /** Get the function to return
     * @access public
     * @return \Pas_View_Helper_FlickrTagDetails
     */
    public function flickrTagDetails() {
        return $this;
    }

    /** Build the html
     * @access public
     * @param array $tags
     * @return string
     */
    public function buildHtml(array $tags) {
        $tagsNew = array();
        $html = '';
        foreach ($tags as $t) {
            if (is_object($t)) {
                $data = '<a title="View all photos we have tagged as ';
                $data .= $t->content;
                $data .= '" href="';
                $data .= $this->view->url(array(
                    'module' => 'flickr',
                    'controller' => 'photos',
                    'action' => 'tagged',
                    'as' => $t->content),'default',true);
                $data .= '">';
                $data .= $t->content;
                $data .= '</a>';
                $tagsNew[] =  $data;
            }
        }
        $html .= implode(', ', $tagsNew);
        return $html;
    }

    /** The string of html to return
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->buildHtml($this->getTags());
    }
}
