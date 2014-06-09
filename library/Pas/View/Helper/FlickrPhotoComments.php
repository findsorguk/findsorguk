<?php
/** A view helper for rendering comments on flickr via the api
 *
 * This view helper can be used for adding comments to the view from the api
 * call for a photo. It also renders the icon.
 *
 * An example of how to use this:
 * <code>
 * <?php
 * echo $this->flickrPhotoComments()->setComments($comments);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @category Pas
 * @package View_Helper
 * @version 1
 * @license GNU
 * @copyright (c) 2014, Daniel Pett
 * @uses Zend_View_Helper_Abstract
 * @example /app/modules/flickr/views/scripts/photos/details.phtml
 */
class Pas_View_Helper_FlickrPhotoComments extends Zend_View_Helper_Abstract
{

    /** The array of comments
     * @access protected
     * @var array
     */
    protected $_comments;

    /** Constant for image dims
     * Not sure why I made this a constant
     *
     */
    const DIMS = 'height="48px" width="48px"';

    /** Get the comments array
     * @access public
     * @return array
     */
    public function getComments() {
        return $this->_comments;
    }

    /** Set the comments array
     * @access public
     * @param array $comments
     * @return \Pas_View_Helper_FlickrPhotoComments
     */
    public function setComments($comments) {
        $this->_comments = $comments;
        return $this;
    }

    /** Build the buddy icon
     * @access public
     * @param object $comment
     * @return string
     */
    public function buildBuddyIcon( object $comment ) {
        $img = '';
        if($comment->iconfarm && $comment->iconserver && $comment->author) {
            $img .= 'http://farm';
            $img .= $comment->iconfarm;
            $img .= '.static.flickr.com/';
            $img .= $comment->iconserver;
            $img .= '/buddyicons/';
            $img .= $comment->author;
            $img .= '.jpg';
        } else {
            $img .= 'http://www.flickr.com/images/buddyicon.jpg';
        }
        return $img;
    }

    /** The to string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->buildHtml( $this->getComments());
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_FlickrPhotoComments
     */
    public function flickrPhotoComments() {
        return $this;
    }

    /** Build the html
     * @access public
     * @param object $comments
     * @return string
     */
    public function buildHtml( object $comments ) {
        $html = '';
        foreach($comments->comment as $comment) {
            $buddyUrl = $this->buildBuddy( $comment );
            $html .= '<li>';
            $html .= '<img src="';
            $html .= $this->buildBuddyIcon( $comment );
            $html .= '" alt="';
            $html .= $this->buildAltTag( $comment );
            $html .= '" ';
            $html .= self::DIMS;
            $html .= ' class="pull-right" />';
            $html .= '<br />';
            $html .= 'Created: ';
            $html .= $this->createAtomDate($c->datecreate);
            $html .= ' by <a href="http://www.flickr.com/photos/';
            $html .= $comment->author;
            $html .= '" title="View ';
            $html .= $comment->authorname;
            $html .= ' on Flickr">';
            $html .= $comment->authorname;
            $html .= '</a><br /><a href="';
            $html .= $comment->permalink;
            $html .= '" title="View the comment in context">Permalink</a>';
            $html .= '</li>';
        }
        return $html;
    }

    /** Create an ATOM date from date string
     * @access public
     * @param string $date
     * @return string
     */
    public function createAtomDate( $date ) {
        $atom = date(DATE_ATOM, $date);
        return $atom;
    }


    /** Build the alt tag
     * @access public
     * @param object $comment
     * @return string
     */
    public function buildAltTag( $comment ) {
        $altTag = '';
        if($comment->iconfarm && $comment->iconserver && $comment->author) {
            $altTag .= $comment->author;
            $altTag .= '\'s buddy icon';
        } else {
            $altTag .= 'The default flickr icon - no buddy photo :-(';
        }
        return $altTag;
    }
}
