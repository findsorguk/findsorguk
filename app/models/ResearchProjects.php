<?php
/** Data model for accessing and manipulating registered research topics
 *
 * An example of code:
 *
 * <code>
 * <?php
 * $model = new ResearchProjects();
 * $data = $model->getCounts();
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
 * @since 22 October 2010, 17:12:34
 * @todo add edit and delete functions
 * @todo add caching
 * @example /app/modules/default/controllers/AjaxController.php
 */

class ResearchProjects extends Pas_Db_Table_Abstract {

    /** the table name
     * @access protected
     * @var string
     */
    protected $_name = 'researchprojects';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The higher level array
     * @access protected
     * @var array
     */
    protected $_higherlevel = array( 'admin', 'flos', 'fa', 'treasure');

    /** The restricted array
     * @access protected
     * @var array
     */
    protected $_restricted = array( null, 'public', 'member' , 'hero');

    /** Get a count of the project types
     * @access public
     * @return array
     */
    public function getCounts() {
        $projects = $this->getAdapter();
        $select = $projects->select()
                ->from($this->_name, array('level','quantity' => 'COUNT(level)'))
                ->joinLeft('projecttypes',$this->_name . '.level = projecttypes.id', array('type' => 'title'))
                ->group('level');
        return $projects->fetchAll($select);
    }

    /** Get a paginated list of the project types
     * @access public
     * @param array $params
     * @return \Zend_Paginator
     */
    public function getProjects($params) {
        $projects = $this->getAdapter();
        $select = $projects->select()
                ->from($this->_name)
                ->joinLeft('projecttypes',$this->_name . '.level = projecttypes.id',
                        array('type' => 'title'));
        $paginator = Zend_Paginator::factory($select);
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']);
        }
        $paginator->setItemCountPerPage(20)->setPageRange(10);
        return $paginator;
    }

    /** Get a specific project by id nymber
     * @access public
     * @param integer $id
     * @return array
     */
    public function getProjectDetails($id) {
        $projects = $this->getAdapter();
        $select = $projects->select()
                ->from($this->_name)
                ->joinLeft('projecttypes',$this->_name
                        . '.level = projecttypes.id',
                        array('type' => 'title'))
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
                        array('fullname', 'email', 'userid' => 'id'))
                ->joinLeft('users','users_2.id = ' . $this->_name
                        . '.updatedBy',
                        array('fn' => 'fullname'))
                ->where($this->_name . '.' . $this->_primary . ' = ?',(int)$id);
    return $projects->fetchAll($select);
    }

    /** Get projects added by a user
     * @access public
     * @param integer $user
     * @return array
     */
    public function getMyProjects($user) {
        $projects = $this->getAdapter();
        $select = $projects->select()
                ->from($this->_name)
                ->joinLeft('projecttypes',$this->_name
                        . '.level = projecttypes.id',
                        array('type' => 'title'))
                ->where($this->_name . '.createdBy = ?', (int)$user);
        return $projects->fetchAll($select);
    }

    /** Get projects list for feeds, limited to 25
     * @access public
     * @return array
     */
    public function getProjectsFeeds() {
        $projects = $this->getAdapter();
        $select = $projects->select()
                ->from($this->_name)
                ->joinLeft('projecttypes',$this->_name
                        . '.level = projecttypes.id',
                        array('type' => 'title'))
                ->limit(25);
        return $projects->fetchAll($select);
    }


    /** Get a specific project
     * @access public
     * @param integer $id
     * @return array
     */
    public function getProject($id) {
        $projects = $this->getAdapter();
        $select = $projects->select()
                ->from($this->_name)
                ->joinLeft('projecttypes',$this->_name
                        . '.level = projecttypes.id',
                        array('type' => 'title'))
                ->joinLeft('people',$this->_name
                        . '.investigator = people.secuid',
                        array('fullname'))
                ->where($this->_name . '.id = ?',(int)$id);
        return $projects->fetchAll($select);
    }

    /** Get a list of all projects paginated
     * @access public
     * @param array $params
     * @return \Zend_Paginator
     */
    public function getAllProjects($params) {
        $projects = $this->getAdapter();
        $select = $projects->select()
                ->from($this->_name,array(
                    'id',
                    'title',
                    'description',
                    'start' =>'DATE_FORMAT(startDate,"%Y")',
                    'finish' => 'DATE_FORMAT(endDate,"%Y")',
                    'investigator',
                    'level',
                    'updated'
                    ))
                ->joinLeft('projecttypes',$this->_name
                        . '.level = projecttypes.id',
                        array('type' => 'title'))
                ->joinLeft('users','users.id = ' . $this->_name
                        . '.createdBy', array(
                            'fullname', 'email', 'userid' => 'id'
                            ))

                ->joinLeft('users','users_2.id = '
                        . $this->_name . '.updatedBy',
                        array('fn' => 'fullname'))
                ->order('id DESC');
        if(isset($params['level'])) {
            $select->where($this->_name . '.level = ?', (int)$params['level']);
        }
        if(in_array($this->getUserRole(), $this->_restricted)) {
            $select->where($this->_name . '.valid = ?',(int)1);
        }
        $paginator = Zend_Paginator::factory($select);
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']);
        }
        $paginator->setItemCountPerPage(20)->setPageRange(10);
        return $paginator;
    }
}