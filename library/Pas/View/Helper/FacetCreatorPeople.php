<?php
/** 
 * This view helper takes the array of facets and their counts and produces
 * an html rendering of these with links for the search.
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * echo $this->facetCreatorPeople()
 * ->setFacets($facets);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package View_Helper
 * @version 1
 * @since 30/1/2012
 * @license GNU
 * @uses Pas_Exception
 * @uses Zend_View_Helper_Url
 * @uses Zend_Controller_Front
 * @example /app/modules/database/views/scripts/people/index.phtml
 */
class Pas_View_Helper_FacetCreatorPeople extends Zend_View_Helper_Abstract {
   
    protected $_facets;
    
    protected $_params;
    
    public function getParams() {
        $this->_params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        return $this->_params;
    }
    
    public function getFacets() {
        return $this->_facets;
    }

    public function setFacets( array $facets) {
        $this->_facets = $facets;
        return $this;
    }

    public function facetCreatorPeople() {
        return $this;
    }  
    
    public function __toString() {
        $html = '';
        $facets = $this->getFacets();
        if (is_array($facets)) {
            $html .= '<h3>Filter or refine your search</h3>';
            foreach ($facets as $facetName => $facet) {
                $html .= $this->_processFacet($facet, $facetName);
            }
        }
        return $html;
    }

    /** Process the facet array and name
     * @access public
     * @param  array                 $facet
     * @param  string                $facetName
     * @return string
     * @throws Pas_Exception
     */
    protected function _processFacet(array $facet, $facetName) {
        if (is_array($facet)) {
            if (count($facet)) {
        $html = '<div id="facet-' . $facetName .'">';
        $html .= '<h4>' . $this->_prettyName($facetName) . '</h4>';
        $html .= '<ul class="navpills nav-stacked nav">';

        if ($facetName !== 'workflow') {
            $facets = array_slice($facet, 0, 10);
        } else {
            $facets = $facet;
        }
        
        foreach ($facets as $key => $value) {
        $request = $this->getParams();
       
        if (isset($request['page'])) {
            unset($request['page']);
        }
        $request[$facetName] = $key;
        $url = $this->view->url($request,'default',true);
        $html .= '<li>';
        if ($facetName !== 'workflow') {
        $html .= '<a href="' . $url . '" title="Facet query for ';
        $html .= $this->view->facetContentSection()->setString($key);
        $html .= '">';
        $html .= $key . ' ('. number_format($value) .')';
        } else {
        $html .=  '<a href="' . $url . '" title="Facet query for ' . $this->_workflow($key);
        $html .= '">';
        $html .= $this->_workflow($key) . ' ('. number_format($value) .')';
        }
        $html .= '</a>';
        $html .= '</li>';
        }

        $html .= '</ul>';
        
        $request = $this->getParams();

        if (isset($request['page'])) {
            unset($request['page']);
        }
        if (count($facet) > 10) {
            $request['controller'] = 'ajax';
            $request['action'] = 'peoplefacet';
            $request['facetType'] = $facetName;
            $html .= '<a class="btn btn-small overlay" href="';
            $html .= $this->view->url(($request),'default',true);
            $html .= '">All ' . $this->_prettyName($facetName);
            $html .= ' options <i class="icon-plus"></i></a>';
        }
        if (array_key_exists($facetName, $request)) {
        $facet = $request[$facetName];
        if (isset($facet)) {
            unset($request[$facetName]);
            $html .= '<p><i class="icon-remove-sign"></i> <a href="';
            $html .= $this->view->url(($request),'default',true);
            $html .= '" title="Clear the facet">Clear this filter</a></p>';
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
            case 'createdBy':
                $clean = 'Created by user';
                break;
            default:
                $clean = ucfirst($name);
                break;
        }

        return $clean;
    }

    /** Get the workflow if needed
     * @access public
     * @param int $key
     * @return string
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
