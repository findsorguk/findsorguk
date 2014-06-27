<?php
/**  
 * Model for describing decorative methods for artefacts
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $decmeths = new DecMethods();
 * $decmeth_options = $decmeths->getDecmethods();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Db_Table
 * @subpackage Abstract
 * @license 		GNU General Public License
 * @version 		1
 * @since 		22 September 2011
 * @example /app/forms/AdvancedSearchForm.php
 */
class DecMethods extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'decmethods';
	
    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primaryKey = 'id';
	

    /** Retrieve a key pair list of decoration methods for dropdown usage as 
     * key value pairs
     * @access public
     * @return array
     */
    public function getDecmethods() {
        $key = md5('decmethoddd');
        if (!$options = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('id', 'term'))
                    ->order('id')
                    ->where('valid = ?', (int)1);
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, $key);
        }
        return $options;
    }
	
    /** Retrieve a list of decoration methods for dropdown usage
     * @access public
     * @return array
     */
    public function getDecorationDetailsList(){
	$methods = $this->getAdapter();
	$select = $methods->select()
		->from($this->_name)
		->where('valid = ?', (int)1)
		->order('id');
	return $methods->fetchAll($select);
    }

    /** Retrieve a list of decoration methods for dropdown usage as admin
     * @access public
     * @return array
     */
    public function getDecorationDetailsListAdmin() {
	$methods = $this->getAdapter();
	$select = $methods->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
                        array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', 
                        array('fn' => 'fullname'))
		->order('id');
	return $methods->fetchAll($select);
    }
    
    /** Retrieve details of decoration method
     * @access public
     * @param integer $id
     * @return array
     */
    public function getDecorationDetails($id){
	$methods = $this->getAdapter();
	$select = $methods->select()
		->from($this->_name)
		->where('valid = ?', (int)1)
		->where($this->_name . '.id = ?', (int)$id);
	return $methods->fetchAll($select);
    }

    /** retrieve a count of objects with a specific decoration method
     * @access public
     * @param integer $id
     * @return array
     */
    public function getDecCount($id) {
	$methods = $this->getAdapter();
	$select = $methods->select()
                ->from($this->_name)
		->joinLeft('finds','finds.decmethod = ' . $this->_name . '.id' ,
                        array('c' => 'count(finds.id)'))
		->where('valid = ?',(int)1)
		->where($this->_name . '.id = ?',(int)$id)
		->group($this->_name . '.id');
	return $methods->fetchAll($select);
    }
}