<?php
/** Controller for manipulating the archaeological context data
 *
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2014 Mary Chester-Kadwell
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Finds
 * @uses Findspots
 * @uses Archaeology
 * @uses Publications
 * @uses HoardForm
 */
class Database_ArchaeologyController extends Pas_Controller_Action_Admin {

    /** The archaeological context model
     * @access protected
     * @var \Archaeology
     */
    protected $_archaeology;

    /** The archaeological context form
     * @access protected
     * @var \Archaeology
     */
    protected $_archaeologyForm;

    /** Base Url redirect
     *
     */
    const REDIRECT = '/database/hoards/';

    public function getArchaeologyForm() {
        $this->_archaeologyForm = new ArchaeologyForm();
        return $this->_archaeologyForm;
    }

    /** Set up the ACL access and appid from config
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->deny('public', null);
        $this->_helper->_acl->allow('member', array('index'));
        $this->_helper->_acl->allow('flos', array('add','delete','edit'));
        $this->_archaeology = new Archaeology();
    }

    /** The index page with no root access
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->getFlash()->addMessage('You cannot access the archaeological context index.');
        $this->redirect(self::REDIRECT);
        $this->getResponse()->setHttpResponseCode(301)
            ->setRawHeader('HTTP/1.1 301 Moved Permanently');
    }

    /** Add a new archaeological context
     * @return void
     * @access public
     * @throws Exception
     * @throws Pas_Exception_Param
     */
    public function addAction() {
        $form = $this->getArchaeologyForm();
        $form->submit->setLabel('Add archaeological context');
        $this->view->form = $form;

    }

    /** Edit the archaeological context
     * @return void
     * @access public
     * @throws Pas_Exception_Param
     */
    public function editAction(){
        $form = $this->getArchaeologyForm();
        $form->submit->setLabel('Add archaeological context');
        $this->view->form = $form;
    }

    /** Delete the archaeological context
     * @return void
     * @access public
     *
     */
    public function deleteAction() {

    }

}