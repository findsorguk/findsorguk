<?php
/**
 * A view helper for rendering the names of hoard materials in the view
 *
 * Example use:
 * <code>
 * <?php
 * echo $this->hoardMaterials()
 * ->setMaterials($this->hoardMaterials)
 * ?>
 * </code>
 *
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @copyright (c) 2014, Mary Chester-Kadwell
 * @uses Zend_View_Helper_Partial
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 16 September 2014

 *
 */

class Pas_View_Helper_HoardMaterials extends Zend_View_Helper_Abstract {

    /** The array of valid materials
     * @access protected
     * @var array
     */
    protected $_validMaterials;

    /** The array of materials in a hoard
     * @access protected
     * @var array
     */
    protected $_materials;

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_HoardMaterials
     */
    public function hoardMaterials() {
        return $this;
    }

    /** Set the array of valid materials
    * @access public
    * @return array
    */
    public function setValidMaterials() {
        $materials = new Materials();
        $materialsList = $materials->getPrimaries();
        $this->_validMaterials = $materialsList;
    }

    /** Get the array of valid materials
     * @access public
     * @return array
     */
    public function getValidMaterials() {
        return $this->_validMaterials;
    }

    /** Set the array of materials in a hoard
     * @access public
     * @return array
     */
    public function setMaterials(array $mats) {
        $this->_materials = $mats;
        return $this;
    }

    /** Get the array of materials in a hoard
 * @access public
 * @return array
 */
    public function getMaterials() {
        return $this->_materials;
    }

    /** Adds material terms to the array of materials in a hoard
     * @access public
     * @return array
     */
    public function buildMaterialTerms() {
        $this->setValidMaterials();
        $reversed = array_flip($this->_materials);
        $replaced = array_intersect_key($this->_validMaterials, $reversed);
        $this->_materials = $replaced;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->buildHtml();
    }

    /** Build the html
     * @access public
     * @return string
     */
    public function buildHtml() {
        $this->buildMaterialTerms();
        $html = '';
        $html .= $this->view->partial(
            'partials/hoards/materials.phtml', array('hoardMaterials' => $this->getMaterials()));
        return $html;
    }

}