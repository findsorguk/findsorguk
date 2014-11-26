<?php

/** Controller for displaying sitemaps
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Zend_Navigation
 * @uses Zend_Config_Xml
 * @uses Content
 * @uses Denominations
 * @uses Emperors
 * @uses Reeces
 * @uses Tribes
 * @uses Mints
 * @uses Reeces
 * @uses CategoriesCoins
 * @todo Scrap this controller and build xml maps for each module within their
 * code base.
 */
class Info_SitemapController extends Pas_Controller_Action_Admin
{

    /** Set up acl, display with no layout
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow(null);
        $this->_helper->layout->disableLayout();
        $this->getResponse()->setHeader('Content-type', 'application/xml');
        ini_set("memory_limit", "256M");
    }

    /** The default action - show the home page
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $config = new Zend_Config_Xml('http://finds.org.uk/info/sitemap/configuration/', 'nav');
        $navigation = new Zend_Navigation($config);
        $this->view->navigation($navigation);
        $this->view->navigation()
            ->sitemap()
            ->setFormatOutput(true); // default is false
    }

    /** Show the locational XML
     * @access public
     * @return void
     */
    public function locationsAction()
    {
        $config = new Zend_Config_Xml('http://finds.org.uk/info/sitemap/databaseconfig', 'locations');
        $navigation = new Zend_Navigation($config);
        $this->view->navigation($navigation);
        $this->view->navigation()
            ->sitemap()
            ->setFormatOutput(true); // default is false
    }

    /** The image locations xml file
     * @access public
     * @return void
     */
    public function imagelocationsAction()
    {
        $config = new Zend_Config_Xml('http://finds.org.uk/info/sitemap/imageconfig', 'locations');
        $navigation = new Zend_Navigation($config);
        $this->view->navigation($navigation);
        $this->view->navigation()
            ->sitemap()
            ->setFormatOutput(true); // default is false
    }

    /** The book locations xml file
     * @access public
     * @return void
     */
    public function booklocationsAction()
    {
        $config = new Zend_Config_Xml('http://finds.org.uk/info/sitemap/bookconfig', 'locations');
        $navigation = new Zend_Navigation($config);
        $this->view->navigation($navigation);
        $this->view->navigation()
            ->sitemap()
            ->setFormatOutput(true); // default is false
    }

    /** The database config file
     * @access public
     * @return void
     */
    public function databaseconfigAction()
    {
        //Magic in view
    }

    /** The image config file
     * @access public
     * @return void
     */
    public function imageconfigAction()
    {
        //Magic in view
    }

    /** The book config file
     * @access public
     * @return void
     */
    public function bookconfigAction()
    {
        //Magic in view
    }

    /** The database records xml file
     * @access public
     * @return void
     */
    public function databaserecordsAction()
    {
        //Magic in view
    }

    /** The images config file
     * @access public
     * @return void
     */
    public function imagesAction()
    {
        //Magic in view
    }

    /** The books location
     * @access public
     * @return void
     */
    public function booksAction()
    {
        //Magic in view
    }

    /** The configuration
     * @access public
     * @return void
     */
    public function configurationAction()
    {
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

