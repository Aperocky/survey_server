<?php
$survey = "test";
$sql = "CREATE TABLE IF NOT EXISTS " . $survey . " (";
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
echo $sql;
?>
