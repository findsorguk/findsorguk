<?php
/** Model for link table for finds to images
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo work out if still needed as a class
 * @example /app/modules/admin/controllers/NumismaticsController.php
*/

class ReecePeriodEmperors extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'reeceperiods_rulers';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';
}