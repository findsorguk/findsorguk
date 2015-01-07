<?php
/** Retrieve and manipulate data from the places listing
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $counties = new OsCounties();
 * $county_options = $counties->getCountiesID();
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
 * @since 22 September 2013
 * @example /app/forms/AdvancedSearchForm.php
 * @uses Zend_Cache
 */
class OsCounties extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'osCounties';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get the district by county
     * @access public
     * @return array
     */
    public function getCounties(){
        $select = $this->select()
                ->from($this->_name, array('osID' => 'id','uri', 'type', 'label'))
                ->order('label');
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve county list as key pairs for labels
     * @access public
     * @return array
     */
    public function getCountyNames(){
        $key = md5('countyNamesList');
        if (!$data = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('label', new Zend_Db_Expr("CONCAT(label,' (',type,')')")))
                    ->order('label');
            $data = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** retrieve county list again as key pairs.
     * @access public
     * @return type
     */
    public function getCountiesID() {
        $key = md5('countyIDs');
        if (!$data = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('osID', new Zend_Db_Expr("CONCAT(label,' (',type,')')")))
                    ->order('label');
            $data = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Retrieve county list again as key pairs.
     * @access public
     * @param string $county
     * @return array
     */
    public function getCountyToRegion( $county ) {
        $key = md5('countyRegion' . $county);
        if (!$data = $this->_cache->load( $key )) {
            $table = $this->getAdapter();
            $select = $table->select()
                    ->from($this->_name, array())
                    ->joinLeft('osRegions', 
                            'osRegions.osID = osCounties.regionID',
                            array(
                                'id' => 'osID', 
                                'term' => new Zend_Db_Expr("CONCAT(osRegions.label,' (',osRegions.type,')')")
                                ))
                ->where('osCounties.osID =?', (int) $county);
            $data = $table->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Retrieve county list again as key pairs.
     * @access public
     * @param string $county
     * @return array
     */
    public function getCountyToRegionList( $county )  {
        $key = md5('countyRegionList' . $county);
        if (!$data = $this->_cache->load( $key )) {
            $table = $this->getAdapter();
            $select = $table->select()
                    ->from($this->_name, array())
                    ->joinLeft('osRegions', 
                            'osRegions.osID = osCounties.regionID',
                            array(
                                'osID',
                                new Zend_Db_Expr("CONCAT(osRegions.label,' (',osRegions.type,')')")
                                ))
                    ->where('osCounties.osID =?', (int) $county);
            $data = $table->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }
}