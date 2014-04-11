<?php
/** Model for setting up help topics
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add caching
*/

class Help extends Pas_Db_Table_Abstract {

	protected $_name = 'help';

	protected $_primary = 'id';

	/** Retrieve front page content for the help section
	* @param string $section
	* @param integer $frontpage
	* @param integer $publish
	* @return array
	*/
	public function getFrontContent($section, $frontpage = 1, $publish = 3) {
		$content = $this->getAdapter();
		$select = $content->select()
			->from($this->_name,array('body','metaDescription','metaKeywords','title','created','updated'))
			->joinLeft('users','users.id = content.author',array('fullname'))
			->where('frontPage = ?', (int)$frontpage)
			->where('publishState = ?', (int)$publish)
			->where('section = ?',(string)$section);
		return $content->fetchAll($select);
	}

	/** Retrieve content in help section via the slug
	* @param string $section
	* @param string $slug
	* @return array
	*/
	public function getContent($section, $slug) {
		$content = $this->getAdapter();
		$select = $content->select()
			->from($this->_name,array('body', 'metaDescription', 'metaKeywords',
			'title', 'created', 'updated'))
			->joinLeft('users','users.id = content.author',array('fullname'))
			->where('publishState = ?', (int)3)
			->where('slug = ?',(string)$slug);
       return $content->fetchAll($select);
	}

	/** Retrieve content in help section for admin via pagination
	* @param integer $page
	* @return array
	*/
	public function getContentAdmin($page) {
		$content = $this->getAdapter();
		$select = $content->select()
			->from($this->_name)
		   ->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('fullname'))
		   ->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',array('fn' => 'fullname'))
		   ->order('created DESC');
		$paginator = Zend_Paginator::factory($select);
		$paginator->setItemCountPerPage(30)
	    	      ->setPageRange(10);
		if(isset($page) && ($page != "")) {
    	$paginator->setCurrentPageNumber($page);
		}
		return $paginator;
	}

	/** Retrieve content by topics in help section via pagination
	* @param string $section
	* @param string $slug
	* @return array
	*/
	public function getTopics($page,$section) {
		$content = $this->getAdapter();
		$select = $content->select()
			->from($this->_name)
			->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('fullname'))
			->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',array('fn' => 'fullname'))
			->where('publishState = ?', (int)3)
			->where('section = ?',$section)
			->order('created DESC');
		$paginator = Zend_Paginator::factory($select);
		$paginator->setItemCountPerPage(30)
	    	      ->setPageRange(10);
		if(isset($page) && ($page != "")) {
    	$paginator->setCurrentPageNumber($page);
		}
	return $paginator;
	}

	/** Retrieve content by topic id
	* @param string $section
	* @param integer $id
	* @return array
	*/
	public function getTopic($section, $id) {
		$content = $this->getAdapter();
		$select = $content->select()
			->from($this->_name,array('body','metaDescription','metaKeywords','title','created','updated'))
			->joinLeft('users','users.id = help.author',array('fullname'))
			->where('publishState = ?', (int)3)
			->where('section = ?',(string)$section)
			->where($this->_name . '.id = ?',(int)$id);
       return $content->fetchAll($select);
	}


}
