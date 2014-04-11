<?php
/** Data model for accessing treasure valuation committee dates
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
* @todo 		implement edit and delete function methods
*/
class TvcDates extends Pas_Db_Table_Abstract {
	
	const  DBASE_ID = 'PAS';
	
	const  SECURE_ID = '001';
	
	
	protected $_primary = 'id';
	
	protected $_name = 'tvcDates';
	
	/** Construct the auth and cache objects
	* @return object
	*/
	public function init() {
		$this->_request = Zend_Controller_Front::getInstance()->getRequest();
		$this->_treasureID = Zend_Controller_Front::getInstance()->getRequest()->getParam('treasureID');
		$this->_time = Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
	}
	
	/** Construct the secuid string
	* @return string
	*/
	private function secuid(){
	    list($usec,$sec)=explode(" ", microtime());
	    $ms=dechex(round($usec*4080));
	    while(strlen($ms)<3) {$ms="0".$ms; }
	    $secuid=strtoupper(self::DBASE_ID.dechex($sec).self::SECURE_ID.$ms);
	    while(strlen($ms)<3) {$ms="0".$ms; }
	    $secuid=strtoupper(self::DBASE_ID.dechex($sec).self::SECURE_ID.$ms);
		return $secuid;
    }
	
    /** Add new TVC date to database
	* @param array $data
	* @return boolean
	*/
	public function add($data){
		if (!isset($data['created']) || ($data['created'] instanceof Zend_Db_Expr)) {
	    $data['created'] = $this->_time;
	  	}
	  	$data['secuid'] = $this->secuid();
	  	$data['createdBy'] = $this->_auth->getIdentity()->id;
		return parent::insert($data);
	}
	
	/** Get a paginated list of TVC dates
	* @param integer $page the current page requested
	* @return array
	*/
	public function listDates($page){
	$tvcs = $this->getAdapter();
	$select = $tvcs->select()
		->from($this->_name)
		->joinLeft('tvcDatesToCases','tvcDatesToCases.tvcID = ' . $this->_name . '.secuid',array('total' => 'COUNT(*)' ))
		->order($this->_name . '.date DESC')
		->group($this->_name . '.' . $this->_primary);
	$data =  $tvcs->fetchAll($select);
	$paginator = Zend_Paginator::factory($data);
	if(isset($page) && ($page != "")) {
    $paginator->setCurrentPageNumber((int)$page); 
	}
	$paginator->setItemCountPerPage(20) 
		->setPageRange(10); 
	return $paginator;
	}
	
	/** Get a specific treasure vc date
	* @param integer $id TVC id
	* @return array
	*/
	public function getDetails($id){
	$tvcs = $this->getAdapter();
	$select = $tvcs->select()
		->from($this->_name)
		->where($this->_name . '.id = ?',(int)$id);
	return $tvcs->fetchAll($select);	
	}
	
	/** Get a key value list for dropdown 
	* @return array
	*/
	public function dropdown(){
	$tvcs = $this->getAdapter();
	$select = $tvcs->select()
		->from($this->_name,array('secuid','date'))
		->order($this->_name . '.date DESC');
	$data =  $tvcs->fetchPairs($select);
	return $data;
	}
	
	/** Get a list of 12 images for a specifice TVC
	* @param integer $id a TVC id number
	* @param integer $limit number of images to return
	* @return array
	*/
	public function getImages($id,$limit = 12){
	$tvcs = $this->getAdapter();
	$select = $tvcs->select()
		->from($this->_name,array())
		->joinLeft('tvcDatesToCases','tvcDatesToCases.tvcID = ' . $this->_name . '.secuid',array())
		->joinLeft('finds','finds.treasureID = tvcDatesToCases.treasureID',array('old_findID','treasureID','id','label' => 'objecttype',
			'objecttype','broadperiod'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id',array())
		->joinLeft('slides','slides.secuid = finds_images.image_id',array('thumbnail' => 'imageID','f' => 'filename')) 
		->joinLeft(array('u' => 'users'),'slides.createdBy = u.id',array('imagedir','username'))
		->limit($limit);
	$data =  $tvcs->fetchAll($select);
	return $data;
	}
}

