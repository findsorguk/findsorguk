<?php
/** Model for pulling audited data from database for organisations
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $audit = new OrganisationsAudit();
 * $auditBaby = $audit->insert($f);
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
 * @todo add caching
 * @example /app/models/OrganisationsAudit.php
*/
class OrganisationsAudit extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'organisationsAudit';
	
    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';
	 
    /** Get all changes for a particular organisation
     * @access public
     * @param integer $organisationID
     * @return array
     */
    public function getChanges($organisationID) {
        $finds = $this->getAdapter();
        $select = $finds->select()
                ->from($this->_name,array($this->_name . '.created','findID','editID'))
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
                        array('id', 'fullname', 'username'))
                ->where($this->_name . '.id= ?',(int)$organisationID)
                ->order($this->_name . '.id DESC')
                ->group($this->_name . '.created');
        return  $finds->fetchAll($select);
    }

    /** Get a specific change to an organisation by edit number
     * @access public
     * @param integer $editID
     * @return array
     */
    public function getChange($editID) {
        $finds = $this->getAdapter();
        $select = $finds->select()
                ->from($this->_name,array($this->_name.'.created','afterValue','fieldName','beforeValue'))
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
			array('id', 'fullname', 'username'))
                ->where($this->_name . '.editID= ?',$editID)
                ->order($this->_name . '.' . $this->_primaryKey);
	return $finds->fetchAll($select);
    }
}