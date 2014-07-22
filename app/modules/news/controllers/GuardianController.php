<?php
/** 
 * Controller retrieving data from the Guardian API
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * 
 * 
 */
class News_GuardianController extends Pas_Controller_Action_Admin {
    /** The format to retrieve
     * 
     */
    const FORMAT = "json";

    /** The base url for the guardian api
     * 
     */
    const GUARDIANAPI_URL = 'http://content.guardianapis.com/';

    /** The default query
     * 
     */
    const QUERY = 'Portable Antiquities Scheme';

    /** The cache
     * @access protected
     * @var \Zend_Cache
     */
    protected $_cache;
    
    /** The api key to use
     * @access public
     * @var string
     */
    protected $_apikey;
    
    /** The curl class
     * @access protected
     * @var \Pas_Curl
     */
    protected $_curl;

    /** Initialise everything
     * 
     */
    public function init() {
        $this->_helper->_acl->allow(null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()
                ->addContext('rss',array('suffix' => 'rss'))
                ->addContext('atom',array('suffix' => 'atom'))
                ->addActionContext('index', array('xml','json','rss','atom'))
                ->addActionContext('story', array('xml','json'))
                ->initContext();
	$this->_cache = Zend_Registry::get('cache');
        $this->_apikey = $this->_helper->config()->webservice->guardian->apikey;
        $this->_curl = new Pas_Curl();
    }

    /** The lister function of all guardian articles
     * @access public
     */
    public function indexAction() {
	$page = $this->_getParam('page');
	if (!($this->_cache->test('guardianpantsSearchA'))) {
            $guardian = self::GUARDIANAPI_URL 
                    . 'search?q=Portable+antiquities+scheme&page-size=50&order-by=newest&format=' 
                    . self::FORMAT . '&show-fields=all&show-tags=all&show-factboxes=all&show-references=all&api-key=' 
                    . $this->_apikey;
            $this->_curl->setUri($guardian);
            $this->_curl->getRequest();
            $articles = $this->_curl->getJson();
            $this->_cache->save($articles);
	} else {
            $articles = $this->_cache->load('guardianpantsSearchA');
	}
	$tags = array();
	$results = array();
	foreach ($articles->response->results as $article){	
        if(isset($article->fields->thumbnail)) {
            $image = $article->fields->thumbnail;
        } else {
            $image = NULL;
        }

        if(isset($article->fields->standfirst)) {
            $stand = $article->fields->standfirst;
        } else {
            $stand = NULL;
        }
        $tags = array();
        foreach($article->tags as $k => $v){
            $tags[$k] = $v;
            }
        if(isset($article->fields->byline)){
            $byline = $article->fields->byline;
        } else {
            $byline = NULL;
        }
        $results[] = array(
            'id' => $article->id,
            'headline' => $article->fields->headline,
            'byline' => $byline,
            'image' => $image,
            'pubDate' => $article->webPublicationDate,
            'content' => $article->fields->body,
            'trailtext' => $article->fields->trailText,
            'publication' => $article->fields->publication,
            'sectionName' => $article->sectionName,
            'linkText' => $article->webTitle,
            'standfirst' => $stand,
            'section' => $article->sectionName,
            'url' => $article->webUrl,
            'shortUrl' => $article->fields->shortUrl,
            'publication' => $article->fields->publication,
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
            $data = array(
                'pageNumber' => $paginator->getCurrentPageNumber(),
                'total' => number_format($paginator->getTotalItemCount(),0),
                'itemsReturned' => $paginator->getCurrentItemCount(),
                'totalPages' => number_format($paginator->getTotalItemCount()
                        /$paginator->getItemCountPerPage(),0));
            $this->view->data = $data;	
            $this->view->guardianStories = array('guardianStory' => $paginated);
            } else {
                $this->view->data = $paginator;
            }
    }

    /** The story action
     * @access public
     * @throws Pas_Exception_Param
     */
    public function storyAction() {
        if($this->_getParam('id',false)){
            $id = urldecode($this->_getParam('id'));
            if (!($this->_cache->test(md5('guardianpantsStory'.$id)))) {
                $guardian = self::GUARDIANAPI_URL;
                $guardian .= $id;
                $guardian .= '?format='; 
                $guardian .= self::FORMAT;
                $guardian .= '&show-fields=all&show-tags=all&show-factboxes=all';
                $guardian .= '&show-references=all&show-related=true&show-';
                $guardian .= 'editors-picks=true&order-by=newest&api-key=';
                $guardian .= $this->_apikey;
                $this->_curl->setUri($guardian);
                $this->_curl->getRequest();
                $articles = $this->_curl->getJson();
                $this->_cache->save($articles);
            } else {
                $articles = $this->_cache->load(md5('guardianpantsStory'.$id));
            }
            $results = array();
            foreach ($articles as $article){	
                if(isset($article->content->fields->thumbnail)) {
                    $image = $article->content->fields->thumbnail;
                } else {
                    $image = NULL;
                }
    
                if(isset($article->content->fields->standfirst)) {
                    $stand = $article->content->fields->standfirst;
                } else {
                    $stand = NULL;
                }
                
                $tags = array();
                foreach($article->content->tags as $k => $v){
                    $tags[$k] = $v;
                }
 
                if(isset($article->content->fields->byline)){
                    $byline = $article->content->fields->byline;
                } else {
                    $byline = NULL;
                }
                $results[] = array(
                    'id' => $article->content->id,
                    'headline' => $article->content->fields->headline,
                    'byline' => $byline,
                    'image' => $image,
                    'pubDate' => $article->content->webPublicationDate,
                    'content' => $article->content->fields->body,
                    'trailtext' => $article->content->fields->trailText,
                    'publication' => $article->content->fields->publication,
                    'linkText' => $article->content->webTitle,
                    'standfirst' => $stand,
                    'section' => $article->content->sectionName,
                    'url' => $article->content->webUrl,
                    'shortUrl' => $article->content->fields->shortUrl,
                    'publication' => $article->content->fields->publication,
                    'tags' => $tags
                        );
                }
                $related = array();
                foreach($article->relatedContent as $r){
                    $related[] = array(
                        'id' => $r->id,
                        'webTitle' =>  $r->webTitle,
                        'webPublicationDate' => $r->webPublicationDate);
                }
                $this->view->story = $results;
                $this->view->related = $related;
                } else {
                    throw new Pas_Exception_Param($this->_missingParameter, 500);
                }
    }
}