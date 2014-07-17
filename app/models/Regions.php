<?php
/** Data model for accessing and manipulating European regions from database
 *
 * An example of code:
 *
 * <code>
 * <?php
 * $regions = new Regions();
 * $regions = $regions->getRegion($region);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 October 2010, 17:12:34
 * @todo add edit and delete functions
 * @todo add caching
 * @example /library/Pas/View/Helper/SearchParamsUsers.php
*/

class Regions extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'regions';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Retrieve regions as key value pairs
     * @access public
     * @return array
     * @todo add caching
     */
    public function getRegionname() {
        $select = $this->select()
                ->from($this->_name, array('id', 'region'))
                ->order($this->_primary)
                ->where('valid = ?', (int)1);
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Retrieve regions as list
     * @access public
     * @return array
     * @todo add caching
     */
    public function getRegion($region) {
        $regions = $this->getAdapter();
        $select = $regions->select()
                ->from($this->_name, array('region'))
                ->order($this->_primary)
                ->limit('1')
                ->where('id = ?', (int)$region);
        return $regions->fetchAll($select);
    }
}