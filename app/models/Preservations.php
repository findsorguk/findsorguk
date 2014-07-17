<?php
/** Retrieve and manipulate data from the preservation states
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $preserves = new Preservations();
 * $preserve_options = $preserves->getPreserves();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/forms/FindForm.php
 */
class Preservations extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'preservations';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get all valid preservation states as a key value list
     * @access public
     * @return array
     */
    public function getPreserves() {
        $select = $this->select()
                ->from($this->_name, array('id', 'term'))
                ->order('term ASC')
                ->where('valid = ?',(int)1);
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get all valid preservation states
     * @access public
     * @return array
     */
    public function getPreservationTerms(){
        $preserves = $this->getAdapter();
        $select = $preserves->select()
                ->from($this->_name)
                ->where('valid = ?',(int)1);
        return $preserves->fetchAll($select);
    }

    /** Get all preservation details by ID
     * @access public
     * @param integer $id
     * @return array
     */
    public function getPreservationDetails($id){
        $preserves = $this->getAdapter();
        $select = $preserves->select()
                ->from($this->_name)
                ->where('id = ?',(int)$id)
                ->where('valid = ?',(int)1);
        return $preserves->fetchAll($select);
    }

    /** Get all preservation types for admin
     * @access public
     * @return array
     */
    public function getPreservationTermsAdmin() {
        $preserves = $this->getAdapter();
        $select = $preserves->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name
                        . '.updatedBy', array('fn' => 'fullname'));
        return $preserves->fetchAll($select);
    }
}