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
class Pas_Exporter_ArrayToCsv {

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

    /** The base uri
     * @access protected
     * @var string
     */
    protected $_uri = 'http://finds.org.uk/database/artefacts/record/id/';

    /** Construct the object
     * @access public
     * @param array $fields
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
                    if($v === ''){
                        $record[$k] = null;
                    }
                }
                $record['uri'] = $this->_uri . $record['id'];
                if(in_array($this->_role,$this->_never)){
                    $record['gridref'] = null;
                    $record['easting'] = null;
                    $record['northing'] = null;
                    $record['latitude'] = null;
                    $record['longitude'] = null;
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