<?php

/**
 * Model for manipulating comments added by users
 *
 * An example of code:
 * <code>
 * <?php
 * $model = new Comments();
 * $data = $model->getFindComments($id);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo add some caching to model
 * @example /app/modules/admin/controllers/CommentsController.php
 */
class Comments extends Pas_Db_Table_Abstract
{

    protected $_name = 'comments';

    protected $_primary = 'id';

    /** Get comments by id
     * @param int $id
     * @return array
     */
    public function getFindComments($id)
    {
        $comments = $this->getAdapter();
        $select = $comments->select()
            ->from($this->_name, array(
                $this->_primary, 'df' => 'DATE_FORMAT(comments.created,"%T on the %D %M %Y")',
                'comment_author', 'comment_author_url', 'comment_content',
                'comment_author_email'
            ))
            ->joinLeft('finds', 'finds.id = comments.contentID', array())
            ->where('finds.id = ?', (int)$id)
            ->where('comments.comment_type  = ?', 'findComment')
            ->where('comments.comment_approved = ?', 'approved')
            ->order('comments.created ASC');
        return $comments->fetchAll($select);
    }

    /** Get comments by id on news articles
     * @access public
     * @param int $id
     * @todo remove date formating and put into view?
     * @return array
     */
    public function getCommentsNews($id)
    {
        if (!$data = $this->_cache->load('newscomments' . $id)) {
            $comments = $this->getAdapter();
            $select = $comments->select()
                ->from($this->_name, array(
                    $this->_primary, 'df' => 'DATE_FORMAT(comments.created,"%T on the  %D %M %Y")',
                    'comment_author', 'comment_author_url', 'comment_content',
                    'comment_author_email'))
                ->joinLeft('finds', 'finds.id = comments.contentID', array())
                ->where('finds.id = ?', (int)$id)
                ->where('comments.comment_type  = ?', 'newsComment')
                ->where('comments.comment_approved = ?', 'approved')
                ->order('comments.created ASC');
            $data = $comments->fetchAll($select);
            $this->_cache->save($data, 'newscomments' . $id);
        }
        return $data;
    }

    /** Get comments list
     * @param array $params
     * @param integer $userid
     * @todo perhaps insert switch on approval status?
     * @todo remove date formating and put into view?
     * @return array
     */
    public function getComments(array $params, $userID = null)
    {
        $comments = $this->getAdapter();
        $select = $comments->select()
            ->from($this->_name, array(
                $this->_primary, 'df' => 'DATE_FORMAT(comments.created,"%T @ %D %M %Y")',
                'comment_author', 'comment_author_url', 'comment_content',
                'comment_approved', 'user_ip', 'comment_author_email',
                'comment_type', 'contentID', 'user_agent',
                'commentID' => 'id'
            ))
            ->joinLeft('finds', 'finds.id = comments.contentID',
                array(
                    'id', 'old_findID', 'broadperiod',
                    'objecttype', 'workflow' => 'secwfstage'
                ))
            ->order('comments.created DESC');
        if (isset($params['approval']) && $params['approval'] == 'spam') {
            $select->where('comments.commentStatus = ?', (string)'spam');
        }
        if (isset($params['approval']) && $params['approval'] == 'approved') {
            $select->where('comments.comment_approved = ?', (string)'approved');
        }
        if (isset($params['approval']) && $params['approval'] == 'moderation') {
            $select->where('comments.comment_approved = ?', (string)'moderation')
                ->where('comments.commentStatus != ?', (string)'spam');
        }
        if (isset($userID)) {
            $select->where('comments.createdBy = ?', (int)$userID);
        }
        $data = $comments->fetchAll($select);
        $paginator = Zend_Paginator::factory($data);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        $paginator->setCache($this->_cache);
        if (isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber($params['page']);
        }
        return $paginator;
    }

    /** Get comments by id
     * @access public@
     * @param int $id
     * @todo remove date formating and put into view?
     * @todo change to fetchrow?
     * @return array
     */
    protected function getComment($id)
    {
        $comments = $this->getAdapter();
        $select = $comments->select()
            ->from($this->_name)
            ->where('id = ?', (int)$id);
        return $comments->fetchAll($select);
    }

    /** Get comments by userid and level of approval
     * @access public
     * @param integer $userid
     * @param integer $page
     * @param integer $approval
     * @todo perhaps insert switch on approval status?
     * @todo remove date formating and put into view?
     * @return array
     */

    public function getCommentsOnMyRecords($userid, $page, $approval)
    {
        $comments = $this->getAdapter();
        $select = $comments->select()
            ->from($this->_name, array(
                $this->_primary, 'df' => 'DATE_FORMAT(comments.created,"%T @ %D %M %Y")',
                'comment_author', 'comment_author_url', 'comment_content',
                'comment_approved', 'user_ip', 'comment_author_email'
            ))
            ->joinLeft('finds', 'finds.id = comments.contentID',
                array(
                    'id', 'old_findID', 'broadperiod',
                    'objecttype'
                ))
            ->where('finds.createdBy = ?', (int)$userid)
            ->where('comments.comment_type = ?', 'findComment');
        if (isset($approval) && $approval == 'spam') {
            $select->where('comments.comment_approved = ?', (string)'spam');
        }
        if (isset($approval) && $approval == 'approved') {
            $select->where('comments.comment_approved = ?', (string)'approved');
        }
        if (isset($approval) && $approval == 'moderation') {
            $select->where('comments.comment_approved = ?', (string)'moderation');
        }
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        $paginator->setCache($this->_cache);
        if (isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber($page);
        }
        return $paginator;
    }

    /** Get comments on finds records
     * @access public
     * @param int $page
     * @todo remove date formating and put into view?
     * @return array
     */
    public function getCommentsToFinds($page)
    {
        $comments = $this->getAdapter();
        $select = $comments->select()
            ->from($this->_name, array(
                $this->_primary, 'df' => 'DATE_FORMAT(comments.created,"%T @ %D %M %Y")',
                'comment_author', 'comment_author_url', 'comment_content',
                'comment_approved', 'user_IP', 'comment_author_email',
                'comment_type', 'updated', 'created'
            ))
            ->joinLeft('finds', 'finds.id = comments.contentID',
                array(
                    'id', 'old_findID', 'broadperiod',
                    'objecttype', 'workflow' => 'secwfstage'
                ))
            ->joinLeft('finds_images', 'finds.secuid = finds_images.find_id',
                array())
            ->joinLeft('slides', 'slides.secuid = finds_images.image_id',
                array('i' => 'imageID', 'f' => 'filename'))
            ->joinLeft(array('u' => 'users'), 'slides.createdBy = u.id',
                array('imagedir'))
            ->joinLeft('findspots', 'finds.secuid = findspots.findID',
                array('county'))
            ->where('comment_type = ?', 'findComment')
            ->where('comment_approved = ?', (string)'approved')
            ->order('created DESC,finds.id ')
            ->group('comments.id');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        $paginator->setCache($this->_cache);
        if (isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber($page);
        }
        return $paginator;
    }
}
