<?php
/**
 * A model for pulling discovery methods from database 
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $discs = new DiscoMethods();
 * $disc_options = $discs->getOptions();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @todo add caching
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/forms/AdvancedSearchForm.php
 */

class DiscoMethods extends Pas_Db_Table_Abstract {
	
    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'discmethods';
    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';
    
    /** Get key value pairs and cache the result for use in dropdowns for 
     * discovery methods
     * @access public
     * @return array
     */
    public function getOptions() {
        $key = md5('discmethoddd');
	if (!$options = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('id', 'method'))
                    ->order('method ASC')
                    ->where('valid = ?', (int)1);
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, $key);
	}
        return $options;
    }
    
    /** Get discovery method 
     * @access public
     * @param integer $id
     * @return array
     */
    public function getDiscMethod($id) {
        $select = $this->select()
                ->from($this->_name, array('method'))
                ->where('id = ?', (int)$id)
                ->where('valid = ?', (int)1);
        $options = $this->getAdapter()->fetchAll($select);
        return $options;
    }

    /** Get discovery method information
     * @access public
     * @param integer $id
     * @return array
     */
    public function getDiscmethodInformation($id) {
        $methods = $this->getAdapter();
        $select = $methods->select()
                ->from($this->_name, array('id', 'method', 'termdesc'))
                ->where('id = ?', (int)$id)
                ->where('valid = ?', (int)1);
        return $methods->fetchAll($select);
    }

    /** Get discovery methods as a list where valid
     * @access public
     * @return array
     */
    public function getDiscMethodsList() {
        $methods = $this->getAdapter();
        $select = $methods->select()
                ->from($this->_name)
                ->where('valid = ?', (int)1);
        return $methods->fetchAll($select);
    }

    /** Get discovery method information as a list for the administration 
     * console
     * @access public
     * @return array
     */
    public function getDiscMethodsListAdmin() {
        $methods = $this->getAdapter();
        $select = $methods->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
                        array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', 
                        array('fn' => 'fullname'));
        return $methods->fetchAll($select);
    }
}