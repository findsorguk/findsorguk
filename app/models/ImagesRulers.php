<?php
/** Model for interacting with the link table for images and rulers
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
*/
class ImagesRulers extends Pas_Db_Table_Abstract{

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'coin_ruler_images';
}
