<?php

/**
 * Retrieve and manipulate data from the quotes table
 *
 * An example of code:
 *
 * <code>
 * <?php
 * $announcements = new Quotes();
 * $data = $announcements->getAnnouncements();
 * ?>
 * </code>
 *
 * @author        Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category      Pas
 * @package       Db_Table
 * @subpackage    Abstract
 * @license       http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version       1
 * @since         22 September 2011
 * @example       /app/modules/admin/controllers/QuotesController.php
 */
class Quotes extends Pas_Db_Table_Abstract
{


    /** Get current date
     *
     * @access public
     * @return string
     */
    public function getTimeForForms()
    {
        return Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
    }

    /** The table name
     *
     * @access protected
     * @var string
     */
    protected $_name = 'quotes';

    /** The primary key
     *
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get all quotes from the admin interface
     *
     * @param integer $page
     *
     * @return array
     */
    public function getQuotesAdmin($page)
    {
        $quotes = $this->getAdapter();
        $select = $quotes->select()
            ->from($this->_name)
            ->joinLeft(
                'users',
                'users.id = ' . $this->_name
                . '.createdBy',
                array('fullname')
            )
            ->joinLeft(
                'users',
                'users_2.id = ' . $this->_name
                . '.updatedBy',
                array('fn' => 'fullname')
            )
            ->order('id DESC');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if (isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber((int)$page);
        }
        return $paginator;
    }

    /** Get all valid quotes
     *
     * @access public
     * @return array
     */
    public function getValidQuotes()
    {
        $key = md5('frontquotes');
        if (!$data = $this->_cache->load($key)) {
            $quotes = $this->getAdapter();
            $select = $quotes->select()
                ->from($this->_name, array('quote', 'quotedBy'))
                ->where('expire >= ?', $this->getTimeForForms())
                ->where('status = ?', (int)1)
                ->where('type = ? ', 'quote')
                ->order('RAND()')
                ->limit(1);
            $data = $quotes->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get all valid announcements
     *
     * @return array
     */
    public function getAnnouncements()
    {
        $key = md5('announcementsAndQuotes');
        $data = $this->_cache->load($key);
        if (!$data) {
            $quotes = $this->getAdapter();
            $select = $quotes->select()
                ->from($this->_name, array('quote', 'quotedBy'))
                ->where('expire >= ?', $this->getTimeForForms())
                ->where('status = ?', (int)1)
                ->where('type = ? ', 'announcement')
                ->order('expire');
            $data = $quotes->fetchAll($select);
            $this->_cache->save($data, $key, array(), 86400);
        }
        return $data;
    }
}