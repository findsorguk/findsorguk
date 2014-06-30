<?php
/** Model for getting object terms from database
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $model = new ObjectTerms();
 * $data = $model->getObJectNames();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license GNU General Public License
 * @version 1
 * @since 22 September 2011
 * @todo add edit and delete functions
 * @example /app/modules/datalabs/controllers/TerminologyController.php 
*/
class ObjectTerms extends Pas_Db_Table_Abstract {
	
    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'objectterms';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_id = 'id';

    /** Retrieve all object terms by string query
     * @access public
     * @param string $q
     * @return array
     */
    public function getObjectterm($q) {
        $objectterms = $this->getAdapter();
        $select = $objectterms->select()
                ->from($this->_name, array('id','term'))
                ->where('term LIKE ?', (string)$q.'%')
                ->where('indexTerm = ?','Y')
                ->order('term');
        return $objectterms->fetchAll($select);
    }

    /** Retrieve all object terms
     * @access public
     * @return array
     */
    public function getObjectNames(){
        $key = md5('objectnames');
        if (!$data = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('term'))
                    ->where('indexTerm = ?','Y');
            $data = $this->getAdapter()->fetchAll($select);
            $this->_cache->save($data, $key);
        } 
        return $data;
    }

    /** Retrieve paginated object terms
     * @access public
     * @param array $params
     * @return \Zend_Paginator
     */
    public function getAllObjectData(array $params){
        $objectterms = $this->getAdapter();
        $select = $objectterms->select()
                ->from($this->_name)
                ->order('term');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber( (int)$params['page']); 
        }
        return $paginator;
    }

    /** Retrieve object term details
     * @access public
     * @param string $term
     * @return array
     */
    public function getObjectTermDetail($term) {
        $objectterms = $this->getAdapter();
        $select = $objectterms->select()
                ->from($this->_name)
                ->where('term = ?', (string)$term);
        return $objectterms->fetchAll($select);
    }
}