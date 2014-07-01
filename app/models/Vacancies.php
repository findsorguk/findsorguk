<?php
/**  Data model for accessing vacancy data in database
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $model = new Vacancies();
 * $data = $model->getJobs($limit);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license GNU General Public License
 * @version 1
 * @example /app/modules/about/controllers/VacanciesController.php 
 */
class Vacancies extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'vacancies';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get current vacancies
     * @access public
     * @return array
     */
    public function getCurrent() {
        $select = $this->select()
                ->from($this->_name)
                ->order('id DESC');
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get current vacancies live
     * @access public
     * @param integer $page
     * @return \Zend_Paginator
     */
    public function getLiveJobs($page){
        $livejobs = $this->getAdapter();
        $select = $livejobs->select()
                ->from($this->_name)
                ->joinLeft(array('locality' => 'staffregions'),
                        'locality.ID = vacancies.regionID',
                        array('staffregions' => 'description'))
                ->where('live <= ?', $this->timeCreation())
                ->where('expire >= ?', $this->timeCreation())
                ->order('id');
        $paginator = Zend_Paginator::factory($select);
        Zend_Paginator::setCache($this->_cache);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber((int)$page);
        }
        return $paginator;
    }

    /** Get current vacancies live limited
     * @access public
     * @param integer $limit
     * @return array
     */
    public function getJobs($limit) {
        $livejobs = $this->getAdapter();
        $select = $livejobs->select()
                ->from($this->_name)
                ->joinLeft(array('locality' => 'staffregions'),
                        'locality.ID = vacancies.regionID',
                        array('staffregions' => 'description'))
                ->where('status = ?', (int)2)
                ->order('id DESC')
                ->limit((int)$limit);
        return $livejobs->fetchAll($select);
    }

    /** Get archived vacancies live limited
     * @access public
     * @param integer $page
     * @return array
     */
    public function getArchiveJobs($page){
        $archivejobs = $this->getAdapter();
        $select = $archivejobs->select()
                ->from($this->_name)
                ->joinLeft(array('locality' => 'staffregions'),
                        'locality.ID = vacancies.regionID',
                        array('staffregions' => 'description'))
                ->where('live <= CURDATE()')
                ->where('expire <= CURDATE()')
                ->where('status = ?', (int)2)
                ->order('id');
        $data = $archivejobs->fetchAll($select);
        $paginator = Zend_Paginator::factory($data);
        Zend_Paginator::setCache($this->_cache);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber((int)$page);
        }
        return $paginator;
    }

    /** Get job details by ID
     * @access public
     * @param integer $id
     * @return array
     */
    public function getJobDetails($id){
        $details = $this->getAdapter();
        $select = $details->select()
                ->from($this->_name)
                ->joinLeft(array('locality' => 'staffregions'),
                        'locality.ID = vacancies.regionID',
                        array('staffregions' => 'description'))
                ->where($this->_name . '.id = ?', (int)$id);
        return $details->fetchAll($select);
    }

    /** Get vacancies for admin
     * @access public
     * @param integer $page
     * @return \Zend_Paginator
     */
    public function getJobsAdmin($page){
        $livejobs = $this->getAdapter();
        $select = $livejobs->select()
                ->from($this->_name)
                ->joinLeft(array('locality' => 'staffregions'),
                        'locality.ID = vacancies.regionID',
                        array('staffregions' => 'description'))
                ->joinLeft('users','users.id = ' . $this->_name 
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name 
                        . '.updatedBy', array('fn' => 'fullname'))
                ->order('id DESC');
        $data = $livejobs->fetchAll($select);
        $paginator = Zend_Paginator::factory($data);
        Zend_Paginator::setCache($this->_cache);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber((int)$page);
        }
        return $paginator;
    }
}