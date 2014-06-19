<?php
/**
 * A front controller plugin for rendering the correct styles.
 * @category   Pas
 * @package    Pas_Controller
 * @subpackage Pas_Controller_Plugin
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @author 	   Daniel Pett
 * @todo	   Change the headlink to a database call for urls to append
 */
class Pas_Controller_Plugin_StyleAndAlternate
	extends Zend_Controller_Plugin_Abstract {

	public function postDispatch(Zend_Controller_Request_Abstract $request) {
	
	$view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;
	
	$view->headMeta('Built using the awesome Zend Framework (but customised by Daniel Pett): '
	. Zend_Version::VERSION,'generator');
	
	$view->baseUrl = $request->getBaseUrl();
	$view->jQuery()->addJavascriptFile($view->baseUrl() . '/js/globalFunctions.js', $type='text/javascript');
	$view->jQuery()->addJavascriptFile($view->baseUrl().'/js/JQuery/jquery.lightbox.js',$type='text/javascript');
	$view->jQuery()->addJavascriptFile($view->baseUrl() . '/js/bootstrap.min.js', $type='text/javascript');
	$view->jQuery()->addJavascriptFile($view->baseUrl() . '/js/JQuery/jquery.cookiesdirective.js', $type='text/javascript');
	$view->jQuery()->addJavascriptFile($view->baseUrl() . '/js/JQuery/cookiesconfig.js', $type='text/javascript');
	$view->jQuery()->addJavascriptFile($view->baseUrl() . '/js/jquery.reject.js', $type='text/javascript');
	
	$view->headLink()->appendStylesheet($view->baseUrl() . '/css/bootstrap.min.css', $type='screen');
	$view->headLink()->appendStylesheet($view->baseUrl() . '/css/custom-bootstrap.css', $type='screen');
	$view->headLink()->appendStylesheet($view->baseUrl() . '/css/lightbox.css', $type='screen');
	$view->headLink()->appendStylesheet($view->baseUrl() . '/css/jquery.reject.css', $type='screen');
	$view->headLink()->appendStylesheet($view->baseUrl() . '/css/bootstrap-responsive.min.css', $type='screen');
	$view->headLink()->appendStylesheet($view->baseUrl() . '/css/print.css', $type='print');
	$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8');
	$view->headMeta()->appendHttpEquiv('X-UA-Compatible', 'IE=Edge');
	$view->headMeta()->appendName('viewport','width=device-width, initial-scale=1.0');
	$view->headLink()->appendAlternate($view->serverUrl().'/database/search/results/format/atom',
		'application/rss+xml', 'Latest recorded finds feed')
		->appendAlternate($view->serverUrl(). '/news/index/format/atom',
		'application/rss+xml', 'Latest Scheme news feed')
		->appendAlternate($view->serverUrl() . '/about/vacancies/index/format/atom', 'application/atom+xml',
		'Latest Scheme vacancies atom Feed')
		->appendAlternate($view->serverUrl() . '/research/projects/index/format/atom',
		'application/atom+xml', 'Research projects based on Scheme data')
		->appendAlternate($view->serverUrl() . '/blogs/centralunit/feed/', 'application/atom+xml',
		'Central unit blog posts')
		->appendAlternate('http://api.flickr.com/services/feeds/photos_public.gne?id=10257668@N04&lang=en-us&format=atom',
		'application/atom+xml', 'Our flickr images feed')
		->appendAlternate($view->serverUrl() . '/events/upcoming/index/format/atom', 'application/atom+xml',
		'Scheme and external events as they are posted')
		->appendAlternate($view->serverUrl() . '/database/search/results/note/1/format/atom', 'application/atom+xml',
		'Amazing finds recorded on the database');
        $view->headLink(array(
            'rel' => 'search',
            'href' => $view->serverUrl() . '/OpenSearchDatabase.xml',
            'type' =>  'application/opensearchdescription+xml',
            'title' => 'Portable Antiquities database search',
            'APPEND'));
        $view->headLink(array(
            'rel' => 'search',
            'href' => $view->serverUrl() . '/OpenSearchContent.xml',
            'type' =>  'application/opensearchdescription+xml',
            'title' => 'Portable Antiquities content search',
            'APPEND'));
        
       }
       

}
