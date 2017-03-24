<?php

/** Model for manipulating completeness details
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $licenses = new LicenseTypes();
 * $license = $licenses->getList();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo add some caching to model
 * @example /app/forms/ImageEditForm.php
 */
class LicenseTypes extends Pas_Db_Table_Abstract
{

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'licenseType';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get dropdown values for personal copyrights
     * @access public
     * @return array
     */
    public function getList()
    {
        $key = md5('cclicenses');
        if (!$options = $this->_cache->load($key)) {
            $select = $this->select()
                ->from($this->_name, array('id', 'license'))
                ->order('id');
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, $key);
        }

        return $options;
    }

    /** Get license text
     * @access public
     * @param string $id License ID
     * @return array
     */
    public function getLicenseText($id)
    {
        $select = $this->select()
            ->from($this->_name, array('licenseText' => 'license'))
            ->where('id LIKE ?', (int)$id . '%')
            ->limit(1);
        return $this->getAdapter()->fetchAll($select);
    }

}