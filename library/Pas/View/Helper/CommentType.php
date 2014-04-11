<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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

    /** Get the data for each comment
     *
     * @param int $id
     * @param string $type
     * @return type
     * @throws Pas_Exception_BadJuJu
     */
    public function getData( $id,  $type){
    	
    	
        switch($type){
            case 'findComment':
                $finds = new Finds();
                $data = $finds->fetchRow($finds->select()->where('id = ?', $id));
                break;
            case 'newsComment':
                $news = new News();
                $data = $news->fetchRow($news->select()->where('id = ?', $id));
                break;
            default:
                throw New Pas_Exception_BadJuJu();
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
    public function buildHtml(Zend_Db_Table_Row $data,  $type,  $id){
        switch($type){
            case 'findComment':
                $url = $this->view->url(array('module' => 'database',
                    'controller' => 'artefacts',
                    'action' => 'record',
                    'id' => $id),
                        'default',true);
                $html = '<a href="' .  $this->view->serverUrl(). $url  . '">Relating to find: '
                        . $data->old_findID . '</a>';
                break;
            case 'newsComment':
               $url = $this->view->url(array(
                    'module' => 'news',
                    'controller' => 'stories',
                    'action' => 'article',
                    'id' => $id),
                        'default',true);
                 $html = '<a href="' . $this->view->serverUrl(). $url . '">Relating to news article: '
                        . $data->title . '</a>';
                break;
            default:
                throw new Pas_Exception_BadJuJu('You need a comment type');
        }
    return $html;
    }

    /** Display the comment type
     *
     * @param int $id
     * @param string $type
     * @return type
     */
    public function commentType( $id,  $type){
    $data = $this->getData($id, $type);
    return $this->buildHtml($data, $type, $id);
    }
}