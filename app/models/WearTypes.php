<?php
/**
 * Data model for accessing wear types for coins
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new WearTypes();
 * $data = $model->getWears();
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
 * @since 22 October 2010, 17:12:34
 * @example app/forms/ByzantineCoinForm.php
*/

class WearTypes extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'weartypes';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get list of wear types for coins as key value pair array
     * @access public
     * @return array
     */
    public function getWears() {
        $select = $this->select()
                ->from($this->_name, array('id', 'term'))
                ->order('term');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get list of wear types for coins
     * @access public
     * @param integer $id
     * @return array
     */
    public function getWearType($id) {
	$select = $this->select()
                ->from($this->_name, array('id', 'term'))
		->where('id = ?', (int)$id);
	return $this->getAdapter()->fetchAll($select);
    }

     /** Get list of wear types for coins admin interface
      * @access public
      * @return array
      */
    public function getWearTypesAdmin() {
        $wears = $this->getAdapter();
	$select = $wears->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
                        array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',
                        array('fn' => 'fullname'));
	return $wears->fetchAll($select);
    }
}
