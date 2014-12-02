<?php
// Define root
defined('ROOT_PATH')
    || define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../app/'));

//Define the cache path
defined('CACHE_PATH')
    || define('CACHE_PATH', realpath(dirname(__FILE__) . '/../cache/'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ?
    getenv('APPLICATION_ENV') : 'production'));

// Define path to Solr
defined('SOLR_PATH')
    || define('SOLR_PATH', realpath(dirname(__FILE__) . '/../solr/'));

//Define the logs path
defined('LOGS_PATH')
    || define('LOGS_PATH', realpath(dirname(__FILE__) . '/../logs/'));

defined('IMAGE_PATH')
    || define('IMAGE_PATH', realpath(dirname(__FILE__) . '/images/'));

ini_set('memory_limit', '128M');
ini_set('upload_max_filesize','16M');
// Ensure library/ is on include_path
// directory setup and class loading
set_include_path(
        '.' . PATH_SEPARATOR . '../library/'
        . PATH_SEPARATOR . '../library/Zend/library'
        . PATH_SEPARATOR . '../library/ZendX/'
        . PATH_SEPARATOR . '../library/Pas/'
        . PATH_SEPARATOR . '../library/HTMLPurifier/library/'
        . PATH_SEPARATOR . '../library/Arc2/'
        . PATH_SEPARATOR . '../library/EasyBib/library/'
        . PATH_SEPARATOR . '../library/EasyBib/'
        . PATH_SEPARATOR . '../library/tcpdf/'
        . PATH_SEPARATOR . '../library/easyrdf/lib/'
        . PATH_SEPARATOR . '../library/Imagecow/'
        . PATH_SEPARATOR . '../app/models/'
        . PATH_SEPARATOR . '../app/forms/'
        . PATH_SEPARATOR . get_include_path()
        );
require_once '../library/ZendX/Loader/StandardAutoloader.php';
$loader = new ZendX_Loader_StandardAutoloader(array(
    'prefixes' => array(
        'Zend' => '../library/Zend/library',
        'HTMLPurifier' => '../library/HTMLPurifier/library/',
        'Pas' => '../library/Pas/',
        'ZendX' => '../library/ZendX/',
        'Imagecow' => '../library/Imagecow/',
        'Imagecow/Libs/' => '../library/Imagecow/Libs/',
        'easyRDF' => '../library/easyrdf/lib/'
    ),
    'namespaces' => array(
        'Imagecow' => '../library/Imagecow',
    ),
    'fallback_autoloader' => true,
));

$loader->register(); // register with spl_autoload_register()

//include 'Zend/Loader/Autoloader.php';
//$autoloader = Zend_Loader_Autoloader::getInstance();
//$autoloader->setDefaultAutoloader(
//        create_function(
//                '$class',"include str_replace('_', '/', \$class) . '.php';"
//                ));
//$autoloader->suppressNotFoundWarnings(false);
//$autoloader->setFallbackAutoloader(true);
require_once 'HTMLPurifier/Bootstrap.php';

//$autoloader->pushAutoloader('HTMLPurifier_Bootstrap', 'autoload');

//$autoloader->registerNamespace('Imagecow');
//$autoloader->registerNamespace('Imagecow\Libs');
/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,array(
        'config' => array(
            APPLICATION_PATH . '/config/application.ini',
            APPLICATION_PATH . '/config/config.ini',
            APPLICATION_PATH . '/config/webservices.ini',
            APPLICATION_PATH . '/config/emails.ini',
            APPLICATION_PATH . '/config/routes.ini'
    ))
);
$application->bootstrap()->run();