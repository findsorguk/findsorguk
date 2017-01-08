<?php
/**
 * Model for subperiods
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @todo add edit and delete functions
 * @version 1
*/
class SubPeriods extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'subperiods';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

}