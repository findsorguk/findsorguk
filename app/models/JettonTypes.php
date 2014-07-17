<?php
/**
 * A model to manipulate the jetton types data.
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $types = new JettonTypes();
 * $type_options = $types->getTypes();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/forms/TokenJettonForm.php 
 */

class JettonTypes extends Pas_Db_Table_Abstract {
	
    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'jettonTypes';
	
    /** The table key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get the types for a dropdown as key value pairs
     * @access public
     * @return array
     */
    public function getTypes() {
        $key = md5('jettonTypes');
        if (!$data = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('id', 'typeName'));
            $data = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get the types of jettons to their group ID
     * @access public
     * @param integer $groupID
     * @return array
     */
    public function getTypesToGroups( $groupID ) {
        $select = $this->select()
                ->from($this->_name, array('id', 'term' => 'typeName'))
                ->joinLeft('groupsJettonsTypes', 
                        'groupsJettonsTypes.typeID = jettonTypes.id ',
                        array())
                ->where('groupsJettonsTypes.groupID = ?', (int) $groupID);
        return $this->getAdapter()->fetchAll($select);
    }
}