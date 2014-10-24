<?php

/** Controller for displaying information about coins
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Pas_Solr_Handler
 *
 */
class Database_VisualisationsController extends Pas_Controller_Action_Admin
{

    /** Init the controller
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow(null);
    }

    /** Redirect as no direct access to the coins index page
     * @access public
     * @return void
     * @todo put something in the view!
     */
    public function indexAction()
    {
        //Nothing here now!
    }

    /** The objects action
     * @access public
     * @return void
     */
    public function objectsAction()
    {
        //Magic in view
    }

    /** An experimental heat map action
     * @access public
     * @return void
     */
    public function heatmapAction()
    {
        $params['show'] = 50000;
        $params['format'] = 'kml';
        $params['q'] = '*:*';
        $search = new Pas_Solr_Handler();
        $search->setCore('objects');
        $search->setFields(array(
                'fourFigureLat', 'fourFigureLon')
        );
        $search->setParams($params);
        $search->execute();
        $this->view->results = $search->processResults();
    }
}