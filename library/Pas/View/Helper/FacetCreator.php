<?php

/** This view helper takes the array of facets and their counts and produces
 * an html rendering of these with links for the search.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->facetCreator()->setFacets($facets);
 * ?>
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
class Pas_View_Helper_FacetCreator extends Zend_View_Helper_Abstract
{

    /** The facets variable
     * @access public
     * @var array
     */
    protected $_facets;

    /** Get the facets to query
     * @access public
     * @return array
     */
    public function getFacets()
    {
        return $this->_facets;
    }

    /** Set the facets to query
     * @access public
     * @param array $facets
     * @return \Pas_View_Helper_FacetCreatorContent
     */
    public function setFacets(array $facets)
    {
        $this->_facets = $facets;
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_FacetCreator
     */
    public function facetCreator()
    {
        return $this;
    }

    /** The to string function
     * @access public
     * @return \generateFacet
     *
     */
    public function __toString()
    {
        return $this->generateFacet($this->getFacets());
    }

    /** Generate the facets
     * @access public
     * @param array $facets
     * @return string
     */
    public function generateFacet($facets)
    {
        $html = '';
        if (is_array($facets)) {
            $html .= '<h3 class="lead">Filter your search</h3>';
            foreach ($facets as $facetName => $facet) {
                $html .= $this->_processFacet($facet, $facetName);
            }
        } else {
            $html .= 'You did not send an array to the function';
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
    protected function _processFacet(array $facet, $facetName)
    {
        $html = '';
        if (is_array($facet)) {
            if (count($facet)) {
                $html .= '<div id="facet-' . $facetName . '">';
                $html .= '<h4 class="lead">' . $this->_prettyName($facetName) . '</h4>';
                $html .= '<ul class="navpills nav-stacked nav">';
                if (!in_array($facetName, array('reeceID', 'workflow'))) {
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
                        $html .= '<a href="';
                        $html .= $url;
                        $html .= '" title="Facet query for ';
                        $html .= $this->view->facetContentSection()->setString($key);
                        $html .= '">';
                        $html .= $key . ' (' . number_format($value) . ')';
                    } else {
                        $html .= '<a href="';
                        $html .= $url;
                        $html .= '" title="Facet query for ';
                        $html . $this->_workflow($key);
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

                if (count($facet) > 10 && $facetName != 'reeceID') {
                    $request['controller'] = 'ajax';
                    $request['action'] = 'facet';
                    $request['facetType'] = $facetName;
                    $html .= '<a class="btn btn-small overlay" href="';
                    $html .= $this->view->url($request, 'default', true);
                    $html .= '" >All ';
                    $html .= $this->_prettyName($facetName);
                    $html .= ' options <i class="icon-plus"></i></a>';

                }

                if (array_key_exists($facetName, $request)) {
                    $facet = $request[$facetName];
                    if (isset($facet)) {
                        unset($request[$facetName]);
                        $html .= '<p><i class="icon-remove-sign"></i>';
                        $html .= ' <a href="';
                        $html .= $this->view->url(($request), 'default', true);
                        $html .= '" title="Clear the facet">';
                        $html .= 'Clear this filter</a></p>';
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
            case 'createdBy':
                $clean = 'Created by user';
                break;
            case 'reeceID':
                $clean = 'Reece Period';
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
    public function _workflow($key)
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