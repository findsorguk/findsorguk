<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArrayToCsv
 *
 * @author danielpett
 */
class Pas_Exporter_ArrayToCsv {

    protected $_fields;

    protected $_role;

    protected $_allowed = array('admin','flos','fa','treasure');

    protected $_maybe = array('hero','research');

    protected $_never = array('member',null,'public');
    
    protected $_uri = 'http://finds.org.uk/database/artefacts/record/id/';
    
    public function __construct($fields){
        $this->_fields = $fields;
        $user = new Pas_User_Details();
        $this->_role = $user->getPerson()->role;
    }

    public function sortArrayByArray(array $toSort, array $sortByValuesAsKeys){
    $commonKeysInOrder = array_intersect_key(array_flip($sortByValuesAsKeys), $toSort);
    $commonKeysWithValue = array_intersect_key($toSort, $commonKeysInOrder);
    $sorted = array_merge($commonKeysInOrder, $commonKeysWithValue);
    return $sorted;
    }

    public function convert($data) {

        $remove = array_merge($this->_never, $this->_maybe);

        foreach($data as $dat){
        foreach($this->_fields as $k){
            if(!array_key_exists($k, $dat)){
                $dat[$k] = NULL;
            }
        }
        $nullified[] = $dat;
        }

    foreach ($nullified AS $null) {

 	foreach($null as $k => $v){

	$record[$k] = trim(strip_tags(str_replace('<br />',array( "\n", "\r"), utf8_decode( $v ))));
        if(in_array($this->_role,$remove)){
            $record['finder'] = 'Restricted info';
        }
        foreach($record as $k => $v){
            if($v === ''){
                $record[$k] = null;
            }
        }
		$record['uri'] = $this->_uri . $record['id'];
        if(in_array($this->_role,$this->_never)){
            $record['gridref'] = NULL;
            $record['easting'] = NULL;
            $record['northing'] = NULL;
            $record['latitude'] = NULL;
            $record['longitude'] = NULL;
            if(!is_null($record['knownas']) ){
                $record['parish'] = 'Restricted access';
                $record['fourFigure'] = 'Restricted access';

            }

        }
        
	}
        $cleanSort = $this->sortArrayByArray($record, $this->_fields);
       
	$finalData[] = $cleanSort;
 	}
 	
	
	
    return $finalData;
	}
}
