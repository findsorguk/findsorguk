<?php
/** A model for interacting with the link table for denominations and rulers
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add caching
*/
class DenomRulers extends Pas_Db_Table_Abstract {
	
	protected $_primary = 'id';
	protected $_name = 'denominations_rulers';

}