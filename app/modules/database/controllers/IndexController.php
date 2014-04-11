<?php
/** Controller for index page for database module
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_IndexController extends Pas_Controller_Action_Admin {
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$this->_helper->_acl->allow('public',NULL);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	}
	/** Setup index page
	*/
	public function indexAction() {
	$content = new Content();
	$this->view->contents = $content->getFrontContent('database');

	$recent = new Logins();
	$this->view->logins = $recent->todayVisitors();

	$form = new SolrForm();
	$form->q->setLabel('Search our database: ');
	$form->setMethod('post');
	$this->view->form = $form;
	$values = $form->getValues();
	if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
	$data = $form->getValues();
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($data);
	}
	}
	}

	function array_cleanup( $array ) {
    $todelete = array('submit','action','controller','module','page','csrf');
		foreach( $array as $key => $value ) {
    foreach($todelete as $match){
    	if($key == $match){
    		unset($array[$key]);
    	}
    }
    }
    return $array;
}
}