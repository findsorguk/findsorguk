<?php 
/** Controller for manipulating publications data
 * 
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @author     Daniel Pett <dpett@britishmuseum.org>
 * @copyright  Daniel Pett <dpett@britishmuseum.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Content
 * @uses RequestForm
*/
class Publications_IndexController extends Pas_Controller_Action_Admin {

    /** Initialise the ACL, cache and config
     * @access public
     * @return void
     */ 
    public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow(null);
    }
	
    /** Render documents on the index page
     * @access public
     * @return void
     */ 
    public function indexAction() {
        $content = new Content();
        $this->view->contents = $content->getFrontContent('publications');
    }

    /** Handle the requests for publications
    */ 
    public function requestAction() {
        $form = new RequestForm();
        $this->view->form = $form;
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
            if ($form->isValid($form->getValues())) {
                $data = array_filter($form->getValues());
                $cc = array();
                $cc[] = array('email' => $form->getvalue('email'),'name' => $form->getValue('fullname'));
                $this->_helper->mailer($data, 'requestPublication', null, $cc, $cc );
                $this->_flashMessenger->addMessage('Your request has been submitted');
                $this->_redirect('getinvolved/publications/');
            } else {
                $this->_flashMessenger->addMessage('There are problems with your submission');
                $form->populate($form->getValues());
            }
        }
    }
}