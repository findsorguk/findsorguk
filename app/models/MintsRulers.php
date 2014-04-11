<?php
/** Model for the link table between mints and rulers
* @category 	Pas
* @package 	Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo 		add, edit and delete functions to be created and moved from controllers
* @todo 		add caching
*/
class MintsRulers extends Pas_Db_Table_Abstract {

	protected $_name = 'mints_rulers';

	protected $_primary = 'id';

	/** Retrieve all mints for a specific ruler
	* @param integer $ruler ruler identification number
	* @return array 
	*/
	public function getMint($ruler) {
        $mints = $this->getAdapter();
		$select = $mints->select()
            ->from($this->_name, array('id','term' => 'mint_name'))
			->joinLeft('mints_rulers','mints_rulers.mint_id = mints.id', array())
		    ->joinLeft('rulers','rulers.id = mints_rulers.ruler_id', array())
            ->where('rulers.id = ?', (int)$ruler)
            ->order('mints.mint_name ASC');
        return $mints->fetchAll($select);
}

}
