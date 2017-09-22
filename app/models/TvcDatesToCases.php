<?php
/** Data model for accessing treasure valuation dates and cases from link table
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $committees = new TvcDatesToCases();
 * $this->view->tvcs = $committees->listDates($this->_treasureID);
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
 * @example /app/modules/database/controllers/TreasureController.php
 */
class TvcDatesToCases extends Pas_Db_Table_Abstract {

    /** The table primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'tvcDatesToCases';

    /** Add new TVC date/case link to database
     * @access public
     * @param array $data
     * @return integer
     */
    public function add(array $data){
        if (!isset($data['created']) || ($data['created']
                instanceof Zend_Db_Expr)) {
            $data['created'] = $this->timeCreation();
        }
        $data['treasureID'] = Zend_Controller_Front::getInstance()->getRequest()->getParam('treasureID');
        $data['createdBy'] = $this->getUserNumber();
        return parent::insert($data);
    }

    /** List cases for a TVC
     * @access public
     * @param integer $tvcID
     * @return array
     */
    public function listCases($tvcID){
        $tvcs = $this->getAdapter();
        $select = $tvcs->select()
                ->from($this->_name,array('treasureID'))
                ->joinLeft('tvcDates',$this->_name
                        . '.tvcID = tvcDates.secuid', array())
                ->where('tvcDates.id = ?', (int)$tvcID)
                ->order($this->_name . '.' . $this->_primary);
        return $tvcs->fetchAll($select);
    }

    /** List dates for a TVC case
     * @access public
     * @param string $treasureID
     * @return array
     */
    public function listDates($treasureID){
        $tvcs = $this->getAdapter();
        $select = $tvcs->select()
                        ->from($this->_name,array())
                        ->joinLeft('tvcDates',$this->_name
                                . '.tvcID = tvcDates.secuid', array('id','date','location'))
                        ->where($this->_name . '.treasureID = ?', (string)$treasureID)
                        ->order($this->_name . '.' . $this->_primary);
        return $tvcs->fetchAll($select);
    }
}