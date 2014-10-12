<?php
/**
 * Model for accredited status dropdowns for the museums table.
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category   Pas
 * @package    Pas_Db_Table
 * @subpackage Abstract
 * @copyright  2010 - DEJ Pett
@license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 */

class AccreditedStatus extends Pas_Db_Table_Abstract {

    /** The primary key
     * @access protected
     * @var int
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var type
     */
    protected $_name = 'accreditedStatus';
}