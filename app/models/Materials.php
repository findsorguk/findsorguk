<?php
/** Get materials from the thesaurus
 * 
 * An example of code:
 * 
 * <code>
 * $primaries = new Materials();
 * $primary_options = $primaries->getPrimaries();
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo add caching
 * @example /app/forms/AdvancedSearchForm.php
 */
class Materials extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'materials';

    /** The primary key 
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get primary materials
     * @access public
     * @return array
     */
    public function getPrimaries(){
        $key = md5('primaryDD');
        if (!$options = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('id', 'term'))
                    ->where('valid = ?',(int)1)
                    ->order('term ASC');
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, $key);
        }
        return $options;
    }

    /** Get secondary materials
     * @access public
     * @return array
     */
    public function getSecondaries(){
        $key = md5('secondaryDD');
        if (!$options = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('id', 'term'))
                    ->where('valid = ?',(int)1)
                    ->order('term ASC');
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, $key);
        }
        return $options;
    }

    /** Get metals
     * @access public
     * @return array
     */
    public function getMetals() {
        $select = $this->select()
                ->from($this->_name, array('id', 'term'))
                ->where('parentID IN (1,6) AND valid =1')
                ->order('term ASC');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get material name
     * @access public
     * @param integer $id
     * @return array
     */
    public function getMaterialName($id){
        $select = $this->select()
                ->from($this->_name, array('term'))
                ->where('id = ?', (int)$id);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get material name list
     * @access public
     * @return array
     */
    public function getMaterials(){
        $select = $this->select()
                ->from($this->_name)
                ->where('valid = ?',(int)1)
                ->order('id');
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get material name list for admin section
     * @access public
     * @param integer $page
     * @return \Zend_Paginator
     */
    public function getMaterialsAdmin($page){
    
        $materials = $this->getAdapter();
        $select = $materials->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name 
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name 
                        . '.updatedBy', array('fn' => 'fullname'))
                ->order('id');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber((int)$page);
        }
        return $paginator;
    }

    /** Get material details
     * @access public
     * @param integer $id
     * @return array
     */
    public function getMaterialDetails($id) {
        $select = $this->select()
                ->from($this->_name)
                ->where('valid = ?', (int)1)
                ->where('id = ?', (int)$id);
        return $this->getAdapter()->fetchAll($select);
    }
}