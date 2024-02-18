<?php require_once 'vendor/autoload.php';
$db = new MysqliDb([
	'host' => 'localhost',
	'username' => 'vocabulary',
	'password' => '',
	'db'=> 'vocabulary',
	'port' => 3306,
	'prefix' => '',
	'charset' => 'utf8'
]);