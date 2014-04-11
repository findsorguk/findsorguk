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
 * @license GNU
 * @uses Pas_Exception_BadJuJu
 * @uses Zend_View_Helper_Url
 * @uses Zend_Controller_Front
 */
class Pas_View_Helper_FacetCreatorAjaxMyImages extends Zend_View_Helper_Abstract {


	protected $_action, $_controller;

	public function __construct(){
		$this->_controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
		$this->_action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
	}
    /** Create the facets boxes for rendering
     * @access public
     * @param array $facets
     * @return string
     * @throws Pas_Exception_BadJuJu
     */

    public function facetCreatorAjaxMyImages(array $facets){
        if(is_array($facets)){
        $html = '';
        foreach($facets as $facetName => $facet){
            $html .= $this->_processFacet($facet, $facetName);
        }
        return $html;
        } else {
            throw new Pas_Exception_BadJuJu('The facets sent are not an array');
        }
    }

    /** Process the facet array and name
     * @access public
     * @param array $facet
     * @param string $facetName
     * @return string
     * @throws Pas_Exception_BadJuJu
     * @uses Zend_Controller_Front
     * @uses Zend_View_Helper_Url
     */
    protected function _processFacet(array $facets, $facetName){
        if(is_array($facets)){
        	if(count($facets)){
        $html = '<div id="facet-' . $facetName .'">';
        $html .= '<ul class="facetExpand">';

        foreach($facets as $key => $value){
        $request = Zend_Controller_Front::getInstance()->getRequest()->getParams();
		if(isset($request['page'])){
            unset($request['page']);
        }
		unset($request['facetType']);
        $request[$facetName] = $key;
		$request['controller'] = 'myscheme';
		$request['action'] = 'myimages';
        $url = $this->view->url($request,'default',true);

        $html .= '<li>';
        if($facetName !== 'workflow'){
        $html .= '<a href="' . $url . '" title="Facet query for ' . $this->view->facetContentSection($key);
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
        $request = Zend_Controller_Front::getInstance()->getRequest()->getParams();
		$request['controller'] = 'search';
		$request['action'] = 'results';
        if(isset($request['page'])){
            unset($request['page']);
        }
		if(count($facets) > 10){
			$request['controller'] = 'ajax';
			$request['action'] = 'facet';
			unset($request['facetType']);
			$html .= '<a class="btn btn-small overlay" href="' . $this->view->url(($request),'default',false) . '">All ' . $this->_prettyName($facetName) . ' options <i class="icon-plus"></i></a>';
		}
                if(array_key_exists($facetName,$request)){
        $facet = $request[$facetName];
        if(isset($facet)){
            unset($request[$facetName]);
            unset($request['facetType']);
            $html .= '<p><i class="icon-remove-sign"></i> <a href="' . $this->view->url(($request),'default',true)
                    . '" title="Clear the facet">Clear this facet</a></p>';
        }
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
     * @param string $name
     * @return string
     */
    protected function _prettyName($name){
        switch($name){
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

    protected function _workflow($key){
        switch($key){
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
