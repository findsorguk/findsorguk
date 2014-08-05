<?php
/** 
 * A model to manipulate jetton groups.
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $groups = new JettonGroups();
 * $group_options = $groups->getGroups();
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
class JettonGroups extends Pas_Db_Table_Abstract {
	
    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'jettonGroup';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get Jetton groups
     * @access public
     * @return array
     */
    public function getGroups() {
        $key = md5('jettonClasses');
	if (!$data = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('id', 'groupName'));
            $data = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($data, $key);
	}
	return $data;
    }
    
    /** Get groups associated with classes
     * @access public
     * @param integer $classID
     * @return array
     */
    public function getGroupsToClasses( $classID ) {
    	$select = $this->select()
                ->from($this->_name, array('id', 'term' => 'groupName'))
                ->joinLeft('classesJettonGroups', 
                        'classesJettonGroups.groupID = jettonGroup.id ',
                        array())
                ->where('classesJettonGroups.classID = ?', (int) $classID);
        return $this->getAdapter()->fetchAll($select);
    }
}