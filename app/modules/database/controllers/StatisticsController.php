<?php 
/** Controller for displaying overall statistics. 
 * @todo This is very slow due to number of queries. Maybe change to ajax calls?
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class Database_StatisticsController extends Pas_Controller_Action_Admin {
	/** Initialise the ACL and contexts
	*/
	public function init() {
		$this->_helper->_acl->allow('public',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

	private function renderForm(){
	$form = new DatePickerForm();
	$form->datefrom->setValue($this->_getParam('datefrom'));
	$form->dateto->setValue($this->_getParam('dateto'));
	$form->submit->setLabel('Search');
	$form->setMethod('post');
	return $form;
	}
	/** Index page showing calendrical interface to dates of recording
	*/	
	public function indexAction() {
	$date = $this->_getParam('date') ? $this->_getParam('date') : $this->getTimeForForms(); 
	$calendar= new Calendar($date); 
	$cases = new Finds();
	$cases = $cases->getFindsByDay();
	$lists = array();
	foreach ($cases as $value) {
	$lists[] = $value['createdOn'];
	}
	$caseslisted = $lists;
	$calendar->highlighted_dates = $caseslisted;
	$calendar->formatted_link_to = $this->view->baseUrl() 
	. '/database/search/results/created/%Y-%m-%d';
	print '<div id="calendar">';
	for($i=1;$i<=12;$i++){ 
		if( $i == $calendar->month ){ 
			print($calendar->output_calendar(null,null, 'table table-striped')); 
		} else { 
			print($calendar->output_calendar($calendar->year, $i, 'table table-striped')); 
		} 
	} 
	print("</div>"); 
	
	}
	
	/** Page rendering records recorded annually
	*/
	public function annualAction() {
	$datefrom = $this->_getParam('datefrom') ? $this->_getParam('datefrom') 
	: Zend_Date::now()->toString('yyyy').'-01-01'; 
	$dateto = $this->_getParam('dateto') ? $this->_getParam('dateto') 
	: Zend_Date::now()->toString('yyyy-MM-dd'); 
	$finds = new Finds();
	$this->view->annualsum = $finds->getReportTotals($datefrom, $dateto);
	$this->view->officers =  $finds->getOfficerTotals($datefrom, $dateto);
	$this->view->institution =  $finds->getInstitutionTotals($datefrom, $dateto);
	$this->view->periods = $finds->getPeriodTotals($datefrom, $dateto);
	$this->view->finders = $finds->getFindersTotals($datefrom, $dateto);
	$this->view->averages = $finds->getAverageMonth($datefrom, $dateto);
	$this->view->year = $finds->getYearFound($datefrom, $dateto);
	$this->view->discovery = $finds->getDiscoveryMethod($datefrom, $dateto);
	$this->view->landuse = $finds->getLandUse($datefrom, $dateto);
	$this->view->precision = $finds->getPrecision($datefrom, $dateto);
	$this->view->datefrom = $datefrom;
	$this->view->dateto = $dateto;
	$form = $this->renderForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$data =  $this->_request->getPost();
	if ($form->isValid($data)) {
	$params = $data;
	unset($params['submit']);
	unset($params['csrf']);
	$query = '';
	foreach($params as $key => $value) {
	$query .= $key . '/' . $value . '/';
	}
	$this->_redirect('/database/statistics/annual/' . $query);
	} else {
	$form->populate($data);
	}
	}
	}
	/** Page rendering records recorded by county
	*/
	public function countyAction() {
	$datefrom = $this->_getParam('datefrom') ? $this->_getParam('datefrom') 
	: Zend_Date::now()->toString('yyyy').'-01-01'; 
	$dateto = $this->_getParam('dateto') ? $this->_getParam('dateto') 
	: Zend_Date::now()->toString('yyyy-MM-dd'); 
	$county = $this->_getParam('county');
	$this->view->county = $county;
	$this->view->datefrom = $datefrom;
	$this->view->dateto = $dateto;
	$finds = new Finds();
	if(!isset($county)) {
	$this->view->counties = $finds->getCounties($datefrom,$dateto);
	} else {
	$this->view->countyTotal = $finds->getCountyStat($datefrom, $dateto, $county);
	$this->view->creators = $finds->getUsersStat($datefrom, $dateto, $county);
	$this->view->periods = $finds->getPeriodTotalsCounty($datefrom, $dateto, $county);
	$this->view->finders = $finds->getFinderTotalsCounty($datefrom, $dateto, $county);
	$this->view->averages = $finds->getAverageMonthCounty($datefrom,$dateto, $county);
	$this->view->year = $finds->getYearFoundCounty($datefrom, $dateto, $county);
	$this->view->discovery = $finds->getDiscoveryMethodCounty($datefrom, $dateto, $county);
	$this->view->landuse = $finds->getLandUseCounty($datefrom, $dateto, $county);
	$this->view->precision = $finds->getPrecisionCounty($datefrom, $dateto, $county);
	}
	$form = $this->renderForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$data =  $this->_request->getPost();
	if ($form->isValid($data)) {
	$params = $data;
	unset($params['submit']);
	unset($params['csrf']);
	$query = '';
	foreach($params as $key => $value) {
	$query .= $key.'/'.$value.'/';
	}
	$this->_redirect('/database/statistics/county/'.$query);
	} else {
	$form->populate($data);
	}
	}
	}
	
	/** Page rendering records recorded by region
	*/
	public function regionalAction() {
	$datefrom = $this->_getParam('datefrom') ? $this->_getParam('datefrom') 
	: Zend_Date::now()->toString('yyyy').'-01-01'; 
	$dateto = $this->_getParam('dateto') ? $this->_getParam('dateto') 
	: Zend_Date::now()->toString('yyyy-MM-dd'); 
	$region = $this->_getParam('region');
	$this->view->region = $region;
	$this->view->datefrom = $datefrom;
	$this->view->dateto = $dateto;
	$finds = new Finds();
	if(!isset($region)) {
	$this->view->regions = $finds->getRegions($datefrom,$dateto);
	} else {
	$this->view->regionTotal = $finds->getRegionStat($datefrom, $dateto, $region);
	$this->view->creators    = $finds->getUsersRegionStat($datefrom, $dateto, $region);
	$this->view->periods     = $finds->getPeriodTotalsRegion($datefrom, $dateto, $region);
	$this->view->finders     = $finds->getFinderTotalsRegion($datefrom, $dateto, $region);
	$this->view->averages    = $finds->getAverageMonthRegion($datefrom, $dateto, $region);
	$this->view->year        = $finds->getYearFoundRegion($datefrom, $dateto, $region);
	$this->view->discovery   = $finds->getDiscoveryMethodRegion($datefrom, $dateto, $region);
	$this->view->landuse     = $finds->getLandUseRegion($datefrom, $dateto, $region);
	$this->view->precision   = $finds->getPrecisionRegion($datefrom, $dateto, $region);
	}
	$form = $this->renderForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$data = $this->_request->getPost();
	if ($form->isValid($data)) {
	$params = $data;
	unset($params['submit']);
	unset($params['csrf']);
	$query = '';
	foreach($params as $key => $value) {
	$query .= $key.'/'.$value.'/';
	}
	$this->_redirect('/database/statistics/regional/'.$query);

	} else {
	$form->populate($data);
	}
	}
	}
	/** Page rendering records recorded by institution
	*/
	public function institutionAction() {
	$datefrom = $this->_getParam('datefrom') ? $this->_getParam('datefrom') 
	: Zend_Date::now()->toString('yyyy').'-01-01'; 
	$dateto = $this->_getParam('dateto') ? $this->_getParam('dateto') 
	: Zend_Date::now()->toString('yyyy-MM-dd'); 
	$institution = $this->_getParam('institution');
	$this->view->institution = $institution;
	$finds = new Finds();
	if(!isset($institution)) {
	$this->view->institutions = $finds->getInstitutions($datefrom, $dateto);
	} else {
	$this->view->instTotal = $finds->getInstStat($datefrom, $dateto, $institution);
	$this->view->creators = $finds->getUsersInstStat($datefrom, $dateto, $institution);
	$this->view->periods = $finds->getPeriodTotalsInst($datefrom, $dateto, $institution);
	$this->view->finders = $finds->getFinderTotalsInst($datefrom, $dateto, $institution);
	$this->view->averages = $finds->getAverageMonthInst($datefrom, $dateto, $institution);
	$this->view->year = $finds->getYearFoundInst($datefrom, $dateto, $institution);
	$this->view->discovery = $finds->getDiscoveryMethodInst($datefrom, $dateto, $institution);
	$this->view->landuse = $finds->getLandUseInst($datefrom, $dateto, $institution);
	Zend_Debug::dump($this->view->precision = $finds->getPrecisionInst($datefrom, $dateto, $institution));
	}
	$this->view->datefrom = $datefrom;
	$this->view->dateto = $dateto;
	$form = $this->renderForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$data = $this->_request->getPost();
	if ($form->isValid($data)) {
	$params = $data;
	unset($params['submit']);
	unset($params['csrf']);
	$query = '';
	foreach($params as $key => $value)
	{
	$query .= $key . '/' . $value . '/';
	}
	$this->_redirect('/database/statistics/institution/' . $query);
	} else {
	$form->populate($data);
	}
	}
	}
}