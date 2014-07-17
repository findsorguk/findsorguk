<?php
/** Model for pulling organisational data from database
 * 
 * An example of use:
 * 
 * <code>
 * $organisations = new Organisations();
 * $paginator = $organisations->getOrganisations($params);
 * </code>
 * 
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/modules/database/controllers/OrganisationsController.php
*/
class Organisations extends Pas_Db_Table_Abstract {
    
    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'organisations';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';
	
    /** Get a list of organisations as key value pairs
     * @access public
     * @return array
     */
    public function getOrgs() {
        $select = $this->select()
                ->from($this->_name, array('secuid', 'name'))
                ->order('name');
        return $this->getAdapter()->fetchPairs($select);
    }
    
    /** Search for organisation name via query string
     * @access public
     * @param type $q
     * @return type
     */
    public function getOrgNames($q) {
        $select = $this->select()
                ->from($this->_name, array('id' => 'secuid', 'term' => 'name'))
                ->where('name LIKE ?', (string)'%' . $q . '%')
                ->order('name')
                ->limit(20);

        return $this->getAdapter()->fetchAll($select);
    }
	
    /** Get an organisation's details by id number
     * @access public
     * @param integer $id
     * @return array
     */
    public function getOrgDetails($id){
        $orgs = $this->getAdapter();
        $select = $orgs->select()
                ->from($this->_name)
                ->joinLeft('countries','organisations.country = countries.iso', 
                        array('abode' => 'printable_name'))
                ->joinLeft('people','organisations.contactpersonID = people.secuid', 
                        array('fn' => 'fullname', 'tit' => 'title','i' => 'id'))
                ->where('organisations.id = ?', (int)$id);
        return $orgs->fetchAll($select);
    }

    /** Get a list of members for organisation
     * @access public
     * @param type $id
     * @return type
     */
    public function getMembers($id) {
        $orgs = $this->getAdapter();
        $select = $orgs->select()
                ->from($this->_name)
                ->joinLeft('people','organisations.secuid = people.organisationID', 
                        array('fnmembers' => 'fullname','i' => 'id'))
                ->where('fullname IS NOT NULL AND organisations.id = ?', (int)$id)
                ->order('fnmembers ASC');
        return $orgs->fetchAll($select);
    }
	

    /** Get a list of organisations paginated
     * @access public
     * @param array $params
     * @return \Zend_Paginator
     */
    public function getOrganisations($params) {
        $orgs = $this->getAdapter();
        $select = $orgs->select()
                ->from($this->_name)
                ->joinLeft('people','organisations.contactpersonID = people.secuid', 
                        array('fullname','i' => 'id'))
                ->order(array('name'));
        if(isset($params['county']) && ($params['county'] != ""))  {
            $county = strip_tags($params['county']);
            $select->where($this->_name . '.county = ?', (string)$county);
        }
        if(isset($params['organisation']) && ($params['organisation'] != ""))  {
            $org = strip_tags($params['organisation']);
            $select->where('name = ?', (string)$org);
        }
        if(isset($params['contactpersonID']) && ($params['contactpersonID'] != "")) {
            $con = strip_tags($params['contactpersonID']);
            $select->where('contactpersonID = ?', (string)$con);
        }
        $paginator = Zend_Paginator::factory($select);
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']); 
        }

        $paginator->setItemCountPerPage(50)->setPageRange(10); 
        return $paginator;
    }
}