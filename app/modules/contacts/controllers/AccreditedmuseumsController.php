<?php

/**
 * Controller for displaying and manipulating accredited museum data
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses AccreditedMuseums
 *
 */
class Contacts_AccreditedMuseumsController extends Pas_Controller_Action_Admin
{

    /** Accredited model
     */
    protected $_accredited;

    /** Initialise the ACL
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('public', null);
        $this->_accredited =  new AccreditedMuseums();
    }

    /** Set up data for museums index page
     * @access public
     * @return void
     */
    public function indexAction()
    {
        //$this->view->museums = $this->_accredited->listMuseums($this->getAllParams());
    }
}
