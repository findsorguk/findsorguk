<?php

/**
 * An action helper for loading geographical information for forms
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $this->_helper->geoFormLoaderOptions($this->getRequest()->getPost());
 * ?>
 * </code>
 * @category Pas
 * @package Controller
 * @subpackage Action_Helper
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Exception
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/database/controllers/SearchController.php
 */
class Pas_Controller_Action_Helper_GeoFormLoaderOptions extends Zend_Controller_Action_Helper_Abstract
{

    /** The view object
     * @return \Zend_View
     */
    protected $_view;

    /** he places model
     */
    protected $_places;

    /** Set up the view in predispatch
     * @access public
     * @return Zend_View
     */
    public function preDispatch()
    {
        $this->_view = $this->_actionController->view;
    }

    /** The direct method for the helper
     * @access public
     * @return void
     */
    public function direct($values)
    {

        return $this->optionsGeoLoader($values);
    }


    /** Construct the loader
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->_places = new Places();
    }

    /** Load the options to the form
     * @access public
     * @return void
     * @param $values
     */
    public function optionsGeoLoader($values)
    {
        if (array_key_exists('county', $values)) {
            $districts = $this->_places->getDistrictList($values['county']);
            $parishes = $this->_places->getParishCList($values['county']);
            $this->_view->form->district->addValidator('inArray', false, array(array_keys($districts)));
            $this->_view->form->district->addMultiOptions(array(NULL => 'Choose district',
                'Available districts' => $districts));
            $this->_view->form->parish->addValidator('inArray', false, array(array_keys($parishes)));
            $this->_view->form->parish->addMultiOptions(array(NULL => 'Choose parishes',
                'Available parishes' => $parishes));
        }
    }

}