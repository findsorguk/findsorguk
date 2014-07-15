<?php
/** An adapted instance of a form for loading in the findspot controller.
 * An example of code use:
 * 
 * <code>
 * <?php
 * $fill = new Pas_Form_Findspot();
 * $fill->populate($findspot->toArray());
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @license http://URL name
 * @category Pas
 * @package Pas_Form
 * @example /app/modules/database/controllers/FindspotsController.php
 */

class Pas_Form_Findspot {

    /** The view object
     * @access protected
     * @var \Zend_View
     */
    protected $_view;

    /** The constructor
     * 
     */
    public function __construct() {
    	$this->_view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
    }

    /** Populate the form with the array of data
     * @access public
     * @param array $data
     */
    public function populate( array $data){
	$this->_view->form->populate($data);
        if(array_key_exists('countyID', $data)) {
    
            $districts = new OsDistricts();
            $district = $districts->getDistrictsToCountyList($data['countyID']);
            if($district) {
                $this->_view->form->districtID->addMultiOptions(
                        array(
                            null => 'Choose a district',
                            'Available districts' => $district
                        ));
            }

            if(array_key_exists('districtID', $data)) {
                $parishModel = new OsParishes();
                $parishes = $parishModel->getParishesToDistrictList($data['districtID']);
                $this->_view->form->parishID->addMultiOptions(
                        array(
                            null => 'Choose a parish',
                            'Available parishes' => $parishes
                        ));
            }

            if(array_key_exists('countyID', $data)) {
                $countyModel = new OsCounties();
                $regions = $countyModel->getCountyToRegionList($data['countyID']);
                $this->_view->form->regionID->addMultiOptions(
                        array(
                            null => 'Choose a region',
                            'Available regions' => $regions
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
            $finders = $finders->getName($data['landowner']);
            foreach($finders as $finder) {
                $this->_view->form->landownername->setValue($finder['term']);
            }
        }
    }

}


