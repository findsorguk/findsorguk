 <?php
/** Retrieve and manipulate data the period thesauri
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $periods = new Periods();
 * $data = $period->getMintsActive();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example ./app/modules/datalabs/controllers/TerminologyController.php
*/
class Periods extends Pas_Db_Table_Abstract {

    /** The table name
     * @access public
     * @var string
     */
    protected $_name = 'periods';
	
    /** Primary key
     * @access protected
     * @var type 
     */
    protected $_primary = 'id';
	
    /** Get period from dropdown
     * @access public
     * @return array
     */
    public function getPeriodFrom() {
        $key = md5('periodlistfrom');
        if (!$options = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('id', 'term'))
                    ->order('id')
                    ->where('valid = ?', (int)1);
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, $key);
        } 
        return $options;
    }

    /** Get period from in words dropdown
     * @access public
     * @return array
     */
    public function getPeriodFromWords(){
        $key = md5('periodlistwords');
        if (!$options = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('term', 'term'))
                    ->order('id')
                    ->where('valid = ?', (int)1);
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, $key);
        } 
        return $options;
    }

    /** Get periods for coin mints
     * @access public
     * @return array
     */
    public function getMintsActive() {
        $key = md5('activeMintsPeriods');
        if (!$options = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('id', 'term'))
                    ->where('id IN (16,21,29,36,41,47,66,67)')
                    ->order('term')
                    ->where('valid = ?', (int)1);
            $actives = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, $key);
        }
        return $actives;
    }

    /** Get periods for coins
     * @access public
     * @return array
     */
    public function getCoinsPeriod(){
        $key = md5('coinperiods');
        if (!$options = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('id', 'term'))
                    ->where('id IN (16,21,29,36,41,47,66,67)')
                    ->where('valid = ?', (int)1)
                    ->order(array('fromdate', 'id'));
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, $key);
        } 
        return $options;
    }

    /** Get periods for coins as word pairs
     * @access public
     * @return array
     */
    public function getCoinsPeriodWords(){
        $key = md5('coinperiodsWords');
        if (!$options = $this->_cache->load($key)) {
            $select = $this->select()
                ->from($this->_name, array('term', 'term'))
                ->where('id IN (16,21,29,36,41,47,66,67)')
                ->where('valid = ?', (int)1)
                ->order(array('fromdate', 'id'));
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, $key);
        }
        return $options;
    }

    /** Get medieval periods for coin mints
     * @access public
     * @return array
     */
    public function getMedievalCoinsPeriodList()  {
        $select = $this->select()
                ->from($this->_name, array('id', 'term'))
                ->where('id IN (29,36,47)')
                ->where('valid = ?', (int)1)
                ->order(array('fromdate','id'));
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get period name by period number
     * @access public
     * @param integer $id
     * @return array
     */
    public function getPeriodName($id) {
        $periods = $this->getAdapter();
        $select = $periods->select()
                ->from($this->_name, array('id','term'))
                ->order('id')
                ->limit(1)
                ->where('id = ?',(int)$id);
        return $periods->fetchAll($select);
    }

    /** Get valid periods
     * @access public
     * @return array
     */
    public function getPeriods() {
        $periods = $this->getAdapter();
        $select = $periods->select()
                ->from($this->_name)
                ->where('valid = ?', (int)1)
                ->order('fromdate ASC');
        return $periods->fetchAll($select);
    }

    /** Get periods for admin
     * @access public
     * @return array
     */
    public function getPeriodsAll() {
        $periods = $this->getAdapter();
        $select = $periods->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
                        array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',
                        array('fn' => 'fullname'))
                ->order('valid DESC');
        return $periods->fetchAll($select);
    }

    /** Get specific period details
     * @access public
     * @param integer $id
     * @return array
     */
    public function getPeriodDetails($id){
        $periods = $this->getAdapter();
        $select = $periods->select()
                ->from($this->_name)
                ->where('valid = ?',(int)1)
                ->where('id = ?',(int)$id);
        return $periods->fetchAll($select);
    }

    /** Get object types by period
     * @access public
     * @param integer $id
     * @return array
     * @todo deprecate this and move to solr
     */
    public function getObjectTypesByPeriod($id) {
        $key = md5('objbyperiod' . $id);
        if (!$data = $this->_cache->load($key)) {
            $periods = $this->getAdapter();
            $select = $periods->select()
                    ->from($this->_name,array('term'))
                    ->joinLeft('finds',$this->_name . '.term = finds.broadperiod',
                            array('title' => 'objecttype','weight' => 'COUNT(*)'))
                    ->where($this->_name . '.id =?',(int)$id)
                    ->order('finds.objecttype')
                    ->group('finds.objecttype');
            $data =  $periods->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get period from in words dropdown
     * @access public
     * @return array
     */
    public function getPeriodsHoards(){
        $key = md5('periodlistwordsHoards');
        if (!$options = $this->_cache->load($key)) {
            $select = $this->select()
                ->from($this->_name, array('term', 'term'))
                ->order('id')
                ->where('id > ?', 9)
                ->where('valid = ?', (int)1);
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, $key);
        }
        return $options;
    }
}