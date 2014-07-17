<?php
/** Data model for accessing treasure valuations in the database
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $valuations = new TreasureValuations();
 * $this->view->values = $valuations->listvaluations($this->_treasureID);
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
 * @since 22 October 2010, 17:12:34
 * @example /app/modules/database/controllers/TreasureController.php
 */
class TreasureValuations extends Pas_Db_Table_Abstract {

    /** The table's primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'agreedTreasureValuations';

    /** The ID parameter
     * @access protected
     * @var integer
     */
    protected $_treasureID;

    /** Get the ID
     * @access public
     * @return integer
     */
    public function getTreasureID() {
        $this->_treasureID = Zend_Controller_Front::getInstance()->getRequest()->getParam('treasureID');
        return $this->_treasureID;
    }

    /** Add data to the system for a treasure value
     * @access public
     * @param type $data
     * @return type
     */
    public function add($data){
        if (!isset($data['created']) || ($data['created'] instanceof Zend_Db_Expr)) {
            $data['created'] = $this->timeCreation();

        }
        $data['createdBy'] = $this->getUserNumber();
        $data['treasureID'] = $this->getTreasureID();
        return parent::insert($data);
    }

    /** Update system for a treasure action
     * @access public
     * @param array $data
     * @return integer
     */
    public function updateTreasure(array $data){
        if (!isset($data['updated']) || ($data['updated'] instanceof Zend_Db_Expr)) {
            $data['updated'] = $this->timeCreation();
        }
        $where = parent::getAdapter()->quoteInto('treasureID = ?', $this->getTreasureID());
        $data['updatedBy'] = $this->getUserNumber();
        return parent::update($data, $where);
    }

    /** Get a list of values for a specific treasure ID
     * @access public
     * @param integer $treasureID
     * @return array
     */
    public function listvaluations($treasureID){
        $values = $this->getAdapter();
        $select = $values->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name
                        . '.createdBy',array('enteredBy' => 'fullname'))
                ->joinLeft('people','people.secuid = '. $this->_name
                        . '.valuerID',array('fullname','personID' => 'id'))
                ->where('treasureID = ?',(int)$treasureID)
                ->order($this->_name . '.created');
        return $values->fetchAll($select);
    }

    /** Get a specific list of values for a specific treasure ID
     *  @access public
     * @param integer $treasureID
     * @return array
     */
    public function getValuation($treasureID){
        $values = $this->getAdapter();
        $select = $values->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name
                        . '.createdBy', array('enteredBy' => 'fullname'))
                ->joinLeft('people','people.secuid = '. $this->_name
                        . '.valuerID', array('fullname','personID' => 'id'))
                ->where('treasureID = ?',(int)$treasureID)
                ->order($this->_name . '.created');
        return $values->fetchAll($select);
    }
}