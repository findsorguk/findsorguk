<?php

/** Model for manipulating copyright data
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new Copyrights();
 * $data = $model->getTypes();
 * ?>
 * </code>
 *
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @author Daniel Pett dpett @ britishmuseum.org
 * @copyright 2010 - DEJ Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/forms/ImageForm.php
 */
class Copyrights extends Pas_Db_Table_Abstract
{

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'copyrights';

    /** The primary key
     * @access protected
     * @var int
     */
    protected $_primary = 'id';

    /** Get drop down values for personal copyrights
     * @access public
     * @return array
     */
    public function getTypes()
    {
        if (!$options = $this->_cache->load('imagecopyright')) {
            $select = $this->select()
                ->from($this->_name, array('copyright', 'copyright'))
                ->order('copyright');
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, 'imagecopyright');
        }
        return $options;
    }


    /** Get all copyrights for editing within the system
     * @access public
     * @return array
     */
    public function getCopyrightsAdmin($page)
    {
        $copyrights = $this->getAdapter();
        $select = $copyrights->select()
            ->from($this->_name,array('copyright', 'created', 'updated', 'id'))
            ->joinLeft('users', 'users.id = ' . $this->_name
                . '.createdBy', array('fullname'))
            ->joinLeft('users', 'users_2.id = ' . $this->_name
                . '.updatedBy', array('fn' => 'fullname'))
            ->order('copyrights.created DESC');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if (isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber((int)$page);
        }
        return $paginator;
    }
}