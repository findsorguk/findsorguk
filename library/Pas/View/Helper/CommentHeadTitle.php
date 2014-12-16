<?php
/**
 * A view helper for displaying the correct headtitle from parameters posted
 *
 * An example of use:
 * <code>
 * <?php
 * echo $this->commentHeadTitle()->setParams($params);
 * ?>
 * </code>
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett@britishmuseum.org>
 */

class Pas_View_Helper_CommentHeadTitle extends Zend_View_Helper_Abstract
{
    /** The paramter array
     * @access protected
     * @var array
     */
    protected $_params;

    /** Get the parameters
     * @access public
     * @return array
     */
    public function getParams() {
        return $this->_params;
    }

    /** Set the params to query
     * @access public
     * @param  array $params
     * @return \Pas_View_Helper_CommentHeadTitle
     */
    public function setParams(array $params) {
        $this->_params = $params;
        return $this;
    }

    /** Assemble the title
     * @access public
     * @return string
     * @todo Add an array key exists clause?
     */
    public function assemble() {
        $params = $this->getParams();
        if(array_key_exists('approval', $params)) {
            switch ($params['approval']) {
                case 'approved':
                    $title = 'All approved comments';
                    break;
                case 'spam':
                    $title = 'All spam comments';
                    break;
                case 'moderation':
                    $title = 'All comments awaiting moderation';
                    break;
                default:
                    $title = 'All comments';
                    break;
            }
        } else {
            $title = 'All comments';
        }
        return $title;
    }

    /** Function to return
     * @access public
     * @return \Pas_View_Helper_CommentHeadTitle
     */
    public function commentHeadTitle(){
        return $this;
    }

    /** To string method
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->assemble();
    }
}
