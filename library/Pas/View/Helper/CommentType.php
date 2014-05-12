<?php
/**
 * Description of CommentType
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @version 1
 * @copyright Daniel Pett
 * @license GNU
 * @since 23/1/2012
 * @uses Zend_View_Helper_Url
 * @author danielpett
 */
class Pas_View_Helper_CommentType extends Zend_View_Helper_Abstract{

    protected $_id;

    protected $_type;

    protected $_server;

    /** Construct the objects
     *
     * @param type $id
     * @param type $type
     */
    public function __construct( $id, $type ) {
        $this->_id = $id;
        $this->_type = $type;
        $this->_server = $this->view->serverUrl();
    }

    /** The comment type function
     *
     * @return \Pas_View_Helper_CommentType
     */
    public function commentType(  ){
        return $this;
    }

    /** Get the data for each comment
     *
     * @param int $id
     * @param string $type
     * @return type
     * @throws Pas_Exception_BadJuJu
     */
    public function getData(){
        switch($this->_type){
            case 'findComment':
                $finds = new Finds();
                $data = $finds->fetchRow($finds->select()->where('id = ?', $this->_id));
                break;
            case 'newsComment':
                $news = new News();
                $data = $news->fetchRow($news->select()->where('id = ?', $this->_id));
                break;
            default:
                throw New Zend_Exception('That type of comment is not a choice');
            }
        return $data;
    }

    /** Build the Html for rendering
     *
     * @param object $data
     * @param string $type
     * @param int $id
     * @return string
     * @throws Pas_Exception_BadJuJu
     */
    public function buildHtml(){
        $data = $this->getData();
        if($data instanceof Zend_Db_Table_Row){
            switch($type){
                case 'findComment':
                    $url = $this->view->url(
                            array(
                                'module' => 'database',
                                'controller' => 'artefacts',
                                'action' => 'record',
                                'id' => $id),
                            'default',
                            true);
                    $html = '<a href="' .  $this->_server . $url  . '">Relating to find: '
                        . $data->old_findID . '</a>';
                    break;
                case 'newsComment':
                    $url = $this->view->url(
                            array(
                                'module' => 'news',
                                'controller' => 'stories',
                                'action' => 'article',
                                'id' => $id),
                            'default',
                            true);
                    $html = '<a href="' . $this->_server . $url . '">Relating to news article: '
                        . $data->title . '</a>';
                    break;
                default:
                    throw new Zend_Exception('You need a comment type');
                    }
            }
        return $html;
    }

    /** The magic method to return
     *
     * @return string
     */
    public function __toString() {
        return $this->buildHtml();
    }

}