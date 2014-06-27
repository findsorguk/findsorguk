<?php
/** 
 * A model for manipulating error report data
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $model = new ErrorReports();
 * $data = $model->getCount();
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
 * @example /app/modules/admin/controllers/ErrorsController.php
 */
class ErrorReports extends Pas_Db_Table_Abstract {
	
    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'errorreports';
	
    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';
	
    /** Insert error report data
     * @access public
     * @param array $formData
     * @return integer
     */
    public function addReport($formData) {
        return $this->insert($formData);
    }
    
    /** Retrieve a count of the error reports submitted so far
     * @access public
     * @return array
     */
    public function getCount(){
        $messages = $this->getAdapter();
        $select = $messages->select()
                ->from($this->_name,array('total' => 'COUNT(id)'));
        return $messages->fetchAll($select);	
    }

    /** Retrieve all submitted error messages so far
     * @param array $params
     * @return object $paginator
     */
    public function getMessages($params){
        $messages = $this->getAdapter();
        $select = $messages->select()
                ->from($this->_name)
                ->joinLeft('finds','finds.id = comment_findID', 
                        array('broadperiod','objecttype','old_findID'))
                >order($this->_name . '.id DESC');
        $data = $messages->fetchAll($select);
        $paginator = Zend_Paginator::factory($data);
        $paginator->setCache($this->_cache);
        $paginator->setItemCountPerPage(20)->setPageRange(10) ;
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']); 
        }
        return $paginator;
    }
}