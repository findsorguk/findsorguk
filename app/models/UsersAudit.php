<?php
/** Model for auditing changes to personal data entries
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

class UsersAudit extends Pas_Db_Table_Abstract {

	protected $_name = 'usersAudit';

	protected $_primaryKey = 'id';

}