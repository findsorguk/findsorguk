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

    protected static string $minDate = "1998-01-01";

    protected static string $regexPattern = "/^\d\d\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/";

    protected array $allowedUserRoles = array('hero','flos','treasure','fa','hoard','research');

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
        $permissionConfig = Zend_Registry::get('config')->controller->permissions->statistics->allowed_users;
        if (!empty($permissionConfig)) {
            $this->allowedUserRoles = $permissionConfig->toArray();
        }

        $statisticsActions = array('index','annual', 'county', 'regional', 'institution');
        $this->_helper->_acl->allow($this->allowedUserRoles, $statisticsActions);
        $this->_helper->_acl->allow('admin');

        //Allow all users to view notauthorised page
        $this->_helper->_acl->allow(null, 'notauthorised');

        $this->_helper->_acl->setErrorModule('database')->setErrorController('statistics')->setErrorAction('notauthorised');

        $this->_finds = new Finds();
    }

    public function notauthorisedAction()
    {
        $this->view->message =
            "<p>Sorry, the statistics page is only available for authorised members of the scheme.</p>" .
            "<p>We apologise for any inconvenience, however, overuse of this facility has caused a degradation for " .
            "normal users, so we have decided to restrict it.</p>";

        $this->view->role = $this->getRole();
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
        $datefrom = $this->getParam('datefrom') ?? date('Y-') . "01-01";
        $dateto = $this->getParam('dateto') ?? date('Y-m-d');

        //Validate date
        $validDate[] = $this->dateIsValid($datefrom, "date from");
        $validDate[] = $this->dateIsValid($dateto, "Date to");

        if (!in_array(false, $validDate)) {
            if ($this->getRole() != "public") {
                $form = $this->renderForm();
                $this->view->form = $form;
            } else {
                //Default time to a single year range to ensure cache results are hit.
                $baseYear = date('Y', strtotime($datefrom));
                $datefrom = $baseYear . "-01-01";
                $dateto = $baseYear == date('Y') ? date('Y-m-d') : $baseYear . "-12-31";
            }

            if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
                $params = $this->getCleaner()->array_cleanup($this->_request->getPost());
                $query = '';
                foreach ($params as $key => $value) {
                    $query .= $key . '/' . $value . '/';
                }
                $this->redirect('/database/statistics/annual/' . $query);
            } else {
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
        } else {
            $this->redirect($this->redirect('/database/statistics/annual/'));
        }
    }

    /** Page rendering records recorded by county
     */
    public function countyAction()
    {
        $datefrom = $this->getParam('datefrom') ?? date('Y-') . "01-01";
        $dateto = $this->getParam('dateto') ?? date('Y-m-d');
        $county = $this->getParam('county');

        //Validate date
        $validDate[] = $this->dateIsValid($datefrom, "date from");
        $validDate[] = $this->dateIsValid($dateto, "Date to");

        if (!in_array(false, $validDate)) {
            if ($this->getRole() != "public") {
                $form = $this->renderForm();
                $this->view->form = $form;
            } else {
                //Default time to a single year range to ensure cache results are hit.
                $baseYear = date('Y', strtotime($datefrom));
                $datefrom = $baseYear . "-01-01";
                $dateto = $baseYear == date('Y') ? date('Y-m-d') : $baseYear . "-12-31";
            }

            if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
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
        } else {
            $this->redirect('/database/statistics/county/');
        }
    }

    /** Page rendering records recorded by region
     * @access public
     * @return void
     */
    public function regionalAction()
    {
        $datefrom = $this->getParam('datefrom') ?? date('Y-') . "01-01";
        $dateto = $this->getParam('dateto') ?? date('Y-m-d');
        $region = $this->getParam('region');

        //Validate date
        $validDate[] = $this->dateIsValid($datefrom, "date from");
        $validDate[] = $this->dateIsValid($dateto, "Date to");

        if (!in_array(false, $validDate)) {
            if ($this->getRole() != "public") {
                $form = $this->renderForm();
                $this->view->form = $form;
            } else {
                //Default time to a single year range to ensure cache results are hit.
                $baseYear = date('Y', strtotime($datefrom));
                $datefrom = $baseYear . "-01-01";
                $dateto = $baseYear == date('Y') ? date('Y-m-d') : $baseYear . "-12-31";
            }

            if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
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
        } else {
            $this->redirect('/database/statistics/regional/');
        }
    }

    /** Page rendering records recorded by institution
     * @access public
     * @return void
     */
    public function institutionAction()
    {
        $datefrom = $this->getParam('datefrom') ?? date('Y-') . "01-01";
        $dateto = $this->getParam('dateto') ?? date('Y-m-d');
        $institution = $this->getParam('institution');
        $this->view->institution = $institution;

        //Validate date
        $validDate[] = $this->dateIsValid($datefrom, "date from");
        $validDate[] = $this->dateIsValid($dateto, "Date to");

        if (!in_array(false, $validDate)) {
            if ($this->getRole() != "public") {
                $form = $this->renderForm();
                $this->view->form = $form;
            } else {
                //Default time to a single year range to ensure cache results are hit.
                $baseYear = date('Y', strtotime($datefrom));
                $datefrom = $baseYear . "-01-01";
                $dateto = $baseYear == date('Y') ? date('Y-m-d') : $baseYear . "-12-31";
            }

            if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
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
        } else {
            $this->redirect('/database/statistics/institution/');
        }
    }

    /** Validate date
     *
     * @param string $datefrom
     * @param string $dateto
     * @return bool
     */
    private function dateIsValid($date, $dateName = "Date"): bool
    {
        //Check format of text matches YYYY-MM-DD
        if (!preg_match(self::$regexPattern, $date)) {
            $this->getFlash()->addMessage($dateName . " must be in the format YYYY-MM-DD. Please try again.");
            return false;
        }

        if (!$this->dateInValidRange($date, $dateName)) {
            return false;
        }

        return true;
    }

    private function dateInValidRange($date, $dateName = "Date"): bool
    {
        $minDate = strtotime(self::$minDate);
        //Set max date to today
        $maxDate = strtotime(date('Y-m-d'));

        if (strtotime($date) < $minDate || strtotime($date) > $maxDate) {
            $this->getFlash()->addMessage(
                ucfirst($dateName) . " must be between " . self::$minDate
                .  " and " . date('Y-m-d', $maxDate) . "."
            );
            return false;
        }
        return true;
    }
}
