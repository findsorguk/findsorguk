<?php
/**
 * Data model for accessing treasure assignations in the database
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $curators = new TreasureAssignations();
 * $this->view->curators = $curators->listCurators($this->_treasureID);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/database/controllers/TreasureController.php
*/

class TreasureAssignations extends Pas_Db_Table_Abstract {

    /** The treasure ID number
     * @access protected
     * @var integer
     */
    protected  $_treasureID;

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'treasureAssignations';

    public function getTreasureID() {
        $this->_treasureID = Zend_Controller_Front::getInstance()->getRequest()->getParam('treasureID');
        return $this->_treasureID;
    }


    /** Add data to the system for a treasure action
     * @access public
     * @param array $data
     * @return integer
     */
    public function add(array $data){
        if (!isset($data['created']) || ($data['created'] instanceof Zend_Db_Expr)) {
            $data['created'] = $this->timeCreation();
        }
        $data['createdBy'] = $this->getUserNumber();
        $data['treasureID'] = $this->getTreasureID();
        return parent::insert($data);
    }

    /** List curators assigned to a case by treasure ID
     * @access public
     * @param integer $treasureID
     * @return array
     */
    public function listCurators($treasureID){
        $values = $this->getAdapter();
        $select = $values->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name
                        . '.createdBy', array('enteredBy' => 'fullname'))
                ->joinLeft('people','people.secuid = '. $this->_name
                        . '.curatorID', array('fullname','personID' => 'id'))
                ->where('treasureID = ?',(int)$treasureID)
                ->order($this->_name . '.created');
        return $values->fetchAll($select);
    }
}