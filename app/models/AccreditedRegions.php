<?php
/**
 * Model for accredited regions for the museums table.
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category   Pas
 * @package    Db_Table
 * @subpackage Abstract
 * @version    1
 * @since	  22 September 2011
 * @license http://URL name
*/

class AccreditedRegions extends Pas_Db_Table_Abstract {

    /** The primary key
     * @access protected
     * @var int
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'accreditedRegions';
}