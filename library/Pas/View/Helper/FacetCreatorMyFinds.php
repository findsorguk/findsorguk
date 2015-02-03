<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** This view helper takes the array of facets and their counts and produces
 * an html rendering of these with links for the search.
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @version 1
 * @since 30/1/2012
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Pas_Exception
 * @uses Zend_View_Helper_Url
 * @uses Zend_Controller_Front
 */
class Pas_View_Helper_FacetCreatorMyFinds extends Zend_View_Helper_Abstract
{
    /** Create the facets boxes for rendering
     * @access public
     */

    public function facetCreatorMyFinds()
    {
        return $this;
    }

    /** Create string of html
     * @return string
     * @access public
     */
    public function __toString()
    {
        $html = '';
        $html .= '<h3 class="lead">Search facets</h3>';
        foreach ($this->getFacets() as $facetName => $facet) {
            $html .= $this->_processFacet($facet, $facetName);
        }

        return $html;

    }

    protected $_facets;

    /** Get the facets to parse
     * @return mixed
     */
    public function getFacets()
    {
        return $this->_facets;
    }

    /** Set the facets
     * @param array $facets
     */
    public function setFacets(array $facets)
    {
        $this->_facets = $facets;
        return $this;
    }


    /** Process the facet array and name
     * @access public
     * @param  array $facet
     * @param  string $facetName
     * @return string
     * @throws Pas_Exception
     * @uses Zend_Controller_Front
     * @uses Zend_View_Helper_Url
     */
    protected function _processFacet(array $facet, $facetName)
    {
        if (is_array($facet)) {
            if (count($facet)) {
                $html = '<div id="facet-' . $facetName . '">';
                $html .= '<h4 class="lead">' . $this->_prettyName($facetName) . '</h4>';
                $html .= '<ul class="navpills nav-stacked nav">';

                if ($facetName !== 'workflow') {
                    $facets = array_slice($facet, 0, 10);
                } else {
                    $facets = $facet;
                }
                foreach ($facets as $key => $value) {
                    $request = Zend_Controller_Front::getInstance()->getRequest()->getParams();
                    if (isset($request['page'])) {
                        unset($request['page']);
                    }
                    $request[$facetName] = $key;

                    $url = $this->view->url($request, 'default', true);

                    $html .= '<li>';

                    if ($facetName !== 'workflow') {
                        $html .= '<a href="' . $url . '" title="Facet query for ' . $this->view->facetContentSection()->setString($key);
                        $html .= '">';
                        $html .= $key . ' (' . number_format($value) . ')';
                    } else {
                        $html .= '<a href="' . $url . '" title="Facet query for ' . $this->_workflow($key);
                        $html .= '">';
                        $html .= $this->_workflow($key) . ' (' . number_format($value) . ')';
                    }

                    $html .= '</a>';
                    $html .= '</li>';
                }

                $html .= '</ul>';
                $request = Zend_Controller_Front::getInstance()->getRequest()->getParams();

                if (isset($request['page'])) {
                    unset($request['page']);
                }
                if (count($facet) > 10) {
                    $request['controller'] = 'ajax';
                    $request['action'] = 'myfindsfacet';
                    $request['facetType'] = $facetName;
                    $html .= '<a class="btn btn-small overlay" href="' . $this->view->url(($request), 'default', true) . '">All ' . $this->_prettyName($facetName) . ' options <i class="icon-plus"></i></a>';
                }

                if (array_key_exists($facetName, $request)) {
                    unset($request[$facetName]);
                    $html .= '<p><i class="icon-remove-sign"></i> <a href="' . $this->view->url(($request), 'default', true)
                        . '" title="Clear the facet">Clear this facet</a></p>';
                }

                $html .= '</div>';

                return $html;
            }
        } else {
            throw new Pas_Exception('The facet is not an array');
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
            case 'creator':
                $clean = 'Created by user';
                break;
            default:
                $clean = ucfirst($name);
                break;
        }

        return $clean;
    }

    /** The workflow switch
     * @access protected
     * @param int $key
     * @return string
     */
    protected function _workflow($key)
    {
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
