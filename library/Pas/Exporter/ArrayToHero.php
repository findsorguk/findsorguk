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
class Pas_Exporter_ArrayToHero
{

    /** Fields to use
     * @access protected
     * @var array
     */
    protected $_fields;

    /** The user's role
     * @access protected
     * @var string
     */
    protected $_role = 'member';

    /** The allowed roles
     * @access protected
     * @var array
     */
    protected $_allowed = array('admin', 'flos', 'fa', 'treasure', 'hoard');

    /** Maybe allowed
     * @access protected
     * @var array
     */
    protected $_maybe = array('hero', 'research');

    /** Never allowed
     * @access protected
     * @var array
     */
    protected $_never = array('member', null, 'public');

    /** Get the user role and the fields
     * @access public
     * @param type $fields
     */
    public function __construct($fields)
    {
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
    public function sortArrayByArray(array $toSort, array $sortByValuesAsKeys)
    {
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
    public function convert($data)
    {
        $remove = array_merge($this->_never, $this->_maybe);

        foreach ($data as $dat) {
            $new = array();
            set_time_limit(0);
            $cleanedArray = array(
                'SecUID' => 'secuid',
                'FindID' => 'old_findID',
                'ObjectType' => 'objecttype',
                'ObjectTypeCertainty' => 'objectCertainty',
                'ObjectDescription' => 'description',
                'ObjectInscription' => 'inscription',
                'notes' => 'notes',
                'DateFrom' => 'datefrom',
                'DateTo' => 'dateto',
                'PeriodFrom' => 'periodFromName',
                'PeriodTo' => 'periodToName',
                'DateTo' => 'todate',
                'DateFrom' => 'fromdate',
                'AscribedCulture' => 'cultureName',
                'PrimaryMaterial' => 'materialTerm',
                'AdditionalMaterial' => 'secondaryMaterialTerm',
                'MethodOfManufacture' => 'manufactureTerm',
                'SurfaceTreatment' => 'treatment',
                'Preservation' => 'preservationTerm',
                'Completeness' => 'completenessTerm',
                'OSRef' => 'gridref',
                'Easting' => 'easting',
                'Northing' => 'northing',
                'Finder' => 'finder',
                'DateFound1' => 'datefound1',
                'DateFound2' => 'datefound2',
                'MethodsOfDiscovery' => 'discoveryMethod',
                'RecordedBy' => 'recorder',
                'PrimaryIdentifier' => 'identifier',
                'SecondaryIdentifier' => 'secondaryIdentifier',
                'CurrentLocation' => 'currentLocation',
                'MuseumAccNo' => 'musaccno',
                'SubsequentAction' => 'subsequentActionTerm',
                'OtherReference' => 'otherRef',
                'CoolFind' => 'note',
                'SMRReference' => 'smrRef',
                'BroadPeriod' => 'broadperiod',
                'WorkflowStage' => 'workflow',
                'Ruler' => 'rulerName',
                'Denomination' => 'denomination',
                'Mint' => 'mintName',
                'CoinType' => 'typeTerm',
                'Moneyer' => 'moneyerName',
                'Obverse_description' => 'obverseDescription',
                'Obverse_inscription' => 'obverseLegend',
                'Reverse_description' => 'reverseDescription',
                'Reverse_inscription' => 'reverseLegend',
                'Reverse_mintmark' => 'mintmark',
                'Die_axis_measurement' => 'axis',
                'Completeness' => 'completenessTerm',
                'FindOfficer' => 'recorder',
                'KnownAs' => 'knownas',
                'IDOfFind' => 'id',
                'Decstyle' => 'decstyleTerm',
                'Denomination' => 'denominationName',
                'FindspotCode' => 'Findspotcode'
            );
            foreach ($cleanedArray as $key => $value) {
                if (array_key_exists($value, $dat)) {
                    $new[$key] = $dat[$value];
                } else {
                    $new[$key] = NULL;
                }
            }
            $new['Initial_mark'] = null;
            $new['SubperiodFrom'] = null;
            $new['SubperiodTo'] = null;
            $new['STATUS'] = null;
            $nullified[] = $new;
        }
        foreach ($nullified AS $null) {
            foreach ($null as $k => $v) {
                $trimmed = trim(strip_tags(str_replace(array('<br />'), array("\n", "\r"), utf8_decode($v))));
                $record[$k] = preg_replace("/\r|\n/", "", $trimmed);
                if (in_array($this->_role, $remove)) {
                    $record['finder'] = 'Restricted info';
                }
                foreach ($record as $k => $v) {
                    if ($v === '' || is_null($v)) {
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