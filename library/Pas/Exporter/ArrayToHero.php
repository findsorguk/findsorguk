<?php
/** A class for exporting an array of fields to the correct HERO format
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $converter = new Pas_Exporter_ArrayToHero($this->_exegesis);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Exporter
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /library/Pas/Exporter/Hero.php
 *
 */
class Pas_Exporter_ArrayToHero {

   /** Fields to use
     * @access protected
     * @var array
     */
    protected $_fields;

    /** The user's role
     * @access protected
     * @var string
     */
    protected $_role;

    /** The allowed roles
     * @access protected
     * @var array
     */
    protected $_allowed = array('admin','flos','fa','treasure');

    /** Maybe allowed
     * @access protected
     * @var array
     */
    protected $_maybe = array('hero','research');

    /** Never allowed
     * @access protected
     * @var array
     */
    protected $_never = array('member',null,'public');

    /** Get the user role and the fields
     * @access public
     * @param type $fields
     */
    public function __construct($fields){
        $this->_fields = $fields;
        $user = new Pas_User_Details();
        $this->_role = $user->getPerson()->role;
    }


    /** Sort an array by an array
     * @access public
     * @param array $toSort
     * @param array $sortByValuesAsKeys
     * @return array
     */
    public function sortArrayByArray(array $toSort, array $sortByValuesAsKeys){
        $commonKeysInOrder = array_intersect_key(array_flip($sortByValuesAsKeys), $toSort);
        $commonKeysWithValue = array_intersect_key($toSort, $commonKeysInOrder);
        $sorted = array_merge($commonKeysInOrder, $commonKeysWithValue);
        return $sorted;
    }

    /** Convert the data
     * @access public
     * @param array $data
     * @return array
     */
    public function convert($data) {
        $remove = array_merge($this->_never, $this->_maybe);

        foreach($data as $dat){
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
            $dat['Initial_mark'] = null;
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
                $dat[$k] = null;
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
                        $record[$k] = null;
                    }
               }
	}
        $cleanSort = $this->sortArrayByArray($record, $this->_fields);
	$finalData[] = $cleanSort;
        }
        return $finalData;
    }
}