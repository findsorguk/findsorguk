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
	  '/database' => 'Simple search',
	  '/database/search/advanced' => 'Advanced search', 
	  '/database/myscheme/myfinds' => 'Finds recorded by me',
	  '/database/myscheme/myinstitution' => 'My institution\'s records',
  	  '/database/myscheme/recordedbyflos' => 'My finds recorded by FLOs',
  	  '/users/account' => 'My account page',	
  	  '/database/people' => 'People',
	  '/guide' => 'Volunteer recording guide'
	);

	protected $_redirects = array( 
	    'flos' => '/database/myscheme/myfinds',
	    'fa' => '/database/search/advanced',
	    'admin' => '/users/account',
	    'member' => '/database/myscheme/recordedbyflos',
	    'treasure' => '/database',
	    'hero' => '/database',
	    'reasearch' => '/database/search/advanced'
    );

	/** Get a dropdown key value pair list for uri and alias
	* @return array
	*/
	public function getOptions()
	{
    	return $this->_default;
    }

	public function getConfig()
	{
		$select = $this->select()->from($this->_name, array('uri')->where('userID = ?', (int)$this->userNumber());
  		$pageRow = $this->getAdapter()->fetchRow($select);
  		$page = $pageRow->toArray();
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