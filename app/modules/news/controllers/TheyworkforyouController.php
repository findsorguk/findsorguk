<?php

/**  Controller for accessing they work for you based news
 *
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version    1.1
 * @since      1/2/2012
 */
class News_TheyworkforyouController extends Pas_Controller_Action_Admin
{

    /** Initialise contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow(null);
    }

    /** Get the index page and results for PAS search of twfy
     * @uses Pas_Twfy_Hansard
     * @return void
     */
    public function indexAction()
    {
        $this->getResponse()->setHttpResponseCode(410)->setRawHeader('HTTP/1.1 Gone');
        $this->redirect('error/error.phtml');
        $this->getFlash()->addMessage('Due to MySociety API charges, this service is no longer available.');
    }

    /** Get data for a MP
     * @access public
     * @uses Pas_Twfy_Person
     * @throws Pas_Exception_Param
     * @return void
     */
    public function mpAction()
    {
    }

    /** Get the finds within a consituency
     * @uses Pas_Twfy_Geometry
     * @throws Pas_Exception_Param
     */
    public function findsAction()
    {
    }

    /** Get a list of constituencies
     * @uses Pas_Twfy_Constituencies
     * @access public
     * @return void
     */
    public function constituenciesAction()
    {
    }

    /** get a list of members of parliament
     * @access public
     */
    public function membersAction()
    {
    }

    /** Get an MP based on a postcode
     * @access public
     * @return void
     */
    public function findmympAction()
    {
    }
}