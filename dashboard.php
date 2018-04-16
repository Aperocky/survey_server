<?php
require_once('_php/userinit.php');
$username = $_SESSION['username'];
unset($_SESSION['survey']);
unset($_SESSION['survey_long']);
// Query all survey under the username
$sql = "SELECT * FROM survey WHERE username = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $username);
if($stmt->execute()){
  $result = $stmt->get_result();
  // echo var_dump($result);
} else {
  exit("Problem detected");
}
$questions = 0;
$replies = 0;
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
      <nav class="navbar navbar-inverse">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="#">HAL Surveyor</a>
          </div>
          <ul class="nav navbar-nav">
            <li class="active"><a href="welcome.php">Home</a></li>
            <li class="active"><a href="#">DASHBOARD : <?php echo htmlspecialchars($username); ?></a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="log_out active">
              <a href="welcome.php?logout=1">Log Out</a>
            </li>
          </ul>
        </div>
      </nav>
      <div class="dash">
        <h3 class="text-center"> List of your surveys </h3>
        <?php while($row = $result->fetch_array()){
          $numquestion = count_row($row['survey_name']);
          $survey_result = $row['survey_name'] . "_result";
          $submissions = count_row($survey_result); ?>
          <div class="panel">
            <div class="panel-body">
              <div class="container">
                <div class="row">
                  <div class="col-xs-3">
                    <h5><b><?php echo $row['long_name'] ?></b></h5>
                  </div>
                  <div class="col-xs-2">
                    <h5 <?php echo ($row['status'])?"style='color:green'":"style='color:red'" ?>><?php echo ($row['status'])? "Completed": "In Progress"; ?></h5>
                  </div>
                  <div class="col-xs-2">
                    <h5>
                      <?php echo '#Question: ' . $numquestion; ?>
                    </h5>
                  </div>
                  <div class="col-xs-3">
                    <h5>
                      <?php echo '#Submissions: ' . $submissions;?>
                    </h5>
                  </div>
                  <div class="col-xs-1">
                    <a href="buildquestions.php?survey=<?php echo $row['survey_name']; ?>" class="btn btn-default">Edit</a>
                  </div>
                  <div class="col-xs-1">
                    <a href="display.php?survey=<?php echo $row['survey_name']; ?>" class="btn btn-default">View</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
      <footer class="page-footer">
        <div class="emptyspace"></div>
        <div class="row">
          <div class="footer-copyright text-center col-xs-6">
              © 2018 HAL:
              <a href="https://hal.pratt.duke.edu"> HAL Lab </a>
          </div>
          <div class="footer-copyright text-center col-xs-6">
              Creator:
              <a href="https://www.aperocky.com"> Rocky Li </a>
          </div>
        </div>
      </footer>
    </div>
</body>
</html>
