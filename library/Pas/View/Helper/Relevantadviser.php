<?php 
class Pas_View_Helper_Relevantadviser 
	extends Zend_View_Helper_Abstract {
	
	protected $_coinarray = array(
		'Coin', 'COIN', 'coin', 
		'token', 'jetton', 'coin weight',
		'TOKEN', 'JETTON', 'COIN WEIGHT'
	);
	
	protected $_periodRomIA = array(
		'Roman','ROMAN','roman',
		'Iron Age','Iron age','IRON AGE',
		'Byzantine','BYZANTINE','Greek and Roman Provincial',
		'GREEK AND ROMAN PROVINCIAL','Unknown','UNKNOWN');
	
	protected $_periodRomPrehist = array(
		'Roman','ROMAN','roman','Iron Age',
		'Iron age','IRON AGE','Byzantine',
		'BYZANTINE','Greek and Roman Provincial','GREEK AND ROMAN PROVINCIAL',
		'Unknown','UNKNOWN','Mesolithic',
		'MESOLITHIC','PREHISTORIC','NEOLITHIC',
		'Neolithic','Palaeolithic','PALAEOLITHIC',
		'Bronze Age','BRONZE AGE');
	
	protected $_earlyMed = array('Early Medieval','EARLY MEDIEVAL');
	
	protected $_medieval = array('Medieval','MEDIEVAL');
	
	protected $_postMed = array('Post Medieval','POST MEDIEVAL','Modern');
	
	protected $_config;


	/** Construct the objects
	 */
	public function __construct(){
	$this->_config = Zend_Registry::get('config');
	$this->_romancoinsadviser = $this->_config->findsadviser->romancoins;
	$this->_medievalcoinsadviser = $this->_config->findsadviser->medievalcoins;
	$this->_romanobjects = $this->_config->findsadviser->romanobjects;
	$this->_medievalobjects = $this->_config->findsadviser->medievalobjects;
	$this->_postmedievalobjects = $this->_config->findsadviser->postmedievalobjects;
	$this->_catchall = $this->_config->findsadviser->default;
	}

	/** Get the relevant finds adviser based on object type and broadperiod
	 * 
	 * @param string $objecttype
	 * @param string $broadperiod
	 */
	public function relevantadviser($objecttype,$broadperiod) {
	
	switch($objecttype) {
		case (in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_periodRomIA)): 
			$adviserdetails = $this->_romancoinsadviser;
			break;
		case (in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_earlyMed)):
			$adviserdetails = $this->_medievalcoinsadviser;
			break;
		case (in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_medieval)):
			$adviserdetails = $this->_medievalcoinsadviser;
			break;
		case (in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_postMed)):
			$adviserdetails = $this->_medievalcoinsadviser;
			break;
		case (!in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_periodRomPrehist)):
			$adviserdetails = $this->_romanobjects;
			break;
		case (!in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_postMed)):
			$adviserdetails = $this->_postmedievalobjects;
			break;
		case (!in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_medieval)):
			$adviserdetails = $this->_medievalobjects;
			break;
		case (!in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_earlyMed)):
			$adviserdetails = $this->_medievalobjects;
			break;
		default:
			$adviserdetails = $this->_catchall;
			break;
		}
	return $this->buildHtml($adviserdetails);
	}
	
	/** Build up the html
	 * 
	 * @param object $adviserdetails
	 */
	public function buildHtml($adviserdetails) {
	$adviserdetails = $adviserdetails->toArray();
	$html = '<ul>';
	foreach($adviserdetails as $k => $v){
    	$html .= '<li>' . $v . ' </li>';	
	}
	$html .= '</ul>';	
	return $html;
	}
}