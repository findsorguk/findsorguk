<?php
/** Controller for setting up and manipulating staff contacts
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_ContactsController extends Pas_Controller_Action_Admin
{
	const LOGOPATH = './images/logos/';

	const STAFFPATH = './images/staffphotos/';

	protected $_geoPlanet;

	protected $_geocoder;

	protected $_redirectUrl = 'admin/contacts/';
	/** Set up the ACL and contexts
	*/
	public function init() {
	$flosActions = array('index',);
	$this->_helper->_acl->allow('flos',$flosActions);
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_geoPlanet = new Pas_Service_Geo_Geoplanet($this->_helper->config()->webservice->ydnkeys->appid);
	$this->_geocoder =  new Pas_Service_Geo_Coder();
    }
	/** Display the index page
	*/
	public function indexAction(){
	$contacts = new Contacts();
	$this->view->contacts = $contacts->getContacts($this->_getAllParams());
	}

	public function alumniAction(){
	$contacts = new Contacts();
	$this->view->contacts = $contacts->getAlumni($this->_getAllParams());
	}
	/** View a contact's details
	*/
	public function contactAction() {
	if($this->_getParam('format') == ('vcf')) {
	$this->_helper->layout->disableLayout();    //disable layout
	$persons = new Contacts();
	$this->view->persons = $persons->getPersonDetails($this->_getParam('id'));
	} else {
	$staffs = new Contacts();
	$this->view->staffs = $staffs->getPersonDetails($this->_getParam('id'));
	}
	}
	/** Add new staff member
	*/
 	public function addAction(){
	$form = new ContactForm();
	$form->submit->setLabel('Add a new Scheme contact');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$address = $form->getValue('address_1') . ',' . $form->getValue('address_2') . ','
	. $form->getValue('town') . ',' . $form->getValue('county') . ','
	. $form->getValue('postcode') . ', UK';
	$coords = $this->_geocoder->getCoordinates($address);
	if($coords){
		$lat = $coords['lat'];
		$lon = $coords['lon'];
		$place = $this->_geoPlanet->reverseGeoCode($lat,$lon);
		$woeid = $place['woeid'];
	} else {
		$lat = NULL;
		$lon = NULL;
		$woeid = NULL;
	}

	$insertData = array(
	'firstname' => $form->getValue('firstname'),
	'lastname' => $form->getValue('lastname'),
	'role' => $form->getValue('role'),
	'dbaseID' => $form->getValue('dbaseID'),
	'email_one' => $form->getValue('email_one'),
	'email_two' => $form->getValue('email_two'),
	'address_1' => $form->getValue('address_1'),
	'address_2' => $form->getValue('address_2'),
	'region' => $form->getValue('region'),
	'town' => $form->getValue('town'),
	'county' => $form->getValue('county'),
	'postcode' => $form->getValue('postcode'),
	'country' => $form->getValue('country'),
	'identifier' => $form->getValue('identifier'),
	'telephone' => $form->getValue('telephone'),
	'fax' => $form->getValue('fax'),
	'website' => $form->getValue('website'),
	'profile' => $form->getValue('profile'),
	'alumni' => $form->getValue('alumni'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms(),
	'latitude' => $lat,
	'longitude' => $lon,
	'woeid' => $woeid);
	foreach ($insertData as $key => $value) {
	      if (is_null($value) || $value=="") {
	        unset($insertData[$key]);
	      }
	    }
	$contacts = new Contacts();
	$insert = $contacts->insert($insertData);
	$this->_flashMessenger->addMessage('Scheme contact created!');
	$this->_redirect($this->_redirectUrl . 'contact/id/' . $insert);
	} else {
	$form->populate($formData);
	}
	}
	}

	/** Edit a contact's details
	*/
	public function editAction() {
	$form = new ContactForm();
	$form->submit->setLabel('Save');
	$this->view->form = $form;
	if ($this->_request->isPost())  {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$address = $form->getValue('address_1') . ',' . $form->getValue('address_2') . ','
	. $form->getValue('town') . ',' . $form->getValue('county') . ','
	. $form->getValue('postcode') . ', UK';
	$coords = $this->_geocoder->getCoordinates($address);
	if($coords){
		$lat = $coords['lat'];
		$lon = $coords['lon'];
		$place = $this->_geoPlanet->reverseGeoCode($lat,$lon);
		$woeid = $place['woeid'];
	} else {
		$lat = NULL;
		$lon = NULL;
		$woeid = NULL;
	}
	$updateData = array(
	'firstname' => $form->getValue('firstname'),
	'lastname' => $form->getValue('lastname'),
	'role' => $form->getValue('role'),
	'dbaseID' => $form->getValue('dbaseID'),
	'email_one' => $form->getValue('email_one'),
	'email_two' => $form->getValue('email_two'),
	'address_1' => $form->getValue('address_1'),
	'address_2' => $form->getValue('address_2'),
	'town' => $form->getValue('town'),
	'county' => $form->getValue('county'),
	'postcode' => $form->getValue('postcode'),
	//'country' => $form->getValue('country'),
	'identifier' => $form->getValue('identifier'),
	'telephone' => $form->getValue('telephone'),
	'fax' => $form->getValue('fax'),
	'region' => $form->getValue('region'),
	'website' => $form->getValue('website'),
	'profile' => $form->getValue('profile'),
	'alumni' => $form->getValue('alumni'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms(),
	'latitude' => $lat,
	'longitude' => $lon,
	'woeid' => $woeid
	);

	$contacts = new Contacts();
	$where = array();
	$where[] = $contacts->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$insert = $contacts->update($updateData,$where);
	$this->_flashMessenger->addMessage('Contact information for ' . $form->getValue('firstname') . ' '
	. $form->getValue('lastname') . ' updated!');
	$this->_redirect($this->_redirectUrl . 'contact/id/' . $this->_getParam('id'));
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$contacts = new Contacts();
	$contact = $contacts->fetchRow('id='.$id);
	$form->populate($contact->toArray());
	}
	}
	}
	/** Delete a contact
	*/
	public function deleteAction() {
	$this->_flashMessenger->addMessage($this->_noChange);
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$contacts = new Contacts();
	$where = 'id = ' . $id;
	$contacts->delete($where);
	}
	$this->_flashMessenger->addMessage('Contact information deleted! This cannot be undone.');
	$this->_redirect($this->_redirectUrl);
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$contacts = new Contacts();
	$this->view->contact = $contacts->fetchRow('id ='.$id);
	}
	}
	}
	/** provide an avatar for a contact
	*/
	public function avatarAction() {
	$form = new AddStaffPhotoForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();	{
	if ($form->isValid($formData)) {
    $upload = new Zend_File_Transfer_Adapter_Http();
   	$upload->addValidator('NotExists', true,array(self::STAFFPATH));
	if($upload->isValid()) {
	$filename = $form->getValue('image');
	$insertData = array();
	$insertData['image'] = $filename;
	$insertData['updated'] = $this->getTimeForForms();
	$insertData['updatedBy'] = $this->getIdentityForForms();
	foreach ($insertData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($insertData[$key]);
      }
    }
	$original   = self::STAFFPATH . $filename;
	$name       = substr($filename, 0, strrpos($filename, '.'));
	$ext        = '.jpg';
	$converted  = $name . $ext;

	$smallpath  = self::STAFFPATH . 'thumbnails/' . $converted;
	$mediumpath = self::STAFFPATH . 'resized/' . $converted;

	//create medium size
	$phMagick = new phMagick($original, $mediumpath);
	$phMagick->resize(300,0);
	$phMagick->convert();
	/* Zend_Debug::dump($convertsmall);
	Zend_Debug::dump($phMagick);
	exit; */
	$phMagick = new phMagick($original, $smallpath);
	$phMagick->resize(100,0);
	$phMagick->convert();

	$staffs = new Contacts();
	$where = array();
	$where[] = $staffs->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$staffs->update($insertData,$where);
	$upload->receive();
 	$this->_flashMessenger->addMessage('The image has been resized and zoomified!');
	$this->_redirect('/admin/contacts/contact/id/' . $this->_getParam('id'));
	} else {
	$this->_flashMessenger->addMessage('There is a problem with your upload.
	Probably that image exists.');
	$this->view->errors = $upload->getMessages();
	}
	} else {
	$form->populate($formData);
	$this->_flashMessenger->addMessage('Check your form for errors');
	}
	}
	}
	}

	/** Give them a logo
	*/
	public function logoAction() {
	$form = new AddStaffLogoForm();
	$form->details->setLegend('Add a logo: ');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();	{
	if ($form->isValid($formData)) {
    $upload = new Zend_File_Transfer_Adapter_Http();
   	$upload->addValidator('NotExists', true,array(self::LOGOPATH));
	if($upload->isValid()) {
	$filename = $form->getValue('image');
	$insertData = array();
	$insertData['host'] = $filename;
	$insertData['updated'] = $this->getTimeForForms();
	$insertData['updatedBy'] = $this->getIdentityForForms();
	foreach ($insertData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($insertData[$key]);
      }
    }
	$original  = self::LOGOPATH . $filename;
	$name      = substr($filename, 0, strrpos($filename, '.'));
	$ext       = '.jpg';
	$converted = $name.$ext;

	$smallpath  = self::LOGOPATH . 'thumbnails/' . $converted;
	$mediumpath = self::LOGOPATH . 'resized/' . $converted;

	//create medium size
	$phMagick = new phMagick($original, $mediumpath);
	$phMagick->resize(300,0);
	$phMagick->convert();
	/* Zend_Debug::dump($convertsmall);
	Zend_Debug::dump($phMagick);
	exit; */
	$phMagick = new phMagick($original, $smallpath);
	$phMagick->resize(100,0);
	$phMagick->convert();

	$regions = new StaffRegions();
	$where = array();
	$where[] = $regions->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$regions->update($insertData,$where);
	$upload->receive();
 	$this->_flashMessenger->addMessage('The image has been resized and zoomified!');
	$this->_redirect('/admin/contacts/institution/id/' . $this->_getParam('id'));
	} else {
	$this->_flashMessenger->addMessage('There is a problem with your upload.
	Probably that image exists.');
	$this->view->errors = $upload->getMessages();
	}
	} else {
	$form->populate($formData);
	$this->_flashMessenger->addMessage('Check your form for errors');
	}
	}
	}
	}

	}