<?php

/** Controller for displaying overall statistics.
 * @todo This is very slow due to number of queries. Maybe change to ajax calls?
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses DatePickerForm
 * @uses Finds
 * @uses Calendar
 * @uses Pas_ArrayFunctions
 */
class Database_StatisticsController extends Pas_Controller_Action_Admin
{

    /** The finds model
     * @access protected
     * @var \Finds
     */
    protected $_finds;

    /** The array function class
     * @access protected
     * @var \Pas_ArrayFunctions
     */
    protected $_cleaner;

    protected string $regexPattern = "/^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/";

    /** Get the array class
     * @access public
     * @return \Pas_ArrayFunctions
     */
    public function getCleaner()
    {
        $this->_cleaner = new Pas_ArrayFunctions();
        return $this->_cleaner;
    }


    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('public', null);

        $this->_finds = new Finds();
    }

    /** Render a form
     * @access public
     * @return \DatePickerForm
     */
    public function renderForm()
    {
        $form = new DatePickerForm();
        $form->datefrom->setValue($this->getParam('datefrom'));
        $form->dateto->setValue($this->getParam('dateto'));
        $form->submit->setLabel('Search');
        $form->setMethod('post');
        return $form;
    }

    /** Index page showing calendrical interface to dates of recording
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $date = $this->getParam('date') ? $this->getParam('date') : $this->getTimeForForms();
        $calendar = new Calendar($date);
        $cases = $this->_finds->getFindsByDay();
        $lists = array();
        foreach ($cases as $value) {
            $lists[] = $value['createdOn'];
        }
        $calendar->highlighted_dates = $lists;
        $calendar->formatted_link_to = $this->view->baseUrl()
            . '/database/search/results/created/%Y-%m-%d';
        print '<div id="calendar">';

        for ($i = 1; $i <= 12; $i++) {
            if ($i == $calendar->month) {
                print($calendar->output_calendar(null, null, 'table table-striped'));
            } else {
                print($calendar->output_calendar($calendar->year, $i, 'table table-striped'));
            }
        }
        print("</div>");
    }

    /** Page rendering records recorded annually
     * @access public
     * @return void
     */
    public function annualAction()
    {
        $datefrom = $this->getParam('datefrom') ? $this->getParam('datefrom')
            : Zend_Date::now()->toString('yyyy') . '-01-01';
        $dateto = $this->getParam('dateto') ? $this->getParam('dateto')
            : Zend_Date::now()->toString('yyyy-MM-dd');

        //Validate date
        $this->validateDates($datefrom, $dateto, 'annual');

        $form = $this->renderForm();
        $this->view->form = $form;
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost()))
        {
            $params = $this->getCleaner()->array_cleanup($this->_request->getPost());
            $query = '';
            foreach ($params as $key => $value) {
                $query .= $key . '/' . $value . '/';
            }
            $this->redirect('/database/statistics/annual/' . $query);
        }
        else {
            $this->view->annualsum = $this->_finds->getReportTotals($datefrom, $dateto);
            $this->view->officers = $this->_finds->getOfficerTotals($datefrom, $dateto);
            $this->view->institution = $this->_finds->getInstitutionTotals($datefrom, $dateto);
            $this->view->periods = $this->_finds->getPeriodTotals($datefrom, $dateto);
            $this->view->finders = $this->_finds->getFindersTotals($datefrom, $dateto);
            $this->view->averages = $this->_finds->getAverageMonth($datefrom, $dateto);
            $this->view->year = $this->_finds->getYearFound($datefrom, $dateto);
            $this->view->discovery = $this->_finds->getDiscoveryMethod($datefrom, $dateto);
            $this->view->landuse = $this->_finds->getLandUse($datefrom, $dateto);
            $this->view->precision = $this->_finds->getPrecision($datefrom, $dateto);
            $this->view->datefrom = $datefrom;
            $this->view->dateto = $dateto;
        }
    }

    /** Page rendering records recorded by county
     */
    public function countyAction()
    {
        $datefrom = $this->getParam('datefrom') ? $this->getParam('datefrom')
            : Zend_Date::now()->toString('yyyy') . '-01-01';
        $dateto = $this->getParam('dateto') ? $this->getParam('dateto')
            : Zend_Date::now()->toString('yyyy-MM-dd');
        $county = $this->getParam('county');

        //Validate date
        $this->validateDates($datefrom, $dateto, 'county');

        $form = $this->renderForm();
        $this->view->form = $form;

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost()))
        {
            $params = $this->getCleaner()->array_cleanup($this->_request->getPost());
            $query = '';
            foreach ($params as $key => $value) {
                $query .= $key . '/' . $value . '/';
            }
            $this->redirect('/database/statistics/county/' . $query);
        } else {
            $this->view->county = $county;
            $this->view->datefrom = $datefrom;
            $this->view->dateto = $dateto;
            if (!isset($county)) {
                $this->view->counties = $this->_finds->getCounties($datefrom, $dateto);
            } else {
                $this->view->countyTotal = $this->_finds->getCountyStat($datefrom, $dateto, $county);
                $this->view->creators = $this->_finds->getUsersStat($datefrom, $dateto, $county);
                $this->view->periods = $this->_finds->getPeriodTotalsCounty($datefrom, $dateto, $county);
                $this->view->finders = $this->_finds->getFinderTotalsCounty($datefrom, $dateto, $county);
                $this->view->averages = $this->_finds->getAverageMonthCounty($datefrom, $dateto, $county);
                $this->view->year = $this->_finds->getYearFoundCounty($datefrom, $dateto, $county);
                $this->view->discovery = $this->_finds->getDiscoveryMethodCounty($datefrom, $dateto, $county);
                $this->view->landuse = $this->_finds->getLandUseCounty($datefrom, $dateto, $county);
                $this->view->precision = $this->_finds->getPrecisionCounty($datefrom, $dateto, $county);
            }
        }
    }

    /** Page rendering records recorded by region
     * @access public
     * @return void
     */
    public function regionalAction()
    {
        $datefrom = $this->getParam('datefrom') ? $this->getParam('datefrom')
            : Zend_Date::now()->toString('yyyy') . '-01-01';
        $dateto = $this->getParam('dateto') ? $this->getParam('dateto')
            : Zend_Date::now()->toString('yyyy-MM-dd');
        $region = $this->getParam('region');

        //Validate date
        $this->validateDates($datefrom, $dateto, 'regional');

        $form = $this->renderForm();
        $this->view->form = $form;

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost()))
        {
            $params = $this->getCleaner()->array_cleanup($this->_request->getPost());
            $query = '';
            foreach ($params as $key => $value) {
                $query .= $key . '/' . $value . '/';
            }
            $this->redirect('/database/statistics/regional/' . $query);
        } else {
            $this->view->region = $region;
            $this->view->datefrom = $datefrom;
            $this->view->dateto = $dateto;
            if (!isset($region)) {
                $this->view->regions = $this->_finds->getRegions($datefrom, $dateto);
            } else {
                $this->view->regionTotal = $this->_finds->getRegionStat($datefrom, $dateto, $region);
                $this->view->creators = $this->_finds->getUsersRegionStat($datefrom, $dateto, $region);
                $this->view->periods = $this->_finds->getPeriodTotalsRegion($datefrom, $dateto, $region);
                $this->view->finders = $this->_finds->getFinderTotalsRegion($datefrom, $dateto, $region);
                $this->view->averages = $this->_finds->getAverageMonthRegion($datefrom, $dateto, $region);
                $this->view->year = $this->_finds->getYearFoundRegion($datefrom, $dateto, $region);
                $this->view->discovery = $this->_finds->getDiscoveryMethodRegion($datefrom, $dateto, $region);
                $this->view->landuse = $this->_finds->getLandUseRegion($datefrom, $dateto, $region);
                $this->view->precision = $this->_finds->getPrecisionRegion($datefrom, $dateto, $region);
            }
        }
    }

    /** Page rendering records recorded by institution
     * @access public
     * @return void
     */
    public function institutionAction()
    {
        $datefrom = $this->getParam('datefrom') ? $this->getParam('datefrom')
            : Zend_Date::now()->toString('yyyy') . '-01-01';
        $dateto = $this->getParam('dateto') ? $this->getParam('dateto')
            : Zend_Date::now()->toString('yyyy-MM-dd');
        $institution = $this->getParam('institution');
        $this->view->institution = $institution;

        //Validate date
        $this->validateDates($datefrom, $dateto, 'institution');

        $form = $this->renderForm();
        $this->view->form = $form;

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost()))
        {
            $params = $this->getCleaner()->array_cleanup($this->_request->getPost());

            $query = '';
            foreach ($params as $key => $value) {
                $query .= $key . '/' . $value . '/';
            }
            $this->redirect('/database/statistics/institution/' . $query);
        } else {
            if (!isset($institution)) {
                $this->view->institutions = $this->_finds->getInstitutions($datefrom, $dateto);
            } else {
                $this->view->instTotal = $this->_finds->getInstStat($datefrom, $dateto, $institution);
                $this->view->creators = $this->_finds->getUsersInstStat($datefrom, $dateto, $institution);
                $this->view->periods = $this->_finds->getPeriodTotalsInst($datefrom, $dateto, $institution);
                $this->view->finders = $this->_finds->getFinderTotalsInst($datefrom, $dateto, $institution);
                $this->view->averages = $this->_finds->getAverageMonthInst($datefrom, $dateto, $institution);
                $this->view->year = $this->_finds->getYearFoundInst($datefrom, $dateto, $institution);
                $this->view->discovery = $this->_finds->getDiscoveryMethodInst($datefrom, $dateto, $institution);
                $this->view->landuse = $this->_finds->getLandUseInst($datefrom, $dateto, $institution);
                $this->view->precision = $this->_finds->getPrecisionInst($datefrom, $dateto, $institution);
            }
            $this->view->datefrom = $datefrom;
            $this->view->dateto = $dateto;
        }
    }

    /** Validate date matches regex, else redirect
     *
     * @param string $datefrom
     * @param string $dateto
     * @param string $action
     *
     * @return void
     */
    private function validateDates(string $datefrom, string $dateto, string $action)
    {
        if (!preg_match($this->regexPattern, $datefrom) || !preg_match($this->regexPattern, $dateto)) {
            $this->getFlash()->addMessage(
                "Date must be in the format YYYY-MM-DD.
                Please try again."
            );
            $this->redirect("/database/statistics/$action/");
        }
    }
}
