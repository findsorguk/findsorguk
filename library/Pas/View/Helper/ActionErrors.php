<?php
/** 
 * A view helper for displaying errors 
 * 
 * This view helper formats the errors created and logged in the error 
 * controller.
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * echo $this->actionErrors();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @category Pas
 * @package Pas_View_Helper
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @copyright (c) 2014, Daniel Pett
 * 
 */
class Pas_View_Helper_ActionErrors extends Zend_View_Helper_Abstract
{
    /** The css class to use
     * @access protected
     * @var string
     */
    protected $_class = 'action-errors';

    /** The css id to use
     * @access protected
     * @var string
     */
    protected $_id = 'action-errors';

    /** Get the class to use
     * @access public
     * @return string $_class
     */
    public function getClass() {
        return $this->_class;
    }

    /** Set the class
     * @access public
     * @param string $_class
     */
    public function setClass($_class) {
        $this->_class = $_class;
        return $this;
    }

    /** Return the csss ID
     * @return string $_id
     * @access public
     */
    public function getID()  {
        return $this->_id;
    }

    /** Set the ID
     * @access public
     * @param string $_id
     */
    public function setID($_id) {
        $this->_id = $_id;
        return $this;
    }

    /** The class to return
     * @access public
     * @return \Pas_View_Helper_ActionErrors
     */
    public function actionErrors()  {
        return $this;
    }

    /** Generate the css and html
     * @access public
     * @return string
     */
    public function generateHtml() {
        $result = '';
        if (isset($this->_view->actionErrors)) {
            $result .= '<ul class="';
            $result .= $this->getClass();
            $result .= '" id="';
            $result .= $this->getID();
            $result .= '">' . PHP_EOL;
            foreach ($this->_view->actionErrors as $error) {
                $result .= '<li>' . $error . '</li>' . PHP_EOL;
            }
            $result .= '</ul>' . PHP_EOL;
        }
        return $result;
    }

    /** The string function
     * @access public
     * @return string
     */
    public function __toString()  {
        return $this->generateHtml();
    }
}
