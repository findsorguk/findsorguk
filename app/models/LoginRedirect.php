<?php
/**
* A model to manipulate the login redirect page data
* 
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Mary Chester-Kadwell mchester-kadwell @ britishmuseum.org
* @copyright  	Copyright (c) 2014 Mary Chester-Kadwell
* @license 		GNU General Public License
* @version 		1
* @since 		9 May 2014
*/

class LoginRedirect extends Pas_Db_Table_Abstract
{

	protected $_name = 'loginRedirect';
	protected $_primary = 'id';

	protected $_default = array(

	);

	/** Get a dropdown key value pair list for uri and alias
	* @return array
	*/
	public function getOptions()
	{
		if (!$options = $this->_cache->load('loginredirectoptions')) {
			$select = $this->select()
			->from($this->_name, array('uri', 'alias'))
			->order('alias ASC');
			$options = $this->getAdapter()->fetchPairs($select);
			$this->_cache->save($options, 'loginredirectoptions');
		}
		return $options;
    }

	public function getConfig()
	{
		$redirect = $this->getAdapter();
		$select = $redirect->select()
		->from($this->_name, array('uri', 'alias'))
		->where('userID = ?', (int)$this->userNumber());
		$page = $redirect->fetchPairs($select);
		if($page) {
			$uri = $page['0']['uri'];
		} else {
			$page =  $this->_default;
		}
		return $page;
	}

	public function updateConfig($data)
	{
		if(array_key_exists('csrf', $data)) {
 		unset($data['csrf']);
  		}

		$newUri = array_keys($data);
		$updateData['uri'] = $newUri;
		$updateData['created'] = $this->timeCreation();
		$updateData['createdBy'] = $this->userNumber();
		$updateData['userID'] = $this->userNumber();
		parent::delete('userID =' . $this->userNumber());
		return parent::insert($updateData);	
	}



}