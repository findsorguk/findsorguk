<?php
/**
 * A validation class for checking if a user is valid to use the database
 * @category   Pas
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_Validate_Abstract
 */
class Pas_Validate_UserValid extends Zend_Validate_Abstract
{
    const NOT_Valid = 'notValid';

    /**
     * Database Connection
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_zendDbTable = null;

    /**
     * $_tableName - the table name to check
     *
     * @var string
     */
    protected $_tableName = null;

    /**
     * $_identityColumn - the column to use as the identity
     *
     * @var string
     */
    protected $_identityColumn = null;

    /**
     * $_primaryKeyColumn - the column to use as the primaryKey
     *
     * @var string
     */
    protected $_primaryKeyColumn = null;

    protected $_idKey = null;

    public function __construct($Key = 'activationKey', $identityColumn='username', 
    $tableName='users', $emailColumn = 'email' ){
	$this->setTableName($tableName);
	include_once $this->_tableName . '.php';
	$this->_zendDbTable = new $this->_tableName;
	$this->setIdentityColumn($identityColumn);
	$this->setEmailColumn($emailColumn);
	$this->setKey($Key);
    }

    /**
     * setTableName() - set the table name to be used in the select query
     *
     * @param  string $tableName
     * @return Zend_Auth_Adapter_DbTable
     */
    public function setTableName($tableName){
        $this->_tableName = $tableName;
        return $this;
    }

    public function setKey($Key){
        $this->_Key = $Key;
        return $this;
    }

    /**
     * setIdentityColumn() - set the column name to be used as the identity column
     *
     * @param  string $identityColumn
     * @return Zend_Auth_Adapter_DbTable
     */
    public function setIdentityColumn($identityColumn){
	$this->_identityColumn = $identityColumn;
	return $this;
    }

    /**
     * setPrimaryKeyColumn() - set the column name to be used as the primaryKey column
     *
     * @param  string $primaryKeyColumn
     * @return Zend_Auth_Adapter_DbTable
     */
    public function setEmailColumn($emailColumn){
	$this->_emailColumn = $emailColumn;
	return $this;
    }
	
	/**
	* Validation failure message template definitions
	*
	* @var array
	*/
    protected $_messageTemplates = array(
    self::NOT_VALID => 'That activation key has already been used. 
    If you feel that there has been an error, please contact info@finds.org.uk'
    );
    
	/* Check if value is valid
	*/
    public function isValid($username, $key, $email) {
	$username = $this->_getParam('username');
	$key = $this->_getParam('key');
	$email = $this->_getParam('email');
	$where = array();
    // Check that this is not the user's own identity
	$where[] = $this->_zendDbTable->getAdapter()->quoteInto($this->_Key . ' = ?', $key);
	$where[] = $this->_zendDbTable->getAdapter()->quoteInto($this->_emailColumn . ' = ?', $email);
	$where[] = $this->_zendDbTable->getAdapter()->quoteInto($this->_identityColumn . ' = ?', $username);
	$row = $this->_zendDbTable->fetchRow($where);
	if (null !== $row) {
	$this->_error(self::NOT_VALID);
	return false;
	}
	return true;
    }
}