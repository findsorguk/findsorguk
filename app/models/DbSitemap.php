<?php
/** get data for a sitemap of finds
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* */
class DbSitemap extends Pas_Db_Table_Abstract {

	protected $_name = 'finds';
	protected $_primary = 'id';

	/** get sitemap data
	* @param integer $page 
	* @return boolean
	*/
	public function getSitemap($page){
	$comp = $this->getAdapter();
	$select = $comp->select()
		->from($this->_name,array('id', 'updated', 'old_findID'))
		->where('secwfstage > ?',(int)2);
	$data =  $comp->fetchAll($select);
	$paginator = Zend_Paginator::factory($data);
	$paginator->setCache(Zend_Registry::get('cache'));
	$paginator->setItemCountPerPage(10000) 
		->setPageRange(25);
	if(isset($page) && ($page != "")) {
	$paginator->setCurrentPageNumber($page); 
	}
	return $paginator;
	}
		 
	
}