<?php
/** 
 * Model for manipulating emperor data
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $emperors = new Emperors();
 * $data = $emperors->getEmperorsAdminList($page);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license GNU General Public License
 * @version 1
 * @since 22 September 2011
 * @todo add caching
 * @example /app/modules/admin/controllers/NumismaticsController.php
 */
class Emperors extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string  
     */
    protected $_name = 'emperors';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get Roman Emperor details by id number
     * @access public
     * @param integer $id
     * @return array
     */
    public function getEmperorDetails($id){
        $key = md5('empdetails' . $id);
        if (!$data = $this->_cache->load($key)) {
            $emperors = $this->getAdapter();
            $select = $emperors->select()
                    ->from($this->_name)
                    ->joinLeft(array('r' => 'reeceperiods'),'r.id = emperors.reeceID', 
                            array('p' => 'period_name'))
                    ->joinLeft(array('d' => 'dynasties'),'d.id = emperors.dynasty', 
                            array('i' => 'id','dyn' => 'dynasty'))
                    ->joinLeft('rulerImages','emperors.pasID = rulerImages.rulerID', 
                            array('filename'))
                    ->where('emperors.id= ?', (int)$id);
            $data = $emperors->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get Roman Emperor reverse types
     * @access public
     * @param integer $id
     * @return array
     */
    public function getEmperorRevTypes($id) {
        $key = md5('emprevs' . $id);
        if (!$data = $this->_cache->load($key)) {
            $emperors = $this->getAdapter();
            $select = $emperors->select()
                    ->from($this->_name,array('name','i' => 'id'))
                    ->joinLeft('rulers','emperors.pasID = rulers.id', array())
                    ->joinLeft('ruler_reversetype','rulers.id = ruler_reversetype.rulerID', array())
                    ->joinLeft('revtypes','revtypes.id = ruler_reversetype.reverseID', array())
                    ->where('revtypes.id = ?', (int)$id)
                    ->order('emperors.date_from');
            $data =$emperors->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get Roman Emperor's available denominations by join on denoms to 
     * emperor table
     * @access public
     * @param integer $id
     * @return array
     */
    public function getDenomEmperor($id) {
        $key = md5('emprevs' . (int)$id);
        if (!$data = $this->_cache->load($key)) {
            $emperors = $this->getAdapter();
            $select = $emperors->select()
                    ->from($this->_name)
                    ->joinLeft('rulers','emperors.pasID = rulers.id', 
                            array('i' => 'id'))
                    ->joinLeft('coins_denomxruler','rulers.id = coins_denomxruler.rulerID', 
                            array())
                    ->joinLeft('denominations','denominations.id = coins_denomxruler.denomID', 
                            array('denomID' => 'id'))
                    ->where('denominations.id = ?', (int)$id)
                    ->order($this->_name . '.' . $this->_primary);
            $data =  $emperors->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get Reece period for a Roman emperor
     * @access public
     * @param integer $id
     * @return array
     */
    public function getReeceDetail($id) {
        $key = md5('reecedetails' . $id);
        if (!$data = $this->_cache->load($key)) {
            $reeces = $this->getAdapter();
            $select = $reeces->select()
                    ->from('emperors', array( 
                        'id', 'issuer' => 'name', 'date_from', 
                        'date_to','image','dbaseID' => 'pasID'
                        ))
                    ->joinLeft(array('r' => 'reeceperiods'),'r.id = emperors.reeceID', 
                            array('period_name', 'description', 'date_range'))
                    ->where('emperors.reeceID = ?', (int)$id)
                    ->order($this->_name . '.' . $this->_primary);
            $data = $reeces->fetchAll($select);
            $this->_cache->save($data, $key);
        }
    }

    /** Get Reece period for a Roman emperor
     * @access public
     * @param integer $id
     * @return array
     */
    public function getEmperorsDynasty($id) {
        $emperors = $this->getAdapter();
        $select = $emperors->select()
                ->from('emperors', array(
                    'id', 'issuer' => 'name', 'date_from',
                    'date_to','image','dbaseID' => 'pasID'
                    ))
                ->where('emperors.dynasty = ?', (int)$id)
                ->order($this->_name . '.' . $this->_primary);
        return $emperors->fetchAll($select);
    }

    /** Get administration list of emperors and paginate it
     * @access public
     * @param integer $page
     * @return array
     */
    public function getEmperorsAdminList($page) {
        $emperors = $this->getAdapter();
        $select = $emperors->select()->from($this->_name)
                ->joinLeft('users',$this->_name . '.createdBy = users.id', 
                        array('fullname'))
                ->joinLeft('users',$this->_name . '.updatedBy = users_2.id', 
                        array('fn' => 'fullname'))
                ->group($this->_name . '.' . $this->_primary)
                ->order($this->_name . '.' . $this->_primary);
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)->setPageRange(10)->setCache($this->_cache);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber((int)$page);
        }
        return $paginator;
    }

    /** Get dynasty to emperors
     * @access public
     * @param integer $id
     * @return array
     */
    public function getDynEmp($id) {
        $key = md5('dynasties' . $id);
        if (!$data = $this->_cache->load($key)) {
        $emperors = $this->getAdapter();
        $select = $emperors->select()
                ->from('emperors', array(
                    'id', 'issuer' => 'name', 'date_from',
                    'date_to', 'dbaseID' => 'pasID', 'dbpedia', 
                    'viaf'
                    ))
                ->joinLeft('rulerImages',$this->_name 
                        . '.pasID = rulerImages.rulerID', 
                        array('image' => 'filename'))
                ->where('emperors.dynasty = ?', $id)
                ->order('emperors.date_from');
        $data = $emperors->fetchAll($select);
        $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Produce a sitemap list of emperors
     * @access public
     * @return array
     */
    public function getEmperorsSiteMap() {
        $key = md5('empsSiteMap');
        if (!$data = $this->_cache->load($key)) {
            $emperors = $this->getAdapter();
            $select = $emperors->select()
                    ->from('emperors', array(
                        'id', 'issuer' => 'name', 'dbaseID' => 'pasID',
                        'updated'
                        ))
                    ->order('date_from');
            $data = $emperors->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return  $data;
    }

    /** Get json data from model for building a timeline of emperors
     * @access public
     * @return array
     */
    public function getEmperorsTimeline(){
        $key = md5('empsTimeline');
        if (!$data = $this->_cache->load($key)) {
            $emperors = $this->getAdapter();
            $select = $emperors->select()->from('emperors')
                    ->order('date_from')
                    ->joinLeft('rulerImages',$this->_name 
                            . '.pasID = rulerImages.rulerID', array( 'filename'))
                    ->where('emperors.image IS NOT NULL');
            $data = $emperors->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return  $data;
    }

    /** Get dynasty to emperors
     * @access public
     * @return type
     */
    public function getEmperors() {
        $key = md5('emperorsJsonList');
        if (!$data = $this->_cache->load($key)) {
            $emperors = $this->getAdapter();
            $select = $emperors->select()
                    ->from('emperors', array(
                        'id', 'issuer' => 'name', 'date_from',
                        'date_to', 'dbaseID' => 'pasID', 'dbpedia', 
                        'viaf'
                        ))
                    ->joinLeft('rulerImages',$this->_name 
                            . '.pasID = rulerImages.rulerID', 
                            array('image' => 'filename'))
                    ->order('emperors.date_from');
            $data = $emperors->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }
}