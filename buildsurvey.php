<?php
require_once('_php/userinit.php');
$survey = $describe = "";
$survey_err = $describe_err = "";
if(is_post_request()){
  // Verify if survey name has been taken
  if(empty(trim($_POST["survey_name"]))){
    $survey_err = "Please enter a survey name.";
  } elseif(strlen(trim($_POST["survey_name"])) < 10) {
    $survey_err = "Please enter a meaningful name longer than 10 letters.";
  } else {
    $survey_long = trim($_POST["survey_name"]);
    $survey_name = preg_replace('/\s+/', '', $survey_long);
    $sql = "SELECT id FROM survey WHERE survey_name = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $survey_name);
    if($stmt->execute()){
      $stmt->store_result();
      if($stmt->num_rows() == 1){
        $survey_err = "This survey name already exists.";
      } else {
        $survey = $survey_name;
      }
    } else {
      echo "Something is wrong, please call Rocky to fix this.";
    }
    $stmt->close();
  }

  // Verify the description / purpose
  if(empty(trim($_POST["description"]))){
    $describe_err = "Please describe the purpose of this survey";
  } elseif(strlen(trim($_POST["description"])) < 30){
    $describe_err = "Please give some more detail.";
  } else {
    $describe = trim($_POST["description"]);
  }

  // Set up base survey database for this particular survey, if this is the first question in the list.
  // If type INT == 0, this signify this question should no longer be used.
  function setup(){
    global $db;
    global $survey;
    $sql = "CREATE TABLE " . $db->real_escape_string($survey) . " (";
    $sql .= "id INT NOT NULL AUTO_INCREMENT, ";
    $sql .= "question TEXT, ";
    $sql .= "type INT, ";
    $sql .= "numquestion INT, ";
    $sql .= "mc1 VARCHAR(255), ";
    $sql .= "mc2 VARCHAR(255), ";
    $sql .= "mc3 VARCHAR(255), ";
    $sql .= "mc4 VARCHAR(255), ";
    $sql .= "mc5 VARCHAR(255), ";
    $sql .= "mc6 VARCHAR(255), ";
    $sql .= "PRIMARY KEY (id)) ";
    $result = $db->query($sql);
    return $result;
  }

  # Ready to enter into database
  if(empty($survey_err) && empty($describe_err)){
    $sql = "INSERT INTO survey (survey_name, username, description, long_name) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssss", $survey, $username, $describe, $survey_long);
    if($stmt->execute()){
      setup();
      $_SESSION['survey'] = $survey;
      $_SESSION['survey_long'] = $survey_long;
      redirect_to('buildquestions.php?question=1');
    } else {
      echo "Something is wrong, please call Rocky to fix this.";
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Build New Survey</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="shortcut icon" type="image/x-icon" href="hal.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            <li class="active"><a href="welcome.php">Home</a></li>
            <li class="active"><a href="#">Survey Builder</a></li>
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
      <h2 class="text-center"> Build New Survey </h2>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($survey_err)) ? 'has-error' : '';?>">
          <label>New Survey Name</label>
          <input type="text" class="form-control" name="survey_name" placeholder="Enter name of the survey">
          <span class="help-block"><?php echo $survey_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($describe_err)) ? 'has-error' : '';?>">
          <label>Purpose</label>
          <textarea class="form-control" rows="6" name="description" placeholder="Describe the purpose of this survey"></textarea>
          <span class="help-block"><?php echo $describe_err; ?></span>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>

      <!-- Footer comes next -->
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
