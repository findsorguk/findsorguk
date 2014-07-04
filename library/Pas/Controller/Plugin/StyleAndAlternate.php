<?php
/**
 * A front controller plugin for rendering the correct styles.
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @license http://URL name
 * @category   Pas
 * @package    Controller
 * @subpackage Plugin
 * @version 1
 */
class Pas_Controller_Plugin_StyleAndAlternate extends Zend_Controller_Plugin_Abstract {
    
    /** The javascript to add
     * @access protected
     * @var array
     */
    protected $_javascript = array(
        '/js/globalFunctions.js',
        '/js/JQuery/jquery.lightbox.js',
        '/js/bootstrap.min.js',
        '/js/JQuery/jquery.cookiesdirective.js',
        '/js/jquery.reject.js'
        );
    
    /** The headlinks to add
     * @access protected
     * @var array
     */
    protected $_headLinks = array(
            '/css/bootstrap.min.css' => 'screen',
            '/css/custom-bootstrap.css' => 'screen',
            '/css/lightbox.css' => 'screen',
            '/css/jquery.reject.css' => 'screen',
            '/css/bootstrap-responsive.min.css' => 'screen',
            '/css/print.css' => 'print'
        );
    
    /** Head meta stuff
     * @access protected
     * @var array
     */
    protected $_headMeta = array(
        'Content-Type' => 'text/html; charset=utf-8',
        'X-UA-Compatible' => 'IE=Edge',
        );
    
    /** Post dispatch add the following css and alternate links
     * @access public
     * @param Zend_Controller_Request_Abstract $request
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request) {
        $view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;
	$view->headMeta('Daniel Pett: ' . Zend_Version::VERSION, 'generator');
                
        foreach($this->_javascript as $script) {
            $location = $view->baseUrl() . $script;
            $mime = 'text/javascript';
            $view->jQuery()->addJavascriptFile($location, $mime);
        }

        foreach($this->_headLinks as $k => $v) {
            $location = $view->baseUrl() . $k;
            $view->headLink()->appendStylesheet($location, $v);
        }
        
        foreach($this->_headMeta as $k => $v) {
            $view->headMeta()->appendHttpEquiv($k, $v);
        }
        $view->headMeta()->appendName('viewport','width=device-width, initial-scale=1.0');
        $view->headLink()
                ->appendAlternate(
                        $view->serverUrl() . $view->baseUrl() 
                        . '/database/search/results/format/atom',
                        'application/rss+xml', 'Latest recorded finds feed')
		->appendAlternate(
                        $view->serverUrl(). $view->baseUrl() 
                        . '/news/index/format/atom',
                        'application/rss+xml', 'Latest Scheme news feed')
		->appendAlternate(
                        $view->serverUrl() . $view->baseUrl() 
                        . '/about/vacancies/index/format/atom', 
                        'application/atom+xml', 'Latest Scheme vacancies atom Feed')
		->appendAlternate(
                        $view->serverUrl() . $view->baseUrl() 
                        . '/research/projects/index/format/atom',
                        'application/atom+xml', 'Research projects based on Scheme data')
		->appendAlternate(
                        $view->serverUrl() . $view->baseUrl()
                        . '/blogs/centralunit/feed/', 
                        'application/atom+xml', 'Central unit blog posts')
		->appendAlternate(
                        $view->serverUrl() . $view->baseUrl() 
                        . '/events/upcoming/index/format/atom', 
                        'application/atom+xml', 'Scheme and external events as they are posted')
		->appendAlternate(
                        $view->serverUrl() . $view->baseUrl()
                        . '/database/search/results/note/1/format/atom', 
                        'application/atom+xml', 'Amazing finds recorded on the database');
        $view->headLink(array(
            'rel' => 'search',
            'href' => $view->serverUrl() . $view->baseUrl() . '/OpenSearchDatabase.xml',
            'type' =>  'application/opensearchdescription+xml',
            'title' => 'Portable Antiquities database search',
            'APPEND'));
        $view->headLink(array(
            'rel' => 'search',
            'href' => $view->serverUrl() . $view->baseUrl() .'/OpenSearchContent.xml',
            'type' =>  'application/opensearchdescription+xml',
            'title' => 'Portable Antiquities content search',
            'APPEND'));
       }
}