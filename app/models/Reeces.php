<?php
/** Data model for accessing and manipulating Reece period data
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $reeces = new Reeces();
 * $reeces_options = $reeces->getOptions();
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
 * @since 22 October 2010, 17:12:34
 * @todo add edit and delete functions
 * @todo add caching
 * @example /app/forms/ReeceEmperorForm.php 
 */

class Reeces extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var type 
     */
    protected $_name = 'reeceperiods';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Retrieve key value pairs for Reece period dropdowns
    * @return array
    * @todo add caching
    */
    public function getOptions() {
        $select = $this->select()
                ->from($this->_name, array('id', 'period_name'))
                ->order($this->_primary)
                ->where('valid =?', (int)1);
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Retrieve key value pairs for Reece period dropdowns > period 15
     * @access public
     * @return array
     */
    public function getRevTypes() {
        $select = $this->select()
                ->from($this->_name, array('id', 'period_name'))
                ->where('id >= ?', (int)15)
                ->order($this->_primary);
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Retrieve rulers for a specific reece period, one to many relationship
     * @access public
     * @param integer $ruler
     * @return array
     */
    public function getRulerReece($ruler) {
        $reeces = $this->getAdapter();
        $select = $reeces->select()
                ->from($this->_name, array(
                    'id',
                    'term' => 'CONCAT(period_name," - ",description," ","(",date_range,")")'))
                ->joinLeft('reeceperiods_rulers','reeceperiods.id = reeceperiods_rulers.reeceperiod_id',
                        array())
                ->joinLeft('rulers','rulers.id = reeceperiods_rulers.ruler_id',
                        array())
                ->where('rulers.id = ?', (int)$ruler)
                ->order('period_name ASC');
        return $reeces->fetchAll($select);
    }	

    /** Get unassigned reece periods > 14
     * @access public
     * @return array
     */
    public function getReeceUnassigned(){
        $reeces2 = $this->getAdapter();
        $select = $reeces2->select()
                ->from($this->_name, array('id','term' => 'CONCAT(period_name," - ",description," ","(",date_range,")")'))
                ->where('reeceperiods.id > ?', (int)14)			
                ->order('period_name ASC');
        return $reeces2->fetchAll($select);
    }	

    /** Retrieve all valid reece periods as key value pairs for dropdown on 
     * forms or ajax
     * @access public
     * @return array
     */
    public function getReeces() {
        $reeces2 = $this->getAdapter();
        $select = $reeces2->select()
                ->from($this->_name, array('id','term' => 'CONCAT(period_name," - ",description," ","(",date_range,")")'))
                ->where('valid = ?',(int)1)
                ->order('id ASC');
        return $reeces2->fetchPairs($select);
    }

    /** Retrieve all valid reece periods for admin interface
     * @access public
     * @return array
     */
    public function getReecesAdmin() {
        $reeces = $this->getAdapter();
        $select = $reeces->select()
                ->from($this->_name)
                ->joinLeft('users',$this->_name . '.createdBy = users.id', array('fullname'))
                ->joinLeft('users',$this->_name . '.updatedBy = users_2.id', array('fn' => 'fullname'))
                ->order('id ASC');
        return $reeces->fetchAll($select);
    }	

    /** Retrieve details for specific reece period
     * @access public
     * @param integer $id
     * @return array
     */
    public function getReecePeriodDetail($id){
        $reeces = $this->getAdapter();
        $select = $reeces->select()
                ->from($this->_name, array(
                    'id', 'period_name', 'description', 
                    'date_range', 'created', 'updated'
                    ))
                ->joinLeft('users',$this->_name . '.createdBy = users.id', 
                        array('createdBy' => 'fullname'))
                ->joinLeft('users',$this->_name . '.updatedBy = users_2.id', 
                        array('updatedBy' => 'fullname'))
                ->where($this->_name . '.id = ?',(int)$id);
        return $reeces->fetchAll($select);
    }

    /** Retrieve details for periods for site map
     * @access public
     * @return 
     */
    public function getSiteMap(){
        if (!$data = $this->_cache->load('reecescached')) {	
            $rulers = $this->getAdapter();
            $select = $rulers->select()
                    ->from($this->_name, array('id', 'period_name','updated'));
            $data = $rulers->fetchAll($select);
            $this->_cache->save($data, 'reecescached');
            } 
        return $data;
    }
}