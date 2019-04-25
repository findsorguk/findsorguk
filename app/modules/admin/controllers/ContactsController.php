<?php

/** Controller for setting up and manipulating staff contacts
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @todo move code to models
 * @uses Pas_Service_Geo_Coder
 * @uses Contacts
 * @uses ContactForm
 * @uses Zend_File_Transfer_Adapter_Http
 * @uses AddStaffPhotoForm
 * @uses Imagecow
 * @uses StaffRegions
 * @uses AddStaffLogoForm
 */
class Admin_ContactsController extends Pas_Controller_Action_Admin
{

    /** The path for logos for contacts and orgs
     *
     */
    const LOGOPATH = 'logos/';

    /** The path for staff photos
     *
     */
    const STAFFPATH = './assets/staffphotos/';

   /** The profile image path for thumbnail
     *
     */
    const THUMB = array('destination' => self::STAFFPATH . 'thumbnails/' , 'width' => 100, 'height' => 100);

    /** The profile image path for resized
     *
     */
    const RESIZE = array('destination' => self::STAFFPATH . 'resized/' , 'width' => 400, 'height' => 0);

    /** Get the geocoder function
     * @access public
     * @return \Pas_Service_Geo_Coder
     */
    public function getGeocoder()
    {
        $this->_geocoder = new Pas_Service_Geo_Coder();
        return $this->_geocoder;
    }

    /** The contacts model returned for use
     * @access public
     * @return \Contacts
     */
    public function getContacts()
    {
        $this->_contacts = new Contacts();
        return $this->_contacts;
    }

    /** The geocoding class
     * @access protected
     * @var \Pas_Service_Geo_Coder
     */
    protected $_geocoder;

    /** The contacts model
     * @access protected
     * @var \Contacts
     */
    protected $_contacts;

    /** The redirect url
     * @access protected
     * @var string
     */
    protected $_redirectUrl = 'admin/contacts/';

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $flosActions = array('index',);
        $this->_helper->_acl->allow('flos', $flosActions);
        $this->_helper->_acl->allow('fa', null);
        $this->_helper->_acl->allow('admin', null);
    }

    /** Display the index page for contacts
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->view->contacts = $this->getContacts()->getContacts($this->getAllParams());
    }

    /** View index page for alumni
     * @access public
     * @return void
     */
    public function alumniAction()
    {
        $this->view->contacts = $this->getContacts()->getAlumni($this->getAllParams());
    }

    /** View a contact's details
     * @access public
     * @return void
     */
    public function contactAction()
    {
        if ($this->getParam('format') == ('vcf')) {
            $this->_helper->layout->disableLayout();
            $this->view->persons = $this->getContacts()->getPersonDetails($this->getParam('id'));
        } else {
            $this->view->staffs = $this->getContacts()->getPersonDetails($this->getParam('id'));
        }
    }

    /** Add new staff member
     * @access public
     * @return void
     * @todo move data manipulation to contacts model
     */
    public function addAction()
    {
        $form = new ContactForm();
        $form->submit->setLabel('Add a new Scheme contact');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $address = $form->getValue('address_1') . ',' . $form->getValue('address_2') . ','
                    . $form->getValue('town') . ',' . $form->getValue('county') . ','
                    . $form->getValue('postcode') . ', UK';
                $coords = $this->getGeocoder()->getCoordinates($address);
                if ($coords) {
                    $lat = $coords['lat'];
                    $lon = $coords['lon'];
                    $woeid = null;
                } else {
                    $lat = null;
                    $lon = null;
                    $woeid = null;
                }
                $insertData = $form->getValues();
                $insertData['latitude'] = $lat;
                $insertData['longitude'] = $lon;
                $insertData['woeid'] = $woeid;
                $insert = $this->getContacts()->add($insertData);
                $this->getFlash()->addMessage('Scheme contact created!');
                $this->redirect($this->_redirectUrl . 'contact/id/' . $insert);
            } else {
                $form->populate($formData);
            }
        }
    }

    /** Edit a contact's details
     * @access public
     * @return void
     */
    public function editAction()
    {
        $form = new ContactForm();
        $form->submit->setLabel('Save');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($this->_request->getPost())) {
                $address = $form->getValue('address_1') . ',' . $form->getValue('address_2') . ','
                    . $form->getValue('town') . ',' . $form->getValue('county') . ','
                    . $form->getValue('postcode') . ', UK';
                $coords = $this->getGeoCoder()->getCoordinates($address);
                if ($coords) {
                    $lat = $coords['lat'];
                    $lon = $coords['lon'];
                    $woeid = null;
                } else {
                    $lat = null;
                    $lon = null;
                    $woeid = null;
                }
                $updateData = $form->getValues();
                $updateData['latitude'] = $lat;
                $updateData['longitude'] = $lon;
                $updateData['woeid'] = $woeid;
                if($updateData['alumni'] == '0'){
                    $updateData['alumni'] = NULL;
                }
                $where = array();
                $where[] = $this->getContacts()->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                $this->getContacts()->update($updateData, $where);
                $this->getFlash()->addMessage('Contact information updated!');
                $this->getCache()->clean(Zend_Cache::CLEANING_MODE_ALL);
                $this->redirect($this->_redirectUrl . 'contact/id/' . $this->getParam('id'));
            } else {
                $form->populate($this->_request->getPost());
            }
        } else {
            $id = (int)$this->_request->getParam('id', 0);
            if ($id > 0) {
                $contact = $this->getContacts()->fetchRow('id=' . $id);
                $form->populate($contact->toArray());
            }
        }
    }

    /** Delete a contact
     * @access public
     * @return void
     */
    public function deleteAction()
    {
        $this->getFlash()->addMessage($this->_noChange);
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = 'id = ' . $id;
                $this->getContacts()->delete($where);
            }
            $this->getFlash()->addMessage('Contact information deleted! This cannot be undone.');
            $this->redirect($this->_redirectUrl);
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $this->view->contact = $this->getContacts()->fetchRow('id =' . $id);
            }
        }
    }

    /**
     * Provide an avatar for a contact
     *
     * @access public
     * @return void
     */
    public function avatarAction()
    {
        $form = $this->view->form = new AddStaffPhotoForm();
	$postData = $this->_request->getPost();

        if ($this->_request->isPost() && $form->isValid($postData))
	{
            $this->uploadStaffPhoto($form, $this->getParam('id'));
        }
	else
	{
	    $form->populate($postData);
            $this->getFlash()->addMessage('Check your form for errors');
	}
    }

    /** Give them a logo
     * @access public
     * @return void
     *
     */
    public function logoAction()
    {
        $form = new AddStaffLogoForm();
        $form->details->setLegend('Add a logo: ');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $upload = new Zend_File_Transfer_Adapter_Http();
                $upload->addValidator('NotExists', true, array(self::LOGOPATH));
                if ($upload->isValid()) {
                    $filename = $form->getValue('image');
                    $insertData = array();
                    $insertData['host'] = $filename;
                    $insertData['updated'] = $this->getTimeForForms();
                    $insertData['updatedBy'] = $this->getIdentityForForms();

                    $regions = new StaffRegions();
                    $where = array();
                    $where[] = $regions->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                    $regions->update($insertData, $where);
                    $upload->receive();
                    $this->getFlash()->addMessage('The image has been resized and zoomified!');
                    $this->redirect('/admin/contacts/institution/id/' . $this->getParam('id'));
                } else {
                    $this->getFlash()->addMessage('There is a problem with your upload. Probably that image exists.');
                    $this->view->errors = $upload->getMessages();
                }
            } else {
                $form->populate($formData);
                $this->getFlash()->addMessage('Check your form for errors');
            }
        }
    }

    public function deleteavatarAction()
    {
        $id = $this->getParam('id');

        if (!(ctype_digit($id) && ($id > 0)))
        {
            $this->redirect($this->_redirectUrl);
        }

        if ($this->_request->isPost())
	{
            $postVariable = $this->_request->getPost('confirmDelete');
            $confirmDelete = isset($postVariable) ? strtoupper($postVariable) : "NO";

	    $this->deleteStaffPhoto($confirmDelete, $id);
        } else {
            $this->view->contact = $this->getContacts()->fetchRow('id =' . $id);
        }
    }

    private function deleteStaffPhoto($confirmDelete, $id)
    {
	if ('YES' === $confirmDelete)
	{
	    $this->removeStaffImage($id);
        } else {
	    $this->getFlash()->addMessage('Image NOT deleted!');
	}

        $this->redirect('/admin/contacts/contact/id/' . $id);
    }

    // Upload staff photo
    private function uploadStaffPhoto($form, $id)
    {
        $upload = new Zend_File_Transfer_Adapter_Http();

        if ($upload->isValid())
	{
  	    // Check staff photo exists, if yes then delete
	    $this->removeExistingStaffPhotoIfPresent($id);

	    // Rename the image name with user id, resize image and update the database
	    $this->processStaffImage($form, $id, $upload);
        } else {
            $this->getFlash()->addMessage('There is a problem with your upload.');
            $this->view->errors = $upload->getMessages();
        }

        $this->redirect('/admin/contacts/contact/id/' . $id);
    }

    // Upload staff Image
    private function processStaffImage($form, $id, $upload)
    {
	$pathOfRenamedImage = $this->renameStaffPhotoToID(self::STAFFPATH, $form->getValue('image'), $id);
        $imageName = pathinfo($pathOfRenamedImage)["basename"];

	// Resize the image
        $this->resizeAndSaveImages($pathOfRenamedImage, $upload);

        // Update staff table for image name
        $this->updateStaffTableWithImageName($imageName, $id);

	// Clear the cache as image is uploaded
        $this->getContacts()->clearCacheEntry(Contacts::PERSON_CACHE_ID, $id);

        $this->getFlash()->addMessage('The image is added!');
    }

    // Resize and save the images
    private function resizeAndSaveImages($pathOfImage, $upload)
    {
        $PHPMagick = new PHPMagick();
        $PHPMagick->resize($pathOfImage, self::THUMB);
        $PHPMagick->resize($pathOfImage, self::RESIZE);

        $upload->receive();
    }

    // Update staff table for image
    private function updateStaffTableWithImageName($imageName = null, $id)
    {
        $updateStaffData = array();
        $updateStaffData['image'] = $imageName;
        $updateStaffData['updated'] = $this->getTimeForForms();
        $updateStaffData['updatedBy'] = $this->getIdentityForForms();

        $staff = new Contacts();
        $where = $staff->getAdapter()->quoteInto('id = ?', $id);

        $staff->update($updateStaffData, $where);
    }

    // Rename the image to the user id
    private function renameStaffPhotoToID($sourceDirectory, $imageName, $id)
    {
        $originalPath = $sourceDirectory . $imageName;

        if(file_exists($originalPath))
        {
	    $imagePath = pathinfo($originalPath);
	    $newImageName = $id . "." . $imagePath["extension"];
	    $newPath = $sourceDirectory. $newImageName;

	    if (rename($originalPath, $newPath))
	    {
		return $newPath;
	    }
	}

	return $originalPath;
    }

    // Delete image process
    private function removeStaffImage($id)
    {
        // Update staff table for image deletion
        $this->updateStaffTableWithImageName(null, $id);

        // Delete images from the staffphotos, thumbnails and resized folders
        $this->removeExistingStaffPhotoIfPresent($id);

        // Clear the cache for image deletion
	$this->getContacts()->clearCacheEntry(Contacts::PERSON_CACHE_ID, $id);

        $this->getFlash()->addMessage('Image deleted!');
    }

    // Check images exists in the staffphotos, thumbnails and resized folders
    private function removeExistingStaffPhotoIfPresent($id)
    {
	array_map('unlink', glob(self::STAFFPATH . $id . '*'));
	array_map('unlink', glob(self::RESIZE['destination'] . $id . '*'));
	array_map('unlink', glob(self::THUMB['destination'] . $id . '*'));
    }
}
