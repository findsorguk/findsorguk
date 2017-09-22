<?php
/** Model for pulling reverse information from db
 *
 * An example of code:
 *
 * <code>
 * <?php
 * $model = new Reverses();
 * $data = $model->getPersonifcations($type);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @todo add edit and delete functions and cache
 * @version 1
 * @example /app/modules/romancoins/controllers/PersonificationsController.php
 */
class Reverses extends Pas_Db_Table_Abstract {

    protected $_name = 'reverses';

    protected $_primaryKey = 'id';

    /** Get reverse personifications by type for Roman period
     * @access public
     * @param string $type
     * @return array
     */
    public function getPersonifications($type) {
        $personify = $this->getAdapter();
        $select = $personify->select()
                ->from($this->_name)
                ->where('type = ?',(string)$type);
        return $personify->fetchAll($select);
    }

    /** Get reverse personifications by name for Roman period
     * @access public
     * @param string $name
     * @return array
     */
    public function getPersonification($name) {
        $personify = $this->getAdapter();
        $select = $personify->select()
                ->from($this->_name)
                ->where('name = ?',(string)$name);
        return $personify->fetchAll($select);
    }
}