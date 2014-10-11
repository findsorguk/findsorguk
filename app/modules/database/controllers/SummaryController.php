<?php
/** The coin summary controller.
 * This is used for adding coin summaries for the hoard record.
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2014 Mary Chester-Kadwell
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 */
class Database_SummaryController extends Pas_Controller_Action_Admin
{

    const REDIRECT = '/';

    protected $_form;

    /**
     * @return mixed
     */
    public function getForm()
    {
        $this->_form = new CoinSummaryForm();
        return $this->_form;
    }



    /** Init all the permissions in ACL.
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->deny('public', null);
        $this->_helper->_acl->allow('member', array('index'));
        $this->_helper->_acl->allow('flos', array('add','delete','edit'));
    }

    /** Index action for coin summary
     * @return void
     * @access public
     */
    public function indexAction()
    {
        $this->getFlash()->addMessage('You cannot access the summary index.');
        $this->redirect(self::REDIRECT);
        $this->getResponse()->setHttpResponseCode(301)
            ->setRawHeader('HTTP/1.1 301 Moved Permanently');
    }

    /** Action for adding coin summary
     * @access public
     */
    public function addAction()
    {
        $form = $this->getForm();
        $this->view->form = $form;
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){

        } else {
            $form->populate($this->_request->getPost());
        }
        }

    /** Edit action for coin summary
     */
    public function editAction()
    {
        $this->view->form = $this->getForm();
    }

    /** Delete action for coin summary
     */
    public function deleteAction()
    {

    }
}