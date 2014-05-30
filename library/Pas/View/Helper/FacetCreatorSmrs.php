<?php
/**
 *
 * This view helper takes the array of facets and their counts and produces
 * an html rendering of these with links for the search.
 *
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @version 1
 * @since 30/1/2012
 * @copyright Daniel Pett
 * @license GNU
 * @uses Pas_Exception_BadJuJu
 * @uses Zend_View_Helper_Url
 * @uses Zend_Controller_Front
 * @author Daniel Pett <dpett@britishmuseum.org>
 */
class Pas_View_Helper_FacetCreatorSmrs extends Zend_View_Helper_Abstract
{
    /** The action string
     * @access public
     * @var string
     */
    protected $_action;

    /** The controller string
     * @access public
     * @var string
     */
    protected $_controller;

    /** The front controller
     * @access public
     * @var \Zend_Controller_Front
     */
    protected $_front;

    /** The array of facets
     * @access public
     * @var array
     */
    protected $_facets;

    /** Get the front controller request
     * @access public
     * @return \Zend_Controller_Front
     */
    public function getFront() {
        $this->_front = Zend_Controller_Front::getInstance()->getRequest();
        return $this->_front;
    }

    /** Get the action from the request
     * @access public
     * @return string The action string
     */
    public function getAction() {
        $this->_action = $this->getFront()->getActionName();
        return $this->_action;
    }

    /** Get teh controller from the request
     * @access public
     * @return string The controller string
     */
    public function getController() {
        $this->_controller = $this->getFront()->getControllerName();
        return $this->_controller;
    }

    /** Get the facets to render
     * @access public
     * @return array
     */
    public function getFacets() {
        return $this->_facets;
    }

    /** Set the facets to render
     * @access public
     * @param array $facets
     * @return \Pas_View_Helper_FacetCreatorSmrs
     */
    public function setFacets(array $facets) {
        $this->_facets = $facets;
        return $this;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_FacetCreatorSmrs
     */
    public function facetCreatorSmrs() {
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->buildHtml($this->getFacets());
    }

    /** Create the facets boxes for rendering
     * @access public
     * @param  array $facets
     * @return string
     * @throws Pas_Exception_BadJuJu
     */

    public function buildHtml(array $facets) {
        $html = '';
        if (is_array($facets)) {
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
     * @throws Pas_Exception_BadJuJu
     * @uses Zend_Controller_Front
     * @uses Zend_View_Helper_Url
     */
    protected function _processFacet(array $facets, $facetName) {
        $html = '';
        if (is_array($facets)) {
            if (count($facets)) {
                $html .= '<div id="facet-' . $facetName .'">';
                $html .= '<ul class="facetExpand">';
                foreach ($facets as $key => $value) {
                    $request = $this->getFront()->getParams();
                    if (isset($request['page'])) {
                        unset($request['page']);
                    }
                    unset($request['facetType']);
                $request[$facetName] = $key;
                $request['controller'] = 'myscheme';
                $request['action'] = 'myimages';
                $url = $this->view->url($request,'default',true);

                $html .= '<li>';
            if ($facetName !== 'workflow') {
                $html .= '<a href="';
                $html .= $url;
                $html .= '" title="Facet query for ';
                $html .= $this->view->facetContentSection()->setKey($key);
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
        $request = $this->getFront()->getParams();
        $request['controller'] = 'smr';
        $request['action'] = 'index';
        if (isset($request['page'])) {
            unset($request['page']);
        }
        if (count($facets) > 10) {
            $request['controller'] = 'ajax';
            $request['action'] = 'facet';
            unset($request['facetType']);
            $html .= '<a class="btn btn-small overlay" href="';
            $html .= $this->view->url(($request),'default',false);
            $html .= '">All ';
            $html .= $this->_prettyName($facetName);
            $html .= ' options <i class="icon-plus"></i></a>';
        }
        $facet = $request[$facetName];
        if (isset($facet)) {
            unset($request[$facetName]);
            unset($request['facetType']);
            $html .= '<p><i class="icon-remove-sign"></i> <a href="';
            $html .= $this->view->url(($request),'default',true);
            $html .= '" title="Clear the facet">Clear this facet</a></p>';
        }

        $html .= '</div>';

        return $html;
            }
        } else {
            throw new Pas_Exception_BadJuJu('The facet is not an array');
        }
    }

    /** Create a pretty name for the facet
     * @access public
     * @param  string $name
     * @return string
     */
    protected function _prettyName($name)
    {
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
            case 'district':
                $clean = 'District';
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

    /** render the correct workflow key
     * @access public
     * @param int $key
     * @return string
     */
    protected function _workflow($key) {
        switch ($key) {
            case 1:
                $type = 'Quarantine';
                break;
            case 2:
                $type = 'Review';
                break;
            case 3:
                $type = 'Published';
                break;
            case 4:
                $type = 'Validation';
                break;
            default:
                $type = 'Unset workflow';
                break;
            }

            return $type;
        }

}
