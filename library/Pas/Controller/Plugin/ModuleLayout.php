<?php

/** A front controller plugin for layouts
 *
 * This class can choose whether to enable or disable layouts after the
 * request has been dispatched.
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller
 * @subpackage Plugin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 */
class Pas_Controller_Plugin_ModuleLayout extends Zend_Controller_Plugin_Abstract
{

    /** The base path for structured menus
     * @access protected
     * @var string
     */
    protected $_basePath = 'structure/menus/';

    /** Get the base path for the menus
     * @access public
     * @return string
     */
    public function getBasePath()
    {
        return $this->_basePath;
    }

    /** Set up the available array of contexts
     * @var array $_contexts
     */
    protected $_contexts = array(
        'xml', 'rss', 'json',
        'atom', 'kml', 'georss',
        'ics', 'rdf', 'xcs',
        'vcf', 'csv', 'foaf',
        'pdf', 'qrcode', 'geojson',
        'midas');

    /** Set up contexts to disable layout for based on modules
     * @var array $_disabled
     */
    protected $_disabled = array('ajax', 'oai', 'sitemap', 'version1');

    /** Create the layout after the request has been dispatched
     *  Disable or enable layouts depending on type.
     * @access public
     * @param  object $request The request being made
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        $controller = $request->getControllerName();

        $contextSwitch = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch');
        $response = $this->getResponse();

        $view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;
        $route = Zend_Controller_Front::getInstance()->getRouter()->getCurrentRoute();
        if (!in_array($controller, $this->_disabled) && (!in_array($contextSwitch->getCurrentContext(), $this->_contexts))) {
            if (!in_array($contextSwitch->getCurrentContext(), $this->_contexts)) {
                $module = strtolower($request->getModuleName());
                $view->contexts = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch')
                    ->getActionContexts(Zend_Controller_Front::getInstance()->getRequest()->getActionName());
                $view->messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
                $ini = new Zend_Config_Ini(APPLICATION_PATH . '/config/menus.ini', 'production');
                $menus = $ini->toArray();

                if (in_array($module, array_keys($menus))) {
                    $layout = $menus[$module]['layout'];
                    $view->headTitle($menus[$module]['layout'])->setSeparator(' - ');
                    $response->insert('sidebar', $view->render(
                        $this->getBasePath() . $menus[$module]['menu'] . 'Sidebar.phtml')
                    );
                } else {
                    $layout = 'new';
                }

            }
            if (!$route instanceOf Zend_Rest_Route) {
                //$response->insert('userdata', $view->render('structure/userData.phtml'));
                $response->insert('breadcrumb', $view->render('structure/breadcrumb.phtml'));
                $response->insert('navigation', $view->render('structure/navigation.phtml'));
                $response->insert('footer', $view->render('structure/footer.phtml'));
                $response->insert('messages', $view->render('structure/messages.phtml'));
                $response->insert('contexts', $view->render('structure/contexts.phtml'));
                $response->insert('analytics', $view->render('structure/analytics.phtml'));
                //$response->insert('searchfacet', $view->render('structure/facetSearch.phtml'));
                $response->insert('announcements', $view->render('structure/announcements.phtml'));
                $response->insert('bronzeage', $view->render('structure/bronzeAgeWidget.phtml'));
                $response->insert('staffs', $view->render('structure/staffordshireHoardWidget.phtml'));
                $response->insert('searchForm', $view->render('structure/searchForm.phtml'));
                $response->insert('tags', $view->render('structure/tag.phtml'));
                $template = Zend_Layout::getMvcInstance();
                if ($template->getMvcEnabled()) {
                    $template->setLayoutPath(APPLICATION_PATH . '/layouts/');
                    if ($controller != 'error') {
                        $template->setLayout($layout);
                    } else {
                        $template->setLayout('home');
                    }
                }
            } else {
                $contextSwitch->setAutoDisableLayout(true)->initContext();

            }

        } else {
            $contextSwitch->setAutoDisableLayout(true)->initContext();
        }
    }

}

