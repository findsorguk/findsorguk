<?php
/** Retrieve and manipulate data from the project type table
 *
 * An example of code:
 *
 * <code>
 * <?php
 * $model = new ProjectTypes();
 * $data = $model->getTypes();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/forms/AcceptUpgradeForm.php
*/
class ProjectTypes extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'projecttypes';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primarykey = 'id';

    /** Get all valid types
     * @access public
     * @return array
     */
    public function getTypes() {
        $select = $this->select()
                ->from($this->_name, array('id', 'title'));
        return $this->getAdapter()->fetchPairs($select);
    }
    /** Get all valid degrees
     * @access public
     * @return array
     */
    public function getDegrees(){
        $select = $this->select()
                ->from($this->_name, array('id', 'title'))
                ->where($this->_name . '.id IN ( 1, 2, 3 )');
        return  $this->getAdapter()->fetchPairs($select);
    }
}