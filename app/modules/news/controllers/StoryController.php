<?php

/**  Controller for all the Scheme's news stories
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses News
 * @uses Comments
 * @uses CommentFindForm
 */
class News_StoryController extends Pas_Controller_Action_Admin
{

    /** The init controller
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('public', null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()
            ->setAutoDisableLayout(true)->addActionContext('index', array('xml', 'json'))
            ->initContext();
    }

    /** For individual article
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function indexAction()
    {
        if ($this->getParam('id', false)) {
            $news = new News();
            $this->view->news = $news->getStory($this->getParam('id'));
            $comments = new Comments();
            $this->view->comments = $comments->getCommentsNews($this->getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}