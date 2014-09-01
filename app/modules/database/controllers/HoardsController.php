<?php
/** Controller for manipulating the hoards data
 *
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2014 Mary Chester-Kadwell
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Hoards
 * @uses Pas_ArrayFunctions
 * @uses Finds
 * @uses Pas_Exception_NotAuthorised
 * @uses Findspots
 * @uses Archaeology
 * @uses Publications
 * @uses HoardForm
 * @uses Pas_Exception_Param
 */
class Database_HoardsController extends Pas_Controller_Action_Admin {

    /** The redirect uri
     *
     */
    const REDIRECT = '/database/hoards/';

    /** The array of restricted access
     * @access protected
     * @var array restricted access roles
     */
    protected $_restricted = array(null, 'member','public');

    /** the higher level roles
     * @access protected
     * @var array Higher level groups
     */
    protected $_higherLevel = array('treasure', 'flos', 'admin', 'hero', 'fa' );

    /** The array of numismatic terms
     * @var array coins pseudonyms
     */
    protected $_coinarray = array(
        'Coin','COIN','coin',
        'token','jetton','coin weight',
        'COIN HOARD', 'TOKEN', 'JETTON'
    );

    /** An array of Roman and Iron Age periods
     * Used for coins
     * @access protected
     * @var array Romanic periods
     */
    protected $_periodRomIA = array(
        'Roman','ROMAN','roman',
        'Iron Age','Iron age','IRON AGE',
        'Byzantine','BYZANTINE','Greek and Roman Provincial',
        'GREEK AND ROMAN PROVINCIAL','Unknown',
        'UNKNOWN');

    /** An array of Roman and Prehistoric periods
     * Used for objects
     * @var array
     * @access protected
     */
    protected $_periodRomPrehist = array(
        'Roman', 'ROMAN', 'roman',
        'Iron Age', 'Iron age', 'IRON AGE',
        'Byzantine', 'BYZANTINE', 'Greek and Roman Provincial',
        'GREEK AND ROMAN PROVINCIAL', 'Unknown', 'UNKNOWN',
        'Mesolithic', 'MESOLITHIC', 'PREHISTORIC',
        'NEOLITHIC', 'Neolithic', 'Palaeolithic',
        'PALAEOLITHIC', 'Bronze Age', 'BRONZE AGE');

    /** An array of Early medieval periods
     * Used for objects and coins
     * @access protected
     * @var array
     */
    protected $_earlyMed = array('Early Medieval','EARLY MEDIEVAL');

    /** An array of Medieval periods
     * Used for coins and objects
     * @access protected
     * @var array
     */
    protected $_medieval = array('Medieval','MEDIEVAL');

    /** An array of Post Medieval periods
     * Used for coins and objects
     * @access protected
     * @var array
     */
    protected $_postMed = array('Post Medieval','POST MEDIEVAL','Modern', 'MODERN');

    protected $_contexts = array(
        'xml','rss','json',
        'atom','kml','georss',
        'ics','rdf','xcs',
        'vcf','csv','pdf',
        'geojson');

    protected $_auth;

    protected $_comments;

    protected $_findspots;

    protected $_hoardForm;

    public function getHoardForm() {
        $this->_hoardForm = new HoardForm();
        return $this->_hoardForm;
    }

    public function getFindspots() {
        $this->_findspots = new Findspots();
        return $this->_findspots;
    }

    public function getComments() {
        $this->_comments = new Comments();
        return $this->_comments;
    }


    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init()  {
        $this->_helper->_acl->deny('public',array('add','edit'));
        $this->_helper->_acl->allow('public',array(
            'index','record','errorreport',
            'notifyflo'
        ));
        $this->_helper->_acl->allow('member',null);

        $this->_helper->contextSwitch()->setAutoJsonSerialization(false)
            ->setAutoDisableLayout(true)
            ->addContext('csv',array('suffix' => 'csv'))
            ->addContext('kml',array('suffix' => 'kml'))
            ->addContext('rss',array('suffix' => 'rss'))
            ->addContext('atom',array('suffix' => 'atom'))
            ->addContext('rdf',array('suffix' => 'rdf','headers' => array('Content-Type' => 'application/xml')))
            ->addContext('pdf',array('suffix' => 'pdf'))
            ->addContext('midas', array('suffix' => 'midas', 'headers' => array('Content-Type' => 'application/xml')))
            ->addContext('qrcode',array('suffix' => 'qrcode'))
            ->addContext('geojson',array('suffix' => 'geojson', 'headers' => array('Content-Type' => 'application/json')))
            ->addActionContext('record', array('qrcode', 'json', 'xml', 'geojson', 'rdf'))
            ->initContext();
        $this->_hoards = new Hoards();
        $this->_auth = Zend_Registry::get('auth');
    }

    /** Display a list of hoards recorded with pagination
     * This redirects to just the search results as there is nothing else here.
     * @access public
     * @return void
     */
    public function indexAction(){
        $this->_redirect('database/search/results/');
        $this->getResponse()->setHttpResponseCode(301)
            ->setRawHeader('HTTP/1.1 301 Moved Permanently');
    }

    /** Display individual hoard record
     * @access public
     * @return void
     */
    public function recordAction() {
        if($this->_getParam('id',false)) { // Check there is a hoardID in the URL
            $id = $this->_getParam('id');
            $hoardsdata = $this->_hoards->getBasicHoardData($id);
            if($hoardsdata) {
                $this->view->hoards = $hoardsdata;
                $this->view->multipleKnownAs     = $this->_hoards->getKnownAs($id);
                $this->view->temporals     = $this->_hoards->getChronology($id);
                $this->view->coinChronology     = $this->_hoards->getCoinChronology($id);
                $this->view->hoardDescription     = $this->_hoards->getHoardDescription($id);
                $this->view->coinDataQuality     = $this->_hoards->getQualityRating($id);
                $this->view->subsequentActions     = $this->_hoards->getSubsequentActions($id);
                $this->view->treasureDetails     = $this->_hoards->getTreasureDetails($id);
                $this->view->hoardMaterials     = $this->_hoards->getMaterials($id);
                $this->view->linkedCoins     = $this->_hoards->getLinkedCoins($id);
                $this->view->linkedArtefacts     = $this->_hoards->getLinkedArtefacts($id);
                $this->view->linkedContainers     = $this->_hoards->getLinkedContainers($id);
                $this->view->recordersIdentifiers     = $this->_hoards->getRecordersIdentifiers($id);
                $this->view->finders     = $this->_hoards->getFinders($id);
                $this->view->discoverySummary     = $this->_hoards->getDiscoverySummary($id);
                $this->view->referenceNumbers     = $this->_hoards->getReferenceNumbers($id);

                $coinsummary = new CoinSummary();
                $this->view->coinSummary = $coinsummary->getCoinSummary($id);

                $this->view->findspots = $this->getFindspots()->getFindSpotData($id,'hoards');

                $archaeology = new Archaeology();
                $this->view->archaeologicalContext = $archaeology->getArchaeologyData($id);

                $refs = new Publications();
                $this->view->refs = $refs->getReferences($id,'hoards');

            } else {
                throw new Pas_Exception_NotAuthorised('You are not authorised to view this record', 401);
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }

    }

    /** Add a hoard
     * @access public
     * @return void
     */
    public function addAction() {

        $form = $this->getHoardForm();
        $form->submit->setLabel('Save record');
        $this->view->form = $form;


    }

    /** Edit a hoard
     * @access public
     * @return void
     * @todo move update logic to model finds.php
     */
    public function editAction() {
        if($this->_getParam('id',false)){
            $user = $this->getAccount();
            $form = $this->getHoardForm();
            $form->submit->setLabel('Update record');
            $this->view->form = $form;
            if(in_array($this->getRole(),$this->_restricted)) {
                $form->removeDisplayGroup('discoverers');
                $form->removeElement('finder');
                $form->finderID->setValue($user->peopleID);
                $form->removeElement('secondfinder');
                $form->removeElement('idBy');
                $form->recordername->setAttrib('disabled', true);
                $form->removeElement('id2by');
            }
            if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
                $data = $form->getValues();
                if ($form->isValid($form->getValues())) {
                    $updateData = $form->getValues();
                    $id2by = $form->getValue('id2by');
                    if($id2by === "" || is_null($id2by)){
                        $updateData['identifier2ID'] = null;
                    }
                    unset($updateData['recordername']);
                    unset($updateData['finder']);
                    unset($updateData['idBy']);
                    unset($updateData['id2by']);
                    unset($updateData['secondfinder']);
                    $oldData = $this->_finds->fetchRow('id=' . $this->_getParam('id'))->toArray();
                    $where = array();
                    $where[] = $this->_finds->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                    $this->_finds->update($updateData, $where);
                    $this->_helper->audit(
                        $updateData,
                        $oldData,
                        'FindsAudit',
                        $this->_getParam('id'),
                        $this->_getParam('id')
                    );
                    $this->_helper->solrUpdater->update('beowulf', $this->_getParam('id'));
                    $this->getFlash()->addMessage('Artefact information updated and audited!');
                    $this->_redirect(self::REDIRECT . 'record/id/' . $this->_getParam('id'));
                } else {
                    $this->view->find = $this->_finds->fetchRow('id='.$this->_getParam('id'));
                    $form->populate($this->_request->getPost());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $formData = $this->_hoards->getEditData($id);
                    $materialsData = $this->_hoards->getMaterials($id);
                    $materialsData = array_keys($materialsData);
                    if(count($formData)){
                        $form->populate($formData);
                        $form->getElement('primarymaterials')->setValue($materialsData);
                        $this->view->hoard = $this->_hoards->fetchRow('id='.$id);
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound, 404);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

}
