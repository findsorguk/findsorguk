<?php
/** Controller for displaying the photos section of the flickr module.
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Pas_Yql_Flickr
 * @uses Pas_Exception_Param
 * @uses Zend_Paginator
 * 
 * 
*/
class Flickr_PhotosController extends Pas_Controller_Action_Admin {

    /** The api key for accessing flickr
     * @access protected
     * @var string
     */
    protected $_flickr;
    
    /** The Api
     * @access protected
     * @var \Pas_Yql_Flickr
     */
    protected $_api;
    
    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->acl->allow('public',null);
        $this->_flickr = $this->_helper->config()->webservice->flickr;
        $this->_api	= new Pas_Yql_Flickr($this->_flickr);
    }

    /** No direct access to photos, goes to the index controller
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->_flashMessenger->addMessage('You can only see photos at the index page');
        $this->_redirect('/flickr/');
    }


    /** Retrieve the sets of photos we have
     * @access public
     * @return void
     */
    public function setsAction() {
        $page = $this->getPage();
        $key = md5('sets' . $page);
        if (!($this->_cache->test($key))) {
            $flickr = $this->_api->getSetsList($this->_flickr->userid, $page,10);
            $this->_cache->save($flickr);
        } else {
            $flickr = $this->_cache->load($key);
        }
        $pagination = array(
            'page'          => $page,
            'perpage'      => (int)$flickr->photosets->perpage,
            'total_results' => (int)$flickr->photosets->total
        );
        $paginator = Zend_Paginator::factory($pagination['total_results']);
        $paginator->setCurrentPageNumber($pagination['page'])
                ->setItemCountPerPage($pagination['perpage'])
                ->setCache($this->_cache);
        $this->view->paginator = $paginator;
        $this->view->photos = $flickr->photosets;
    }

    /** Find photos with a set radius of the where on earthID
    */
    public function whereonearthAction() {
        $woeid = (int)$this->_getParam('id');
        $page = $this->getPage();
        $this->view->place = $woeid;
        $key = md5('woeid' . $woeid . $page);
        if (!($this->_cache->test($key))) {
            $flickr = $this->_api->getWoeidRadius( 
                    $woeid, 
                    $radius = 500, 
                    $units = 'm',
                    $per_page = 20, 
                    $page, 
                    'archaeology', 
                    '1,2,3,4,5,6,7'
                    );
            $this->_cache->save($flickr);
        } else {
        $flickr = $this->_cache->load($key);
        }
        $total = $flickr->photos->total;
        $perpage = $flickr->photos->perpage;
        $pagination = array(
            'page'          => $page,
            'per_page'      => $perpage,
            'total_results' => (int)$total
        );
        $paginator = Zend_Paginator::factory($pagination['total_results']);
        $paginator->setCurrentPageNumber($pagination['page'])
                ->setItemCountPerPage(20)
                ->setPageRange(10)
                ->setCache($this->_cache);
        $this->view->paginator = $paginator;
        $this->view->pictures = $flickr;
    }
    
    /** Find images in a set
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function inasetAction() {
        if($this->_getParam('id',false)){
            $id = $this->_getParam('id');
            $page = $this->getPage();
            $key = md5 ('set' . $id . $page);
            if (!($this->_cache->test($key))) {
                $flickr = $this->_api->getPhotosInAset($id, 10, $page);
                $this->_cache->save($flickr);
            } else {
                $flickr = $this->_cache->load($key);
            }
            $pagination = array(
                'page'          => $page,
                'per_page'      => $flickr->photoset->perpage,
                'total_results' => (int)$flickr->photoset->total
            );
            $paginator = Zend_Paginator::factory($pagination['total_results']);
            $paginator->setCurrentPageNumber($pagination['page'])
                    ->setItemCountPerPage(10)
                    ->setCache($this->_cache);
            $paginator->setPageRange(10);
            $this->view->paginator = $paginator;
            $this->view->pictures = $flickr;
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Get a single photos's details
    */
    public function detailsAction() {
        if($this->_getParam('id',false)){
            $id = $this->_getParam('id');
            $exif = $this->_api->getPhotoExifDetails( $id );
            $this->view->exif = $exif;
            $geo = $this->_api->getGeoLocation($id);
            $this->view->geo = $geo;
            $comments = $this->_api->getPhotoCommentList($id);
            $this->view->comments = $comments;
            $image = $this->_api->getPhotoInfo($id);
            $this->view->image = $image;
            $sizes = $this->_api->getSizes($id);
            $this->view->sizes = $sizes;
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Find images tagged in a certain way.
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function taggedAction() {
	if($this->_getParam('as',false)){
            $tags = $this->_getParam('as');
            $page = $this->getPage();
            $key = md5('tagged' . $tags . $page);
            if (!($this->_cache->test($key))) {
                $flickr = $this->_api->getPhotosTaggedAs( $tags, 20, $page);
                $this->_cache->save($flickr);
            } else {
                $flickr = $this->_cache->load($key);
            }
            if(!is_null($flickr)){
                $this->view->tagtitle = $tags;
                $pagination = array(
                    'page'          => $page,
                    'per_page'      => (int)$flickr->perpage,
                    'total_results' => (int) $flickr->total
                );
                $paginator = Zend_Paginator::factory($pagination['total_results']);
                $paginator->setCurrentPageNumber($pagination['page']) ;
                $paginator->setPageRange(10);
                $paginator->setItemCountPerPage(20);
                $this->view->paginator = $paginator;
                $this->view->pictures = $flickr;
            }
	} else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
	}
    }

    /** Get a list of our favourite images
     * @access public
     * @return void
     */
    public function favouritesAction() {
        $page = $this->getPage();
        $key = md5('faves' . $page);
        if (!($this->_cache->test($key))) {
            $flickr = $this->_api->getPublicFavourites( null, null, 20, $page);
            $this->_cache->save($flickr);
        } else {
            $flickr = $this->_cache->load($key);
        }
        $pagination = array(
            'page'          => $page,
            'per_page'      => (int)$flickr->perpage,
            'total_results' => (int)$flickr->total
        );
        $paginator = Zend_Paginator::factory($pagination['total_results']);
        $paginator->setCurrentPageNumber($page)
                ->setPageRange(10)
                ->setCache($this->_cache);
        $paginator->setItemCountPerPage(20);
        $this->view->paginator = $paginator;
        $this->view->photos = $flickr;
    }

    /** Get a list of interesting flickr images attributed to archaeology
     * The woeid 23424975 = United Kingdom
     * @access public
     * @return void
    */
    public function interestingAction() {
        $page = $this->getPage();
        $key = md5('interesting' . $page);
        if (!($this->_cache->test($key))) {
            $flickr = $this->_api->getArchaeology( 'archaeology', 20, $page, 23424975);
            $this->_cache->save($flickr);
        } else {
            $flickr = $this->_cache->load($key);
        }
        $pagination = array(
            'page'          => $page,
            'total_results' => (int)$flickr->total
        );
        $paginator = Zend_Paginator::factory($pagination['total_results']);
        $paginator->setCurrentPageNumber($page)
                ->setPageRange(10)
                ->setCache($this->_cache);
        $paginator->setItemCountPerPage(20);
        $this->view->paginator = $paginator;
        $this->view->photos = $flickr;
    }
}