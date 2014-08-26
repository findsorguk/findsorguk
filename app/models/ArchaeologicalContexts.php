<?php
/**
 * A model for accessing archaeological context information from database
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $contexts = new ArchaeologicalContexts();
 * $contexts_options = $contexts->getOptions();
 * ?>
 * </code>
 *
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @copyright (c) 2014 Mary Chester-Kadwell
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 26 August 2014
 * @example /app/forms/ArchaeologyForm.php
 */

class ArchaeologicalContexts extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'archsitetype';
    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get key value pairs and cache the result for use in form dropdowns
     * @access public
     * @return array
     */
    public function getOptions() {
        $key = md5('archsitetypeee');
        if (!$options = $this->_cache->load($key)) {
            $select = $this->select()
                ->from($this->_name, array('id', 'sitetype'))
                ->order('sitetype ASC')
                ->where('valid = ?', (int)1);
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, $key);
        }
        return $options;
    }

}