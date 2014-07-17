<?php
/**
 * A model for getting Roman dynasties from database
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new Dynasties();
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
 * @since 22 September 2011
 * @todo add caching
 * @example path description
 */
class Dynasties extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'dynasties';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get a key value pair list for use in dropdown list for dynasties
     * @access public
     * @return array
     * @todo add caching
     */
    public function getOptions() {
        $select = $this->select()
                ->from($this->_name, array('id', 'dynasty'))
                ->where('valid = ?', (int)1)
                ->order($this->_primary);
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get a list for dynasties in Rome
     * @access public
     * @return array
     * @todo add caching
     */
    public function getDynastyList(){
        $dynasties = $this->getAdapter();
        $select = $dynasties->select()
                ->from($this->_name)
                ->where('valid = ?', (int)1)
                ->order($this->_primary);
        return $dynasties->fetchAll($select);
    }

    /** Get dynasty details for Roman period by id
     * @access public
     * @param integer $id
     * @return array
     * @todo add caching
     */
    public function getDynasty($id) {
        $dynasties = $this->getAdapter();
        $select = $dynasties->select()
                ->from($this->_name)
                ->where('id = ?', (int)$id)
                ->order($this->_primary);
        return $dynasties->fetchAll($select);
    }

    /** Get dynasty list for administation
     * @access public
     * @return array
     * @todo add caching
     */
    public function getDynastyListAdmin(){
        $dynasties = $this->getAdapter();
        $select = $dynasties->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name
                        . '.createdBy',array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name
                        . '.updatedBy',array('fn' => 'fullname'))
                ->order($this->_primary);
        return $dynasties->fetchAll($select);
    }
}