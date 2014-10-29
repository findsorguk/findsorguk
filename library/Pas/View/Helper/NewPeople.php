<?php
/**
 * A view helper for returning the number of new sign ups recently
 *
 * A very pointless view helper, but people seem to like it.
 *
 * An example of use
 *
 * <code>
 * <?php
 * echo $this->newPeople();
 * ?>
 * </code>
 *
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see 	   Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett@britishmuseum.org>
 */
class Pas_View_Helper_NewPeople extends Zend_View_Helper_Abstract
{
    /** Get a list of new people from model
     * @access public
     * @return array
     */
    public function getNew() {
        $users = new Users();
        return 	$count = $users->newPeople();
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_NewPeople
     */
    public function newPeople() {
        return $this;
    }

    /** The to string function
     * @access public
     * @return type
     */
    public function __toString() {
        return $this->buildHtml();
    }

    /** The build url function
     * @access public
     * @param array $person
     * @return array]
     */
    public function buildUrl( array $person ) {
        $params = array(
            'module' => 'users',
            'controller' => 'named',
            'action' => 'person',
            'as' => $person['username']);
        return $params;
    }

    /** Build the html and return
     * @access public
     * @return string
     */
    public function buildHtml() {
        $html = '';
        $people = $this->getNew();
        if($people){
            $html .= '<h4 class="lead">Welcome to today\'s new joiners</h4>';
            $html .= '<ul>';
            foreach ($people as $person) {
                $url =
                $html .= '<li><a href="';
                $html .=  $this->view->url($this->buildUrl($person),NULL,true);
                $html .= '" title="View account details for ';
                $html .= $person['username'];
                $html .= '">';
                $html .= $person['username'];
                $html .= '</a></li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }
}