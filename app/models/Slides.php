<?php

/** Data model for accessing slides data
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
* @todo 		rewrite this terrible piece of cruddy programming. Man, I've learnt since I
* 				wrote this crap. SOLR it up baby!
*/
class Slides extends Pas_Db_Table_Abstract {

	protected $_name = 'slides';

	protected $_primary = 'imageID';

	protected $_higherlevel = array('admin','flos','fa');

	protected $_restricted = array('public','member');


	/** get user's role
	* @return string
	*/
	protected function getRole() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	return $role;
	} else {
	$role = 'public';
	return $role;
	}
	}

	/** Get thumbnails for a particular find number
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	public function getThumbnails($id) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID','f' => 'filename','i' => 'imageID','label','createdBy'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id', array())
		->joinLeft('finds','finds.secuid = finds_images.find_id', array('old_findID','objecttype','id','secuid'))
		->joinLeft('users','users.id = slides.createdBy', array('username','imagedir'))
		->where('finds.id = ?', (int)$id)
		->order('slides.' . $this->_primary . ' ASC');
	return  $thumbs->fetchAll($select);
	}


	/** Get specific thumbnails
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	public function getThumb($id) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array())
		->joinLeft('finds','finds.secuid = finds_images.find_id',array('old_findID'))
		->where('finds.id = ?', (int)$id)
		->limit(1);
	return  $thumbs->fetchAll($select);
	}


	/** Get a specific image
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	public function getImage($id) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name,array('id' => 'imageID','filename','label',
		'filesize','county','period','imagerights', 'institution',
		'secuid','created','createdBy'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id', array())
		->joinLeft('finds','finds.secuid = finds_images.find_id', array('old_findID','broadperiod'))
		->joinLeft('users','users.id = slides.createdBy', array('imagedir','fullname'))
		->joinLeft('licenseType','slides.ccLicense = licenseType.id',array('license'))
		->where('slides.imageID = ?', (int)$id);
	return  $thumbs->fetchAll($select);
	}

	/** Get linked finds to an image
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	public function getLinkedFinds($id) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name)
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array('linkid' => 'id'))
		->joinLeft('finds','finds.secuid = finds_images.find_id', array('old_findID','broadperiod','objecttype',
		'findID' => 'id'))
		->joinLeft('users','users.id = slides.createdBy', array('fullname','userid' => 'id'))
		->where('slides.imageID = ?', (int)$id);
	return  $thumbs->fetchAll($select);
	}

	/** Get linked finds to an image
	* @param string $secuid
	* @return array
	* @todo add caching
	*/
	public function getImageForLinks($secuid) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name)
		->where('slides.secuid = ?', (string)$secuid);
	return  $thumbs->fetchAll($select);
	}

	/** Get the filename for an image number
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	public function getFileName($id) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('f' => 'filename','label','imageID','secuid'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id', array())
		->joinLeft('finds','finds_images.find_id = finds.secuid', array('id'))
		->joinLeft('users','users.id = slides.createdBy', array('imagedir'))
		->where($this->_name.'.imageID = ?', (int)$id);
	return  $thumbs->fetchAll($select);
	}

	/** Fetch deletion data
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	public function fetchDelete($id) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('f' => 'filename','imageID','label'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array())
		->joinLeft('users','users.id = slides.createdBy',array('imagedir'))
		->where($this->_name.'.imageID = ?', (int)$id);
	return  $thumbs->fetchAll($select);
	}












	public function getSolrData($id){
	$slides = $this->getAdapter();
	$select = $slides->select()
		->from($this->_name,array(
			'identifier' => 'CONCAT("images-",imageID)','id' => 'imageID',
			'title' => 'label', 'filename','keywords','createdBy','updated',
			'created'))
		->joinLeft('periods',$this->_name . '.period = periods.id',
		array('broadperiod' => 'term'))
		->joinLeft('finds_images','finds_images.image_id = slides.secuid',array())
		->joinLeft('finds','finds_images.find_id = finds.secuid',array('old_findID',
		'findID' => 'finds.id'))
		->joinLeft('findspots','finds.secuid = findspots.findID',array('woeid',
		'latitude' => 'declat','longitude' => 'declong',
		'coordinates' => 'CONCAT( findspots.declat,  ",", findspots.declong )',
		'county'))
		->joinLeft('users','slides.createdBy = users.id',array('imagedir','fullname'))
		->joinLeft('licenseType','slides.ccLicense = licenseType.id',array('licenseAcronym' => 'acronym' ,
		'license' => 'flickrID'))
		->where('slides.imageID = ?',(int)$id);
	return $slides->fetchAll($select);
	}
	
	public function add( $data ) {
		
	}
	
	public function updateAndProcess($data, $where){
		if(isset($data['rotate'])){
			
		}
		
		if(isset($data['regenerate'])){
			
		}
		
	$rotate = $form->getValue('rotate');
	$filename = $form->getValue('filename');
	$imagedir = $form->getValue('imagedir');
	$regenerate = $form->getValue('regenerate');
	$path = './'.$imagedir.$filename;
	$largepath = './'.$imagedir;
	$mediumpath = './'.$imagedir.'medium/';
	$smallpath = './'.$imagedir.'small/';
	$displaypath = './'.$imagedir.'display/';
	$thumbpath = self::PATH . 'thumbnails/';
	$id = $this->_getParam('id');
	$name = substr($filename, 0, strrpos($filename, '.'));
	$ext = '.jpg';
	if(isset($rotate)) {
	//rotate original
	$phMagickOriginal= new phMagick($largepath.$filename, $largepath.$filename);
	$phMagickOriginal->rotate($rotate);
	//rotate image for medium
	if(file_exists($mediumpath.$name.$ext)) {
	$phMagickMedium = new phMagick($mediumpath.$name.$ext, $mediumpath.$name.$ext);
	$phMagickMedium->rotate($rotate);
//	Zend_Debug::dump($phMagickMedium);

	} else {
	$phMagickMediumCreate = new phMagick($largepath.$filename, $mediumpath.$name.$ext);
    $phMagickMediumCreate->resize(500,0);
    $phMagickMediumCreate->rotate($rotate);
	$phMagickMediumCreate->convert();
//	Zend_Debug::dump($phMagickMediumCreate);

	}
	//rotate small image
	if(file_exists($smallpath.$name.$ext)) {
	$phMagickSmall = new phMagick($smallpath.$name.$ext, $smallpath.$name.$ext);
	$phMagickSmall->rotate($rotate);
	//Zend_Debug::dump($phMagickSmall);

	} else {
	$phMagickSmallCreate = new phMagick($largepath.$filename, $smallpath.$name.$ext);
    $phMagickSmallCreate->resize(40,0);
    $phMagickSmallCreate->rotate($rotate);
	$phMagickSmallCreate->convert();
	//Zend_Debug::dump($phMagickSmallCreate);

	}
	//rotate display image
	if(file_exists($displaypath.$name.$ext)) {
	$phMagickDisplay = new phMagick($displaypath.$name.$ext, $displaypath.$name.$ext);
	$phMagickDisplay->rotate($rotate);
//	Zend_Debug::dump($phMagickDisplay);

	} else {
	$phMagickDisplayCreate = new phMagick($largepath.$name.$ext, $displaypath.$name.$ext);
    $phMagickDisplayCreate->resize(0,150);
    $phMagickDisplayCreate->rotate($rotate);
	$phMagickDisplayCreate->convert();
	//Zend_Debug::dump($phMagickDisplayCreate);
	}
	//rotate thumbnail
	if(file_exists($thumbpath.$id.'.jpg')) {
	$phMagickThumb = new phMagick($thumbpath.$id.'.jpg', $thumbpath.$id.'.jpg');
	$phMagickThumb->rotate($rotate);
	//Zend_Debug::dump($phMagickThumb);
	} else {
	$thumbpath = self::PATH . 'thumbnails/';
	$originalpath = $path;
	$phMagickRegen = new phMagick($originalpath, $thumbpath.$id.'.jpg');
	$phMagickRegen->resize(100,0);
	$phMagickRegen->convert();

	}
	}

	if(isset($regenerate)) {
	$thumbpath = self::PATH . 'thumbnails/';
	$originalpath = $path;
	$phMagickRegen = new phMagick($originalpath, $thumbpath.$id.'.jpg');
	$phMagickRegen->resize(100,0);
	$phMagickRegen->convert();
	}
	}

}
