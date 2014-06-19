
<!-- saved from url=(0140)https://raw.githubusercontent.com/findsorguk/findsorguk/0e044981b8121339c5dc61d835be3d3e93683759/library/Pas/View/Helper/RelevantAdviser.php -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body about="https://raw.githubusercontent.com/findsorguk/findsorguk/0e044981b8121339c5dc61d835be3d3e93683759/library/Pas/View/Helper/RelevantAdviser.php"><pre style="word-wrap: break-word; white-space: pre-wrap;">&lt;?php
/** A view helper to get the correct finds adviser for an error report
 *
 * An example of use:
 * &lt;code&gt;
 * &lt;?php
 * echo $this-&gt;relevantAdviser()
 * -&gt;setObjectType($objecttype)
 * -&gt;setBroadperiod($broadperiod);
 * ?&gt;
 * &lt;/code&gt;
 *
 * @author Daniel Pett &lt;dpett@britishmuseum.org&gt;
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
        return $this-&gt;_objectType;
    }

    /** Get the broadperiod
     * @access public
     * @return string
     */
    public function getBroadperiod() {
        return $this-&gt;_broadperiod;
    }

    /** Set the object type
     * @access public
     * @param string $objectType
     * @return \Pas_View_Helper_RelevantAdviser
     */
    public function setObjectType($objectType) {
        $this-&gt;_objectType = $objectType;
        return $this;
    }

    /** Set the broadperiod
     * @access public
     * @param string $broadperiod
     * @return \Pas_View_Helper_RelevantAdviser
     */
    public function setBroadperiod($broadperiod) {
        $this-&gt;_broadperiod = $broadperiod;
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
        $this-&gt;_catchAll = $this-&gt;getConfig()-&gt;findsadviser-&gt;default;
        return $this-&gt;_catchAll;
    }

    /** Get the Medieval coins adviser
     * @access public
     * @return \Zend_Config
     */
    public function getMedievalCoins() {
        $this-&gt;_medievalCoins = $this-&gt;getConfig()-&gt;findsadviser-&gt;medievalcoins;
        return $this-&gt;_medievalCoins;
    }

     /** Get the Roman coins adviser
     * @access public
     * @return \Zend_Config
     */
    public function getRomanCoins() {
        $this-&gt;_romanCoins = $this-&gt;getConfig()-&gt;findsadviser-&gt;romancoins;
        return $this-&gt;_romanCoins;
    }

    /** Get the Roman objects adviser
     * @access public
     * @return \Zend_Config
     */
    public function getRomanObjects() {
        $this-&gt;_romanObjects = $this-&gt;getConfig()-&gt;findsadviser-&gt;romanobjects;
        return $this-&gt;_romanObjects;
    }

    /** Get the Medieval objects adviser
     * @access public
     * @return \Zend_Config
     */
    public function getMedievalObjects() {
        $this-&gt;_medievalObjects = $this-&gt;getConfig()-&gt;findsadviser-&gt;medievalobjects;
        return $this-&gt;_medievalObjects;
    }

    /** Get the post medieval objects adviser
     * @access public
     * @return \Zend_Config
     */
    public function getPostMedievalObjects() {
        $this-&gt;_postMedievalObjects = $this-&gt;getConfig()-&gt;findsadviser-&gt;postmedievalobjects;
        return $this-&gt;_postMedievalObjects;
    }

    /** Get the config object
     * @access public
     * @return \Zend_Config
     */
    public function getConfig() {
        $this-&gt;_config = Zend_Registry::get('config');
        return $this-&gt;_config;
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
        $broadperiod = $this-&gt;getBroadperiod();
        switch ($this-&gt;getObjectType()) {
            case (in_array($objecttype,$this-&gt;_coinarray) &amp;&amp; in_array($broadperiod,$this-&gt;_periodRomIA)):
                $adviserdetails = $this-&gt;getRomanCoins();
                break;
            case (in_array($objecttype,$this-&gt;_coinarray) &amp;&amp; in_array($broadperiod,$this-&gt;_earlyMed)):
                $adviserdetails = $this-&gt;getMedievalCoins();
                break;
            case (in_array($objecttype,$this-&gt;_coinarray) &amp;&amp; in_array($broadperiod,$this-&gt;_medieval)):
                $adviserdetails = $this-&gt;getMedievalCoins();
                break;
            case (in_array($objecttype,$this-&gt;_coinarray) &amp;&amp; in_array($broadperiod,$this-&gt;_postMed)):
                $adviserdetails = $this-&gt;getMedievalCoins();
                break;
            case (!in_array($objecttype,$this-&gt;_coinarray) &amp;&amp; in_array($broadperiod,$this-&gt;_periodRomPrehist)):
                $adviserdetails = $this-&gt;getRomanObjects();
                break;
            case (!in_array($objecttype,$this-&gt;_coinarray) &amp;&amp; in_array($broadperiod,$this-&gt;_postMed)):
                $adviserdetails = $this-&gt;getPostMedievalObjects();
                break;
            case (!in_array($objecttype,$this-&gt;_coinarray) &amp;&amp; in_array($broadperiod,$this-&gt;_medieval)):
                $adviserdetails = $this-&gt;getMedievalObjects();
                break;
            case (!in_array($objecttype,$this-&gt;_coinarray) &amp;&amp; in_array($broadperiod,$this-&gt;_earlyMed)):
                $adviserdetails = $this-&gt;getMedievalObjects();
                break;
            default:
                $adviserdetails = $this-&gt;getCatchAll();
                break;
            }
        return $this-&gt;buildHtml($adviserdetails);
    }

    /** Build up the html
     * @access public
     * @param Zend_Config $adviserdetails
     * @return string The html to return
     */
    public function buildHtml( Zend_Config $adviserdetails ) {
        $html = '';
        if( $adviserdetails instanceof Zend_Config) {
        $advisers = $adviserdetails-&gt;toArray();
        $html .= '&lt;ul&gt;';
        foreach ($advisers as $k =&gt; $v) {
            $html .= '&lt;li&gt;' . $v . ' &lt;/li&gt;';
        }
        $html .= '&lt;/ul&gt;';
        }
        return $html;
    }
}</pre></body></html>