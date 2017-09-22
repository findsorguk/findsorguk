<?php
/** Retrieve and manipulate data from the places listing
 * 
 * An example of code:
 * <code>
 * <?php
 * $districts = new OsDistricts();
 * $json = $districts->getDistrictsToCounty($term);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo add caching
 * @example /app/modules/default/controllers/AjaxController.php
 */
class OsDistricts extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'osDistricts';

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

    /** Retrieve county list again as key pairs.
     * @access public
     * @return array
     */
    public function getCountiesID() {
        $key = md5('countyIDs');
        if (!$data = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array(
                        'osID', 
                        'label' => new Zend_Db_Expr("CONCAT(label,' (',type,')')")
                        ))
                    ->order('label');
            $data = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Retrieve districts to a county
     * @access public
     * @param integer $county
     * @return array
     */
    public function getDistrictsToCounty( $county ) {
        $key = md5('districtsCounty' . $county);
        if (!$data = $this->_cache->load( $key )) {
            $select = $this->select()
                    ->from($this->_name, 
                            array(
                                'id' => 'osID', 
                                'term' => new Zend_Db_Expr("CONCAT(label,' (',type,')')")
                                ))
                    ->order('label')
                    ->where('countyID =?', (int) $county);
            $data = $this->getAdapter()->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Retrieve district to county list again as key pairs.
     * @access public
     * @param string $county
     * @return array
     */
    public function getDistrictsToCountyList( $county ) {
        $key = md5('districtsCountyList' . $county);
        if (!$data = $this->_cache->load( $key )) {
            $select = $this->select()
                    ->from($this->_name, array('osID', new Zend_Db_Expr("CONCAT(label,' (',type,')')")))
                    ->order('label')
                    ->where('countyID =?', (int) $county);
            $data = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }
}