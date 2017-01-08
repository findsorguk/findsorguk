<?php
/** Data model for accessing treasure actions in the database
 * 
 * An example of code:
 * 
 * <code>
 * <?php
 * $model = new TreasureActions();
 * $data = $model->getActionsListed($id);
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
 * @since  22 October 2010, 17:12:34
 * @example /app/modules/database/controllers/TreasureController.php
 */
class TreasureActions extends Pas_Db_Table_Abstract {

    /** The treasure parameter
     * @access protected
     * @var integer
     */
    protected $_treasureID;

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'treasureActions';

    /** Get the treasure ID parameter
     * @access public
     * @return integer
     */
    public function getTreasureID() {
        $this->_treasureID = Zend_Controller_Front::getInstance()->getRequest()->getParam('treasureID');
        return $this->_treasureID;
    }

    /** Add data to the system for a treasure action
     * @access public
     * @param array $data
     * @return integer
     */
    public function add($data){
        if (!isset($data['created']) || ($data['created'] instanceof Zend_Db_Expr)) {
            $data['created'] = $this->timeCreation();
        }
        $data['createdBy'] = $this->getUserNumber();
        $data['treasureID'] = $this->getTreasureID();
        return parent::insert($data);
    }

    /** Get a list of treasure actions for a specific treasure case
     * @access public
     * @param integer $treasureID
     * @return array
     */
    public function getActionsListed($treasureID){
        $actions = $this->getAdapter();
        $select = $actions->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name 
                        . '.createdBy',array('enteredBy' => 'fullname'))
                ->joinLeft('treasureActionTypes',$this->_name 
                        . '.actionID = treasureActionTypes.id',array('action'))
                ->where('treasureID = ?',(int)$treasureID)
                ->order($this->_name . '.created');
        return $actions->fetchAll($select);
    }
}