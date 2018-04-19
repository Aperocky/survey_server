<?php
// Build a Question object from a single line of mysql query.
function qfromfetch($row){
  $mc = array($row['mc1'], $row['mc2'], $row['mc3'], $row['mc4'], $row['mc5'], $row['mc6']);
  $continue = $row['cont'];
  $deleted = $row['status'];
  $curr_question = new Question($_SESSION['survey'], $row['id'], $row['type'], $row['question'], $row['numquestion'], $mc, $continue, $deleted);
  return $curr_question;
}

// Create a result table containing the rows
function create_result_table(){
  global $db;
  global $survey;
  $survey_result = $survey . "_result";
  // If table is created, drop it.
  $sql = "SHOW TABLES LIKE '" . $db->real_escape_string($survey_result) . "'";
  if($result=$db->query($sql)){
    echo var_dump($result);
    if($result->num_rows == 1){
      echo "detect and drop";
      $sql = "DROP TABLE " . $db->real_escape_string($survey_result);
      $result = $db->query($sql);
      if(!$result){
        echo("Something is wrong, please call for help");
      }
    } else {
      echo "not detected";
    }
  }
  // Creates table.
  $sql = "CREATE TABLE " . $db->real_escape_string($survey_result) . " (";
  $sql .= "id INT NOT NULL AUTO_INCREMENT, ";
  $total_question = checklines();
  for($x = 1; $x <= $total_question; $x++){
    $sql .= "Q" . strval($x) . " VARCHAR(255), ";
  }
  $sql .= "PRIMARY KEY (id)) ";
  $result = $db->query($sql);
  if(!$result){
    echo("Something is wrong, please call Rocky for help");
  }
}

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
  public function __construct($survey, $index, $type, $question, $num = 0, $mc, $continue = 0, $deleted = 0){
    $this->survey = $survey;
    $this->index = $index;
    $this->question = $question;
    $this->type = $type;
    $this->continue = $continue;
    $this->deleted = $deleted;
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
      $sql .= "question, type, numquestion, mc1, mc2, mc3, mc4, mc5, mc6, cont, status)";
      $sql .= " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = $db->prepare($sql);
      $mc = $this->choicelist;
      $stmt->bind_param("siissssssii", $this->question, $this->type, $this->num, $mc[0], $mc[1], $mc[2], $mc[3], $mc[4], $mc[5], $continue, $deleted);
      $stmt->execute();
    }
  }

  public function update(){
    global $db;
    global $survey;
    $sql = "UPDATE " . $db->real_escape_string($survey);
    $sql .= " SET question = ?, type = ?, numquestion = ?, mc1 = ?, mc2 = ?, mc3 = ?, mc4 = ?, mc5 = ?, mc6 = ?, cont = ?, status = ? WHERE id = ?";
    echo $sql;
    $stmt = $db->prepare($sql);
    $mc = $this->choicelist;
    $stmt->bind_param("siissssssiii", $this->question, $this->type, $this->num, $mc[0], $mc[1], $mc[2], $mc[3], $mc[4], $mc[5], $this->continue, $this->deleted, $this->index);
    $stmt->execute();
  }

  public function display($index){
    if($this->deleted == 1){
      return $index;
    }
    if($this->index == 1){ ?>
    <div class="panel panel-default">
    <?php }
    if($this->continue == 0){ ?>
    </div>
    <div class="panel panel-default">
    <?php } ?>
      <div class="panel-body">
        <?php if($this->continue == 0){ ?>
        <p>
          <?php echo $index . ". " . $this->question; ?>
        </p>
      <?php $index += 1; } ?>
        <?php if($this->type == 3){ ?>
          <div class="form-group">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="<?php echo $this->index; ?>choice[]" value="A">
              <label class="form-check-label"><?php echo $this->choicelist[0]; ?></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="<?php echo $this->index; ?>choice[]" value="B">
              <label class="form-check-label"><?php echo $this->choicelist[1]; ?></label>
            </div>
            <div class="form-check form-check-inline" <?php echo ($this->num < 3)? "style='display:none;'":''; ?>>
              <input class="form-check-input" type="checkbox" name="<?php echo $this->index; ?>choice[]" value="C">
              <label class="form-check-label"><?php echo $this->choicelist[2]; ?></label>
            </div>
            <div class="form-check form-check-inline" <?php echo ($this->num < 4)? "style='display:none;'":''; ?>>
              <input class="form-check-input" type="checkbox" name="<?php echo $this->index; ?>choice[]" value="D">
              <label class="form-check-label"><?php echo $this->choicelist[3]; ?></label>
            </div>
            <div class="form-check form-check-inline" <?php echo ($this->num < 5)? "style='display:none;'":''; ?>>
              <input class="form-check-input" type="checkbox" name="<?php echo $this->index; ?>choice[]" value="E">
              <label class="form-check-label"><?php echo $this->choicelist[4]; ?></label>
            </div>
            <div class="form-check form-check-inline" <?php echo ($this->num < 6)? "style='display:none;'":''; ?>>
              <input class="form-check-input" type="checkbox" name="<?php echo $this->index; ?>choice[]" value="F">
              <label class="form-check-label"><?php echo $this->choicelist[5]; ?></label>
            </div>
          </div>
        <?php } elseif ($this->type == 1) { ?>
          <div class="form-group">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="<?php echo $this->index; ?>choice[]" value="A">
              <label class="form-check-label"><?php echo $this->choicelist[0]; ?></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="<?php echo $this->index; ?>choice[]" value="B">
              <label class="form-check-label"><?php echo $this->choicelist[1]; ?></label>
            </div>
            <div class="form-check form-check-inline" <?php echo ($this->num < 3)? "style='display:none;'":''; ?>>
              <input class="form-check-input" type="radio" name="<?php echo $this->index; ?>choice[]" value="C">
              <label class="form-check-label"><?php echo $this->choicelist[2]; ?></label>
            </div>
            <div class="form-check form-check-inline" <?php echo ($this->num < 4)? "style='display:none;'":''; ?>>
              <input class="form-check-input" type="radio" name="<?php echo $this->index; ?>choice[]" value="D">
              <label class="form-check-label"><?php echo $this->choicelist[3]; ?></label>
            </div>
            <div class="form-check form-check-inline" <?php echo ($this->num < 5)? "style='display:none;'":''; ?>>
              <input class="form-check-input" type="radio" name="<?php echo $this->index; ?>choice[]" value="E">
              <label class="form-check-label"><?php echo $this->choicelist[4]; ?></label>
            </div>
            <div class="form-check form-check-inline" <?php echo ($this->num < 6)? "style='display:none;'":''; ?>>
              <input class="form-check-input" type="radio" name="<?php echo $this->index; ?>choice[]" value="F">
              <label class="form-check-label"><?php echo $this->choicelist[5]; ?></label>
            </div>
          </div>
        <?php } else { ?>
          <div class="form-group">
            <label>Answer</label>
            <textarea class="form-control" rows="3" name="<?php echo $this->index; ?>choice"></textarea>
          </div>
        <?php } ?>
      </div>
      <?php return $index; ?>
    <!-- </div> -->


  <?php }
}
?>
