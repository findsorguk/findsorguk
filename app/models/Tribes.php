<?php
/** Data model for accessing tribe listings on the database
 * An example of code:
 *
 * <code>
 * <?php
 * $tribes = new Tribes();
 * $to = $tribes->getTribes();
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
 * @since 22 October 2010, 17:12:34
 * @example /app/forms/IronAgeCoinForm.php
 */
class Tribes extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'ironagetribes';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get a key value pair list of tribes
     * @access public
     * @return array
     */
    public function getTribes() {
        $tribes = $this->getAdapter();
        $select = $tribes->select()
                ->from($this->_name, array('id','tribe'))
    		->where($this->_name . '.valid = ?',(int)1)
                ->order($this->_primary);
        return $tribes->fetchPairs($select);
    }

    /** Get a  list of tribes
     * @access public
     * @return array
     */
    public function getTribesList()  {
        $tribes = $this->getAdapter();
        $select = $tribes->select()
                ->from($this->_name, array('id','tribe'))
                ->joinLeft('ironageregionstribes',
                        'ironageregionstribes.tribeID =' . $this->_name . '.id',
                        array())
                ->joinLeft('geographyironage',
                        'geographyironage.id = ironageregionstribes.regionID',
                        array('area','region'))
    		->where($this->_name . '.valid = ?', 1)
                ->order('id');
        $options = $this->getAdapter()->fetchAll($select);
        return $options;
    }

    /** Get a paginated list of all tribes for administration
     * @access public
     * @param integer $page
     * @return \Zend_Paginator
     */
    public function getTribesListAdmin($page) {
        $tribes = $this->getAdapter();
        $select = $tribes->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name
                        . '.updatedBy', array('fn' => 'fullname'))
                ->order('id');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($page) && ($page != ""))  {
            $paginator->setCurrentPageNumber((int)$page);
        }
        return $paginator;
    }

    /** Get a tribe details
     * @access public
     * @param integer $id
     * @return array
     */
    public function getTribe($id) {
        $tribes = $this->getAdapter();
        $select = $tribes->select()
                ->from($this->_name, array('id','tribe','description'))
                ->where($this->_name . '.valid = ?', (int)1)
                ->where('id = ?',(int)$id)
                ->order('id ASC');
        return $tribes->fetchAll($select);
    }

    /** Get a tribe to region list
     * @access public
     * @param integer $region
     * @return array
     */
    public function getIronAgeTribeRegion($region) {
        $tribes = $this->getAdapter();
        $select = $tribes->select()
                ->from($this->_name, array('id','term' => 'tribe'))
                ->joinLeft('ironageregionstribes',
                        'ironageregionstribes.tribeID = ironagetribes.id',
                        array())
                ->joinLeft('geographyironage',
                        'ironageregionstribes.regionID = geographyironage.id',
                        array())
                ->where('geographyironage.id = ?', (int)$region)
                ->order('ironagetribes.tribe ASC');
        return $tribes->fetchAll($select);
    }

    /** Get a tribe list for xml site map
     * @access public
     * @return array
     */
    public function getSitemap(){
        $key = md5('tribeslist');
        if (!$data = $this->_cache->load($key)) {
            $tribes = $this->getAdapter();
            $select = $tribes->select()
                    ->from($this->_name, array('id','term' => 'tribe','updated'))
                    ->order('ironagetribes.tribe ASC');
            $data =  $tribes->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }
}