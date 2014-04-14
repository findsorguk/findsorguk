<?php
/** Controller for all our staff profiles pages
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Contacts_StaffController extends Pas_Controller_Action_Admin
{
	/** Initialise the ACL and contexts
	*/
	public function init() {
		$this->_helper->_acl->allow('public',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$contexts = array('xml','json','foaf','vcf');
	  	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
			 ->addContext('foaf',array('suffix' => 'foaf'))
 			 ->addContext('vcf',array('suffix' => 'vcf'))
			 ->addActionContext('profile',$contexts)
             ->initContext();
    }

    /** Redirect away from this page, no root access
	*/
	public function indexAction() {
		$this->_redirect('contacts');
	}

	/** Profile page
	* @todo sort out the xml generated pages with proper class to generate data
	*/
	public function profileAction()	{
		if($this->_getParam('id',false)) {
			$id = $this->_getParam('id');
			$staffs = new Contacts();
			$this->view->persons = $staffs->getPersonDetails($id);
			$findstotals = new Finds();
			$this->view->findstotals = $findstotals->getFindsFloQuarter($id);
			$periodtotals = new Finds();
			$this->view->periodtotals = $periodtotals->getFindsFloPeriod($id);
			$accts = new OnlineAccounts();
			$this->view->accts = $accts->getAccounts($id);
		} else {
			throw new Pas_Exception_Param($this->_missingParameter);
		}
	}

	/** Map of staff
	*/
	public function mapAction() {
	}

        public function findnearestAction(){
        $postcode = new Pas_Service_Geo_PostCodeToGeo();
        $geo = $postcode->getData('WC1B 3DG');
        $config = $this->_helper->config()->solr->toArray();
        $config['core'] = 'objects';

        $client = new Solarium_Client(array('adapteroptions' => $config ));

        // get a select query instance and a query helper instance

        $select = array(
        'query'         => '*:*',
        'fields'        => array('*'),
        'filterquery' => array(),
        );

        $query = $client->createSelect($select);

        $helper = $query->getHelper();
        // add a filterquery on a price range, using the helper to generate the range
        $query->createFilterQuery('geodist')->setQuery($helper->geodist($geo['lat'], $geo['lon'], 'coordinates'));

        $resultset = $client->select($query);
        Zend_Debug::dump($resultset);

        }

}