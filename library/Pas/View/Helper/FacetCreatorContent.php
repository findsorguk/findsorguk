<?php
/** 
 * This view helper takes the array of facets and their counts and produces
 * an html rendering of these with links for the search.
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * echo $this->facetContentCreator()->setFacets($facets);
 * ?>
 * </code>
 * 
 * 
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @version 1
 * @since 30/1/2012
 * @copyright Daniel Pett
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @license GNU
 * @uses Pas_Exception_BadJuJu
 * @uses Zend_View_Helper_Url
 * @uses Zend_Controller_Front
 */
class Pas_View_Helper_FacetCreatorContent extends Zend_View_Helper_Abstract {
    /** The facets variable
     * @access public
     * @var array
     */
    protected $_facets;
    
    /** Get the facets to query
     * @access public
     * @return array
     */
    public function getFacets() {
        return $this->_facets;
    }

    /** Set the facets to query
     * @access public
     * @param array $facets
     * @return \Pas_View_Helper_FacetCreatorContent
     */
    public function setFacets( array $facets) {
        $this->_facets = $facets;
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_FacetCreatorContent
     */
    public function facetCreatorContent(){
        return $this;
    }
    
    /** The to string function
     * @access public
     * @return type
     */
    public function __toString() {
        return $this->buildFacets( $this->getFacets());
    }
    
    /** Create the facets boxes for rendering
     * @access public
     * @param  array $facets
     * @return string
     * @throws Pas_Exception_BadJuJu
     */

    public function buildFacets(array $facets) {
        $html = '';
        if (is_array($facets)) {
            $html .= '<h3>Search facets</h3>';
            foreach ($facets as $facetName => $facet) {
                $html .= $this->_processFacet($facet, $facetName);
            }
        } 
        return $html;
    }

    /** Process the facet array and name
     * @access public
     * @param  array $facet
     * @param  string $facetName
     * @return string
     * @uses Zend_Controller_Front
     * @uses Zend_View_Helper_Url
     */
    protected function _processFacet(array $facet, $facetName) {
        $html = '';
        if (is_array($facet)) {
            $html .= '<div id="facet-' . $facetName .'">';
            $html .= '<h4>' . $this->_prettyName($facetName) . '</h4>';
            $html .= '<ul class="navpills nav-stacked nav">';
            foreach ($facet as $key => $value) {
                $url = $this->view->url(array(
                    'fq' . $facetName => $key
                        ),'default',false);
        
                $html .= '<li>';
                if ($facetName !== 'workflow') {
                    $html .= '<a href="';
                    $html .= $url; 
                    $html .= '" title="Facet query for ';
                    $html .= $this->view->facetContentSection()->setString($key);
                    $html .= '">';
                    $html .= $this->view->facetContentSection()->setString($key);
                    $html .= ' (';
                    $html .= number_format($value);
                    $html .= ')';
                } else {
                    $html .= '<a href="';
                    $html .= $url;
                    $html .= '" title="Facet query for ';
                    $html .= $this->_workflow($key);
                    $html .= '">';
                    $html .= $this->_workflow($key);
                    $html .= ' (';
                    $html .= number_format($value);
                    $html .= ')';
                }

                $html .= '</a>';
                $html .= '</li>';
                }
                $html .= '</ul>';
        
                $request = Zend_Controller_Front::getInstance()->getRequest()->getParams();

        
                if (isset($request['page'])) {
                    unset($request['page']);
                }
        
                if (array_key_exists($facetName,$request)) {
                    $facet = $request['fq' . $facetName];
                    if (isset($facet)) {
                        unset($request['fq' . $facetName]);
                        $html .= '<p><i class="icon-remove-sign"></i> <a href="';
                        $html .= $this->view->url($request,'default',true);
                        $html .= '" title="Clear the facet">Clear this facet</a></p>';
                    }
                }
            $html .= '</div>';
        } 
        return $html;
    }

    /** Create a pretty name for the facet
     * @access public
     * @param  string $name
     * @return string
     */
    public function _prettyName($name) {
        switch ($name) {
            case 'objectType':
                $clean = 'Object type';
                break;
            case 'broadperiod':
                $clean = 'Broad period';
                break;
            case 'county':
                $clean = 'County of origin';
                break;
            default:
                $clean = ucfirst($name);
                break;
        }
        return $clean;
    }

    /** Function for rendering workflow labels
     * @access protected
     * @param type $key
     * @return string
     * @todo move this to a library function
     */
    public function _workflow($key) {
        switch ($key) {
            case '1':
                $type = 'Quarantine';
                break;
            case '2':
                $type = 'Review';
                break;
            case '3':
                $type = 'Published';
                break;
            case '4':
                $type = 'Validation';
                break;
            default:
                $type = 'Unset workflow';
                break;
            }
        return $type;
    }
}