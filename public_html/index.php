<?php
// Define root
defined('ROOT_PATH') || define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../app/'));

//Define the cache path
defined('CACHE_PATH') || define('CACHE_PATH', realpath(dirname(__FILE__) . '/../cache/'));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Define path to Solr
defined('SOLR_PATH') || define('SOLR_PATH', realpath(dirname(__FILE__) . '/../solr/'));

//Define the logs path
defined('LOGS_PATH') || define('LOGS_PATH', realpath(dirname(__FILE__) . '/../logs/'));

// Set up image path
defined('IMAGE_PATH') || define('IMAGE_PATH', realpath(dirname(__FILE__) . '/images/'));

// Set up assets constant for path
defined('ASSETS_PATH') || define('ASSETS_PATH', realpath(dirname(__FILE__) . '/assets/'));

// Set Memory limit
ini_set('memory_limit', '128M');

//Set upload max size
ini_set('upload_max_filesize','20M');

// Ensure libraries are on include_path
set_include_path(
        '.' . PATH_SEPARATOR . '../library/'
        . PATH_SEPARATOR . '../library/Zend/library'
        . PATH_SEPARATOR . '../library/ZendX/'
        . PATH_SEPARATOR . '../library/Pas/'
        . PATH_SEPARATOR . '../library/HTMLPurifier/library/'
        . PATH_SEPARATOR . '../library/EasyBib/library/'
        . PATH_SEPARATOR . '../library/tcpdf/'
        . PATH_SEPARATOR . '../library/easyrdf/lib/'
        . PATH_SEPARATOR . '../library/imagecow/'
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
        'Imagecow' => '../library/imagecow/src/',
        'easyRDF' => '../library/easyrdf/lib/',
    ),
    'namespaces' => array(
        'Imagecow' => '../library/imagecow/src/',
    ),
    'fallback_autoloader' => true,
));

$loader->register();

require_once 'HTMLPurifier/Bootstrap.php';

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