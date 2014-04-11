<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Csv
 *
 * @author danielpett
 */
class Pas_Exporter_Hero extends Pas_Exporter_Generate {

    protected $_format = 'hero';

    protected $_heroFields = array(
        'secuid', 'old_findID', 'objecttype',
        'description','classification', 'subClassification',
        'inscription', 'notes', 'fromdate',
        'todate', 'cultureName', 'materialTerm',
        'secondaryMaterialTerm', 'manufactureTerm', 'treatment',
        'length', 'width', 'thickness',
        'diameter', 'weight', 'quantity',
        'preservationTerm', 'completenessTerm',
        'gridref', 'easting', 'northing',
        'finder', 'datefound1', 'datefound2',
        'discoveryMethod', 'recorder', 'identifier',
        'secondaryIdentifier', 'currentLocation', 'musaccno',
        'subsequentActionTerm', 'otherRef', 'fromsubperiod',
        'tosubperiod', 'decstyleTerm', 'note',
        'smrRef', 'broadperiod', 'workflow',
        'creator', 'county', 'district',
        'parish', 'description', 'knownas',
        'id', 'rulerName', 'denominationName',
        'mintName', 'typeTerm', 'moneyerName',
        'obverseDescription', 'obverseInscription',
        'reverseDescription', 'reverseInscription',
        'mintmark', 'axis', 'reeceID',
        'periodFromName', 'periodToName', 'Findspotcode'
	);

    protected $_exegesis = array(
        'SecUID', 'FindID', 'ObjectType',
        'ObjectTypeCertainty', 'ObjectDescription', 'ObjectClassification',
        'ObjectSubClassification', 'ObjectInscription',	'Notes',
        'ObjectDate1Certainty', 'DateFrom', 'PeriodFrom',
        'CalendarDate1Qualifier', 'ObjectDate2Certainty', 'PeriodTo',
        'CalendarDate2Qualifier', 'DateTo', 'AscribedCulture',
        'PrimaryMaterial', 'AdditionalMaterial', 'MethodOfManufacture',
        'SurfaceTreatment', 'length', 'width',
        'thickness', 'diameter', 'weight',
        'quantity', 'Wear', 'Preservation',
        'Completeness', 'EvidenceOfReuse', 'OSRef',
        'Easting', 'Northing', 'Finder',
        'DateFound1Qualifier', 'DateFound1', 'DateFound2Qualifier',
        'DateFound2', 'MethodsOfDiscovery', 'CircumstancesofDiscovery',
        'RecordedBy', 'PrimaryIdentifier', 'SecondaryIdentifier',
        'CurrentLocation', 'MuseumAccNo', 'SubsequentAction',
        'OtherReference', 'SubperiodFrom', 'SubperiodTo',
        'PeriodOfReuse', 'DecmethodObsolete', 'Decstyle',
        'CoolFind', 'FindspotCode', 'FormerFinderID',
        'FormerCandidateTerm', 'FormerPhotoReference','FormerDrawingReference',
        'ExportedToWeb', 'SMRReference', 'BroadPeriod',
        'WorkflowStage', 'FindOfficer', 'county',
        'district', 'parish', 'address',
        'postcode', 'description', 'KnownAs',
        'comments', 'LandOwner', 'Occupier',
        'SpecificLanduse', 'GeneralLanduse', 'IDOfFind',
        'FindOfficerFindspot', 'Ruler', 'RulerQualifier',
        'Denomination', 'DenominationQualifier', 'Mint',
        'MintQualifier', 'CoinType', 'STATUS',
        'StatusQualifier', 'Moneyer', 'Obverse_description',
        'Obverse_inscription', 'Initial_mark', 'Reverse_description',
        'Reverse_inscription', 'Reverse_mintmark', 'Degree_of_wear',
        'Die_axis_measurement',	'Die_axis_certainty', 'reeceID'
    );

    public function fetch($page){
    $this->_params['page'] = $page;
    $this->_search->setFields($this->_heroFields);
    $this->_search->setParams($this->_params);
    $this->_search->execute();
    return $this->_search->_processResults();
    }


    public function create(){
    $this->_search->setFields($this->_heroFields);
    $this->_search->setParams($this->_params);
    $this->_search->execute();
    $data = $this->_search->_processResults();
    $paginator = $this->_search->_createPagination();
    $pages = $paginator->getPages();
    $iterator = $pages->pageCount;

    $converter = new Pas_Exporter_ArrayToHero($this->_exegesis);
    $clean = $converter->convert($data);
    if($iterator > 300) {
            set_time_limit(0);
            ini_set('memory_limit', '256M');
        }
    $file = fopen('php://temp/maxmemory:'. (12*1024*1024), 'r+');
    fputcsv($file,$this->_exegesis,',','""');
    foreach($clean as $c){
        fputcsv($file,array_values($c),',','""');
    }
    if($iterator > 1){
    foreach (range(2, $iterator) as $number) {
    $retrieved = $this->fetch($number);
    $record = $converter->convert($retrieved);
    foreach($record as $rec){
    fputcsv($file, $rec, ',', '"');
    }
    }
    }
      rewind($file);
      $output = stream_get_contents($file);
      fclose($file);
      $filename = 'HistoricEnvironmentExportFor_' . $this->_user->username . '_' . $this->_dateTime . '.csv';
      $fc = Zend_Controller_Front::getInstance();
      $fc->getResponse()->setHeader('Content-type', 'text/csv; charset=utf-8');
      $fc->getResponse()->setHeader('Content-Disposition', 'attachment; filename=' . $filename);
      echo $output;

    }
}

