<?php
/** 
 * Retrieve and manipulate data for medieval coin types
 * 
 * An example of code:
 * 
 * <code>
 * <?php
 * $types = new MedievalTypes();
 * $type_options = $types->getMedievalTypesForm(47);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo add caching throughout model as the cached version won't be changing!
 * @example path description
*/

class MedievalTypes extends Pas_Db_Table_Abstract {

    /** The table name
     * @access public
     * @var string
     */
    protected $_name = 'medievaltypes';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';


    /** Get all the early medieval types attached to a ruler
     * @access public
     * @param integer $rulerID
     * @return array
     */
    public function getEarlyMedTypes($rulerID) {
        $select = $this->select()
                ->from($this->_name, array('id','term' => new Zend_Db_Expr("CONCAT(type,' (',datefrom,' - ',dateto,')')")))
                ->where('periodID = ? ', (int)47)
                ->where('rulerID = ?', (int)$rulerID)
                ->order($this->_primary);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get all the early medieval types attached to a ruler
     * @access public
     * @param integer $rulerID
     * @return array
     */
    public function getEarlyMedTypeRuler($rulerID) {
        $rulers = $this->getAdapter();
        $select = $rulers->select()
                ->from($this->_name, array('id','term' => new Zend_Db_Expr("CONCAT(type,' (',datefrom,' - ',dateto,')')")))
                ->joinLeft('rulers','rulers.id = medievaltypes.rulerID', array())
                ->where('medievaltypes.rulerID = ?', (int)$rulerID)
                ->order('medievaltypes.id');
        return $rulers->fetchAll($select);
    }

    /** Get all the early medieval types attached to a ruler for admin
     * @access public
     * @param integer $rulerID
     * @return array
     */
    public function getEarlyMedTypeRulerAdmin($rulerID){
        $rulers = $this->getAdapter();
        $select = $rulers->select()
                ->from($this->_name, array('linkid' => 'id','term' => new Zend_Db_Expr("CONCAT(type,' (',datefrom,' - ',dateto,')')"),'created'))
                ->joinLeft('rulers','rulers.id = medievaltypes.rulerID',array())
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
                ->where('medievaltypes.rulerID = ?', (int)$rulerID)
                ->order('medievaltypes.id');
        return $rulers->fetchAll($select);
    }

    /** Get all the medieval types attached to a ruler
     * @access public
     * @param integer $rulerID
     * @return array
     */
    public function getMedievalTypeToRuler($rulerID) {
        $rulers = $this->getAdapter();
        $select = $rulers->select()
                ->from($this->_name, array('id','type','datefrom','dateto'))
                ->joinLeft('rulers','rulers.id = medievaltypes.rulerID', array())
                ->where('period = ?', (int)29)
                ->where('medievaltypes.rulerID = ?', (int)$rulerID)
                ->order('medievaltypes.id');
        return $rulers->fetchAll($select);
    }

    /** Get all the early medieval types attached to a ruler
     * @access public
     * @param integer $rulerID
     * @return array
     */
    public function getEarlyMedievalTypeToRuler($rulerID) {
        $rulers = $this->getAdapter();
        $select = $rulers->select()
                ->from($this->_name, array('id','type','datefrom','dateto'))
                ->joinLeft('rulers','rulers.id = medievaltypes.rulerID', array())
                ->where('period = ?', (int)47)
                ->where('medievaltypes.rulerID = ?', (int)$rulerID)
                ->order('medievaltypes.id');
        return $rulers->fetchAll($select);
    }


    /** Get all the post medieval types attached to a ruler
     * @access public
     * @param integer $rulerID
     * @return array
     */
    public function getPostMedievalTypeToRuler($rulerID) {
        $rulers = $this->getAdapter();
        $select = $rulers->select()
                ->from($this->_name, array('id','type','datefrom','dateto'))
                ->joinLeft('rulers','rulers.id = medievaltypes.rulerID', array())
                ->where('period = ?', (int)36)
                ->where('medievaltypes.rulerID = ?', (int)$rulerID)
                ->order('medievaltypes.id');
        return $rulers->fetchAll($select);
    }

    /** Get all the medieval types attached to a ruler as dropdown ket value pairs
     * @access public
     * @param integer $rulerID
     * @return array
     */
    public function getMedievalTypeToRulerMenu($rulerID) {
        $rulers = $this->getAdapter();
        $select = $rulers->select()
                ->from($this->_name, array('id','term' => new Zend_Db_Expr("CONCAT(type,' (',datefrom,' - ',dateto,')')")))
                ->joinLeft('rulers','rulers.id = medievaltypes.rulerID', array())
                ->where('medievaltypes.rulerID = ?',(int)$rulerID)
                ->order('medievaltypes.id');
        return $rulers->fetchPairs($select);
    }


    /** Get all the medieval types attached to a specific ruler no concatenation
     * @access public
     * @param integer $rulerID
     * @return array
     */
    public function getMedievalRulersToType($rulerID) {
        $rulers = $this->getAdapter();
        $select = $rulers->select()
                ->from($this->_name, array())
                ->joinLeft('rulers','rulers.id = medievaltypes.rulerID',array('id', 'issuer', 'date1', 'date2'))
                ->where('medievaltypes.id = ?',(int)$rulerID)
                ->order('medievaltypes.id');
        return $rulers->fetchAll($select);
    }

    /** Get all the medieval types attached to a specific category
     * @access public
     * @param integer $catID
     * @return array
     */
    public function getCoinTypeCategory($catID)  {
        $key = md5('cointypeCat' . $catID);
        if (!$data = $this->_cache->load($key)) {
            $rulers = $this->getAdapter();
            $select = $rulers->select()
                    ->from($this->_name, array('id', 'type', 'datefrom', 'dateto'))
                    ->where('categoryID = ?', (int)$catID)
                    ->order($this->_primary);
            $data =  $rulers->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get all the medieval types paginated by period
     * @access public
     * @param integer $periodID
     * @param integer $page
     * @return \Zend_Paginator
     */
    public function getTypesByPeriod($periodID, $page) {
        $rulers = $this->getAdapter();
        $select = $rulers->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name 
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name 
                        . '.updatedBy', array('fn' => 'fullname'))
                ->joinLeft('medievalcategories',$this->_name 
                        . '.categoryID = medievalcategories.id', 
                        array('c' => 'category'))
                ->joinLeft('rulers','rulers.id = ' . $this->_name 
                        . '.rulerID', array('ruler' => 'issuer','i' => 'id'))
                ->where('medievaltypes.periodID = ?',(int)$periodID)
                ->order('medievaltypes.id');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber((int)$page);
        }
        return $paginator;
    }

    /** Get all the medieval types paginated by period for admin console
     * @access public
     * @param array $params
     * @return \Zend_Paginator
     */
    public function getTypesByPeriodAdmin($params) {
        $rulers = $this->getAdapter();
        $select = $rulers->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name 
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name 
                        . '.updatedBy', array('fn' => 'fullname'))
                ->joinLeft('medievalcategories', $this->_name 
                        . '.categoryID = medievalcategories.id', 
                        array('c' => 'category'))
                ->joinLeft('rulers','rulers.id = '. $this->_name 
                        . '.rulerID', array('ruler' => 'issuer','i' => 'id'))
                ->where('medievaltypes.periodID = ?', (int)$params['period'])
                ->order('medievaltypes.id');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']);
        }
        return $paginator;
    }

    /** Get a specific medieval type details
     * @access public
     * @param integer $id
     * @return array
     */
    public function getTypeDetails($id) {
        $types = $this->getAdapter();
        $select = $types->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name 
                        . '.createdBy',array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name 
                        . '.updatedBy',array('fn' => 'fullname'))
                ->joinLeft('rulers','rulers.id = ' . $this->_name 
                        . '.rulerID',array('ruler' => 'issuer','i' => 'id'))
                ->joinLeft('categoriescoins',$this->_name 
                        . '.categoryID = categoriescoins.id',array('category'))
                ->where($this->_name . '.id = ?',(int)$id);
        return $types->fetchAll($select);
    }

    /** Get all the medieval types for sitemap
     * @access public
     * @param integer $periodID
     * @return array
     */
    public function getTypesSiteMap($periodID) {
        $key = md5('sitemaptypes' . $periodID);
        if (!$data = $this->_cache->load($key)) {
            $types = $this->getAdapter();
            $select = $types->select()
                    ->from($this->_name, array('id', 'updated', 'type'))
                    ->where($this->_name . '.periodID = ?',(int)$periodID);
            $data =  $types->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get types for form
     * @access public
     * @param integer $periodID
     * @return array
     */
    public function getMedievalTypesForm($periodID){
        $key = md5('searchformedieval' . $periodID);
        if (!$data = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('id','term' => new Zend_Db_Expr("CONCAT(type,' (',datefrom,' - ',dateto,')')")))
                    ->where('periodID = ? ', (int)$periodID)
                    ->order($this->_primary);
            $data = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }
}