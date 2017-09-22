<?php
/** Retrieve and manipulate data from the places listing
 * 
 * An example of use:
 * <code>
 * <?php
 * $regions = new OsRegions();
 * $region_options = $regions->getRegionsID();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo add caching
 * @example /app/forms/AdvancedSearchForm.php
 * @uses Zend_Cache
 */
class OsRegions extends Pas_Db_Table_Abstract {
    
    /** The table name
     * @access protected
     * @var type 
     */
    protected $_name = 'osRegions';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get the district by county
     * @access public
     * @return array
     */
    public function getRegions(){
        $select = $this->select()
                ->from($this->_name, array('osID' => 'id','uri', 'type', 'label'))
                ->order('label');
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve region list again as key pairs.
     * @access public
     * @return array
     */
    public function getRegionsID() {
        $key = md5('regionIDs');
        if (!$data = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('osID', new Zend_Db_Expr("CONCAT(label,' (',type,')')")))
                    ->order('label');
            $data = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }
}