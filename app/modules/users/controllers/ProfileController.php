<?php

/** Controller for manipulating user profile details
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @uses Pas_Service_Geo_Coder
 * @uses ContactForm
 * @uses AddStaffPhotoForm
 * @uses Contacts
 * @uses Pas_Exception_Param
 */
class Users_ProfileController extends Pas_Controller_Action_Admin
{

    /** The geocoder
     * @access protected
     * @var \Pas_Service_Geo_Coder
     */
    protected $_gecoder;

    /** The redirect uri
     * @access protected
     * @var string
     */
    protected $_redirectUrl = 'users/profile/image';

    /** The logo path
     *
     */
    const LOGOPATH = './assets/logos/';

    /** The profile image path
     *
     */
    const PROFILEPATH = './assets/staffphotos/';

    /** The profile image path for thumbnail
     *
     */
    const THUMB = array('destination' => self::PROFILEPATH . 'thumbnails/' , 'width' => 100, 'height' => 100);

    /** The profile image path for resized
     *
     */
    const RESIZE = array('destination' => self::PROFILEPATH . 'resized/' , 'width' => 400, 'height' => 0);

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_contacts = new Contacts();
        $this->_helper->_acl->allow('flos', null);
        $this->_helper->_acl->allow('fa', null);
        $this->_helper->_acl->allow('admin', null);
        $this->_geocoder = new Pas_Service_Geo_Coder();
    }


    /** No access to the index page
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->redirect('/users/');
    }


    /** Edit staff profile
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editAction()
    {
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
            if ($form->isValid($formData)) {

                $latLong = $this->getLatLongFromAddress($formData);

                $updateData = $form->getValues();
                $updateData['latitude'] = $latLong['lat'] ?? $updateData['latitude'];
                $updateData['longitude'] = $latLong['long'] ?? $updateData['longitude'];
                $updateData['woeid'] = null;
                $where = array();
                $where[] = $this->_contacts->getAdapter()->quoteInto('dbaseID = ?', $this->getIdentityForForms());
                $this->_contacts->update($updateData, $where);
                $this->getFlash()->addMessage('Contact information updated!');
                $this->redirect('/users/account/');
            } else {
                $form->populate($formData);
            }
        } else {
            $contact = $this->_contacts->fetchRow($this->_contacts->select()
                ->where('dbaseID = ' . $this->getIdentityForForms()));
            if (is_null($contact)) {
                throw new Pas_Exception_Param('Admin has not yet set up a profile for you', 500);
            } else {
                $form->populate($contact->toArray());
            }

        }
    }

    /** Get Lat/Long of staff member via Google GeocoderAPI
     * @throws Pas_Geo_Exception
     */
    private function getLatLongFromAddress($formValues): array
    {
        //Try to get lat/long from the address if not supplied
        if (!empty(Zend_Registry::get('config')->webservice->google->geocoderAPI)
        ) {
            $address = $formValues->getValue('address_1') . ','
                . $formValues->getValue('address_2') . ','
                . $formValues->getValue('town') . ','
                . $formValues->getValue('county') . ','
                . $formValues->getValue('postcode') . ', UK';

            $coords = $this->_geocoder->getCoordinates($address);
            return array(
                'lat' => $coords['lat'] ?? null,
                'long' => $coords['lon'] ?? null
            );

        }
        return array();
    }

    /** Change your staff profile image
     */
    public function imageAction()
    {
	$dbaseID = $this->getIdentityForForms();
        $people = $this->_contacts->fetchRow($this->_contacts->select()->where('dbaseID = ' . $dbaseID));

        if (is_null($people)) {
            throw new Pas_Exception('Admin has not yet set up a profile for you');
        }

        $this->view->contacts = $people->toArray();
        $form = new AddStaffPhotoForm();
        $this->view->form = $form;

        if ($this->_request->isPost()) 
	{
	    $formData = $this->_request->getPost();
	    if ($form->isValid($formData))
            {
                $upload = new Zend_File_Transfer_Adapter_Http();

                if ($upload->isValid()) 
		{
		    // Check if any image arleady exists
		    $this->findExistingStaffImages($dbaseID);

                    $renamedImage = $this->renameImageUsingId(self::PROFILEPATH, $form->getValue('image'), $dbaseID);

                    $phpMagick = new PHPMagick();
                    $phpMagick->resize($renamedImage, self::THUMB);
                    $phpMagick->resize($renamedImage, self::RESIZE);

		    // Update staff table for image details
		    $this->storeStaffImageName($this->getImagename($renamedImage), $dbaseID);

                    // Upload image
                    $upload->receive();

                    $this->getFlash()->addMessage('The image has been resized and added to your profile.');
                } else {
                    $this->getFlash()->addMessage('There is a problem with your upload.');
	            $this->view->errors = $upload->getMessages();
                }

                $this->redirect($this->_redirectUrl);
            } else {
                $form->populate($formData);
                $this->getFlash()->addMessage('Check your form for errors');
	    }
        }
    }

    /** Change staff profile image
     */
    public function logoAction()
    {
        $contacts = new Contacts();
        $people = $contacts->fetchRow($contacts->select()->where('dbaseID = ?', $this->getIdentityForForms()));

        $inst = $people->identifier;
        $this->view->inst = $inst;
        $logos = new InstLogos();
        $logoslisted = $logos->getLogosInst($inst);
        $this->view->logos = $logoslisted;
        $form = new AddStaffLogoForm();
        $form->details->setLegend('Add a logo: ');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            {
                if ($form->isValid($formData)) {
                    $upload = new Zend_File_Transfer_Adapter_Http();
                    $upload->addValidator('NotExists', true, array(self::LOGOPATH));
                    if ($upload->isValid()) {
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
                            if (is_null($value) || $value == "") {
                                unset($insertData[$key]);
                            }
                        }
                        $replace = $form->getValue('replace');
                        if ($replace == 1) {
                            foreach ($logoslisted as $l) {
                                unlink(self::LOGOPATH . 'thumbnails/' . $l['image']);
                                unlink(self::LOGOPATH . $l['image']);
                                unlink(self::LOGOPATH . 'resized/' . $l['image']);
                            }
                        }
                        $smallpath = self::LOGOPATH . 'thumbnails/' . $converted;
                        $mediumpath = self::LOGOPATH . 'resized/' . $converted;

                        //create medium size
                        $phpMagick = new PHPMagick($original, $mediumpath);
                        $phpMagick->resize(300, 0);
                        $phpMagick->convert();

                        $phpMagick = new PHPMagick($original, $smallpath);
                        $phpMagick->resize(100, 0);
                        $phpMagick->convert();

                        $logos->insert($insertData);
                        $upload->receive();
                        $this->getFlash()->addMessage('The image has been resized and zoomified!');
                        $this->redirect('/users/account/');
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
    }

    /** Delete a profile image
     * @access public
     * @return mixed
     */
    public function deleteprofileimageAction()
    {
	$dbaseID = $this->getIdentityForForms();

        if (!(is_numeric($dbaseID) && ($dbaseID > 0)))
	{
	    $this->redirect($this->_redirectUrl);
	}

        if ($this->_request->isPost())
	{
	    $postVariable = $this->_request->getPost('confirmDelete');
            $confirmDelete = isset($postVariable) ? strtoupper($postVariable) : "NO";
            if ('YES' === $confirmDelete)
	    {
	       $staff = new Contacts();
               $staffMemberImage = $staff->getImage($dbaseID);

	       // Update staff table for image is deletion
	       $this->storeStaffImageName(null, $dbaseID);

	       // Delete images from the staffphotos, thumbnails and resized folders
               $this->findExistingStaffImages($dbaseID);

	       $this->getFlash()->addMessage('Image deleted!');
            }
            else
            {
               $this->getFlash()->addMessage('Image NOT deleted!');
            }
            $this->redirect($this->_redirectUrl);
         }
    }

    // Update staff table for image is deletion
    private function storeStaffImageName($imageName = null, $dbaseID)
    {
       $updateStaffData = array();
       $updateStaffData['image'] = $imageName;
       $updateStaffData['updated'] = $this->getTimeForForms();
       $updateStaffData['updatedBy'] = $dbaseID;

       $staff = new Contacts();
       $where = $staff->getAdapter()->quoteInto('dbaseID = ?', $dbaseID);

       $staff->update($updateStaffData, $where);
    }

    // Rename the image with the user id suffix
    private function renameImageUsingId($sourceDirectory, $imageName, $id)
    {
	$originalPath = $sourceDirectory . $imageName;
        if(file_exists($originalPath))
        {
	    $imagePath = pathinfo($originalPath);

            $newImage = $imagePath["filename"] . "_" . $id . "." . $imagePath["extension"];
            $newPath = $sourceDirectory . $newImage;

            if (rename($originalPath, $newPath))
            {
                return $newPath;
            }
        }

        return $originalPath;
    }

    // Extract the image name
    private function getImagename($renamedImage)
    {
        $imagePath = pathinfo($renamedImage);

        return $imagePath["basename"];
    }

    // Check images exists in the staffphotos, thumbnails and resized folders
    private function findExistingStaffImages($id)
    {
        $userImages = '*_' . $id . '.*';
	$pattern = "/" . $id . "\.(jpg|jpeg|JPG|JPEG|png|PNG)$/";
	$this->deleteExistingImages(glob(self::PROFILEPATH . $userImages), $pattern);
	$this->deleteExistingImages(glob(self::RESIZE['destination'] . $userImages), $pattern);
	$this->deleteExistingImages(glob(self::THUMB['destination'] . $userImages), $pattern);
    }

    // Delete images from the staffphotos, thumbnails and resized folders
    private function deleteExistingImages($images, $pattern)
    {
	foreach ($images as $image)
	{
	    if (file_exists($image) && preg_match($pattern, $image))
	    {
	        unlink($image);
   	    }
	}
    }
}
