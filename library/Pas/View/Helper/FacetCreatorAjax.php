<?php
/** This view helper takes the array of facets and their counts and produces
 * an html rendering of these with links for the search.
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * echo $this->facetCreatorAjax()->setFacets($facets);
 * </code>
 * 
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @version 1
 * @since 30/1/2012
 * @copyright Daniel Pett
 * @author Daniel Pett <dpett at britishmuseum.org>
  * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Pas_Exception
 * @uses Zend_View_Helper_Url
 * @uses Zend_Controller_Front
 */
class Pas_View_Helper_FacetCreatorAjax extends Zend_View_Helper_Abstract
{
    /** The action string
     * @access protected
     * @var string
     */
    protected $_action;
    
    /** The controller string
     * @access protected
     * @var string
     */
    protected $_controller;
    
    /** The request object
     * @access protected
     * @var object 
     */
    protected $_request;
    
    /** Get the request object
     * @access public
     * @return \Zend_Controller_Front
     */
    public function getRequest() {
        $this->_request = Zend_Controller_Front::getInstance()->getRequest();
        return $this->_request;
    }
 
    /** Get the requested action
     * @access public
     * @return string
     */
    public function getAction() {
        $this->_action = $this->getRequest()->getActionName();
        return $this->_action;
    }

    /** Get the requested controller
     * @access public
     * @return type
     */
    public function getController() {
        $this->_controller = $this->getRequest()->getControllerName();
        return $this->_controller;
    }
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
     * @return \Pas_View_Helper_FacetCreatorAjax
     */
    public function facetCreatorAjax() {
        return $this;
    }
    
    /** The to string function
     * @access public
     * @return \generateFacets
     */
    public function __toString() {
        return $this->generateFacets( $this->getFacets());
    }
    
    /** Create the facets boxes for rendering
     * @access public
     * @param  array                 $facets
     * @return string
     * @throws Pas_Exception
     */
    public function generateFacets(array $facets) {
        $html = '';
        if (is_array($facets)) {
            foreach ($facets as $facetName => $facet) {
                $html .= $this->_processFacet($facet, $facetName);
            }
        } else {
            $html .= 'The function was not sent an array to query';
        }
        return $html;
    }

    /** Process the facet array and name
     * @access public
     * @param  array $facets
     * @param  string $facetName
     * @return string
     * @throws Pas_Exception
     * @uses Zend_Controller_Front
     * @uses Zend_View_Helper_Url
     */
    protected function _processFacet(array $facets, $facetName) {
        $html = '';
        if (is_array($facets)) { 
            if (count($facets)) {
                $html .= '<div id="facet-';
                $html .= $facetName;
                $html .= '">';
                $html .= '<ul class="facetExpand">';
                foreach ($facets as $key => $value) {
                    $request = $this->getRequest()->getParams();
                    if (isset($request['page'])) {
                        unset($request['page']);
                    }
                    unset($request['facetType']);
                    $request[$facetName] = $key;
                    $request['controller'] = 'search';
                    $request['action'] = 'results';
                    $url = $this->view->url($request,'default',true);
                    $html .= '<li>';
                    if ($facetName !== 'workflow') {
                        $html .= '<a href="';
                        $html .= $url;
                        $html .= '" title="Facet query for ';
                        $html .= $this->view->facetContentSection()->setString($key);
                        $html .= '">';
                        $html .= $key;
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
            $request = $this->getRequest()->getParams();
            $request['controller'] = 'search';
            $request['action'] = 'results';
            if (isset($request['page'])) {
                unset($request['page']);
            }
            if (count($facets) > 10) {
                $request['controller'] = 'ajax';
                $request['action'] = 'facet';
                unset($request['facetType']);
            }
            if (array_key_exists($facetName,$request)) {
            $facet = $request[$facetName];
            if (isset($facet)) {
                unset($request[$facetName]);
                unset($request['facetType']);
            }
        }
        $html .= '</div>';
            }
        }
        return $html;
    }

    /** Create a pretty name for the facet
     * @access public
     * @param  string $name
     * @return string
     */
    protected function _prettyName($name) {
        switch ($name) {
            case 'objectType':
                $clean = 'Object type';
                break;
            case 'objecttype':
                $clean = 'Object type';
                break;
            case 'broadperiod':
                $clean = 'Broad period';
                break;
            case 'county':
                $clean = 'County of origin';
                break;
            case 'denominationName':
                $clean = 'Denomination';
                break;
            case 'mintName':
                $clean = 'Mint';
                break;
            case 'rulerName':
                $clean = 'Ruler/issuer';
                break;
            case 'licenseAcronym':
                $clean = 'License applicable';
                break;
            case 'materialTerm':
                $clean = 'Material';
                break;
            case 'institution':
                $clean = 'Institution';
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
