<?php
/** Controller for displaying Early Medieval coin types page
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Earlymedievalcoins_TypesController extends Pas_Controller_Action_Admin {

	/** Initialise the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow(null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('type', array('xml','json'))
		->initContext();
    }

	/** Internal period number for querying the database
	*/
	protected $_period = '47';


   	/** Set up the index page for early medieval types.
	*/
	public function indexAction() {
	$type = new MedievalTypes();
	$this->view->types = $type->getTypesByPeriod($this->_period,$this->_getParam('page'));
	}


   	/** Set up the individual types
	*/
	public function typeAction() {
	if($this->_getParam('id',false)){
	$this->view->id = $this->_getParam('id');
	$types = new MedievalTypes();
	$this->view->types = $types->getTypeDetails($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}


}