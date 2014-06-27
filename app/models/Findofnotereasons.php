<?php
/** Model for manipulating find of note reasoning 
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $model = new FindOfNoteReasons();
 * $data = $model->getReasons();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license GNU General Public License
 * @version  1
 * @since  22 September 2011
 * @example /app/forms/AdvancedSearchForm.php
*/

class FindOfNoteReasons extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'findofnotereasons';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';
	
    /** Get all find of note reasons where valid as key value pairs
     * @access public
     * @return array
     */
    public function getReasons() {
        $select = $this->select()
                ->from($this->_name, array('id', 'term'))
                ->order('id')
                ->where('valid = ?',(int)1);					   
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get all find of note reasons as a list
     * @access public
     * @return type
     */
    public function getReasonsList() {
        $reasons = $this->getAdapter();
        $select = $reasons->select()
                ->from($this->_name)
                ->where('valid = ?',(int)1)
                ->order('id');
        return $reasons->fetchAll($select);
    }

    /** Get all find of note reasons as a list for the admin interface
     * @access public
     * @return type
     */
    public function getReasonsListAdmin() {
        $reasons = $this->getAdapter();
        $select = $reasons->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name.'.createdBy', 
                        array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name.'.updatedBy', 
                        array('fn' => 'fullname'))			
                ->order('id');
        return $reasons->fetchAll($select);
    }

    /** Get find of note reason details
     * @access public
     * @param integer $id
     * @return array
     */
    public function getReasonDetails($id) {
        $reasons = $this->getAdapter();
        $select = $reasons->select()
                ->from($this->_name)
                ->where('valid = ?',(int)1)
                ->where('id = ?',(int)$id);
        return $reasons->fetchAll($select);
    }
}
