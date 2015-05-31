<?php

/**
 * Model for manipulating contacts data for staff at the PAS
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new Contacts();
 * $data = $model-> getPersonDetails($id);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/admin/controllers/ContactsController.php
 */
class Contacts extends Pas_Db_Table_Abstract
{

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'staff';

    /** The primary key
     * @access protected
     * @var type
     */
    protected $_primary = 'id';

    /** Get person's details
     * @access public
     * @param int $id
     * @return array
     */
    public function getPersonDetails($id)
    {
        $key = md5('currentstaffmember' . $id);
        if (!$data = $this->_cache->load($key)) {
            $persons = $this->getAdapter();
            $select = $persons->select()
                ->from($this->_name, array(
                    'number' => 'id', 'firstname', 'lastname',
                    'email_one', 'email_two', 'address_1',
                    'address_2', 'identifier', 'town',
                    'county', 'postcode', 'country',
                    'profile', 'telephone', 'fax',
                    'dbaseID', 'longitude', 'latitude',
                    'image', 'created', 'updated', 
                    'createdBy', 'updatedBy'))
                ->joinLeft(array('locality' => 'staffregions'), 'locality.ID = staff.region', 
                array('description'))
                ->joinLeft('instLogos', $this->_name . '.identifier = instID',
                    array('host' => 'image'))
                ->joinLeft(array('position' => 'staffroles'), 'staff.role = position.ID',
                    array('staffroles' => 'role'))
                ->where('staff.id = ?', (int)$id)
                ->group('staff.id');
            $data = $persons->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get person's image
     * @param integer $id
     * @return array
     * @todo add caching and change to fetchrow
     */
    public function getImage($id)
    {
        $persons = $this->getAdapter();
        $select = $persons->select()
            ->from($this->_name, array('image'))
            ->where('staff.id= ?', (int)$id);
        return $persons->fetchAll($select);
    }

    /** Get a list of alumni
     * @access public
     * @return array
     */
    public function getAlumniList()
    {
        $key = md5('alumniList');
        if (!$data = $this->_cache->load($key)) {
            $persons = $this->getAdapter();
            $select = $persons->select()
                ->from('staff', array(
                    'id', 'firstname', 'lastname',
                    'email_one', 'address_1', 'address_2',
                    'town', 'county', 'postcode',
                    'telephone', 'fax', 'role'
                ))
                ->joinLeft(array('locality' => 'staffregions'),
                    'locality.ID = staff.region',
                    array('staffregions' => 'description'))
                ->joinLeft(array('position' => 'staffroles'),
                    'staff.role = position.ID',
                    array('staffroles' => 'role'))
                ->where('alumni = ?', (int)0)
                ->order('lastname');
            $data = $persons->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get a list of current staff to display on the map of contacts
     * @access public
     * @return array
     */
    public function getContactsForMap()
    {
        $persons = $this->getAdapter();
        $select = $persons->select()
            ->from($this->_name, array(
                'id', 'firstname', 'lastname',
                'email_one', 'email_two', 'address_1',
                'address_2', 'identifier', 'town',
                'county', 'postcode', 'country',
                'profile', 'telephone', 'fax',
                'dbaseID', 'longitude', 'latitude',
                'image', 'alumni'
            ))
            ->joinLeft(array('locality' => 'staffregions'),
                'locality.ID = staff.region',
                array('area' => 'DESCRIPTION'))
            ->joinLeft(array('position' => 'staffroles'),
                'staff.role = position.ID',
                array('role', 'roleid' => 'id'))
            ->where('alumni = ?', (int)1);
        return $contacts = $persons->fetchAll($select);
    }

    /** Get a list of current staff to display on the map of contacts
     * @params array $params
     * @return array
     * @todo add caching
     */
    public function getContacts(array $params)
    {
        $persons = $this->getAdapter();
        $select = $persons->select()
            ->from($this->_name, array(
                'id', 'firstname', 'lastname',
                'email_one', 'email_two', 'address_1',
                'address_2', 'identifier', 'town',
                'county', 'postcode', 'country',
                'profile', 'telephone', 'fax',
                'dbaseID', 'longitude', 'latitude',
                'image', 'alumni'))
            ->joinLeft(array('locality' => 'staffregions'),
                'locality.ID = staff.region',
                array('area' => 'DESCRIPTION'))
            ->joinLeft(array('position' => 'staffroles'),
                'staff.role = position.ID',
                array('role', 'roleid' => 'id'))
            ->where('alumni = ?', (int)1);
        $paginator = Zend_Paginator::factory($select);
        if (isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber($params['page']);
        }
        $paginator->setItemCountPerPage(20)->setPageRange(10);
        return $paginator;
    }

    /** Get a list of old staff to display on the map of contacts
     * @access public
     * @param array $params
     * @return array
     * @todo add caching
     */
    public function getAlumni($params)
    {
        $persons = $this->getAdapter();
        $select = $persons->select()
            ->from($this->_name, array(#
                'id', 'firstname', 'lastname',
                'email_one', 'email_two', 'address_1',
                'address_2', 'identifier', 'town',
                'county', 'postcode', 'country',
                'profile', 'telephone', 'fax',
                'dbaseID', 'longitude', 'latitude',
                'image', 'alumni'
            ))
            ->joinLeft(array('locality' => 'staffregions'),
                'locality.ID = staff.region',
                array('area' => 'DESCRIPTION'))
            ->joinLeft(array('position' => 'staffroles'),
                'staff.role = position.ID',
                array('role', 'roleid' => 'id'))
            ->where('alumni = ?', (int)0);
        $paginator = Zend_Paginator::factory($select);
        if (isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']);
        }
        $paginator->setItemCountPerPage(20)->setPageRange(10);
        return $paginator;
    }

    /** Get a list of current staff for the central unit
     * @access public
     * @return array
     */
    public function getCentralUnit()
    {
        $key = md5('centralUnit');
        if (!$data = $this->_cache->load($key)) {
            $persons = $this->getAdapter();
            $select = $persons->select()
                ->from($this->_name, array(
                    'id', 'firstname', 'lastname',
                    'email_one', 'address_1', 'address_2',
                    'town', 'county', 'postcode',
                    'telephone', 'fax', 'role',
                    'longitude', 'latitude', 'image'
                ))
                ->joinLeft(array('position' => 'staffroles'), 'staff.role = position.ID', array('staffroles' => 'role'))
                ->joinLeft('users', 'users.id = staff.dbaseID', array('institution'))
                ->where('staff.role IN (1,2,3,4,24,25)')
                ->where('alumni = ?', (int)1)
                ->order('lastname');
            $data = $persons->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get a list of current finds liaison officers
     * @access public
     * @return array
     */
    public function getLiaisonOfficers()
    {
        $key = md5('liaisonOfficers');
        if (!$data = $this->_cache->load($key)) {
            $persons = $this->getAdapter();
            $select = $persons->select()
                ->from($this->_name, array(
                    'id', 'firstname', 'lastname',
                    'email_one', 'address_1', 'address_2',
                    'town', 'county', 'postcode',
                    'telephone', 'fax', 'longitude',
                    'latitude', 'image'
                ))
                ->joinLeft(array('locality' => 'staffregions'),
                    'locality.ID = staff.region',
                    array('staffregions' => 'description'))
                ->joinLeft(array('position' => 'staffroles'),
                    'staff.role = position.ID',
                    array('staffroles' => 'role'))
                ->joinLeft('users', 'users.id = staff.dbaseID', array('institution'))

                ->where('staff.role IN (7,10) AND alumni =1')
                ->order('locality.description');
            $data = $persons->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get a list of current treasure team
     * @access public
     * @return array
     */
    public function getTreasures()
    {
        $key = md5('treasureTeam');
        if (!$data = $this->_cache->load($key)) {
            $persons = $this->getAdapter();
            $select = $persons->select()
                ->from($this->_name, array(
                    'id', 'firstname', 'lastname',
                    'email_one', 'address_1', 'address_2',
                    'town', 'county', 'postcode',
                    'telephone', 'fax', 'role',
                    'longitude', 'latitude', 'image'
                ))
                ->joinLeft(array('position' => 'staffroles'),
                    'staff.role = position.ID',
                    array('staffroles' => 'role'
                    ))
                ->where('staff.role IN (6,8)')
                ->where('alumni = ?', (int)1)
                ->order('lastname');
            $data = $persons->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get a list of current finds adviser team
     * @access public
     * @return array
     */
    public function getAdvisers()
    {
        $key = md5('findsAdvisers');
        if (!$data = $this->_cache->load($key)) {
            $persons = $this->getAdapter();
            $select = $persons->select()
                ->from($this->_name, array(
                    'id', 'firstname', 'lastname',
                    'email_one', 'address_1', 'address_2',
                    'town', 'county', 'postcode',
                    'telephone', 'fax', 'role',
                    'longitude', 'latitude', 'image'
                ))
                ->joinLeft(array('position' => 'staffroles'),
                    'staff.role = position.ID',
                    array('staffroles' => 'role'))
                ->where('staff.role IN (12,16,17,18,19,20)')
                ->joinLeft('users', 'users.id = staff.dbaseID', array('institution'))
                ->where('alumni = ?', (int)1)
                ->order('lastname');
            $data = $persons->fetchAll($select);
            $this->_cache->save($data, 'findsAdvisers');
        }
        return $data;
    }


    /** Get a list of all current staff
     * @access public
     * @return array
     */
    public function getCurrent()
    {
        $key = md5('currentStaff');
        if (!$data = $this->_cache->load($key)) {
            $persons = $this->getAdapter();
            $select = $persons->select()
                ->from($this->_name, array(
                    'id', 'firstname', 'lastname',
                    'email_one', 'address_1', 'address_2',
                    'town', 'county', 'postcode',
                    'telephone', 'fax', 'role',
                    'longitude', 'latitude', 'created',
                    'updated', 'profile'
                ))
                ->joinLeft(array('locality' => 'staffregions'),
                    'locality.regionID = staff.region',
                    array('staffregions' => 'description'))
                ->joinLeft(array('position' => 'staffroles'),
                    'staff.role = position.ID',
                    array('staffroles' => 'role'))
                ->order($this->_name . '.id')
                ->where('alumni = ?', (int)1);
            $data = $persons->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get a list of all current staff
     * @access public
     * @return array
     */
    public function getFloEmailsForForm()
    {
        $key = md5('currentstaffpairs');
        if (!$data = $this->_cache->load($key)) {
            $persons = $this->getAdapter();
            $select = $persons->select()
                ->from($this->_name, array(
                    'id' => 'dbaseID', 'name' => new Zend_Db_Expr("CONCAT(firstname,' ',lastname,': ',county)")))
                ->order($this->_name . '.id')
                ->where('alumni = ?', (int)1)
                ->where('role IN (7,10)');
            $data = $persons->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get the email and name for and ID
     * @access public
     * @param integer $id
     * @return array
     */
    public function getNameEmail($id)
    {
        $key = md5('staffemail' . $id);
        if (!$data = $this->_cache->load($key)) {
            $persons = $this->getAdapter();
            $select = $persons->select()
                ->from($this->_name, array(
                    'email' => 'email_one', 'name' => new Zend_Db_Expr("CONCAT(firstname,' ',lastname)")))
                ->where('alumni = ?', (int)1)
                ->where('dbaseID = ?', (int)$id);
            $data = $persons->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get a dropdown list of attending staff
     * @access public
     * @return array
     */
    public function getAttending()
    {
        $persons = $this->getAdapter();
        $select = $persons->select()
            ->from($this->_name, array('dbaseID', 'term' => new Zend_Db_Expr("CONCAT(firstname,' ',lastname)")))
            ->order($this->_name . '.firstname');
        return $persons->fetchPairs($select);
    }

    /** Retrieve the owner of a find record
     * @param integer $findID the find record ID number
     * @return array
     */
    public function getOwner($findID)
    {
        $key = 'owneroffind' . $findID;
        if (!$accounts = $this->_cache->load($key)) {
            $users = $this->getAdapter();
            $select = $users->select()
                ->from($this->_name, array(
                    'name' => new Zend_Db_Expr("CONCAT(firstname,' ', lastname)"),
                    'email' => 'email_one'
                ))
                ->joinLeft('finds', 'finds.institution = '
                    . $this->_name . '.identifier', array())
                ->where('finds.id = ?', (int)$findID)
                ->where($this->_name . '.alumni = ?', 1);
            $accounts = $users->fetchAll($select);
            $this->_cache->save($accounts, $key);
        }
        return $accounts;
    }

    /** Retrieve the owner of a find record
     * @param integer $findID the find record ID number
     * @return array
     */
    public function getOwnerHoard($hoardID)
    {
        $key = 'ownerofhoard' . $hoardID;
        if (!$accounts = $this->_cache->load($key)) {
            $users = $this->getAdapter();
            $select = $users->select()
                ->from($this->_name, array(
                    'name' => new Zend_Db_Expr("CONCAT(firstname,' ', lastname)"),
                    'email' => 'email_one'
                ))
                ->joinLeft('hoards', 'hoards.institution = '
                    . $this->_name . '.identifier', array())
                ->where('hoards.id = ?', (int)$hoardID)
                ->where($this->_name . '.alumni = ?', 1);
            $accounts = $users->fetchAll($select);
            $this->_cache->save($accounts, $key);
        }
        return $accounts;
    }

    public function resizeImage()
    {
        $thumbnailWidth = 100;
        $resizedWidth = 300;
    }
}
