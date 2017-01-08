<?php
/** Data model for accessing and manipulating Roman reverse type database table
 *
 * An example of code:
 * <code>
 * <?php
 * $model = new RevTypes();
 * $data = $model->getRevTypes();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 October 2010, 17:12:34
 * @todo add edit and delete functions
 * @todo add caching
 * @example /app/forms/RomanCoinForm.php
 */

class RevTypes extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'revtypes';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Retrieve reverse types assigned to a ruler with concatenation over
     * reeceperiod
     * @access public
     * @param integer $ruler Ruler identification number
     * @return array
     * @todo add caching
     */
    public function getTypes($ruler){
        $types = $this->getAdapter();
        $select = $types->select()
                ->from($this->_name, array('id','term' => new Zend_Db_Expr("CONCAT(type,' Reece period: ',reeceID, ' ', description)")))
                ->joinLeft('ruler_reversetype','ruler_reversetype.reverseID = revtypes.id',array())
                ->joinLeft('rulers','rulers.id = ruler_reversetype.rulerID',array())
                ->where('rulers.id = ?',(int)$ruler)
                ->order('type');
        return $types->fetchAll($select);
    }

    /** Retrieve reverse types for the administration module with concatenation over reeceperiod
     * @access public
     * @param integer $ruler Ruler ID number
     * @return array
     */
    public function getTypesAdmin($ruler) {
        $types = $this->getAdapter();
        $select = $types->select()
                ->from($this->_name, array('id','term' => new Zend_Db_Expr("CONCAT(type,' Reece period: ',reeceID)")))
                ->joinLeft('ruler_reversetype','ruler_reversetype.reverseID = revtypes.id',
                        array('created','linkid' => 'id'))
                ->joinLeft('rulers','rulers.id = ruler_reversetype.rulerID',
                        array())
                ->joinLeft('users','users.id = ruler_reversetype.createdBy',
                        array('fullname'))
                ->where('rulers.id = ?',(int)$ruler)
                ->order('type');
        return $types->fetchAll($select);
    }

    /** Retrieve reverse types as key value pairs for form dropdown
     * @access public
     * @param integer $ruler The ruler ID
     * @return array
     */
    public function getRevTypesForm($ruler) {
        $types = $this->getAdapter();
        $select = $types->select()
                ->from($this->_name, array('id','term' => new Zend_Db_Expr("CONCAT(type,' Reece period: ',reeceID)")))
                ->joinLeft('ruler_reversetype','ruler_reversetype.reverseID = revtypes.id',
                        array())
                ->joinLeft('rulers','rulers.id = ruler_reversetype.rulerID',
                        array())
                ->where('rulers.id = ?', (int)$ruler)
                ->order('type');
        return  $types->fetchPairs($select);
    }

    /** Retrieve reverse type for single instance
     * @access public
     * @param integer $reverse The reverse ID number
     * @return array
     */

    public function getRevType($reverse) {
        $types = $this->getAdapter();
        $select = $types->select()
                ->from($this->_name, array('id','term' => 'type'))
                ->where('id = ?',(int)$reverse);
        return $types->fetchAll($select);
    }

    /** Get key value pairs for dropdown where type is not null
     * @access public
     * @return array
     */
    public function getRevTypes() {
        $types = $this->getAdapter();
        $select = $types->select()
                ->from($this->_name, array('id','term' => new Zend_Db_Expr("CONCAT(type,' Reece period: ',reeceID, ' ', description)")))
                ->where('type IS NOT NULL')
                ->order('type');
        return $types->fetchPairs($select);
    }

    /** Get most common reverse types list
     * @access public
     * @param integer $common
     * @return array
     */
    public function getReverseTypeList($common) {
        $types = $this->getAdapter();
        $select = $types->select()
                ->from('revtypes')
                ->joinLeft('reeceperiods', 'reeceperiods.id = revtypes.reeceID',
                        array( 'period_name', 'date_range', 'i' => 'id'))
                ->where('revtypes.common = ?',(int)$common)
                ->order('reeceID');
        return $types->fetchAll($select);
    }

    /** Get reverse details enhanced
     * @access public
     * @param integer $id
     * @return array
     */
    public function getReverseTypesDetails($id) {
        $types = $this->getAdapter();
        $select = $types->select()
                ->from($this->_name, array('id', 'type', 'reeceID', 'description', 'gendate'))
                ->joinLeft('reeceperiods','reeceperiods.id = revtypes.reeceID',
                        array('period_name', 'date_range', 'i' => 'id'))
                ->where('type IS NOT NULL')
                ->where($this->_name . '.id =?',(int)$id);
        return $types->fetchAll($select);
    }

    /** Get reverse type allied to reece type
     * @access public
     * @param integer $id
     * @return array
     */
    public function getRevTypeReece($id) {
        $types = $this->getAdapter();
        $select = $types->select()
                ->from($this->_name)
                ->where('reeceID = ?', (int)$id);
        return $types->fetchAll($select);
    }
}