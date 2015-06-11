<?php
/**
 * A view helper for displaying export links for PAS data
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->export()->setParams($params);
 * ?>
 * </code>
 * 
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see Zend_View_Helper_Abstract
 * @todo See if this still used/
 */
class Pas_View_Helper_Export extends Zend_View_Helper_Abstract
{
    /** The parameters to use
     * @access protected
     * @var array
     */
    protected $_params = array();

    /** Get the parameters
     * @access public
     * @return array
     */
    public function getParams() {
        return $this->_params;
    }

    /** Set the paramters
     * @access public
     * @param array $params
     * @return \Pas_View_Helper_Export
     */
    public function setParams(array $params) {
        $this->_params = $params;
        return $this;
    }

    /** Get the cleaner class
     * @access protected
     * @var object
     */
    protected $_cleaner;

    /** The array keys to remove
     * @access protected
     * @var array
     */
    protected $_keys = array( 'controller', 'module', 'action', 'submit');

    /** The cleaning object
     * @access public
     * @return array
     */
    public function getCleaner() {
        $this->_cleaner = new Pas_Array();
        return $this->_cleaner;
    }

    /** Clean the array
     * @access public
     * @param array $array
     * @return array
     */
    public function cleanArray( array $array ) {
        return $this->_cleaner->array_cleanup($array, $this->_keys);
    }

    /** The export function
     * @access public
     * @return \Pas_View_Helper_Export
     */
    public function export(){
        return $this;
    }

    /** Render the html
     * @access public
     */
    public function __toString() {
        $cleanData = $this->cleanArray($this->getParams());
        $where = array();
        foreach ($cleanData as $key => $value) {
            if (!is_null($value)) {
                $where[] = $key . '/' . urlencode($value);
            }
            }
            $whereString = implode('/', $where);
            $query = $whereString;
            $mapUrl = $this->view->url(
                    array(
                        'module' => 'database',
                        'controller' => 'search',
                        'action' => 'map'
                        ),null,true) . '/' . $query;

            $map = '<a href="' . $mapUrl . '">Map results</a>';
            $exportformats = '<p>' . $map;

            $auth = Zend_Auth::getInstance();

            if ($auth->hasIdentity()) {

                $exportformats .= ' | <a  href="';
                $exportformats .=  $this->view->url(
                        array(
                            'module' => 'database',
                            'controller' => 'search',
                            'action' => 'save'
                            ),null,true);
                $exportformats .= '" title="Save this search">';
                $exportformats .= 'Save this search for later</a> | <a href="';
                $exportformats .= $this->view->url(
                        array(
                            'module' => 'database',
                            'controller' => 'search',
                            'action' => 'email'
                            ),null,true);
                $exportformats .= '" title="Email this search">Email this search</a>';
            }

            $exportformats .= '</p>';
    }
}
