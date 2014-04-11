<?php
/** Controller for displaying byzantine ruler pages with recent examples
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ByzantineCoins_RulersController extends Pas_Controller_Action_Admin {

    protected $_rulers;
    /** Initialise the ACL and contexts
     *
     */
    public function init()  {
    $this->_helper->_acl->allow(null);
    $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
    $this->_helper->contextSwitch()->addActionContext('index', array('xml','json'))
            ->addActionContext('ruler', array('xml','json'))
            ->initContext();
    $this->_rulers = new Rulers();
    }
    /** Setup the index page for rulers
    */
    public function indexAction() {
    $this->view->rulers = $this->_rulers->getRulersByzantineList($this->_getParam('page'));
    }

    /** Get individual ruler page
    */
    public function rulerAction() {
    if($this->_getParam('id',false)){
    $this->view->ruler = $this->_rulers->getRulerProfile((int)$this->_getParam('id'));
    $this->view->id = $this->_getParam('id');
    } else {
        throw new Pas_Exception_Param($this->_missingParameter);
    }
    }


}