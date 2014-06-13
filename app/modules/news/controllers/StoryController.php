<?php
/** 
 * Controller for all the Scheme's news stories
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class News_StoryController extends Pas_Controller_Action_Admin {

    public function init() {
        $this->_helper->_acl->allow('public',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index', array('xml','json'))
                ->initContext();
    }
    
    /** For individual article
    */
    public function indexAction(){
        if($this->_getParam('id',false)){
	$news = new News();
	$this->view->news = $news->getStory($this->_getParam('id'));
	$comments = new Comments();
	$this->view->comments = $comments->getCommentsNews($this->_getParam('id'));
	$form = new CommentFindForm();
	$form->submit->setLabel('Add a new comment');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
	$data = $this->_helper->akismet($form->getValues());
	$data['contentID'] = $this->_getParam('id');
	$data['comment_type'] = 'newsComment';
	$data['comment_approved'] = 'moderation';
	$comments->add($data);
	$this->_flashMessenger->addMessage('Your comment has been entered and will appear shortly!');
	$this->_redirect('/news/stories/article/id/' . $this->_getParam('id'));
	$this->_flashMessenger->addMessage('There are problems with your comment submission');
	$form->populate($form->getValues());
	}
	} else {
	throw new Exception('No parameter on the url string');
	}
    }
}