<?php
/** Data model for accessing and manipulating saved searches
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $saved = new SavedSearches();
 * $insert = $saved->add($insertData);
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/database/controllers/SearchController.php
*/
class SavedSearches extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'savedSearches';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';


    /** Get all saved searches as a paginated array
     * @access public
     * @param integer $userid
     * @param integer $page
     * @param integer $private
     * @return \Zend_Paginator
     */

    public function getSavedSearches($userid, $page, $private) {
        $search = $this->getAdapter();
        $select = $search->select()
                ->from($this->_name)
                ->joinLeft('users',$this->_name . '.createdBy = users.id',
                        array( 'username' ))
                ->order('id DESC');
        if(isset($userid)) {
            $select->where($this->_name . '.createdBy = ?', (int)$userid);
        }
        if(!isset($private)) {
            $select->where($this->_name . '.public = ?',(int)1);
        }
        $paginator = Zend_Paginator::factory($select);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber((int)$page);
        }
        $paginator->setItemCountPerPage(10)->setPageRange(10);
        return $paginator;
    }
}