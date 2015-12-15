<?php

/**
 * A view helper for displaying a count of unpublished comments on news
 *
 * Each news story allows comments to be entered. This view helper allows
 * the comment count to be rendered and a link to be displayed.
 *
 * To use:
 * <code>
 * <?php
 * echo $this->newsCommentCount()->setId(1);
 * ?>
 * </code>
 *
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 */
class Pas_View_Helper_NewsCommentCount extends Zend_View_Helper_Abstract
{

    /** The id number of the news story
     * @access protected
     * @var int
     */
    protected $_id;

    /** Get the id to query
     * @access public
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /** Set the ID to query
     * @access public
     * @param int $id
     * @return \Pas_View_Helper_NewsCommentCount
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /** Get a count of the comments from the model
     * @access public
     * @param integer $id The ID number to query
     * @return int The count of the comments
     */
    public function getCount($id)
    {
        $news = new Comments();
        $comments = $news->getCommentsNews($id);
        return count($comments);
    }

    /** The function tor return
     * @access public
     * @return \Pas_View_Helper_NewsCommentCount
     */
    public function newsCommentCount()
    {
        return $this;
    }

    public function __toString()
    {
        try {
            return $this->buildHtml($this->getCount($this->getId()), $this->getId());
        } catch (Exception $e) {

        }
    }

    /** Create the html for rendering
     * @access public
     * @param int $total
     * @param int $id
     * @return string
     */
    public function buildHtml($total, $id)
    {
        $html = '';
        if ($total > 0) {
            $html .= '<p><strong>Comments: </strong> There are already ';
            $html .= $total;
            $html .= ' comments</p>';
        }
        return $html;
    }
}