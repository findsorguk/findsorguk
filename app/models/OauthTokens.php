<?php
/** 
 * Model for interacting with oauth tokens
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $tokens = new OauthTokens();
 * $tokenexists = $tokens->fetchRow($tokens->select()
 * ->where('service = ?', 'twitterAccess'));
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example  /app/models/Twitter.php 
 */
class OauthTokens extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'oauthTokens';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get the cached token for accessing twitter's oauth'd endpoint
     * @access public
     * @return array
     */
    public function getTokens(){
        $key = md5('oauthtwitter');
        if (!$data = $this->_cache->load($key)) {
            $tokens = $this->getAdapter();
            $select = $tokens->select()
                    ->from($this->_name)
                    ->where('service = ?', 'twitterAccess');
            $data =  $tokens->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Create a token
     * @access public
     * @param object $data
     * @return array
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