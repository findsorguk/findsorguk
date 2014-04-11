<?php
/** Controller for displaying overall statistics. 
 * @todo This is very slow due to number of queries. Maybe change to ajax calls?
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_SitemapController extends Pas_Controller_Action_Admin {
	
	/** Initialise the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->layout->disableLayout();
	$this->getResponse()->setHeader('Content-type', 'application/xml');
	ini_set("memory_limit","512M");
    }
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
	$page = $this->_getParam('page');
	$config = new Zend_Config_Xml('http://finds.org.uk/info/sitemap/databaserecords/page/' 
	. $page,'nav');
   	$navigation = new Zend_Navigation($config);
   	$this->view->navigation($navigation);
	$this->view->navigation()
		->sitemap()
		->setFormatOutput(true); // default is false
	}
	public function imagesAction() {
	$page = $this->_getParam('page');
	$config = new Zend_Config_Xml('http://finds.org.uk/info/sitemap/images/page/' 
	. $page,'nav');#
   	$navigation = new Zend_Navigation($config);
   	$this->view->navigation($navigation);
	$this->view->navigation()
		->sitemap()
		->setFormatOutput(true); // default is false
	}
	public function booksAction() {
	$page = $this->_getParam('page');
	$config = new Zend_Config_Xml('http://finds.org.uk/info/sitemap/books/page/' 
	. $page,'nav');#
   	$navigation = new Zend_Navigation($config);
   	$this->view->navigation($navigation);
	$this->view->navigation()
		->sitemap()
		->setFormatOutput(true); // default is false
	}
}

