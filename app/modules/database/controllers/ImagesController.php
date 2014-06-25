<?php
/** Controller for displaying images
 * @todo replace some of functions when solr is installed
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_ImagesController extends Pas_Controller_Action_Admin
{
	protected $_auth, $_images, $_cache, $_zoomifyObject, $_arrayTools;
	/** Set up the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow('public',array('image','zoom','index'));
	$this->_helper->_acl->allow('member',array('add','delete','edit'));
	$this->_helper->_acl->allow('flos',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addContext('csv',array('suffix' => 'csv'))
		->addContext('kml',array('suffix' => 'kml'))
		->addContext('rss',array('suffix' => 'rss'))
		->addContext('atom',array('suffix' => 'atom'))
		->addActionContext('image', array('xml','json'))
		->addActionContext('index',array('xml','json'))
		->initContext();
	$this->_auth = Zend_Registry::get('auth');
	$this->_images = new Slides();
	$this->_cache = Zend_Registry::get('cache');
	$this->_zoomifyObject = new Pas_Zoomify_FileProcessor();
	$this->_arrayTools = new Pas_ArrayFunctions();
	}
	
	const REDIRECT 	= 'database/images/';

	const PATH 		= './images/';
	
	const THUMB		= 'thumbnails/';
	
	const SMALL		= 'small/';
	
	const MEDIUM	= 'medium/';

	const LARGE		= 'large/';
	
	const DISPLAY 	= 'display/';
	
	const EXT		= '.jpg';
	
	/** Retrieve the user's details
	*/
	private function getUserDetails()	{
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	return (array)$user;
	}
	}
	/** Display index page of images
	*/
	public function indexAction() 
	{
		//Set up form
		$form = new SolrForm();
		//Remove the thumbnail element from form
        $form->removeElement('thumbnail');
        //Send form to view
        $this->view->form = $form;
		//Clean params
        $params = $this->_arrayTools->array_cleanup($this->_getAllParams());
        //Set up search handler
        $search = new Pas_Solr_Handler();
        $search->setCore('beoimages');
        //Set search fields
        $search->setFields(array(
            'id', 'identifier', 'objecttype',
            'title', 'broadperiod', 'imagedir',
            'filename', 'thumbnail', 'old_findID',
            'county','licenseAcronym','findID',
            'institution'
            ));
        $search->setFacets(array('licenseAcronym','broadperiod','county', 'objecttype','institution'));
        //Form handler
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())
                && !is_null($this->_getParam('submit'))){
            //Check if valid
        if ($form->isValid($form->getValues())) {
        //Clean params
        $params = $this->_arrayTools->array_cleanup($form->getValues());
		//Set up redirect
        $this->_helper->Redirector->gotoSimple('index','images','database',$params);
        } else {
        //if failed, refill form
        $form->populate($form->getValues());
        $params = $form->getValues();
        }
        } else {
		//Get the parameters if not post
        $params = $this->_getAllParams();
        //Populate the form
        $form->populate($this->_getAllParams());
        }
        //If q parameter is not set or is '', set default query
        if(!isset($params['q']) || $params['q'] == ''){
            $params['q'] = '*';
        }
		//Set the search params
        $search->setParams($params);
        //Execute the search
        $search->execute();
		//Process the facets
        $search->processFacets();
        //Send pagination to view
        $this->view->paginator = $search->createPagination();
        //Send results to view
        $this->view->results = $search->processResults();
        //Send facets to view
        $this->view->facets = $search->processFacets();

	}

	/** Add a new image
	*/
	public function addAction()	 
	{
		//This could probably be sent to a view helper
		//Get the help topic about image labels
		$help = new Help();
		//Send contents to the view 
		$this->view->contents = $help->fetchRow('id = 14')->toArray();
		//Get the image form
		$form = new ImageForm();
		//Set image form label
		$form->submit->setLabel('Submit a new image.');
		//Get imagedir		
		$imagedir = '.' . $this->_helper->Identity()->imagedir;
		//Check if a directory and if not make directory
		if( !is_dir( $imagedir ) ) {
			mkdir( $imagedir, 775, true );
		}
		//Set up directory
		$form->image->setDestination( $imagedir );
		//Send form to view
		$this->view->form = $form;

		//Set up save path
		$savePath 	= $path . self::MEDIUM;
		//Set up thumbnail path
		$thumbPath 	= $path . self::THUMB;
		
		//Check if post request
		if ($this->_request->isPost()) {
		//get request data
		$formData = $this->_request->getPost();	{
		//Check if valid
		if ($form->isValid($formData)) {
		//This repeats above
		$upload = new Zend_File_Transfer_Adapter_Http();
		//Set a validator to check if the file exists
		$upload->addValidator('NotExists', false, array( $path ));
		//get the filesize
		$filesize = $upload->getFileSize();
		//Check if upload is valid
		if($upload->isValid()) 	{
			$insertData = $form->getValues();
			$insertData['filesize'] = $upload->getFileSize();
			//Receive the upload
			$upload->receive();
        }
		//Insert data into images table
		$id = $this->_images->insertImage($insertData);
		//Update the solr instance
		$this->_helper->solrUpdater->update('beoimages', $id);
		$this->_helper->solrUpdater->update('beowulf', $this->_getParam('id'));
		$this->_flashMessenger->addMessage('The image has been resized and added!');
		$this->_redirect('/database/artefacts/record/id/' . $this->_getParam('id'));
		} else {
		$this->_flashMessenger->addMessage('There is a problem with your upload. Probably that image exists.');
		$this->view->errors = $upload->getMessages();
		}
		}
	}
	}

	/** View details of a specific image
	*/
	public function imageAction() {
	if($this->_getParam('id',false)) {
	$this->view->images = $this->_images->getImage((int)$this->_getParam('id'));
	$this->view->finds = $this->_images->getLinkedFinds((int)$this->_getParam('id'));
	} else {
	throw new Exception('No parameter found on the url string');
	}
	}

	/** Edit a specific image
	*/
	public function editAction() {
	if($this->_getParam('id',0)) {
	$form = new ImageEditForm();
	$form->submit->setLabel('Update image..');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$updateData = $form->getValues();
	$where =  $this->_images->getAdapter()->quoteInto('imageID = ?', $this->_getParam('id'));
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

	$update = $this->_images->update($updateData, $where);
		//Update the solr instance
	$this->_helper->solrUpdater->update('beoimages', $this->_getParam('id'));
	
	$this->_flashMessenger->addMessage('Image and metadata updated!');
	$this->_redirect(self::REDIRECT . 'image/id/' . $this->_getParam('id'));

	} else {
	$form->populate($form->getValues());
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$image = $this->_images->getImage($id);
	$form->populate($image['0']);
	}
	}
	} else {
		throw new Exception($this->_missingParameter);
	}
	}
	/** Delete an image
	*/
	public function deleteAction() {
	$this->_flashMessenger->addMessage('Image and links deleted!');
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {

	$imagedata = $this->_images->getFileName($id);
	$filename = $imagedata['0']['f'];
	$splitf = explode('.',$filename);
	$spf = $splitf['0'];
	$imagedir = $imagedata['0']['imagedir'];
	$imagenumber = $imagedata['0']['imageID'];
	$zoom = './'.$imagedir.'zoom/'.$spf.'_zdata';
	$thumb = self::PATH . 'thumbnails/'.$imagenumber.'.jpg';
	$small = './'.$imagedir.'small/'.$filename;
	$display = './'.$imagedir.'display/'.$filename;
	$medium = './'.$imagedir.'medium/'.$filename;
	$original = './'.$imagedir.$filename;
	$where = 'imageID = ' . $id;
	$this->_images->delete($where);
	$this->_helper->solrUpdater->deleteById('images', $id);
	$linked = new FindsImages();
	$wherelinks = array();
	$wherelinks[] = $linked->getAdapter()->quoteInto('image_id = ?', $imagedata['0']['secuid']);
	$deletelinks = $linked->delete($wherelinks);
	unlink($thumb);
	unlink($display);
	unlink($small);
	unlink($original);
	unlink($medium);
	unlink(strtolower($thumb));
	unlink(strtolower($display));
	unlink(strtolower($small));
	unlink(strtolower($original));
	unlink(strtolower($medium));
	unlink($zoom);
	}
	$this->_flashMessenger->addMessage('Image and metadata deleted!');
	$this->_redirect('/database/myscheme/myimages/');
	}  else  {
	$id = (int)$this->_request->getParam('id');
	if ((int)$id > 0) {
	$this->view->slide = $this->_images->fetchRow('imageID ='.$id);
	}
	}
	}
	/** Link an image to a record
	*/
	public function linkAction() {
	if($this->_getParam('imageID',false)) {
	$form = new ImageLinkForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$updateData = array();
	$updateData['image_id'] = $this->_getParam('imageID');
	$updateData['find_id'] = $form->getValue('findID');
	$updateData['secuid'] = $this->secuid();
	$updateData['created'] = $this->getTimeForForms();
	$updateData['createdBy'] = $this->getIdentityForForms();
	foreach ($updateData as $key => $value) {
	if (is_null($value) || $value=="") {
        	unset($updateData[$key]);
      		}
	}
	$images = new FindsImages();
	$insert = $images->insert($updateData);
	$findID = $form->getValue('findID');
	$finds = new Finds();
	$returns = $finds->fetchRow($finds->select()->where('secuid = ?',$findID));
//	$this->_helper->solrUpdater->update('images', $this->_getParam('imageID'));
	$this->_helper->solrUpdater->update('beowulf', $findID);
	$returnID = $returns->id;
	$this->_flashMessenger->addMessage('You just linked an image to this record');
	$this->_redirect('/database/artefacts/record/id/' . $returnID);
	}
	}
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Unlink an image from a record
	*/
	public function unlinkAction() {
	if($this->_getParam('returnID',false)) {
	$this->view->findID = $this->_getParam('secuid');
	$this->view->returnID = $this->_getParam('returnID');
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$imageID = $this->_request->getPost('imageID');
	$findID = $this->_request->getPost('findID');
	$imagedata = $this->_images->fetchRow('imageID = ' . $id);
	$imageID = $imagedata['secuid'];
	$linked = new FindsImages();
	$where = array();

	$where[] = $linked->getAdapter()->quoteInto('image_id = ?', $imageID);
	$where[] = $linked->getAdapter()->quoteInto('find_id = ?', $findID);
	
	$linked->delete($where);
//	$this->_helper->solrUpdater->update('images', $imageID);
	$this->_helper->solrUpdater->update('beowulf', $findID);
	$this->_flashMessenger->addMessage('Links deleted!');
	$this->_redirect('/database/artefacts/record/id/' . $this->_getParam('returnID'));
	}
	} else {
	$id = (int)$this->_request->getParam('id');

	if ((int)$id > 0) {
	$this->view->slide = $this->_images->fetchRow($this->_images->select()->where('imageID = ?', $id));
	$this->view->params = $this->_getAllParams();
	}
	}
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

	/** View a zooming image of the file
	*/
	public function zoomAction() {
	if($this->_getParam('id',false)) {
	$imageID = $this->_getParam('id');
	$imagedata = $this->_images->getFileName($imageID);
	$this->view->data = $imagedata;
	$zoomdir = 'zoom/';
	$imagepath = $imagedata['0']['imagedir'];
	$filename = $imagedata['0']['f'];
	$stripped = explode('.',$filename);
	$stripped = end($stripped);
	$new = str_replace('.', '_', $filename);
	$new[strrpos($new, '_')] = '.';
	$stripit = explode('.',$new);
	$zoomedimagepath = $stripit['0'];

	$filepath = './' . $imagepath . $filename;
	$path = './' . $imagepath . $zoomdir;
	$ord = $imagepath . $zoomdir;


	if(file_exists($filepath)) {
	if(!file_exists($path)){
	mkdir($path, 0777);
	}
	if(!file_exists($path . $zoomedimagepath.'_zdata')) {

	$this->_zoomifyObject->_filegroup = "www-data"; // name of group to write files as
//	$this->_zoomifyObject->_filemode = '664';
//	$this->_zoomifyObject->_dirmode = '2775';
	$this->_zoomifyObject->_dir = $imagepath;
	$this->_zoomifyObject->_vSaveToLocation = $ord . $zoomedimagepath . '_zdata';
	$this->_zoomifyObject->ZoomifyProcess($filename, $imagepath);

	$this->view->path = $ord . $zoomedimagepath . '_zdata';

	} else {
	$this->view->path = $ord . $zoomedimagepath . '_zdata';
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	//EOF controller
}
