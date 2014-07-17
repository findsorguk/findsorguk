<?php
/** 
 * Model for interacting with landuse entries in db
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $landusevalues = new Landuses();
 * $landuse_options = $landusevalues->getUsesValid();
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
 * @todo add edit and delete functions
 * @todo add caching throughout
 * @example /app/forms/FindSpotForm.php
 */
class Landuses extends Pas_Db_Table_Abstract {
	
    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'landuses';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';
	
    /** Get list of landuses where valid and at top level
     * @access public
     * @return array 
     */
    public function getLanduses(){
        $landuses = $this->getAdapter();
        $select = $landuses->select()
                ->from($this->_name)
                ->where('belongsto IS NULL')
                ->where('valid = ?',(int)1);
        return $landuses->fetchAll($select);
    }

    /** get list of landuses for admin list
     * @access public
     * @return array
     */
    public function getLandusesAdmin() {
        $landuses = $this->getAdapter();
        $select = $landuses->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name 
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name 
                        . '.updatedBy', array('fn' => 'fullname'));
        return $landuses->fetchAll($select);
    }

    /** Get landuse details by id number
     * @access public
     * @param integer $id
     * @return array
     */
    public function getLanduseDetails($id) {
        $landuses = $this->getAdapter();
        $select = $landuses->select()
                ->from($this->_name)
                ->where('landuses.id = ?', (int)$id);
        return $landuses->fetchAll($select);
    }

    /** Get child landuse details by id number
     * @access public
     * @param integer $id
     * @return array
     */
    public function getLandusesChild($id) {
        $landuses = $this->getAdapter();
        $select = $landuses->select()
                ->from($this->_name)
                ->where('belongsto = ?',(int)$id);
        return $landuses->fetchAll($select);
    }

    /** Get list of child landuses
     * @access public
     * @param integer $id
     * @return array
     */
    public function getLandusesChildList($id) { 
        $landuses = $this->getAdapter();
        $select = $landuses->select()
                ->from($this->_name,array('id','term'))
                ->where('belongsto = ?',(int)$id);
        return $landuses->fetchPairs($select);
    }

    /** Get list of child landuses for ajax menus as key value pairs
     * @access public
     * @param integer $id
     * @return array
     */
    public function getLandusesChildAjax($id) {
        $landuses = $this->getAdapter();
        $select = $landuses->select()
                ->from($this->_name,array('id','term'))
                ->where('belongsto = ?',(int)$id);
        return $landuses->fetchPairs($select);
    }

    /** Get list of child landuses for ajax menus 
     * @access public
     * @param type $id
     * @return type
     */
    public function getLandusesChildAjax2($id) {
        $landuses = $this->getAdapter();
        $select = $landuses->select()
                ->from($this->_name,array('id','term'))
                ->where('belongsto = ?',(int)$id);
        return $landuses->fetchAll($select);
    }

    /** Get list of landuses as key value pairs for menus 
     * @access public
     * @return array
     */
    public function getUses() {
        $select = $this->select()
                ->from($this->_name, array('id', 'term'))
                ->order('term ASC');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** get list of key value pairs for menus 
     * @access public
     * @return array
     */
    public function getUsesValid() {
        $select = $this->select()
                ->from($this->_name, array('id', 'term'))
                ->where('valid = 1 AND belongsto IS NULL')
                ->order('id ASC');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** get list of valid codes for EH thesaurus as key value pairs 
     * @access public
     * @return array
     */
    public function getCodesValid() {
        $select = $this->select()
                ->from($this->_name, array('id', 'term'))
                ->where('valid = 1 AND belongsto IS NOT NULL')
                ->order('id ASC');
        return $this->getAdapter()->fetchPairs($select);
    }
}
