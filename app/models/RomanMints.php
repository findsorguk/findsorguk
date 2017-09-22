<?php
/**
 * Data model for accessing and manipulating list of Roman mints
 *
 * An example of code
 *
 * <code>
 * <?php
 * $model = new RomanMints();
 * $data = $model->getOptions();
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 		22 October 2010, 17:12:34
 * @todo 		add edit and delete functions
 * @todo 		add caching
 * @example /app/modules/romancoins/controllers/MintsController.php

 */
class RomanMints extends Pas_Db_Table_Abstract {

    /** the table name
     * @access protected
     * @var string
     */
    protected $_name = 'romanmints';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get a list of roman mints as key pair values
     * @access public
     * @return array
     */
    public function getOptions() {
        $select = $this->select()
                ->from($this->_name, array('ID', 'name'))
                ->order('ID');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get a specific mint's details
     * @access public
     * @param integer $id
     * @return array
     */
    public function getMintDetails($id) {
        $mints = $this->getAdapter();
        $select = $mints->select()
                ->from($this->_name)
                ->where('pasID = ?', (int) $id);
        return $mints->fetchAll($select);
    }

    /** Get a list of all details for Roman mints
     * @access public
     * @return array
     */
    public function getRomanMintsList() {
        $mints = $this->getAdapter();
        $select = $this->select()
                ->from($this->_name)
                ->order('name ASC');
       return $mints->fetchAll($select);
    }
}