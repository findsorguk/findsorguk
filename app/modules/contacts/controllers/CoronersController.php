<?php
/** Controller for coroner based data
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Contacts_CoronersController extends Pas_Controller_Action_Admin
{
	/** Initialise the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow('public',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$contexts = array('xml','json','kml');
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
 			 ->addContext('kml',array('suffix' => 'kml'))
 			 ->addContext('foaf',array('suffix' => 'foaf'))
			 ->addContext('vcf',array('suffix' => 'vcf'))
			 ->addActionContext('profile',array('xml','json','vcf','foaf'))
			 ->addActionContext('index',$contexts)
             ->initContext();
	}

	/** Set up data for coroners index page
	*/
	public function indexAction() {
	$coroners = new Coroners();
	$coroners =  $coroners->getAll($this->_getAllParams());
		if(in_array($this->_helper->contextSwitch()->getCurrentContext(),array('kml'))) {
			$coroners->setItemCountPerPage(150);
		}
	$this->view->coroners = $coroners;

        }

	/** Render individual coroner profile
	*/
	public function profileAction() {
		if($this->_getParam('id',false)){
			$coroners = new Coroners();
			$this->view->persons = $coroners->getCoronerDetails($this->_getParam('id'));
		} else {
			throw new Pas_Exception_Param($this->_missingParameter);
		}
	}
	/** Render map of the coroners
	*/
	public function mapAction() {
	}

}