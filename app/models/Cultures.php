<?php
/**
 * A model for listing all the ascribed cultures in use on the database
 * An example of use:
 * 
 * <code>
 * <?php
 * $model = Cultures();
 * $data = $model->getCulture($id);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license GNU General Public License
 * @version 1
 * @since 22 September 2011
 * @todo add caching
 * @example /app/forms/FindForm.php
*/

class Cultures extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var type 
     */
    protected $_name = 'cultures';
    
    /** The table's primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get a list of all ascribed cultures as key pair values
     * @access public
     * @return array
     */
    public function getCultures() {
        $select = $this->select()
                ->from($this->_name, array('id', 'term'))
                ->order('id');
        return $this->getAdapter()->fetchPairs($select);
    }
    
    /** Get a list of all ascribed cultures as an array
     * @access public
     * @return array
     */
    public function getCulturesList() {
        $select = $this->select()
                ->from($this->_name, array('id', 'term','termdesc'))
                ->where('valid = ?', (int)1)
                ->order('id');
        return $this->getAdapter()->fetchAll($select);
    }
	
    /** Get an admin list of ascribed cultures
     * @access public
     * @return array
     */
    public function getCulturesListAdmin() {
        $options = $this->getAdapter();
        $select = $options->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
                        array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', 
                        array('fn' => 'fullname'))
                ->order('id');
        return $options->fetchAll($select);
    }

    /** Get a cultural detail via the id number
     * @access public
     * @param integer $id The id number of the ascribed culture
     * @return array
     */
    public function getCulture($id) {
        $select = $this->select()
                ->from($this->_name, array('id', 'term', 'termdesc'))
                ->where('valid = ?', (int)1)
                ->where('id = ?', (int)$id)
                ->order('id');
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get a count of finds for a specific cultural ID
     * @access public
     * @param integer $id
     * @return array 
     */
    public function getCultureCountFinds($id){
        $reasons = $this->getAdapter();
        $select = $reasons->select()
                ->from('finds',array('c' => 'COUNT(finds.id)'))
                ->where('finds.culture =?', (int)$id);
        return  $reasons->fetchAll($select);
    }
}