<?php

/** Controller for displaying object terminologies we employ
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Periods
 * @uses Pas_Exception_Param
 * @uses PrimaryActivities
 * @uses DiscoMethods
 * @uses FindOfNoteReasons
 * @uses Landuses
 * @uses Materials
 * @uses Denominations
 * @uses Preservations
 * @uses Manufactures
 * @uses DecStyles
 * @uses Mints
 * @uses Rulers
 * @uses Completeness
 * @uses ObjectTerms
 * @uses Workflows
 * @uses DieAxes
 * @uses Completeness
 * @uses Cultures
 * @uses DecorationMethods
 * @uses SurfTreatments
 *
 */
class Datalabs_TerminologyController extends Pas_Controller_Action_Admin
{

    /** The contexts array
     * @access protected
     * @var array
     */
    protected $_contexts;

    /** The periods model
     * @access protected
     * @var \Periods
     */
    protected $_periods;

    /** Get the periods model
     * @access public
     * @return \Periods
     */
    public function getPeriods()
    {
        $this->_periods = new Periods();
        return $this->_periods;
    }

    /** The primary activity model
     * @access public
     * @var \PrimaryActivities
     */
    protected $_primaryActivities;

    /** Get the primary activities model
     * @access public
     * @return \PrimaryActivities
     */
    public function getPrimaryActivities()
    {
        $this->_primaryActivities = new PrimaryActivities();
        return $this->_primaryActivities;
    }

    /** The Decoration Styles model
     * @access protected
     * @var \DecStyles
     */
    protected $_decStyles;

    /** Get the decoration Styles model
     * @access public
     * @return \DecStyles
     */
    public function getDecStyles()
    {
        $this->_decStyles = new DecStyles();
        return $this->_decStyles;
    }

    /** The discovery methods model
     * @access protected
     * @var \DiscoMethods
     */
    protected $_discoMethods;

    /** Get the disco methods model
     * @access public
     * @return \DiscoMethods
     */
    public function getDiscoMethods()
    {
        $this->_discoMethods = new DiscoMethods();
        return $this->_discoMethods;
    }

    /** Get the preservations model
     * @access protected
     * @var \Preservations
     */
    protected $_preservations;

    /** Get the preservations model
     * @access public
     * @return \Preservations
     */
    public function getPreservations()
    {
        $this->_preservations = new Preservations();
        return $this->_preservations;
    }

    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('public', null);

        $this->_contexts = array('xml', 'json');
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
            ->addActionContext('periods', $this->_contexts)
            ->addActionContext('period', $this->_contexts)
            ->addActionContext('activities', $this->_contexts)
            ->addActionContext('activity', $this->_contexts)
            ->addActionContext('cultures', $this->_contexts)
            ->addActionContext('culture', $this->_contexts)
            ->addActionContext('methods', $this->_contexts)
            ->addActionContext('method', $this->_contexts)
            ->addActionContext('preservations', $this->_contexts)
            ->addActionContext('preservation', $this->_contexts)
            ->addActionContext('notes', $this->_contexts)
            ->addActionContext('note', $this->_contexts)
            ->addActionContext('materials', $this->_contexts)
            ->addActionContext('material', $this->_contexts)
            ->addActionContext('workflows', $this->_contexts)
            ->addActionContext('workflow', $this->_contexts)
            ->addActionContext('manufactures', $this->_contexts)
            ->addActionContext('manufacture', $this->_contexts)
            ->addActionContext('surfaces', $this->_contexts)
            ->addActionContext('surface', $this->_contexts)
            ->addActionContext('objects', $this->_contexts)
            ->addActionContext('object', $this->_contexts)
            ->addActionContext('rulers', $this->_contexts)
            ->addActionContext('mints', $this->_contexts)
            ->addActionContext('denominations', $this->_contexts)
            ->addActionContext('dieaxes', $this->_contexts)
            ->addActionContext('dieaxis', $this->_contexts)
            ->addActionContext('index', $this->_contexts)
            ->addActionContext('landuses', $this->_contexts)
            ->addActionContext('landuse', $this->_contexts)
            ->initContext();

    }

    /** Setup the index page for listing the actions to show
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $vocab = array(
            'activities', 'periods', 'cultures',
            'denominations', 'rulers', 'surfaces',
            'mints', 'objects', 'manufactures',
            'workflows', 'notes', 'methods',
            'preservations', 'weartypes');
        $base = $this->view->serverUrl() . '/datalabs/terminology/';
        $vocab3 = sort($vocab);
        $vocab2 = null;
        foreach ($vocab as $v) {
            $vocab2[] = array(
                'type' => $v,
                'html' => $base . $v,
                'xml' => $base . $v . '/format/xml',
                'json' => $base . $v . '/format/json');
        }
        $this->view->vocabularies = $vocab2;
    }

    /** Display a list of periods
     * @access public
     * @return void
     */
    public function periodsAction()
    {
        $this->view->periods = $this->getPeriods()->getPeriods();
    }

    /** Details about a specific period
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function periodAction()
    {
        if ($this->_getParam('id', false)) {
            $this->view->periods = $this->getPeriods()->getPeriodDetails($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Show a list of primary activities
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function activityAction()
    {
        if ($this->_getParam('id', false)) {
            $this->view->activities = $this->getPrimaryActivities()
                ->getActivityDetails($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Show details of an activity
     * @access public
     * @return void
     */
    public function activitiesAction()
    {
        $this->view->activities = $this->getPrimaryActivities()
            ->getActivitiesList();
    }

    /** Show details of a method
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function methodAction()
    {
        if ($this->_getParam('id', false)) {
            $this->view->methods = $this->getDiscoMethods()->getDiscmethodInformation($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Display a list of methods
     * @access public
     * @return void
     */
    public function methodsAction()
    {
        $this->view->methods = $this->getDiscoMethods()->getDiscMethodsList();
    }

    /** Display details for a preservation method
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function preservationAction()
    {
        if ($this->_getParam('id', false)) {
            $this->view->preserves = $this->getPreservations()
                ->getPreservationDetails($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Display list of preservation methods
     * @access public
     * @return void
     */
    public function preservationsAction()
    {
        $this->view->preserves = $this->getPreservations()->getPreservationTerms();
    }

    /** The find of note reason model
     * @access protected
     * @var \FindOfNoteReasons
     */
    protected $_findsOfNoteReasons;

    /** Get the find of note model
     * @access public
     * @return \FindOfNoteReasons
     */
    public function getFindsOfNoteReasons()
    {
        $this->_findsOfNoteReasons = new FindOfNoteReasons();
        return $this->_findsOfNoteReasons;
    }

    /** Display details for a find of note
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function noteAction()
    {
        if ($this->_getParam('id', false)) {
            $this->view->notes = $this->getFindsOfNoteReasons()
                ->getReasonDetails($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Display details for notes
     * @access public
     * @return void
     */
    public function notesAction()
    {
        $this->view->notes = $this->getFindsOfNoteReasons()->getReasonsList();
    }

    /** Get the cultures model
     * @access public
     * @var \Cultures
     */
    protected $_cultures;

    /** Get the cultures model
     * @access public
     * @return \Cultures
     *
     */
    public function getCultures()
    {
        $this->_cultures = new Cultures();
        return $this->_cultures;
    }

    /** Display details for an ascribed cultural identity
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function cultureAction()
    {
        if ($this->_getParam('id', false)) {
            $this->view->cultures = $this->getCultures()
                ->getCulture($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Display list of ascribed cultures
     * @access public
     * @return void
     */
    public function culturesAction()
    {
        $this->view->cultures = $this->getCultures()->getCulturesList();
    }

    /** The materials
     * @access protected
     * @var \Materials
     */
    protected $_materials;

    /** Get the materials model
     * @access public
     * @return \Materials
     */
    public function getMaterials()
    {
        $this->_materials = new Materials();
        return $this->_materials;
    }

    /** Display details for a material
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function materialAction()
    {
        if ($this->_getParam('id', false)) {
            $this->view->materials = $this->getMaterials()->getMaterialDetails($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Display list of materials
     * @access public
     * @return void
     */
    public function materialsAction()
    {
        $this->view->materials = $this->getMaterials()->getMaterials();
    }

    /** Display details for a decoration style
     * @access public
     * @@return void
     * @throws Pas_Exception_Param
     */
    public function decorationstyleAction()
    {
        if ($this->_getParam('id', false)) {
            $this->view->decs = $this->getDecStyles()->getDecStyleDetails($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Display list of decoration styles
     * @access public
     * @return void
     */
    public function decorationstylesAction()
    {
        $this->view->decs = $this->getDecStyles()->getDecStyles();
    }

    /** The manufactures model
     * @access protected
     * @var \Manufactures
     */
    protected $_manufactures;

    /** Get the manufactures method
     * @access public
     * @return \Manufactures
     */
    public function getManufactures()
    {
        $this->_manufactures = new Manufactures();
        return $this->_manufactures;
    }

    /** Display details for method of manufacture
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function manufactureAction()
    {
        if ($this->_getParam('id', false)) {
            $this->view->manufactures = $this->getManufactures()
                ->getManufactureDetails($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Display list of manufacturing methods
     * @access public
     * @return void
     */
    public function manufacturesAction()
    {
        $this->view->manufactures = $this->getManufactures()
            ->getManufacturesListed();
    }

    /** The decoration methods model
     * @access protected
     * @var \DecorationMethods
     *
     */
    protected $_decorationMethods;

    /** Get the decoration methods model
     * @access public
     * @return \DecorationMethods
     */
    public function getDecorationMethods()
    {
        $this->_decorationMethods = new DecMethods();
        return $this->_decorationMethods;
    }

    /** Display details for a decorative method
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function decorationmethodAction()
    {
        if ($this->_getParam('id', false)) {
            $this->view->decs = $this->getDecorationMethods()
                ->getDecorationDetails($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Display list of decorative methods
     * @access public
     * @return void
     */
    public function decorationmethodsAction()
    {
        $this->view->decs = $this->getDecorationMethods()->getDecorationDetailsList();
    }

    /** The mints model
     * @access protected
     * @var \Mints
     */
    protected $_mints;

    /** Get the mints model
     * @access public
     * @return \Mints
     */
    public function getMints()
    {
        $this->_mints = new Mints();
        return $this->_mints;
    }

    /** Display list of mints
     * @access public
     * @return void
     */
    public function mintsAction()
    {
        $mintsList = $this->getMints()->getMintsListAll($this->getAllParams());
        if (in_array($this->_helper->contextSwitch()->getCurrentContext(), $this->_contexts)) {
            $data = array(
                'pageNumber' => $mintsList->getCurrentPageNumber(),
                'total' => number_format($mintsList->getTotalItemCount(), 0),
                'itemsReturned' => $mintsList->getCurrentItemCount(),
                'totalPages' => number_format($mintsList->getTotalItemCount() /
                    $mintsList->getCurrentItemCount(), 0)
            );
            $this->view->data = $data;
            $mints = array();
            foreach ($mintsList as $k) {
                $action = $k['t'];
                switch ($action) {
                    case $action == strtoupper('Roman'):
                        $actionset = 'mint';
                        $module = 'romancoins';
                        break;
                    case $action == strtoupper('Byzantine'):
                        $module = 'byzantinecoins';
                        $actionset = 'mint';
                        break;
                    case $action == strtoupper('Greek and Roman Provincial');
                        $module = 'greekromancoins';
                        $actionset = 'mint';
                        break;
                    case $action == strtoupper('Post Medieval'):
                        $module = 'postmedievalcoins';
                        $actionset = 'mint';
                        break;
                    case $action == strtoupper('Early Medieval'):
                        $module = 'earlymedievalcoins';
                        $actionset = 'mint';
                        break;
                    case $action == strtoupper('Iron Age'):
                        $module = 'ironagecoins';
                        $actionset = 'mint';
                        break;
                    case $action == strtoupper('medieval');
                        $module = 'medievalcoins';
                        $actionset = 'mint';
                        break;
                    default:
                        $actionset = 'mint';
                        $module = 'medievalcoins';
                }
                $mints[] = array(
                    'id' => $k['id'],
                    'name' => $k['mint_name'],
                    'period' => $k['t'],
                    'url' => Zend_Registry::get('siteurl') . $this->view->url(array('module' => $module,
                            'controller' => $actionset . 's', 'action' => $actionset, 'id' => $k['id']), null, true)
                );
            }
            $this->view->mints = $mints;
        } else {
            $this->view->mints = $mintsList;
        }
    }

    /** The landuses model
     * @access protected
     * @var \Landuses
     */
    protected $_landuses;

    /** Get the landuses model
     * @access public
     * @return \Landuses
     */
    public function getLanduses()
    {
        $this->_landuses = new Landuses();
        return $this->_landuses;
    }

    /** Display landuse action details
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function landuseAction()
    {
        if ($this->_getParam('id', false)) {
            $this->view->landuses = $this->getLanduses()->getLanduseDetails($this->_getParam('id'));
            $this->view->landuses2 = $this->getLanduses()->getLandusesChild($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Display list of landuses
     * @access public
     * @return void
     */
    public function landusesAction()
    {
        $this->view->landuses = $this->getLanduses()->getLanduses();
    }

    /** The workflows model
     * @access protected
     * @var \Workflows
     */
    protected $_workflows;

    /** Get the workflow model
     * @access public
     * @return \Workflows
     */
    public function getWorkflows()
    {
        $this->_workflows = new Workflows();
        return $this->_workflows;
    }

    /** Display workflow details
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function workflowAction()
    {
        if ($this->_getParam('id', false)) {
            $this->view->workflows = $this->getWorkflows()->getStageName($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Display list of workflows
     * @access public
     * @return void
     */
    public function workflowsAction()
    {
        $this->view->workflows = $this->getWorkflows()->getStageNames();
    }

    /** The surface treatment model
     * @access protected
     * @var \SurfTreatments
     */
    protected $_surfaceTreatments;

    /** Get the surface treatment model
     * @access public
     * @return \SurfTreatments
     */
    public function getSurfaceTreatments()
    {
        $this->_surfaceTreatments = new SurfTreatments();
        return $this->_surfaceTreatments;
    }

    /** Display surface treastment details
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function surfaceAction()
    {
        if ($this->_getparam('id', false)) {
            $this->view->surfaces = $this->getSurfaceTreatments()
                ->getSurfaceTreatmentDetails($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Display list of surface treatments
     * @access public
     * @return void
     */
    public function surfacesAction()
    {
        $this->view->surfaces = $this->getSurfaceTreatments()
            ->getSurfaceTreatments();
    }

    /** The die axes model
     * @access protected
     * @var \DieAxes
     */
    protected $_dieAxes;

    /** Get the die axes model
     * @access public
     * @return \DieAxes
     */
    public function getDieAxes()
    {
        $this->_dieAxes = new DieAxes();
        return $this->_dieAxes;
    }

    /** Display list of die axes
     * @access public
     * @return void
     */
    public function dieaxesAction()
    {
        $this->view->dieaxes = $this->getDieAxes()->getDieList();
    }

    /** Display details of die axis
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function dieaxisAction()
    {
        if ($this->_getParam('id', false)) {
            $this->view->dieaxes = $this->getDieAxes()->getDieAxesDetails((int)$this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Display list of rulers
     * @access public
     * @return void
     */
    public function rulersAction()
    {
        $rulers = new Rulers();
        $rulerList = $rulers->getRulerList($this->getAllParams());
        if (in_array($this->_helper->contextSwitch()->getCurrentContext(), $this->_contexts)) {
            $data = array('pageNumber' => $rulerList->getCurrentPageNumber(),
                'total' => number_format($rulerList->getTotalItemCount(), 0),
                'itemsReturned' => $rulerList->getCurrentItemCount(),
                'totalPages' => number_format($rulerList->getTotalItemCount() /
                    $rulerList->getCurrentItemCount(), 0));
            $this->view->data = $data;
            $rulers = null;

            foreach ($rulerList as $k) {
                $action = $k['term'];
                switch ($action) {
                    case $action == strtoupper('Roman'):
                        $actionset = 'emperor';
                        $module = 'romancoins';
                        break;
                    case $action == strtoupper('Byzantine'):
                        $module = 'byzantinecoins';
                        $actionset = 'ruler';
                        break;
                    case $action == strtoupper('Greek and Roman Provincial');
                        $module = 'greekromancoins';
                        $actionset = 'ruler';
                        break;
                    case $action == strtoupper('Post Medieval'):
                        $module = 'postmedievalcoins';
                        $actionset = 'ruler';
                        break;
                    case $action == strtoupper('Early Medieval'):
                        $module = 'earlymedievalcoins';
                        $actionset = 'ruler';
                        break;
                    case $action == strtoupper('Iron Age'):
                        $module = 'ironagecoins';
                        $actionset = 'ruler';
                        break;
                    case $action == strtoupper('medieval');
                        $module = 'medievalcoins';
                        $actionset = 'ruler';
                        break;
                    default:
                        $actionset = 'ruler';
                        $module = 'medievalcoins';

                }


                if ($k['term'] == 'ROMAN') {
                    $id = $k['pasID'];
                } else {
                    $id = $k['id'];
                }
                $rulers[] = array(
                    'id' => $id,
                    'name' => $k['issuer'],
                    'period' => $k['term'],
                    'dateFrom' => $k['date1'],
                    'dateTo' => $k['date2'],
                    'pasID' => $k['pasID'],
                    'url' => $this->view->serverUrl() . $this->view->url(array('module' => $module,
                            'controller' => $actionset . 's', 'action' => $actionset, 'id' => $id), null, true)
                );

            }

            $this->view->rulers = $rulers;
        } else {
            $this->view->rulers = $rulerList;
        }
    }

    /** The object terms model
     * @access protected
     * @var \ObjectTerms
     */
    protected $_objectTerms;

    /** Get the object terms
     * @access public
     * @return \ObjectTerms
     */
    public function getObjectTerms()
    {
        $this->_objectTerms = new ObjectTerms();
        return $this->_objectTerms;
    }

    /** Display object type list
     * @access public
     * @return void
     */
    public function objectsAction()
    {
        $objectTerms = $this->getObjectTerms()->getAllObjectData($this->getAllParams());
        if (in_array($this->_helper->contextSwitch()->getCurrentContext(), $this->_contexts)) {
            $data = array(
                'pageNumber' => $objectTerms->getCurrentPageNumber(),
                'total' => number_format($objectTerms->getTotalItemCount(), 0),
                'itemsReturned' => $objectTerms->getCurrentItemCount(),
                'totalPages' => number_format(
                    $objectTerms->getTotalItemCount() /
                    $objectTerms->getCurrentItemCount(),
                    0));
            $this->view->data = $data;
            $objectterms = array();

            foreach ($objectTerms as $k => $v) {
                $objectterms[$k] = $v;
            }

            $this->view->objectdata = $objectterms;
        } else {
            $this->view->paginator = $objectTerms;
        }
    }

    /** Display details of an object term
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function objectAction()
    {
        if ($this->_getParam('term', false)) {
            $this->view->objectdata = $this->getObjectTerms()
                ->getObjectTermDetail($this->_getParam('term'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** The completeness model
     * @access protected
     * @var \Completeness
     */
    protected $_completeness;

    /** Get the completeness model
     * @access public
     * @return \Completeness
     */
    public function getCompleteness()
    {
        $this->_completeness = new Completeness();
        return $this->_completeness;
    }

    /** Get the completeness term
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function completenessAction()
    {
        if ($this->_getParam('id', false)) {
            $this->view->comps = $this->getCompleteness()
                ->getDetails($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** The denomination model
     * @access protected
     * @var \Denominations
     */
    protected $_denominations;

    /** Get the denomination model
     * @access public
     * @return \Denominations
     */
    public function getDenominations()
    {
        $this->_denominations = new Denominations();
        return $this->_denominations;
    }

    /** Get a list of denominations
     * @access public
     * @return void
     */
    public function denominationsAction()
    {
        $denomsList = $this->getDenominations()->getDenomsValid($this->getAllParams());
        if (in_array($this->_helper->contextSwitch()->getCurrentContext(), $this->_contexts)) {
            $data = array(
                'pageNumber' => $denomsList->getCurrentPageNumber(),
                'total' => number_format($denomsList->getTotalItemCount(), 0),
                'itemsReturned' => $denomsList->getCurrentItemCount(),
                'totalPages' => number_format(
                    $denomsList->getTotalItemCount() /
                    $denomsList->getCurrentItemCount(), 0)
            );
            $this->view->data = $data;
            $denoms = array();
            foreach ($denomsList as $k) {

                $action = $k['temporal'];
                switch ($action) {
                    case $action == strtoupper('Roman'):
                        $actionset = 'denomination';
                        $module = 'romancoins';
                        break;
                    case $action == strtoupper('Byzantine'):
                        $module = 'byzantinecoins';
                        $actionset = 'denomination';
                        break;
                    case $action == strtoupper('Greek and Roman Provincial');
                        $module = 'greekromancoins';
                        $actionset = 'denomination';
                        break;
                    case $action == strtoupper('Post Medieval'):
                        $module = 'postmedievalcoins';
                        $actionset = 'denomination';
                        break;
                    case $action == strtoupper('Early Medieval'):
                        $module = 'earlymedievalcoins';
                        $actionset = 'denomination';
                        break;
                    case $action == strtoupper('Iron Age'):
                        $module = 'ironagecoins';
                        $actionset = 'denomination';
                        break;
                    case $action == strtoupper('medieval');
                        $module = 'medievalcoins';
                        $actionset = 'denomination';
                        break;
                    default:
                        $actionset = 'denomination';
                        $module = 'medievalcoins';
                }
                $denoms[] = array(
                    'id' => $k['id'],
                    'name' => $k['denomination'],
                    'period' => $k['temporal'],
                    'url' => $this->view->serverUrl() . $this->view->url(array(
                            'module' => $module,
                            'controller' => $actionset . 's',
                            'action' => $actionset,
                            'id' => $k['id']
                        ), null, true)
                );
            }
            $this->view->denominations = $denoms;
        } else {
            $this->view->denominations = $denomsList;
        }
    }

    /** A list of wear types
     * @access public
     * @return void
     */
    public function weartypesAction()
    {
        $wear = new WearTypes();
        $this->view->wear = $wear->getWearTypesAdmin();
    }

    /** A wear type individual record
     * @access public
     */
    public function weartypeAction()
    {
        if($this->_getParam('id', false)) {
            $wear = new WearTypes();
            $this->view->wear = $wear->getWearType($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}