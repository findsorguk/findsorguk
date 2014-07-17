<?php
/**
 * Model for interacting with macktypes table. This is an Iron Age coin
 * classification type
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new MackTypes();
 * $data = $model->getMackTypesDD;
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo add, edit and delete functions to be created and moved from controllers
 * @example /app/forms/IronAgeCoinForm.php
 *
 */
class MackTypes extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'macktypes';

    /** The primary key
     * @access protected
     * @var int
     */
    protected $_primary = 'id';

    /** Retrieve key value paired dropdown list array
     * @access public
     * @return array $paginator
     */
    public function getMackTypesDD(){
        $select = $this->select()
                ->from($this->_name, array('type', 'type'))
                ->order('type');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }

    /** Retrieve data for an autocomplete ajax query
     * @access public
     * @param string $q
     * @return array $paginator
     * @todo reckon this can be made more efficient in the controller action
     */
    public function getTypes($q) {
        $types = $this->getAdapter();
        $select = $types->select()
                ->from($this->_name, array('id','term' => 'type'))
                ->where('type LIKE ? ', $q . '%')
                ->order('type')
                ->limit(10);
       return $types->fetchAll($select);
    }

    /** Retrieve paginated mack types
     * @access public
     * @param integer $page
     * @return array $paginator
     */
    public function getMackTypes($params) {
        $types = $this->getAdapter();
        $select = $types->select()
                ->from($this->_name)
                ->joinLeft('coins','coins.mack_type = macktypes.type',array())
                ->order($this->_name . '.type')
                ->group($this->_name . '.type');
        $paginator = Zend_Paginator::factory($select);
        Zend_Paginator::setCache($this->_cache);
        $paginator->setItemCountPerPage(30)
                ->setPageRange(10);
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber($params['page']);
        }
        return $paginator;
    }
}