<?php
/** Model for manipulating contents for static pages
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* */

class Content extends Pas_Db_Table_Abstract {

	protected $_name = 'content';
	protected $_primary = 'id';

	/** Retrieves single front page article when publication status is set to published
     * @param string $section
     * @param integer $type
     * @param integer $publish
     * @return array
	*/
	public function getFrontContent($section, $frontpage = 1, $publish = 3) {
	if (!$data = $this->_cache->load('frontcontent' . $section)) {
	$content = $this->getAdapter();
	$select = $content->select()
		->from($this->_name,array(
		'body', 'metaDescription', 'metaKeywords',
		'title', 'created', 'updated'))
		->joinLeft('users','users.id = content.author', array('fullname'))
		->where('frontPage = ?', (int)$frontpage)
		->where('publishState = ?', (int)$publish)
		->where('section = ?',(string)$section);
	$data = $content->fetchAll($select);
	$this->_cache->save($data, 'frontcontent' . $section);
	}
	return $data;
	}

	/** Retrieves content by section, slug and when publication status is set to published
     * @param string $section
     * @param string $slug
     * @return array
	*/
	public function getContent($section, $slug)	{
	$key = 'content'.md5($slug);
	if (!$data = $this->_cache->load($key)) {
	$content = $this->getAdapter();
	$select = $content->select()
		->from($this->_name,array(
		'body', 'metaDescription', 'metaKeywords',
		'title', 'created', 'updated', 'menutitle'))
		->joinLeft('users','users.id = content.author',array('fullname'))
		->where('publishState = 3')
		->where('section = ?',(string)$section)
		->where('slug = ?',(string)$slug);
	$data = $content->fetchAll($select);
	$this->_cache->save($data, $key);
	}
	return $data;
	}

/** Retrieves content by section, slug and when publication status is set to published
     * @param string $section
     * @param string $slug
     * @return array
	*/
	public function getSecretContent($section, $slug)	{
	$key = 'secretcontent'.md5($slug);
	if (!$data = $this->_cache->load($key)) {
	$content = $this->getAdapter();
	$select = $content->select()
		->from($this->_name,array(
		'body', 'metaDescription', 'metaKeywords',
		'title', 'created', 'updated', 'menutitle'))
		->joinLeft('users','users.id = content.author',array('fullname'))
		->where('publishState = 3')
		->where('slug = ?',(string)$slug);
	$data = $content->fetchAll($select);
	$this->_cache->save($data, $key);
	}
	return $data;
	}
	
	/** Retrieves all content in administration interface
     * @param integer $page
     * @return array
	*/
	public function getContentAdmin($page) {
	$content = $this->getAdapter();
	$select = $content->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'))
		->order('created DESC');
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($page) && ($page != "")) {
	$paginator->setCurrentPageNumber($page);
	}
	return $paginator;
	}

	/** Retrieves conservation notes list when publication status is set to published
     * @param string $section
     * @return array
	*/
	public function getConservationNotes() {
	$key = md5('consNotes');
	if (!$data = $this->_cache->load($key)) {
	$content = $this->getAdapter();
	$select = $content->select()
		->from($this->_name,array('slug', 'menuTitle', 'updated'))
		->where('frontPage = ?', (int)0)
		->where('section = ?',(string) 'conservation')
		->where('publishState = ?', (int)3);
	$data = $content->fetchAll($select);
	$this->_cache->save($data, $key);
    }
	return $data;
	}

	/** Retrieves treasure section list for menu when publication status is set to published
     * @return array
	*/
	public function getTreasureContent() {
	$key = md5('treasurecontent');
	if (!$data = $this->_cache->load($key)) {
	$content = $this->getAdapter();
	$select = $content->select()
		->from($this->_name, array('slug', 'menuTitle', 'updated'))
		->where('frontPage = ?', (int) 0)
		->where('section = ?',(string) 'treasure')
		->where('publishState = ?', (int)3);
	$data = $content->fetchAll($select);
	$this->_cache->save($data, $key);
    }
	return $data;
	}

	/** Retrieves section list for menu when publication status is set to published
     * @param string $section
     * @return array
	*/
	public function getSectionContents($section) {
	$key = md5('sectionContents' . $section);
	if (!$data = $this->_cache->load($key)) {
	$content = $this->getAdapter();
	$select = $content->select()
		->from($this->_name,array('slug','menuTitle','updated','title'))
		->where('frontPage = ?', (int)0)
		->where('section = ?',(string)$section)
		->where('publishState = 3');
	$data = $content->fetchAll($select);
	$this->_cache->save($data, $key);
    }
	return $data;
	}

	/** Retrieves content list for menu by section when publication status is set to published
     * and frontpage status is not set
     * @param string $section
     * @param integer $front
     * @param integer $publish
     * @return array
	*/
	public function buildMenu($section,$front = 0,$publish = 3) {
	$key = md5('menu' . $section);
	if (!$data = $this->_cache->load($key)) {
	$content = $this->getAdapter();
	$select = $content->select()
		->from($this->_name,array('slug', 'menuTitle', 'updated'))
		->where('frontPage = ?', (int)$front)
		->where('section =?', (string)$section)
		->where('publishState = ?', (int)$publish)
		->order('id ASC');
	$data = $content->fetchAll($select);
	$this->_cache->save($data, $key);
    }
	return $data;
	}

	/** Retrieves content list for treasure section when publication status is set to published
     * and frontpage status is not set
     * @param string $section
     * @param integer $front
     * @param integer $publish
     * @return array
	*/
	public function buildTMenu($section = 'treports',$front = 0,$publish = 3) {
	if (!$data = $this->_cache->load('treportsmenu')) {
	$content = $this->getAdapter();
	$select = $content->select()
		->from($this->_name,array('slug', 'menuTitle', 'updated'))
		->where('frontPage = ?', (int)$front)
		->where('section =?',$section)
		->where('publishState = ?',$publish)
		->order('slug ASC');
	$data = $content->fetchAll($select);
	$this->_cache->save($data, 'treportsmenu');
    }
	return $data;
	}

	public function getSolrData($id){
	$contents = $this->getAdapter();
	$select = $contents->select()
		->from($this->_name,array(
			'identifier' => 'CONCAT("content-",content.id)',
			'id',
			'title',
			'excerpt',
			'body',
			'section',
			'slug',
			'created',
			'updated',
			'type' => 'CONCAT("sitecontent")',
			 ))
		->where($this->_name .  '.id = ?',(int)$id);
		
	return	$contents->fetchAll($select);		
	}
}
