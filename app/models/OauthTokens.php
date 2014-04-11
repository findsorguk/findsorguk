<?php
/** Model for interacting with oauth tokens
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add edit and delete functions
*/
class OauthTokens extends Pas_Db_Table_Abstract {

	protected $_name = 'oauthTokens';
	
	protected $_primary = 'id';


	/** get the cached token for accessing twitter's oauth'd endpoint
	* @param string twitteraccess 
	* @return object
	*/
	public function getTokens(){
	if (!$data = $this->_cache->load('oauthtwitter')) {
		$tokens = $this->getAdapter();
		$select = $tokens->select()
		->from($this->_name)
		->where('service = ?', 'twitterAccess');
		$data =  $tokens->fetchAll($select);
		$this->_cache->save($data, 'oauthtwitter');
	}
    return $data;
	}

	public function create($data){
	$data = (object)$data;
	$tokenRow = $this->_tokens->createRow();	
	$tokenRow->service = 'yahooAccess';
	$tokenRow->accessToken = serialize(urldecode($data->oauth_token));
	$tokenRow->tokenSecret = serialize($data->oauth_token_secret);
	$tokenRow->guid = serialize($data->xoauth_yahoo_guid);
	$tokenRow->sessionHandle = serialize($data->oauth_session_handle);
	$tokenRow->created = $this->getTimeForForms();
	$tokenRow->expires = $this->expires();
	$tokenRow->save();
	$tokenData = array('accessToken' => $data->oauth_token, 'secret' => $data->oauth_token_secret);
	return $tokenData;	
	}
}