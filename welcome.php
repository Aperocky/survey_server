<?php
// require_once('_php/init.php');
// session_start();
// if(isset($_SESSION['username'])){
//   // echo var_dump($_SESSION);
//   // echo 'userhere';
//   $username = $_SESSION['username'];
// } else {
//   // echo 'whatdafuck';
//   redirect_to('login.php');
// }
// if(isset($_GET['logout'])){
//   $_SESSION = array();
//   session_destroy();
//   redirect_to('login.php');
// }
require_once('_php/userinit.php')
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 960px; padding: 20px; margin: auto; background-color: #eee;}
        .container{ width: 900px; margin: auto;}
        .col-centered{ float: none; margin: 0 auto;}
        .emptyspace{ height: 100px;}
    </style>
</head>
<body>
    <!-- This is the navbar -->
    <div class="wrapper">
      <nav class="navbar navbar-inverse">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="#">HAL Surveyor</a>
          </div>
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="navbar-text">
              Logged in as <?php echo htmlspecialchars($username); ?>
            </li>
            <li class="log_out active">
              <a href="welcome.php?logout=1">Log Out</a>
            </li>
          </ul>
        </div>
      </nav>
      <!-- Content here -->
      <div class="container">
        <div class="row">
          <div class="col-xs-6">
            <div class="panel panel-default">
              <div class="panel-body">
                <h4 class="panel-heading"> Build Survey </h4>
                <div class="panel-body">
                  <img src='_image/online-survey.jpg' width=100%/>
                </div>
                <p class="panel-body">
                  Build your own survey with HAL surveyor! It is completely free and you will have access to ALL data gathered. Select from multiple choice, textbox and have pictures uploaded to accompany your questions.
                </p>
                <a class="btn btn-primary" href="buildsurvey.php"> Build </a>
              </div>
            </div>
          </div>
          <div class="col-xs-6">
            <div class="panel panel-default">
              <div class="panel-body">
                <h4 class="panel-heading"> Manage Surveys </h4>
                <div class="panel-body">
                  <img src='_image/data.jpg' width=100%/>
                </div>
                <p class="panel-body">
                  Manage all of your survey in a dashboard! Manage the health and potentially edit the survey questions. Download all data in csv form.
                </p>
                <a class="btn btn-primary" href="dashboard.php"> Manage </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer comes next -->
      <footer class="page-footer">
        <div class="emptyspace">

        </div>
        <div class="footer-copyright text-center">
            © 2018 HAL:
            <a href="https://hal.pratt.duke.edu"> HAL Lab </a>
        </div>
        <div class="footer-copyright text-center">
            Creator:
            <a href="https://www.aperocky.com"> Rocky Li </a>
        </div>
      </footer>
    </div>
</body>
</html>
