<?php
/** Model for interacting with subsequent actions table
 *
 * An example of code:
 * <code>
 * <?php
 * $actions = new SubsequentActions();
 * $actionsDD = $actions->getSubActionsDD();
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Zend_Cache
 * @example /app/forms/FindForm.php
*/

class SubsequentActions extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'subsequentActions';

    /** The table key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Retrieve a key value pair list for subsequent actions
     * @access public
     * @return array
     */
    public function getSubActionsDD() {
        $key = md5('subsequentActions');
	if (!$actions = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('id', 'action'))
                    ->order(array('action'));
            $actions = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($actions, $key);
            }
        return $actions;
    }
}