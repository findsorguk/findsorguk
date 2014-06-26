<?php
/** Model for interacting with status for coins table
 *
 * An example of code:
 *
 * <code>
 * <?php
 *
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage 	Abstract
 * @license GNU General Public License
 * @todo add edit and delete functions
 * @example /app/forms/ByzantineCoinForm.php
 *
*/
class Statuses extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'statuses';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Retrieve a key value pair list for coin status dropdown list
     * @access public
     * @return Array
     */
    public function getCoinStatus() {
        $select = $this->select()
                ->from($this->_name, array('id', 'term'))
                ->order('id');
        return $this->getAdapter()->fetchPairs($select);
    }
}