<?php

/** A plugin for loading coin form options
 *
 * <code>
 * <?php
 * $form = $this->_helper->coinFormLoader($broadperiod);
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller_Action
 * @subpackage Helper
 * @version 1
 * @example /app/modules/database/controllers/CoinsController.php
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 *
 */
class Pas_Controller_Action_Helper_CoinFormLoader extends Zend_Controller_Action_Helper_Abstract
{

    /** The Zend View object
     * @access protected
     * @var \Zend_View
     */
    protected $_view;

    /** Set the view up before dispatch
     * @access public
     */
    public function preDispatch()
    {
        $this->_view = $this->_actionController->view;
        return $this->_view;
    }

    /** Set up the correct options to load
     * @access public
     * @param string $broadperiod
     * @return void
     */
    public function direct($broadperiod)
    {
        $broadperiod = $this->_filter->filter($broadperiod);
        return $this->loadForm($broadperiod);
    }

    /** Set up the filter
     * @access protected
     * @var \Zend_Filter_StringToUpper
     */
    protected $_filter;

    /** Constructor function
     * @access public
     *
     */
    public function __construct()
    {
        $this->_filter = new Zend_Filter_StringToUpper();
    }

    /** The array of periods
     * @access protected
     * @var array
     */
    protected $_periods = array(
        'ROMAN', 'IRON AGE', 'EARLY MEDIEVAL',
        'POST MEDIEVAL', 'MEDIEVAL', 'BYZANTINE',
        'GREEK AND ROMAN PROVINCIAL'
    );

    /** Load the form options based on the broadperiod provided
     * @access public
     * @param string $broadperiod
     * @return \GreekAndRomanCoinForm
     * @throws Exception
     */
    public function loadForm($broadperiod)
    {
        switch ($broadperiod) {
            case 'ROMAN':
                $form = new RomanCoinForm();
                $form->details->setLegend('Add Roman numismatic data');
                $form->submit->setLabel('Add Roman data');
                $this->_view->headTitle('Add a Roman coin\'s details');
                $this->_view->jQuery()->addJavascriptFile($this->_view->baseUrl()
                    . '/js/JQuery/coinslinkedinit.js', $type = 'text/javascript');
                break;
            case 'IRON AGE':
                $form = new IronAgeCoinForm();
                $form->details->setLegend('Add Iron Age numismatic data');
                $form->submit->setLabel('Add Iron Age data');
                $this->_view->headTitle('Add an Iron Age coin\'s details');
                $this->_view->jQuery()->addJavascriptFile($this->_view->baseUrl()
                    . '/js/JQuery/iacoinslinkedinit.js', $type = 'text/javascript');
                break;
            case 'EARLY MEDIEVAL':
                $form = new EarlyMedievalCoinForm();
                $form->details->setLegend('Add Early Medieval numismatic data');
                $form->submit->setLabel('Add Early Medieval data');
                $this->_view->headTitle('Add an Early Medieval coin\'s details');
                $this->_view->jQuery()->addJavascriptFile($this->_view->baseUrl()
                    . '/js/JQuery/coinslinkedinitearlymededit.js', $type = 'text/javascript');
                break;
            case 'MEDIEVAL':
                $form = new MedievalCoinForm();
                $form->details->setLegend('Add Medieval numismatic data');
                $form->submit->setLabel('Add Medieval data');
                $this->_view->headTitle('Add a Medieval coin\'s details');
                $this->_view->jQuery()->addJavascriptFile($this->_view->baseUrl()
                    . '/js/JQuery/coinslinkedinitmededit.js', $type = 'text/javascript');
                break;
            case 'POST MEDIEVAL':
                $form = new PostMedievalCoinForm();
                $form->details->setLegend('Add Post Medieval numismatic data');
                $form->submit->setLabel('Add Post Medieval data');
                $this->_view->headTitle('Add a Post Medieval coin\'s details');
                $this->_view->jQuery()->addJavascriptFile($this->_view->baseUrl()
                    . '/js/JQuery/coinslinkedinitpostmededit.js', $type = 'text/javascript');
                break;
            case 'BYZANTINE':
                $form = new ByzantineCoinForm();
                $form->details->setLegend('Add Byzantine numismatic data');
                $form->submit->setLabel('Add Byzantine data');
                break;
            case 'GREEK AND ROMAN PROVINCIAL':
                $form = new GreekAndRomanCoinForm();
                $form->details->setLegend('Add Greek & Roman numismatic data');
                $form->submit->setLabel('Add Greek & Roman data');
                break;
            default:
                throw new Exception('You cannot have a coin for that period.');
        }
        $this->initNomsimaDropdowns($form);
        return $form;
    }

    private function setFormDisabled($element, $errorMessage){
        $element->setDescription($errorMessage)
            ->setAttrib('tabindex', '-1')
            ->setAttrib('style', 'pointer-events:none; background: #eee;')
            ->addDecorators(array(
                array(
                    'Description',
                    array(
                        'tag' => 'p',
                        'style' => 'color:#e95420; font-weight: bold; margin-top:5px;'
                    )
                ),
            ));
    }

    private function initNomsimaDropdowns($form)
    {
        $rrcTypes = new Nomisma();
        $errorMessageNomisma = 'Nomisma - the third party data source - is ' .
            'currently unavailable. Please try again later';

        if ($rrcTypes->getStatusNomisma() == false) {
            $this->setFormDisabled($form->rrcID, $errorMessageNomisma);
            $this->setFormDisabled($form->ricID, $errorMessageNomisma);
        }
    }

    /** Clone the options
     * @access public
     * @param string $broadperiod
     * @param array $coinDataFlat
     * @throw Exeption
     */
    public function optionsAddClone($broadperiod, array $coinDataFlat)
    {
        switch ($broadperiod) {
            case 'IRON AGE':
                if (isset($coinDataFlat['denomination'])) {
                    $geographies = new Geography();
                    $geography_options = $geographies
                        ->getIronAgeGeographyMenu($coinDataFlat['denomination']);
                    $form->geographyID->addMultiOptions(
                        array(
                            null => 'Choose geographic region',
                            'Available regions' => $geography_options
                        ));
                    $form->geographyID->addValidator('InArray', false,
                        array(array_keys($geography_options)));
                }
                break;
            case 'ROMAN':
                if (isset($coinDataFlat['ruler'])) {
                    $reverses = new RevTypes();
                    $reverse_options = $reverses->getRevTypesForm($coinDataFlat['ruler']);
                    if ($reverse_options) {
                        $form->revtypeID->addMultiOptions(
                            array(
                                null => 'Choose reverse type',
                                'Available reverses' => $reverse_options
                            ));
                    } else {
                        $form->revtypeID->addMultiOptions(
                            array(
                                null => 'No options available'
                            ));
                    }
                } else {
                    $form->revtypeID->addMultiOptions(
                        array(
                            null => 'No options available'
                        ));
                }
                if (isset($coinDataFlat['ruler'])
                    && ($coinDataFlat['ruler'] == 242)
                ) {
                    $moneyers = new Moneyers();
                    $moneyer_options = $moneyers->getRepublicMoneyers();
                    $form->moneyer->addMultiOptions(
                        array(
                            null => 'Choose moneyer',
                            'Available moneyers' => $moneyer_options
                        ));
                } else {
                    $form->moneyer->addMultiOptions(
                        array(
                            null => 'No options available'
                        ));
                }
                break;

            case 'EARLY MEDIEVAL':
                $types = new MedievalTypes();
                $type_options = $types->getMedievalTypeToRulerMenu($coinDataFlat['ruler']);
                $form->typeID->addMultiOptions(
                    array(
                        null => 'Choose Early Medieval type',
                        'Available types' => $type_options
                    ));
                break;
            case 'MEDIEVAL':
                $types = new MedievalTypes();
                $type_options = $types->getMedievalTypeToRulerMenu($coinDataFlat['ruler']);
                $form->typeID->addMultiOptions(
                    array(
                        null => 'Choose Medieval type',
                        'Available types' => $type_options
                    ));
                break;
            case 'POST MEDIEVAL':
                $types = new MedievalTypes();
                $type_options = $types->getMedievalTypeToRulerMenu($coinDataFlat['ruler']);
                $form->typeID->addMultiOptions(
                    array(
                        null => 'Choose Post Medieval type',
                        'Available types' => $type_options
                    ));
                break;
            default:
                throw new Exception('No period supplied', 500);
        }
    }
}