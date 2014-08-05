<?php
/** Retrieve and manipulate data for mints issuing coins
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $model = new Mints();
 * $data = $model->getListMints();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @todo add caching throughout model as the cached version won't be changing!
 * @example /app/forms/ByzantineCoinForm.php
 */

class Mints extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'mints';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get all Roman mints as a key value pair list for dropdown
     * @access public
     * @return array
     */
    public function getRomanMints() {
        $select = $this->select()
                ->from($this->_name, array('id', 'mint_name'))
                ->where('period = ?', (int)21)
                ->where('valid = ?', (int)1)
                ->order($this->_primary);
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get all Byzantine mints as a key value pair list for dropdown
     * @access public
     * @return array
     */
    public function getMintsByzantine() {
	$select = $this->select()
		->from($this->_name, array('id', 'mint_name'))
		->where('period = ?', (int)67)
                ->where('valid = ?', (int)1)
		->order($this->_primary);
	return $this->getAdapter()->fetchPairs($select);
    }

    /** Get all Greek mints as a key value pair list for dropdown
     * @access public
     * @return array
     */
    public function getMintsGreek(){
	$select = $this->select()
		->from($this->_name, array('id', 'mint_name'))
		->where('period = ?', (int)66)
                ->where( 'valid = ?', (int)1)
		->order($this->_primary);
	return $this->getAdapter()->fetchPairs($select);
    }

    /** Get all Byzantine mints as a list
     * @access public
     * @return array
     */
    public function getMintsByzantineList(){
	$select = $this->select()
		->from($this->_name, array(
                    'i' => 'id', 'mint_name','mint_id' => 'id'
                    ))
		->where('period = ?',(int)67)
		->where('valid = ?', (int)1)
		->order($this->_primary);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get all Greek mints as a list
     * @access public
     * @return array
     */
    public function getMintsGreekList(){
	$select = $this->select()
		->from($this->_name, array(
                    'i' => 'id',
                    'mint_name',
                    'mint_id' => 'id'
                    ))
		->where('period = ?',(int)67)
		->where('valid = ?', (int)1)
		->order($this->_primary);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get all Medieval mints as a key value pair list for dropdown
     * @access public
     * @return array
     */
    public function getMedievalMints() {
	$select = $this->select()
                ->from($this->_name, array('id', 'mint_name'))
		->where('period = ?',(int)29)
		->where('valid = ?', (int)1)
		->order('mint_name');
	return $this->getAdapter()->fetchPairs($select);
    }

    /** Get all post Medieval mints as a key value pair list for dropdown
     * @access public
     * @return array
     */
    public function getPostMedievalMints() {
	$select = $this->select()
		->from($this->_name, array('id', 'mint_name'))
		->where('period = ?',(int)36)
		->where('valid = ?', (int)1)
 		->order($this->_primary);
	return $this->getAdapter()->fetchPairs($select);
    }

    /** Get all early Medieval mints as a key value pair list for dropdown
     * @access public
     * @return array
     */
    public function getEarlyMedievalMints(){
	$select = $this->select()
		->from($this->_name, array('id', 'mint_name'))
		->where('period = ?',(int)47)
		->where('valid = ?', (int)1)
		->order($this->_primary);
	return $this->getAdapter()->fetchPairs($select);
    }

    /** Get all iron age mints as a key value pair list for dropdown
     * @access public
     * @return array
     */
    public function getIronAgeMints(){
	$select = $this->select()
		->from($this->_name, array('id', 'mint_name'))
                ->where('period = ?',(int)16)
		->where('valid = ?', (int)1)
		->order($this->_primary);
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get all  mints by period id as a key value pair list for dropdown
     * @access public
     * @param integer $periodID
     * @return array
     */
    public function getMints($periodID){
	$select = $this->select()
		->from($this->_name, array('id', 'mint_name'))
		->where('period = ?', (int)$periodID)
		->where('valid = ?', (int)1)
		->order('mint_name');
	return $this->getAdapter()->fetchPairs($select);
    }

    /** Get a list of all iron age mints
     * @access public
     * @return array
     */
    public function listIronAgeMints() {
	$select = $this->select()
		->from($this->_name, array(
                    'i' => 'id', 'mint_name', 'created',
                    'updated', 'valid', 'mint_id' => 'id'))
		->joinLeft('periods','periods.id = mints.period',
                        array('p' => 'term'))
		->where('period = ?', (int)16)
		->where($this->_name . '.valid = ?', (int)1)
		->order('mints.id');
	return $this->getAdapter()->fetchAll($select);
    }

    /** Get all list of all  mints by period id
     * @access public
     * @param int $periodID
     * @return array
     */
    public function getListMints($periodID) {
        $key = md5('getlistmints' . $periodID);
	if (!$data = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array(
                        'id',
                        'name' => 'mint_name',
                        'created',
                        'updated',
                        'valid'))
                    ->joinLeft('periods','periods.id = mints.period',
                            array('p' => 'term'))
                    ->where('period = ?', (int)$periodID)
                    ->order('mint_name');
            $data =  $this->getAdapter()->fetchAll($select);
            $this->_cache->save($data, $key);
	}
        return $data;
    }

    /** Get details of a specific iron age mint
     * @access public
     * @param integer $mintID
     * @return array
     */
    public function getIronAgeMint($mintID) {
        $select = $this->select()
                ->from($this->_name, array(
                    'pasID' => 'id', 'name' => 'mint_name', 'updated',
                    'created'
                    ))
                ->joinLeft('periods','periods.id = mints.period',
                        array('p' => 'term', 'i' => 'id'))
                ->where('mints.id = ?', (int)$mintID)
                ->where('period = ?', (int)16)
                ->order('mints.id ASC');
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get mints attached to a roman ruler
     * @access public
     * @param integer $rulerID
     * @return array
     */
    public function getRomanMintRuler($rulerID) {
        $select = $this->select()
                ->from($this->_name, array('id', 'term' => 'mint_name'))
                ->joinLeft('mints_rulers','mints_rulers.mint_id = mints.id', array())
                ->joinLeft('rulers','rulers.id = mints_rulers.ruler_id', array())
                ->where('rulers.id = ?', (int)$rulerID)
                ->order('mints.mint_name ASC');
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get mints attached to a roman ruler for admin console
     * @access public
     * @param integer $rulerID
     * @return array
     */
    public function getRomanMintRulerAdmin($rulerID)  {
        $select = $this->select()
                ->from($this->_name, array('id', 'term' => 'mint_name'))
                ->joinLeft('mints_rulers','mints_rulers.mint_id = mints.id', array('created', 'linkid' => 'id'))
                ->joinLeft('rulers','rulers.id = mints_rulers.ruler_id', array())
                ->joinLeft('users','users.id = mints_rulers.createdBy', array('fullname'))
                ->joinLeft('periods','periods.id = mints.period', array('period' => 'term'))
                ->where('rulers.id = ?',$rulerID)
                ->order('mints.mint_name ASC');
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get mints attached to a roman emperor
     * @access public
     * @param integer $emperorID
     * @return array
     */
    public function getMintEmperorList($emperorID) {
        $select = $this->select()
                ->from($this->_name, array(
                    'mint_name','mint_id' => 'id', 'pleiadesID',
                    'woeid', 'geonamesID'))
                ->joinLeft('mints_rulers','mints_rulers.mint_id = mints.id',
                        array())
                ->joinLeft('romanmints','romanmints.pasID = mints.id',
                        array('i' => 'id'))
                ->joinLeft('emperors','mints_rulers.ruler_id = emperors.pasID',
                        array('pasID', 'name', 'id'))
                ->where('emperors.id = ?', (int)$emperorID);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get early med mints attached to a ruler
     * @access public
     * @param integer $rulerID
     * @return array
     */
    public function getEarlyMedMintRuler($rulerID) {
        $select = $this->select()
                ->from($this->_name, array('id','term' => 'mint_name'))
                ->joinLeft('mints_rulers','mints_rulers.mint_id = mints.id',
                        array())
                ->joinLeft('rulers','rulers.id = mints_rulers.ruler_id',
                        array())
                ->where('rulers.id = ?', (int)$rulerID)
                ->order('mints.mint_name ASC');
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get early med mints attached to a ruler as key value pairs for dropdown
     * @access public
     * @param integer $rulerID
     * @return array
     */
    public function getEarlyMedMintRulerPairs($rulerID) {
        $select = $this->select()
                ->from($this->_name, array('id','term' => 'mint_name'))
                ->joinLeft('mints_rulers','mints_rulers.mint_id = mints.id',
                        array())
                ->joinLeft('rulers','rulers.id = mints_rulers.ruler_id',
                        array())
                ->where('rulers.id = ?', (int)$rulerID)
                ->order('mints.mint_name ASC');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get med mints attached to a ruler as list
     * @access public
     * @param integer $rulerID
     * @return array
     */
    public function getMedMintRuler($rulerID) {
        $mints = $this->getAdapter();
        $select = $mints->select()
                ->from($this->_name, array('id','name' => 'mint_name'))
                ->joinLeft('mints_rulers','mints_rulers.mint_id = mints.id',
                        array('i' => 'id'))
                ->joinLeft('rulers','rulers.id = mints_rulers.ruler_id',
                        array('rulerID' => 'id'))
                ->where('rulers.id = ?', (int)$rulerID)
                ->order('mints.mint_name ASC');
        return $mints->fetchAll($select);
    }


    /** Get specific mint name from an id
     * @access public
     * @param integer $mintID
     * @return array
     */
    public function getMintName($mintID) {
        $select = $this->select()
                ->from($this->_name, array('mint_name'))
                ->joinLeft('periods','periods.id = mints.period', array('term'))
                ->where('mints.id = ?', (int)$mintID)
                ->limit(1);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get specific mint name from a reverse type ID
     * @access public
     * @param integer $reverseID
     * @return array
     */
    public function getMintReverseType($reverseID){
        $select = $this->select()
                ->from($this->_name, array('mint_name', 'mint_id' => 'id'))
                ->joinLeft('romanmints','mints.id = romanmints.pasID',
                        array('i' => 'id'))
                ->joinLeft('mint_reversetype','mints.id = mint_reversetype.mintID',
                        array())
                ->joinLeft('revtypes','mint_reversetype.reverseID = revtypes.id',
                        array('id'))
                ->where('revtypes.id = ?', (int)$reverseID);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get specific mint details from an id
     * @access public
     * @param integer $mintID
     * @return array
     */
    public function getMintDetails($mintID) {
        $select = $this->select()
                ->from($this->_name)
                ->joinLeft('periods', 'periods.id = mints.period', array('term'))
                ->where('mints.id = ?', (int)$mintID)
                ->limit(1);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get paginated list of mints
     * @access public
     * @param array $params
     * @return \Zend_Paginator
     */
    public function getMintsListAll($params) {
        $mints = $this->getAdapter();
        $select = $mints->select()
                ->from($this->_name)
                ->joinLeft('periods','periods.id = mints.period', array('t' => 'term'))
                ->where($this->_name . '.valid = ?',(int)1);
        if(isset($params['period']) && ($params['period'] != "")) {
        $select->where('period = ?',(int)$params['period']);
        }
        $paginator = Zend_Paginator::factory($select);
        Zend_Paginator::setCache($this->_cache);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']);
        }
        return $paginator;
    }

    /** Get paginated list of mints for admin console
     * @access public
     * @param array $params
     * @return \Zend_Paginator
     */
    public function getMintsListAllAdmin($params){
        $mints = $this->getAdapter();
        $select = $mints->select()
                ->from($this->_name)
                ->joinLeft('periods','periods.id = mints.period',
                        array('t' => 'term'))
                ->joinLeft('users',$this->_name . '.createdBy = users.id',
                        array('fullname'))
                ->joinLeft('users',$this->_name . '.updatedBy = users_2.id',
                        array('fn' => 'fullname'))
                ->where($this->_name . '.valid = ?',(int)1);
        if(isset($params['period']) && ($params['period'] != "")) {
            $select->where('period = ?',(int)$params['period']);
        }
        $paginator = Zend_Paginator::factory($select);
        Zend_Paginator::setCache($this->_cache);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']);
        }
        return $paginator;
}

    /** Get mints by period for sitemap
     * @access public
     * @param integer $periodID
     * @return array
     */
    public function getMintsSiteMap($periodID){
        $key = md5('mintlistsmap' . $periodID);
        if (!$data = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('id', 'mint_name', 'updated'))
                    ->where('period = ?', $periodID)
                    ->where('valid = ?', (int)1)
                    ->order('mints.id ASC');
            $data = $this->getAdapter()->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get a pleiades ID
     * @access public
     * @param integer $mint
     * @return string
     */
    public function getPleiadesID($mint){
        $mints = $this->getAdapter();
        $mint = $mints->fetchRow($this->select()->where('id =' . $mint));
        return $mint->pleiadesID;
    }
}