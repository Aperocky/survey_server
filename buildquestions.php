<?php
require_once('_php/userinit.php');
// Initial Values
$edit = False;
$question = "";
$type = "1";
$numquestion = "2";
$mc1 = $mc2 = $mc3 = $mc4 = $mc5 = $mc6 = "";

$new_question = False;
// This page only handles get request
if(!is_get_request()){
  redirect_to('buildsurvey.php');
}
if(isset($_GET['survey'])){
  $survey = $_GET['survey'];
  set_survey($survey);
}
// If survey not set, redirect to build survey page.
if(isset($_SESSION['survey'])){
  $survey = $_SESSION['survey'];
  $survey_long = $_SESSION['survey_long'];
} else {
  redirect_to('buildsurvey.php');
}
// Total number of questions.
$total_question = checklines();
// If question index not set, set it to 1
if(isset($_GET['question'])){
  $question_index = $_GET['question'];
  if($question_index == 0){
    $question_index = 1;
  } else if ($question_index > $total_question + 1){
    $question_index = $total_question + 1;
  }
} else {
  $question_index = 1;
}
if(isset($_GET['edit'])){
  $edit = True;
}
// Find out if this question index has an existing question, if so, edit instead of new.
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
    $new_question = False;
    $question_set = $result->fetch_assoc();
    // echo var_dump($question_set);
  }
}
// If not new question, set up the prior questions.
if(!$new_question){
  try{
    $question = $question_set['question'];
    $type = $question_set['type'];
    $numquestion = $question_set['numquestion'];
    $mc1 = $question_set['mc1'];
    $mc2 = $question_set['mc2'];
    $mc3 = $question_set['mc3'];
    $mc4 = $question_set['mc4'];
    $mc5 = $question_set['mc5'];
    $mc6 = $question_set['mc6'];
  } catch (Exception $e){
    echo $e->getMessage();
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
            <li class="active"><a href="welcome.php">Home</a></li>
            <li class="active"><a href="#"><?php echo $survey_long; ?></a></li>
            <li class="active"><a href="#">Question <?php echo $question_index; ?></a></li>
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

    <!-- Have a button to go to the last page -->
    <div class="container">
      <div class="row">
        <div class="col-xs-3">
          <div class="redirectbutton">
            <a class="btn btn-primary" href="<?php echo 'buildquestions.php?question=' . ($question_index - 1); ?>">Edit last question</a>
          </div>
        </div>
        <?php if(!$new_question){ ?>
          <div class="col-xs-3">
            <div class="redirectbutton">
              <a class="btn btn-primary" href="<?php echo 'buildquestions.php?question=' . ($question_index + 1); ?>">Go to next question</a>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
    <br />
    <div class="question_entry">
      <form class="submit" action="submitquestion.php" method="post">
        <div class="form-group">
          <div class="panel">
            <div class="panel-body">
              <label>Type in your Question</label>
              <textarea class="form-control" rows="6" name="description"><?php echo $question; ?></textarea>
            </div>
          </div>
        </div>
        <div class="form-group" id="qtypediv">
          <div class="panel">
            <div class="panel-body">
              <p>
                Select type of question you want the current question to be.
              </p>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="qtype" id="qtypemulti" value="1" <?php echo ($type=="1")?'checked':''; ?>>
                <label class="form-check-label" for="qtypemulti">Multiple Choice</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="qtype" id="qtypefill" value="2" <?php echo ($type=="2")?'checked':''; ?>>
                <label class="form-check-label" for="qtypefill">Fill in the Blank</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="qtype" id="qtypesel" value="3" <?php echo ($type=="3")?'checked':''; ?>>
                <label class="form-check-label" for="qtypeselect">Select all that apply</label>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group multichoice" <?php echo ($type=='2')?"style='display:none'":''; ?>>
          <div class="panel">
            <div class="panel-body">
              <label for="numquestion">Select number of options</label>
              <select class="form-control" name="numquestion" id="numquestion">
                <option value="2" <?php echo ($numquestion == '2')? 'selected' : ''; ?>>2</option>
                <option value="3" <?php echo ($numquestion == '3')? 'selected' : ''; ?>>3</option>
                <option value="4" <?php echo ($numquestion == '4')? 'selected' : ''; ?>>4</option>
                <option value="5" <?php echo ($numquestion == '5')? 'selected' : ''; ?>>5</option>
                <option value="6" <?php echo ($numquestion == '6')? 'selected' : ''; ?>>6</option>
              </select>
            </div>
          </div>
        </div>
        <div class="form-group multichoice" <?php echo ($type=='2')?"style='display:none'":''; ?>>
          <div class="panel">
            <div class="panel-body">
              <p>
                Insert choices description for each of the options.
              </p>
              <div class="input1">
                <label for="mcinput1">Choice A</label>
                <input class="form-control" name="mc1" type="text" id="mcinput1" value="<?php echo $mc1; ?>"/>
                <br />
              </div>
              <div class="input2">
                <label for="mcinput2">Choice B</label>
                <input class="form-control" name="mc2" type="text" id="mcinput2" value="<?php echo $mc2; ?>"/>
                <br />
              </div>
              <div class="input3" <?php echo ($numquestion < 3)?"style='display:none;'" :''; ?>>
                <label for="mcinput3">Choice C</label>
                <input class="form-control" name="mc3" type="text" id="mcinput3" value="<?php echo $mc3; ?>"/>
                <br />
              </div>
              <div class="input4" <?php echo ($numquestion < 4)?"style='display:none;'" :''; ?>>
                <label for="mcinput4">Choice D</label>
                <input class="form-control" name="mc4" type="text" id="mcinput4" value="<?php echo $mc4; ?>"/>
                <br />
              </div>
              <div class="input5" <?php echo ($numquestion < 5)?"style='display:none;'" :''; ?>>
                <label for="mcinput5">Choice E</label>
                <input class="form-control" name="mc5" type="text" id="mcinput5" value="<?php echo $mc5; ?>"/>
                <br />
              </div>
              <div class="input6" <?php echo ($numquestion < 6)?"style='display:none;'" :''; ?>>
                <label for="mcinput6">Choice F</label>
                <input class="form-control" name="mc6" type="text" id="mcinput6" value="<?php echo $mc6; ?>"/>
                <br />
              </div>
            </div>
          </div>
          <input type="hidden" type="text" name="index" value="<?php echo $question_index; ?>">
        </div>
        <div>
          <button type="submit" class="btn btn-primary" <?php echo ($new_question)? '':"style='display:none;'"; ?>>Submit</button>
          <input type="submit" class="btn btn-primary" name="edit" value="Edit" <?php echo ($new_question)? "style='display:none;'":''; ?>/>
          <button type="reset" class="btn btn-default" value="Reset">Reset</button>
          <input type="submit" class="btn btn-primary" name="final" value="Submit Form">
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
