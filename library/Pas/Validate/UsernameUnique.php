<?php
/** A validation class for checking for unique usernames
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Validate
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/forms/RegisterForm.php 
 */
class Pas_Validate_UsernameUnique extends Zend_Validate_Abstract {
    
    /** The not unique constant
     * 
     */
    const NOT_UNIQUE = 'notUnique';

    /** Database Connection
     * @access protected
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_zendDbTable = null;

    /** The table name
     * @access protected
     * @var string
     */
    protected $_tableName = 'users';

    /** Identity column in the database
     * @access protected
     * @var string
     */
    protected $_identityColumn = 'username';

    /** primaryKeyColumn - the column to use as the primaryKey
     * @access protected
     * @var string
     */
    protected $_primaryKeyColumn = 'id';

    /** The id key to check
     * @access protected
     * @var string
     */
    protected $_idKey = 'id';

    /** The constructor
     * @access public
     * @param string $idKey
     * @param string $identityColumn
     * @param string $primaryKeyColumn
     * @param string $tableName
     */
    public function __construct($idKey = 'id', $identityColumn='username', $primaryKeyColumn='id', $tableName='users' ) {
        $this->setTableName($tableName);
        $this->_zendDbTable = new $this->_tableName;
        $this->setIdentityColumn($identityColumn);
        $this->setPrimaryKeyColumn($primaryKeyColumn);
        $this->setIdKey($idKey);
    }

    /** Set the table name to be used in the select query
     * @access public
     * @param  string $tableName
     * @return \Zend_Auth_Adapter_DbTable
     */
    public function setTableName($tableName) {
        $this->_tableName = $tableName;
        return $this;
    }

    /** Set the id key
     * @access public
     * @param string $idKey
     * @return \Pas_Validate_UsernameUnique
     */
    public function setIdKey($idKey) {
        $this->_idKey = $idKey;
        return $this;
    }

    /** Set the column name to be used as the identity column
     * @access public
     * @param  string $identityColumn
     * @return Zend_Auth_Adapter_DbTable
     */
    public function setIdentityColumn($identityColumn) {
        $this->_identityColumn = $identityColumn;
        return $this;
    }

    /** Set the column name to be used as the primaryKey column
     * @access public
     * @param  string $primaryKeyColumn
     * @return Zend_Auth_Adapter_DbTable
     */
    public function setPrimaryKeyColumn($primaryKeyColumn) {
        $this->_primaryKeyColumn = $primaryKeyColumn;
        return $this;
    }
    
    /** Validation failure message template definitions
     * @access protected
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_UNIQUE => 'That username is already in use, please try again.'
    );

    /** Check if valid
     * @access public
     * @param string $value
     * @param string $context
     * @return boolean
     */
    public function isValid($value, $context = null) {
        $this->_setValue($value);
        $where = array();
        // Check that this is not the user's own identity
        if (isset($context[$this->_idKey])) {
            $where[] = $this->_zendDbTable->getAdapter()->quoteInto($this->_primaryKeyColumn . ' != ?', $context[$this->_idKey]);
        }
        $where[] = $this->_zendDbTable->getAdapter()->quoteInto($this->_identityColumn . ' = ?', $value);
        $row = $this->_zendDbTable->fetchRow($where);
        if (null !== $row) {
            $this->_error(self::NOT_UNIQUE);
            return false;
        }
        return true;
    }
}