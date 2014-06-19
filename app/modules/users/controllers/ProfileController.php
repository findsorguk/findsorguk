<?php
/** Controller for manipulating user profile details
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Users_ProfileController extends Pas_Controller_Action_Admin {

	protected $_gecoder;

	const LOGOPATH = './images/logos/';

	const PROFILEPATH = './assets/staffphotos/';

	/** Set up the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow('flos',null);
	$this->_helper->_acl->allow('fa',null);
 	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_geocoder = new Pas_Service_Geo_Coder();
	}
	
	
	/** No access to the index page
	*/
	public function indexAction() {
	return $this->_redirect('/users/');
	}
	
	
	/** Edit staff profile
	*/
	public function editAction(){
	$form = new ContactForm();
	$form->submit->setLabel('Save');
	$form->removeElement('role');
	$form->removeElement('alumni');
	$form->removeElement('identifier');
	$form->removeElement('role');
	$form->removeElement('dbaseID');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)){
	$address = $form->getValue('address_1').',' . $form->getValue('address_2') . ','
	. $form->getValue('town') . ',' . $form->getValue('county')
	. ','.$form->getValue('postcode') . ', UK';
	$coords = $this->_geocoder->getCoordinates($address);
	if($coords){
		$lat = $coords['lat'];
		$lon = $coords['lon'];
		$pm = new Pas_Service_Geo_Geoplanet();
		$place = $pm->reverseGeoCode($lat,$lon);
		$woeid = $place['woeid'];
	} else {
		$lat = NULL;
		$lon = NULL;
		$woeid = NULL;
	}
	$woeid = $place->woeid;

	$updateData = array(
		'firstname' => $form->getValue('firstname'),
		'lastname' => $form->getValue('lastname'),
		'email_one' => $form->getValue('email_one'),
		'email_two' => $form->getValue('email_two'),
		'address_1' => $form->getValue('address_1'),
		'address_2' => $form->getValue('address_2'),
		'town' => $form->getValue('town'),
		'county' => $form->getValue('county'),
		'postcode' => $form->getValue('postcode'),
		'telephone' => $form->getValue('telephone'),
		'fax' => $form->getValue('fax'),
		'region' => $form->getValue('region'),
		'website' => $form->getValue('website'),
		'profile' => $form->getValue('profile'),
		'updated' => $this->getTimeForForms(),
		'updatedBy' => $this->getIdentityForForms(),
		'latitude' => $lat,
		'longitude' => $lon,
		'woeid' => $woeid);

	foreach ($updateData as $key => $value) {
		  if (is_null($value)) {
			unset($updateData[$key]);
		  }
		}
	$contacts = new Contacts();
	$where = array();
	$where[] = $contacts->getAdapter()->quoteInto('dbaseID = ?', $this->getIdentityForForms());
	$insert = $contacts->update($updateData,$where);
	$this->_flashMessenger->addMessage('Contact information for '.$form->getValue('firstname').' '.$form->getValue('lastname') . ' updated!');
	$this->_redirect('/users/account/');
	} else {
	$form->populate($formData);
	}
	} else {
	$contacts = new Contacts();
	$contact = $contacts->fetchRow($contacts->select()->where('dbaseID = '.  $this->getIdentityForForms()));
	if(is_null($contact)){
		throw new Pas_Exception_Param('Admin has not yet set up a profile for you');
	} else {
	$form->populate($contact->toArray());
	}
	
	}
	}
	/** Change your staff profile image
	*/
	public function imageAction() {
	$contacts = new Contacts();

	$people = $contacts->fetchRow($contacts->select()->where('dbaseID = ' . $this->getIdentityForForms()));
	
	if(is_null($people)){
		throw new Pas_Exception_Param('Admin has not yet set up a profile for you');
	}
	$this->view->contacts = $people->toArray();
	$currentimage = $people->image;
	$form = new AddStaffPhotoForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();	{
	if ($form->isValid($formData)) {
            $upload = new Zend_File_Transfer_Adapter_Http();
   	$upload->addValidator('NotExists', true,array('./assets/staffphotos/'));
	if($upload->isValid()) {
	$filename = $form->getValue('image');
	$largepath = self::PROFILEPATH;
	$original = $largepath . $filename;
	$name = substr($filename, 0, strrpos($filename, '.'));
	$ext = '.jpg';
	$converted = $name . $ext;
	$insertData = array();
	$insertData['image'] = $converted;
	$insertData['updated'] = $this->getTimeForForms();
	$insertData['updatedBy'] = $this->getIdentityForForms();
	foreach ($insertData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($insertData[$key]);
      }
    }
	$smallpath = self::PROFILEPATH . 'thumbnails/' . $converted;
	$mediumpath =  self::PROFILEPATH . 'resized/' . $converted;

	//create medium size
	$phMagick = new phMagick($original, $mediumpath);
	$phMagick->resize(400,0);
	$phMagick->convert();
	/* Zend_Debug::dump($convertsmall);
	Zend_Debug::dump($phMagick);
	exit; */
	$phMagick = new phMagick($original, $smallpath);
	$phMagick->resize(80,0);
	$phMagick->convert();

	$staffs = new Contacts();
	$where = array();
	$where[] = $staffs->getAdapter()->quoteInto('dbaseID  = ?', $this->getIdentityForForms());
	$staffs->update($insertData,$where);
	$upload->receive();
	unlink( self::PROFILEPATH . 'thumbnails/' . $currentimage);
	unlink( self::PROFILEPATH . $currentimage);
	unlink( self::PROFILEPATH . 'resized/' . $currentimage);
 	$this->_flashMessenger->addMessage('The image has been resized and added to your profile.');
	$this->_redirect('/users/account/');
	} else {
	$this->_flashMessenger->addMessage('There is a problem with your upload. Probably that image exists.');
	$this->view->errors = $upload->getMessages();
	}
	} else {
	$form->populate($formData);
	$this->_flashMessenger->addMessage('Check your form for errors');
	}
	}
	}
	}

	/** Change staff profile image
	*/
	public function logoAction() {
	$contacts = new Contacts();
	$people = $contacts->fetchRow($contacts->select()->where('dbaseID = ?' , $this->getIdentityForForms()));
	Zend_Debug::dump($people);
	exit;
	$inst = $people->identifier;
	$this->view->inst = $inst;
	$logos = new InstLogos();
	$logoslisted = $logos->getLogosInst($inst);
	$this->view->logos =$logoslisted;
	$form = new AddStaffLogoForm();
	$form->details->setLegend('Add a logo: ');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();	{
	if ($form->isValid($formData)) {
        $upload = new Zend_File_Transfer_Adapter_Http();
   	$upload->addValidator('NotExists', true, array( self::LOGOPATH ));
	if($upload->isValid()) {
	$filename = $form->getValue('logo');
	$largepath = self::LOGOPATH;
	$original = $largepath . $filename;
	$name = substr($filename, 0, strrpos($filename, '.'));
	$ext = '.jpg';
	$converted = $name . $ext;
	$insertData = array();
	$insertData['image'] = $converted;
	$insertData['instID'] = $inst;
	$insertData['created'] = $this->getTimeForForms();
	$insertData['createdBy'] = $this->getIdentityForForms();
	foreach ($insertData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($insertData[$key]);
      }
    }
	$replace = $form->getValue('replace');
	if( $replace == 1) {
	foreach($logoslisted as $l) {
	unlink(self::LOGOPATH . 'thumbnails/' . $l['image']);
	unlink(self::LOGOPATH . $l['image']);
	unlink(self::LOGOPATH . 'resized/' . $l['image']);
	}
	}
	$smallpath = self::LOGOPATH . 'thumbnails/' . $converted;
	$mediumpath = self::LOGOPATH . 'resized/' . $converted;

	//create medium size
	$phMagick = new phMagick($original, $mediumpath);
	$phMagick->resize(300,0);
	$phMagick->convert();

	$phMagick = new phMagick($original, $smallpath);
	$phMagick->resize(100,0);
	$phMagick->convert();

	$logos->insert($insertData);
	$upload->receive();
 	$this->_flashMessenger->addMessage('The image has been resized and zoomified!');
	$this->_redirect('/users/account/');
	} else {
	$this->_flashMessenger->addMessage('There is a problem with your upload. Probably that image exists.');
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