<?php
require_once('_php/userinit.php');
require_once('_php/survey_class.php');
session_start();
if(!is_post_request()){
  redirect_to('welcome.php');
} else {
  if(!isset($_POST['description'])){
    redirect_to('welcome.php');
  }
}
echo var_dump($_POST);
$survey = $_SESSION['survey'];
$survey_long = $_SESSION['survey_long'];
try{
  $question = $_POST['description'];
  $type = $_POST['qtype'];
  $numquestion = $_POST['numquestion'];
  $mc1 = $_POST['mc1'];
  $mc2 = $_POST['mc2'];
  $mc3 = $_POST['mc3'];
  $mc4 = $_POST['mc4'];
  $mc5 = $_POST['mc5'];
  $mc6 = $_POST['mc6'];
  $index = $_POST['index'];
  echo $type;
} catch(Exception $e){
  echo ($e->getMessage());
}
$mc = array($mc1, $mc2, $mc3, $mc4, $mc5, $mc6);
$curr_question = new Question($survey, $index, $type, $question, $numquestion, $mc);
if($_POST['edit']==1){
  $curr_question->update();
} else {
  $curr_question->store();
}
// $curr_question->store();
$nextindex = $index + 1;
create_result_table();
if(!isset($_POST['final'])){
  redirect_to("buildquestions.php?question=$nextindex");
} else {
  $sql = "UPDATE survey";
  $sql .= " SET status=1 WHERE survey_name=?";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("s", $survey);
  $stmt->execute();
  create_result_table();
  redirect_to("dashboard.php");
}

?>
