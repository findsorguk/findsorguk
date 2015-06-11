<?php

/**
 * Description of CommentType
 *
 * An example of use:
 * <code>
 * <?php
 * echo $this->commentType()->setType('newsComment');
 * ?>
 * </code>
 *
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @version 1
 * @copyright Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @since 23/1/2012
 * @uses Zend_View_Helper_Url
 * @author danielpett
 */
class Pas_View_Helper_CommentType extends Zend_View_Helper_Abstract
{
    /** The id to query
     * @access protected
     * @var int
     */
    protected $_id;

    /** The type to query
     * @access protected
     * @var string
     */
    protected $_type;

    /** The server
     * @access protected
     * @var string
     */
    protected $_server;

    /** Get the ID to query
     * @access public
     * @return type
     */
    public function getId()
    {
        return $this->_id;
    }

    /** Get the type
     * @access public
     * @return type
     */
    public function getType()
    {
        return $this->_type;
    }

    /** Get the server
     * @access public
     * @return type
     */
    public function getServer()
    {
        $this->_server = $this->view->serverUrl();
        return $this->_server;
    }

    /** Set the id to query
     * @access public
     * @param  int $id
     * @return \Pas_View_Helper_CommentType
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /** Set the type to query
     * @access public
     * @param  string $type
     * @return \Pas_View_Helper_CommentType
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    /** The comment type function
     * @access public
     * @return \Pas_View_Helper_CommentType
     */
    public function commentType()
    {
        return $this;
    }

    /** Get the data for each comment
     * @return string
     * @throws Zend_Exception
     * @access public
     */
    public function getData()
    {
        switch ($this->getType()) {
            case 'findComment':
                $finds = new Finds();
                $data = $finds->fetchRow($finds->select()->where('id = ?', $this->getId()));
                break;
            case 'newsComment':
                $news = new News();
                $data = $news->fetchRow($news->select()->where('id = ?', $this->getId()));
                break;
            default:
                throw New Zend_Exception('That type of comment is not a choice');
        }

        return $data;
    }

    /** Build the Html for rendering
     * @access public
     * @return string
     * @throws Zend_Exception
     */
    public function buildHtml()
    {
        $html = '';
        $data = $this->getData();
        if ($data instanceof Zend_Db_Table_Row) {
            switch ($this->getType()) {
                case 'findComment':
                    $url = $this->view->url(
                        array(
                            'module' => 'database',
                            'controller' => 'artefacts',
                            'action' => 'record',
                            'id' => $this->getId()),
                        'default',
                        true);
                    $html .= '<a href="';
                    $html .= $this->getServer();
                    $html .= $url;
                    $html .= '">Relating to find: ';
                    $html .= $data->old_findID;
                    $html .= '</a>';
                    break;
                case 'newsComment':
                    $url = $this->view->url(
                        array(
                            'module' => 'news',
                            'controller' => 'stories',
                            'action' => 'article',
                            'id' => $this->getId()),
                        'default',
                        true);
                    $html .= '<a href="';
                    $html .= $this->getServer();
                    $html .= $url;
                    $html .= '">Relating to news article: ';
                    $html .= $data->title;
                    $html .= '</a>';
                    break;
                default:
                    throw new Zend_Exception('You need a comment type');
            }
        }

        return $html;
    }

    /** The magic method to return
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->buildHtml();
    }

}
