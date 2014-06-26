<?php
/**
 * Model for manipulating frequently asked questions
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new Faqs();
 * $data = $model->getAll();
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license GNU General Public License
 * @version 1
 * @since 22 September 2011
 * @todo add caching
 * @example /app/modules/getinvolved/controllers/FaqController.php
 */

class Faqs extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'faqs';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get all frequently asked questions and their answers
     * @access public
     * @return type
     */
    public function getAll() {
        $faqs = $this->getAdapter();
        $select = $faqs->select()
                ->from($this->_name, array('id','question','answer'))
                ->where('valid = ?', (int)1)
                ->order($this->_primary);
       return $faqs->fetchAll($select);
    }
}