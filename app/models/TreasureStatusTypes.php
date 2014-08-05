<?php
/** Data model for treasure statuses
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new TreasureStatusTypes();
 * $data = $model->getList();
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
 */
class TreasureStatusTypes extends Pas_Db_Table_Abstract {

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'treasureStatusTypes';

    /** Get a cached list of actions
     * @access public
     * @return array
     */
    public function getActions(){
        $key = md5('tactions');
        if (!$data = $this->_cache->load($key)) {
            $actions = $this->getAdapter();
            $select = $actions->select()
                    ->from($this->_name)
                    ->where('valid = ?',(int)1)
                    ->order('action');
            $data =  $actions->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** get a key value pair array of actions
     * @access public
     * @return array
     */
    public function getList(){
        $key = md5('tactionslist');
        if (!$data = $this->_cache->load($key)) {
            $actions = $this->getAdapter();
            $select = $actions->select()
                    ->from($this->_name,array('id','action'))
                    ->where('valid = ?',(int)'1')
                    ->order('action');
            $data =  $actions->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }
}

