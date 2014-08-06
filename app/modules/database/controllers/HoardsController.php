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
 * @uses Coins
 * @uses CoinClassifications
 * @uses Slides
 * @uses Publications
 * @uses Comments
 * @uses Rallies
 * @uses CommentFindForm
 * @uses FindForm
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

    protected $_findForm;

    public function getFindForm() {
        $this->_findForm = new FindForm();
        return $this->_findForm;
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
     * @todo move comment functionality to a model
     */
    public function recordAction() {
        if($this->_getParam('id',false)) { // Check there is a hoardID in the URL
            $id = $this->_getParam('id');
            $hoardsdata = $this->_hoards->getAllHoardData($id);
            if($hoardsdata) {
                $this->view->hoards = $hoardsdata;

                $this->view->chronology     = $this->_hoards->getChronology($id);
/*                $this->view->coinChronology     = $this->_hoards->getCoinChronology($id);
                $this->view->coinSummary     = $this->_hoards->getCoinSummary($id);
                $this->view->hoardDescription     = $this->_hoards->getHoardDescription($id);
                $this->view->qualityRating     = $this->_hoards->getQualityRating($id);
                $this->view->subsequentActions     = $this->_hoards->getSubsequentActions($id);
                $this->view->treasureDetails     = $this->_hoards->getTreasureDetails($id);
                $this->view->materials     = $this->_hoards->getMaterials($id);*/
                $this->view->linkedCoins     = $this->_hoards->getLinkedCoins($id);
/*                $this->view->linkedArtefacts     = $this->_hoards->getLinkedArtefacts($id);
                $this->view->linkedContainers     = $this->_hoards->getLinkedContainers($id);
                $this->view->recordersIdentifiers     = $this->_hoards->getRecordersIdentifiers($id);
                $this->view->finders     = $this->_hoards->getFinders($id);
                $this->view->discoverySummary     = $this->_hoards->getDiscoverySummary($id);
                $this->view->referenceNumbers     = $this->_hoards->getReferenceNumbers($id);*/


            } else {
                throw new Pas_Exception_NotAuthorised('You are not authorised to view this record', 401);
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }

    }
}