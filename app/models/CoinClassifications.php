<?php 
/** Model for pulling coin classifications
 * I have no idea why this is different to the Coins Classifications model! 
 * 
 * An example of use:
 * <code>
 * <?php
 * $refs = new CoinClassifications();
 * $ref_list = $refs->getClass();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category   Pas
 * @package    Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/forms/ReferenceCoinForm.php
 */
class CoinClassifications extends Pas_Db_Table_Abstract {

    /** The primary key
     * @access protected
     * @var int
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'coinclassifications';

	
    /** Get all valid references for coin classifications as a dropdown
     * @access public
     * @return array
     */
    public function getClass() {
        $key = md5('classificationsdd');
        if (!$options = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('id', 'referenceName'))
                    ->order('id')
                    ->where('valid = ?',(int)1);
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, $key);
        }
        return $options;
    }

    /** Get all valid references for coin classifications
     * @access public
     * @param int $id
     * @return array
     */
    public function getAllClasses($id) {
        $key = md5('classificationscoins' . $id);
        if (!$data = $this->_cache->load($key)) {
            $coins = $this->getAdapter();
            $select = $coins->select()
                    ->from($this->_name,array('referenceName'))
                    ->joinLeft('coinxclass','coinxclass.classID = coinclassifications.id', 
                            array('vol_no','reference','id'))
                    ->joinLeft('finds','finds.secuid =  coinxclass.findID', array('returnID' => 'id'))
                    ->where('finds.id = ?' ,(int)$id);
            $data = $coins->fetchAll($select);
            $this->_cache->save($data, $key);
	}
	return $data;
    }
    
    /** Get all valid references for coin classifications
     * @access public
     * @return array
     */
    public function getRefs() {
        $references = $this->getAdapter();
        $select = $references->select()
                ->from($this->_name, array('id','referenceName','valid'))
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'))
                ->joinLeft('periods',$this->_name . '.period = periods.id', array('term'));
        return $references->fetchAll($select); 
    }

    /** Get all valid references for coin classifications as a dropdown
     * @access public
     * @return array
     */
    public function getRefsByPeriod() {
        $references = $this->getAdapter();
        $select = $references->select()
                ->from($this->_name, array('id','referenceName'))
                ->where($this->_name . '.valid = ?',(int)1);
        return $references->fetchPairs($select); 
    }
}