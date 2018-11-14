<?php
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

  //check if there has been something posted to the server to be processed
  if($_SERVER['REQUEST_METHOD'] == 'POST')
  {

    // need to process
    $logic = $_POST['logicCount'];
    $abstract = $_POST['abstractCount'];
    $total = $_POST['correctCount'];

    $logic_es =$db->escapeString($logic);
  	$abstract_es = $db->escapeString($abstract);
  	$total_es=$db->escapeString($total);

    $logic_int = intval($logic_es);
    $abstract_int = intval($abstract_es);
    $total_int = intval($total_es);

    $queryInsert ="INSERT INTO quizResults(logic, abstract, total)VALUES ('$logic_int', '$abstract_int','$total_int')";
    // again we do error checking when we try to execute our SQL statement on the db
  	$ok1 = $db->exec($queryInsert);
    // NOTE:: error messages WILL be sent back to JQUERY success function .....
  	if (!$ok1) {
      die("Cannot execute statement.");
      exit;
      }
    //send back success...
    // echo "success";
    // exit;

    // Getting an average value of the coulumn
    $sql_totalAv='SELECT AVG(total) FROM quizResults';
    $sql_logicAv='SELECT AVG(logic) FROM quizResults';
    $sql_abstractAv='SELECT AVG(abstract) FROM quizResults';
    $avgTotal = $db->query($sql_totalAv);
    $avgLogic = $db->query($sql_logicAv);
    $avgAbstract = $db->query($sql_abstractAv);
    if (!$avgTotal || !$avgLogic || !$avgAbstract) die("Cannot execute query.");

    while($row = $avgTotal->fetchArray(SQLITE3_ASSOC))
    {
      foreach ($row as $key=>$entry)
      {
        $finalTotalVal = (int)($entry*10);
        // echo $finalTotalVal;
      }
    }
    while($row = $avgLogic->fetchArray(SQLITE3_ASSOC))
    {
      foreach ($row as $key=>$entry)
      {
        $finalLogicVal = (int)($entry*10);
        // echo $finalLogicVal;
      }
    }
    while($row = $avgAbstract->fetchArray(SQLITE3_ASSOC))
    {
      foreach ($row as $key=>$entry)
      {
        $finalAbstractVal = (int)($entry*10);
        // echo $finalAbstractVal;
      }
    }
    $currentPara = array($finalLogicVal, $finalAbstractVal, $finalTotalVal);

    $myJSONObj = json_encode($currentPara);
    echo $myJSONObj;
    exit;
  }//POST
?>

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
    <!-- NEW for the result -->
    <div id = "result"></div>

    <div class="questionForm">
      <form id="insertResults" action="" enctype="multipart/form-data">
        <h2>FEED THE COLLECTIVE MIND</h2>
        <div class="question" id="question">
        </div>
        <div><input type="radio" id="opt1" name="options" onclick="checkAnswer()">
          <span id="optt1"></span>
        </div>
        <div><input type="radio" id="opt2" name="options" onclick="checkAnswer()">
          <span id="optt2"></span>
        </div>
        <div><input type="radio" id="opt3" name="options" onclick="checkAnswer()">
          <span id="optt3"></span>
        </div>
        <div><input type="radio" id="opt4" name="options" onclick="checkAnswer()">
          <span id="optt4"></span>
        </div>
        <div><input type="radio" id="opt5" name="options" onclick="checkAnswer()">
          <span id="optt5"></span>
        </div>
        <p class="hide" id = "logic" name="a_logic"></p>
        <p class="hide" id = "abstract" name="a_abstract"></p>
        <p class="hide" id = "total" name="a_total"></p>
        <button input type = "submit" name = "submit" value = "submit my info" id =buttonS disabled="disabled"> submit </button>
        <h2>CURRENT STATE</h2>
      </form>
      <!-- <form id="getResults" action="">
        <h2>CURRENT STATE</h2>
        <button input type = "submit" name = "view" value = "get results" id =buttonR> view </button>
      </form>
    </div> -->
    <div id = "result"></div>
    <script src="js/data.js"></script>

    <script>
      // json array sequence variable
   //$(document).ready (function(){
      var i = 0;
      var logicCount = 0 ;
      var abstractCount = 0;
      var correctCount = 0;
      //initialize the first question
      generate(0);
      // generate from json array data with index
      function generate(index) {
        document.getElementById("question").innerHTML = jsonData[index].q;
        document.getElementById("optt1").innerHTML = jsonData[index].opt1;
        document.getElementById("optt2").innerHTML = jsonData[index].opt2;
        document.getElementById("optt3").innerHTML = jsonData[index].opt3;
        document.getElementById("optt4").innerHTML = jsonData[index].opt4;
        document.getElementById("optt5").innerHTML = jsonData[index].opt5;
      }

      function checkAnswer() {

        if (document.getElementById("opt1").checked && jsonData[i].opt1 == jsonData[i].answer) {
          if (jsonData[i].type == "logic"){
            logicCount++;
          }
          else if (jsonData[i].type == "abstract"){
            abstractCount++;
          }
          correctCount++;
        }
        if (document.getElementById("opt2").checked && jsonData[i].opt2 == jsonData[i].answer) {
          if (jsonData[i].type == "logic"){
            logicCount++;
          }
          else if (jsonData[i].type == "abstract"){
            abstractCount++;
          }
          correctCount++;
        }
        if (document.getElementById("opt3").checked && jsonData[i].opt3 == jsonData[i].answer) {
          if (jsonData[i].type == "logic"){
            logicCount++;
          }
          else if (jsonData[i].type == "abstract"){
            abstractCount++;
          }
          correctCount++;
        }
        if (document.getElementById("opt4").checked && jsonData[i].opt4 == jsonData[i].answer) {
          if (jsonData[i].type == "logic"){
            logicCount++;
          }
          else if (jsonData[i].type == "abstract"){
            abstractCount++;
          }
          correctCount++;
        }
        if (document.getElementById("opt5").checked && jsonData[i].opt5 == jsonData[i].answer) {
          if (jsonData[i].type == "logic"){
            logicCount++;
          }
          else if (jsonData[i].type == "abstract"){
            abstractCount++;
          }
          correctCount++;
        }
        // increment i for next question
        i++;
        if(jsonData.length-1 < i){
          document.getElementById('buttonS').disabled = false;
          document.getElementById("logic").innerHTML = "Logic: "+logicCount;
          document.getElementById("abstract").innerHTML = "Abstract: "+abstractCount;
          document.getElementById("total").innerHTML = "Total: "+correctCount;
          console.log(logicCount);
          console.log(abstractCount);
          console.log(correctCount);
        }
        else{
        // callback to generate
        generate(i);
      }
    }

      $("#insertResults").submit(function(event) {
        //stop submit the form, we will post it manually. PREVENT THE DEFAULT behaviour ...
        event.preventDefault();
        console.log("button clicked");
        //let form = $('#insertResults')[0];
        let data = new FormData();
        data.append('logicCount', logicCount);
        data.append('abstractCount', abstractCount);
        data.append('correctCount', correctCount);

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "index1.php",
            data: data,
            processData: false,//prevents from converting into a query string
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (response) {
              console.log("Yoohoo!"+response);

            // Parse average values of coulumns
            let parsedJSON = JSON.parse(response);
            console.log(parsedJSON);
            displayResponse(parsedJSON);
            },
           error:function(){
             console.log("error occurred");
           }
         });
      // });
    });

        // validate and process form here
 // function displayResponse(theResult){
 //   // theResult is AN ARRAY of objects ...
 //   for(let i=0; i< theResult.length; i++)
 //   {
 //   // get the next object
 //   let currentObject = theResult[i];
 //   let container = $('<div>').addClass("outer");
 //   let contentContainer = $('<div>').addClass("content");
 //   // go through each property in the current object ....
 //   for (let property in currentObject) {
 //       let para = $('<p>');
 //       $(para).text(property+"::" +currentObject[property]);
 //         $(para).appendTo(contentContainer);
 //     }
 //   //
 //   }
 //   $(contentContainer).appendTo(container);
 //   $(container).appendTo("#result");
 // }

     </script>
    </body>
    </html>
