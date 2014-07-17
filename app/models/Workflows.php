<?php
/**
 * Model for interacting with workflow
 *
 * An example of code:
 *
 * <code>
 * <?php
 * $model = new Workflows();
 * $data = $model->getStageNames();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @version 1
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/admin/controllers/TerminologyController.php
 */
class Workflows extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'workflowstages';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Retrieve a key value pair list of workflows for use in dropdowns
     * @access public
     * @return array
     */
    public function getUses() {
        $select = $this->select()
                ->from($this->_name, array('id', 'workflowstage'))
                ->where($this->_name.'.valid = ?', (int)1)
                ->order('workflowstage ASC');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Retrieve workflow stage details by ID
     * @access public
     * @param integer $stage
     * @return array
     */
    public function getStageName($stage) {
        $select = $this->select()
                ->from($this->_name)
                ->where('id = ?',(int)$stage)
                ->where($this->_name.'.valid = ?',(int)1)
                ->limit(1);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve stage names for valid workflow stages
     * @access public
     * @return array
     */
    public function getStageNames()  {
        $select = $this->select()
                ->from($this->_name)
                ->order('workflowstage ASC')
                ->where($this->_name . '.valid = ?', (int)1);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve stage names for admin section
     * @access public
     * @return array
     */
    public function getStageNamesAdmin() {
        $stages = $this->getAdapter();
        $select = $stages ->select()
                ->from($this->_name)
                ->order('workflowstage ASC')
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
                        array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',
                        array('fn' => 'fullname'));
        return $stages->fetchAll($select);
    }

    /** Retrieve stage counts for telling people to work harder!
     * @access public
     * @param integer $stage workflow stage
     * @return array
     */
    public function getStageCounts($stage) {
        $stages = $this->getAdapter();
        $select = $stages->select()
                ->from($this->_name, array('id', 'workflowstage'))
                ->joinLeft('finds',$this->_name . '.id = finds.secwfstage',
                        array('c' => 'count(*)'))
                ->order('workflowstage ASC')
                ->where($this->_name . '.id = ?',(int)$stage)
                ->where($this->_name . '.valid = ?',(int)1)
                ->group($this->_name . '.id');
        return $stages->fetchAll($select);
    }
}
