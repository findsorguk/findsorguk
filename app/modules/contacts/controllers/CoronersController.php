<?php

/** Controller for coroner based data
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version    2
 * @uses Coroners
 *
 */
class Contacts_CoronersController extends Pas_Controller_Action_Admin
{

    /** The coroners model
     * @access protected
     * @var \Coroners
     */
    protected $_coroners;

    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('public', null);
        $contexts = array('xml', 'json', 'kml');
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
            ->addContext('kml', array('suffix' => 'kml'))
            ->addContext('foaf', array('suffix' => 'foaf'))
            ->addContext('vcf', array('suffix' => 'vcf'))
            ->addActionContext('profile', array('xml', 'json', 'vcf', 'foaf'))
            ->addActionContext('index', $contexts)
            ->initContext();
        $this->_coroners = new Coroners();
    }

    /** Set up data for coroners index page
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $coroners = $this->_coroners->getAll($this->getAllParams());
        if (in_array($this->_helper->contextSwitch()->getCurrentContext(), array('kml'))) {
            $this->_coroners->setItemCountPerPage(150);
        }
        $this->view->coroners = $coroners;
    }

    /** Render individual coroner profile
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function profileAction()
    {
        if ($this->getParam('id', false)) {
            $this->view->persons = $this->_coroners->getCoronerDetails($this->getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Render map of the coroners
     * @access public
     * @return void
     */
    public function mapAction()
    {
        //Magic in the view
    }
}