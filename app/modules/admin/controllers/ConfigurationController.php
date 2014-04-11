<?php
/** Controller viewing the current configuration variables
*
* @category   Pas
* @package    Pas_Controller
* @subpackage Action
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_ConfigurationController extends Pas_Controller_Action_Admin {


	/** Set up the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_config = $this->_helper->config();
	}

	/** Display the index page
	*/
	public function indexAction(){
	}
	
	/** Display the webservice configurations
	 *
	 */
	public function webserviceAction(){
	$this->view->webservice = $this->_config->webservice->toArray();
	}
	
	/** Display the system configurations
	 */
	public function systemAction(){
	$this->view->resources = $this->_config->resources->toArray();
	}

	/** Display the routing configurations
	*/
	public function routingAction(){
	$config = new Zend_Config_Ini('app/config/routes.ini', 'production');
	$this->view->routing = $config->toArray();
	}

	/** Display the ACL config
	 * 
	 */
	public function aclAction(){
	$this->view->acl = $this->_config->acl->toArray();
	}

	/** Display salts used
	 * 
	 */
	public function saltsAction(){
	$this->view->salt = $this->_config->form->salt;
	$this->view->authority = $this->_config->auth->salt;
	}
}
