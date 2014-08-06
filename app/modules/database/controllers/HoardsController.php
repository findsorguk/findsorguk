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
        $this->_finds = new Finds();
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
        if($this->_getParam('id',false)) {
            $this->view->recordID = $this->_getParam('id');
            $id = $this->_getParam('id');
            $findsdata = $this->_finds->getIndividualFind($id, $this->getRole());

            if($findsdata) {
                $this->view->finds = $findsdata;
            } else {
                throw new Pas_Exception_NotAuthorised('You are not authorised to view this record', 401);
            }
            if(!in_array($this->_helper->contextSwitch()
                ->getCurrentContext(), $this->_contexts)) {
                $this->view->findsdata     = $this->_finds->getFindData($id);
                $this->view->findsmaterial = $this->_finds->getFindMaterials($id);
                $this->view->temporals     = $this->_finds->getFindTemporalData($id);
                $this->view->peoples       = $this->_finds->getPersonalData($id);
                $this->view->findotherrefs = $this->_finds->getFindOtherRefs($id);

                $this->view->findspots = $this->getFindspots()->getFindSpotData($id);

                $rallyfind = new Rallies;
                $this->view->rallyfind = $rallyfind->getFindToRallyNames($id);

                $coins = new Coins;
                $this->view->coins = $coins->getCoinData($id);

                $coinrefs = new CoinClassifications();
                $this->view->coinrefs = $coinrefs->getAllClasses($id);

                $thumbs = new Slides;
                $this->view->thumbs = $thumbs->getThumbnails($id);

                $refs = new Publications;
                $this->view->refs = $refs->getReferences($id);

                $this->view->comments = $this->getComments()->getFindComments($id);

                $this->view->findspots = $this->getFindspots()->getFindSpotData($id);

                $form = new CommentFindForm();
                $form->submit->setLabel('Add a new comment');
                $this->view->form = $form;
                if($this->getRequest()->isPost()
                    && $form->isValid($this->_request->getPost())) {
                    if ($form->isValid($form->getValues())) {
                        $data = $this->_helper->akismet($form->getValues());
                        $data['contentID'] = $this->_getParam('id');
                        $data['comment_type'] = 'findComment';
                        $data['comment_approved'] = 'moderation';
                        $this->getComments()->add($data);
                        $this->getFlash()->addMessage('Your comment has been entered and will appear shortly!');
                        $this->_redirect(self::REDIRECT . 'record/id/' . $this->_getParam('id'));
                        $this->_request->setMethod('GET');
                    } else {
                        $this->getFlash()->addMessage('There are problems with your comment submission');
                        $form->populate($this->_request->getPost());
                    }
                }
            } else {
                $this->_helper->layout->disableLayout();    //disable layout
                $record = $this->_finds->getAllData($id);
                if(in_array($this->getRole(), $this->_restricted)) {
                    $record['0']['gridref'] = 'Restricted information';
                    $record['0']['easting'] = 'Restricted information';
                    $record['0']['northing'] = 'Restricted information';
                    $record['0']['lat'] = 'Restricted information';
                    $record['0']['lon'] = 'Restricted information';
                    $record['0']['finder'] = 'Restricted information';
                    $record['0']['address'] = 'Restricted information';
                    $record['0']['postcode'] = 'Restricted information';
                    $record['0']['findspotdescription'] = 'Restricted information';
                    if(!is_null($record['0']['knownas'])){
                        $record['0']['parish'] = 'Restricted information';
                        $record['0']['fourFigure'] = 'Restricted information';
                        $record['0']['fourFigureLat'] = 'Restricted information';
                        $record['0']['fourFigureLon'] = 'Restricted information';
                    }
                }
                $this->view->record = $record;
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    }

}