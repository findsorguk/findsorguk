<?php
/** Model for auditing changes to personal data entries
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/modules/admin/controllers/UsersController.php 
 */

class UsersAudit extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'usersAudit';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primaryKey = 'id';

}