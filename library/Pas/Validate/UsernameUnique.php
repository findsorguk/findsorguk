<?php
/**
 * A validation class for checking for unique usernames
 * @category   Pas
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_Validate_Abstract
 */
class Pas_Validate_UsernameUnique extends Zend_Validate_Abstract
{
    const NOT_UNIQUE = 'notUnique';

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

    public function __construct($idKey = 'id', $identityColumn='username', $primaryKeyColumn='id', $tableName='users' )
    {
        $this->setTableName($tableName);
        include_once $this->_tableName . '.php';
        $this->_zendDbTable = new $this->_tableName;
        $this->setIdentityColumn($identityColumn);
        $this->setPrimaryKeyColumn($primaryKeyColumn);
        $this->setIdKey($idKey);
    }

    /**
     * setTableName() - set the table name to be used in the select query
     *
     * @param  string $tableName
     * @return Zend_Auth_Adapter_DbTable
     */
    public function setTableName($tableName)
    {
        $this->_tableName = $tableName;
        return $this;
    }

    public function setIdKey($idKey)
    {
        $this->_idKey = $idKey;
        return $this;
    }

    /**
     * setIdentityColumn() - set the column name to be used as the identity column
     *
     * @param  string $identityColumn
     * @return Zend_Auth_Adapter_DbTable
     */
    public function setIdentityColumn($identityColumn)
    {
        $this->_identityColumn = $identityColumn;
        return $this;
    }

    /**
     * setPrimaryKeyColumn() - set the column name to be used as the primaryKey column
     *
     * @param  string $primaryKeyColumn
     * @return Zend_Auth_Adapter_DbTable
     */
    public function setPrimaryKeyColumn($primaryKeyColumn)
    {
        $this->_primaryKeyColumn = $primaryKeyColumn;
        return $this;
    }
    
	/**
	* Validation failure message template definitions
	*
	* @var array
	*/
    protected $_messageTemplates = array(
        self::NOT_UNIQUE => 'That username is already in use, please try again.'
    );

    public function isValid($value, $context = null)
    {
        $value = (string) $value;
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