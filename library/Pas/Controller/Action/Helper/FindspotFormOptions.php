<?php
/** An action helper for loading findspot form options
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * if($this->_getParam('copy') === 'last') {
 *      $this->_helper->findspotFormOptions();
 * }
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @license http://URL name
 * @category Pas
 * @package Controller_Action
 * @subpackage Helper
 * @version 1
 * @example /app/modules/database/controllers/FindspotsController.php
 */
class Pas_Controller_Action_Helper_FindspotFormOptions extends Zend_Controller_Action_Helper_Abstract {

    /** The view object
     * @access protected
     * @var \Zend_View
     */
    protected $_view;

    /** Pre dispatch and set the view
     * 
     */
    public function preDispatch() {
	$this->_view = $this->_actionController->view;
    }

    /** Clone options via proxy to method
     * @access public
     * @return 
     */
    public function direct(){
        return $this->optionsAddClone();
    }


    /** Get the identity of user
     * @access public
     * @return integer
     * @throws Zend_Exception
     */
    protected function _getIdentity(){
        $user = new Pas_User_Details();
        $person = $user->getPerson();
        if($person){
            return $person->id;
        } else {
            throw new Zend_Exception('No user credentials found', 500);
        }
    }

    /** Clone the options and add to form
     * @access public
     * @return \Pas_Controller_Action_Helper_FindspotFormOptions
     */
    public function optionsAddClone(){
        $findspots = new Findspots();
        $findspot = $findspots->getLastRecord($this->_getIdentity());
        $data = $findspot[0];
        $this->_view->form->populate($data);
        Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')
            ->addMessage('Your last record data has been cloned');
        if(array_key_exists('countyID', $data) && !is_null($data['countyID'])) {
            
            $districts = new OsDistricts();
            $district = $districts->getDistrictsToCountyList($data['countyID']);
            if($district) {
                $this->_view->form->districtID->addMultiOptions(
                        array(
                            null  => 'Choose district',
                            'Available districts' => $district
                        ));
            }
            
            if(array_key_exists('districtID', $data) && !is_null($data['districtID'])) {
                $parishes = new OsParishes();
                $parishes = $parishes->getParishesToDistrictList($data['districtID']);
                $this->_view->form->parishID->addMultiOptions(
                        array(
                            null => 'Choose parish',
                            'Available parishes' => $parishes
                        ));
            }
             
            if(array_key_exists('countyID' , $data) && !is_null($data['countyID'])) {
                $cnts = new OsCounties();
                $region_list = $cnts->getCountyToRegionList($data['countyID']);
                $this->_view->form->regionID->addMultiOptions(
                        array(
                            null => 'Choose region',
                            'Available regions' => $region_list
                        ));
            }
        }
        
        if(array_key_exists('landusevalue', $data)) {
            $landcodes = new Landuses();
            $landusecode_options = $landcodes->getLandusesChildList($data['landusevalue']);
            $this->_view->form->landusecode->addMultiOptions(
                    array(
                        null => 'Choose code',
                        'Available landuses' => $landusecode_options
                    ));
        }
        
        if(array_key_exists('landowner', $data)) {
            $finders = new People();
            $finders = $finders->getName($findspot['landowner']);
            foreach($finders as $finder) {
                $form->landownername->setValue($finder['term']);
            }
        }
        return $this;
    }
}