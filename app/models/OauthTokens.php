<?php
/** 
 * Model for interacting with oauth tokens
 * 
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright 2010 - DEJ Pett
 * @license GNU General Public License
 * @version 1
 * @since 22 September 2011
 * @example path 
 */
class OauthTokens extends Pas_Db_Table_Abstract {

    protected $_name = 'oauthTokens';

    protected $_primary = 'id';


    /** Get the cached token for accessing twitter's oauth'd endpoint
     * @access public
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

    /** Create a token
     * @access public
     * @param object $data
     * @return type
     */
    public function create($data){
        $token = (object)$data;
        $tokenRow = $this->_tokens->createRow();	
        $tokenRow->service = 'yahooAccess';
        $tokenRow->accessToken = serialize(urldecode($token->oauth_token));
        $tokenRow->tokenSecret = serialize($token->oauth_token_secret);
        $tokenRow->guid = serialize($token->xoauth_yahoo_guid);
        $tokenRow->sessionHandle = serialize($token->oauth_session_handle);
        $tokenRow->created = $this->getTimeForForms();
        $tokenRow->expires = $this->expires();
        $tokenRow->save();
        $tokenData = array(
            'accessToken' => $token->oauth_token, 
            'secret' => $token->oauth_token_secret
                );
        return $tokenData;	
    }
}