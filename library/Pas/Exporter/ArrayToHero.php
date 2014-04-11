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
class Pas_Exporter_ArrayToHero {

    protected $_fields;

    protected $_role;

    protected $_allowed = array('admin','flos','fa','treasure');

    protected $_maybe = array('hero',);

    protected $_never = array('member',null,'public','research');

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
//            Zend_Debug::dump($dat,'ORIGINAL');
set_time_limit(0);
            $dat['SecUID'] = $dat['secuid'];
            $dat['FindID'] = $dat['old_findID'];
            $dat['ObjectType'] = $dat['objecttype'];
            $dat['ObjectDescription'] = $dat['description'];
            unset($dat['description']);//This export routine is rubbish.
            $dat['ObjectInscription'] = $dat['inscription'];
            $dat['notes'] = $dat['notes'];
            $dat['DateFrom'] = $dat['datefrom'];
            $dat['DateTo'] = $dat['dateto'];
            $dat['PeriodFrom'] = $dat['periodFromName'];
            $dat['PeriodTo'] = $dat['periodToName'];
            $dat['DateTo'] = $dat['todate'];
            $dat['DateFrom'] = $dat['fromdate'];
            $dat['AscribedCulture'] = $dat['cultureName'];
            $dat['PrimaryMaterial'] = $dat['materialTerm'];
            $dat['AdditionalMaterial'] = $dat['secondaryMaterialTerm'];
            $dat['MethodOfManufacture'] = $dat['manufactureTerm'];
            $dat['SurfaceTreatment'] = $dat['treatment'];
            $dat['Preservation'] = $dat['preservationTerm'];
            $dat['Completeness'] = $dat['completeness'];
            $dat['OSRef'] = $dat['gridref'];
            $dat['Easting'] = $dat['easting'];
            $dat['Northing'] = $dat['northing'];
            $dat['Finder'] = $dat['finder'];
            $dat['DateFound1'] = $dat['datefound1'];
            $dat['DateFound2'] = $dat['datefound2'];
            $dat['MethodsOfDiscovery'] = $dat['discoveryMethod'];
            $dat['RecordedBy'] = $dat['recorder'];
            $dat['PrimaryIdentifier'] = $dat['identifier'];
            $dat['SecondaryIdentifier'] = $dat['secondaryIdentifier'];
            $dat['CurrentLocation'] = $dat['currentLocation'];
            $dat['MuseumAccNo'] = $dat['musaccno'];
            $dat['SubsequentAction'] = $dat['subsequentActionTerm'];
            $dat['OtherReference'] = $dat['otherRef'];
            $dat['SubperiodFrom'] = null;
            $dat['SubperiodTo'] = null;
            $dat['CoolFind'] = $dat['note'];
            $dat['SMRReference'] = $dat['smrRef'];
            $dat['BroadPeriod'] = $dat['broadperiod'];
            $dat['WorkflowStage'] = $dat['workflow'];
            $dat['Ruler'] = $dat['rulerName'];
            $dat['Denomination'] = $dat['denomination'];
            $dat['Mint'] = $dat['mintName'];
            $dat['CoinType'] = $dat['typeTerm'];
            $dat['STATUS'] = null;
            $dat['Moneyer'] = $dat['moneyerName'];
            $dat['Obverse_description'] = $dat['obverseDescription'];
            $dat['Obverse_inscription'] = $dat['obverseLegend'];
            $dat['Initial_mark'] = NULL;
            $dat['Reverse_description'] = $dat['reverseDescription'];
            $dat['Reverse_inscription']	= $dat['reverseLegend'];
            $dat['Reverse_mintmark'] = $dat['mintmark'];
            $dat['Die_axis_measurement'] = $dat['axis'];
            $dat['Completeness'] = $dat['completenessTerm'];
            $dat['FindOfficer'] = $dat['recorder'];
            $dat['KnownAs'] = $dat['knownas'];
            $dat['IDOfFind'] = $dat['id'];
            $dat['Decstyle'] = $dat['decstyleTerm'];
            $dat['Denomination'] = $dat['denominationName'];
            $dat['FindspotCode'] = $dat['Findspotcode'];
//            Zend_Debug::dump($dat,"NEW");
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
            if($v === '' || is_null($v)){
                $record[$k] = NULL;
            }
        }


	}
        $cleanSort = $this->sortArrayByArray($record, $this->_fields);

	$finalData[] = $cleanSort;
 }
    return $finalData;
	}
}
