<?php
/**
 * Model for interacting with macktypes table
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new AbcNumbers();
 * $numbers = $model->getTerms();
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/forms/IronAgeCoinForm.php
 */
class AbcNumbers extends Pas_Db_Table_Abstract {

    /** The name of the database table
     * @access protected
     * @var string
     */
    protected $_name = 'abcNumbers';

    /** The default primary key
     * @access public
     * @var int
     */
    protected $_primary = 'id';


    /** Retrieve key value paired dropdown list array
     * @access public
     * @return array $options
     */
    public function getTerms(){
        if (!$options = $this->_cache->load('abcNumbers')) {
            $select = $this->select()
                ->from($this->_name, array('term', 'term'))
                ->order('id');
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, 'abcNumbers');
        }
        return $options;
    }
}