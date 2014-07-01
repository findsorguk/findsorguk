<?php
/** Data model for accessing treasure action types in the database
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $actionTypes = new TreasureActionTypes();
 * $actionlist = $actionTypes->getList();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license GNU General Public License
 * @version 1
 * @since 22 October 2010, 17:12:34
 * @example /app/forms/TreasureActionForm.php
 */
class TreasureActionTypes extends Pas_Db_Table_Abstract {

    /** The primary key for the table
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'treasureActionTypes';

    /** Get list of all treasure actions
     * @access public
     * @return array
     */
    public function getActions(){
        $key = md5('tactions');
        if (!$data = $this->_cache->load($key)) {
            $actions = $this->getAdapter();
            $select = $actions->select()
                    ->from($this->_name)
                    ->where('valid = ?', (int)1)
                    ->order('action');
            $data =  $actions->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get key value pair list of actions for treasure
     * @access public
     * @return array
     */
    public function getList(){
        $key = md5('tactionslist');
        if (!$data = $this->_cache->load('tactionslist')) {
            $actions = $this->getAdapter();
            $select = $actions->select()
                    ->from($this->_name,array('id','action'))
                    ->where('valid = ?',(int)1)
                    ->order('action');
            $data =  $actions->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
    return $data;
    }
}

