<?php
require_once('_php/userinit.php');
if(isset($_GET['survey'])){
  $survey = $_GET['survey'];
  set_survey($survey);
}
$survey_result = $survey . "_result";
// Output survey files into tmp folder as csv.
$sql = "SELECT * FROM " . $db->real_escape_string($survey_result);
// echo $sql;
if(!$result = $db->query($sql)){
  exit("Something is wrong, please call Rocky for fix.");
}
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="export.csv"');
header('Cache-Control: max-age=0');
// echo var_dump($result);
foreach($result->fetch_fields() as $field){
  echo $field->name . ", ";
}
echo "\n";
while($row = $result->fetch_assoc()){
  foreach($row as $key => $value){
    echo $value . ", ";
  }
  echo "\n";
}
?>
