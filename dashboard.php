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
  echo var_dump($result);
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
    <title>Build New Survey</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="shortcut icon" type="image/x-icon" href="hal.ico" />
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
            <li class="active"><a href="#">DASHBOARD</a></li>
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
      <div class="dash">
        <?php while($row = $result->fetch_array()){ ?>
          <div class="panel">
            <div class="panel-body">
              <div class="container">
                <div class="row">
                  <div class="col-xs-3">
                    <h5><?php echo $row['long_name'] ?></h5>
                  </div>
                  <div class="col-xs-2">
                      <?php echo ($row['status'])? "Completed": "In Progress"; ?>
                  </div>
                  <div class="col-xs-2">
                    <p>
                      <?php echo '#Question: '; ?>
                    </p>
                  </div>
                  <div class="col-xs-3">
                    <p>
                      <?php echo '#Submissions: ';?>
                    </p>
                  </div>
                  <div class="col-xs-1">
                    <a href="buildquestions.php?survey=<?php echo $row['survey_name']; ?>" class="btn btn-default">Edit</a>
                  </div>
                  <div class="col-xs-1">
                    <a href="display?survey=<?php echo $row['survey_name']; ?>" class="btn btn-default">View</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
