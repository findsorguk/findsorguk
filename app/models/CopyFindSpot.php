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

class CopyFindSpot extends Pas_Db_Table_Abstract {

	protected $_name = 'copyFindSpot';
	protected $_primary = 'id';

	protected $_default = array(
		'county', 'district', 'parish',
		'knownas', 'regionID', 'knownas',
		'gridref', 'gridrefsrc', 'gridrefcert',
		'description', 'comments', 'landusecode',
		'landusevalue', 'depthdiscovery', 'countyID',
                'parishID', 'districtID'
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
