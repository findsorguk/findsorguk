<?php
/** Model for manipulating institutional data
 *
 * An example of code:
 *
 * <code>
 * <?php
 * $inst = new Institutions();
 * $inst_options = $inst->getInsts();
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license GNU General Public License
 * @version 1
 * @since 22 September 2011
 * @example path description
 */
class Institutions extends Pas_Db_Table_Abstract {

    /** The institution name
     * @access protected
     * @var string
     */
    protected $_name = 'institutions';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get list of institutions on website
     * @access public
     * @return array $data
     * @todo add caching
     */
    public function getInsts() {
        $roles = $this->getAdapter();
        $select = $roles->select()
                ->from($this->_name, array('institution','institution'))
                ->where('valid = ?',(int)1)
                ->order('institution');
        return $roles->fetchPairs($select);
    }

    /** Get paginated list of institutions that are valid
     * @access public
     * @param array $params
     * @return \Zend_Paginator $paginator
     */
    public function getValidInsts($params) {
        $roles = $this->getAdapter();
        $select = $roles->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = '.$this->_name . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = '.$this->_name . '.updatedBy', array('fn' => 'fullname'));
        $data = $roles->fetchAll($select);
        $paginator = Zend_Paginator::factory($data);
        Zend_Paginator::setCache(Zend_Registry::get('cache'));
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']);
        }
        $paginator->setItemCountPerPage(20)->setPageRange(10);
        return $paginator;
    }

    /** Get your institution by role
     * @param integer $role - is this correct?
     * @return array $data
     * @todo add caching
     */
    public function getInst($role) {
        $roles = $this->getAdapter();
        $select = $roles->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name
                        . '.updatedBy', array('fn' => 'fullname'))
                ->where($this->_name.'.id = ?',(int)$role);
        return $roles->fetchAll($select);
    }

    /** List collections - what was this one for?
     * @access public
     * @return array $data
     * @todo add caching
     */
    public function listCollections() {
        $roles = $this->getAdapter();
        $select = $roles->select()
                ->from($this->_name,
                        array('id' => 'institution','name' => 'description'))
                ->where('valid = ?',(int)1)
                ->order('institution');
        return $roles->fetchAll($select);
    }
}
