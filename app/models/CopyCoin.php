<?php
/**
* A model to manipulate data for the Counties of England and Wales. Scotland may be added
* in the future 
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/

class CopyCoin extends Pas_Db_Table_Abstract {
	
	protected $_name = 'copyCoin';
	protected $_primary = 'id';
	
	protected $_key;
	
	public function init(){
		$this->_cache = Zend_Registry::get('cache');
		$this->_key = md5('coinConfig' . $this->userNumber());
	}

	protected $_default = array(
		'ruler_id', 'ruler_qualifier', 'denomination',
		'denomination_qualifier', 'mint_id', 'mint_qualifier', 
		'status',	'status_qualifier',	'obverse_description',
		'obverse_inscription', 'reverse_description', 'reverse_inscription',
		'reverse_mintmark', 'degree_of_wear', 'die_axis_measurement',
		'die_axis_certainty', 'moneyer', 'reeceID',
		'revtypeID', 'revTypeID_qualifier', 'ruler2_id',
		'ruler2_qualifier', 'tribe' , 'tribe_qualifier',
		'geographyID', 'geography_qualifier', 'bmc_type',
		'allen_type', 'mack_type', 'rudd_type',
		'va_type','numChiab', 'categoryID', 
		'typeID', 'type', 'initial_mark', 
		'greekstateID', 'revtypeID'
	);
	
	public function getConfig(){
		$copy = $this->getAdapter();
		$select = $copy->select()
		->from($this->_name, array('fields'))
		->where('userID = ?', (int)$this->userNumber());
		$fields = $copy->fetchAll($select);
		if($fields) {
			$checked = unserialize($fields['0']['fields']);
		} else {
			$checked =  $this->_default;
		}
		return $checked;
		}

	public function updateConfig( $data ){
		if(array_key_exists('csrf', $data)){
 		unset($data['csrf']);
  		}
		foreach ( $data as $key => $value){
			if(is_null($value) || $value === '' || $value === '0'){
				unset($data[$key]);
			}
		} 
		$newFields = array_keys($data);
		$updateData['fields'] = serialize($newFields);
		$updateData['created'] = $this->timeCreation();
		$updateData['createdBy'] = $this->userNumber();
		$updateData['userID'] = $this->userNumber();
		parent::delete('userID =' . $this->userNumber());
		return parent::insert($updateData);	
	}
	
	
}
