<?php
require_once('_php/userinit.php');
require_once('_php/survey_class.php');
// Only takes get request
if(!is_get_request()){
  redirect_to('dashboard.php');
}
// Set current survey
if(isset($_GET['survey'])){
  $survey = $_GET['survey'];
  set_survey($survey);
} else {
  redirect_to('dashboard.php');
}
$sql = "SELECT * FROM " . $db->real_escape_string($survey);
$result = $db->query($sql);
if(!$result){
  exit("something went wrong, please contact Rocky.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="shortcut icon" type="image/x-icon" href="hal.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="_script/buildq.js"></script>
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 960px; padding: 20px; margin: auto; background-color: #eee;}
        .container{ width: 900px; margin: auto;}
        .col-centered{ float: none; margin: 0 auto;}
        .emptyspace{ height: 100px;}
    </style>
</head>
<body>
  <div class="wrapper">
      <!-- This is the header -->
      <nav class="navbar navbar-inverse">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="#">HAL Surveyor</a>
          </div>
          <ul class="nav navbar-nav">
            <li class="active"><a href="welcome.php">Home</a></li>
            <li class="active"><a href="#"><?php echo $_SESSION['survey_long']; ?></a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="log_out active">
              <a href="welcome.php?logout=1">Log Out</a>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Main body of the survey -->
      <form action="submitsurvey.php" method="post">
        <?php while($row = $result->fetch_assoc()){
          $curr_question = qfromfetch($row);
          $curr_question->display();
        }
        ?>
        <div class="form-group">
          <button type="submit" class="btn btn-default"> Submit Survey</button>
        </div>
      </form>
