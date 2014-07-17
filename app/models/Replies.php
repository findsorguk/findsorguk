<?php
/**
 * Model for pulling replies to contact us messages from the database
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Db_Table
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @todo add edit and delete functions and cache
*/
class Replies extends Pas_Db_Table_Abstract{

    /** The table name
     * @access protected
     * @var string Table name
     */
    protected $_name = 'replies';

    /** The primary key
     * @access protected
     * @var int The primary key
     */
    protected $_primary = 'id';
}