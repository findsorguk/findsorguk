<?php
/** Controller for displaying information about coins
* 
* @category   Pas
* @package Pas_Controller_Action
* @subpackage Admin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class Database_VisualisationsController extends Pas_Controller_Action_Admin {

    public function init() {	
    $this->_helper->_acl->allow(NULL);
    }
    
    /** Redirect as no direct access to the coins index page
    */
    public function indexAction() {
    }
    
    public function getData($params){
    	
    }
    
    public function findspotsAction(){
    	
    }
    
    public function objectsAction(){
    }
    
    public function heatmapAction(){
    $params['show'] = 50000;
	$params['format'] = 'kml';
	$params['q'] = '*:*'; 
//	'-knownas:* objecttype:coin';
	$search = new Pas_Solr_Handler();
        $search->setCore('beowulf');
	$search->setFields(array(
            'fourFigureLat', 'fourFigureLon')
	);
	$search->setParams($params);
	$search->execute();
	$this->view->results = $search->processResults();	
    }

}