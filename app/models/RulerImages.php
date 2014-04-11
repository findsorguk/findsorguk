<?php

/** Data model for accessing and manipulating images attached to issuers/rulers
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
* @todo 		add edit and delete functions
* @todo 		add caching
*/

class RulerImages extends Pas_Db_Table_Abstract {

	protected $_primary = 'id';

	protected $_name = 'rulerImages';

	/** Get a list of all images attached to a ruler
	* @param integer $id
	* @return array
	* @todo add cache
	*/
	public function getImages($id) {
		$images = $this->getAdapter();
		$select = $images->select()
            ->from($this->_name)
			->where('rulerID = ?', (int)$id);
        return $images->fetchAll($select);
	}
	
	
	/** Get a specific image
	* @param integer $id
	* @return array
	*/
	public function getFilename($id) {
		$images = $this->getAdapter();
		$select = $images->select()
            ->from($this->_name, array('filename'))
			->where('id = ?', (int)$id);
        return $images->fetchAll($select);
	}

}