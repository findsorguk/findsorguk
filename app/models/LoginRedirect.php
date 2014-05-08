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
	
	public function getConfig(){

	}

	public function updateConfig( $data ){

	}


}