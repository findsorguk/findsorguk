<?php 
/** 
 * Link table for linking coins to class
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $coins = new CoinXClass();
 * $coins->insert($insertData);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/modules/database/controllers/CoinsController.php 
*/

class CoinXClass extends Pas_Db_Table_Abstract {

    /** The primary key
     * @access protected
     * @var int
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'coinxclass';
}

