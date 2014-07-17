<?php
/**  Data model for accessing treasure cases in the database
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $treasure = new TreasureCases();
 * $this->view->cases = $treasure->getCaseHistory($this->_treasureID);
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
 * @example /Users/danielpett/Documents/findsorguk/app/modules/database/controllers/TreasureController.php
 */
class TreasureCases extends Pas_Db_Table_Abstract {

    protected $_primary = 'id';

    protected $_name = 'finds';

    protected $_access = array('fa','flos','admin','treasure');

    /** get a list of Treasure cases in paginated format
     * @access public
     * @param array $params
     * @return \Zend_Paginator
     */
    public function getCases($params){
        $finds = $this->getAdapter();
        $select = $finds->select()
                ->from($this->_name,array('id','old_findID','treasureID','updated'))
                ->joinLeft('findspots','findspots.findID = finds.secuid', array('county'))
                ->where('finds.treasure = ?', (int)1)
                ->order($this->_name . '.treasureID ASC')
                ->group('finds.treasureID');
        if(!in_array($this->getUserRole(), $this->_access)){
            $select->where('finds.secwfstage > ?', (int)2);
        }
        if(isset($params['year'])){
            $select->where($this->_name . '.treasureID LIKE ?',$params['year'].'%');
        }
        $data =  $finds->fetchAll($select);
        $paginator = Zend_Paginator::factory($data);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($params['page']) && ($params['page'] != "")){
            $paginator->setCurrentPageNumber((int)$params['page']);
        }
        return $paginator;
    }

    /** Get a Treasure case's details
     * @access public
     * @param integer $treasureID
     * @return array
     */
    public function getBasicHistory($treasureID){
        $finds = $this->getAdapter();
        $select = $finds->select()
                ->from($this->_name, array('id','old_findID','treasureID','updated'))
                ->joinLeft('findspots','findspots.findID = finds.secuid',array('county'))
                ->where('finds.treasure = ?', (int)1)
                ->where('finds.treasureID = ?',(int)$treasureID);
        return $finds->fetchAll($select);
    }

    /** Get an extended set of a Treasure case's details
     * @access public
     * @param integer $treasureID
     * @return array
     */
    public function getCaseHistory($treasureID){
        $finds = $this->getAdapter();
        $select = $finds->select()
                ->from($this->_name,array('id','old_findID','treasureID','updated','objecttype'))
                ->joinLeft('findspots','findspots.findID = finds.secuid',array('county'))
                ->where('finds.treasure = ?',(int)1)
                ->where('finds.treasureID = ?', (int)$treasureID);
        return $finds->fetchAll($select);
    }
}

