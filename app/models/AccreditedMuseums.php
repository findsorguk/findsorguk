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

class AccreditedMuseums
	extends Pas_Db_Table_Abstract {

    protected $_primary = 'id';
    protected $_name = 'accreditedMuseums';

        /** Get list of all acronyms and paginator them
    * @access public
    * @return array
    * @param  array $params sent via controller
    */
    public function listMuseums($params)	{
    $acros = $this->getAdapter();
    $select = $acros->select()
	->from($this->_name, array('museumName', 'lat', 'lon', 'id', 'accreditedNumber'))
	->joinLeft('accreditedRegions','accreditedRegions.id = ' . $this->_name . '.area',
                array('regionName'))
	->joinLeft('accreditedStatus','accreditedStatus.id = ' . $this->_name . '.status',
                array('status'));
    $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage(20)
	->setPageRange(10)
	->setCache( $this->_cache );
    if(isset($params['page']) && ($params['page'] != "")) {
    $paginator->setCurrentPageNumber($params['page']);
    }
    return $paginator;
    }
    
    public function mapMuseums(){
    $acros = $this->getAdapter();
    $select = $acros->select()
	->from($this->_name, array('museumName', 'lat', 'lon', 'id', 'accreditedNumber'));
    return $acros->fetchAll($select);
    }
}