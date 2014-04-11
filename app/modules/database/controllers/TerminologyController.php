<?php
/** Controller for displaying object terminologies we employ
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_TerminologyController extends Pas_Controller_Action_Admin {

	protected $_contexts, $_periods;
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$this->_helper->_acl->allow('public',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		 ->addActionContext('periods', 		$this->_contexts)
 		 ->addActionContext('period', 		$this->_contexts)
 		 ->addActionContext('activities', 	$this->_contexts)
 		 ->addActionContext('activity', 	$this->_contexts)
 		 ->addActionContext('cultures', 	$this->_contexts)
 		 ->addActionContext('culture', 		$this->_contexts)
 		 ->addActionContext('methods', 		$this->_contexts)
 		 ->addActionContext('method', 		$this->_contexts)
 		 ->addActionContext('preservations',$this->_contexts)
 		 ->addActionContext('preservation', $this->_contexts)
 		 ->addActionContext('notes',		$this->_contexts)
		 ->addActionContext('note',			$this->_contexts)
		 ->addActionContext('materials',	$this->_contexts)
		 ->addActionContext('material',		$this->_contexts)
		 ->addActionContext('workflows',	$this->_contexts)
		 ->addActionContext('workflow',		$this->_contexts)
		 ->addActionContext('manufactures',	$this->_contexts)
		 ->addActionContext('manufacture',	$this->_contexts)
		 ->addActionContext('surfaces',		$this->_contexts)
		 ->addActionContext('surface',		$this->_contexts)
		 ->addActionContext('objects',		$this->_contexts)
		 ->addActionContext('object',		$this->_contexts)
		 ->addActionContext('rulers',		$this->_contexts)
		 ->addActionContext('mints',		$this->_contexts)
		 ->addActionContext('denominations',$this->_contexts)
		 ->addActionContext('dieaxes',		$this->_contexts)
		 ->addActionContext('dieaxis',		$this->_contexts)
		 ->addActionContext('index',		$this->_contexts)
		 ->addActionContext('landuses',		$this->_contexts)
		 ->addActionContext('landuse',		$this->_contexts)		 
		 ->initContext();
	$this->_periods = new Periods();
	}
	/** Setup the index page for listing the actions to show
	*/
	public function indexAction() {
	$vocab = array(
		'activities', 'periods', 'cultures',
		'denominations', 'rulers', 'surfaces',
		'mints', 'objects', 'manufactures',
		'workflows', 'notes', 'methods',
		'preservations');
	$base = $this->view->serverUrl() . '/database/terminology/';
	$vocab3 = sort($vocab);
	$vocab2 = NULL;
	foreach($vocab as $v){
		$vocab2[] = array(
		'type' => $v,
		'html' =>  $base . $v,
		'xml' => $base . $v . '/format/xml',
		'json' => $base . $v . '/format/json');
	}
	$this->view->vocabularies = $vocab2;
	}
	/** Display a list of periods
	*/
	public function periodsAction() {
	$this->view->periods = $this->_periods->getPeriods();
	}
	/** Details about a specific period
	*/
	public function periodAction() {
	if($this->_getParam('id',false)){
	$this->view->periods = $this->_periods->getPeriodDetails($this->_getParam('id'));
	} else {
		throw new Exception($this->_missingParameter);
	}
	}
	/** Show a tag cloud of periods
	*/
	public function periodtagcloudAction() {
	$this->getHelper('layout')->disableLayout();
	$this->view->periods = $this->_periods->getPeriodDetails($this->_getParam('id'));
	$this->view->objects = $this->_periods->getObjectTypesByPeriod($this->_getParam('id'));
	}
	/** Show a list of primary activities
	*/
	public function activityAction() {
	if($this->_getParam('id',false)){
	$activities = new PrimaryActivities();
	$this->view->activities = $activities->getActivityDetails($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Show details of an activity
	*/
	public function activitiesAction() {
	$activities = new PrimaryActivities();
	$this->view->activities = $activities->getActivitiesList();
	}
	/** Show details of a method
	*/
	public function methodAction()  {
	if($this->_getParam('id',false)) {
	$methods = new DiscoMethods();
	$this->view->methods = $methods->getDiscmethodInformation($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Display a list of methods
	*/
	public function methodsAction() {
	$methods = new DiscoMethods();
	$this->view->methods = $methods->getDiscMethodsList();
	}
	/** Display details for a preservation method
	*/
	public function preservationAction() {
	if($this->_getParam('id',false)) {
	$preserves = new Preservations();
	$this->view->preserves = $preserves->getPreservationDetails($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Display list of preservation methods
	*/
	public function preservationsAction() {
	$preserves = new Preservations();
	$this->view->preserves = $preserves->getPreservationTerms();
	}
	/** Display details for a find of note
	*/
 	public function noteAction() {
	if($this->_getParam('id',false)) {
	$notes = new Findofnotereasons();
	$this->view->notes = $notes->getReasonDetails($this->_getParam('id'));
	} else {
            throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Display details for notes
	*/
	public function notesAction() {
	$notes = new Findofnotereasons();
	$this->view->notes = $notes->getReasonsList();
	}
	/** Display details for an ascribed cultural identity
	*/
	public function cultureAction() {
	if($this->_getParam('id',false)) {
	$cultures = new Cultures();
	$this->view->cultures = $cultures->getCulture($this->_getParam('id'));
	} else {
            throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Display list of ascribed cultures
	*/
	public function culturesAction() {
	$cultures = new Cultures();
	$this->view->cultures = $cultures->getCulturesList();
	}
	/** Display details for a material
	*/
	public function materialAction() {
	if($this->_getParam('id',false)) {
	$materials = new Materials();
	$this->view->materials = $materials->getMaterialDetails($this->_getParam('id'));
	} else {
            throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Display list of materials
	*/
	public function materialsAction() {
	$materials = new Materials();
	$this->view->materials = $materials->getMaterials();
	}
	/** Display details for a decoration style
	*/
	public function decorationstyleAction() {
	if($this->_getParam('id',false)) {
	$decs = new Decstyles();
	$this->view->decs = $decs->getDecStyleDetails($this->_getParam('id'));
	} else {
            throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Display list of decoration styles
	*/
	public function decorationstylesAction() {
	$decs = new Decstyles();
	$this->view->decs = $decs->getDecStyles();
	}
	/** Display details for method of manufacture
	*/
	public function manufactureAction() {
	if($this->_getParam('id',false)) {
	$manufactures = new Manufactures();
	$this->view->manufactures = $manufactures->getManufactureDetails($this->_getParam('id'));
	} else {
            throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Display list of manufacturing methods
	*/
	public function manufacturesAction() {
	$manufactures = new Manufactures();
	$this->view->manufactures = $manufactures->getManufacturesListed();
	}
	/** Display details for a decorative method
	*/
	public function decorationmethodAction() {
	if($this->_getParam('id',false)) {
	$decs = new Decmethods();
	$this->view->decs = $decs->getDecorationDetails($this->_getParam('id'));
	$this->view->counts = $decs->getDecCount($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Display list of decorative methods
	*/
	public function decorationmethodsAction() {
	$decs = new Decmethods();
	$this->view->decs = $decs->getDecorationDetailsList();
	}
	/** Display list of mints
	*/
	public function mintsAction() {
	$mints = new Mints();
	$mintsList = $mints->getMintsListAll($this->_getAllParams());
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$this->_contexts)) {
	$data = array('pageNumber' => $mintsList->getCurrentPageNumber(),
				  'total' => number_format($mintsList->getTotalItemCount(),0),
				  'itemsReturned' => $mintsList->getCurrentItemCount(),
				  'totalPages' => number_format($mintsList->getTotalItemCount()/
				$mintsList->getCurrentItemCount(),0));
	$this->view->data = $data;
	$mints = NULL;
	foreach($mintsList as $k){
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
	'controller' => $actionset.'s' ,'action' => $actionset,'id' => $k['id']),null,true)
	);
	}
	$this->view->mints = $mints;
	} else {
	$this->view->mints = $mintsList;
	}
	}
	/** Display landuse action details
	*/
	public function landuseAction() {
	if($this->_getParam('id',false)) {
	$landuses = new Landuses();
	$this->view->landuses = $landuses->getLanduseDetails($this->_getParam('id'));
	$this->view->landuses2 = $landuses->getLandusesChild($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Display list of landuses
	*/
	public function landusesAction() {
	$landuses = new Landuses();
	$this->view->landuses = $landuses->getLanduses();
	}
	/** Display workflow details
	*/
	public function workflowAction() {
	if($this->_getParam('id',false)) {
	$workflows = new Workflows();
	$this->view->workflows = $workflows->getStageName($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Display list of workflows
	*/
	public function workflowsAction() {
	$workflows = new Workflows();
	$this->view->workflows = $workflows->getStageNames();
	}
	/** Display surface treastment details
	*/
	public function surfaceAction() {
	if($this->_getparam('id',false)) {
	$surfaces = new Surftreatments();
	$this->view->surfaces = $surfaces->getSurfaceTreatmentDetails($this->_getParam('id'));
	$this->view->counts = $surfaces->getSurfaceCounts($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Display list of surface treatments
	*/
	public function surfacesAction() {
	$surfaces = new Surftreatments();
	$this->view->surfaces = $surfaces->getSurfaceTreatments();
	}
	/** Display list of die axes
	*/
	public function dieaxesAction() {
	$dieaxes = new Dieaxes();
	$this->view->dieaxes = $dieaxes->getDieList();
	}
	/** Display details of die axis
	*/
	public function dieaxisAction() {
	if($this->_getParam('id',false)){
	$dieaxes = new Dieaxes();
	$this->view->dieaxes = $dieaxes->getDieAxesDetails((int)$this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Display list of rulers
	*/
	public function rulersAction() {
	$rulers = new Rulers();
	$rulerList = $rulers->getRulerList($this->_getAllParams());
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$this->_contexts)) {
	$data = array('pageNumber' => $rulerList->getCurrentPageNumber(),
				  'total' => number_format($rulerList->getTotalItemCount(),0),
				  'itemsReturned' => $rulerList->getCurrentItemCount(),
				  'totalPages' => number_format($rulerList->getTotalItemCount()/
				$rulerList->getCurrentItemCount(),0));
	$this->view->data = $data;
	$rulers = NULL;

	foreach($rulerList as $k){
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
	
	
	if($k['term'] == 'ROMAN'){
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
	'controller' => $actionset . 's' ,'action' => $actionset,'id' => $id),null,true)
	);
	
	}

	$this->view->rulers = $rulers;
	} else {
	$this->view->rulers = $rulerList;
	}
	}
	/** Display object type list
	*/
	public function objectsAction() {
	$objects = new ObjectTerms();
	$objectTerms = $objects->getAllObjectData($this->_getAllParams());
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$this->_contexts)) {
	$data = array('pageNumber' => $objectTerms->getCurrentPageNumber(),
				  'total' => number_format($objectTerms->getTotalItemCount(),0),
				  'itemsReturned' => $objectTerms->getCurrentItemCount(),
				  'totalPages' => number_format($objectTerms->getTotalItemCount()/
				$objectTerms->getCurrentItemCount(),0));
	$this->view->data = $data;
	$objectterms = array();

	foreach($objectTerms as $k => $v){
	$objectterms[$k] = $v;
	}

	$this->view->objectdata = $objectterms;
	} else {
	$this->view->paginator = $objectTerms;
	}
	}

	/** Display details of an object term
	*/
	public function objectAction()
	{
	$term = $this->_getParam('term');
	$objects = new ObjectTerms();
	$this->view->objectdata = $objects->getObjectTermDetail($term);
	}
	/** Display completeness details
	*/
	public function completenessAction() {
	if($this->_getParam('id',false)) {
	$comp = new Completeness();
	$this->view->comps = $comp->getDetails($this->_getParam('id'));
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Display list of denominations
	*/
	public function denominationsAction() {
	$denominations = new Denominations();
	$denomsList = $denominations->getDenomsValid($this->_getAllParams());
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$this->_contexts)) {
	$data = array('pageNumber' => $denomsList->getCurrentPageNumber(),
				  'total' => number_format($denomsList->getTotalItemCount(),0),
				  'itemsReturned' => $denomsList->getCurrentItemCount(),
				  'totalPages' => number_format($denomsList->getTotalItemCount()/
				$denomsList->getCurrentItemCount(),0));
	$this->view->data = $data;
	$denoms = NULL;

	foreach($denomsList as $k){

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
	'url' => $this->view->serverUrl() . $this->view->url(array('module' => $module,
	'controller' => $actionset.'s' ,'action' => $actionset,'id' => $k['id']),null,true)
	);
	}
	$this->view->denominations = $denoms;
	} else {
	$this->view->denominations = $denomsList;
	}
	}

}