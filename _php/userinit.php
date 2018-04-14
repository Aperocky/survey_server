<?php
require_once('_php/init.php');
session_start();
if(isset($_SESSION['username'])){
  // echo var_dump($_SESSION);
  // echo 'userhere';
  $username = $_SESSION['username'];
} else {
  // echo 'whatdafuck';
  redirect_to('login.php');
}
if(isset($_GET['logout'])){
  $_SESSION = array();
  session_destroy();
  redirect_to('login.php');
}
?>
