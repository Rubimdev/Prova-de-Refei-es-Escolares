<?php 
header('Content-Type: text/html; charset=utf-8');
require_once "config.php";
require_once "classes/Medoo.php";

use Medoo\Medoo;

$bd = new Medoo([
	'type' => 'mariadb',
	'host' => $conf["bd_host"],
	'database' => $conf["bd_name"],
	'username' => $conf["bd_user"],
	'password' => $conf["bd_pass"],
]);