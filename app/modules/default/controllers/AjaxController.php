<?php

/** A class for controlling ajax outputs for use in various functions
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @copyright 2014 Daniel Pett & Trustees of the British Museum
 * @category Pas
 * @package Controller
 * @subpackage Action
 */
class AjaxController extends Pas_Controller_Action_Ajax
{
    /** The cache object
     * @access protected
     * @var NULL
     */
    protected $_cache = NULL;

    /** The user model
     * @access protected
     * @var null
     */
    protected $_user = NULL;

    /** The places model
     * @access protected
     * @var null
     */
    protected $_places = NULL;

    /** Get the cache model
     * @access public
     * @return NULL
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Get the user model
     * @access public
     * @return null
     */
    public function getUser()
    {
        $this->_user = $this->_helper->identity->getPerson();
        return $this->_user;
    }

    /** Get the places model
     * @access public
     * @return \Places
     */
    public function getPlaces()
    {
        $this->_places = new Places();
        return $this->_places;
    }

    /** Init the controller
     * @access public
     */
    public function init()
    {
        $this->_helper->acl->allow('public', null);
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
    }

    /** No access to the root page
     * @access public
     */
    public function indexAction()
    {
        $this->getFlash()->addMessage('There is not a root action available to you.');
        $this->getResponse()->setHttpResponseCode(301)
            ->setRawHeader('HTTP/1.1 301 Moved Permanently');
        $this->redirect('/');

    }

    /** Get an ajax list of available counties
     * @access public
     * @return mixed
     */
    public function countiesAction()
    {
        $counties = new Counties;
        $countiesjson = $counties->getCountyName2();
        echo Zend_Json::encode($countiesjson);
    }

    /** Get a list of districts
     * @return void
     */
    public function placesAction()
    {
        if ($this->getParam('term', false)) {
            $districts = $this->_places->getDistrict($this->getParam('term'));
        }
        echo Zend_Json::encode($districts);
    }

    /** Get a list of parishes
     * @access public
     */
    public function parishesAction()
    {
        if ($this->getParam('term', false)) {
            $parishes = $this->_places->getParish($this->getParam('term'));
            echo Zend_Json::encode($parishes);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }

    }

    /** Get parishes by county
     * @access public
     */
    public function parishesbycountyAction()
    {
        if ($this->getParam('term', false)) {
            $parishes = $this->_places->getParishByCounty($this->getParam('term'));
            echo Zend_Json::encode($parishes);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Get district associated with a parish
     * @access public
     */
    public function districtbyparishAction()
    {
        if ($this->getParam('term', false)) {
            $parishes = $this->_places->getDistrictByParish($this->getParam('term'));
            echo Zend_Json::encode($parishes);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Get the regions list
     * @access public
     */
    public function regionsAction()
    {
        if ($this->getParam('term', false)) {
            $regions = new Counties;
            $response = $regions->getRegions($this->getParam('term'));
            echo Zend_Json::encode($response);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Get the landuse codes
     * @access public
     */
    public function landusecodesAction()
    {
        if ($this->getParam('term', false)) {
            $landcodes = new Landuses();
            $json = $landcodes->getLandusesChildAjax2($this->getParam('term'));
        } else {
            $json = array(null => 'You must choose a landuse first');
        }
        echo Zend_Json::encode($json);
    }

    /** Get object terms
     * @access public
     */
    public function objecttermAction()
    {
        $objectterms = new ObjectTerms;
        $objecttermsjson = $objectterms->getObjectterm($this->getParam('q'));
        echo Zend_Json::encode($objecttermsjson);
    }

    /** Object to image link action
     * @access public
     * @return void
     */
    public function objectimagelinkAction()
    {
        $objectterms = new Finds;
        $objecttermsjson = $objectterms->getImageLinkData($this->getParam('q'));
        echo Zend_Json::encode($objecttermsjson);
    }

    /** Get publication titles
     * @access public
     * @return void
     */
    public function publicationtitleAction()
    {
        $publications = new Publications();
        $pubjson = $publications->getTitles(urlencode($this->getParam('q')));
        echo Zend_Json::encode($pubjson);
    }

    /** Get json encoded other references
     * @access public
     */
    public function otherrefsAction()
    {
        $otherrefs = new Finds();
        $otherrefsjson = $otherrefs->getOtherRef($this->getParam('q'));
        echo Zend_Json::encode($otherrefsjson);
    }

    /** Get treasure IDs
     * @access public
     */
    public function treasureidsAction()
    {
        $treasureids = new Finds();
        $treasureidsjson = $treasureids->getTreasureID($this->getParam('q'));
        echo Zend_Json::encode($treasureidsjson);
    }

    /** Get people in json format
     * @access public
     */
    public function peopleAction()
    {
        $peoples = new People();
        $people_options = $peoples->getNames($this->getParam('term'));
        echo Zend_Json::encode($people_options);
    }

    /** People searching json
     * @access public
     */
    public function peoplesearchAction()
    {
        $peoples = new People();
        $people_options = $peoples->getNamesSearch($this->getParam('q'));
        echo Zend_Json::encode($people_options);
    }

    /** Rulers to denominations json
     * @access public
     */
    public function rulerdenomAction()
    {
        if ($this->getParam('term', false)) {
            $denominations = new Denominations();
            $data = $denominations->getRomanRulerDenom($this->getParam('term'));
            if ($data) {
                $response = $data;
            } else {
                $response = array(array('id' => null, 'term' => 'No options available'));
            }
        } else {
            $response = array(array('id' => null, 'term' => 'No ruler specified'));
        }
        $data = Zend_Json::encode($response);
        echo Zend_Json::prettyPrint($data, array("indent" => " ", 'format' => 'html'));
    }

    /** Ruler to early medieval denominations
     * @access public
     */
    public function rulerdenomearlymedAction()
    {
        if ($this->getParam('term', false)) {
            $denominations = new Denominations();
            $denom_options = $denominations->getEarlyMedRulerDenom($this->getParam('term'));
            if ($denom_options) {
                echo Zend_Json::encode($denom_options);
            } else {
                $data = array(array('id' => '', 'term' => 'No options available'));
                echo Zend_Json::encode($data);
            }
        } else {
            $response = array(array('id' => null, 'term' => 'No ruler specified'));
            echo Zend_Json::encode($response);
        }
    }

    /** Get denomination to roman ruler
     * @access public
     * @return mixed
     */
    public function romandenomrulerAction()
    {
        if ($this->getParam('term', false)) {
            $rulers = new Rulers();
            $ruler_options = $rulers->getRomanDenomRuler($this->getParam('term'));
            if ($ruler_options) {
                echo Zend_Json::encode($ruler_options);
            } else {
                $data = array(array('id' => null, 'term' => 'No options available.'));
                echo Zend_Json::encode($data);
            }
        } else {
            $response = array(array('id' => null, 'term' => 'No ruler specified'));
            echo Zend_Json::encode($response);
        }
    }

    /** Get roman mints to rulers
     * @access public
     * @return mixed
     */
    public function romanmintrulerAction()
    {
        if ($this->getParam('term', false)) {
            $mints = new Mints();
            $mint_options = $mints->getRomanMintRuler($this->getParam('term'));
            if ($mint_options) {
                echo Zend_Json::encode($mint_options);
            } else {
                $data = array(array('id' => null, 'term' => 'No options available'));
                echo Zend_Json::encode($data);
            }
        } else {
            $data = array(array('id' => null, 'term' => 'No ruler specified'));
            echo Zend_Json::encode($data);
        }
    }

    /** Get early medieval mints to rulers
     * @access public
     * @return mixed
     */
    public function earlymedmintrulerAction()
    {
        $mints = new Mints();
        $ruler = $this->getParam('term');
        $mint_options = $mints->getEarlyMedMintRuler($this->getParam('term'));
        if ($mint_options) {
            echo Zend_Json::encode($mint_options);
        } else if ($ruler == null) {
            $data = array(array('id' => null, 'term' => 'I donated my brain to Michael'));
            echo Zend_Json::encode($data);
        } else {
            $data = array(array('id' => null, 'term' => 'No options available'));
            echo Zend_Json::encode($data);
        }
    }

    /** Get medieval mints to rulers
     * @access public
     * @return mixed
     */
    public function medmintrulerAction()
    {
        if ($this->getParam('term', false)) {
            $ruler = $this->getParam('term');
            $mints = new Mints();
            $mint_options = $mints->getEarlyMedMintRuler($this->getParam('term'));
            if ($mint_options) {
                echo Zend_Json::encode($mint_options);
            } else if ($ruler == null) {
                $data = array(array('id' => null, 'term' => 'No options available'));
                echo Zend_Json::encode($data);
            } else {
                $data = array(array('id' => null, 'term' => 'No options available'));
                echo Zend_Json::encode($data);
            }
        } else {
            $error = array(array('id' => '', 'term' => 'No ruler specified'));
            echo Zend_Json::encode($error);
        }
    }

    /** Get early medieval categories
     * @access public
     * @return mixed
     */
    public function earlymedtypecatAction()
    {
        if ($this->getParam('term', false)) {
            $cats = new CategoriesCoins();
            $cat_options = $cats->getCategories($this->getParam('term'));
            if ($cat_options) {
                echo Zend_Json::encode($cat_options);
            } else {
                $data = array(array('id' => null, 'term' => 'No options available'));
                echo Zend_Json::encode($data);
            }
        } else {
            $error = array(array('id' => null, 'term' => 'No ruler specified'));
            echo Zend_Json::encode($error);
        }
    }

    /** Get early medieval types to rulers
     * @access public
     * @return mixed
     */
    public function earlymedtyperulerAction()
    {
        if ($this->getParam('term', false)) {
            $types = new MedievalTypes();
            $ruler_options = $types->getEarlyMedTypeRuler($this->getParam('term'));
            if ($ruler_options) {
                echo Zend_Json::encode($ruler_options);
            } else {
                $data = array(array('id' => null, 'term' => 'No options available'));
                echo Zend_Json::encode($data);
            }
        } else {
            $data = array(array('id' => null, 'term' => 'No ruler specified'));
            echo Zend_Json::encode($data);
        }
    }

    /** Get Reece periods
     * @access public
     * @return mixed
     */
    public function reeceAction()
    {
        if ($this->getParam('term', false)) {
            $reeces = new Reeces();
            $reece_options = $reeces->getRulerReece($this->getParam('term'));
            $reece2_options = $reeces->getReeceUnassigned();
            if ($reece_options) {
                echo Zend_Json::encode($reece_options);
            } else {
                echo Zend_Json::encode($reece2_options);
            }
        } else {
            $error = array(array('id' => null, 'term' => 'No ruler specified'));
            echo Zend_Json::encode($error);
        }
    }

    /** Get Iron Age geography
     * @access public
     * @return mixed
     */
    public function iageographyAction()
    {
        if ($this->getParam('term', false)) {
            $geographies = new Geography();
            $response = $geographies->getIronAgeRegionToRulerSearch($this->getParam('term'));
        } else {
            $response = array(array('id' => null, 'term' => 'No ruler specified'));
        }
        echo Zend_Json::encode($response);
    }

    /** Get iron age rulers to regions
     * @access public
     * @return mixed
     */
    public function iarulerregionAction()
    {
        if ($this->getParam('term', false)) {
            $rulers = new Rulers();
            $response = $rulers->getIronAgeRulerRegion($this->getParam('term'));
        } else {
            $response = array(array('id' => null, 'term' => 'No ruler specified'));
        }
        echo Zend_Json::encode($response);
    }

    /** Get categories to a period
     * @access public
     * @return mixed
     */
    public function catsperiodAction()
    {
        if ($this->getParam('term', false)) {
            $cats = new CategoriesCoins();
            $response = $cats->getCategoriesPeriod($this->getParam('term'));
        } else {
            $response = array(array('id' => null, 'term' => 'No period specified'));
        }
        echo Zend_Json::encode($response);
    }

    /** Get the rulers as json to period
     * @access public
     * @return mixed
     */
    public function rulersperiodAction()
    {
        if ($this->getParam('term', false)) {
            $rulers = new Rulers();
            $response = $rulers->getAllRulers($this->getParam('term'));
        } else {
            $response = array(array('id' => null, 'term' => 'No period specified.'));
        }
        echo Zend_Json::encode($response);
    }

    /** Iron age tribe to region json
     * @access public
     * @return mixed
     */
    public function iatriberegionAction()
    {
        if ($this->getParam('term', false)) {
            $tribes = new Tribes();
            $response = $tribes->getIronAgeTribeRegion($this->getParam('term'));
        } else {
            $response = array(array('id' => null, 'term' => 'No region specified'));
        }
        echo Zend_Json::encode($response);
    }

    /** Get reverse types
     * @access public
     * @return mixed
     */
    public function revtypesAction()
    {
        if ($this->getParam('term', false)) {
            $types = new RevTypes();
            $type_options = $types->getTypes($this->getParam('term'));
            if ($type_options) {
                $response = $type_options;
            } else {
                $response = array(array('id' => null, 'term' => 'No options available'));
            }
        } else {
            $response = array(array('id' => null, 'term' => 'No ruler specified'));
        }
        echo Zend_Json::encode($response);
    }

    /** A function to get RRC types for moneyers as json
     * @access public
     * @return mixed
     */
    public function rrctypesAction()
    {
        if ($this->getParam('term', false)) {
            $moneyer = new Moneyers();
            $nomismaID = $moneyer->fetchRow($moneyer->select()->where('id = ?', $this->getParam('term')))->nomismaID;
            $nomisma = new Nomisma();
            $types = $nomisma->getRRCDropdowns($nomismaID);

            if ($types) {
                $response = $types;
            } else {
                $response = array(array('id' => null, 'term' => 'No options available'));
            }
        } else {
            $response = array(array('id' => null, 'term' => 'No moneyer specified'));
        }
        echo Zend_Json::encode($response);
    }

    /** A function to get RRC types for moneyers as json
     * @access public
     * @return mixed
     */
    public function rictypesAction()
    {
        if ($this->getParam('term', false)) {
            $rulers = new Rulers();
            $nomismaID = $rulers->fetchRow($rulers->select()->where('id = ?', $this->getParam('term')))->nomismaID;
            $nomisma = new Nomisma();
            $types = $nomisma->getRICDropdowns($nomismaID);
            if ($types) {
                $response = $types;
            } else {
                $response = array(array('id' => null, 'term' => 'No options available'));
            }
        } else {
            $response = array(array('id' => null, 'term' => 'No ruler specified'));
        }
        echo Zend_Json::encode($response);
    }

    /** Get early medieval category to ruler
     * @access public
     * @return mixed
     */
    public function earlymedcatrulerAction()
    {
        if ($this->getParam('term', false)) {
            $rulers = new Rulers();
            $rulerOptions = $rulers->getEarlyMedievalRulersAjax($this->getParam('term'));
            if ($rulerOptions) {
                $response = $rulerOptions;
            } else {
                $response = array(array('id' => '', 'term' => 'No options available'));
            }
        } else {
            $response = array(array('id' => null, 'term' => 'No ruler specified'));
        }
        echo Zend_Json::encode($response);
    }

    /** Post medieval category to ruler
     * @access public
     * @return mixed
     */
    public function postmedcatrulerAction()
    {
        if ($this->getParam('term', false)) {
            $rulers = new Rulers();
            $rulerOptions = $rulers->getPostMedievalRulersAjax($this->getParam('term'));
            if ($rulerOptions) {
                $response = $rulerOptions;
            } else {
                $response = array(array('id' => '', 'term' => 'No options available'));
            }
        } else {
            $response = array(array('id' => null, 'term' => 'No category specified'));
        }
        echo Zend_Json::encode($response);
    }

    /** Medieval category to ruler
     * @access public
     * @return mixed
     */
    public function medcatrulerAction()
    {
        if ($this->getParam('term', false)) {
            $rulers = new Rulers();
            $rulerOptions = $rulers->getMedievalRulersAjax($this->getParam('term'));
            if ($rulerOptions) {
                $response = $rulerOptions;
            } else {
                $response = array(array('id' => '', 'term' => 'No options available'));
            }
        } else {
            $response = array(array('id' => null, 'term' => 'No category specified'));
        }
        echo Zend_Json::encode($response);
    }

    /** Get moneyers as json
     * @return mixed
     */
    public function moneyersAction()
    {
        if ($this->getParam('term', false)) {
            $ruler = $this->getParam('term');
            $moneyers = new Moneyers();
            $moneyerOptions = $moneyers->getMoneyers();
            if ($ruler == 242) {
                $response = $moneyerOptions;
            } else {
                $response = array(array('id' => '', 'term' => 'No options available'));
            }
        } else {
            $response = array(array('id' => null, 'term' => 'No options available'));
        }
        echo Zend_Json::encode($response);
    }

    /** Get related finds as json
     * @access public
     * @return mixed
     */
    public function relatedfindAction()
    {
        $finds = new Finds;
        $findsjson = $finds->getFindSecuid($this->getParam('q'));
        echo Zend_Json::encode($findsjson);
    }

    /** Get old find ID as json
     * @access public
     * @return mixed
     */
    public function oldfindidAction()
    {
        $finds = new Finds;
        $findsjson = $finds->getOldFindID($this->getParam('q'));
        echo Zend_Json::encode($findsjson);
    }

    /** Get organisations as json
     * @access public
     * @return mixed
     */
    public function organisationAction()
    {
        $orgs = new Organisations;
        $orgsjson = $orgs->getOrgNames($this->getParam('q'));
        echo Zend_Json::encode($orgsjson);
    }

    /** Get usernames as json
     * @access public
     * @return mixed
     */
    public function usernameAction()
    {
        $users = new Users;
        $usersjson = $users->findUserAccountAjax($this->getParam('q'));
        echo Zend_Json::encode($usersjson);
    }

    /** Delete image link
     * @access public
     * @return mixed
     */
    public function deleteimagelinkAction()
    {
        if ($this->getParam('id', false)) {
            $links = new FindsImages();
            $where = $links->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
            $links->delete($where);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Get  staff data as json
     * @access public
     * @return mixed
     */
    public function staffdataAction()
    {
        $this->_helper->viewRenderer->setNoRender(false);
        $contacts = new Contacts();
        $this->view->contacts = $contacts->getContactsForMap();
    }

    /** Delete a project
     * @access public
     * @return mixed
     * @throws Pas_Exception_Param
     */
    public function deleteprojectAction()
    {
        if ($this->getParam('id', false)) {
            $projects = new ResearchProjects();
            $where = $projects->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
            $projects->delete($where);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 404);
        }
    }

    /** Delete an image by ruler
     * @access public
     * @return mixed
     */
    public function deleteimagerulerAction()
    {
        $images = new RulerImages();
        $deletefiles = $images->getFilename($this->getParam('id'));
        foreach ($deletefiles as $files) {
            $filename = $files['filename'];
            $where = $images->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
            $images->delete($where);
            unlink('./assets/rulers/' . $filename);
        }

    }

    /** Delete a profile image
     * @access public
     * @return mixed
     */
    public function deleteprofileimageAction()
    {
        $staff = new Contacts();
        $staffs = $staff->getImage($this->getParam('id'));
        foreach ($staffs as $staff) {
            $filename = $staff['image'];
        }
        $updateData = array();
        $updateData['image'] = null;
        $updateData['updated'] = $this->getTimeForForms();
        $updateData['updatedBy'] = $this->getIdentityForForms();
        $stafflist = new Contacts();

        $where = $stafflist->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
        $stafflist->update($updateData, $where);
        $name = substr($filename, 0, strrpos($filename, '.'));
        $ext = '.jpg';
        $converted = $name . $ext;
        unlink('./assets/staffphotos/' . $filename);
        unlink('./assets/staffphotos/resized/' . $converted);
        unlink('./assets/staffphotos/thumbnails/' . $converted);
    }

    /** Delete a mint and ruler
     * @access public
     * @return mixed
     */
    public function deletemintrulerAction()
    {
        $mints = new MintsRulers();
        $where = $mints->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
        $mints->delete($where);
    }

    /** Delete a denom by ruler id
     * @access public
     * @return mixed
     */
    public function deletedenomrulerAction()
    {
        $denoms = new DenomRulers();
        $where = $denoms->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
        $denoms->delete($where);
    }

    /** Delete a reverse type by ruler id
     * @access public
     */
    public function deletereverserulerAction()
    {
        $reverses = new RulerRevType();
        $where = $reverses->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
        $reverses->delete($where);
    }

    /** Link an image
     * @access public
     * @return mixed
     * @throws Pas_Exception_Param
     */
    public function linkimageAction()
    {
        if ($this->getParam('secuid', false)) {
            $this->_helper->layout->disableLayout();
            $form = new ImageLinkForm();
            $this->view->form = $form;
            $images = new Slides();
            $this->view->images = $images->getImageForLinks($this->getParam('secuid'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Scheduled ancient monument action
     * @access public
     * @return mixed
     */
    public function samsAction()
    {
        $monuments = new ScheduledMonuments();
        $monjson = $monuments->samLookup($this->getParam('q'));
        echo Zend_Json::encode($monjson);
    }

    /** Delete a comment ajax action
     * @access public
     * @return mixed
     * @throws Pas_Exception_Param
     */
    public function deletecommentAction()
    {
        if ($this->getParam('id', false)) {
            $comments = new Comments();
            $where = $comments->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
            $comments->delete($where);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Emperors action
     * @access public
     */
    public function emperorsAction()
    {
        $emps = new Emperors();
        $this->view->emperors = $emps->getEmperorsTimeline();
    }

    /** List message replies
     * @access public
     */
    public function messagereplyAction()
    {
        $replies = new Replies();
        $this->view->replies = $replies->fetchRow('messageID=' . $this->getParam('id'));
        $this->_helper->viewRenderer->setNoRender(false);
    }

    /** Get the OS parishes by county
     * @access public
     * @return mixed
     */
    public function osparishesbycountyAction()
    {
        if ($this->getParam('term', false)) {
            $parishes = new OsParishes();
            $json = $parishes->getParishesToCounty($this->getParam('term'));
        } else {
            $json = array(null => 'You must choose a county first');
        }
        echo Zend_Json::encode($json);
    }

    /** Get the os districts by county json
     * @access public
     */
    public function osdistrictsbycountyAction()
    {
        if ($this->getParam('term', false)) {
            $districts = new OsDistricts();
            $json = $districts->getDistrictsToCounty($this->getParam('term'));
        } else {
            $json = array(null => 'You must choose a county first');
        }
        echo Zend_Json::encode($json);
    }

    /** Get the OS regions by county as json
     * @access public
     */
    public function osregionsbycountyAction()
    {
        if ($this->getParam('term', false)) {
            $parishes = new OsCounties();
            $json = $parishes->getCountyToRegion($this->getParam('term'));
        } else {
            $json = array(null => 'You must choose a county first');
        }
        echo Zend_Json::encode($json);
    }

    /** Get os parishes by district
     * @access public
     */
    public function osparishesbydistrictAction()
    {
        if ($this->getParam('term', false)) {
            $parishes = new OsParishes();
            $json = $parishes->getParishesToDistrict($this->getParam('term'));

        } else {
            $json = array(null => 'You must choose a district first');
        }
        echo Zend_Json::encode($json);
    }

    /** Get the usernames
     * @access public
     */
    public function usernamesAction()
    {
        if ($this->getParam('q', false)) {
            $users = new Users();
            $json = $users->usernames($this->getParam('q'));
            echo Zend_Json::encode($json);
        }
    }

    /** Get the user's full names
     * @access public
     */
    public function usersfullnamesAction()
    {
        if ($this->getParam('q', false)) {
            $users = new Users();
            $json = $users->userFullNames($this->getParam('q'));
            echo Zend_Json::encode($json);
        }
    }

    /** Get the publications as json
     * @access public
     */
    public function publicationsAction()
    {
        if ($this->getParam('term', false)) {
            $publication = new Publications();
            $json = $publication->getTitles($this->getParam('term'));
        } else {
            $json = array(null => 'You must choose an author first');
        }
        echo Zend_Json::encode($json);
    }

    /** Ajax action that returns a dynamic form field for HoardForm
     * @access public
     * @return string
     */
    public function newfieldAction()
    {

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('newfield', 'html')->initContext();

        $id = $this->getParam('hiddenfield', null);
        $uniqueTextLabel = "finder$id";
        $uniqueIdLabel = $uniqueTextLabel . 'ID';

        $finderID = new Zend_Form_Element_Hidden($uniqueIdLabel);
        $finderID->setRequired(false)
            ->removeDecorator('Label')
            ->removeDecorator('HtmlTag')
            ->addFilters(array('StripTags', 'StringTrim', 'Null'));

        $finderName = new Zend_Form_Element_Text($uniqueTextLabel);
        $finderName->setRequired(true)
            ->setLabel('Also found by:')
            ->addDecorators(array(
                array('HtmlTag', array('tag' => 'div', 'class' => "controls", 'id' => "$uniqueTextLabel-controls")),
                array('Label', array('class' => 'control-label', 'id' => "$uniqueTextLabel")),
                array(array('controlGroupWrapper' => 'HtmlTag'),
                    array('tag' => 'div', 'class' => "control-group", 'id' => "$uniqueTextLabel-control-group")),
            ))
            ->addFilters(array('StripTags', 'StringTrim', 'Null'));

        $this->view->finderName = $finderName->__toString();
        $this->view->finderID = $finderID->__toString();
        $this->_helper->viewRenderer->setNoRender(false);
    }

    /** Action to generate the last ruler based off broadperiod
     * @access public
     */
    public function lastrulerAction()
    {
        if ($this->getParam('term', false)) {
            $rulers = new Rulers();
            $data = $rulers->getLastRulers($this->getParam('term'));
            if (empty($data)) {
                $data = array(array('id' => null, 'term' => 'No Options'));
            }
        } else {
            $data = array(null => 'You must choose a broad period first');
        }
        echo Zend_Json::encode($data);
    }

    /** Get the denominations
     * @access public
     */
    public function getdenominationsAction()
    {
        if ($this->getParam('term', false)) {
            $denominations = new Denominations();
            $data = $denominations->getDenominationByBroadPeriod($this->getParam('term'));
            if (empty($data)) {
                $data = array(array('id' => null, 'term' => 'No Options'));
            }
        } else {
            $data = array(null => 'You must choose a broad period first');
        }
        echo Zend_Json::encode($data);
    }

    /** Get the mints in json format
     * @access public
     */
    public function getmintsAction()
    {
        if ($this->getParam('term', false)) {
            $mints = new Mints();
            $data = $mints->getMintbyBroadperiod($this->getParam('term'));
            if (empty($data)) {
                $data = array(array('id' => null, 'term' => 'No Options'));
            }
        } else {
            $data = array(null => 'You must choose a broad period first');
        }
        echo Zend_Json::encode($data);
    }

    public function keepaliveAction()
    {

    }
}