<?php
/**
 * A model to manipulate jetton classes.
 * 
 * An example of code:
 * <code>
 * <?php
 * $categories = new JettonClasses();
 * $cat_options = $categories->getClasses();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/forms/TokenJettonForm.php
 */
class JettonClasses extends Pas_Db_Table_Abstract {
    
    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'jettonClasses';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Retrieve a key pair array for dropdown
     * @access public
     * @return array
     */
    public function getClasses() {
        $key = md5('jettonClasses');
        if (!$data = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('id', 'className'));
            $data = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }
}