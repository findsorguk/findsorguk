<?php

/** An action helper for adding correct options to a form
 *
 * An example of use:
 * <code>
 * <?php
 * $formLoader = $this->_helper->coinFormLoaderOptions($broadperiod,  $coin);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller_Action
 * @subpackage Helper
 * @version 1
 * @example /app/modules/database/controllers/CoinsController.php
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Zend_Filter_StringToUpper
 * @uses Zend_View
 * @uses Geography
 * @uses RevTypes
 * @uses Moneyers
 * @uses MedievalTypes
 */
class Pas_Controller_Action_Helper_CoinFormLoaderOptions extends Zend_Controller_Action_Helper_Abstract
{

    /** The view object
     * @access protected
     * @var \Zend_View
     */
    protected $_view;
    /** The filter class
     * @access protected
     * @var \Zend_Filter_StringToUpper
     */
    protected $_filter;
    /** The array of coin periods
     * @access protected
     * @var array
     */
    protected $_periods = array(
        'ROMAN', 'IRON AGE', 'EARLY MEDIEVAL',
        'POST MEDIEVAL', 'MEDIEVAL', 'BYZANTINE',
        'GREEK AND ROMAN PROVINCIAL'
    );

    /** The constructor function
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->_filter = new Zend_Filter_StringToUpper();
    }

    /** The predispatch call
     * @access public
     * @return void
     */
    public function preDispatch()
    {
        $this->_view = $this->_actionController->view;
    }

    /** The direct action
     * @access public
     * @param string $broadperiod
     * @param array $coinDataFlat
     * @return void
     */
    public function direct($broadperiod, array $coinDataFlat)
    {
        $broadperiod = $this->_filter->filter($broadperiod);
        return $this->optionsAddClone($broadperiod, $coinDataFlat);
    }

    /** Make database value for RIC/RRC id into a more human-readable
     * value that matches the value expected in the dropdown
     * when nomisma is online.
     * @param string $coinId
     * @return string
     * Example
     * ric.1(2).gai.35 -> RIC/1(2)/GAI/35
     */
    private function convertToHumanReadableCoinId(string $coinId): string
    {
        return strtoupper(
            str_replace('-', ' ', str_replace('.', '/', $coinId))
        );
    }

    /** Add array of key/pair values to dropdown, or
     * use ID from database as value and call convertToHumanReadableCoinId()
     * for the name
     * @param Zend_Form_Element_Select $formElement
     * @param array $dropdownValues
     * @param string $type
     * @param string $coinId
     */
    private function addDropdown(
        Zend_Form_Element_Select $formElement,
        array $dropdownValues,
        string $type,
        string $coinId
    ) {
        if ($dropdownValues || $coinId) {
            $formElement->addMultiOptions(
                array(
                    null => 'Choose ' . $type . ' type',
                    'Available ' . $type . ' types' =>
                        $dropdownValues ?: array($coinId => $this->convertToHumanReadableCoinId($coinId))
                )
            );
        }
    }

    /** Add and clone last record
     * fill options when coin has data
     * @access public
     * @param string $broadperiod
     * @param array $coinDataFlat
     * @return void
     */
    public function optionsAddClone($broadperiod, array $coinDataFlat)
    {
        $coinDataFlat = $coinDataFlat[0];
        switch ($broadperiod) {
            case 'IRON AGE':
                if (array_key_exists('denomination', $coinDataFlat)) {
                    $geographies = new Geography();
                    $geography_options = $geographies->getIronAgeGeographyMenu($coinDataFlat['denomination']);
                    $this->_view->form->geographyID->addMultiOptions(array(
                        null => 'Choose geographic region',
                        'Available regions' => $geography_options
                    ));
                    $this->_view->form->geographyID->addValidator('InArray',
                        false, array(array_keys($geography_options)));
                }
                break;
            case 'ROMAN':
                if (array_key_exists('ruler_id', $coinDataFlat)) {
                    //Populate RIC dropdown
                    if (!is_null($coinDataFlat['ruler_id'])) {
                        $rulers = new Rulers();
                        $identifier = $rulers->fetchRow($rulers->select()->where('id = ?', $coinDataFlat['ruler_id']));
                        if ($identifier) {
                            $this->addDropdown(
                                $this->_view->form->ricID,
                                (new Nomisma())->getRICDropdownsFlat($identifier->nomismaID),
                                'ric',
                                $coinDataFlat['ricID']
                            );
                        }
                    }

                    $reverses = new RevTypes();
                    $reverse_options = $reverses->getRevTypesForm($coinDataFlat['ruler_id']);
                    if ($reverse_options) {
                        $this->_view->form->revtypeID->addMultiOptions(array(
                            null => 'Choose reverse type',
                            'Available reverses' => $reverse_options
                        ));
                        $this->_view->form->revtypeID->setRegisterInArrayValidator(false);
                    } else {
                        $this->_view->form->revtypeID->addMultiOptions(array(
                            null => 'No options available'
                        ));
                        $this->_view->form->revtypeID->setRegisterInArrayValidator(false);
                    }
                } else {
                    $this->_view->form->revtypeID->addMultiOptions(array(
                        null => 'No options available'));
                    $this->_view->form->revtypeID->setRegisterInArrayValidator(false);
                }
                if (array_key_exists('ruler_id', $coinDataFlat) && ($coinDataFlat['ruler_id'] == '242')) {
                    $moneyers = new Moneyers();
                    $moneyer_options = $moneyers->getRepublicMoneyers();
                    $this->_view->form->moneyer->addMultiOptions(array(
                        null => 'Choose moneyer',
                        'Available moneyers' => $moneyer_options
                    ));
                    //Populate RRC dropdown
                    if (array_key_exists('moneyer', $coinDataFlat)) {
                        if (!is_null($coinDataFlat['moneyer'])) {
                            $identifier = $moneyers->fetchRow($moneyers->select()->where('id = ?', $coinDataFlat['moneyer']));
                            if ($identifier) {
                                $this->addDropdown(
                                    $this->_view->form->rrcID,
                                    (new Nomisma())->getRRCDropdownsFlat($identifier->nomismaID),
                                    'rrc',
                                    $coinDataFlat['rrcID']
                                );
                            }
                        }
                    }
                } else {
                    $this->_view->form->moneyer->addMultiOptions(array(
                        null => 'No options available'
                    ));
                }
                break;
            case 'EARLY MEDIEVAL':
                if (array_key_exists('ruler_id', $coinDataFlat)) {
                    $types = new MedievalTypes();
                    $type_options = $types->getMedievalTypeToRulerMenu($coinDataFlat['ruler_id']);
                    $this->_view->form->typeID->addMultiOptions(array(
                        null => 'Choose Early Medieval type',
                        'Available types' => $type_options));
                }
                break;
            case 'MEDIEVAL':
                if (array_key_exists('ruler_id', $coinDataFlat)) {
                    $types = new MedievalTypes();
                    $type_options = $types->getMedievalTypeToRulerMenu($coinDataFlat['ruler_id']);
                    $this->_view->form->typeID->addMultiOptions(array(
                        null => 'Choose Medieval type',
                        'Available types' => $type_options
                    ));
                }
                break;
            case 'POST MEDIEVAL':
                if (array_key_exists('ruler_id', $coinDataFlat)) {
                    $types = new MedievalTypes();
                    $type_options = $types->getMedievalTypeToRulerMenu((int)$coinDataFlat['ruler_id']);
                    $this->_view->form->typeID->addMultiOptions(array(
                        null => 'Choose Post Medieval type',
                        'Available types' => $type_options
                    ));
                }
                break;
            default:
                return false;
        }
    }
}