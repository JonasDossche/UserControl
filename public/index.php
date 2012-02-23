<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
		realpath(APPLICATION_PATH . '/../library'),
		get_include_path(),
)));


set_include_path(implode(PATH_SEPARATOR, array(
		realpath(APPLICATION_PATH . '/../library/doctrine/lib'),
		get_include_path(),
)));

set_include_path(implode(PATH_SEPARATOR, array(
		realpath(APPLICATION_PATH . '/../library/doctrine/lib/vendor/doctrine-dbal/lib'),
		get_include_path(),
)));

set_include_path(implode(PATH_SEPARATOR, array(
		realpath(APPLICATION_PATH . '/../library/doctrine/lib/vendor/doctrine-common/lib'),
		get_include_path(),
)));






$path = 'C:\wamp\www\UserControl\application\controllers';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);





/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();