<?php

/** A class for exporting an array to a csv file
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $converter = new Pas_Exporter_ArrayToCsv($this->_csvFields);
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Exporter
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /library/Pas/Exporter/Csv.php
 */
class Pas_Exporter_ArrayToCsv
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
    protected $_role;

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

    /** The base uri
     * @access protected
     * @var string
     */
    protected $_uri = 'https://finds.org.uk/database/artefacts/record/id/';

    /** Construct the object
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
    public function convert($data)
    {
        $remove = array_merge($this->_never, $this->_maybe);
        $nullified = array();
        foreach ($data as $dat) {
            foreach ($this->_fields as $k) {
                if (!array_key_exists($k, $dat)) {
                    $dat[$k] = null;
                }
            }
            $nullified[] = $dat;
        }

        $record = array();
        foreach ($nullified AS $null) {
            foreach ($null as $k => $v) {
                $trimmed = trim(strip_tags(str_replace(array('<br />'), array("\n", "\r"), utf8_decode($v))));
                $record[$k] = preg_replace( "/\r|\n/", "", $trimmed );

                if (in_array($this->_role, $remove)) {
                    $record['finder'] = 'Restricted info';
                }
                foreach ($record as $k => $v) {
                    if ($v === '') {
                        $record[$k] = null;
                    }
                }

                if (in_array($this->_role, $this->_never)) {
                    $record['gridref'] = null;
                    $record['easting'] = null;
                    $record['northing'] = null;
                    $record['latitude'] = null;
                    $record['longitude'] = null;
                    if (!is_null($record['knownas'])) {
                        $record['parish'] = 'Restricted access';
                        $record['fourFigure'] = 'Restricted access';
                    }
                }
            }
            $record['uri'] = $this->createUri( $record['objectType'], $record['id']);

            $cleanSort = $this->sortArrayByArray($record, $this->_fields);
            $finalData[] = $cleanSort;
        }
        return $finalData;
    }

    public function createUri( $objectType, $id)
    {
        if($objectType != 'HOARD'){
            $module = 'artefacts';
        } else {
            $module = 'hoards';
        }
        return 'https://finds.org.uk/database/' . $module . '/record/id/' . $id;
    }
}