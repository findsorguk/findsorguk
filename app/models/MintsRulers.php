<?php
/** Model for the link table between mints and rulers
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $model = new MintsRulers();
 * $data = $model->getMint($ruler);
 * ?>
 * </code>
 * 
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo add, edit and delete functions to be created and moved from controllers
 * @example /app/modules/admin/controllers/NumismaticsController.php
 */
class MintsRulers extends Pas_Db_Table_Abstract {

    /** The table name
     * @access public
     * @var string
     */
    protected $_name = 'mints_rulers';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Retrieve all mints for a specific ruler
     * @access public
     * @param integer $ruler
     * @return array
     */
    public function getMint($ruler) {
        $mints = $this->getAdapter();
        $select = $mints->select()
                ->from($this->_name, array('id','term' => 'mint_name'))
                ->joinLeft('mints_rulers','mints_rulers.mint_id = mints.id', array())
                ->joinLeft('rulers','rulers.id = mints_rulers.ruler_id', array())
                ->where('rulers.id = ?', (int)$ruler)
                ->order('mints.mint_name ASC');
        return $mints->fetchAll($select);
    }
}
