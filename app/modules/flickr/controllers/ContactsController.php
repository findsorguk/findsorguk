<?php
/** Controller for pulling ajax data from flickr.
*
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @version 1
 * @since 10 October 2011
 * @uses Pas_Yql_Flickr
 * @uses Pas_Yql_Exception
 * @uses Zend_Paginator
 *
*/
class Flickr_ContactsController extends Pas_Controller_Action_Admin{

    /** Flickr config
     * @var \Pas_Yql_Flickr
     * @access protected
     */
    protected $_flickr;

    /** The flickr api key
     * @access protected
     * @var string
     */
    protected $_api;

    /** Initiate the api and config, ACl
     * @access public
     * @return void
     */
    public function init(){
        $this->_helper->acl->allow('public',null);
        $this->_flickr = Zend_Registry::get('config')->webservice->flickr;
        $this->_api = new Pas_Yql_Flickr($this->_flickr);
        parent::init();
    }

    /** Display the index page
     * @access public
     * @return void
    */
    public function indexAction() {
        $page = $this->_getParam('page');
        if(!isset($page)){
            $start = 1;
        } else {
                $start = $page;
        }
        $contacts = $this->_api->getContacts($start);
        $pagination = array(
            'page' => $page,
            'contacts' => $contacts->contact,
            'per_page' => (int)$contacts->per_page,
            'total_results' => (int)$contacts->total
                );
        $paginator = Zend_Paginator::factory($pagination['total_results']);
        $paginator->setCurrentPageNumber($pagination['page'])
                ->setItemCountPerPage($pagination['per_page'])
                ->setCache($this->getCache());
        $this->view->paginator = $paginator;
        $this->view->contacts = $contacts;
    }

    /** Display contact details and some images
     * @access public
     * @return void
     * @throws Pas_Yql_Exception
     */
    public function knownAction() {
        if($this->_getParam('as',false)){
            $this->view->details	= $this->_api->getContactDetails($this->_getParam('as'));
            if (!($this->getCache()->test(md5('contacts'.$this->_getParam('as'))))) {
                $ph	= $this->_api->getContactPhotos($this->_getParam('as'), 0, 18);
                $this->getCache()->save($ph);
            } else {
                $ph = $this->getCache()->load(md5('contacts'.$this->_getParam('as')));
            }
            $this->view->photos = $ph;
        } else {
            throw new Pas_Yql_Exception($this->_missingParameter);
        }
    }
}

