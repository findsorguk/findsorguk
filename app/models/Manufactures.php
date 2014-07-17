<?php
/** Model for manipulating methods of manufacture from DB
 * 
 * <code>
 * <?php
 * $mans = new Manufactures();
 * $man_options = $mans->getOptions();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo add edit and delete functions
 * @todo add caching
 * @example /app/forms/AdvancedSearchForm.php 
 */
class Manufactures extends Pas_Db_Table_Abstract {

   /** The table name
    * @access protected
    * @var string
    */
    protected $_name = 'manufactures';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get a key pair value list for dropdowns for manufacturing methods
     * @access public
     * @return array
     */
    public function getOptions() {
        $select = $this->select()
                ->from($this->_name, array('id', 'term'))
                ->order('term ASC')
                ->where('valid = ?',(int)1);
        return $this->getAdapter()->fetchPairs($select);
    }

    /** get a list of manufacturing methods
     * @access public
     * @return array
     */
    public function getManufacturesListed() {
        $manufactures = $this->getAdapter();
        $select = $manufactures->select()
                ->from($this->_name)
                ->where('valid = ?',(int)1);
        return $manufactures->fetchAll($select);
    }
    
    /** Get a list of manufacturing methods for admin interface
     * @access public
     * @return array
     */
    public function getManufacturesListedAdmin() {
        $manufactures = $this->getAdapter();
        $select = $manufactures->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name 
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name 
                        . '.updatedBy', array('fn' => 'fullname'));
        return $manufactures->fetchAll($select);
    }

    /** get manufacturing method details by id number
     * @access public
     * @param integer $id
     * @return array
     */
    public function getManufactureDetails($id) {
        $manufactures = $this->getAdapter();
        $select = $manufactures->select()
                ->from($this->_name)
                ->where('id = ?', (int)$id)
                ->where('valid = ?', (int)1);
        return $manufactures->fetchAll($select);
    }
}