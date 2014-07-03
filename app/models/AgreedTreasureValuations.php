<?php
/** Retrieve treasure valuations from the database
 * An example of code use:
 * 
 * <code>
 * <?php
 * $model = new AgreedTreasureValuations();
 * $data = $model->getValuations($id);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license GNU General Public License
 * @version 1
 * @since 22 September 2011
 */
class AgreedTreasureValuations extends Pas_Db_Table_Abstract {

    /** The treasure ID parameter
     * @access protected
     * @var string
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
    protected $_name = 'agreedTreasureValuations';
    
    /** Get the treasure ID number for queries
     * @access public
     * @return string
     */
    public function getTreasureID() {
        $this->_treasureID = Zend_Controller_Front::getInstance()
                ->getRequest()
                ->getParam('treasureID');
        return $this->_treasureID;
    }
    /** Add a valuation
     * @access public
     * @param array $data
     * @return integer
     */
    public function add(array $data){
        if (!isset($data['created']) || ($data['created'] 
                instanceof Zend_Db_Expr)) {
            $data['created'] = (string)$this->timeCreation();
        }
        $data['createdBy'] = (int)$this->getUserNumber();
        $data['treasureID'] = (string)$this->getTreasureID();
        return parent::insert($data);
    }

    /** Update a valuation
     * @access public
     * @param array $data
     * @return integer
     */
    public function updateTreasure(array $data){
        if (!isset($data['updated']) || ($data['updated'] instanceof Zend_Db_Expr)) {
            $data['updated'] = (string)$this->timeCreation();
        }
        $where = parent::getAdapter()->quoteInto('treasureID = ?', 
                (string)$this->getTreasureID());
        $data['updatedBy'] = (int)$this->getUserNumber();
        return parent::update($data, $where);
    }

    /** List valuations
     * @access public
     * @param string $treasureID
     * @return type
     */
    public function listvaluations($treasureID){
        $select = $this->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name 
                        . '.createdBy', array('enteredBy' => 'fullname'))
                ->where('treasureID = ?',(string)$treasureID)
                ->order($this->_name . '.created');
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get individual valuation
     * @access public
     * @param string $treasureID
     * @return array
     */
    public function getValuation($treasureID){
        $select = $this->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name 
                        . '.createdBy', array('enteredBy' => 'fullname'))
                ->where('treasureID = ?',(string)$treasureID)
                ->order($this->_name . '.created');
        return $this->getAdapter()->fetchAll($select);
    }
}
