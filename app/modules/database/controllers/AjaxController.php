<?php

/**
 * Controller for displaying various ajax request pages
 *
 * @author     Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license    http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version    2
 * @uses       Finds
 * @uses       Pas_Exception_Param
 * @uses       Slides
 * @uses       Rallies
 * @uses       Periods
 * @uses       Coins
 * @uses       Publications
 * @uses       Pas_Exception_NotAuthorised
 * @uses       Findspots
 * @uses       FindsAudit
 * @uses       FindSpotsAudit
 * @uses       CoinsAudit
 * @uses       SaveSearchForm
 * @uses       Pas_Solr_Handler
 * @uses       Pas_Solr_FieldGeneratorFinds
 * @uses       Pas_Exporter_Generate
 * @uses       JettonGroups
 * @uses       JettonTypes
 */
class Database_AjaxController extends Pas_Controller_Action_Ajax
{

    /** The base redirect
     *
     */
    const REDIRECT = '/database/artefacts/';
    /** The finds model
     *
     * @access protected
     * @var \Finds
     */
    protected $_finds;
    /** The hoards model
     *
     * @access protected
     * @var \Hoards
     */
    protected $_hoards;

    /** Setup the contexts by action and the ACL.
     *
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('public', null);
        $this->_helper->_acl->deny('public', array('her', 'gis', 'workflow'));
        $this->_helper->_acl->deny('member', array('her', 'gis', 'workflow'));
        $this->_helper->_acl->allow('flos', null);
        $this->_helper->_acl->allow('hero', null);
        $this->_helper->_acl->allow('research', null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->addActionContext(
            'timeline',
            array('json')
        )->initContext();
        $this->_helper->layout->disableLayout();
    }

    /** Redirect as no direct access
     *
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->redirect(self::REDIRECT);
    }

    /** Display the web citation page
     *
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function webciteAction()
    {
        if ($this->getParam('id', false)) {
            $this->view->type = $this->getParam('type');
            if ($this->getParam('type') == 'artefacts') {
                $this->view->finds = $this->getFinds()->getWebCiteFind(
                    $this->getParam('id')
                );
            } else {
                $this->view->finds = $this->getHoards()->getWebCiteHoard(
                    $this->getParam('id')
                );
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Get the finds model
     *
     * @access public
     * @return \Finds
     */
    public function getFinds()
    {
        $this->_finds = new Finds();
        return $this->_finds;
    }

    /** Get the hoards model
     *
     * @return mixed
     */
    public function getHoards()
    {
        $this->_hoards = new Hoards();
        return $this->_hoards;
    }

    /** Display the find embed view
     *
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function embedAction()
    {
        if ($this->getParam('id', false)) {
            $id = (int)$this->getParam('id');
            $this->view->type = $this->getParam('type');
            if ($this->getParam('type') == 'artefacts') {
                $this->view->finds = $this->getFinds()->getEmbedFind($id);
                $thumbs = new Slides;
                $this->view->thumbs = $thumbs->getThumbnails($id, 'artefacts');
            } else {
                $this->view->finds = $this->getHoards()->getEmbedHoard($id);
                $thumbs = new Slides;
                $this->view->thumbs = $thumbs->getThumbnails($id, 'hoards');
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Download a file
     *
     * @access public
     * @return void
     * @throws \Pas_Exception_Param
     */
    public function downloadAction()
    {
        if ($this->getParam('id', false)) {
            $images = new Slides();
            $download = $images->getFileName(
                $this->getParam('id'),
                'artefacts'
            );
            foreach ($download as $d) {
                $filename = $d['f'];
                $path = $d['imagedir'];
            }
            $file = './' . $path . $filename;
            $mime_type = mime_content_type($file);
            if (file_exists($file)) {
                $this->_helper->viewRenderer->setNoRender();
                $this->_helper->sendFile($file, $mime_type);
            } else {
                throw new Pas_Exception_Param('That file does not exist', 404);
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Display rally data
     *
     * @access public
     * @return void
     */
    public function rallydataAction()
    {
        $rallies = new Rallies();
        $this->view->mapping = $rallies->getMapdata();
        $this->getResponse()->setHeader('Content-type', 'text/xml');
    }

    /** Record data overlay page
     *
     * @access public
     * @return void
     * @throws Pas_Exception_NotAuthorised
     * @throws Pas_Exception_Param
     */
    public function recordAction()
    {
        if ($this->getParam('id', false)) {
            $this->view->recordID = $this->getParam('id');
            $id = $this->getParam('id');
            $findsdata = $this->getFinds()->getIndividualFind(
                $id,
                $this->getRole()
            );
            if (!empty($findsdata)) {
                $this->view->finds = $findsdata;
            } else {
                throw new Pas_Exception_NotAuthorised(
                    'You are not authorised to view this record'
                );
            }
            $this->view->findsdata = $this->getFinds()->getFindData($id);
            $this->view->findsmaterial = $this->getFinds()->getFindMaterials(
                $id
            );
            $this->view->temporals = $this->getFinds()->getFindTemporalData(
                $id
            );
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
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Display a report in pdf format
     *
     * @return void
     * @access public
     * @throws Pas_Exception_NotAuthorised
     * @throws Pas_Exception_Param
     */
    public function reportAction()
    {
        if ($this->getParam('id', false)) {
            $this->view->recordID = $this->getParam('id');
            $id = $this->getParam('id');
            $findsdata = $this->getFinds()->getIndividualFind(
                $id,
                $this->getRole()
            );
            if (count($findsdata)) {
                $this->view->finds = $findsdata;
            } else {
                throw new Pas_Exception_NotAuthorised(
                    'You are not authorised to view this record'
                );
            }
            $this->view->findsdata = $this->getFinds()->getFindData($id);
            $this->view->findsmaterial = $this->getFinds()->getFindMaterials(
                $id
            );
            $this->view->temporals = $this->getFinds()->getFindTemporalData(
                $id
            );
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
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Get a find audit overlay
     *
     * @access public
     * @return void
     */
    public function findsauditAction()
    {
        $audit = new FindsAudit();
        $this->view->audit = $audit->getChange($this->getParam('id'));
    }

    /** Get a findspot overlay from the audit table
     *
     * @access public
     * @return void
     */
    public function findspotsauditAction()
    {
        $audit = new FindspotsAudit();
        $this->view->audit = $audit->getChange($this->getParam('id'));
    }

    /** Get a coin overlay from the audit table
     *
     * @access public
     * @return void
     */
    public function coinsauditAction()
    {
        $audit = new CoinsAudit();
        $this->view->audit = $audit->getChange($this->getParam('id'));
    }

    /** Get a coin overlay from the audit table
     *
     * @access public
     * @return void
     */
    public function hoardsauditAction()
    {
        $audit = new HoardsAudit();
        $this->view->audit = $audit->getChange($this->getParam('id'));
    }

    /** Get a archaeology overlay from the audit table
     *
     * @access public
     * @return void
     */
    public function archaeologyauditAction()
    {
        $audit = new ArchaeologyAudit();
        $this->view->audit = $audit->getChange($this->getParam('id'));
    }

    /** Get a archaeology overlay from the audit table
     *
     * @access public
     * @return void
     */
    public function summaryauditAction()
    {
        $audit = new SummaryAudit();
        $this->view->audit = $audit->getChange($this->getParam('id'));
    }

    /** Get a saved search overlay
     *
     * @access public
     * @return void
     */
    public function savesearchAction()
    {
        $form = new SaveSearchForm();
        $this->view->form = $form;
    }

    /** Copy the last find
     *
     * @access public
     * @return void
     */
    public function copyfindAction()
    {
        $finddata = $this->getFinds()->getLastRecord(
            $this->getIdentityForForms()
        );
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        echo Zend_Json::encode($finddata);
    }

    /** Get the mapdata for layouts
     *
     * @access public
     * @return void
     */
    public function mapdataAction()
    {
        $this->_helper->layout->disableLayout();
        $params = $this->getAllParams();
        $params['show'] = 2000;
        $params['format'] = 'json';
        $search = new Pas_Solr_Handler();
        $search->setCore('objects');
        $search->setFields(array(
            'id',
            'old_findID',
            'description',
            'gridref',
            'fourFigure',
            'longitude',
            'latitude',
            'county',
            'woeid',
            'district',
            'parish',
            'knownas',
            'thumbnail',
            'objecttype'
        ));
        $search->setParams($params);
        $search->execute();
        $this->view->results = $search->processResults();
    }

    /** Secondary version of the mapdata function
     *
     * @access public
     * @return void
     * @todo   deprecate?
     */
    public function mapdata2Action()
    {
        $params = $this->getAllParams();
        if (!isset($params['show'])) {
            $params['show'] = 2000;
        }
        $params['format'] = 'kml';
        $search = new Pas_Solr_Handler();
        $search->setCore('objects');
        $search->setFields(array(
            'id',
            'old_findID',
            'description',
            'gridref',
            'fourFigure',
            'longitude',
            'latitude',
            'county',
            'woeid',
            'district',
            'parish',
            'knownas',
            'thumbnail',
            'objecttype',
            'secwfstage',
            'findIdentifier'
        ));
        $search->setParams($params);
        $search->execute();
        $this->view->results = $search->processResults();
        $this->getResponse()->setHeader('Content-type', 'text/xml');
    }

    /** Exporter action
     *
     * @access public
     * @return void
     */
    public function exporterAction()
    {
        $this->_helper->layout->disableLayout();
        $params = $this->getAllParams();
        $params['show'] = 15000;
        $params['format'] = 'json';
        $search = new Pas_Solr_Handler();
        $search->setCore('objects');
        $search->setFields(array(
            'id',
            'old_findID',
            'description',
            'gridref',
            'fourFigure',
            'longitude',
            'latitude',
            'county',
            'woeid',
            'district',
            'parish',
            'knownas',
            'thumbnail'
        ));
        $search->setParams($params);
        $search->execute();
        $this->view->results = $search->processResults();
    }

    /** Create KML action
     *
     * @access public
     * @return void
     */
    public function kmlAction()
    {
        $exporter = new Pas_Exporter_Generate();
        $exporter->setFormat('kml');
        $this->view->results = $exporter->execute();
        $filename = 'KMLExport_' . Zend_Date::now()->toString('yyyyMMddHHmmss')
            . '.kml';
        $this->getResponse()->setHeader(
            'Content-type',
            'application/vnd.google-earth.kml+xml'
        )
            ->setHeader(
                'Content-Disposition',
                'attachment; filename=' . $filename
            );
    }

    /** The HER export action
     *
     * @access public
     * @return void
     */
    public function herAction()
    {
        $exporter = new Pas_Exporter_Generate();
        $exporter->setFormat('hero');
        $exporter->execute();
    }

    /** The hoard specific module exporter
     *
     * @access public
     * @return void
     */
    public function hoardAction()
    {
        $exporter = new Pas_Exporter_Generate();
        $exporter->setFormat('hoard');
        $exporter->execute();
    }

    /** Create CSV export
     *
     * @access public
     * @return void
     */
    public function csvAction()
    {
        $exporter = new Pas_Exporter_Generate();
        $exporter->setFormat('csv');
        $exporter->execute();
    }

    /** Create a summary exporter for coins from hoards module
     *
     * @access public
     * @return void
     */
    public function summaryAction()
    {
        $exporter = new Pas_Exporter_SummaryGenerate();
        $exporter->setFormat('csvsummary');
        $exporter->execute();
    }

    /** The Norfolk exporter
     *
     * @access public
     * @return void
     */
    public function pdfAction()
    {
        $exporter = new Pas_Exporter_Generate();
        $exporter->setFormat('pdf');
        $this->view->filename = 'PDFRecordsExport_For_' . $this->getUsername()
            . '_' . Zend_Date::now()->toString('yyyyMMddHHmmss') . '.pdf';
        $this->view->data = $exporter->execute();
    }

    /** An action for exporting as GIS shp files
     *
     * @access public
     * @return void
     */
    public function gisAction()
    {
        //Unused
    }

    /** Dump out all antiquities from the OS 1:50k
     *
     * @access public
     * @return void
     */
    public function osdataAction()
    {
        $params = $this->getAllParams();
        $params['show'] = 5489;
        $params['format'] = 'json';
        $params['source'] = 'osdata';
        $params['sort'] = 'id';
        $q = $this->getParam('q');
        if (is_null($q)) {
            $params['q'] = 'type:R OR type:A';
        } else {
            $params['q'] = 'type:R || type:A && ' . $q;
        }
        $search = new Pas_Solr_Handler();
        $search->setCore('geodata');
        $search->setParams($params);
        $search->setFields(array('*'));
        $search->execute();
        $this->view->results = $search->processResults();
    }

    /** Dump out SMR data
     *
     * @access public
     * @return void
     */
    public function smrsAction()
    {
        $params = $this->getAllParams();
        $params['show'] = 1000;
        $params['format'] = 'kml';
        $params['sort'] = 'id';
        $params['source'] = 'smrdata';
        $search = new Pas_Solr_Handler();
        $search->setCore('geodata');
        $search->setParams($params);
        $search->setFields(array('*'));
        $search->execute();
        $this->view->results = $search->processResults();
    }

    /** The people action
     *
     * @access public
     * @return void
     */
    public function peopleAction()
    {
        $params = $this->getAllParams();
        $params['show'] = 5000;
        $params['format'] = 'json';
        $params['sort'] = 'id';
        $search = new Pas_Solr_Handler();
        $search->setCore('people');
        $search->setParams($params);
        $search->setFields(array('*'));
        $search->execute();
        $this->view->results = $search->processResults();
    }

    /** The facet action
     *
     * @access public
     * @return void
     */
    public function facetAction()
    {
        $search = new Pas_Solr_Handler();
        $search->setCore('objects');
        $context = $this->_helper->contextSwitch->getCurrentContext();
        $fields = new Pas_Solr_FieldGeneratorFinds($context);
        //	$search->setFields($fields->getFields());
        $search->setFacets(array(
            'objectType',
            'county',
            'broadperiod',
            'institution',
            'rulerName',
            'denominationName',
            'mintName',
            'materialTerm',
            'workflow'
        ));
        $search->setParams($this->getAllParams());
        $search->execute();
        $data = array('facets' => $search->processFacets());
        $this->view->data = $data;
        $this->view->facetName = $this->getParam('facetType');
    }

    /** The people facet generator
     *
     * @access public
     * @return void
     */
    public function peoplefacetAction()
    {
        $search = new Pas_Solr_Handler();
        $search->setCore('people');
        $context = $this->_helper->contextSwitch->getCurrentContext();
        $fields = new Pas_Solr_FieldGeneratorFinds($context);
        $search->setFields($fields->getFields());
        $search->setFacets(array(
            'county',
            'organisation',
            'activity'
        ));
        $search->setParams($this->getAllParams());
        $search->execute();
        $data = array('facets' => $search->processFacets());
        $this->view->data = $data;
        $this->view->facetName = $this->getParam('facetType');
    }


    /** The image facet generator
     *
     * @access public
     * @return void
     */
    public function imagefacetAction()
    {
        $search = new Pas_Solr_Handler();
        $search->setCore('images');
        $context = $this->_helper->contextSwitch->getCurrentContext();
        $fields = new Pas_Solr_FieldGeneratorFinds($context);
        $search->setFields($fields->getFields());
        $search->setFacets(array(
            'licenseAcronym',
            'broadperiod',
            'county',
            'objecttype',
            'institution',
            'imageRights'
        ));
        $search->setParams($this->getAllParams());
        $search->execute();
        $data = array('facets' => $search->processFacets());
        $this->view->data = $data;
        $this->view->facetName = $this->getParam('facetType');
        $this->renderScript('ajax/imagesfacet.phtml');
    }

    /** Generate my finds facet overlay
     *
     * @access public
     * @return void
     */
    public function myfindsfacetAction()
    {
        $search = new Pas_Solr_Handler();
        $search->setCore('objects');
        $context = $this->_helper->contextSwitch->getCurrentContext();
        $fields = new Pas_Solr_FieldGeneratorFinds($context);
        $search->setFields($fields->getFields());
        $search->setFacets(array(
            'objectType',
            'county',
            'broadperiod',
            'institution',
            'rulerName',
            'denominationName',
            'mintName',
            'materialTerm',
            'workflow'
        ));
        $params['createdBy'] = $this->getIdentityForForms();
        $search->setParams($params);
        $search->execute();
        $data = array('facets' => $search->processFacets());
        $this->view->data = $data;
        $this->view->facetName = $this->getParam('facetType');
    }

    /** My institution facet overlay
     *
     * @access public
     * @return void
     */
    public function myinstitutionfacetAction()
    {
        $search = new Pas_Solr_Handler();
        $search->setCore('objects');
        $context = $this->_helper->contextSwitch->getCurrentContext();
        $fields = new Pas_Solr_FieldGeneratorFinds($context);
        $search->setFields($fields->getFields());
        $search->setFacets(array(
            'objectType',
            'county',
            'broadperiod',
            'institution',
            'rulerName',
            'denominationName',
            'mintName',
            'materialTerm',
            'workflow'
        ));
        $params['institution'] = $this->getInstitution();
        $search->setParams($params);
        $search->execute();
        $data = array('facets' => $search->processFacets());
        $this->view->data = $data;
        $this->view->facetName = $this->getParam('facetType');
        $this->renderScript('ajax/facet.phtml');
    }

    /** My images facet overlay
     *
     * @access public
     * @return void
     */

    public function myimagesfacetAction()
    {
        $search = new Pas_Solr_Handler();
        $search->setCore('images');
        $context = $this->_helper->contextSwitch->getCurrentContext();
        $fields = new Pas_Solr_FieldGeneratorFinds($context);
        $search->setFields($fields->getFields());
        $search->setFacets(array(
            'licenseAcronym',
            'broadperiod',
            'county',
            'objecttype',
            'institution'
        ));
        $params = $this->getAllParams();
        $params['createdBy'] = $this->getIdentityForForms();
        $search->setParams($params);
        $search->execute();
        $data = array('facets' => $search->processFacets());
        $this->view->data = $data;
        $this->view->facetName = $this->getParam('facetType');
    }

    /** Force an index update
     *
     * @access public
     * @return void
     */
    public function forceindexupdateAction()
    {
        $this->_helper->solrUpdater->update(
            'objects',
            $this->getParam('id'),
            $this->getParam('recordType')
        );
    }

    /** Get the classes to token
     *
     * @access public
     * @return void
     */
    public function getclassestokenAction()
    {
        $classes = new JettonGroups();
        $this->view->json = $classes->getGroupsToClasses(
            $this->getParam('term')
        );
    }

    /** Get jetton types
     *
     * @access public
     * @return void
     */
    public function gettypesgroupAction()
    {
        $types = new JettonTypes();
        $this->view->json = $types->getTypesToGroups(
            $this->getParam('term')
        );
    }

    /** Function to get denoms
     *
     * @access public
     * @return void
     */
    public function getdenominationsAction()
    {
    }

    /** Function to get mints
     *
     * @access public
     * @return void
     */
    public function getMintsAction()
    {
    }

    /** Function to get geographies
     *
     * @access public
     * @return void
     */
    public function getgeographyAction()
    {
        $geography = new Geography();
        $this->view->json = $geography->getIronAgeGeographyAll();
    }

    /** Action for displaying upload action
     *
     * @access public
     * @return \Database_AjaxController
     */
    public function uploadAction()
    {
        if ($this->_request->isOptions()) {
            $this->upload();
        }
        if ($this->_request->isPost()) {
            $this->upload();
        }
        if ($this->_request->isGet()) {
            $this->upload();
        }
        if (
            $this->_request->isDelete()
            || $_SERVER['REQUEST_METHOD'] == 'DELETE'
        ) {
            $this->delete();
        }
    }

    /** Function for performing upload of files
     *
     * @access public
     * @throws \Pas_Exception_NotAuthorised
     */
    public function upload()
    {
        if ($this->_helper->Identity()) {
            //Check if images path directory is writable
            if (!is_writable(IMAGE_PATH)) {
                throw new Pas_Exception_NotAuthorised(
                    'The images directory is not writable',
                    500
                );
            }
            // Create the imagedir path
            $imagedir = IMAGE_PATH . '/' . $this->_helper->Identity()->username;

            //Check if a directory and if not make directory
            if (!is_dir($imagedir)) {
                mkdir($imagedir, 0775, true);
            }
            //Check if the personal image directory is writable
            if (!is_writable($imagedir)) {
                throw new Pas_Exception_NotAuthorised(
                    'The user image directory is not writable',
                    500
                );
            }

            // Get images and do the magic
            $adapter = new Zend_File_Transfer_Adapter_Http();
            $adapter->setDestination($imagedir);
            $adapter->setOptions(array('useByteString' => false));
            // Only allow good image files!
            $adapter->addValidator(
                'Extension',
                false,
                'jpg, tiff'
            );
            $adapter->addValidator(
                'NotExists',
                false,
                array($imagedir)
            );
            $files = $adapter->getFileInfo();

            // Create an array for the images
            $images = array();

            // Loop through the submitted files
            foreach ($files as $file => $info) {
                // Clean up the image name for crappy characters
                $filename = pathinfo($adapter->getFileName($file));
                // Instantiate the re-namer
                $reNamer = new Pas_Image_Rename();
                // Clean the filename
                $cleaned = $reNamer->strip(
                    $filename['filename'],
                    $filename['extension']
                );
                // Rename the file
                $adapter->addFilter('rename', $cleaned);
                // receive the files into the user directory
                $adapter->receive($file); // this has to be on top
                if (!$adapter->hasErrors()) {
                    // Create the object for reuse
                    $image = new stdClass();
                    $image->cleaned = $cleaned;
                    $image->basename = $filename['basename'];
                    $image->extension = $filename['extension'];
                    $image->thumbnailUrl = $this->createThumbnailUrl(
                        $adapter->getFileName($file, false)
                    );
                    $image->deleteUrl = $this->_createUrl(
                        $adapter->getFileName($file, false)
                    );
                    $image->path = $adapter->getFileName($file);
                    $image->name = $adapter->getFileName($file, false);
                    $image->size = $adapter->getFileSize($file);
                    $image->mimetype = $adapter->getMimeType($file);
                    // The secure ID stuff for linking images
                    $image->secuid = $this->_helper->GenerateSecuID();
                    // Get the image dimensions
                    $imagesize = getimagesize($adapter->getFileName($file));
                    $image->width = $imagesize[0];
                    $image->height = $imagesize[1];
                    //Grab parameters from URL
                    $params = $this->getAllParams();
                    $image->findID = $params['findID'];
                    // Create the raw image url
                    $image->url = $this->_createUrl(
                        $adapter->getFileName($file, false)
                    );
                    $image->deleteType = 'DELETE';
                    $images[] = $image;
                    //Update the slides table
                    $slides = new Slides();
                    $insert = $slides->addAndResize(
                        $images,
                        $params['recordtype']
                    );
                    $this->view->data = $images;
                    // Update the appropriate cores - images and objects
                    $this->_helper->solrUpdater->update(
                        'images',
                        (int)$insert,
                        $params['recordtype']
                    );
                    $this->_helper->solrUpdater->update(
                        'objects',
                        (int)$params['findID'],
                        $params['recordtype']
                    );
                } else {
                    $image = new stdClass();
                    $image->error = $adapter->getErrors();
                    $images[] = $image;
                    $this->view->data = $images;
                }
            }
        } else {
            throw new Pas_Exception_NotAuthorised(
                'Your account does not seem enabled to do this', 401
            );
        }
    }

    /** Create a thumbnail
     *
     * @param string $file
     *
     * @return string
     */
    public function createThumbnailUrl($file)
    {
        $user = $this->_helper->Identity()->username;
        return $this->view->serverUrl() . '/images/' . $user . '/medium/'
            . $file;
    }

    /** Create url for file
     *
     * @param string $file
     *
     * @return string
     */
    public function _createUrl($file)
    {
        $user = $this->_helper->Identity()->username;
        return $this->view->serverUrl() . '/images/' . $user . '/' . $file;
    }

    /** Functuon for deleting files
     *
     * @param array files
     *
     * @return string
     */
    public function delete()
    {
        $file_name = $this->_request->getParam('files');
        $imagedir = IMAGE_PATH . '/' . $this->_helper->Identity()->username;
        $file_path = $imagedir . '/' . $file_name;
        $success = is_file($file_path) && $file_name[0] !== '.'
            && unlink(
                $file_path
            );
        echo json_encode($success);
    }

    /** Function for displaying a timeline
     *
     * @access public
     *
     * @param int $id
     *
     * @return array
     */
    public function timelineAction()
    {
        $this->_helper->layout->disableLayout();
        if ($this->getParam('id', false)) {
            $finds = new Finds();
            $this->view->data = $finds->getAllData($this->getParam('id'));
        } else {
            $this->view->data = 'Error';
        }
    }
}
