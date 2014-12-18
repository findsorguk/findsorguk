<?php

/** View helper to render an MP's parliamentary career
 * @author Daniel Pett <dpett@britishmuseum.org>
  * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @copyright (c) 2014, Daniel Pett <dpett@britishmuseum.org>
 * @package Pas
 * @category Pas_View_Helper
 * @version 1
 * @since 1
 *
 */

class Pas_View_Helper_ParliamentCareer extends Zend_View_Helper_Abstract
{

    /** The data variable
     * @access protected
     * @var array
     */
    protected $_data;

    /** Get the data to use in functions
     * @access public
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /** Set the data to use
     * @access public
     * @param  array                             $data
     * @return \Pas_View_Helper_ParliamentCareer
     */
    public function setData(array $data)
    {
        $this->_data = $data;

        return $this;
    }

    /** Return the function
     * @access public
     * @return \Pas_View_Helper_ParliamentCareer
     */
    public function parliamentCareer()
    {
        return $this;
    }

    /** Build html to return
     * @access public
     * @return string
     */
    public function buildHtml()
    {
        $data = $this->getData();
        $html = '';
        if (is_array($data)) {
            $html .= '<div id="career">';
            foreach ($data as $d) {
                $html .= $this->view->partial('partials/news/mp.phtml', $d);
            }
            $html .= '</div>';
        }

        return $html;
    }

    /** Return the string
     * @access public
     * @return type
     */
    public function __toString()
    {
        return $this->buildHtml();
    }
}
