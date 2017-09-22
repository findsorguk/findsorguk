<?php
/**
 * Model for creating the audited data for changes to content
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new ContentAudit();
 * $data = $model->getChanges($id);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example path description
 */
class ContentAudit extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'contentAudit';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get all changes to a coin record since creation
     * @access public
     * @param integer $id
     * @return array
     */
    public function getChanges($id) {
        $finds = $this->getAdapter();
        $select = $finds->select()
                ->from($this->_name,array($this->_name . '.created','recordID','editID'))
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
                        array('id','fullname','username'))
                ->where($this->_name . '.recordID= ?',(int)$id)
                ->order($this->_name . '.id DESC')
                ->group($this->_name . '.created');
        return $finds->fetchAll($select);
    }

    /** Get change by id
     * @access public
     * @param integer $id
     * @return array
     */
    public function getChange($id) {
        $finds = $this->getAdapter();
        $select = $finds->select()
                ->from($this->_name, array($this->_name . '.created','afterValue',
                    'fieldName','beforeValue'))
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
                        array('id','fullname','username'))
                ->where($this->_name . '.editID= ?',$id)
                ->order($this->_name . '.' . $this->_primary);
        return $finds->fetchAll($select);
    }
}