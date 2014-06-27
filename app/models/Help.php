<?php
/**
 *  Model for setting up help topics
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new Help();
 * $data = $model->getContentAdmin($page);
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
 * @example /app/modules/admin/controllers/HelpController.php
 */

class Help extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'help';

    /** The primary key
     * @access public
     * @var integer
     */
    protected $_primary = 'id';

    /** Retrieve front page content for the help section
     * @access public
     * @param string $section
     * @param integer $frontpage
     * @param integer $publish
     * @return array
     */
    public function getFrontContent($section, $frontpage = 1, $publish = 3) {
        $content = $this->getAdapter();
        $select = $content->select()
                ->from($this->_name, array(
                    'body', 'metaDescription', 'metaKeywords',
                    'title', 'created', 'updated'
                    ))
                ->joinLeft('users','users.id = content.author',
                        array('fullname'))
                ->where('frontPage = ?', (int)$frontpage)
                ->where('publishState = ?', (int)$publish)
                ->where('section = ?',(string)$section);
        return $content->fetchAll($select);
    }

    /** Retrieve content in help section via the slug
     * @access public
     * @param string $section
     * @param string $slug
     * @return array
     */
    public function getContent($slug) {
        $content = $this->getAdapter();
        $select = $content->select()
                ->from($this->_name, array(
                    'body', 'metaDescription', 'metaKeywords',
                    'title', 'created', 'updated'
                    ))
                ->joinLeft('users','users.id = content.author',
                        array('fullname'))
                ->where('publishState = ?', (int)3)
                ->where('slug = ?',(string)$slug);
        return $content->fetchAll($select);
    }

    /** Retrieve content in help section for admin via pagination
     * @access public
     * @param integer $page
     * @return array
    */
    public function getContentAdmin($page) {
        $content = $this->getAdapter();
        $select = $content->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name
                        . '.updatedBy', array('fn' => 'fullname'))
                ->order('created DESC');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber((int)$page);
        }
        return $paginator;
    }

    /** Retrieve content by topics in help section via pagination
     * @access public
     * @param string $page
     * @param string $section
     * @return array
     */
    public function getTopics($page, $section) {
        $content = $this->getAdapter();
        $select = $content->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name
                        . '.updatedBy', array('fn' => 'fullname'))
                ->where('publishState = ?', (int)3)
                ->where('section = ?', (string)$section)
                ->order('created DESC');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber((int)$page);
        }
        return $paginator;
    }

    /** Retrieve content by topic id
     * @access public
     * @param string $section
     * @param integer $id
     * @return array
     */
    public function getTopic($section, $id) {
        $content = $this->getAdapter();
        $select = $content->select()
                ->from($this->_name,array(
                    'body', 'metaDescription', 'metaKeywords',
                    'title', 'created', 'updated'
                    ))
                ->joinLeft('users','users.id = help.author', array('fullname'))
                ->where('publishState = ?', (int)3)
                ->where('section = ?', (string)$section)
                ->where($this->_name . '.id = ?', (int)$id);
        return $content->fetchAll($select);
    }
}