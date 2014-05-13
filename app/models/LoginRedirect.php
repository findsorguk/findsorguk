<?php
/**
* A model to manipulate the login redirect page data
*
* @category Pas
* @package  Pas_Db_Table
* @subpackage   Abstract
* @author   Mary Chester-Kadwell mchester-kadwell @ britishmuseum.org
* @author   Daniel Pett dpett @ britishmuseum.org
* @copyright    Copyright (c) 2014 Mary Chester-Kadwell/ Trustees British Museum
* @license  GNU General Public License
* @version  1
* @since    9 May 2014
*/

class LoginRedirect extends Pas_Db_Table_Abstract
{
    protected $_name = 'loginRedirect';

    protected $_primary = 'id';

    /** The default array of available URIs
     * 
     * @var array
     */
    protected $_default = array(
        '/database' => 'Simple search',
        '/database/search/advanced' => 'Advanced search',
        '/database/myscheme/myfinds' => 'Finds recorded by me',
        '/database/myscheme/myinstitution' => 'My institution\'s records',
        '/database/myscheme/recordedbyflos' => 'My finds recorded by FLOs',
        '/users/account' => 'My account page',
        '/database/people' => 'People',
        //I have changed this to recording from volunteer recording
        '/guide' => 'Recording guide'
        );

    /** The default array of role specific uris
     * 
     * @var array
     */
    protected $_redirects = array(
        'flos' => '/database/myscheme/myfinds',
        'fa' => '/database/search/advanced',
        'admin' => '/users/account',
        'member' => '/database/myscheme/recordedbyflos',
        'treasure' => '/database',
        'hero' => '/database',
        'research' => '/database/search/advanced'
        );

    /** Get a dropdown key value pair list for uri and alias
     *  @return array
     */
    public function getOptions() {
        return $this->_default;
    }

    /** Get the user's role
     * @return string user role 
     */
    public function getUserRole() {
        return $this->user()->role;
    }

    /** Get the default uri for the user role
     * @return array uri and label pair
     */
    public function getDefaultUri(){
        $defaultUri = array_search($this->getUserRole(),
                   array_flip($this->_redirects));
        $label = array_search($defaultUri, array_flip($this->_default));
        $uri = array($defaultUri => $label);
        return $uri;
    }


    /** Get the uri
     * 
     * @param $uri
     */
    public function getUri( $uri ){
        if(sizeof($uri) > 0) {
        $label = array_search($uri[0]['uri'], array_flip($this->_default));
        $uri = array($uri[0]['uri'] => $label);
        return $uri;
        }
    }

    /** Get the uri to return for the form and redirect
     * 
     */
    public function getConfig(){
        //Line flows over 80 character, so return
        $select = $this->select()->from( $this->_name, array( 'uri' ))
                ->where('userID = ?', (int) $this->userNumber() );

        $uri = $this->getAdapter()->fetchAll($select);
        $dbChoice = $this->getUri($uri);
        if(!is_null($dbChoice)) {
            return $dbChoice;

        } else {
           return $this->getDefaultUri();

        }
    }


    /** Update the config for each user
     * 
     * @param $data
     */
    public function updateConfig( $data ) {
        if(array_key_exists('csrf', $data)) {
            unset($data['csrf']);
        }
	
        $updateData['uri'] = $data['uri'];
		$updateData['created'] = $this->timeCreation();
		$updateData['createdBy'] = $this->userNumber();
		$updateData['userID'] = $this->userNumber();
		//Delete the existing menu option
		parent::delete('userID =' . $this->userNumber() );
		//Insert the new option
		return parent::insert($updateData);
	}
}