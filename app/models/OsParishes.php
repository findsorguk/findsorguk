<?php
/** Retrieve and manipulate data from the places listing
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $parishes = new OsParishes();
 * $rallies  = $this->_rallies->getRallyNames($params);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo add caching
 * @example /app/modules/database/controllers/RalliesController.php
 */
class OsParishes extends Pas_Db_Table_Abstract {
    
    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'osParishes';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';


    /** Get the district by county
     * @access public
     * @return array
     */
    public function getParishes(){
        $select = $this->select()
                ->from($this->_name, array(
                    'osID' => 'id','uri', 'type', 
                    'label'
                    ))
                ->order('label');
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve county list again as key pairs
     * @access public
     * @param integer $district
     * @return array
     */
    public function getParishesToDistrict( $district ) {
        $key = md5('parishes' . $district);
        if (!$data = $this->_cache->load( $key )) {
            $select = $this->select()
                    ->from($this->_name, array(
                        'id' => 'osID', 'term' => 'CONCAT(label," (",type,")")'
                        ))
                    ->order('label')
                    ->where('districtID =?', (int) $district);
            $data = $this->getAdapter()->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Retrieve county list again as key pairs.
     * @access public
     * @param integer $district
     * @return array
     */
    public function getParishesToDistrictList( $district ) {
        $key = md5('parishesList' . $district);
        if (!$data = $this->_cache->load( $key )) {
            $select = $this->select()
                    ->from($this->_name, array(
                        'osID', 'CONCAT(label," (",type,")")'
                        ))
                    ->order('label')
                    ->where('districtID =?', (int) $district);
            $data = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Retrieve county list again as key pairs.
    * @return array
    * @todo not sure why duplicate of first function. Fix it!(doofus Dan).
    */
    public function getParishesToCounty( $county ) {
        $key = md5('parishesCounty' . $county);
        if (!$data = $this->_cache->load( $key )) {
            $select = $this->select()
                    ->from($this->_name, array(
                        'osID', 'label' => 'CONCAT(label," (",type,")")'
                        ))
                    ->order('label DESC')
                    ->where('countyID =?', (int) $county);
            $data = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Retrieve county list again as key pairs.
     * @access public
     * @param integer $region
     * @return array
     */
    public function getParishesToRegion( $region ) {
        $key = md5('parishesRegion' . $region);
        if (!$data = $this->_cache->load( $key )) {
            $select = $this->select()
                    ->from($this->_name, array(
                        'osID', 'label' => 'CONCAT(label," (",type,")")'
                        ))
                    ->order('label')
                    ->where('regionID =?', (int) $region);
            $data = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }
}