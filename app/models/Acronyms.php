<?php
/**
 * Model for pulling common acronyms used for the website from the database.
 *
 * An example of use:
 * <code>
 * <?php
 * $model = new Acronyms();
 * $acronyms = $model->getValid();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category   Pas
 * @package    Pas_Db_Table
 * @subpackage Abstract
 * @author     Daniel Pett dpett @ britishmuseum.org
 * @copyright  2010 - DEJ Pett
 * @version    1
 * @since	  22 September 2011
 * @license http://URL name
 * @example /app/modules/admin/controllers/AcronymsController.php Controller for acronyms
 * @uses Zend_Cache
*/

class Acronyms extends Pas_Db_Table_Abstract {

    /** The primary key
     * @access protected
     * @var int
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'abbreviations';

    /** Get all valid acronyms
    * @access public
    * @return array
    */
    public function getValid() {
        if (!$data = $this->_cache->load('acronymsSite')) {
            $acros = $this->getAdapter();
            $select = $acros->select()
                    ->from($this->_name, array('abbreviation','expanded'))
                    ->where('valid = 1');
            $data =  $acros->fetchPairs($select);
            $this->_cache->save($data, 'acronymsSite');
        }
        return $data;
    }

    /** Get list of all acronyms and paginator them
    * @access public
    * @return array
    * @param  array $params sent via controller
    */
    public function getAllAcronyms(array $params) {
        $acros = $this->getAdapter();
        $select = $acros->select()
                ->from($this->_name, array(
                    'id', 'abbreviation', 'expanded',
                    'updated'))
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
                        array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',
                        array('fn' => 'fullname'))
                ->order('abbreviation');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(20)
                ->setPageRange(10)
                ->setCache( $this->_cache );
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber($params['page']);
        }
        return $paginator;
    }
}