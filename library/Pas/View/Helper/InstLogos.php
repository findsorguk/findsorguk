<?php
/**
 * A view helper for displaying logos for an institution
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->instLogos()->setInst($inst);
 * ?>
 * </code>
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    GNU Public
 * @version    1
 * @since      17 November 2011
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @example /app/views/scripts/partials/contacts/staff.phtml
 */
class Pas_View_Helper_InstLogos extends Zend_View_Helper_Abstract {

    /** Institution to query
     * @access protected
     * @var string
     */
    protected $_inst;

    /** Get the institution
     * @access public
     * @return string
     */
    public function getInst() {
        return $this->_inst;
    }

    /** Set the institution to query
     * @access public
     * @param string $inst
     * @return \Pas_View_Helper_InstLogos
     */
    public function setInst($inst) {
        $this->_inst = $inst;
        return $this;
    }

    /** The main function
     * @access public
     * @return \Pas_View_Helper_InstLogos
     */
    public function instLogos(){
        return $this;
    }

    /** To string
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->getLogos($this->getInst());
    }

    /** Get the logos from the db
     * @access public
     * @param string $inst
     * @return array|function
     */
    public function getLogos($inst) {
        $logos = new InstLogos();
        $data = $logos->getLogosInst($inst);
        return $this->buildHtml($data);
    }

    /** Build the html
     * @access public
     * @param array $data
     * @return string
     */
    public function buildHtml(array $data) {
        $html = '';
        $html .= '<ul class="ilogo">';
        $html .= $this->view->partialLoop('partials/contacts/logos.phtml',$data);
        $html .= '</ul>';
        return $html;
    }

}