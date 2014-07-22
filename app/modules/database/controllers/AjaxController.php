<?php
/** 
 * Controller for displaying various ajax request pages
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 2
 * 
 * 
*/
class Database_AjaxController extends Pas_Controller_Action_Ajax {

    /** The finds model
     * @access protected
     * @var \Finds
     */
    protected $_finds;
    
    /** Get the finds model
     * @access public
     * @return \Finds
     */
    public function getFinds() {
        $this->_finds = new Finds();
        return $this->_finds;
    }
    /** Setup the contexts by action and the ACL.
     * @access public
     */
    public function init() {
	$this->_helper->_acl->allow('public',NULL);
	$this->_helper->_acl->deny('public',array(
            'nearest', 'kml', 'her', 'gis','workflow')
                );
	$this->_helper->_acl->deny('member',array(
            'nearest', 'kml', 'her', 'gis','workflow')
                );
	$this->_helper->_acl->allow('flos',NULL);
	$this->_helper->_acl->allow('hero',NULL);
	$this->_helper->_acl->allow('research',NULL);
	$this->_helper->layout->disableLayout();
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    /** The base redirect
     * 
     */
    const REDIRECT = '/database/artefacts/';

    /** Redirect as no direct access
     * @access public
     */
    public function indexAction() {
        $this->_redirect(self::REDIRECT);
    }

    /** Display the webcitation page
     * @access public
     */
    public function webciteAction()	{
        if($this->_getParam('id',false)){
            $this->view->finds = $this->getFinds()->getWebCiteFind((int)$this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Display the find embed view
     * @access public
     */
    public function embedAction() {
        if($this->_getParam('id',false)){
            $id = (int)$this->_getParam('id');
            $this->view->finds = $this->getFinds()->getEmbedFind($id);
            $thumbs = new Slides;
            $this->view->thumbs = $thumbs->getThumbnails($id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Retrieve the nearest finds to a lat lon point
     * @access public
     */
    public function nearestAction() {
        $lat = $this->_getParam('lat');
        $long = $this->_getParam('long');
        $distance = (int)$this->_getParam('distance');
        $this->view->finds = $this->getFinds()->getByLatLong($lat, $long, $distance);
        $this->view->distance = $distance;
        $this->view->lat = $lat;
        $this->view->long = $long;
    }

    /** Download a file
     * @access public
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
                throw new Pas_Exception_Param('That file does not exist', 404);
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Display rally data
     * @access public
     */
    public function rallydataAction() {
        $rallies = new Rallies();
        $this->view->mapping = $rallies->getMapdata();
	$this->getResponse()->setHeader('Content-type', 'text/xml');
    }

    /** Display period tag cloud
     * @access public
     * @todo Deprecate?
     */
    public function tagcloudAction() {
        $periods = new Periods();
        $this->view->periods = $periods->getPeriodDetails($this->_getParam('id'));
        $this->view->objects = $periods->getObjectTypesByPeriod($this->_getParam('id'));
    }

    /** Record data overlay page
     * @access public
     */
    public function recordAction() {
        if($this->_getParam('id',false)) {
            $this->view->recordID = $this->_getParam('id');
            $id = $this->_getParam('id');
            $findsdata = $this->getFinds()->getIndividualFind($id,$this->getRole());
            if(!empty($findsdata)) {
                $this->view->finds = $findsdata;
            } else {
                throw new Pas_Exception_NotAuthorised('You are not authorised to view this record');
            }
            $this->view->findsdata = $this->getFinds()->getFindData($id);
            $this->view->findsmaterial = $this->getFinds()->getFindMaterials($id);
            $this->view->temporals = $this->getFinds()->getFindTemporalData($id);
            $this->view->peoples = $this->getFinds()->getPersonalData($id);
            $rallyfind = new Rallies;
            $this->view->rallyfind = $rallyfind->getFindRallyNames($id);
            $coins = new Coins;
            $this->view->coins = $coins->getCoinData($id);
            $thumbs = new Slides;
            $this->view->thumbs = $thumbs->getThumbnails($id);
            $refs = new Publications;
            $this->view->refs = $refs->getReferences($id);
            } else {
                throw new Pas_Exception_Param($this->_missingParameter,500);
            }
    }

    /** Display a report in pdf format
     * @access public
     */
    public function reportAction() {
        if($this->_getParam('id',false)) {
            $this->view->recordID = $this->_getParam('id');
            $id = $this->_getParam('id');
            $findsdata = $this->getFinds()->getIndividualFind($id,$this->getRole());
            if(count($findsdata)) {
                $this->view->finds = $findsdata;
            } else {
                throw new Pas_Exception_NotAuthorised('You are not authorised to view this record');
            }
            $this->view->findsdata = $this->getFinds()->getFindData($id);
            $this->view->findsmaterial = $this->getFinds()->getFindMaterials($id);
            $this->view->temporals = $this->getFinds()->getFindTemporalData($id);
            $this->view->peoples = $this->getFinds()->getPersonalData($id);
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
     * @access public
     */
    public function auditAction() {
        $audit = new FindsAudit();
        $this->view->audit = $audit->getChange($this->_getParam('id'));
    }

    /** Get a findspot overlay from the audit table
     * @access public
     */
    public function fsauditAction(){
        $audit = new FindSpotsAudit();
        $this->view->audit = $audit->getChange($this->_getParam('id'));
    }

    /** Get a coin overlay from the audit table
     * @access public
     */
    public function coinauditAction(){
        $audit = new CoinsAudit();
        $this->view->audit = $audit->getChange($this->_getParam('id'));
    }

    /** Get a saved search overlay
     * @access public
     */
    public function savesearchAction() {
        $form = new SaveSearchForm();
        $this->view->form = $form;
    }

    /** Copy the last find
     * @access public
     */
    public function copyfindAction() {
        $finddata = $this->getFinds()->getLastRecord($this->getIdentityForForms());
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        echo Zend_Json::encode($finddata);
    }

    /** Get the mapdata for layouts
     * @access public
     */
    public function mapdataAction(){
	$this->_helper->layout->disableLayout();
        $params = $this->_getAllParams();
	$params['show'] = 2000;
	$params['format'] = 'json';
	$search = new Pas_Solr_Handler();
        $search->setCore('beowulf');
	$search->setFields(array(
            'id','old_findID','description', 
            'gridref','fourFigure', 'longitude',
            'latitude', 'county', 'woeid',
            'district', 'parish','knownas', 
            'thumbnail'
            ));
	$search->setParams($params);
	$search->execute();
        $this->view->results = $search->processResults();
   }

   /** Secondary version of the mapdata function
    * @access public
    * @todo deprecate?
    */
   public function mapdata2Action(){
        $params = $this->_getAllParams();
        if(!isset($params['show'])){
            $params['show'] = 2000;
        }
	$params['format'] = 'kml';
	$search = new Pas_Solr_Handler();
        $search->setCore('beowulf');
	$search->setFields(array(
            'id','old_findID','description', 
            'gridref','fourFigure', 'longitude', 
            'latitude', 'county', 'woeid',
            'district', 'parish','knownas', 
            'thumbnail'
            ));
	$search->setParams($params);
	$search->execute();
	$this->view->results = $search->processResults();
	$this->getResponse()->setHeader('Content-type', 'text/xml');
    }
    
    /** Exporter action
     * @access public
     */
    public function exporterAction(){
   	$this->_helper->layout->disableLayout();
        $params = $this->_getAllParams();
	$params['show'] = 15000;
	$params['format'] = 'json';
	$search = new Pas_Solr_Handler();
        $search->setCore('beowulf');
	$search->setFields(array(
            'id','old_findID','description', 
            'gridref','fourFigure', 'longitude', 
            'latitude', 'county', 'woeid',
            'district', 'parish','knownas', 
            'thumbnail'
            ));
	$search->setParams($params);
	$search->execute();
    $this->view->results = $search->processResults();
   }

   /** Create KML action
    * 
    */
   public function kmlAction(){
       $exporter = new Pas_Exporter_Generate();
       $exporter->setFormat('kml');
       $this->view->results = $exporter->execute();
       $filename = 'KMLExport_' . Zend_Date::now()->toString('yyyyMMddHHmmss') . '.kml';
       $this->getResponse()->setHeader('Content-type', 'application/vnd.google-earth.kml+xml')
               ->setHeader('Content-Disposition', 'attachment; filename=' . $filename);
    }

    /** The HER export action
     * 
     */
   public function herAction(){
       $exporter = new Pas_Exporter_Generate();
       $exporter->setFormat('hero');
       $exporter->execute();
   }

   /** Create CSV export
    * 
    */
   public function csvAction(){
       $exporter = new Pas_Exporter_Generate();
       $exporter->setFormat('csv');
       $exporter->execute();
   }

   /** The Norfolk exporter
    * 
    */
   public function nmsAction(){
       $exporter = new Pas_Exporter_Generate();
       $exporter->setFormat('nms');
       $data = $exporter->execute();
       $filename = 'NMSRecordsExport_For_' . $this->getUsername() . '_'. Zend_Date::now()->toString('yyyyMMddHHmmss') . '.pdf';
       $this->view->filename = $filename;
       $this->view->path = APPLICATION_PATH . '/tmp';
       $this->view->nms = $data;
   }

   /** An action for exporting as GIS shp files
    * @access public
    */
   public function gisAction(){
       //Unused
   }

   /** Dump out all antiquities from the OS 1:50k
    * @access public
    */
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
	$search = new Pas_Solr_Handler();
        $search->setCore('beogeodata');
	$search->setParams($params);
	$search->setFields(array('*'));
	$search->execute();
        $this->view->results =  $search->processResults();
   }

   /** Dump out SMR data
    * @access public
    */
   public function smrsAction(){
   	$params = $this->_getAllParams();
	$params['show'] = 25046;
	$params['format'] = 'json';
	$params['sort'] = 'id';
	$params['source'] = 'smrdata';
	$search = new Pas_Solr_Handler();
        $search->setCore('beogeodata');
	$search->setParams($params);
	$search->setFields(array('*'));
	$search->execute();
        $this->view->results =  $search->processResults();
   }

   /** The people action
    * @access public
    */
   public function peopleAction(){
   	$params = $this->_getAllParams();
	$params['show'] = 5000;
	$params['format'] = 'json';
	$params['sort'] = 'id';
	$search = new Pas_Solr_Handler();
        $search->setCore('beopeople');
	$search->setParams($params);
	$search->setFields(array('*'));
	$search->execute();
        $this->view->results =  $search->processResults();
   }

    public function facetAction(){
        $search = new Pas_Solr_Handler();
        $search->setCore('beowulf');
        $context = $this->_helper->contextSwitch->getCurrentContext();
        $fields = new Pas_Solr_FieldGeneratorFinds($context);
    //	$search->setFields($fields->getFields());
        $search->setFacets(array(
            'objectType','county', 'broadperiod',
            'institution', 'rulerName', 'denominationName',
            'mintName', 'materialTerm', 'workflow'
            ));
        $search->setParams($this->_getAllParams());
        $search->execute();
        $data = array('facets' => $search->processFacets());
        $this->view->data = $data;
        $this->view->facetName = $this->_getParam('facetType');
    }

    /** The people facet generator
     * @access public
     */
    public function peoplefacetAction(){
        $search = new Pas_Solr_Handler();
        $search->setCore('beopeople');
        $context = $this->_helper->contextSwitch->getCurrentContext();
        $fields = new Pas_Solr_FieldGeneratorFinds($context);
        $search->setFields($fields->getFields());
        $search->setFacets(array(
            'county', 'organisation', 'activity'));
        $search->setParams($this->_getAllParams());
        $search->execute();
        $data = array('facets' => $search->processFacets());
        $this->view->data = $data;
        $this->view->facetName = $this->_getParam('facetType');
    }


    /** The image facet generator
     * @access public
     */
    public function imagefacetAction(){
        $search = new Pas_Solr_Handler();
        $search->setCode('beoimages');
        $context = $this->_helper->contextSwitch->getCurrentContext();
        $fields = new Pas_Solr_FieldGeneratorFinds($context);
        $search->setFields($fields->getFields());
        $search->setFacets(array(
            'licenseAcronym','broadperiod','county',
            'objecttype','institution'
            ));
        $search->setParams($this->_getAllParams());
        $search->execute();
        $data = array('facets' => $search->processFacets());
        $this->view->data = $data;
        $this->view->facetName = $this->_getParam('facetType');
        $this->renderScript('ajax/imagesfacet.phtml');
    }

    /** Generate my finds facet overlay
     * @access public
     */
    public function myfindsfacetAction(){
        $search = new Pas_Solr_Handler();
        $search->setCore('beowulf');
        $context = $this->_helper->contextSwitch->getCurrentContext();
        $fields = new Pas_Solr_FieldGeneratorFinds($context);
        $search->setFields($fields->getFields());
        $search->setFacets(array(
            'objectType','county', 'broadperiod',
            'institution', 'rulerName', 'denominationName',
            'mintName', 'materialTerm', 'workflow'
            ));
        $params['createdBy'] = $this->getIdentityForForms();
        $search->setParams($params);
        $search->execute();
        $data = array('facets' => $search->processFacets());
        $this->view->data = $data;
        $this->view->facetName = $this->_getParam('facetType');
    }

    /** My institution facet overlay
     * @access public
     */
    public function myinstitutionfacetAction(){
        $search = new Pas_Solr_Handler();
        $search->setCore('beowulf');
        $context = $this->_helper->contextSwitch->getCurrentContext();
        $fields = new Pas_Solr_FieldGeneratorFinds($context);
        $search->setFields($fields->getFields());
        $search->setFacets(array(
            'objectType','county', 'broadperiod',
            'institution', 'rulerName', 'denominationName',
            'mintName', 'materialTerm', 'workflow'
            ));
        $params['institution'] = $this->getInstitution();
        $search->setParams($params);
        $search->execute();
        $data = array('facets' => $search->processFacets());
        $this->view->data = $data;
        $this->view->facetName = $this->_getParam('facetType');
        $this->renderScript('ajax/facet.phtml');
    }

    /** My images facet overlay
     * @access public
     */

    public function myimagesfacetAction(){
        $search = new Pas_Solr_Handler();
        $search->setCore('beoimages');
        $context = $this->_helper->contextSwitch->getCurrentContext();
        $fields = new Pas_Solr_FieldGeneratorFinds($context);
        $search->setFields($fields->getFields());
        $search->setFacets(array(
            'licenseAcronym','broadperiod','county',
            'objecttype','institution'
            ));
        $params = $this->_getAllParams();
        $params['createdBy'] = $this->getIdentityForForms();
        $search->setParams($params);
        $search->execute();
        $data = array('facets' => $search->processFacets());
        $this->view->data = $data;
        $this->view->facetName = $this->_getParam('facetType');
    }

    /** Force an index update
     * @access public
     */
   public function forceindexupdateAction(){
	$this->_helper->solrUpdater->update('beowulf', $this->_getParam('findID'));
   }
   
   /** Get the classes to token
    * @access public
    */
   public function getclassestokenAction(){
   	$classes = new JettonGroups();
   	$this->view->json = $classes->getGroupsToClasses($this->_getParam('term'));
   }
   
   /** Get jetton types
    * @access public
    */
   public function gettypesgroupAction(){
   	$types = new JettonTypes();
   	$this->view->json = $types->getTypesToGroups($this->_getParam('term'));
   }
}