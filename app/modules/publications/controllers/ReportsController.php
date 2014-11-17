<?php

/** Controller for all Annual and Treasure reports
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses  Content
 * @uses  Zend_Gdata_Docs
 * @uses  Zend_Gdata_ClientLogin
 * @uses  Zend_Gdata_Docs_Query
 *
 */
class Publications_ReportsController extends Pas_Controller_Action_Admin
{

    /** The content model
     * @access protected
     * @var \Content
     */
    protected $_content;

    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->acl->allow('public', null);
        $this->_content = new Content();

    }

    /** Nothing on the index page
     * @access public
     * @return void
     */
    public function indexAction()
    {
        //Magic in view
    }

    /** Render annual report pages
     * @access public
     * @return void
     */
    public function annualAction()
    {
        //Magic in view
        $content = new Content();
        $this->view->contents = $content->getContent('reports', $this->_getParam('slug'));
    }

    /** Render treasure report pages
     * @access public
     * @return void
     */
    public function treasureAction()
    {
        //Magic in view
        $content = new Content();
        $this->view->contents = $content->getContent('treports', $this->_getParam('slug'));
    }
}