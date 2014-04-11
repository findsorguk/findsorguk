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
class Pas_Exporter_Csv extends Pas_Exporter_Generate {

    protected $_format = 'csv';

    protected $_csvFields = array(
		'id','uri','old_findID','secuid',
		'objecttype', 'classification', 'subClassification',
		'length', 'height', 'width',
		'thickness', 'diameter', 'weight', 'quantity', 
		'otherRef', 'smrRef', 'musaccno',
        'TID', 'broadperiod','fromdate',
        'todate', 'periodFromName', 'subperiodFrom',
    	'periodToName', 'subperiodTo', 'cultureName',
    	'description', 'note', 'reasonTerm',
        'notes', 'inscription', 'workflow',
        'rulerName', 'mintName', 'denominationName',
        'tribeName', 'reeceID', 'categoryTerm',
        'typeTerm', 'geography', 'axis',
        'moneyerName', 'obverseDescription', 'reverseDescription',
        'obverseLegend','reverseLegend', 'mintmark',
        'cciNumber', 'reverseType', 'datefound1',
        'datefound2','regionName', 'county',
        'district', 'parish', 'knownas',
        'gridref','fourFigure', 'easting',
        'northing','latitude', 'longitude', 'gridSource',
        'subsequentActionTerm','currentLocation',
        'thumbnail', 'imagedir','filename', 'finder', 'discoveryMethod',
        'creator','institution','created',
        'updated'
	);

    public function fetch($page){
    $this->_params['page'] = $page;
    $this->_search->setFields($this->_csvFields);
    $this->_search->setParams($this->_params);
    $this->_search->execute();
    return $this->_search->_processResults();
    }


    public function create(){
    $this->_search->setFields($this->_csvFields);
    $this->_search->setParams($this->_params);
    $this->_search->execute();
    $data = $this->_search->_processResults();
    $paginator = $this->_search->_createPagination();
    $pages = $paginator->getPages();
    $iterator = $pages->pageCount;

    $converter = new Pas_Exporter_ArrayToCsv($this->_csvFields);
    $clean = $converter->convert($data);
    if($iterator > 300) {
            set_time_limit(0);
            ini_set('memory_limit', '256M');
        }
    $file = fopen('php://temp/maxmemory:'. (12*1024*1024), 'r+');
    fputcsv($file,$this->_csvFields,',','""');
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
      $filename = 'CsvExportFor_' . $this->_user->username . '_' . $this->_dateTime . '.csv';
      $fc = Zend_Controller_Front::getInstance();
      $fc->getResponse()->setHeader('Content-type', 'text/csv; charset=utf-8');
      $fc->getResponse()->setHeader('Content-Disposition', 'attachment; filename=' . $filename);
      echo $output;

    }
}

