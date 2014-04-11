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

class CopyFind extends Pas_Db_Table_Abstract {
	
	protected $_name = 'copyFind';
	protected $_primary = 'id';

	protected $_default = array(
		'description', 'finderID', 'other_ref',
		'datefound1', 'datefound2', 'culture',
		'discmethod', 'disccircum', 'notes',
		'objecttype', 'classification', 'subclass',
		'inscription', 'objdate1period', 'objdate2period',
		'broadperiod', 'numdate1', 'numdate2',
		'material1', 'material2', 'manmethod',
		'decmethod', 'surftreat', 'decstyle',
		'preservation', 'completeness', 'reuse',
		'reuse_period', 'length', 'width',
		'thickness', 'diameter', 'weight',
		'height', 'quantity', 'curr_loc',
		'recorderID', 'finder2ID', 'identifier1ID',
		'identifier2ID', 'findofnotereason', 'findofnote',
		'numdate1qual', 'numdate2qual','objdate1cert',
		'objdate2cert',	'treasure', 'treasureID',
		'subs_action', 'musaccno', 'smr_ref',
		'objdate1subperiod','objdate2subperiod'
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
