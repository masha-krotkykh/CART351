<?php
// CREDITS:
// Quiz part is based on Even Stronger JS quiz: https://github.com/iamevenstronger/quiz
// image Designed by GarryKillian from: https://www.freepik.com/free-vector/abstract-polygonal-cyber-sphere_1534720.html

// First of all we need to check if an SQL database exists
// if not, create an SQL database
  class MyDB extends SQLite3
  {
    function __construct()
    {
      $this->open('../db/quizResults.db');
    }
  }
  try
  {
    $db = new MyDB();
  }
  catch(Exception $e)
  {
    die($e);
  }

  // check if there has been something posted to the server to be processed
  if($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    // need to process
    $logic = $_POST['logicCount'];
    $abstract = $_POST['abstractCount'];
    $total = $_POST['correctCount'];
    $inputTime = $_POST['morning'];

    date_default_timezone_set('America/Toronto');

    // escapeString for secuirity
    $logic_es = $db->escapeString($logic);
  	$abstract_es = $db->escapeString($abstract);
  	$total_es = $db->escapeString($total);
    $inputTime_es = $db->escapeString($inputTime);

    // converting the values into integers
    $logic_int = intval($logic_es);
    $abstract_int = intval($abstract_es);
    $total_int = intval($total_es);
    $inputTime_int = intval($inputTime_es);

    // and inserting these values into the table into corresponding columns
    $queryInsert ="INSERT INTO quizResults(logic, abstract, total, inputTime)VALUES ('$logic_int', '$abstract_int','$total_int','$inputTime_int')";
    // again we do error checking when we try to execute our SQL statement on the db
  	$ok1 = $db->exec($queryInsert);
    // NOTE:: error messages WILL be sent back to JQUERY success function .....
  	if (!$ok1) {
      die("Cannot execute statement.");
      exit;
    }

    // Getting an average value of every coulumn
    // if current time is between 2am and 2 pm, we'll only check the results where "morning" is true i.e. the results that were submitted in the morning
    if (date('H') < 14 && date('H') > 2) {
      $sql_totalAv='SELECT AVG(total) FROM quizResults WHERE inputTime = 1';
      $sql_logicAv='SELECT AVG(logic) FROM quizResults WHERE inputTime = 1';
      $sql_abstractAv='SELECT AVG(abstract) FROM quizResults WHERE inputTime = 1';
    }
    // if current time is between 2pm and 2am, we'll only check the afternoon results
    else {
      $sql_totalAv='SELECT AVG(total) FROM quizResults WHERE inputTime = 0';
      $sql_logicAv='SELECT AVG(logic) FROM quizResults WHERE inputTime = 0';
      $sql_abstractAv='SELECT AVG(abstract) FROM quizResults WHERE inputTime = 0';
    }

    // and running a querry
    $avgTotal = $db->query($sql_totalAv);
    $avgLogic = $db->query($sql_logicAv);
    $avgAbstract = $db->query($sql_abstractAv);
    // kill the querry if at least one of the values is unreacheable
    if (!$avgTotal || !$avgLogic || !$avgAbstract) die("Cannot execute query.");
    // !!! THE RESULT RETURNS AS AN ARRAY EVEN IF IT HAS ONLY ONE VALUE !!!
    // use while loop to "unpack" values from arrays
    while($row = $avgTotal->fetchArray(SQLITE3_ASSOC))
    {
      foreach ($row as $key=>$entry)
      {
        $finalTotalVal = (int)($entry);
        // echo $finalTotalVal;
      }
    }
    while($row = $avgLogic->fetchArray(SQLITE3_ASSOC))
    {
      foreach ($row as $key=>$entry)
      {
        $finalLogicVal = (int)($entry);
        // echo $finalLogicVal;
      }
    }
    while($row = $avgAbstract->fetchArray(SQLITE3_ASSOC))
    {
      foreach ($row as $key=>$entry)
      {
        $finalAbstractVal = (int)($entry);
        // echo $finalAbstractVal;
      }
    }
    // create an array of average values from the database
    $currentPara = array($finalLogicVal, $finalAbstractVal, $finalTotalVal);
    $myJSONObj = json_encode($currentPara);

   echo $myJSONObj;
    exit;
  }//POST
?>

<!-- HTML page display -->
<!DOCTYPE html>
<html>
  <head>
  <title>Question Form</title>
  </head>
  <script src = "libs/jquery-3.3.1.min.js"></script>
  <link rel="stylesheet" href="css/index.css">
  <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
  <meta charset="utf-8">
  <body>
    <!-- Here the graphics will be displayed with parameters retreived from the database -->
    <div id = "result">
      <img id = "hero" class="svg" src="img/hero.svg">
      <div id="heroState">
        <p id = "heroLogic"></p>
        <p id = "heroAbstract"></p>
        <p id = "heroTotal"></p>
      </div>
    </div>

    <!-- Personal user results of the current session for comparison -->
    <div id = "avatar">
      <img id = "avatar" class="svg" src="img/hero.svg">
    </div>
    <!-- Section of the questionaire to be populated from data file -->
    <div id="quizContainer">
      <div class="questionForm" id="questionForm">
        <form id="insertResults" action="" enctype="multipart/form-data">
          <h2>FEED THE COLLECTIVE MIND</h2>
          <div id = "reply">
            <div class="question" id="question">
            </div>
            <div><input type="radio" id="opt1" name="options" onclick="checkAnswers()">
              <span id="optt1"></span>
            </div>
            <div><input type="radio" id="opt2" name="options" onclick="checkAnswers()">
              <span id="optt2"></span>
            </div>
            <div><input type="radio" id="opt3" name="options" onclick="checkAnswers()">
              <span id="optt3"></span>
            </div>
            <div><input type="radio" id="opt4" name="options" onclick="checkAnswers()">
              <span id="optt4"></span>
            </div>
            <div><input type="radio" id="opt5" name="options"onclick="checkAnswers()">
              <span id="optt5"></span>
            </div>
          </div>
          <!-- The current session results will be displayed here -->
          <div id = "yourScore">
            <p class="hide" id = "logic" name="a_logic"></p>
            <p class="hide" id = "abstract" name="a_abstract"></p>
            <p class="hide" id = "total" name="a_total"></p>
        </div>
        <button input type = "submit" name = "submit" value = "submit my info" id =buttonS disabled="disabled" onclick="toggle()"> submit </button>
        </form>
      </div> <!-- end of questionForm -->
    </div> <!-- end of quizContainer-->
    <button onclick="toggle()" name = "buttonH" id ="buttonH">&#187;</button>

    <!-- Linking to the question database -->
    <script src="js/data.js"></script>
    <!-- Linking to the main JS file -->
    <script src="js/mechanics.js"></script>
    </body>
    </html>
