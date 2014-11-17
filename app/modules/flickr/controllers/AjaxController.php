<?php

/** Controller for pulling ajax data from flickr.
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Pas_Yql_Flickr
 */
class Flickr_AjaxController extends Pas_Controller_Action_Admin
{

    /** The flickr key to use
     * @access protected
     * @var string
     */
    protected $_flickr;

    /** The flickr api object
     * @access protected
     * @var \Pas_Yql_Flickr
     */
    protected $_api;

    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->acl->allow('public', null);
        $this->_helper->layout->disableLayout();
        $this->_flickr = Zend_Registry::get('config')->webservice->flickr;
        $this->_api = new Pas_Yql_Flickr($this->_flickr);

    }

    /** Display the index action for mapping flickr images
     * @access public
     * @return void
     */
    public function indexAction()
    {
        if (!($this->getCache()->test('mappingflickr'))) {
            $ph = $this->_api->getPhotosGeoData($start = 0, $limit = 50, $this->_flickr->userid);
            $this->getCache()->save($ph);
        } else {
            $ph = $this->getCache()->load('mappingflickr');
        }
        $this->view->recent = $ph;
        $this->_response->setHeader('Content-Type', 'application/vnd.google-earth.kml+xml');
    }
}