<?php
/** An action helper for adding correct options to a form
 *
 * An example of use:
 * <code>
 * <?php
 * $formLoader = $this->_helper->coinSummaryFormLoaderOptions( $coin );
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
class Pas_Controller_Action_Helper_CoinSummaryFormLoaderOptions extends Zend_Controller_Action_Helper_Abstract {

    /** The view object
     * @access protected
     * @var \Zend_View
     */
    protected $_view;

    /** The predispatch call
     * @access public
     * @return void
     */
    public function preDispatch(){
	$this->_view = $this->_actionController->view;
    }

    /** The direct action
     * @access public
     * @param array $coinDataFlat
     * @return void
     */
    public function direct( array $coinDataFlat ){
        return $this->optionsAddClone($coinDataFlat);
    }


    /** The constructor function
     * @access public
     * @return void
     */
    public function __construct() {
        $this->_filter = new Zend_Filter_StringToUpper();
    }

    /** The array of coin periods
     * @access protected
     * @var array
     */
    protected $_periods = array(
        'ROMAN','IRON AGE', 'EARLY MEDIEVAL',
        'POST MEDIEVAL', 'MEDIEVAL', 'BYZANTINE',
        'GREEK AND ROMAN PROVINCIAL'
        );

    /** add and clone last record
     * @access public
     * @param array $coinDataFlat
     * @return void
     */
    public function optionsAddClone( $coinData ){

        if(!is_null($coinData['ruler_id'])) {
            $rulers = new Rulers();
            $this->_view->form->ruler_id->addMultiOptions(array(
                NULL => 'Please choose a ruler',
                'Available rulers' => $rulers->getLastRulersPairs($coinData['broadperiod']))
            );
        }

        if(!is_null($coinData['denomination'])) {
            $denominations = new Denominations();
            $this->_view->form->denomination->addMultiOptions(array(
                NULL => 'Please choose a denomination',
                'Available choices' => $denominations->getDenominationByBroadPeriodPairs($coinData['broadperiod']))
            );
        }

        if(!is_null($coinData['mint_id'])) {
            $mints = new Mints();
            $this->_view->form->mint_id->addMultiOptions(array(
                    NULL => 'Please choose a mint',
                    'Available choices' => $mints->getMintbyBroadperiodPairs($coinData['broadperiod']))
            );
        }

        if($coinData['broadperiod'] == 'IRON AGE') {
            $geography = new Geography();
            $this->_view->form->geographyID->addMultiOptions(array(
                NULL => 'Please choose a geography if applicable',
                'Available choices' => $geography->getIronAgeGeographyDD())
            );
        }

    }
}