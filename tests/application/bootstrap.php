<?php

// définition des chemins utiles
$rootPath = dirname( dirname( dirname( __FILE__ ) ) );
define( 'APPLICATION_PATH', $rootPath . DIRECTORY_SEPARATOR . 'application' );
$confPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'configs';

/** Zend_Loader_Autoloader */
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace( 'Escarmouche_' );

$loader = new Zend_Loader_Autoloader_Resource( array( 'basePath' => APPLICATION_PATH, 'namespace' => 'Application' ) );
$loader->addResourceType( 'model', 'models', 'Model' );

// Define application environment
define('APPLICATION_ENV', 'testing');

/**
* Setup default DB adapter
*/
$ini = new Zend_Config_Ini( APPLICATION_PATH . '/configs/tests.ini');
$db = Zend_Db::factory( 'pdo_sqlite', $ini->tests );
Zend_Db_Table_Abstract::setDefaultAdapter( $db );