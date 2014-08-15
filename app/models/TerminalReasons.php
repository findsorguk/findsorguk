<?php
/** Model for manipulating terminal coin date reasoning
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new TerminalReasons();
 * $data = $model->getReasons();
 * ?>
 * </code>
 *
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Mary Chester-Kadwell
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
@license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version  1
 * @since  15 August 2014
 * @example /app/forms/HoardForm.php
 */

class TerminalReasons extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'terminalreason';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get all terminal coin dating reasons where valid as key value pairs
     * @access public
     * @return array
     */
    public function getReasons() {
        $select = $this->select()
            ->from($this->_name, array('id', 'reason'))
            ->order('id')
            ->where('valid = ?',(int)1);
        return $this->getAdapter()->fetchPairs($select);
    }

}
