<?php
require_once('_php/userinit.php');
require_once('_php/survey_class.php');

if(!is_post_request()){
  redirect_to('dashboard.php');
}
$survey = $_SESSION['survey'];
$survey_result = $survey . "_result";

echo var_dump($_POST);
$choicelist = array();
foreach($_POST as $key => $value){
  // Only process choices
  if (strstr($key, 'choice')){
    $newkey = str_ireplace("choice", "", $key);
    $newkey = "Q" . $newkey;
    if(is_array($value)){
      $curr_result = "";
      foreach($value as $choices){
        $curr_result .= $choices;
      }
    } else {
      $curr_result = $value;
    }
    $choicelist[$newkey] = $curr_result;
  }
}
echo var_dump($choicelist);
$sql = "INSERT INTO " . $db->real_escape_string($survey_result) . " (";
$indexstr = $answerstr = "";
foreach($choicelist as $qindex => $qanswer){
  $indexstr .= $qindex . ", ";
  $answerstr .= $qanswer . ", ";
}
echo $indexstr;
$indexstr = rtrim($indexstr, ",");
echo $indexstr;
$answerstr = rtrim($answerstr, ",");
$sql .= $indexstr . ") VALUES (";
$sql .= $answerstr . ")";
echo $sql;

?>
