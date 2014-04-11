<?php
/** Controller for displaying sitemaps
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Info_SitemapController extends Pas_Controller_Action_Admin {
	
	protected $_cache;
	/** Set up acl, display with no layout
	*/		
	public function init() {
	$this->_helper->_acl->allow(null);
   	$this->_helper->layout->disableLayout();
	$this->getResponse()->setHeader('Content-type', 'application/xml');
	ini_set("memory_limit","256M");
    }
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {

	$config = new Zend_Config_Xml('http://finds.org.uk/info/sitemap/configuration/','nav');
   	$navigation = new Zend_Navigation($config);
   	$this->view->navigation($navigation);
	$this->view->navigation()
      ->sitemap()
      ->setFormatOutput(true); // default is false
	}
	/**
	 * Show the locational XML
	 */
	public function locationsAction() {
	$config = new Zend_Config_Xml('http://finds.org.uk/info/sitemap/databaseconfig','locations');
   	$navigation = new Zend_Navigation($config);
   	$this->view->navigation($navigation);
	$this->view->navigation()
      ->sitemap()
      ->setFormatOutput(true); // default is false	
	}
	
	public function imagelocationsAction() {
	$config = new Zend_Config_Xml('http://finds.org.uk/info/sitemap/imageconfig','locations');
   	$navigation = new Zend_Navigation($config);
   	$this->view->navigation($navigation);
	$this->view->navigation()
      ->sitemap()
      ->setFormatOutput(true); // default is false	
	}
	
	public function booklocationsAction() {
	$config = new Zend_Config_Xml('http://finds.org.uk/info/sitemap/bookconfig','locations');
   	$navigation = new Zend_Navigation($config);
   	$this->view->navigation($navigation);
	$this->view->navigation()
      ->sitemap()
      ->setFormatOutput(true); // default is false	
	}
	
	public function databaseconfigAction(){
		
	}
	
	public function imageconfigAction(){
		
	}
	
	public function bookconfigAction(){
		
	}
	public function databaserecordsAction(){
		
	}
	
	public function imagesAction(){
		
	}
	public function booksAction(){
	
	}
	
	public function configurationAction() {
	$content = new Content();
	$this->view->conservation = $content->getConservationNotes();
	$this->view->treasure = $content->getTreasureContent();
	$denoms = new Denominations();
	$this->view->romanDenoms = $denoms->getDenominationsSitemap('21');
	$this->view->ironageDenoms = $denoms->getDenominationsSitemap('16');
	$this->view->earlymedDenoms = $denoms->getDenominationsSitemap('47');
	$this->view->medievalDenoms = $denoms->getDenominationsSitemap('29');
	$this->view->byzantineDenoms = $denoms->getDenominationsSitemap('67');
	$this->view->greekDenoms = $denoms->getDenominationsSitemap('66');
	$this->view->postMedDenoms = $denoms->getDenominationsSitemap('36');
	$emperors = new Emperors();
	$this->view->emperors = $emperors->getEmperorsSiteMap();
	$rulers = new Rulers();
	$this->view->medrulers = $rulers->getMedievalRulersList();
	$this->view->earlymedrulers = $rulers->getEarlyMedievalRulersList();
	$this->view->postmedrulers = $rulers->getPostMedievalRulersList();
	$this->view->ironagerulers = $rulers->getIARulersList();
	$this->view->byzantinerulers = $rulers->getByzRulersList();
	$this->view->greekrulers = $rulers->getGreekRulersList();
	$mints = new Mints();
	$this->view->romanMints = $mints->getMintsSiteMap(21);
	$this->view->ironageMints = $mints->getMintsSiteMap(16);
	$this->view->byzantineMints = $mints->getMintsSiteMap(67);
	$this->view->earlymedMints = $mints->getMintsSiteMap(47);
	$this->view->medMints = $mints->getMintsSiteMap(29);
	$this->view->postmedMints = $mints->getMintsSiteMap(36);
	$this->view->greekMints = $mints->getMintsSiteMap(66);
	$reeces = new Reeces();
	$this->view->reeces = $reeces->getSiteMap(); 
	$types = new MedievalTypes();
	$this->view->medtypes = $types->getTypesSiteMap(29);
	$this->view->postmedtypes = $types->getTypesSiteMap(36);
	$this->view->earlymedtypes = $types->getTypesSiteMap(47);
	$cats = new CategoriesCoins();
	$this->view->medcats = $cats->getCatsSiteMap(29);
	$this->view->earlymedcats = $cats->getCatsSiteMap(47); 
	$this->view->postmedcats = $cats->getCatsSiteMap(36);
	$tribes = new Tribes();
	$this->view->tribes = $tribes->getSitemap();
	$news = new News();
	$this->view->news = $news->getSitemapNews();
	
	}
	
}

