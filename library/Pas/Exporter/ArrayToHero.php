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

    /** Restricted field message
     * @access protected
     */
    protected const RESTRICTED_MESSAGE = 'This information is restricted for your access level.';

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
     * @param array $fields
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
    public function convert(array $data): array
    {
        $data = $this->filterExport($data);
        $mappedData = [];
        $mapHerExport = array(
            'SecUID' => 'secuid',
            'FindID' => 'old_findID',
            'ObjectType' => 'objecttype',
            'ObjectTypeCertainty' => 'objectCertainty',
            'ObjectDescription' => 'description',
            'ObjectInscription' => 'inscription',
            'notes' => 'notes',
            'DateFrom' => 'fromdate',
            'DateTo' => 'todate',
            'PeriodFrom' => 'periodFromName',
            'PeriodTo' => 'periodToName',
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
            'Denomination' => 'denominationName',
            'Mint' => 'mintName',
            'CoinType' => 'typeTerm',
            'Moneyer' => 'moneyerName',
            'Obverse_description' => 'obverseDescription',
            'Obverse_inscription' => 'obverseLegend',
            'Reverse_description' => 'reverseDescription',
            'Reverse_inscription' => 'reverseLegend',
            'Reverse_mintmark' => 'mintmark',
            'Die_axis_measurement' => 'axis',
            'FindOfficer' => 'recorder',
            'KnownAs' => 'knownas',
            'IDOfFind' => 'id',
            'Decstyle' => 'decstyleTerm',
            'FindspotCode' => 'Findspotcode',
            'ObjectClassification' => 'classification',
            'ObjectSubClassification' => 'subclass',
            'Notes' => 'notes',
            'ObjectDate1Certainty' => 'periodFromCertainty',
            'ObjectDate2Certainty' => 'periodToCertainty',
            'CalendarDate1Qualifier' => 'dateFromCertainty',
            'CalendarDate2Qualifier' => 'dateToCertainty',
            'reeceID' => 'reeceID',
            'Die_axis_certainty' => 'dieAxisCertainty',
            'Degree_of_wear' => 'wear',
            'length' => 'length',
            'width' => 'width',
            'thickness' => 'thickness',
            'diameter' => 'diameter',
            'weight' => 'weight',
            'quantity' => 'quantity',
            'county' => 'county',
            'district' => 'district',
            'parish' => 'parish',
            'address' => 'address',
            'postcode' => 'postcode',
            'description' => 'findspotDescription',
            'comments' => 'comments',
            'Occupier' => null,
            'FormerFinderID' => 'finderID',
            'DateFound1Qualifier' => null,
            'DateFound2Qualifier' => null,
            'DecmethodObsolete' => null,
            'FormerPhotoReference' => null,
            'FormerDrawingReference' => null,
            'FormerCandidateTerm' => null,
            'ExportedToWeb' => null,
            'FindOfficerFindspot' => 'recorder',
            'RulerQualifier' => 'rulerQualifier',
            'MintQualifier' => 'mintQualifier',
            'DenominationQualifier' => 'denominationQualifier',
            'StatusQualifier' => 'statusQualifier',
            'SpecificLanduse' => null,
            'GeneralLanduse' => null,
            'Wear' => null,
            'EvidenceOfReuse' => 'reuse',
            'CircumstancesofDiscovery' => null,
            'PeriodOfReuse' => null,
            'LandOwner' => 'landowner',
            'STATUS' => 'statusTerm',
            'SubperiodFrom' => 'subperiodFrom',
            'SubperiodTo' => 'subperiodTo'
        );

        foreach ($data as $dat) {
            $new = array();
            set_time_limit(0);

            foreach ($mapHerExport as $key => $value) {
                if (array_key_exists($value, $dat)) {
                    $new[$key] = $dat[$value];
                } else {
                    $new[$key] = null;
                }
            }
            $new['Initial_mark'] = null;
            $mappedData[] = $this->sortArrayByArray($new, $this->_fields);
        }

        return $mappedData;
    }

    /** Clean data, stripping new lines, and setting empty values to null
     * @param array $record Single record
     * @return array Cleaned record
     */
    private function cleanRecordValues(array $record): array
    {
        $cleanedRecord = [];
        foreach($record as $key => $value) {
            //Force empty fields to be empty
            if (empty($value)) {
                $value = null;
            } else {
                //Remove new lines and tags from value
                $value = preg_replace(
                    "/\r|\n/",
                    "",
                    trim(strip_tags(str_replace(array('<br />'), array("\n", "\r"), utf8_decode($value))))
                );
            }
            $cleanedRecord[$key] = $value;
        }
        return $cleanedRecord;
    }

    /** Filter export for users do not have permissions to view fields.
     *
     * While data is filtered by the SensitiveFields() class beforehand, the HER export will still include these fields.
     * This class will set these values to the restricted message constant, informing users of the data filtering for
     * their role.
     *
     * @param array $data Multidimensional array of records
     * @return array|array[] Filtered records, as a multidimensional array
     */
    private function filterExport(array $data): array
    {
        $userPermissions = new UserPermissions();
        $viewFinder = $userPermissions->canRole(userPermissions::VIEW_RECORD_FINDERS);
        $viewIdentifiers = $userPermissions->canRole(userPermissions::VIEW_RECORD_IDENTIFIERS);
        $viewRecorders = $userPermissions->canRole(userPermissions::VIEW_RECORD_RECORDERS);

        return array_map(function (array $record) use ($viewRecorders, $viewIdentifiers, $viewFinder) {
            //Clean value of field
           $record = $this->cleanRecordValues($record);

            if (!$viewFinder) {
                $record['finder'] = self::RESTRICTED_MESSAGE;
            }
            if (!$viewIdentifiers) {
                $record['identifier'] =
                $record['secondaryIdentifier'] =
                    self::RESTRICTED_MESSAGE;
            }
            if (!$viewRecorders) {
                $record['recorder'] = self::RESTRICTED_MESSAGE;
            }
            return $record;
        }, $data);
    }
}