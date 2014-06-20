<?php
/** 
 * Model for pulling news data from database
 * 
 * @category Pas
 * @package Pas_Db_Table
 * @subpackage Abstract
 * @since 22 September 2011
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @license
 * 
 */
class News extends Pas_Db_Table_Abstract {

    protected $_name = 'news';

    protected $_primary = 'id';

    protected $_geoPlanet;
    
    protected $_geoCoder;
    
    protected $_higherlevel = array('admin', 'flos', 'fa');

    /** Initialise the objects
     * @access public
     */
    public function init(){
        $this->_geoPlanet = new Pas_Service_Geo_Geoplanet(
                Zend_Registry::get('config')->webservice->ydnkeys->appid
                );
	$this->_geoCoder = new Pas_Service_Geo_Coder();
    }

    /** 
     * Get all news articles
     * @return array
     * @access public
    */
    public function getNews() {
        $news = $this->getAdapter();
        $select = $news->select()
                ->from($this->_name)
                ->where('golive <= NOW()')
                ->where('publish_state > ?', 0)
                ->order('golive DESC')
                ->limit((int)25);
        return $news->fetchAll($select);
    }

    /** get all news articles headlines limited to 5
     * @return array
     * @access public
     */
    public function getHeadlines() {
        if (!$data = $this->_cache->load('headlines')) {
            $news = $this->getAdapter();
            $select = $news->select()
                    ->from($this->_name)
                    ->where('golive <= NOW()')
                    ->where('publish_state > ?', 0)
                    ->order('id DESC')
                    ->limit((int)5);
            $data = $news->fetchAll($select);
            $this->_cache->save($data, 'headlines');
        }
        return $data;
    }

    /** get all news articles paginated for public view
    * @param integer $params['page']
    * @return array
    *
    */
    public function getAllNewsArticles($params) {
        $news = $this->getAdapter();
        $select = $news->select()
                ->from($this->_name, array(
                    'datePublished', 'title', 'id',
                    'summary', 'contents', 'created',
                    'd' =>'updated', 'latitude', 'longitude',
                    'updated', 'golive', 'author',
                    'contactEmail'))
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
                        array('fullname', 'username'))
                ->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', 
                        array('fn' => 'fullname', 'un' => 'username'))
                ->joinLeft('staff', 'staff.dbaseID = users.id',
                        array('personID' => 'id'))
                ->where('golive <= NOW()')
                ->where('publish_state > ?', 0)		
                ->order('created DESC');
        $data = $news->fetchAll($select);
        $paginator = Zend_Paginator::factory($data);
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']);
        }
        $paginator->setItemCountPerPage(10)->setPageRange(10);
        return $paginator;
    }

    /** get all news articles paginated for admin interface
    * @param integer $params['page']
    * @return array
    *
    */
    public function getAllNewsArticlesAdmin($params) {
        $news = $this->getAdapter();
        $select = $news->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
                        array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', 
                        array('fn' => 'fullname'))
                ->order('id DESC');
        if(in_array($this->getUserRole(),$this->_higherlevel)) {
            $select->where($this->_name . '.createdBy = ?',$this->getUserNumber());
        }
        $paginator = Zend_Paginator::factory($select);
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']);
        }
        $paginator->setItemCountPerPage(10)->setPageRange(10);
        return $paginator;
    }

    /** Retrieve a news story by the id number
    * @param integer $id
    * @return array
    * @todo change to by slug eventually and make nicer urls.
    */
    public function getStory($id) {
        $news = $this->getAdapter();
        $select = $news->select()
                ->from($this->_name, array(
                    'created', 'd' => 'DATE_FORMAT(datePublished,"%D %M %Y")', 
                    'title', 'id', 'contents', 'link',
                    'author', 'contactName', 'contactEmail',
                    'contactTel', 'editorNotes', 'keywords',
                    'golive'))
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
                        array('fullname'))
                ->joinLeft('staff', 'staff.dbaseID = users.id', 
                        array('personID' => 'id'))
                ->where('news.id = ?',(int)$id)
                ->order('datePublished DESC');
        return $news->fetchAll($select);
    }

    /** Retrieve  news stories to show on map or as kml
    * @return array
    * @todo add caching?
    */
    public function getMapData() {
        $events = $this->getAdapter();
        $select = $events->select()
                ->from($this->_name, array(
                    'id','title','lat' => 'latitude', 'lon' => 'longitude',
                    'contents'))
                ->where('latitude IS NOT NULL');
        return $events->fetchAll($select);
    }

    /** Retrieve news stories for site map that are available publicly
    * @return array
    * @todo add caching?
    */
    public function getSitemapNews(){
        if (!$data = $this->_cache->load('newscached')) {
            $news = $this->getAdapter();
            $select = $news->select()
                    ->from($this->_name,array('id','title','updated'))
                    ->where('golive <= CURDATE()')
                    ->order('id DESC');
            $data = $news->fetchAll($select);
            $this->_cache->save($data, 'newscached');
        }
        return $data;
    }

    public function addNews($data){
        if(is_array($data)){
            $coords = $this->_geoCoder->getCoordinates($data['primaryNewsLocation']);
        
            if($coords){
                $data['latitude'] = $coords['lat'];
                $data['longitude'] = $coords['lon'];
                $place = $this->_geoPlanet->reverseGeoCode($data['latitude'],$data['longitude']);
                $data['woeid'] = $place['woeid'];
            } else {
                $data['latitude'] = NULL;
                $data['longitude']  = NULL;
                $data['woeid'] = NULL;
            }
        
            if(array_key_exists('csrf', $data)){
                unset($data['csrf']);
            }
            if(empty($data['created'])){
                $data['created'] = $this->timeCreation();
            }
            if(empty($data['createdBy'])){
                $data['createdBy'] = $this->getUserNumber();
            }
        
            foreach($data as $k => $v) {
                if ( $v == "") {
                    $data[$k] = NULL;
                }
            }
        return parent::insert($data);
        } else {
            throw new Exception(('The insert data must be in array format.'));
        }
    }

    public function updateNews($data, $id){
        $coords = $this->_geoCoder->getCoordinates($data['primaryNewsLocation']);
        if($coords){
            $data['latitude'] = $coords['lat'];
            $data['longitude'] = $coords['lon'];
            $place = $this->_geoPlanet->reverseGeoCode($data['latitude'],$data['longitude']);
            $data['woeid'] = $place['woeid'];
        } else {
            $data['longitude']  = NULL;
            $data['latitude'] = NULL;
            $data['woeid'] = NULL;
        }
        if(empty($data['updated'])){
            $data['updated'] = $this->timeCreation();
        }
        if(empty($data['updatedBy'])){
            $data['updatedBy'] = $this->getUserNumber();
        }
        $where = array();
        $where[] =  $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return parent::update($data, $where);
    }

    public function getSolrData($id){
        $contents = $this->getAdapter();
        $select = $contents->select()->from($this->_name,array(
                        'identifier' => 'CONCAT("news-",news.id)',
                        'id',
                        'title',
                        'excerpt' => 'summary',
                        'body' => 'contents',
                        'created',
                        'updated',
                         ))
                ->where($this->_name .  '.id = ?',(int)$id);
        $data = $contents->fetchAll($select);
        $data[0]['type'] = 'news';
        $data[0]['section'] = 'news';
        return $data;
    }
}