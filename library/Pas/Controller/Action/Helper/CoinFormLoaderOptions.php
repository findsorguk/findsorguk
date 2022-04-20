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

                    if (!is_null($coinDataFlat['ruler_id'])) {
                        $rulers = new Rulers();
                        $identifier = $rulers->fetchRow($rulers->select()->where('id = ?', $coinDataFlat['ruler_id']));
                        if ($identifier) {
                            $nomisma = $identifier->nomismaID;
                            $rrcTypes = new Nomisma();
                            $ricDropdowns = $rrcTypes->getRICDropdownsFlat($nomisma);

                            if (!empty($ricDropdowns)) {
                                $this->_view->form->ricID->addMultiOptions(array(
                                    null => 'Choose RIC type',
                                    'Available RIC types' => $ricDropdowns
                                ));
                            } elseif (!empty($coinDataFlat['ricID'])) {
                                $this->_view->form->ricID->addMultiOptions(array(
                                    null => 'Choose RIC type',
                                    'Available RIC types' => array(
                                        $coinDataFlat['ricID'] =>
                                            strtoupper(
                                                str_replace('-', ' ', str_replace('.', '/', $coinDataFlat['ricID']))
                                            )
                                    )
                                ));
                            }
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
                    if (array_key_exists('moneyer', $coinDataFlat)) {
                        if (!is_null($coinDataFlat['moneyer'])) {
                            $identifier = $moneyers->fetchRow($moneyers->select()->where('id = ?', $coinDataFlat['moneyer']));
                            if ($identifier) {
                                $nomisma = $identifier->nomismaID;
                                $rrcTypes = new Nomisma();
                                $rrcDropdowns = $rrcTypes->getRRCDropdownsFlat($nomisma);

                                if (!empty($rrcDropdowns)) {
                                    $this->_view->form->rrcID->addMultiOptions(array(
                                        null => 'Choose RRC type',
                                        'Available RRC types' => $rrcDropdowns
                                    ));
                                } elseif (!empty($coinDataFlat['rrcID'])) {
                                    $this->_view->form->rrcID->addMultiOptions(array(
                                        null => 'Choose RRC type',
                                        'Available RRC types' => array(
                                            $coinDataFlat['rrcID'] =>
                                                strtoupper(
                                                    str_replace('-', ' ', str_replace('.', '/', $coinDataFlat['rrcID']))
                                                )
                                        )
                                    ));
                                }
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