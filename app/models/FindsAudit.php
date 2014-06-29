<?php
/**
 * Model for manipulating audit data for finds
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new FindsAudit();
 * $data = $model->getChange($id);
 * ?>
 * </code>
 *
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license GNU General Public License
 * @version 1
 * @since 22 September 2011
 * @example /app/modules/database/controllers/AjaxController.php
*/
class FindsAudit extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'findsAudit';

    /** the primary key
     * @access protected
     * @var string
     */
    protected $_primary = 'id';

    /** get all audited changes on a record
     * @access public
     * @param integer $id
     * @return array
     * @todo add cache
     */
    public function getChanges($id) {
        $finds = $this->getAdapter();
        $select = $finds->select()
                ->from($this->_name,array($this->_name . '.created','recordID','editID'))
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
                        array('id','fullname','username'))
                ->where($this->_name  . '.recordID = ?',(int)$id)
                ->order($this->_name  . '.id DESC')
                ->group($this->_name  . '.editID');
        return $finds->fetchAll($select);
    }

    /** get an audited change set on a record
     * @access public
     * @param integer $id
     * @return array
     * @todo add cache
     */
    public function getChange($id){
        $finds = $this->getAdapter();
        $select = $finds->select()
                ->from($this->_name,array($this->_name . '.created',
                'afterValue','fieldName','beforeValue'))
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
                        array('id','fullname','username'))
                ->where($this->_name  . '.editID = ?',$id)
                ->order($this->_name.'.id');
        return $finds->fetchAll($select);
    }
}