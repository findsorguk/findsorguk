<?php
/** Model for pulling hoard cover sheets from database
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new Hoards();
 * $data = $model->getHoards();
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
 * @since 22 September 2011
 * @todo add caching
 * @example /app/forms/AdvancedSearchForm.php
 */

class Hoards extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'hoards';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Retrieval of all hoards on database
     * @access public
     * @return array $data
     * @todo add caching
     */
    public function getHoards() {
        $select = $this->select()
                ->from($this->_name, array('id', 'term'))
                ->order('term ASC');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Retrieval of hoard data by ID number
     * @access public
     * @param integer $id
     * @return array $data
     * @todo add caching
     */
    public function getHoardDetails($id) {
        $hoards = $this->getAdapter();
        $select = $hoards->select()
                ->from($this->_name)
                ->joinLeft('periods','periods.id = hoards.period', array('t' => 'term'))
                ->where('hoards.id =? ',(int)$id);
        return $hoards->fetchAll($select);
    }

    /** get paginated hoard list
     * @access public
     * @param integer $page
     * @return \Zend_Paginator $paginator
     * @todo add caching
     */
    public function getHoardList($params) {
        $hoards = $this->getAdapter();
        $select = $hoards->select()
                ->from($this->_name)
                ->joinLeft('finds','finds.hoardID = hoards.id', array('q' => 'SUM(quantity)'))
                ->joinLeft('periods','periods.id = hoards.period', array('t' => 'term'))
                ->group('hoards.id')
                ->order($this->_name . '.id ASC');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']);
        }
        return $paginator;

    }
}