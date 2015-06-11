<?php
/**
 * Type of moderation for comments
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->commentsModerate();
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @copyright (c) 2014, Daniel Pett
 * @version 1
 * @since 20/5/2014
 * @category Pas
 * @package View
 * @subpackage Helper
 * @uses Comments
 * @see Zend_View_Helper_Abstract
 * @todo check if this is still used
 */
class Pas_View_Helper_CommentsModerate extends Zend_View_Helper_Abstract
{

    /** Get comments from the model
     * @access public
     * @return array of comments
     */
    public function getComments() {
        $comments = new Comments();
        return $comments->getCommentsTypeModerate();
    }

    /** Build html
     * @access public
     * @return string html for view helper
     */
    public function buildHtml(){
        $html = '';
        $data = $this->getComments();
        if (is_array( $data )) {
            $html .= '<li>';
            $html .= '<p>';
            foreach ($data as $d) {
                $html .= $d['comments'];
                $html .= ' ';
                $html .= $d['type'];
                $html .= ' comments';
                $html .= '<br />';
            }
            $html .= '</p>';
            $html .= '</li>';

        }
        return $html;
    }

    /** Function to call
     * @access public
     * @return \Pas_View_Helper_CommentsModerate
     */
    public function commentsModerate() {
        return $this;
    }

    /** To string method
     * @access public
     * @return string The html for the view
     */
    public function __toString() {
        return $this->buildHtml();
    }
}
