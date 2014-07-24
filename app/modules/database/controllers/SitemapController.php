<?php
/** Controller for displaying overall statistics. 
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @todo This is very slow due to number of queries. Maybe change to ajax calls?
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Zend_Config_Xml
 * @uses Zend_Navigation
 * 
*/
class Database_SitemapController extends Pas_Controller_Action_Admin {
	
    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->layout->disableLayout();
        $this->getResponse()->setHeader('Content-type', 'application/xml');
        ini_set("memory_limit","512M");
    }
    /** The default sitemap
     * @access public
     * @return void
     */
    public function indexAction() {
        $page = $this->_getParam('page');
        $config = new Zend_Config_Xml(
                'http://finds.org.uk/info/sitemap/databaserecords/page/' 
                . $page,'nav'
                );
        $navigation = new Zend_Navigation($config);
        $this->view->navigation($navigation);
        $this->view->navigation()
                ->sitemap()
                ->setFormatOutput(true); 
    }

    /** The images sitemap
     * @access public
     * @return void
     */
    public function imagesAction() {
        $page = $this->_getParam('page');
        $config = new Zend_Config_Xml(
                'http://finds.org.uk/info/sitemap/images/page/' 
                . $page,'nav'
                );
        $navigation = new Zend_Navigation($config);
        $this->view->navigation($navigation);
        $this->view->navigation()
                ->sitemap()
                ->setFormatOutput(true); 
    }
    
    /** The books sitemap
     * @access public
     * @return void
     */
    public function booksAction() {
        $page = $this->_getParam('page');
        $config = new Zend_Config_Xml(
                'http://finds.org.uk/info/sitemap/books/page/' 
                . $page,'nav');
        $navigation = new Zend_Navigation($config);
        $this->view->navigation($navigation);
        $this->view->navigation()
                ->sitemap()
                ->setFormatOutput(true);
    }
}