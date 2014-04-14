<?php
/** Controller retrieving data from the Guardian API
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class News_GuardianController extends Pas_Controller_Action_Admin {

	
	const FORMAT = "json";
	
	const GUARDIANAPI_URL = 'http://content.guardianapis.com/';
	
	const QUERY = 'Portable Antiquities Scheme';
	
	protected $_cache;
	
	/** Initialise the ACL and contexts
	*/ 
	public function init() {
	$this->_helper->_acl->allow(null);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()
			 ->addContext('rss',array('suffix' => 'rss'))
			 ->addContext('atom',array('suffix' => 'atom'))
			 ->addActionContext('index', array('xml','json','rss','atom'))
 			 ->addActionContext('story', array('xml','json'))
             ->initContext();
	$this->_cache = Zend_Registry::get('cache');
	}
	
	public function get($url){
	$config = array(
    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
    'curloptions' => array(CURLOPT_POST =>  true,
						   CURLOPT_USERAGENT =>  $_SERVER["HTTP_USER_AGENT"],
						   CURLOPT_FOLLOWLOCATION => true,
						  // CURLOPT_HEADER => false,
						   CURLOPT_RETURNTRANSFER => true,
						   CURLOPT_LOW_SPEED_TIME => 1
						   ),
	);
	$request = $url;
	$client = new Zend_Http_Client($request, $config);
	$response = $client->request();
	
	$code = $this->getStatus($response);
	if($code == true){
		return $response->getBody();	
	} else {
		return NULL;
	}
	}
	
	private function getStatus($response) {
    $code = $response->getStatus();
    switch($code) {
    	case ($code == 200):
    		return true;
    		break;
    	case ($code == 400):
    		throw new Exception('A valid appid parameter is required for this resource');
    		break;
    	case ($code == 404):
    		throw new Exception('The resource could not be found');
    		break;
    	case ($code == 406):
    		throw new Exception('You asked for an unknown representation');
    		break;
    	default;
    		return false;
    		break;	
    }
	}
	
	public function indexAction() {
	$page = $this->_getParam('page');
	if (!($this->_cache->test('guardianpantsSearchA'))) {
	$guardian = self::GUARDIANAPI_URL 
	. 'search?q=Portable+antiquities+scheme&page-size=50&order-by=newest&format=' 
	. self::FORMAT . '&show-fields=all&show-tags=all&show-factboxes=all&show-references=all&api-key=' 
	. $this->_helper->config()->webservice->guardian->apikey;
	$articles = json_decode($this->get($guardian));
	
	$articles = json_decode($this->get($guardian));
	$this->_cache->save($articles);
	} else {
	$articles = $this->_cache->load('guardianpantsSearchA');
	}
	$tags = array();
	$results = array();
	foreach ($articles->response->results as $a){	
	if(isset($a->fields->thumbnail)) {
	$image = $a->fields->thumbnail;
	} else {
	$image = NULL;
	}
	if(isset($a->fields->standfirst)) {
	$stand = $a->fields->standfirst;
	} else {
	$stand = NULL;
	}
	$tags = array();
	 foreach($a->tags as $k => $v){
         $tags[$k] = $v;
       }
     if(isset($a->fields->byline)){
     	$byline = $a->fields->byline;
     }else {
     	$byline = NULL;
     }
	$results[] = array(
	'id' => $a->id,
	'headline' => $a->fields->headline,
	'byline' => $byline,
	'image' => $image,
	//'pubDate' => date_format(date_create($a->publicationDate),'Y/m/d'),
	'pubDate' => $a->webPublicationDate,
	'content' => $a->fields->body,
	'trailtext' => $a->fields->trailText,
	'publication' => $a->fields->publication,
	'sectionName' => $a->sectionName,
	'linkText' => $a->webTitle,
	'standfirst' => $stand,
	'section' => $a->sectionName,
	'url' => $a->webUrl,
	'shortUrl' => $a->fields->shortUrl,
	'publication' => $a->fields->publication,
	'tags' => $tags
	);
	}
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($results));
	Zend_Paginator::setCache($this->_cache);	
	if(isset($page) && ($page != "")) {
    $paginator->setCurrentPageNumber((int)$page); 
	}
	$paginator->setItemCountPerPage(20) 
    	      ->setPageRange(10); 
   	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),array('xml','json','rss','atom'))) {
   	$paginated = array();
   	foreach($paginator as $k => $v){
   	$paginated[$k] = $v;
   	}
   	$data = array('pageNumber' => $paginator->getCurrentPageNumber(),
				  'total' => number_format($paginator->getTotalItemCount(),0),
				  'itemsReturned' => $paginator->getCurrentItemCount(),
				  'totalPages' => number_format($paginator->getTotalItemCount()
   	/$paginator->getItemCountPerPage(),0));
	$this->view->data = $data;	
   	$this->view->guardianStories = array('guardianStory' => $paginated);
   	}	else {
	$this->view->data = $paginator;
   	}
	}

	public function storyAction() {
	if($this->_getParam('id',false)){
	$id = urldecode($this->_getParam('id'));
	if (!($this->_cache->test(md5('guardianpantsStory'.$id)))) {
	$guardian = self::GUARDIANAPI_URL.$id.'?format=' 
	. self::FORMAT 
	. '&show-fields=all&show-tags=all&show-factboxes=all&show-references=all&show-related=true&show-editors-picks=true&order-by=newest&api-key=' 
	. $this->_helper->config()->webservice->guardian->apikey;
	
	$articles = json_decode($this->get($guardian));
	$this->_cache->save($articles);
	} else {
	$articles = $this->_cache->load(md5('guardianpantsStory'.$id));
	}
	
	$results = array();
	foreach ($articles as $a){	
	if(isset($a->content->fields->thumbnail)) {
	$image = $a->content->fields->thumbnail;
	} else {
	$image = NULL;
	}
	if(isset($a->content->fields->standfirst)) {
	$stand = $a->content->fields->standfirst;
	} else {
	$stand = NULL;
	}
	$tags = array();
	 foreach($a->content->tags as $k => $v){
         $tags[$k] = $v;
       }
     if(isset($a->content->fields->byline)){
     	$byline = $a->content->fields->byline;
     }else {
     	$byline = NULL;
     }
	$results[] = array(
	'id' => $a->content->id,
	'headline' => $a->content->fields->headline,
	'byline' => $byline,
	'image' => $image,
	'pubDate' => $a->content->webPublicationDate,
	'content' => $a->content->fields->body,
	'trailtext' => $a->content->fields->trailText,
	'publication' => $a->content->fields->publication,
	'linkText' => $a->content->webTitle,
	'standfirst' => $stand,
	'section' => $a->content->sectionName,
	'url' => $a->content->webUrl,
	'shortUrl' => $a->content->fields->shortUrl,
	'publication' => $a->content->fields->publication,
	'tags' => $tags
	);
	}
	$related = array();
	foreach($a->relatedContent as $r){
	$related[] = array('id' => $r->id,
	'webTitle' =>  $r->webTitle,
	'webPublicationDate' => $r->webPublicationDate);
	}
	$this->view->story = $results;
	$this->view->related = $related;
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

}
