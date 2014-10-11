<?php
/** Controller for manipulating news added by a user
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @uses Pas_Service_Geo_Coder
 * @uses News
 * @uses NewsStoryForm
 */
class Users_NewsController extends Pas_Controller_Action_Admin {
    /** Set up variables
     * @access protected
     * @var string $_gmapskey
     */
    protected $_gmapskey;

    /** The geocoder object
     * @access protected
     * @var \Pas_Service_Geocoder
     */
    protected $_geocoder;

    /** The news model
     * @access protected
     * @var \News
     */
    protected $_news;

    /** Set up the ACL and contexts
    */
    public function init() {
        $this->_helper->_acl->allow('flos',null);
        $this->_helper->_acl->allow('fa',null);
        $this->_helper->_acl->allow('admin',null);
        $this->_news = new News();
        $this->_geocoder = new Pas_Service_Geo_Coder();
        
    }

    /** The redirect string
     *
     */
    const REDIRECT = '/users/news/';

    /** Set up the index page
    */
    public function indexAction() {
        $this->view->news = $this->_news->getAllNewsArticlesAdmin($this->_getAllParams());
    }
    /** Add a news story
     * @access public
     * @return void
     */
    public function addAction() {
        $form = new NewsStoryForm();
        $form->submit->setLabel('Add a news story');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $address = $form->getValue('primaryNewsLocation');
                $coords = $this->_geocoder->getCoordinates($address);
                if($coords){
                    $lat = $coords['lat'];
                    $long = $coords['lon'];
                } else {
                    $lat = null;
                    $lon = null;
                }
                $row = $this->_news->createRow();
                //Database rows created here ->
                $row->title = $form->getValue('title');
                $row->summary = $form->getValue('summary');
                $row->contents = $form->getValue('contents');
                $row->author = $form->getValue('author');
                $row->contactTel = $form->getValue('contactTel');
                $row->contactEmail = $form->getValue('contactEmail');
                $row->contactName = $form->getValue('contactName');
                $row->keywords = $form->getValue('keywords');
                $row->golive = $form->getValue('golive');
                $row->publish_state = $form->getValue('publish_state');
                $row->datePublished = $this->getTimeForForms();
                $row->primaryNewsLocation = $address;
                $row->latitude = $lat;
                $row->longitude = $long;
                $row->createdBy = $this->getIdentityForForms();
                $row->created = $this->getTimeForForms();
                //Save and redirect
                $row->save();
                $this->getFlash()->addMessage('News story created!');
                $this->redirect(self::REDIRECT);
            } else {
                $form->populate($formData);
            }
        }
    }

    /** Edit a news story
     * @access public
     * @return void
     */
    public function editAction() {
        $form = new NewsStoryForm();
        $form->submit->setLabel('Update details...');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $address = $form->getValue('primaryNewsLocation');
                $coords = $this->_geocoder->getCoordinates($address);
                if($coords){
                    $lat = $coords['lat'];
                    $long = $coords['lon'];
                } else {
                    $lat = null;
                    $lon = null;
                }
                $row = $this->_news->fetchRow('id =' . $this->_getParam('id'));
                $row->title = $form->getValue('title');
                $row->summary = $form->getValue('summary');
                $row->contents = $form->getValue('contents');
                $row->author = $form->getValue('author');
                $row->contactTel = $form->getValue('contactTel');
                $row->contactEmail = $form->getValue('contactEmail');
                $row->contactName = $form->getValue('contactName');
                $row->keywords = $form->getValue('keywords');
                $row->primaryNewsLocation = $address;
                $row->latitude = $lat;
                $row->longitude = $long;
                $row->updatedBy = $this->getIdentityForForms();
                $row->updated = $this->getTimeForForms();
                $row->golive = $form->getValue('golive');
                $row->publish_state = $form->getValue('publish_state');
                $row->datePublished = $this->getTimeForForms();
                $row->save();
                $this->getFlash()->addMessage('News story information updated!');
                $$this->redirect(elf::REDIRECT);
            } else {
                $form->populate($formData);
            }
        } else {
            // find id is expected in $params['id']
            $id = (int)$this->_request->getParam('id', 0);
            if ($id > 0) {
                $new = $this->_news->fetchRow('id= ' . (int)$id);
                $form->populate($new->toArray());
            }
        }
    }
    /** Delete a news story
    */
    public function deleteAction() {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = 'id = ' . (int)$id;
                $this->_news->delete($where);
                $this->getFlash()->addMessage('Record deleted!');
            }
            $this->_redirect(self::REDIRECT);
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
            $this->view->new = $this->_news->fetchRow('id=' . (int)$id);
            }
        }
    }
}