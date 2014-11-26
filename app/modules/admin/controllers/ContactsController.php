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
 * @uses Pas_Service_Geo_Geoplanet
 * @uses Pas_Service_Geo_Coder
 * @uses Contacts
 * @uses ContactForm
 * @uses Zend_File_Transfer_Adapter_Http
 * @uses AddStaffPhotoForm
 * @uses phMagick
 * @uses StaffRegions
 * @uses AddStaffLogoForm
 */
class Admin_ContactsController extends Pas_Controller_Action_Admin
{

    /** The path for logos for contacts and orgs
     *
     */
    const LOGOPATH = './assets/logos/';

    /** The path for staff photos
     *
     */
    const STAFFPATH = './assets/staffphotos/';

    /** The geoplanet class
     * @access protected
     * @var \Pas_Service_Geo_Geoplanet
     */
    protected $_geoPlanet;

    /** Get the geo planet class
     * @access public
     * @return \Pas_Service_Geo_Geoplanet
     */
    public function getGeoPlanet()
    {
        $this->_geoPlanet = new Pas_Service_Geo_Geoplanet(
            $this->_helper->config()->webservice->ydnkeys->appid
        );
        return $this->_geoPlanet;
    }

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
        if ($this->_getParam('format') == ('vcf')) {
            $this->_helper->layout->disableLayout();
            $this->view->persons = $this->getContacts()->getPersonDetails($this->_getParam('id'));
        } else {
            $this->view->staffs = $this->getContacts()->getPersonDetails($this->_getParam('id'));
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
                    $place = $this->getGeoPlanet()->reverseGeoCode($lat, $lon);
                    $woeid = $place['woeid'];
                } else {
                    $lat = null;
                    $lon = null;
                    $woeid = null;
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
                    'woeid' => $woeid
                );
                $insert = $this->getContacts()->insert($insertData);
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
            if ($form->isValid($formData)) {
                $address = $form->getValue('address_1') . ',' . $form->getValue('address_2') . ','
                    . $form->getValue('town') . ',' . $form->getValue('county') . ','
                    . $form->getValue('postcode') . ', UK';
                $coords = $this->_geocoder->getCoordinates($address);
                if ($coords) {
                    $lat = $coords['lat'];
                    $lon = $coords['lon'];
                    $place = $this->getGeoPlanet()->reverseGeoCode($lat, $lon);
                    $woeid = $place['woeid'];
                } else {
                    $lat = null;
                    $lon = null;
                    $woeid = null;
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
                $where = array();
                $where[] = $this->getContacts()->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                $insert = $this->getContacts()->update($updateData, $where);
                $this->getFlash()->addMessage('Contact information for ' . $form->getValue('firstname') . ' '
                    . $form->getValue('lastname') . ' updated!');
                $this->redirect($this->_redirectUrl . 'contact/id/' . $this->_getParam('id'));
            } else {
                $form->populate($formData);
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

    /** provide an avatar for a contact
     * @access public
     * @return void
     */
    public function avatarAction()
    {
        $form = new AddStaffPhotoForm();
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            {
                if ($form->isValid($formData)) {
                    $upload = new Zend_File_Transfer_Adapter_Http();
                    $upload->addValidator('NotExists', true, array(self::STAFFPATH));
                    if ($upload->isValid()) {
                        $filename = $form->getValue('image');
                        $insertData = array();
                        $insertData['image'] = $filename;
                        $insertData['updated'] = $this->getTimeForForms();
                        $insertData['updatedBy'] = $this->getIdentityForForms();
                        foreach ($insertData as $key => $value) {
                            if (is_null($value) || $value == "") {
                                unset($insertData[$key]);
                            }
                        }
                        $original = self::STAFFPATH . $filename;
                        $name = substr($filename, 0, strrpos($filename, '.'));
                        $ext = '.jpg';
                        $converted = $name . $ext;
                        //Small path
                        $smallpath = self::STAFFPATH . 'thumbnails/' . $converted;
                        $mediumpath = self::STAFFPATH . 'resized/' . $converted;
                        //create medium size
                        $phMagick = new phMagick($original, $mediumpath);
                        $phMagick->resize(300, 0);
                        $phMagick->convert();

                        $phMagick = new phMagick($original, $smallpath);
                        $phMagick->resize(100, 0);
                        $phMagick->convert();

                        $staffs = new Contacts();
                        $where = array();
                        $where[] = $staffs->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                        $staffs->update($insertData, $where);
                        $upload->receive();
                        $this->getFlash()->addMessage('The image has been resized and zoomified!');
                        $this->redirect('/admin/contacts/contact/id/' . $this->_getParam('id'));
                    } else {
                        $this->getFlash()->addMessage('There is a problem with your upload.
                Probably that image exists.');
                        $this->view->errors = $upload->getMessages();
                    }
                } else {
                    $form->populate($formData);
                    $this->getFlash()->addMessage('Check your form for errors');
                }
            }
        }
    }

    /** Give them a logo
     * @access public
     * void
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
                    foreach ($insertData as $key => $value) {
                        if (is_null($value) || $value == "") {
                            unset($insertData[$key]);
                        }
                    }
                    $original = self::LOGOPATH . $filename;
                    $name = substr($filename, 0, strrpos($filename, '.'));
                    $ext = '.jpg';
                    $converted = $name . $ext;

                    $smallpath = self::LOGOPATH . 'thumbnails/' . $converted;
                    $mediumpath = self::LOGOPATH . 'resized/' . $converted;

                    //create medium size
                    $phMagick = new phMagick($original, $mediumpath);
                    $phMagick->resize(300, 0);
                    $phMagick->convert();
                    /* Zend_Debug::dump($convertsmall);
                    Zend_Debug::dump($phMagick);
                    exit; */
                    $phMagick = new phMagick($original, $smallpath);
                    $phMagick->resize(100, 0);
                    $phMagick->convert();

                    $regions = new StaffRegions();
                    $where = array();
                    $where[] = $regions->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                    $regions->update($insertData, $where);
                    $upload->receive();
                    $this->getFlash()->addMessage('The image has been resized and zoomified!');
                    $this->redirect('/admin/contacts/institution/id/' . $this->_getParam('id'));
                } else {
                    $this->getFlash()->addMessage('There is a problem with your upload.
                Probably that image exists.');
                    $this->view->errors = $upload->getMessages();
                }
            } else {
                $form->populate($formData);
                $this->getFlash()->addMessage('Check your form for errors');
            }
        }
    }
}