<?php

/** This view helper takes the array of facets and their counts and produces
 * an html rendering of these with links for the search.
 * @category Pas
 * @package View
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
class Pas_View_Helper_FacetCreatorAjaxMyImages extends Zend_View_Helper_Abstract
{
    /** The action to use
     * @var string
     */
    protected $_action;

    /** The controller to use
     * @var string
     */
    protected $_controller;

    /** Construct the class
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->_controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->_action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
    }

    /** Create the facets boxes for rendering
     * @access public
     * @param  array $facets
     * @return string
     * @throws Pas_Exception
     */
    public function facetCreatorAjaxMyImages()
    {
        return $this;
    }

    /** The facets array
     * @var array
     */
    protected $_facets;

    /** Get the facets array
     * @return mixed
     */
    public function getFacets()
    {
        return $this->_facets;
    }

    /** Set the array of facets
     * @param mixed $facets
     */
    public function setFacets($facets)
    {
        $this->_facets = $facets;
        return $this;
    }

    /** Get the string
     * @access public
     * @return string
     */
    public function __toString()
    {
        if (is_array($this->getFacets())) {
            $html = '';
            foreach ($this->getFacets() as $facetName => $facet) {
                $html .= $this->_processFacet($facet, $facetName);
            }

            return $html;
        } else {
            throw new Pas_Exception('The facets sent are not an array');
        }
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
    protected function _processFacet(array $facets, $facetName)
    {
        if (is_array($facets)) {
            if (count($facets)) {
                $html = '<div id="facet-' . $facetName . '">';
                $html .= '<ul class="facetExpand">';

                foreach ($facets as $key => $value) {
                    $request = Zend_Controller_Front::getInstance()->getRequest()->getParams();
                    if (isset($request['page'])) {
                        unset($request['page']);
                    }
                    unset($request['facetType']);
                    $request[$facetName] = $key;
                    $request['controller'] = 'myscheme';
                    $request['action'] = 'myimages';
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
                $request['controller'] = 'search';
                $request['action'] = 'results';
                if (isset($request['page'])) {
                    unset($request['page']);
                }

                if (array_key_exists($facetName, $request)) {
                    $facet = $request[$facetName];
                    if (isset($facet)) {
                        unset($request[$facetName]);
                        unset($request['facetType']);
                        $html .= '<p><i class="icon-remove-sign"></i> <a href="' . $this->view->url(($request), 'default', true)
                            . '" title="Clear the facet">Clear this facet</a></p>';
                    }
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
