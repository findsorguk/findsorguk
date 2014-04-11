<?php
/** Controller for displaying various ajax request pages
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_AjaxController extends Pas_Controller_Action_Ajax {

    /** Setup the contexts by action and the ACL.
    */
    public function init() {
	$this->_helper->_acl->allow('public',NULL);
	$this->_helper->_acl->deny('public',array('nearest', 'kml', 'her', 'gis','workflow'));
	$this->_helper->_acl->deny('member',array('nearest', 'kml', 'her', 'gis','workflow'));
	$this->_helper->_acl->allow('flos',NULL);
	$this->_helper->_acl->allow('hero',NULL);
	$this->_helper->_acl->allow('research',NULL);
	$this->_helper->layout->disableLayout();
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    const REDIRECT = '/database/artefacts/';

    /** Redirect as no direct access
     *
     */
    public function indexAction() {
        $this->_redirect(self::REDIRECT);
    }

    /** Display the webcitation page
     *
     */
    public function webciteAction()	{
    if($this->_getParam('id',false)){
    $finds = new Finds();
    $this->view->finds = $finds->getWebCiteFind((int)$this->_getParam('id'));
    } else {
	throw new Pas_Exception_Param($this->_missingParameter);
    }
    }

    /** Display the find embed view
     *
    */
    public function embedAction() {
    if($this->_getParam('id',false)){
    $id = (int)$this->_getParam('id');
    $finds = new Finds();
    $this->view->finds = $finds->getEmbedFind($id);
    $thumbs = new Slides;
    $this->view->thumbs = $thumbs->getThumbnails($id);
    } else {
	throw new Pas_Exception_Param($this->_missingParameter);
    }
    }


    /** Retrieve the nearest finds to a lat lon point
     *
     */
    public function nearestAction() {
    $lat = $this->_getParam('lat');
    $long = $this->_getParam('long');
    $distance = (int)$this->_getParam('distance');
    $finds = new Finds();
    $this->view->finds = $finds->getByLatLong($lat,$long,$distance);
    $this->view->distance = $distance;
    $this->view->lat = $lat;
    $this->view->long = $long;
    }

    /** Download a file
    */
    public function downloadAction() {
    if($this->_getParam('id',false)) {
    $images = new Slides();
    $download = $images->getFileName($this->_getParam('id'));
    foreach($download as $d) {
    $filename = $d['f'];
    $path = $d['imagedir'];
    }
    $file = './' . $path . $filename;
    $mime_type = mime_content_type($file);
    if (file_exists($file)) {
    $this->_helper->viewRenderer->setNoRender();
    $this->_helper->sendFile($file,$mime_type);
    } else {
        throw new Pas_Exception_Param('That file does not exist',404);
    }
    } else {
	throw new Pas_Exception_Param($this->_missingParameter,500);
    }
    }

    /** Display rally data
    */
    public function rallydataAction() {
    $rallies = new Rallies();
    $this->view->mapping = $rallies->getMapdata();
	$this->getResponse()->setHeader('Content-type', 'text/xml');
    }

    /** Display period tag cloud
    */
    public function tagcloudAction() {
    $periods = new Periods();
    $this->view->periods = $periods->getPeriodDetails($this->_getParam('id'));
    $this->view->objects = $periods->getObjectTypesByPeriod($this->_getParam('id'));
    }

    /** Record data overlay page
    */
    public function recordAction() {
    if($this->_getParam('id',false)) {
    $this->view->recordID = $this->_getParam('id');
    $id = $this->_getParam('id');
    $finds = new Finds();
    $findsdata = $finds->getIndividualFind($id,$this->getRole());
    if(count($findsdata)) {
    $this->view->finds = $findsdata;
    } else {
	throw new Pas_Exception_NotAuthorised('You are not authorised to view this record');
    }
    $findsdata = new Finds();
    $this->view->findsdata = $findsdata->getFindData($id);
    $this->view->findsmaterial = $findsdata->getFindMaterials($id);
    $this->view->temporals = $findsdata->getFindTemporalData($id);
    $this->view->peoples = $findsdata->getPersonalData($id);
    $rallyfind = new Rallies;
    $this->view->rallyfind = $rallyfind->getFindRallyNames($id);
    $coins = new Coins;
    $this->view->coins = $coins->getCoinData($id);
    $thumbs = new Slides;
    $this->view->thumbs = $thumbs->getThumbnails($id);
    $refs = new Publications;
    $this->view->refs = $refs->getReferences($id);
    }else {
	throw new Pas_Exception_Param($this->_missingParameter,500);
    }
    }

    /** Display a report in pdf format
    */
    public function reportAction() {
    if($this->_getParam('id',false)) {
    $this->view->recordID = $this->_getParam('id');
    $id = $this->_getParam('id');
    $finds = new Finds();
    $findsdata = $finds->getIndividualFind($id,$this->getRole());
    if(count($findsdata)) {
        $this->view->finds = $findsdata;
    } else {
        throw new Pas_Exception_NotAuthorised('You are not authorised to view this record');
    }
    $findsdata = new Finds();
    $this->view->findsdata = $findsdata->getFindData($id);
    $this->view->findsmaterial = $findsdata->getFindMaterials($id);
    $this->view->temporals = $findsdata->getFindTemporalData($id);
    $this->view->peoples = $findsdata->getPersonalData($id);
    $rallyfind = new Rallies;
    $this->view->rallyfind = $rallyfind->getFindRallyNames($id);
    $coins = new Coins;
    $this->view->coins = $coins->getCoinData($id);
    $thumbs = new Slides;
    $this->view->thumbs = $thumbs->getThumbnails($id);
    $refs = new Publications;
    $this->view->refs = $refs->getReferences($id);
    $findspotsdata = new Findspots();
    $this->view->findspots = $findspotsdata->getFindSpotData($id);
    } else {
	throw new Pas_Exception_Param($this->_missingParameter,500);
    }
    }

    /** Get a find autdit overlay
     *
     *
     */
    public function auditAction() {
    $audit = new FindsAudit();
    $this->view->audit = $audit->getChange($this->_getParam('id'));
    }

    /** Get a findspot overlay from the audit table
     *
     */
    public function fsauditAction(){
    $audit = new FindSpotsAudit();
    $this->view->audit = $audit->getChange($this->_getParam('id'));
    }

    /** Get a coin overlay from the audit table
    */
    public function coinauditAction(){
    $audit = new CoinsAudit();
    $this->view->audit = $audit->getChange($this->_getParam('id'));
    }

    /** Get a saved search overlay
     *
     */

    public function savesearchAction() {
    $form = new SaveSearchForm();
    $this->view->form = $form;
    }

    /** Copy the last find
    */
    public function copyfindAction() {
    $finds = new Finds();
    $finddata = $finds->getLastRecord($this->getIdentityForForms());
    $this->_helper->layout->disableLayout();
    $this->_helper->viewRenderer->setNoRender();
    echo Zend_Json::encode($finddata);
    }

    public function mapdataAction(){

//        $this->_helper->viewRenderer->setNoRender();
	$this->_helper->layout->disableLayout();
        $params = $this->_getAllParams();
	$params['show'] = 2000;
	$params['format'] = 'json';
	$search = new Pas_Solr_Handler('beowulf');
	$search->setFields(array(
		'id','old_findID','description', 'gridref','fourFigure',
		'longitude', 'latitude', 'county', 'woeid',
		'district', 'parish','knownas', 'thumbnail'));
	$search->setParams($params);
	$search->execute();
        $this->view->results = $search->_processResults();
   }

   public function mapdata2Action(){
    $params = $this->_getAllParams();
    if(!isset($params['show'])){
	$params['show'] = 2000;
    }
	$params['format'] = 'kml';
	$search = new Pas_Solr_Handler('beowulf');
	$search->setFields(array(
		'id','old_findID','description', 'gridref','fourFigure',
		'longitude', 'latitude', 'county', 'woeid',
		'district', 'parish','knownas', 'thumbnail'));
	$search->setParams($params);
	$search->execute();
	$this->view->results = $search->_processResults();
	$this->getResponse()->setHeader('Content-type', 'text/xml');
	}


   public function exporterAction(){
   	$this->_helper->layout->disableLayout();
    $params = $this->_getAllParams();
	$params['show'] = 15000;
	$params['format'] = 'json';
	$search = new Pas_Solr_Handler('beowulf');
	$search->setFields(array(
		'id','old_findID','description', 'gridref','fourFigure',
		'longitude', 'latitude', 'county', 'woeid',
		'district', 'parish','knownas', 'thumbnail'));
	$search->setParams($params);
	$search->execute();
    $this->view->results = $search->_processResults();
   }

   public function kmlAction(){
       $exporter = new Pas_Exporter_Generate();
       $exporter->setFormat('kml');
       $this->view->results = $exporter->execute();

       $filename = 'KMLExport_' . Zend_Date::now()->toString('yyyyMMddHHmmss') . '.kml';

       $this->getResponse()->setHeader('Content-type', 'application/vnd.google-earth.kml+xml')
               ->setHeader('Content-Disposition', 'attachment; filename=' . $filename);
    }

   public function herAction(){
       $exporter = new Pas_Exporter_Generate();
       $exporter->setFormat('hero');
       $exporter->execute();
   }



   public function csvAction(){
       $exporter = new Pas_Exporter_Generate();
       $exporter->setFormat('csv');
       $exporter->execute();
   }

   public function nmsAction(){
       $exporter = new Pas_Exporter_Generate();
       $exporter->setFormat('nms');
       $data = $exporter->execute();

       $filename = 'NMSRecordsExport_For_' . $this->getUsername() . '_'. Zend_Date::now()->toString('yyyyMMddHHmmss') . '.pdf';
       $this->view->filename = $filename;
       $this->view->path = APPLICATION_PATH . '/tmp';
       $this->view->nms = $data;
//       $this->getResponse()
//               ->setHeader('Content-Disposition', 'attachment; filename=' . $filename);

   }


   public function gisAction(){

   }

   public function osdataAction(){
   	$params = $this->_getAllParams();
	$params['show'] = 5489;
	$params['format'] = 'json';
	$params['source'] = 'osdata';
	$params['sort'] = 'id';
   $q = $this->_getParam('q');
	if(is_null($q)){
	$params['q'] = 'type:R OR type:A';
	} else {
		$params['q'] = 'type:R || type:A && ' . $q;
	}
	$search = new Pas_Solr_Handler('beogeodata');
	$search->setParams($params);
	$search->setFields(array('*'));
	$search->execute();
    $this->view->results =  $search->_processResults();
   }

   public function smrsAction(){
   	$params = $this->_getAllParams();
	$params['show'] = 25046;
	$params['format'] = 'json';
	$params['sort'] = 'id';
	$params['source'] = 'smrdata';
	$search = new Pas_Solr_Handler('beogeodata');
	$search->setParams($params);
	$search->setFields(array('*'));
	$search->execute();
    $this->view->results =  $search->_processResults();
   }

   public function peopleAction(){
   	$params = $this->_getAllParams();
	$params['show'] = 5000;
	$params['format'] = 'json';
	$params['sort'] = 'id';
	$search = new Pas_Solr_Handler('beopeople');
	$search->setParams($params);
	$search->setFields(array('*'));
	$search->execute();
    $this->view->results =  $search->_processResults();
	}

   	public function facetAction(){
   	$search = new Pas_Solr_Handler('beowulf');
	$context = $this->_helper->contextSwitch->getCurrentContext();
	$fields = new Pas_Solr_FieldGeneratorFinds($context);
	$search->setFields($fields->getFields());
	$search->setFacets(array(
    'objectType','county', 'broadperiod',
    'institution', 'rulerName', 'denominationName',
    'mintName', 'materialTerm', 'workflow'));
	$search->setParams($this->_getAllParams());
	$search->execute();
    $data = array('facets' => $search->_processFacets());
	$this->view->data = $data;
	$this->view->facetName = $this->_getParam('facetType');
	}

	public function peoplefacetAction(){
   	$search = new Pas_Solr_Handler('beopeople');
	$context = $this->_helper->contextSwitch->getCurrentContext();
	$fields = new Pas_Solr_FieldGeneratorFinds($context);
	$search->setFields($fields->getFields());
	$search->setFacets(array(
    'county', 'organisation', 'activity'));
	$search->setParams($this->_getAllParams());
	$search->execute();
    $data = array('facets' => $search->_processFacets());
	$this->view->data = $data;
	$this->view->facetName = $this->_getParam('facetType');
	}


	public function imagefacetAction(){
	$search = new Pas_Solr_Handler('beoimages');
	$context = $this->_helper->contextSwitch->getCurrentContext();
	$fields = new Pas_Solr_FieldGeneratorFinds($context);
	$search->setFields($fields->getFields());
	$search->setFacets(array(
	'licenseAcronym','broadperiod','county',
    'objecttype','institution'));
	$search->setParams($this->_getAllParams());
	$search->execute();
    $data = array('facets' => $search->_processFacets());
	$this->view->data = $data;
	$this->view->facetName = $this->_getParam('facetType');
	$this->renderScript('ajax/imagesfacet.phtml');
	}

	public function myfindsfacetAction(){
	$search = new Pas_Solr_Handler('beowulf');
	$context = $this->_helper->contextSwitch->getCurrentContext();
	$fields = new Pas_Solr_FieldGeneratorFinds($context);
	$search->setFields($fields->getFields());
	$search->setFacets(array(
    'objectType','county', 'broadperiod',
    'institution', 'rulerName', 'denominationName',
    'mintName', 'materialTerm', 'workflow'));
	$params['createdBy'] = $this->_getDetails()->id;
	$search->setParams($params);
	$search->execute();
    $data = array('facets' => $search->_processFacets());
	$this->view->data = $data;
	$this->view->facetName = $this->_getParam('facetType');
	}

	public function myinstitutionfacetAction(){
	$search = new Pas_Solr_Handler('beowulf');
	$context = $this->_helper->contextSwitch->getCurrentContext();
	$fields = new Pas_Solr_FieldGeneratorFinds($context);
	$search->setFields($fields->getFields());
	$search->setFacets(array(
	'objectType','county', 'broadperiod',
    'institution', 'rulerName', 'denominationName',
    'mintName', 'materialTerm', 'workflow'
    )
	);
	$params['institution'] = $this->_getDetails()->institution;
	$search->setParams($params);
	$search->execute();
    $data = array('facets' => $search->_processFacets());
	$this->view->data = $data;
	$this->view->facetName = $this->_getParam('facetType');
	$this->renderScript('ajax/facet.phtml');
	}

	protected function _getDetails() {
    $user = new Pas_User_Details();
    return $user->getPerson();
    }

	public function myimagesfacetAction(){
   	$search = new Pas_Solr_Handler('beoimages');
	$context = $this->_helper->contextSwitch->getCurrentContext();
	$fields = new Pas_Solr_FieldGeneratorFinds($context);
	$search->setFields($fields->getFields());
	$search->setFacets(array(
	'licenseAcronym','broadperiod','county',
    'objecttype','institution'));
	$params = $this->_getAllParams();
	$params['createdBy'] = $this->_getDetails()->id;
	$search->setParams($params);
	$search->execute();
    $data = array('facets' => $search->_processFacets());
	$this->view->data = $data;
	$this->view->facetName = $this->_getParam('facetType');
	}

   public function forceindexupdateAction(){
	$this->_helper->solrUpdater->update('beowulf', $this->_getParam('findID'));
   }
   
   public function getclassestokenAction()
   {
   	$classes = new JettonGroups();
   	$this->view->json = $classes->getGroupsToClasses($this->_getParam('term'));
   	
   }
   
   public function gettypesgroupAction()
   {
   	$types = new JettonTypes();
   	$this->view->json = $types->getTypesToGroups($this->_getParam('term'));
   }


}