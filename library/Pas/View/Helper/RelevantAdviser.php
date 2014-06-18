<?php
/** A view helper to get the correct finds adviser for an error report
 *
 * An example of use:
 * <code>
 * <?php
 * echo $this->relevantAdviser()
 * ->setObjectType($objecttype)
 * ->setBroadperiod($broadperiod);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @category Pas
 * @package Pas_View_Helper
 * @copyright (c) 2014, Daniel Pett
 *
 *
 */
class Pas_View_Helper_RelevantAdviser extends Zend_View_Helper_Abstract {

    /** The object type
     * @access protected
     * @var string
     */
    protected $_objectType;

    /** the broad period
     * @access protected
     * @var string
     */
    protected $_broadperiod;

    /** Get the object type
     * @access public
     * @return string
     */
    public function getObjectType() {
        return $this->_objectType;
    }

    /** Get the broadperiod
     * @access public
     * @return string
     */
    public function getBroadperiod() {
        return $this->_broadperiod;
    }

    /** Set the object type
     * @access public
     * @param string $objectType
     * @return \Pas_View_Helper_RelevantAdviser
     */
    public function setObjectType($objectType) {
        $this->_objectType = $objectType;
        return $this;
    }

    /** Set the broadperiod
     * @access public
     * @param string $broadperiod
     * @return \Pas_View_Helper_RelevantAdviser
     */
    public function setBroadperiod($broadperiod) {
        $this->_broadperiod = $broadperiod;
        return $this;
    }

    /** The coin array
     * @access protected
     * @var array
     */
    protected $_coinarray = array(
        'Coin', 'COIN', 'coin',
        'token', 'jetton', 'coin weight',
        'TOKEN', 'JETTON', 'COIN WEIGHT'
    );

    /** Roman and Iron Age period array
     * @access protected
     * @var array
     */
    protected $_periodRomIA = array(
        'Roman','ROMAN','roman',
        'Iron Age','Iron age','IRON AGE',
        'Byzantine','BYZANTINE','Greek and Roman Provincial',
        'GREEK AND ROMAN PROVINCIAL','Unknown','UNKNOWN');

    /** Roman and prehistoric array
     * @access protected
     * @var array
     */
    protected $_periodRomPrehist = array(
        'Roman','ROMAN','roman','Iron Age',
        'Iron age','IRON AGE','Byzantine',
        'BYZANTINE','Greek and Roman Provincial','GREEK AND ROMAN PROVINCIAL',
        'Unknown','UNKNOWN','Mesolithic',
        'MESOLITHIC','PREHISTORIC','NEOLITHIC',
        'Neolithic','Palaeolithic','PALAEOLITHIC',
        'Bronze Age','BRONZE AGE');

    /** Early medieval array
     * @access protected
     * @var array
     */
    protected $_earlyMed = array('Early Medieval','EARLY MEDIEVAL');

    /** Medieval array
     * @access protected
     * @var array
     */
    protected $_medieval = array('Medieval','MEDIEVAL');

    /** Post medieval array
     * @access public
     * @var array
     */
    protected $_postMed = array('Post Medieval','POST MEDIEVAL','Modern');

    /** The config object
     * @access public
     * @var Zend_Config
     */
    protected $_config;

    /** Medieval coins
     * @access protected
     * @var string
     */
    protected $_medievalCoins;

    /** Roman coins adviser
     * @access protected
     * @var string
     */
    protected $_romanCoins;

    /** Roman objects adviser
     * @access protected
     * @var string
     */
    protected $_romanObjects;

    /** Medieval objects adviser
     * @access protected
     * @var string
     */
    protected $_medievalObjects;

    /** Post Medieval objects adviser
     * @access protected
     * @var string
     */
    protected $_postMedievalObjects;

    /** Catch all adviser
     * @access protected
     * @var string
     */
    protected $_catchAll;

    /** Get the catch all adviser
     * @access public
     * @return \Zend_Config
     */
    public function getCatchAll() {
        $this->_catchAll = $this->getConfig()->findsadviser->default;
        return $this->_catchAll;
    }

    /** Get the Medieval coins adviser
     * @access public
     * @return \Zend_Config
     */
    public function getMedievalCoins() {
        $this->_medievalCoins = $this->getConfig()->findsadviser->medievalcoins;
        return $this->_medievalCoins;
    }

     /** Get the Roman coins adviser
     * @access public
     * @return \Zend_Config
     */
    public function getRomanCoins() {
        $this->_romanCoins = $this->getConfig()->findsadviser->romancoins;
        return $this->_romanCoins;
    }

    /** Get the Roman objects adviser
     * @access public
     * @return \Zend_Config
     */
    public function getRomanObjects() {
        $this->_romanObjects = $this->getConfig()->findsadviser->romanobjects;
        return $this->_romanObjects;
    }

    /** Get the Medieval objects adviser
     * @access public
     * @return \Zend_Config
     */
    public function getMedievalObjects() {
        $this->_medievalObjects = $this->getConfig()->findsadviser->medievalobjects;
        return $this->_medievalObjects;
    }

    /** Get the post medieval objects adviser
     * @access public
     * @return \Zend_Config
     */
    public function getPostMedievalObjects() {
        $this->_postMedievalObjects = $this->getConfig()->findsadviser->postmedievalobjects;
        return $this->_postMedievalObjects;
    }

    /** Get the config object
     * @access public
     * @return \Zend_Config
     */
    public function getConfig() {
        $this->_config = Zend_Registry::get('config');
        return $this->_config;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_RelevantAdviser
     */
    public function relevantAdviser() {
        return $this;
    }


    /** The to string function
     * @access public
     * @return string
     */
    public function __toString() {
        $broadperiod = $this->getBroadperiod();
        switch ($this->getObjectType()) {
            case (in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_periodRomIA)):
                $adviserdetails = $this->getRomanCoins();
                break;
            case (in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_earlyMed)):
                $adviserdetails = $this->getMedievalCoins();
                break;
            case (in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_medieval)):
                $adviserdetails = $this->getMedievalCoins();
                break;
            case (in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_postMed)):
                $adviserdetails = $this->getMedievalCoins();
                break;
            case (!in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_periodRomPrehist)):
                $adviserdetails = $this->getRomanObjects();
                break;
            case (!in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_postMed)):
                $adviserdetails = $this->getPostMedievalObjects();
                break;
            case (!in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_medieval)):
                $adviserdetails = $this->getMedievalObjects();
                break;
            case (!in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_earlyMed)):
                $adviserdetails = $this->getMedievalObjects();
                break;
            default:
                $adviserdetails = $this->getCatchAll();
                break;
            }
        return $this->buildHtml($adviserdetails);
    }

    /** Build up the html
     * @access public
     * @param Zend_Config $adviserdetails
     * @return string The html to return
     */
    public function buildHtml( Zend_Config $adviserdetails ) {
        $html = '';
        if( $adviserdetails instanceof Zend_Config) {
        $advisers = $adviserdetails->toArray();
        $html .= '<ul>';
        foreach ($advisers as $k => $v) {
            $html .= '<li>' . $v . ' </li>';
        }
        $html .= '</ul>';
        }
        return $html;
    }
}