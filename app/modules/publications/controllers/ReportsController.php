<?php
/** Controller for all Annual and Treasure reports
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Content
 * @uses Zend_Gdata_Docs
 * @uses  Zend_Gdata_ClientLogin
 * @uses Zend_Gdata_Docs_Query
 * 
 */
class Publications_ReportsController extends Pas_Controller_Action_Admin {
	
    /** The content model
     * @access protected
     * @var \Content
     */
    protected $_content;
    
    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init(){
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->acl->allow('public',null);
        $this->_content = new Content();
    }

    /** Nothing on the index page
     * @access public
     * @return void
     */
    public function indexAction() {
        //Magic in view
    }

    /** Render annual report pages
     * @access public
     * @return void
     */ 
    public function annualAction(){
        $slug = $this->_getParam('slug');
        if($slug == '\d+') {
            $this->view->contents = $this->_content->getFrontContent('reports');
            $this->view->contents = $this->_content->getFrontContent('publications');
            $service = Zend_Gdata_Docs::AUTH_SERVICE_NAME;
            $client = Zend_Gdata_ClientLogin::getHttpClient(
                    $this->_helper->config()->webservice->google->username, 
                    $this->_helper->config()->webservice->google->password, 
                    $service
                    );
            $docs = new Zend_Gdata_Docs($client);
            $docsQuery = new Zend_Gdata_Docs_Query();
            $docsQuery->setQuery('title:Scheme Annual Report');
            $feed = $docs->getDocumentListFeed($docsQuery);
            $documents = array();	
            foreach ($feed->entries as $entry) {
            $title = $entry->title;
            foreach ($entry->link as $link) {
                if ($link->getRel() === 'alternate') {
                    $altlink = $link->getHref();
                }
            }
            $documents[]=array('title' => $title, 
                'altlink' => $altlink,
                'updated' => $entry->updated,
                'type' => $entry->content->type,
                'published' => $entry->published
                    );    
            }
            $this->view->documents = $documents;
        } else {
            $this->view->contents = $this->_content
                    ->getContent('reports',$this->_getParam('slug'));
        }
    }

    /** Render treasure report pages
     * @access public
     * @return void
     */
    public function treasureAction() {
        $slug = $this->_getParam('slug');
        if($slug == '\d+') {
        $this->view->contents = $this->_content->getFrontContent('treports');
        $service = Zend_Gdata_Docs::AUTH_SERVICE_NAME;
        $client = Zend_Gdata_ClientLogin::getHttpClient(
                    $this->_helper->config()->webservice->google->username, 
                    $this->_helper->config()->webservice->google->password, 
                    $service
                    );
        $docs = new Zend_Gdata_Docs($client);
        $docsQuery = new Zend_Gdata_Docs_Query();
        $docsQuery->setQuery('title:Treasure Annual Report');
        $feed = $docs->getDocumentListFeed($docsQuery);
        $documents = array();	
        foreach ($feed->entries as $entry) {
            $title = $entry->title;
            foreach ($entry->link as $link) {
                if ($link->getRel() === 'alternate') {
                    $altlink = $link->getHref();
                }
            }
            $documents[]=array('title' => $title, 
                'altlink' => $altlink,
                'updated' => $entry->updated,
                'type' => $entry->content->type,
                'published' => $entry->published
            );    
        }
        $this->view->documents = $documents;
        } else {
            $this->view->contents = $content
                    ->getContent('treports',$this->_getParam('slug'));
        }
    }
}