<?php
/** Monarchs model for pulling data from monarchs table
 * 
 * An example of use:
 * <code>
 * <?php
 * $monarchs = new Monarchs();
 * $update = $monarchs->insert($insertData);
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
 * @example /app/modules/admin/controllers/NumismaticsController.php
 */
class Monarchs extends Pas_Db_Table_Abstract {
    
    /** The table name
     * @access protected
     * @var string  
     */
    protected $_name = 'monarchs';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Retrieve monarch biography
     * @access public
     * @param integer $id
     * @return array
     */

    public function getBiography($id) {
        $monarchs = $this->getAdapter();
        $select = $monarchs->select()
                ->from($this->_name, array('id', 'biography', 'dbaseID'))
                ->where('monarchs.dbaseID = ?',(int)$id);
        return $monarchs->fetchAll($select);
    }
}