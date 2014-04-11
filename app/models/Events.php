<?php
/** Model for manipulating events data
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/

class Events extends Pas_Db_Table_Abstract {

    protected $_name = 'events';

    protected $_primary = 'id';

    protected $higherlevel = array('flos', 'fa');

    public function getIdentityForForms() {
    if($this->_auth->hasIdentity()) {
    $user = $this->_auth->getIdentity();
    $id = $user->id;
    return $id;
    } else {
    $id = '3';
    return $id;
    }
    }

    public function getRole() {
    if($this->_auth->hasIdentity()) {
    $user = $this->_auth->getIdentity();
    $role = $user->role;
    return $role;
    } else {
    $role = 'public';
    return $role;
    }
    }

    /**
    * Retrieves all event types on a certain day
    * @param date $date
    * @return array
    */

    public function getEventbyDay($date = NULL){
    $select = $this->select()->from($this->_name)
            ->where('events.eventStartDate = ?',$date);
    $events = $this->getAdapter()->fetchAll($select);
    return $events;
    }

    /**
    * Retrieves all event types that we list
    * @param integer $type
    * @param integer $year
    * @return array
    */

    public function getEventByType($type, $year){
    $select = $this->select()
            ->from($this->_name,array(
                    'id',
                    'name' => 'eventTitle',
                    'eventDescription',
                    'eventStartDate',
                    'eventEndDate',
                    'lat' => 'latitude',
                    'lng' => 'longitude',
                    'organisation'
                    ))
            ->where('EXTRACT(YEAR FROM eventStartDate) = ?',(int)$year)
            ->where($this->_name . '.eventType = ?',(int)$type)
            ->order('eventStartDate ASC');
    $events = $this->getAdapter()->fetchAll($select);
    return $events;
    }

    /**
    * Retrieves all event data by id number
    * @param integer $id
    * @return array
    */

    public function getEventData($id){
    $events = $this->getAdapter();
    $select = $events->select()
        ->from($this->_name)
        ->joinLeft('users','users.id = events.createdBy',array('fullname','email'))
        ->joinLeft('staffregions','events.eventRegion = staffregions.id',array('location' => 'description'))
        ->joinLeft('eventtypes',$this->_name.'.eventType = eventtypes.id',array('type'))
        ->where('events.id = ?',(int)$id);
    return $events->fetchAll($select);
    }

    /**
    * Retrieves all event datae by id number
    * @param integer $id
    * @param array $params - page etc
    * @return array
    */

    public function getEventsDate($day,$params) {
    $events = $this->getAdapter();
    $select = $events->select()
        ->from($this->_name)
        ->joinLeft('staffregions','events.eventRegion = staffregions.id',
                array('rID' => 'ID','description'))
        ->joinLeft('users','users.id = '.$this->_name.'.createdBy',array('fullname'))
        ->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy',array('fn' => 'fullname'))
        ->where('eventStartDate = ?', $day);
    $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage(30)
            ->setPageRange(10);
    if(isset($params['page']) && ($params['page'] != "")) {
    $paginator->setCurrentPageNumber($params['page']);
            }
    return $paginator;
    }

    /**
    * Retrieves all event datae by id number
    * @param integer $page
    * @return array
    */

    public function getEventsAdmin($page) {
    $events = $this->getAdapter();
    $select = $events->select()
            ->from($this->_name)
            ->joinLeft('staffregions','events.eventRegion = staffregions.id',
                    array('rID' => 'ID','description'))
            ->joinLeft('users','users.id = '.$this->_name.'.createdBy',
                    array('fullname'))
            ->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy',
                    array('fn' => 'fullname'))
            ->order('eventStartDate DESC');
    if(in_array($this->getRole(),$this->higherlevel)) {
    $select->where($this->_name.'.createdBy = ?',$this->getIdentityForForms());
    }
    $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage(30)
                        ->setPageRange(10);
    if(isset($page) && ($page != "")) {
    $paginator->setCurrentPageNumber($page);
            }
    return $paginator;
    }

    /**
    * Retrieves all event datae by id number
    * @param integer $page
    * @return array
    */

    public function getUpcomingEvents(){
    $events  = $this->getAdapter();
    $select = $events->select()
            ->from($this->_name,array('eventStartDate','eventTitle','organisation'))
            ->where('events.eventStartDate >= ?',Zend_Date::now()->toString('yyyy-MM-dd'))
            ->joinLeft('staffregions','events.eventRegion = staffregions.id',array('location' => 'description'))
            ->joinLeft('eventtypes',$this->_name.'.eventType = eventtypes.id',array('type'))
            ->order('eventStartDate DESC');
    $events = $this->getAdapter()->fetchAll($select);
    return $events;
    }

    /**
    * Retrieves all event datae by id number
    * @param integer $page
    * @return array
    */

    public function getEventsByDayPast(){
    $select = $this->select()->from($this->_name, array('eventStartDate'))
            ->where('events.eventStartDate <= ?',
                    Zend_Date::now()->toString('yyyy-MM-dd'))
            ->group('eventStartDate');
    $events = $this->getAdapter()->fetchAll($select);
    return $events;
    }

    /**
    * Retrieves all event datae by id number
    * @param integer $page
    * @return array
    */

    public function getEventsByDayFuture(){
    $select = $this->select()
            ->from($this->_name,array('eventStartDate'))
            ->where('events.eventStartDate >= ?',
                    Zend_Date::now()->toString('yyyy-MM-dd'))
            ->group('eventStartDate');
    $events = $this->getAdapter()->fetchAll($select);
    return $events;
    }

    /**
    * Retrieves all event data by id number
    * @param integer $page
    * @return array
    */

    public function getUpcomingEventsList() {
    $events = $this->getAdapter();
    $select = $events->select()->from($this->_name)
            ->joinLeft('users','events.createdBy = users.id',
                    array('fullname','i' => 'id','email'))
            ->joinLeft('staffregions','events.eventRegion = staffregions.id',
                    array('location'=>'description'))
            ->joinLeft('eventtypes',$this->_name.'.eventType = eventtypes.id',
                    array('type'))
            ->where('events.eventStartDate >= ?',
                    Zend_Date::now()->toString('yyyy-MM-dd'))
            ->order('events.eventStartDate ASC');
    return $events->fetchAll($select);
    }

    /**
    * Retrieves all events data in the archive
    * @param array $params
    * @return array
    */

    public function getArchivedEventsList($params) {
    $events = $this->getAdapter();
    $select = $events->select()->from($this->_name)
            ->joinLeft('users','events.createdBy = users.id',
                    array('fullname','i' => 'id','email'))
            ->joinLeft('staffregions','events.eventRegion = staffregions.id',
                    array('location'=>'description'))
            ->joinLeft('eventtypes',$this->_name.'.eventType = eventtypes.id',
                    array('type'))
            ->where('events.eventStartDate <= ?',Zend_Date::now()->toString('yyyy-MM-dd'))
            ->order('eventStartDate DESC');
    $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage(20)
                        ->setPageRange(10);
    if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber($params['page']);
    }
    return $paginator;
    }

    /**
    * Retrieves all event datae by id number
    * @param date $datefrom
    * @param date $dateto
    * @return array
    */

    public function getAttendanceAdults($datefrom = NULL,$dateto = NULL){
    $select = $this->select()
            ->from($this->_name,array(
                'q' => 'SUM(adultsAttend)',
                'child' => 'SUM(childrenAttend)'))
            ->where('events.eventStartDate >= ?',$datefrom)
            ->where('events.eventStartDate <= ?',$dateto);
    $events = $this->getAdapter()->fetchAll($select);
    return $events;
    }

    /**
    * Retrieves all event data and sends data for mapping
    * @param integer $page
    * @return array
    */

    public function getMapData() {
    $events = $this->getAdapter();
    $select = $events->select()
            ->from($this->_name, array(
                'id',
                'name' => 'eventTitle',
                'df' => 'DATE_FORMAT(eventStartDate,"%D %M %Y")',
                'dt' => 'DATE_FORMAT(eventEndDate,"%D %M %Y")',
                'lat' => 'latitude',
                'lng' => 'longitude'))
            ->where('latitude IS NOT NULL')
            ->where('events.eventStartDate >= ?',
                    Zend_Date::now()->toString('yyyy-MM-dd') .
                                        ' 00:00:00');
    return $events->fetchAll($select);
    }

    /**
    * Retrieves statistics for events by yearr
    * @return array
    */

    public function getStatistics(){
    $events = $this->getAdapter();
    $select = $events->select()
        ->from($this->_name, array(
            'number' => 'COUNT(events.id)',
            'children' => 'SUM(childrenAttend)',
            'adults' => 'SUM(adultsAttend)',
            'year' => 'EXTRACT(YEAR FROM eventStartDate)'))
        ->joinLeft('eventtypes',$this->_name.'.eventType = eventtypes.id',array('type'))
        ->where('eventStartDate >= ?','2010-01-01')
        ->where('eventStartDate <= ?',Zend_Date::now()->toString('yyyy-MM-dd'))
        ->group('type')
        ->group('year')
        ->order('year');
    return $events->fetchAll($select);
    }

}
