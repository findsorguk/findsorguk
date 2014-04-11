<?php
/** Retrieve and manipulate data for open calais tagged content
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/
class OpenCalaisModel extends Pas_Db_Table_Abstract {

	protected $_name = 'opencalais';

	protected $_primary = 'id';

	protected $higherlevel = array('admin','flos','fa','treasure');

	protected $restricted = array('public','member','research','hero');

	protected $edittest = array('flos','member');

	/** Get role of user
	* @return string $role
	*/
	public function getRole(){
	$auth = Zend_Auth::getInstance();
	if($auth->hasIdentity()){
	$user = $auth->getIdentity();
	$role = $user->role;
	return $role;
	} else {
	$role = 'public';
	return $role;
	}
	}

	/** Get some tagged content
	* @param integer $id content number
	* @param string $type The type of the content to retrieve
	* @return array
	*/
	public function getTaggedContent($id,$type){
	$tags = $this->getAdapter();
	$select = $tags->select()
		->from($this->_name)
		->where('contentID = ?' , (int)$id)
		->where('origin != ?', (string)'YahooGeo')
		->where('contenttype = ?',( string)$type);
	return $tags->fetchAll($select);
	}

	/** Get some geotagged content
	* @param integer $id content number
	* @param string $type The type of the content to retrieve
	* @return array
	*/
	public function getGeoTags($id,$type){
	$tags = $this->getAdapter();
	$select = $tags->select()
		->from($this->_name)
		->where('contentID = ?' , (int)$id)
		->where('contenttype = ?', (string)$type)
		->where('origin = ?', (string)'YahooGeo');
	return $tags->fetchAll($select);
	}

	/** Get some tagged content by particular tag
	* @param string $params['tag'] The tag to pull out
	* @param string $params['page'] The page number
	* @return array
	*/
	public function getRecordsByTag($params) {
	$tags = $this->getAdapter();
	$select = $tags->select()
		->from($this->_name,array('term'))
		->joinLeft('finds',$this->_name . '.contentID = finds.id',array('id', 'old_findID','objecttype',
		'broadperiod','description'))
		->joinLeft('findspots','finds.secuid = findspots.findID',array('county'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id',array())
		->joinLeft('slides','slides.secuid = finds_images.image_id',array('i' => 'imageID','f' => 'filename'))
		->joinLeft('users','users.id = finds.createdBy',array('username','fullname','institution'))
		->where('term = ?' , (string)$params['tag'])
		->where('origin != ?', (string)'YahooGeo')
		->where('contenttype = ?','findsrecord')
		->group('finds.id');
	if(in_array($this->getRole(),$this->restricted)){
	$select->where('finds.secwfstage NOT IN ( 1, 2 )');
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->setCache(Zend_Registry::get('cache'));
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber($params['page']);
	}
	return $paginator;
	}

	/** Get some geotagged content by particular tag
	* @param string $params['tag'] The tag to pull out
	* @param string $params['page'] The page number
	* @return array
	*/
	public function getRecordsByGeoTag($params) {
	$tags = $this->getAdapter();
	$select = $tags->select()
		->from($this->_name,array('term'))
		->joinLeft('finds',$this->_name . '.contentID = finds.id',
		array('id', 'old_findID', 'objecttype', 'broadperiod', 'description'))
		->joinLeft('findspots','finds.secuid = findspots.findID',array('county'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id',array())
		->joinLeft('users','users.id = finds.createdBy',array('username','fullname','institution'))
		->joinLeft('slides','slides.secuid = finds_images.image_id',array('i' => 'imageID','f' => 'filename'))
		->where('term = ?' ,(string)$params['tag'])
		->where('origin = ?', (string)'YahooGeo')
		->where('contenttype = ?','findsrecord')
		->group('finds.id');
	if(in_array($this->getRole(),$this->restricted)) {
	$select->where('finds.secwfstage NOT IN ( 1, 2 )');
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->setCache(Zend_Registry::get('cache'));
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($params['page']) && ($params['page'] != ""))  {
	$paginator->setCurrentPageNumber($params['page']);
	}
	return $paginator;
	}

	/** Get some tags for a cloud
	* @return array
	*/
	public function getTagsCloud() {
	$tags = $this->getAdapter();
	$select = $tags->select()
		->from($this->_name,array('total' => 'COUNT(*)','term'))
		->joinLeft('finds',$this->_name . '.contentID = finds.id',array())
		->where('contenttype = ?', (string)'findsrecord')
		->where('origin != ?', (string)'YahooGeo')
		->group('term');
	return $tags = $tags->fetchAll($select);
	}

	/** Get some tags for a cloud front page
	* @return array
	*/
	public function getTagsCloudFront() {
	if (!$tags = $this->_cache->load('tagsfront'.$this->getRole())){
	$tags = $this->getAdapter();
	$select = $tags->select()
		->from($this->_name,array('total' => 'COUNT(*)','term'))
		->joinLeft('finds',$this->_name . '.contentID = finds.id',array())
		->where('contenttype = ?', (string)'findsrecord')
		->where('origin != ?', (string)'YahooGeo')
		->order('total DESC')
		->group('term')
		->limit(25);
	$tags = $tags->fetchAll($select);
	$this->_cache->save($tags, 'tagsfront'.$this->getRole());
	}
	return $tags;
	}
	/** Get some tags for a cloud of geo
	* @return array
	*/
	public function getGeoTagCloud() {
	$tags = $this->getAdapter();
	$select = $tags->select()
		->from($this->_name, array('total' => 'COUNT(*)', 'term'))
		->joinLeft('finds',$this->_name . '.contentID = finds.id', array())
		->where('contenttype = ?', (string)'findsrecord')
		->where('origin = ?', (string)'YahooGeo')
		->group('term')
		->order('total DESC');
	if(in_array($this->getRole(),$this->restricted)) {
	$select->where('finds.secwfstage NOT IN ( 1, 2 )');
	}
	return $tags = $tags->fetchAll($select);
	}
}