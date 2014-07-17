<?php
/**
 * Data model for accessing and manipulating staff roles assigned for the
 * contacts list on website
 *
 * An example of code:
 *
 * <code>
 * <?php
 * $model = new StaffRoles();
 * $data = $model->getOptions();
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
 * @since 		20 August 2010, 12:23:46
 * @todo 		add edit and delete functions
 * @todo 		add caching
 * @example /app/forms/ContactForm.php
*/

class StaffRoles extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'staffroles';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** get key value pairs to populate dropdown lists
     * @access public
     * @return array
     * @todo add caching
     */
    public function getOptions(){
        $select = $this->select()
                ->from($this->_name, array('ID', 'role'))
                ->order('ID ASC');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Retrieve valid roles for system with extended data for updates etc
     * @access public
     * @return array
     * @todo add caching
     */
    public function getValidRoles() {
        $roles = $this->getAdapter();
        $select = $roles->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = '.$this->_name
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = '.$this->_name
                        . '.updatedBy', array('fn' => 'fullname'));
	return $roles->fetchAll($select);
    }

    /** Retrieve valid roles for system with extended data for updates etc
     * @access public
     * @param integer $role The role id number
     * @return array
     * @todo add caching
     */
    public function getRole($role) {
        $roles = $this->getAdapter();
        $select = $roles->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = '
                        . $this->_name . '.updatedBy', array('fn' => 'fullname'))
                ->where($this->_name . '.id = ?', (int)$role);
	return $roles->fetchAll($select);
    }

     /** Retrieve members assigned to a specific role
      * @access public
      * @param integer $role
      * @return array
      */
    public function getMembers($role) {
        $roles = $this->getAdapter();
        $select = $roles->select()
                ->from($this->_name,array())
                ->joinLeft('staff',$this->_name.'.id = staff.role',
                        array(
                            'firstname', 'lastname', 'id',
                            'alumni', 'updated', 'updatedBy'
                            ))
                ->joinLeft('users','users.id = ' . $this->_name
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name
                        . '.updatedBy', array('fn' => 'fullname'))
		->where($this->_name . '.id = ?',(int)$role);
	return $roles->fetchAll($select);
    }
}
