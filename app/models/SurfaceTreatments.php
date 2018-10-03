<?php
/** Data model for accessing surface treatment table
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $surfaces = new SurfaceTreatments();
 * $surface_options = $surfaces->getSurfaces();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 October 2010, 17:12:34
 * @example /app/forms/AdvancedSearchForm.php 
 */
class SurfaceTreatments extends Pas_Db_Table_Abstract {
    
    /** The primary Key
     * @access protected
     * @var integer
     */
    protected $_primaryKey = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'surftreatments';

    /** Get surface treatment dropdowns
     * @access public
     * @return array
     */
    public function getSurfaces() {
        $key = md5('surftreatdd');
        if (!$options = $this->_cache->load($key)) {
        $select = $this->select()
                ->from($this->_name, array('id', 'term'))
                ->where('valid = ?',(int)1)
                ->order('term');
        $options = $this->getAdapter()->fetchPairs($select);
        $this->_cache->save($options, $key);
        }
        return $options;
}
    /** Get surface treatment details
     * @access public
     * @param integer $id
     * @return array
     */
    public function getSurfaceTerm($id) {
        $surfaces = $this->getAdapter();
        $select = $surfaces->select()
                ->from($this->_name, array('id','term'))
                ->where('valid = ?',(int)1)
                ->order('id')
                ->limit('1')
                ->where('id = ?', (int)$id);
        return $surfaces->fetchAll($select);
    }

    /** Get surface treatment list
     * @access public
     * @return array
     */
    public function getSurfaceTreatments() {
        $surfs = $this->getAdapter();
        $select = $surfs->select()
                ->from($this->_name, array('id','term'))
                ->where('valid = ?',(int)1);
        return $surfs->fetchAll($select);
    }

    /** Get surface treatment list for admin
     * @access public
     * @return array
     */
    public function getSurfaceTreatmentsAdmin() {
        $surfs = $this->getAdapter();
        $select = $surfs->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name 
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name 
                        . '.updatedBy', array('fn' => 'fullname'));
        return $surfs->fetchAll($select);
    }

    /** Get surface treatment details
     * @access public
     * @param integer $id
     * @return array
     */
    public function getSurfaceTreatmentDetails($id) {
        $surfs = $this->getAdapter();
        $select = $surfs->select()
                ->from($this->_name, array('id','term','termdesc'))
                ->where($this->_name . '.id = ?', (int)$id)
                ->where('valid = ?', (int)1);
        return $surfs->fetchAll($select);
    }
}
