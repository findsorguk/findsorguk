<?php
/** Model for interacting with a link table for rallies to flos
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new RallyXFlo();
 * $data = $model->getStaff($rally);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license GNU General Public License
 * @version 1
 * @since 22 September 2011
 * @todo add edit and delete functions and cache
 * @example /app/modules/database/controllers/RalliesController.php
 */
class RallyXFlo extends Pas_Db_Table_Abstract {

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primaryKey = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'rallyXflo';

    /** Get staff attending a specific rally
     * @access public
     * @param integer $id
     * @return array
     */
    public function getStaff($id) {
        $staff = $this->getAdapter();
        $select = $staff->select()
                ->from($this->_name, array('datefrom', 'dateto', 'rallyID'))
                ->joinLeft('users',$this->_name . '.staffID = users.id',
                        array( 'fullname', 'last_name', 'id' ))
                ->where($this->_name . '.rallyID = ?', (int)$id)
                ->order('last_name');
       return $staff->fetchAll($select);
    }
}