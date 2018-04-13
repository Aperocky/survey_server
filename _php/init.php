<?php

  require_once('credential.php');
  require_once('database.php');

  define("PHP_PATH", dirname(__FILE__));
  define("PROJECT_PATH", dirname(PHP_PATH));
  define("RESOURCES_PATH", PROJECT_PATH . '/Resources');
  define("PARTS_PATH", PHP_PATH . '/parts');
  define("WEB_ROOT", 'http://' . $_SERVER['HTTP_HOST']);

  require_once('functions.php');
  $db = db_connect();

?>
