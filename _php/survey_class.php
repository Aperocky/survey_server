<?php

class Question{

  // Question parameter
  public $types = array(1=>"Multiple Choices", 2=>"Fill in blank");
  public $survey;
  public $question;
  public $type;
  public $num;
  public $choicelist;
  public $index;

  // Construct a Question object per request page
  public function __construct($survey, $index, $type, $question, $num = 0){
    $this->survey = $survey;
    $this->index = $index;
    $this->question = $question;
    $this->type = $type;
    if($this->type === 1){
      $this->num = $num;
      $this->$choicelist = array();
    } else {
      $this->num = 0;
    }
    $this->setup();
  }

  // Save multiple choice questions
  // Please note, all multiple choices come in list length of 6! Distinguish only with numquestion.
  public function multichoice($choicelist){
    $this->choicelist = $choicelist;
  }

  // Set up base survey database for this particular survey, if this is the first question in the list.
  // Migrated to buildsurvey
  // function setup(){
  //   global $db;
  //   $sql = "CREATE TABLE " . $db->real_escape_string($survey) . " (";
  //   $sql .= "id INT NOT NULL AUTO_INCREMENT, ";
  //   $sql .= "question TEXT, ";
  //   $sql .= "type INT, ";
  //   $sql .= "numquestion INT, ";
  //   $sql .= "mc1 VARCHAR(255), ";
  //   $sql .= "mc2 VARCHAR(255), ";
  //   $sql .= "mc3 VARCHAR(255), ";
  //   $sql .= "mc4 VARCHAR(255), ";
  //   $sql .= "mc5 VARCHAR(255), ";
  //   $sql .= "mc6 VARCHAR(255), ";
  //   $sql .= "PRIMARY KEY (id)) ";
  //   $result = $db->query($sql);
  //   return $result;
  // }

  // Store the question in a safe place
  public function store(){
    global $db;
    if($this->type == 2){
      $sql = "INSERT INTO " . $db->real_escape_string($survey) . " (";
      $sql .= "question, type, numquestion) VALUES (?, 2, 0)";
      echo $sql;
      $stmt = $db->prepare($sql);
      $stmt->bind_param("s", $this->question);
      $stmt->execute();
    } else {
      $sql = "INSERT INTO " . $db->real_escape_string($survey) . " (";
      $sql .= "question, type, numquestion, mc1, mc2, mc3, mc4, mc5, mc6)";
      $sql .= " VALUES (?, 1, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = $db->prepare($sql);
      $mc = $this->choicelist;
      $stmt->bind_param("sissssss", $this->question, $this->num, $mc[0], $mc[1], $mc[2], $mc[3], $mc[4], $mc[5]);
      $stmt->execute();
    }
  }
}



?>
