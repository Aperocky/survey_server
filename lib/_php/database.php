<?php
// Function to connect and disconnect with database.

  function db_connect() {
    $connection = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
    if (mysqli_connect_errno()) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
    }
    return $connection;
  }

  function db_close($connection) {
    if(isset($connection)) {
      mysqli_close($connection);
    }
  }

  function confirm_result_set($result_set) {
    if (!$result_set) {
      exit("Database query failed.");
    }
  }

?>
