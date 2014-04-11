<?php
/** Model for pulling common acronyms used for the website from the database.
* @category   Pas
* @package    Pas_Db_Table
* @subpackage Abstract
* @author     Daniel Pett dpett @ britishmuseum.org
* @copyright  2010 - DEJ Pett
* @license 	  GNU General Public License
* @version    1
* @since	  22 September 2011
*/

class Acronyms
	extends Pas_Db_Table_Abstract {

    protected $_primary = 'id';
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
    public function getAllAcronyms($params)	{
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