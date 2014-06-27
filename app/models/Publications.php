<?php

/** Retrieve and manipulate publications data
* @category Pas
 * @package Db_Table
 * @subpackage Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license GNU General Public License
* @version 1
* @since 22 September 2011
* @todo add caching
*/
class Publications extends Pas_Db_Table_Abstract {

	protected $_name = 'publications';

	protected $_primary = 'id';

	public function getAuthors() 
	{
		$publications = $this->getAdapter();
		$select = $publications->select()
			->from($this->_name, array( 'authors', 'authors' ))
			->order('authors ASC')
			->group('authors');
		$data = $publications->fetchPairs($select);
		return $data;
	}
	
	public function getTitles( $author ) 
	{
		$publications = $this->getAdapter();
		$select = $publications->select()
			->from($this->_name, array( 'id' => 'secuid', 'term' => 'title' ))
			->where('authors = ?', $author)
			->order('title ASC')
			->group('title');
		$data = $publications->fetchAll($select);
		return $data;
	}
	
	public function getTitlesPairs( $author ) 
	{
		$publications = $this->getAdapter();
		$select = $publications->select()
			->from($this->_name, array( 'secuid',  'title' ))
			->where('authors = ?', $author)
			->order('title ASC')
			->group('title');
		$data = $publications->fetchPairs($select);
		return $data;
	}
	
	public function getSecuids(  ) 
	{
		$publications = $this->getAdapter();
		$select = $publications->select()
			->from($this->_name, array( 'secuid'));
		$data = $publications->fetchAll($select);
		return $data;
	}

	/** Get all refs for a find
	* @param integer $id find to reference
	* @return array
	*/
	public function getReferences($id) {
	$refs = $this->getAdapter();
	$select = $refs->select()
		->from($this->_name, array('authors','title','publication_year','publication_place',
		'vol_no','ISBN','publisher','medium','accessedDate','url','publication_type','id','in_publication'))
		->joinLeft('bibliography','publications.secuid = bibliography.pubID',
		array('pp' => 'pages_plates','i' => 'id'))
		->joinLeft('finds','finds.secuid = bibliography.findID', 
		array('objecttype','fID' => 'id','createdBy','old_findID'))
		->joinLeft('publicationtypes','publicationtypes.id = publications.publication_type',array('term'))
		->where('finds.id = ?', (int)$id)
		->group('publications.secuid');
	return  $refs->fetchAll($select);
	}
	
	/** Get all reference details
	* @param integer $id reference to reference
	* @return array
	*/
	public function getPublicationDetails($id) {
	$refs = $this->getAdapter();
	$select = $refs->select()
		->from($this->_name,array('id','created','updated', 
		'title','publisher','authors',
		'ISBN','publication_year','publication_place',
		'editors','in_publication', 'biab', 'doi'))
		->joinLeft('publicationtypes','publications.publication_type = publicationtypes.id',array('publicationType' => 'term'))
		->joinLeft('users','publications.createdBy = users.id', array('createdBy' => 'fullname'))
		->joinLeft(array('users2' => 'users'),'publications.updatedBy = users2.id',array('updatedBy' => 'fullname'))
		->where('publications.id ='.$id);
     return  $refs->fetchAll($select);
	}

	
	/** Get publication data for solr updates
	 * 
	 */
	public function getSolrData($id){
	$refs = $this->getAdapter();
	$select = $refs->select()
		->from($this->_name,array(
			'identifier' => 'CONCAT("publications-",publications.id)','publications.id',
			'title', 'authors','editors',
			'inPublication' => 'in_publication','isbn',
			'placePublished' => 'publication_place','yearPublished' => 'publication_year',
			'created','updated','publisher'
			 ))
		->joinLeft('publicationtypes',$this->_name . '.publication_type = publicationtypes.id',
		array('pubType' => 'term'))
		->where('publications.id = ?',(int)$id);
	return	$refs->fetchAll($select);	
	}
}
