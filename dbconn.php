<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* MySQL Main configuration page
* Include that file in all the files that will uses PAS objects.
*/
//DB setup using Doctrine
use Doctrine\Common\ClassLoader;
require dirname(__FILE__).'/Doctrine/Doctrine/Common/ClassLoader.php';
$classLoader = new ClassLoader('Doctrine', dirname(__FILE__).'/Doctrine');
$classLoader->register();
$config = new \Doctrine\DBAL\Configuration();

$connectionParams = array(
    'dbname' => 'sqcrm',
    'user' => 'sqcrmuser',
    'password' => '5ql6rm',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
    'charset' => 'utf8',
    'driverOptions' => array(
		1002=>'SET NAMES utf8'
    )
);

$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
$GLOBALS['conn'] = $conn ;
?>