<?php
/** Controller for TVC dates and display of data
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @uses TvcDates
 * @uses TvcForm
 * @uses TvcDatesToCases
 * @uses Pas_Exception_Param
*/
class Treasure_TvcController extends Pas_Controller_Action_Admin {

    /** The TVC model for dates
     * @access protected
     * @var \TvcDates
     */
    protected $_tvc;

    /** The redirect
     * @access protected
     * @var string
     */
    protected $_redirect;

    /** The init function
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->acl->allow('public',array('index','details'));
        $this->_helper->acl->allow(array('treasure','admin'),null);
        $this->_redirect = $this->view->url(
                array(
                    'module' => 'treasure',
                    'controller' => 'tvc'
                    ),null,true);
        
    }

    /** The index action
     * @access public
     * @return void
     */
    public function indexAction(){
        $this->view->tvcdates = $this->_tvc->listDates($this->_getParam('page'));
    }

    /** The details action
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function detailsAction() {
        if($this->_getParam('id',false)){
            $id = $this->_getParam('id');
            $this->view->details = $this->_tvc->getDetails($id);
            $this->view->images = $this->_tvc->getImages($id);
            $tvccases = new TvcDatesToCases();
            $this->view->cases = $tvccases->listCases($id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** The add action
     * @access public
     * @return void
     */
    public function addAction(){
        $form = new TVCForm();
        $form->submit->setLabel('Add TVC date');
        $this->view->form = $form;
        if ($this->_request->isPost()
                && $form->isValid($this->_request->getPost())) {
            $data = $form->getValues();
            $provisionals = new TvcDates();
            $provisionals->add($data);
            $this->redirect($this->_redirect);
            $this->getFlash()->addMessage('A new provisional value has been added.');
        } else {
            $form->populate($this->_request->getPost());
        }
    }

}