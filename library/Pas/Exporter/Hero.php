<?php
/** An extension of the base generator class for exporting data for HERO use.
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Exporter
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * 
 */
class Pas_Exporter_Hero extends Pas_Exporter_Generate {

    /** The format name
     * @access protected
     * @var string
     */
    protected $_format = 'hero';

    /** The fields exported for the hero export
     * @access protected
     * @var array
     */
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

    /** The exegesis names
     * @access protected
     * @var array
     */
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

    /** Fetch the results based on page number
     * @access public
     * @param integer $page
     * @return array
     */
    public function fetch($page){
        $this->_params['page'] = $page;
        $this->_search->setFields($this->_heroFields);
        $this->_search->setParams($this->_params);
        $this->_search->execute();
        return $this->_search->_processResults();
    }

    /** Create the data to export
     * @access public
     */
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

