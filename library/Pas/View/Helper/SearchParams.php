<?php
/**
 * This class is to display search params
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @version 2
 * @since march 14 2012
*/
class Pas_View_Helper_SearchParams
	extends Zend_View_Helper_Abstract {

	/** The cache object
	 *
	 * @var unknown_type
	 */
	protected $_cache;

	/** Create the cache object
	 *
	 */
	public function __construct(){
		$this->_cache = Zend_Registry::get('cache');
	}

	/** Array of cleaned names for display as a parameter
	 *
	 * @var array
	 */
	protected $_niceNames = array(
		'mintName' => 'Mint',
		'rulerName' => 'Ruler',
		'objectType' => 'Object type',
		'q' => 'Free text search',
		'fourFigure' => 'Four figure NGR',
		'old_findID' => 'Find number',
		'discovered' => 'Discovery year',
		'TID' => 'Treasure case number',
		'hID' => 'Hoard ID',
		'otherref' => 'other reference',
		'smrRef' => 'SMR or HER reference number',
		'typeID' => 'Medieval periodic type',
		'cciNumber' => 'Celtic coin Index number',
		'broadperiod' => 'Broad period',
		'objecttype' => 'Object type',
		'rallyID' => 'Rally known as',
        'woeid' => 'Yahoo!\'s Where on Earth ID number',
        'd' => 'Distance (in kilometres)',
        'lat' => 'latitude',
        'lon' => 'longitude',
        'bbox' => 'Bounding box co-ordinates',
        'createdBy' => 'Created by',
		'fromsubperiod' => 'Sub period from',
		'tosubperiod' => 'Sub period to',
		'periodFrom' => 'Period from',
		'periodTo' => 'Period to',
		'culture' => 'Ascribed culture',
		'otherRef' => 'Other reference',
		'elevation' => 'Height above sea level in metres',
		'geographyID' => 'Iron Age geographical region',
		'tribe' => 'Iron Age tribal association',
		'axis' => 'Die axis measurement',
		'vaType' => 'Van Arsdell number',
		'allenType' => 'Allen type',
		'ruddType' => 'Ancient British Coinage number',
		'mackType' => 'Mack Type',
		'numChiab' => 'Coin hoards of Iron Age Britain number',
		'phase_date_1' => 'Phase date 1',
		'Phase_date_2' => 'Phase date 2',
		'depositionDate' => 'Date of deposition',
		'obverseLegend' => 'Obverse inscription',
		'reverseLegend' => 'Reverse inscription',
		'obverseDescription' => 'Obverse description',
		'reverseDescription' => 'Reverse description',
		'show' => 'Show this many records per page',
		'createdAfter' => 'Created after',
		'createdBefore' => 'Created before',
		'updatedAfter' => 'Updated after',
		'updatedBefore' => 'Updated before',
		'fromdate' => 'Date from',
		'todate' => 'Date to',
		'materialTerm' => 'Primary material',
		'identifier1ID' => 'Primary identifier (obfuscated for security)',
		'identifier2ID' => 'Secondary identifier (obfuscated for security)',
		'recorderID' => 'Recorded by (obfuscated for security)'

	);

	/** Generate the search string from parameters submitted
	 * @access public
	 * @param array $params
	 * @return string
	 */
	public function SearchParams($params = NULL) {

	$params = array_slice($params,3);
	if(array_key_exists('page',$params)){
		unset($params['page']);
	}

	$params = $this->cleanParams($params);
	$html = '<p>You searched for: ';
	if(sizeof($params) > 0 ) {
	$html .= '</p><ul>';
   	$searches = array();
	foreach($params as $k => $v){
		switch($k){
			case 'fromdate':
				$html .= '<li>' . $this->cleanKey($k) .': ' . $this->view->adbc($v) . '</li>';
				break;
			case 'todate':
				$html .= '<li>' . $this->cleanKey($k) .': ' . $this->view->adbc($v) . '</li>';
				break;
			case 'updated':
				$html .= '<li>' . $this->cleanKey($k) .': ' . $this->view->niceshortdate($v) . '</li>';
				break;
			case 'created':
				$html .= '<li>' . $this->cleanKey($k) .': ' . $this->view->niceshortdate($v) . '</li>';
				break;
			case 'updatedAfter':
				$html .= '<li>' . $this->cleanKey($k) .': ' . $this->view->niceshortdate($v) . '</li>';
				break;
			case 'updatedBefore':
				$html .= '<li>' . $this->cleanKey($k) .': ' . $this->view->niceshortdate($v) . '</li>';
				break;
			case 'createdBefore':
				$html .= '<li>' . $this->cleanKey($k) .': ' . $this->view->niceshortdate($v) . '</li>';
				break;
			case 'createdAfter':
				$html .= '<li>' . $this->cleanKey($k) .': ' . $this->view->niceshortdate($v) . '</li>';
				break;
			default:
				$html .= '<li>' . $this->cleanKey($k) .': ' . $v . '</li>';
				break;
		}
		$searches[] = $this->cleanKey($k) . ' ' . $v;


	}
        $this->view->headTitle('Search results from the database');
        $this->view->headMeta(implode(' - ', $searches), 'description');
        $this->view->headMeta(implode(',', $searches), 'keywords');
	$html .= '</ul>';
	} else {
		$html .= 'Everything we have</p>';
	}
	return $html;
	}

	/** Clean the key for nicename
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public function cleanKey($string){
	if(in_array($string,array_keys($this->_niceNames))){
	$text = "$string";
	foreach ($this->_niceNames as $key => $value) {
	$text = preg_replace( "|(?!<[^<>]*?)(?<![?.&])\b$key\b(?!:)(?![^<>]*?>)|msU",
	$value , $text );
	}
	} else {
	$text = $string;
	}
	return ucfirst($text);
	}

	/** Look up the correct value and cache the results
	 * @access public
	 * @param string $name The model name
	 * @param string $field The field to return
	 * @param string $value The value to lookup
	 * @return string
	 */
	public function getData($name, $field, $value, $idField = 'id'){
	$key = md5($name . $field . $value . $idField);
	if (!($this->_cache->test($key))) {
	$model = new $name();
	$data = $model->fetchRow($model->select()->where($idField . ' = ?', $value));
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load($key);
	}
	return $data->$field;
	}

	/** Clean up the parameters submitted
	 * @access public
	 * @param array $params The parameters submitted
	 * @return string
	 */
	public function cleanParams($params){
	foreach($params as $key => $value){
	switch($key){
		case 'regionID':
			$params[$key] = $this->getData('Regions','region', $value);
			break;
		case 'denomination':
			$params[$key] = $this->getData('Denominations','denomination', $value);
			break;
		case 'ruler':
			$params[$key] = $this->getData('Rulers','issuer', $value);
			break;
		case 'mint':
			$params[$key] = $this->getData('Mints','mint_name', $value);
			break;
		case 'material':
			$params[$key] = $this->getData('Materials','term', $value);
			break;
		case 'hID':
			$params[$key] = $this->getData('Hoards','term', $value);
			break;
		case 'countyID':
			$params[$key] = $this->getData('OsCounties', 'label', $value, 'osID');
			break;
		case 'regionID':
			$params[$key] = $this->getData('OsRegions', 'label', $value, 'osID');
			break;
		case 'parishID':
			$params[$key] = $this->getData('OsParishes', 'label', $value, 'osID');
			break;
		case 'districtID':
			$params[$key] = $this->getData('OsDistricts', 'label', $value, 'osID');
			break;
		case 'treasure' :
			$params[$key] = 'yes';
			break;
		case 'rally' :
			$params[$key] = 'yes';
			break;
		case 'note':
			$params[$key] = 'yes';
			break;
		case 'hoard':
			$params[$key] = 'yes';
			break;
		case 'thumbnail':
			$params[$key] = 'Only records with images please';
			break;
		case 'surface':
			$params[$key] = $this->getData('Surftreatments','term', $value);
			break;
		case 'workflow':
			$params[$key] = $this->getData('Workflows','workflowstage', $value);
			break;
		case 'manufacture':
			$params[$key] = $this->getData('Manufactures','term', $value);
			break;
		case 'decoration':
			$params[$key] = $this->getData('Decmethods','term', $value);
			break;
		case 'category':
			$params[$key] = $this->getData('CategoriesCoins','category', $value);
			break;
		case 'reason':
			$params[$key] = $this->getData('Findofnotereasons','term', $value);
			break;
		case 'type':
			$params[$key] = $this->getData('MedievalTypes','type', $value);
			break;
		case 'rallyID':
			$params[$key] = $this->getData('Rallies','rally_name', $value);
			break;
		case 'createdBy':
			$params[$key] = $this->getData('Users','fullname', $value);
			break;
		case 'fromsubperiod':
			$params[$key] = $this->getData('SubPeriods','term', $value);
			break;
		case 'tosubperiod':
			$params[$key] = $this->getData('SubPeriods','term', $value);
			break;
		case 'periodFrom':
			$params[$key] = $this->getData('Periods','term', $value);
			break;
		case 'periodTo':
			$params[$key] = $this->getData('Periods','term', $value);
			break;
		case 'culture':
			$params[$key] = $this->getData('Cultures','term', $value);
			break;
		case 'tribe':
			$params[$key] = $this->getData('Tribes','tribe', $value);
			break;
		case 'geographyID':
			$params[$key] = $this->getData('Geography','area', $value);
			break;
		case 'axis':
			$params[$key] = $this->getData('Dieaxes','die_axis_name', $value);
			break;
		case 'moneyer':
			$params[$key] = $this->getData('Moneyers','name', $value);
			break;
		case 'reeceID':
			$params[$key] = 'Period ' . $value . ': ' . $this->getData('Reeces','description', $value);
			break;
		case 'regionID':
			$params[$key] = $this->getData('OsRegions','label', $value, 'osID');
			break;
		default:
			$params[$key] = $value;
			break;
	}
	}
	return $params;
	}



	}