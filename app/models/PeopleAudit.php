<?php
/** Model for auditing changes to personal data entries
 * 
 * An example of code:
 * 
 * <code>
 * <?php
 * $model = new PeopleAudit();
 * $data = $model->getChange($id);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo add edit and delete functions
 * @example /app/modules/database/controllers/PeopleController.php
*/

class PeopleAudit extends Pas_Db_Table_Abstract {

    /** The table name
     * @access public
     * @var string
     */
    protected $_name = 'peopleAudit';

    /** The primary key
     * @access public
     * @var integer
     */
    protected $_primaryKey = 'id';

    /** Get all audited changes for one person
     * @access public
     * @param integer $personID
     * @return array
     */
    public function getChanges($personID) {
        $finds = $this->getAdapter();
        $select = $finds->select()
                ->from($this->_name,array(
                    $this->_name . '.created',
                    'recordID',
                    'editID'))
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
                        array('id','fullname','username'))
                ->where($this->_name . '.secuid= ?',(int)$personID)
                ->order($this->_name . '.id DESC')
                ->group($this->_name . '.created');
        return $finds->fetchAll($select);
    }

    /** Get audited personal changes by edit number
     * @access public
     * @param integer $editID
     * @return array
     */
    public function getChange($editID) {
        $finds = $this->getAdapter();
        $select = $finds->select()
                ->from($this->_name,array(
                    $this->_name . '.created',
                    'afterValue' ,
                    'fieldName',
                    'beforeValue'))
                ->joinLeft('users','users.id = ' . $this->_name.'.createdBy',
                        array('id', 'fullname', 'username'))
                ->where($this->_name . '.editID= ?', (int)$editID)
                ->order($this->_name . '.' . $this->_primaryKey);
        return $finds->fetchAll($select);
    }

}