<?php
/** 
 * Data model for accessing treasure valuation dates and cases from link table
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $model = new VanArsdellTypes();
 * $data = $model->getTypes();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 October 2010, 17:12:34
 * @todo integrate with the VanArsdellTypes
 * 
 */
class VanArsdellTypes extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'vanarsdelltypes';
	
    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get Van Arsdell types
     * @access public
     * @param string $q
     * @return array
     */
    public function getTypes($q){
        $types = $this->getAdapter();
        $select = $types->select()
                ->from($this->_name, array('id','term' => 'type'))
                ->where('type LIKE ? ', (string)$q .'%')
                ->order('type')
                ->limit(10);
        return $types->fetchAll($select);
    }
    
    /** Get a dropdown list of VA types as key value array
     * @access public
     * @return array
     */
    public function getVATypesDD() {
        if (!$options = $this->_cache->load('vatypedd')) {
            $select = $this->select()
                    ->from($this->_name, array('type', 'type'))
                    ->order('type');
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, 'vatypedd');
	}
	return $options;
    }

    /** Get a list of va types paginated
     * @access public
     * @param array $params
     * @return array
     */
	public function getVaTypes($params) {
            $types = $this->getAdapter();
            $select = $types->select()
                    ->from($this->_name);
            $paginator = Zend_Paginator::factory($select);
            $paginator->setItemCountPerPage(30)->setPageRange(10);
            if(isset($params['page']) && ($params['page'] != "")) {
                $paginator->setCurrentPageNumber((int)$params['page']);
            }
            Zend_Paginator::setCache($this->_cache);
            return $paginator;
	}
}