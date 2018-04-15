<?php

class Question{

  // Question parameter
  public $types = array(1=>"Multiple Choices", 2=>"Fill in blank");
  public $survey;
  public $question;
  public $type;
  public $num;
  public $index;
  public $choicelist;

  // Construct a Question object per request page
  public function __construct($survey, $index, $type, $question, $num = 0, $mc){
    $this->survey = $survey;
    $this->index = $index;
    $this->question = $question;
    $this->type = $type;
    if($this->type != 2){
      $this->num = $num;
      $this->choicelist = $mc;
    } else {
      $this->num = 0;
    }
  }

  // Save multiple choice questions
  // Please note, all multiple choices come in list length of 6! Distinguish only with numquestion.
  // public function multichoice($choicelist){
  //   $this->choicelist = $choicelist;
  // }

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
    global $survey;
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
      $sql .= " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = $db->prepare($sql);
      $mc = $this->choicelist;
      $stmt->bind_param("siissssss", $this->question, $this->type, $this->num, $mc[0], $mc[1], $mc[2], $mc[3], $mc[4], $mc[5]);
      $stmt->execute();
    }
  }

  public function update(){
    global $db;
    global $survey;
    $sql = "UPDATE " . $db->real_escape_string($survey);
    $sql .= " SET question = ?, type = ?, numquestion = ?, mc1 = ?, mc2 = ?, mc3 = ?, mc4 = ?, mc5 = ?, mc6 = ? WHERE id = ?";
    echo $sql;
    $stmt = $db->prepare($sql);
    $mc = $this->choicelist;
    $stmt->bind_param("siissssssi", $this->question, $this->type, $this->num, $mc[0], $mc[1], $mc[2], $mc[3], $mc[4], $mc[5], $this->index);
    $stmt->execute();
  }
}



?>
