<?php
/**
 * Model for pulling news data from database
 *
 * An example of code:
 *
 * <code>
 * <?php
 * $model = new News();
 * $data = $model->getNews();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @since 22 September 2011
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 *
 *
 */
class News extends Pas_Db_Table_Abstract {

   /** The table name
    * @access protected
    * @var string
    */
    protected $_name = 'news';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The geoplanet service
     * @access protected
     * @var \Pas_Service_Geo_Geoplanet
     */
    protected $_geoPlanet;

    /** The geocoder
     * @access protected
     * @var \Pas_Service_Geo_Coder
     */
    protected $_geoCoder;

    /** Higher level access
     * @access protected
     * @var array
     */
    protected $_higherlevel = array('admin', 'flos', 'fa');

    /** Get the geoplanet service
     * @access public
     * @return \Pas_Service_Geo_Geoplanet
     */
    public function getGeoPlanet() {
        $this->_geoPlanet = new Pas_Service_Geo_Geoplanet(
                $this->_config->webservice->ydnkeys->appid
                );
        return $this->_geoPlanet;
    }

    /** Get the geocoder class
     * @access public
     * @return \Pas_Service_Geo_Coder
     */
    public function getGeoCoder() {
        $this->_geoCoder = new Pas_Service_Geo_Coder();
        return $this->_geoCoder;
    }

    /** Get all news articles
     * @return array
     * @access public
    */
    public function getNews() {
        $news = $this->getAdapter();
        $select = $news->select()
                ->from($this->_name)
                ->where('golive <= NOW()')
                ->where('publish_state > ?', (int)0)
                ->order('golive DESC')
                ->limit((int)25);
        return $news->fetchAll($select);
    }

    /** get all news articles headlines limited to 5
     * @return array
     * @access public
     */
    public function getHeadlines() {
        $key = md5('newsHeadlines');
        if (!$data = $this->_cache->load($key)) {
            $news = $this->getAdapter();
            $select = $news->select()
                    ->from($this->_name)
                    ->where('golive <= NOW()')
                    ->where('publish_state > ?', 0)
                    ->order('id DESC')
                    ->limit((int)5);
            $data = $news->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get all news articles paginated for public view
     * @access public
     * @param array $params
     * @return array
     */
    public function getAllNewsArticles(array $params) {
        $news = $this->getAdapter();
        $select = $news->select()
                ->from($this->_name, array(
                    'datePublished', 'title', 'id',
                    'summary', 'contents', 'created',
                    'd' =>'updated', 'latitude', 'longitude',
                    'updated', 'golive', 'author',
                    'contactEmail'
                    ))
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
                        array('fullname', 'username'))
                ->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',
                        array('fn' => 'fullname', 'un' => 'username'))
                ->joinLeft('staff', 'staff.dbaseID = users.id',
                        array('personID' => 'id'))
                ->where('golive <= NOW()')
                ->where('publish_state > ?', (int)0)
                ->order('created DESC');
        $data = $news->fetchAll($select);
        $paginator = Zend_Paginator::factory($data);
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']);
        }
        $paginator->setItemCountPerPage(10)->setPageRange(10);
        return $paginator;
    }

    /** Get all news articles paginated for admin interface
     * @access public
     * @param array $params
     * @return array
     */
    public function getAllNewsArticlesAdmin(array $params) {
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
     * @access public
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
     * @access public
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

    public function getCoords( $place ){
        $data = array();
        $coords = $this->getGeoCoder()->getCoordinates($place);
        if($coords){
            $data['latitude'] = $coords['lat'];
            $data['longitude'] = $coords['lon'];
            $place = $this->getGeoPlanet()->reverseGeoCode($data['latitude'], $data['longitude']);
            $data['woeid'] = $place['woeid'];
        } else {
            $data['latitude'] = null;
            $data['longitude']  = null;
            $data['woeid'] = null;
            }
        return $data;
    }

    /** Add news to the database
     * @access public
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function addNews(array $data){
        if(is_array($data)){
            $coords = $this->getCoords($data['primaryNewsLocation']);
            if(array_key_exists('csrf', $data)){
                unset($data['csrf']);
            }
            if(empty($data['created'])){
                $data['created'] = $this->timeCreation();
            }
            if(empty($data['createdBy'])){
                $data['createdBy'] = $this->getUserNumber();
            }
            $clean = array_merge($data, $coords);
            foreach($clean as $k => $v) {
                if ( $v == "") {
                    $clean[$k] = null;
                }
            }
        return parent::insert($clean);
        } else {
            throw new Exception(('The insert data must be in array format.'));
        }
    }

    /** Update the news
     * @access public
     * @param array $data
     * @param integer $id
     * @return array
     */
    public function updateNews(array $data, $id){
        $coords = $this->getCoords($data['primaryNewsLocation']);
        if(empty($data['updated'])){
            $data['updated'] = $this->timeCreation();
        }
        if(empty($data['updatedBy'])){
            $data['updatedBy'] = $this->getUserNumber();
        }
        $clean = array_merge($data, $coords);
        $where = array();
        $where[] =  $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return parent::update($clean, $where);
    }

    /** Get data to update the solr instance
     * @access public
     * @param integer $id
     * @return array
     */
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