<?php 
/** Model for pulling person's online accounts
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $accts = new OnlineAccounts();
 * $this->view->accts = $accts->getAccounts($id);
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
 * @example /app/modules/contacts/controllers/StaffController.php 
 */

class OnlineAccounts extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'userOnlineAccounts';
    
    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primaryKey = 'id';

    /** Get a list of online personas by userid for staff members
     * @access public
     * @param integer $id
     * @return array
     */
    public function getAccounts($id){
        $accs = $this->getAdapter();
        $select = $accs->select()
                ->from($this->_name,array('account','accountName'))
                ->joinLeft('webServices',$this->_name 
                        . '.accountName = webServices.service', array('serviceUrl'))
                ->joinLeft('staff',$this->_name 
                        . '.userID = staff.dbaseID', array())
                ->where('staff.id = ?', (int)$id)
                ->where($this->_name . '.public = 1');
        return $accs->fetchAll($select);
    }

    /** Get slideshare account name for api call
     * @access public
     * @param integer $id
     * @return array
     */
    public function getSlideshare($id) {
        $key = md5('slideshare' . $id);
        if (!$data = $this->_cache->load($key)) {
        $accs = $this->getAdapter();
        $select = $accs->select()
                ->from($this->_name,array('account'))
                ->joinLeft('webServices',$this->_name 
                        . '.accountName = webServices.service', array())
                ->where('userID = ?', (int)$id)
                ->where('accountName = ?','Slideshare')
                ->where($this->_name . '.public = 1')
                ->limit(1);
        $data = $accs->fetchAll($select);
        $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** List all subscribed third party web services for html
     * @access public
     * @param integer $userID
     * @return array
     */
    public function getAllAccounts($userID) {
        $accs = $this->getAdapter();
        $select = $accs->select()
                ->from($this->_name, array('id','account', 'accountName', 'public'))
                ->joinLeft('webServices', $this->_name 
                        . '.accountName = webServices.service', 
                array('serviceUrl'))
                ->where($this->_name . '.userID = ?', (int)$userID);
        return $accs->fetchAll($select);
    }
}