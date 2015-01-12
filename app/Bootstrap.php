<?php

/** Bootstrap for the website to run
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Zend
 * @package    Zend_Application
 * @subpackage Bootstrap
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version    1.0
 * @since      22 September 2011
 * @uses Zend_Registry
 * @uses Zend_Config
 * @uses Zend_Controller_Front
 * @uses Zend_Controller_Response_Http
 */

set_exception_handler(function ($exception) {
    $log = array(
        'message' => $exception->getMessage(),
        'trace' => array(),
    );
    foreach ($exception->getTrace() as $item) {
        $trace = isset($item['class']) ? $item['class'] . $item['type'] : '';
        $trace .= $item['function'] . '()';
        $log['trace'][] = $trace;
    }
//    echo $log['message'] . "\n";
//    foreach ($log['trace'] as $trace) {
//        echo " - $trace\n";
//    }

    echo 'An error has occurred during start up.';
});

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /** Initialise the config and save to the registry
     * @access protected
     */
    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions());
        Zend_Registry::set('config', $config);
    }


    /** Setup the default timezone
     * @access protected
     */
    protected function _initDate()
    {
        date_default_timezone_set(Zend_Registry::get('config')->settings->application->datetime);
    }


    /** Initialise the database or throw error
     * @access protected
     * @throws Exception
     */
//    protected function _initDatabase()
//    {
//        $this->bootstrap('db');
//        $this->getPluginResource('db');
//        $database = Zend_Registry::get('config')->resources->db;
//
//            // setup database
//            $db = Zend_Db::factory($database);
//            Zend_Registry::set('db', $db);
//            Zend_Db_Table::setDefaultAdapter($db);
//        } catch (Zend_Db_Adapter_Exception $e) {
//            if ($e->getPrevious()) {
//                echo '[' . get_class($e)
//                    . '] has the previous exception of ['
//                    . get_class($e->getPrevious())
//                    . ']' . PHP_EOL;
//            } else {
//                echo '[' . get_class($e)
//                    . '] does not have a previous exception'
//                    . PHP_EOL;
//            }
//
//            echo $e;
//        }
//    }


    /** Setup layouts for the site and modules
     * @access protected
     */
    protected function _initLayouts()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->setParam('useDefaultControllerAlways', false);
        $frontController->registerPlugin(new Pas_Controller_Plugin_ModuleLayout());
        $frontController->registerPlugin(new Pas_Controller_Plugin_StyleAndAlternate());
    }


    /** Initialise the various caches and save to registry
     * @access protected
     */
    protected function _initCache()
    {
        $this->bootstrap('cachemanager');
        Zend_Registry::set('cache', $this->getResource('cachemanager')->getCache('cache'));
    }

    /** Get the site url
     * @access protected
     */
    protected function _initSiteUrl()
    {
        $siteurl = Zend_Registry::get('config')->siteurl;
        Zend_Registry::set('siteurl', $siteurl);
    }

    /** Initialise the response and set gzip status
     * @access protected
     */
    protected function _initResponse()
    {
        $response = new Zend_Controller_Response_Http;
        $response->setHeader('X-Powered-By', 'Dan\'s magic army of elves')
            ->setHeader('Host', 'finds.org.uk')
            ->setHeader('X-Compression', 'gzip')
            ->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + 2 * 3600) . ' GMT', true);
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->setResponse($response);
    }


    /** Initialise the view objects
     * @access protected
     * @return \Zend_View
     */
    protected function _initView()
    {
        $options = $this->getOptions();
        if (isset($options['resources']['view'])) {
            $view = new Zend_View($options['resources']['view']);
        } else {
            $view = new Zend_View;
        }
        if (isset($options['resources']['view']['doctype'])) {
            $view->doctype($options['resources']['view']['doctype']);
        }

        if (isset($options['resources']['view']['contentType'])) {
            $view->headMeta()->appendHttpEquiv('Content-Type', $options['resources']['view']['contentType']);
        }

        $view->setScriptPath(APPLICATION_PATH . '/views/scripts/');
        foreach ($options['resources']['view']['helperPath'] as $k => $v) {
            $view->addHelperPath($v, $k);
        }
        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);
        // Return it, so that it can be stored by the bootstrap
        $view->placeholder('tag');
        return $view;
    }

    /** Initialise the jquery version
     * @access protected
     */
    protected function _initJQuery()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->jQuery()->enable()
            ->setVersion('1.11.1')
            ->setUiVersion('1.11.0')
            ->uiEnable();
    }

    /** Setup the authorisation
     * @access protected
     */
    protected function _initAuth()
    {
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session());
        Zend_Registry::set('auth', $auth);
        $maxSessionTime = 60 * 60 * 60;
    }

    /** Initialise the logging
     * @access protected
     * @todo make better use of logs
     */
    protected function _initRegisterLogger()
    {
        $this->bootstrap('Log');
        if (!$this->hasPluginResource('Log')) {
            throw new Zend_Exception('Log not enabled in config.ini');
        }
        $logger = $this->getResource('Log');
        assert(!is_null($logger));
        Zend_Registry::set('log', $logger);
    }

    /** Initialise the action helpers
     * @access protected
     */
    protected function _initHelpers()
    {
        $acl = new Pas_Acl();
        $aclHelper = new Pas_Controller_Action_Helper_Acl(null,
            array('acl' => $acl)
        );
        Zend_Registry::set('acl', $acl);
        Zend_Controller_Action_HelperBroker::addHelper($aclHelper);

        $sendFile = new Pas_Controller_Action_Helper_SendFile();
        Zend_Controller_Action_HelperBroker::addHelper($sendFile);

        $configObject = new Pas_Controller_Action_Helper_Config();
        Zend_Controller_Action_HelperBroker::addHelper($configObject);

        $geocoder = new Pas_Controller_Action_Helper_GeoCoder();
        Zend_Controller_Action_HelperBroker::addHelper($geocoder);

        $identity = new Pas_Controller_Action_Helper_Identity();
        Zend_Controller_Action_HelperBroker::addHelper($identity);

        $akismet = new Pas_Controller_Action_Helper_Akismet();
        Zend_Controller_Action_HelperBroker::addHelper($akismet);

        $audit = new Pas_Controller_Action_Helper_Audit();
        Zend_Controller_Action_HelperBroker::addHelper($audit);

        $coinForm = new Pas_Controller_Action_Helper_CoinFormLoader();
        Zend_Controller_Action_HelperBroker::addHelper($coinForm);

        $coinFormLoader = new Pas_Controller_Action_Helper_CoinFormLoaderOptions();
        Zend_Controller_Action_HelperBroker::addHelper($coinFormLoader);

        $coinSummaryFormLoader = new Pas_Controller_Action_Helper_CoinSummaryFormLoaderOptions();
        Zend_Controller_Action_HelperBroker::addHelper($coinSummaryFormLoader);

        $solr = new Pas_Controller_Action_Helper_SolrUpdater();
        Zend_Controller_Action_HelperBroker::addHelper($solr);

        $findspot = new Pas_Controller_Action_Helper_FindspotFormOptions();
        Zend_Controller_Action_HelperBroker::addHelper($findspot);

        $findspotFail = new Pas_Controller_Action_Helper_FindspotFailedOptions();
        Zend_Controller_Action_HelperBroker::addHelper($findspotFail);

        $secuid = new Pas_Controller_Action_Helper_GenerateSecuID();
        Zend_Controller_Action_HelperBroker::addHelper($secuid);

        $mailer = new Pas_Controller_Action_Helper_Mailer();
        Zend_Controller_Action_HelperBroker::addHelper($mailer);

        $announcements = new Pas_Controller_Action_Helper_Announcements();
        Zend_Controller_Action_HelperBroker::addHelper($announcements);

        $redirects = new Pas_Controller_Action_Helper_LoginRedirect();
        Zend_Controller_Action_HelperBroker::addHelper($redirects);

    }

    /** Set up rest routing
     * @access public
     * @todo do better than this
     */
    public function _initRoutes()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/config/routes.ini', 'production');
        $router->addConfig($config, 'routes');
    }

    protected function _initTag()
    {
        $this->bootstrap('View');
        $view = $this->getResource('View');
        $view->placeholder('tag');
    }

    public function __construct($application)
    {
        parent::__construct($application);
        Pas_Controller_Plugin_BootstrapError::set();
    }
}