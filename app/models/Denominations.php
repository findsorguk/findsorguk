<?php
/**
 *  A model for pulling denominations from the database
 * 
 * An example of code:
 * <code>
 * <?php
 * $denominations = new Denominations();
 * $denomination_options = $denominations->getDenomsByzantine();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo add caching and amalgamate some functions (duplication!!!)
 * @example /app/forms/ByzantineCoinForm.php
 */
class Denominations extends Pas_Db_Table_Abstract {

    /** The table's name
     * @access protected
     * @var string
     */
    protected $_name = 'denominations';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get denomination by period as a list
     * @access public
     * @param integer $id
     * @return array
     */
    public function getDenByPeriod($id) {
        $denoms = $this->getAdapter();
        $select = $denoms->select()
                ->from($this->_name)
                ->where($this->_name . '.valid = ?', (int)1)
                ->where($this->_name . '.period = ?', (int)$id)
                ->order('denomination');
        return $denoms->fetchAll($select);
    }

    /** Retrieve a key pair list of roman denominations
     * @access public
     * @return array
     */
    public function getOptionsRoman() {
        $select = $this->select()
                ->from($this->_name, array('id', 'denomination'))
                ->where('period = ?',(int)21)
                ->order('denomination');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get a list of Iron Age denominations as key pairs for dropdowns
     * @access public
     * @return array
     */
    public function getOptionsIronAge() {
        $select = $this->select()
                ->from($this->_name, array('id', 'denomination'))
                ->where('period = ?', (int)16)
                ->order('denomination');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get a list of Early medieval denominations as key pairs for dropdowns
     * @access public
     * @return array
     */
    public function getOptionsEarlyMedieval() {
        $select = $this->select()
                ->from($this->_name, array('id', 'denomination'))
                ->where('period = ?', (int)47)
                ->order('denomination');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get a list of Medieval denominations as key pair values
     * @access public
     * @return array
     */
    public function getOptionsMedieval() {
        $select = $this->select()
                ->from($this->_name, array('id', 'denomination'))
                ->where('period = ?', (int)29)
                ->order('denomination');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get a list of post medieval denominations as key pair values
     * @access public
     * @return array
     */
    public function getOptionsPostMedieval() {
        $select = $this->select()
                ->from($this->_name, array('id', 'denomination'))
                ->where('period = ?', (int)36)
                ->where('valid = ?', (int)1)
                ->order('denomination');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get a list of Greek denominations
     * @access public
     * @return array
     */
    public function getDenomsGreek() {
        $select = $this->select()
                ->from($this->_name, array('id', 'denomination'))
                ->where('period = ?',(int)66)
                ->where('valid = ?',(int)1)
                ->order('denomination');
        return $this->getAdapter()->fetchPairs($select);
    }
    
    /** Get a list of Byzantine denominations as key pair values
     * @access public
     * @return array
     */
    public function getDenomsByzantine() {
        $select = $this->select()
                ->from($this->_name, array('id', 'denomination'))
                ->where('period = ?', (int)67)
                ->where('valid = ?', (int)1)
                ->order('denomination');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get a list of Roman rulers and denominations
     * @access public
     * @param integer $id
     * @return array
     */
    public function getRomanRulerDenom($id) {
        $select = $this->select()
                ->from($this->_name, array('id', 'term' => 'denomination'))
                ->joinLeft('denominations_rulers', 'denominations.id = denominations_rulers.denomination_id',
                array())
                ->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id', array())
                ->where('denominations_rulers.ruler_id= ?', $id);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get an admin list of rulers to denominations
     * @access public
     * @param integer $id
     * @return array
     */
    public function getRomanRulerDenomAdmin($id) {
        $options = $this->getAdapter();
        $select = $options->select()
                ->from($this->_name, array('id', 'term' => 'denomination'))
                ->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',
                array('created', 'linkid' => 'id'))
                ->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id', array())
                ->joinLeft('users','users.id = denominations_rulers.createdBy', array('fullname'))
                ->joinLeft('periods','periods.id = denominations.period', array('period' => 'term'))
                ->where('denominations_rulers.ruler_id= ?', (int)$id)
                ->order('denomination');
        return $options->fetchAll($select);
    }

    /** Get a list of early medieval rulers to denominations
     * @access public
     * @param integer $id
     * @return array
     */
    public function getEarlyMedRulerDenom($id) {
        $select = $this->select()
                ->from($this->_name, array('id', 'term' => 'denomination'))
                ->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',
                array())
                ->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id',array())
                ->where('denominations_rulers.ruler_id= ?', (int)$id)
                ->group('denomination');
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get a list of early medieval rulers and their denominations
     * @access public
     * @param integer $id
     * @return array
     */
    public function getEarlyMedRulerToDenomination($id) {
        $select = $this->select()
                ->from($this->_name, array('id',  'denomination'))
                ->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',
                array())
                ->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id',array())
                ->where('denominations_rulers.ruler_id= ?',(int)$id)
                ->group('denomination');
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get a key value pair list of early medieval rulers and denominations
     * @access public
     * @param integer $id
     * @return array
     */
    public function getEarlyMedRulerToDenominationPairs($id) {
        $select = $this->select()
                ->from($this->_name, array('id',  'denomination'))
                ->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',
                array())
                ->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id',
                array())
                ->where('denominations_rulers.ruler_id= ?',(int)$id)
                ->group('denomination');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get a list of Post Medieval rulers to denominations
     * @access public
     * @param integer $id
     * @return array
     */
    public function getPostMedRulerDenom($id) {
        $select = $this->select()
                ->from($this->_name, array('id', 'term' => 'denomination'))
                ->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id', array())
                ->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id', array())
                ->where('denominations_rulers.ruler_id= ?', (int)$id)
                ->group('denomination');
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get a list of Iron Age denominations
     * @access public
     * @return array
     */
    public function getIronAgeDenoms() {
        $denoms = $this->getAdapter();
        $select = $denoms->select()
                ->from($this->_name, array('id', 'denomination'))
                ->where('period = ?', (int)16)
                ->where('valid = ?', (int)1)
                ->order('id');
        return $denoms->fetchAll($select);
    }

    /** Get a list of Iron Age denoms
     * @access public
     * @return array
     */
    public function getIronAgeDenom() {
        $denoms = $this->getAdapter();
        $select = $denoms->select()
                ->from($this->_name, array('id', 'denomination'))
                ->where('period = ?', (int)16)
                ->order('id');
        return $denoms->fetchAll($select);
    }

    /** Get a denomination name from its ID number
     * @access public
     * @param integer $id
     * @return array
     */
    public function getDenomName($id) {
        $denoms = $this->getAdapter();
        $select = $denoms->select()
                ->from($this->_name, array('id', 'denomination'))
                ->where('id = ?', (int)$id)
                ->group('id')
                ->order('id');
        return $denoms->fetchAll($select);
    }

    /** Get am emperor's denominations
     * @access public
     * @param integer $id
     * @return array
     */
    public function getEmperorDenom($id) {
        $denoms = $this->getAdapter();
        $select = $denoms->select()
                ->from($this->_name)
                ->joinLeft('coins_denomxruler','coins_denomxruler.denomID = denominations.id', array())
                ->joinLeft('rulers','coins_denomxruler.rulerID = rulers.id', array('rulerID' => 'id', 'issuer'))
                ->joinLeft('emperors','rulers.id = emperors.pasID', array())
                ->where('rulers.id = emperors.pasID')
                ->where('emperors.id = ?', (int)$id)
                ->order('emperors.date_from');
        return $denoms->fetchAll($select);
    }

    /** Get a denomination by period and id number
     * @access public
     * @param integer $id
     * @param integer $period
     * @return array
     */
    public function getDenom($id, $period){
        $denoms = $this->getAdapter();
        $select = $denoms->select()
                ->from($this->_name)
                ->joinLeft('materials',$this->_name . '.material = materials.id',array('term'))
                ->joinLeft('coins',$this->_name . '.id = coins.denomination',array())
                ->where($this->_name . '.id = ?', $id)
                ->where('period = ?',$period)
                ->group($this->_primary);
        return $denoms->fetchAll($select);
    }

    /** Get denominations by period and paginated by page
     * @access public
     * @param integer $period
     * @param integer $page
     * @return array
     */
    public function getDenominations($period, $page) {
        $denoms = $this->getAdapter();
        $select = $denoms->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
                        array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',
                        array('fn' => 'fullname'))
                ->where('period = ?',(int)$period)
                ->order('denomination');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber($page);
        }
        return $paginator;
    }

    /** Get denominations for json by period
     * @access public
     * @param integer $id
     * @return array
     */
    public function getDenominationsJson($id) {
        $denoms = $this->getAdapter();
        $select = $denoms->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
                        array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',
                        array('fn' => 'fullname'))
                ->where('period = ?', (int)$id)
                ->order('denomination');
        return $denoms->fetchAll($select);
    }

    /** Get a pair list of all denominations by period
     * @access public
     * @param integer $id
     * @return array
     */
    public function getDenomsAdd($id){
        $denoms = $this->getAdapter();
        $select = $denoms->select()
                ->from($this->_name,array('id', 'denomination'))
                ->where('period = ?', (int)$id)
                ->order('denomination');
        return $denoms->fetchPairs($select);
    }

    /** Get a paginated list of valid denominations
     * @access public
     * @param array $params
     * @return array
     */
    public function getDenomsValid( array $params) {
        $denoms = $this->getAdapter();
        $select = $denoms->select()
                ->from($this->_name)
                ->joinLeft('materials','denominations.material = materials.id', array('mat' => 'term'))
                ->joinLeft('periods','periods.id = denominations.period', array('temporal' => 'term'))
                ->where($this->_name . '.valid = ?',(int)1)
                ->order('denomination');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber($params['page']);
        }
        return $paginator;
    }

    /** Get a ruler to denomination by id
     * @access public
     * @param integer $id
     * @return array
     */
    public function getRulerDenomination( $id) {
        $options = $this->getAdapter();
        $select = $options->select()
                ->from($this->_name, array('id','denomination'))
                ->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',
                array())
                ->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id',
                        array('i'=>'rulers.id', 'issuer'))
                ->where('denominations_rulers.denomination_id= ?',(int)$id)
                ->group('issuer');
        return $options->fetchAll($select);
    }

    /** Get a denomination by ID
     * @access public
     * @param integer $id
     * @return array
     */
    public function getDenomination($id) {
        $options = $this->getAdapter();
        $select = $options->select()
                ->from('denominations',array('denomination','id'))
                ->joinLeft('materials','denominations.material = materials.id',array('mat' => 'term'))
                ->joinLeft('periods','periods.id = denominations.period',array('temporal' => 'term'))
                ->where('denominations.id =' . (int)$id);
        return $options->fetchAll($select);
    }

    /** Get a list of denominations for the sitemap by period
     * @access public
     * @param integer $id
     * @return array
     */
    public function getDenominationsSitemap($id) {
        $key = md5('denomsSiteMap' . $id);
        if (!$data = $this->_cache->load($key)) {
        $denoms = $this->getAdapter();
        $select = $denoms->select()
                ->from($this->_name,array('id','denomination','updated'))
                ->where($this->_name . '.valid = ?', (int)1)
                ->where($this->_name . '.period = ?',(int)$id)
                ->order('denomination');
        $data = $denoms->fetchAll($select);
        $this->_cache->save($data, $key);
        }
        return $data;
    }
}