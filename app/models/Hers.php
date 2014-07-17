<?php
/**
 * Model for sorting out which HERs have signed up
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $hers = new Hers();
 * $data = $hers->getAll($params);
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
 * @todo add caching and work out if still valid function
 * @example /app/modules/admin/controllers/HerController.php
 */

class Hers extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'hers';

    /** The table's primary key
     * @access protected
     * @var type
     */
    protected $_primary = 'id';

    /** Retrieval of all HERs
     * @access public
     * @return array $data
    */
    public function getAll($params) {
        $types = $this->getAdapter();
        $select = $types->select()
                ->from($this->_name)
                ->order('name');
        $paginator = Zend_Paginator::factory($select);
        Zend_Paginator::setCache(Zend_Registry::get('cache'));
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']);
        }
        $paginator->setItemCountPerPage(20)->setPageRange(10);
        return $paginator;
    }
}