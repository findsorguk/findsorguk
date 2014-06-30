<?php
/** Data model for accessing and manipulating rulers and reverse type link table
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) year, John Doe
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license GNU General Public License
 * @version 1
 * @since 22 October 2010, 17:12:34
 */
class RulerRevType extends Pas_Db_Table_Abstract {

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'ruler_reversetype';

}