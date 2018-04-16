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
// Check how many rows the database has.
function checklines(){
  global $db;
  global $survey;
  $sql = "SELECT count(1) FROM " . $db->real_escape_string($survey);
  $res = $db->query($sql);
  $res = $res->fetch_assoc();
  $lines = $res['count(1)'];
  return $lines;
}
function set_survey($survey){
  global $db;
  $_SESSION['survey'] = $survey;
  $sql = "SELECT long_name FROM survey WHERE survey_name=?";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("s", $survey);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $survey_long = $row['long_name'];
  $_SESSION['survey_long'] = $survey_long;
}
?>
