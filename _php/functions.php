<?php

// ------------------ DATABASE QUERY FUNCTIONS ----------------------

  // This function finds the number of rows in a databse.
  function count_row($table){
    global $db;
    $sql = "SELECT COUNT(1) FROM " . $db->real_escape_string($table);
    $res = $db->query($sql);
    $rows = $res->fetch_array();
    $total = $rows[0];
    return $total;
  }

  // Get the last row of the database
  function get_last_element(){
    global $db;
    $sql = "SELECT * FROM " . CURR_TABLE;
    $sql .= " ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

// ------------------------- OTHER FUNCTIONS ------------------------

  // Redirection
  function redirect_to($location) {
    header("Location: " . $location);
    exit;
  }

  // Is post request
  function is_post_request(){
    return $_SERVER['REQUEST_METHOD'] == 'POST';
  }

  // Is GET request
  function is_get_request(){
    return $_SERVER['REQUEST_METHOD'] == 'GET';
  }

  function u($string="") {
  return urlencode($string);
  }

  function h($string="") {
    return htmlspecialchars($string);
  }

// ------------------------- QUERY FUNCTIONS ------------------------

  function find_records() {
    global $db;
    $sql = "SELECT * FROM time_stamps ";
    $sql .= "ORDER BY id ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

?>
