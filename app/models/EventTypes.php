<?php 
/** 
 * Model for manipulating event types
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $model = new EventTypes();
 * $data = $model->getType();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/forms/CalendarForm.php
 * 
 */

class EventTypes extends Pas_Db_Table_Abstract {
	
    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'eventtypes';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primaryKey = 'id';
	
     /** Retrieves all event types that we list
      * @access public
      * @param type $type
      * @return type
      */
    public function getType($type){
        $key = md5('eventtypes' . $type );
        if(!$data = $this->_cache->load($key)) {
            $events = $this->getAdapter();
            $select = $events->select()
                    ->from($this->_name, array('id','type'));
            $data =  $events->fetchRow($select); 
            $this->_cache->save($data, $key );
        }
       return $data;	
    }

    /** Retrieve all event types
     * @access public
     * @return array
     */
    public function getTypes(){
        $key = md5('eventTypes');
        if(!$data = $this->_cache->load($key)) {
            $events = $this->getAdapter();
            $select = $events->select()
                    ->from($this->_name, array('id','type'));
            $data =  $events->fetchPairs($select); 
            $this->_cache->save($data, $key);
        }
        return $data;
    }
	
    /** Retrieves all event types that we list as word pairs
     * @access public
     * @return array
     */
    public function getTypesWords(){
        $key = md5('eventtypesWords');
        if(!$data = $this->_cache->load($key)) {
            $events = $this->getAdapter();
            $select = $events->select()
                    ->from($this->_name, array('type','type'));
            $data =  $events->fetchPairs($select); 
            $this->_cache->save($data, $key);
        }
        return $data;
    }
}