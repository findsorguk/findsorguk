<?php

/** Contacts based ajax controller
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @version 1
 * @uses Coroners
 */
class Contacts_AjaxController extends Pas_Controller_Action_Ajax
{

    /** The coroners model
     * @access protected
     * @var \Coroners
     */
    protected $_coroners;

    /** Set up the ACL and layouts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('public', null);
        $this->_helper->layout->disableLayout();
        $this->_coroners = new Coroners();
    }

    /** The index action
     * @access public
     * @return void
     */
    public function indexAction()
    {
        //Magic in view
    }

    /** Get the coroners data for mapping
     * @access public
     * @return void
     */
    public function coronersAction()
    {
        $data = $this->_coroners->getAll($this->getAllParams());
        $details = $data->setItemCountPerPage(150);
        $this->view->coroners = $details;
    }

    /** Museums mapping data
     * @access public
     * @return void
     */
    public function museumsAction()
    {
        $museums = new AccreditedMuseums();
        $this->view->museums = $museums->mapMuseums();
    }
}