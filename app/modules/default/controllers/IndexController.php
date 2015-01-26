<?php

/** The default controller for the index
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses SolrForm
 * @uses Content
 */
class IndexController extends Pas_Controller_Action_Admin
{

    /** Init the controller
     * @access public
     * @return void
     */
    public function init()
    {

        $this->_helper->acl->allow(null);
    }

    /** The index action
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $content = new Content();
        $this->view->contents = $content->getFrontContent('index');
        $form = new SolrForm();
        $form->setAttrib('class', 'form-inline');
        $this->view->form = $form;
        $form->removeElement('thumbnail');
        $form->removeElement('3D');
        $form->q->removeDecorator('label');
        $form->q->setAttrib('class', 'input-large');
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $params = array_filter($form->getValues());
                unset($params['csrf']);
                $this->getFlash()->addMessage('Your search is complete');
                $this->_helper->Redirector->gotoSimple('results', 'search', 'database', $params);
            } else {
                $form->populate($form->getValues());
            }
        }
    }
}