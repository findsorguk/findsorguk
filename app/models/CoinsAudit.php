<?php
/** 
 * Model for creating the audited data for changes to coin records
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $audit = new CoinsAudit();
 * $auditdata = $audit->getChanges($id);
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license 		GNU General Public License
 * @version 		1
 * @since 		22 September 2011
 * @example /library/Pas/View/Helper/ChangesCoins.php 
 */
class CoinsAudit extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'coinsAudit';
    
    /** The primary key
     * @access protected
     * @var int
     */
    protected $_primary = 'id';

    /** Get all changes to a coin record since creation
     * @access public
     * @param int $id 
     * @return array
     */
    public function getChanges($id) {
        $finds = $this->getAdapter();
        $select = $finds->select()
                ->from($this->_name,array($this->_name . '.created', 'recordID', 
                'editID'))
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
                    array('id','fullname','username'))
                ->where($this->_name . '.recordID= ?',(int)$id)
                ->order($this->_name . '.id DESC')
                ->group($this->_name . '.created');
        return  $finds->fetchAll($select);
    }

    /** Get change by id
     * @access public
     * @param int $id
     * @return array
     */
    public function getChange($id) {
        $finds = $this->getAdapter();
        $select = $finds->select()
                ->from($this->_name, array($this->_name . '.created', 'afterValue',
                'fieldName', 'beforeValue'))
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
                array('id','fullname','username'))
                ->where($this->_name . '.editID= ?',$id);
        return $finds->fetchAll($select);
    }
}