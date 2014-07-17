<?php
/** Data model for accessing treasure valuation committee dates
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $dates = new TvcDates();
 * $list = $dates->dropdown();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/forms/TVCDateForm.php
 */
class TvcDates extends Pas_Db_Table_Abstract {
	
    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';
	
    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'tvcDates';
	
    /** Add new TVC date to database
    * @param array $data
    * @return boolean
    */
    public function add($data){
        if (!isset($data['created']) || ($data['created'] instanceof Zend_Db_Expr)) {
            $data['created'] = $this->timeCreation();
        }
        $generator = new Pas_Generator_SecuID();
        $data['secuid'] = $generator->secuid();
        $data['createdBy'] = $this->getUserNumber();
        return parent::insert($data);
    }
	
    /** Get a paginated list of TVC dates
     * @access public
     * @param integer $page
     * @return \Zend_Paginator
     */
    public function listDates($page){
        $tvcs = $this->getAdapter();
        $select = $tvcs->select()
                ->from($this->_name)
                ->joinLeft('tvcDatesToCases','tvcDatesToCases.tvcID = ' 
                        . $this->_name . '.secuid', 
                        array('total' => 'COUNT(*)' ))
                ->order($this->_name . '.date DESC')
                ->group($this->_name . '.' . $this->_primary);
        $data =  $tvcs->fetchAll($select);
        $paginator = Zend_Paginator::factory($data);
        Zend_Paginator::setCache($this->_cache);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber((int)$page); 
        }
        $paginator->setItemCountPerPage(20)->setPageRange(10); 
        return $paginator;
    }

    /** Get a specific treasure vc date
     * @access public
     * @param integer $id
     * @return array
     */
    public function getDetails($id){
        $tvcs = $this->getAdapter();
        $select = $tvcs->select()
                ->from($this->_name)
                ->where($this->_name . '.id = ?',(int)$id);
        return $tvcs->fetchAll($select);	
    }

    /** Get a key value list for dropdown 
     * @access public
     * @return array
     */
    public function dropdown(){
        $tvcs = $this->getAdapter();
        $select = $tvcs->select()
                ->from($this->_name,array('secuid','date'))
                ->order($this->_name . '.date DESC');
        return $tvcs->fetchPairs($select);
    }
}

