<?php
/** 
 * Model for getting moneyer data (primarily Roman)
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $model = new Moneyers();
 * $data $model->getMoneyers():
 * ?>
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
 * @todo add edit and delete functions and caching
 * @example /app/forms/RomanCoinForm.php 
 */
class Moneyers extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'moneyers';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Retrieve moneyer list with concatenated names and dates
     * @access public
     * @return array
     * @todo add caching
     */
    public function getMoneyers() {
        $moneyers = $this->getAdapter();
        $select = $moneyers->select()
                ->from($this->_name, array(
                    'id', 
                    'term' => new Zend_Db_Expr("CONCAT(name,'(', IFNULL(date_1,''), ' ' , IFNULL(date_2, ''), ')')")
                    ))
                ->order('name ASC');
        return $moneyers->fetchAll($select);
    }

    /** Retrieve moneyer list with concatenated names and dates in key value 
     * pair array
     * @access public
     * @return array
     * @todo add caching
     */
    public function getRepublicMoneyers() {
	$moneyers = $this->getAdapter();
	$select = $moneyers->select()
		->from($this->_name, array(
                    'id',
                    'term' => new Zend_Db_Expr("CONCAT(name,'(', IFNULL(date_1,''), ' ' , IFNULL(date_2, ''), ')')")
                    ))
		->order('name ASC');
	return $moneyers->fetchPairs($select);
    }

    /** Retrieve paginated moneyer list
     * @access public
     * @param array $params
     * @return array
     */
    public function getValidMoneyers($params) {
	$moneyers = $this->getAdapter();
	$select = $moneyers->select()
                ->from($this->_name)
                ->where('valid = ?',(int)1);
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber($params['page']);
	}
	return $paginator;
    }

    /** Retrieve moneyer by ID number
     * @param integer $id
     * @return array
     * @todo change to fetchrow?
     * @todo add caching?
     */
    public function getMoneyer($id) {
	$moneyers = $this->getAdapter();
	$select = $moneyers->select()
		->from($this->_name)
		->where($this->_primary . ' = ?',(int)$id);
	return $moneyers->fetchAll($select);
    }


    /** Retrieve moneyer list with concatenated names and dates in key value
     * pair array
     * @access public
     * @return array
     * @todo add caching
     */
    public function getRepublicMoneyersListed() {
        $moneyers = $this->getAdapter();
        $select = $moneyers->select()
            ->from($this->_name, array('id', 'name', 'date_1', 'date_2', 'valid'))
            ->order('name ASC');
        return $moneyers->fetchAll($select);
    }

}