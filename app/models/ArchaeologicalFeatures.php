<?php

/**
 * A model for accessing archaeological feature information from database
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $features = new ArchaeologicalFeatures();
 * $feature_options = $features->getOptions();
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
class ArchaeologicalFeatures extends Pas_Db_Table_Abstract
{

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'archfeature';
    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get key value pairs and cache the result for use in form dropdowns
     * @access public
     * @return array
     */
    public function getOptions()
    {
        $key = md5('archfeatureee');
        if (!$options = $this->_cache->load($key)) {
            $select = $this->select()
                ->from($this->_name, array('id', 'feature'))
                ->order('feature ASC')
                ->where('valid = ?', (int)1);
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, $key);
        }
        return $options;
    }

}