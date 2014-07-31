<?php
/** Controller for all our staff profiles pages
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Contacts
 * @uses Finds
 * @uses OnlineAccounts
 * @uses Pas_Service_Geo_PostCodeToGeo
 * @uses Pas_Exception_Param
 * 
*/
class Contacts_StaffController extends Pas_Controller_Action_Admin {

    /** The finds model
     * @access protected
     * @var \Finds
     */
    protected $_finds;
    
    /** The content model
     * @access protected
     * @var \Content
     */
    protected $_content;
    
    /** Get the finds model
     * @access public
     * @return \Finds
     */
    public function getFinds() {
        $this->_finds = new Finds();
        return $this->_finds;
    }

    /** Init the controller
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('public',null);
        
        $contexts = array('xml','json','foaf','vcf');
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addContext('foaf',array('suffix' => 'foaf'))
                ->addContext('vcf',array('suffix' => 'vcf'))
                ->addActionContext('profile',$contexts)
                ->initContext();
    }

    /** Redirect away from this page, no root access
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->_redirect('contacts');
    }

    /** Profile page
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function profileAction() {
        if($this->_getParam('id',false)) {
            $id = $this->_getParam('id');
            $staffs = new Contacts();
            $this->view->persons = $staffs->getPersonDetails($id);
            $this->view->findstotals = $this->getFinds()->getFindsFloQuarter($id);
            $this->view->periodtotals = $this->getFinds()->getFindsFloPeriod($id);
            $accts = new OnlineAccounts();
            $this->view->accts = $accts->getAccounts($id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Map of staff
     * @access public
     * @return void
     */
    public function mapAction() {
        //Magic in view
    }

    /** Find nearest staff member from solr
     * @access public
     * @return void
     * @todo finish function
     */
    public function findnearestAction() {
        $postcode = new Pas_Service_Geo_PostCodeToGeo();
        $geo = $postcode->getData('WC1B 3DG');
        $config = $this->_helper->config()->solr->toArray();
        $config['core'] = 'objects';
        $client = new Solarium_Client(array('adapteroptions' => $config ));
        // get a select query instance and a query helper instance
        $select = array(
                'query' => '*:*',
                'fields'=> array('*'),
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