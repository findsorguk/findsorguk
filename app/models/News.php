<?php
/** Model for pulling news data from database
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo 		add edit and delete functions
*/
class News extends Pas_Db_Table_Abstract {

	protected $_name = 'news';

	protected $_primary = 'id';

	protected $_higherlevel = array('admin', 'flos', 'fa');

	public function init(){
	$this->_geoPlanet = new Pas_Service_Geo_Geoplanet($this->_config->webservice->ydnkeys->appid);
	$this->_geocoder = new Pas_Service_Geo_Coder();
	}

	/** get the user's id from their identity object
	* @return integer
	*/
	public function getIdentityForForms() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	} else  {
	$id = '3';
	return $id;
	}
	}

	/** get the user's role from their identity object
	* @return String
	*
	*/
	public function getRole(){
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	return $role;
	} else {
	$role = 'public';
	return $role;
	}
	}

	/** get all news articles
	* @return array
	*
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
	*
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
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname', 'username'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname', 'un' => 'username'))
		->joinLeft('staff', 'staff.dbaseID = users.id',array('personID' => 'id'))
		->where('golive <= NOW()')
		->where('publish_state > ?', 0)		
		->order('created DESC');
	$data = $news->fetchAll($select);
	$paginator = Zend_Paginator::factory($data);
	if(isset($params['page']) && ($params['page'] != "")) {
        $paginator->setCurrentPageNumber((int)$params['page']);
	}
	$paginator->setItemCountPerPage(10)
		->setPageRange(10);
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
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'))
		->order('id DESC');
	if(in_array($this->getRole(),$this->_higherlevel)) {
	$select->where($this->_name . '.createdBy = ?',$this->getIdentityForForms());
	}
	$paginator = Zend_Paginator::factory($select);
	if(isset($params['page']) && ($params['page'] != "")) {
    $paginator->setCurrentPageNumber((int)$params['page']);
	}
	$paginator->setItemCountPerPage(10)
          ->setPageRange(10);
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
		->from($this->_name, array('created', 'd' => 'DATE_FORMAT(datePublished,"%D %M %Y")', 'title',
		'id', 'contents', 'link',
		'author', 'contactName', 'contactEmail',
		'contactTel', 'editorNotes', 'keywords',
		'golive'))
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
                ->joinLeft('staff', 'staff.dbaseID = users.id',array('personID' => 'id'))
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
		->from($this->_name, array('id','title','lat' => 'latitude', 'lon' => 'longitude','contents'))
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
	$coords = $this->_geocoder->getCoordinates($data['primaryNewsLocation']);
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
		$data['createdBy'] = $this->userNumber();
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
	$coords = $this->_geocoder->getCoordinates($data['primaryNewsLocation']);
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
	if(empty($data['updated'])){
		$data['updated'] = $this->timeCreation();
	}
	if(empty($data['updatedBy'])){
		$data['updatedBy'] = $this->userNumber();
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
