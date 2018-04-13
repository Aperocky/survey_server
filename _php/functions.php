<?php

// ------------------ DATABASE QUERY FUNCTIONS ----------------------

  // This function insert a regular entree into time_stamps table.
  function insert_subject($subject, $object) {
    global $db;

    $sql = "INSERT INTO ". CURR_TABLE;
    $sql .= " (time, device, payload, picture_location) ";
    $sql .= "VALUES (";
    $sql .= "'" . $subject['time'] . "',";
    $sql .= "'" . $subject['device'] . "',";
    $sql .= "'" . $subject['payload'] . "'";
    if ($object !== 0){
      $sql .= ",'". mysqli_real_escape_string($db, $object) ."'";
    } else {
      $sql .= ",'NULL'";
    }
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // if ($object !== 0){
    //   $sql = "INSERT INTO ". CURR_TABLE;
    //   $sql .= " (picture) ";
    //   $sql .= "VALUES (";
    //   $sql .= "'" . mysqli_real_escape_string($db, $object) . "'";
    //   $sql .= ")";
    //   $result = mysqli_query($db, $sql);
    // }
    // return $sql;
    if($result) {
      return $sql;
    } else {
      $error = mysqli_error($db);
      db_close($db);
      return $error;
    }
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
