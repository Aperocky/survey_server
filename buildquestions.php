<?php
require_once('_php/userinit.php');
$new_question = False;
// This page only handles get request
if(!is_get_request()){
  redirect_to('buildsurvey.php');
}
// If question index not set, set it to 1
if(isset($_GET['question'])){
  $question_index = $_GET['question'];
} else {
  $question_index = 1;
}
// If survey not set, redirect to build survey page.
if(isset($_SESSION['survey'])){
  $survey = $_SESSION['survey'];
} else {
  redirect_to('buildsurvey.php');
}
$sql = "SELECT * FROM " . $db->real_escape_string($survey) . " WHERE id = ?";
$stmt = $db->prepare($sql);
if(!$stmt){
  exit('Something is wrong, call Rocky to fix this.');
}
$stmt->bind_param('i', $question_index);
if($stmt->execute()){
  // echo var_dump($stmt);
  $result = $stmt->get_result();
  // echo var_dump($result);
  if($result->num_rows<1){
    $new_question = True;
    // echo 'success';
  } else {
    $question = $result->fetch_assoc();
    echo var_dump($question);
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
    <!-- This is the navbar -->
  <div class="wrapper">
      <nav class="navbar navbar-inverse">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="#">HAL Surveyor</a>
          </div>
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Build Question <?php echo $question_index; ?></a></li>
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

    <div class="question_entry">
      <form class="submit" action="submitquestion.php" method="post">
        <div class="form-group">
          <label>Type in your Question</label>
          <textarea class="form-control" rows="6" name="description" placeholder="Question here"></textarea>
        </div>
        <div class="form-group" id="qtypediv">
          <div class="panel">
            <div class="panel-body">
              <p>
                Select type of question you want the current question to be.
              </p>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="qtype" id="qtypemulti" value="1" checked>
                <label class="form-check-label" for="qtypemulti">Multiple Choices</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="qtype" id="qtypefill" value="2">
                <label class="form-check-label" for="qtypefill">Fill in the Blank</label>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group multichoice">
          <div class="panel">
            <div class="panel-body">
              <label for="numquestion">Select number of multiple choice questions</label>
              <select class="form-control" id="numquestion">
                <option selected="selected" value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
              </select>
            </div>
          </div>
        </div>
        <div class="form-group multichoice">
          <div class="panel">
            <div class="panel-body">
              <p>
                Insert choices for the multiple choice question.
              </p>
              <div class="input1">
                <label for="mcinput1">Choice A</label>
                <input class="form-control" type="text" id="mcinput1"/>
                <br />
              </div>
              <div class="input2">
                <label for="mcinput2">Choice B</label>
                <input class="form-control" type="text" id="mcinput2"/>
                <br />
              </div>
              <div class="input3">
                <label for="mcinput3">Choice C</label>
                <input class="form-control" type="text" id="mcinput3"/>
                <br />
              </div>
              <div class="input4">
                <label for="mcinput4">Choice D</label>
                <input class="form-control" type="text" id="mcinput4"/>
                <br />
              </div>
              <div class="input5">
                <label for="mcinput5">Choice E</label>
                <input class="form-control" type="text" id="mcinput5"/>
                <br />
              </div>
              <div class="input6">
                <label for="mcinput6">Choice F</label>
                <input class="form-control" type="text" id="mcinput6"/>
                <br />
              </div>
            </div>
          </div>
        </div>
        <div>
          <button type="submit" class="btn btn-primary">Submit</button>
          <input type="reset" class="btn btn-default" value="Reset">
        </div>
      </form>
    </div>


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
