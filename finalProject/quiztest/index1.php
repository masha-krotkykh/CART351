<!-- CREDITS: -->
<!--Quiz part is based on Even Stronger JS quiz: https://github.com/iamevenstronger/quiz -->
<!-- image Designed by GarryKillian from: https://www.freepik.com/free-vector/abstract-polygonal-cyber-sphere_1534720.html -->

<?php
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

    // escapeString for secuirity
    $logic_es =$db->escapeString($logic);
  	$abstract_es = $db->escapeString($abstract);
  	$total_es=$db->escapeString($total);

    // converting the values into integers
    $logic_int = intval($logic_es);
    $abstract_int = intval($abstract_es);
    $total_int = intval($total_es);

    // and inserting these values into the table into corresponding columns
    $queryInsert ="INSERT INTO quizResults(logic, abstract, total)VALUES ('$logic_int', '$abstract_int','$total_int')";
    // again we do error checking when we try to execute our SQL statement on the db
  	$ok1 = $db->exec($queryInsert);
    // NOTE:: error messages WILL be sent back to JQUERY success function .....
  	if (!$ok1) {
      die("Cannot execute statement.");
      exit;
    }

    // Getting an average value of every coulumn
    $sql_totalAv='SELECT AVG(total) FROM quizResults';
    $sql_logicAv='SELECT AVG(logic) FROM quizResults';
    $sql_abstractAv='SELECT AVG(abstract) FROM quizResults';
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
    <!-- <div id = "result"><img class = "svg" src ="img/hero.svg"></div> -->
    <div id = "result">
      <img id = "hero" class="svg" src="img/hero.svg">
    </div>

    <!-- Section of the questionaire to be populated from data file -->
    <div id="quizContainer">
      <div class="questionForm" id="questionForm">
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
          <!-- The current session results will be displayed here -->
          <p class="hide" id = "logic" name="a_logic"></p>
          <p class="hide" id = "abstract" name="a_abstract"></p>
          <p class="hide" id = "total" name="a_total"></p>
          <button input type = "submit" name = "submit" value = "submit my info" id =buttonS disabled="disabled"> submit </button>
        </form>
      </div> <!-- end of questionForm -->
    </div> <!-- end of quizContainer-->
    <button onclick="toggle()" name = "buttonH" id ="buttonH">&#187;</button>



    <!-- JavaScript starts here -->
    <!-- Linking to the question database -->
    <script src="js/data.js"></script>
    <script>
      // js array sequence variable
      var i = 0;
      var logicCount = 0 ;
      var abstractCount = 0;
      var correctCount = 0;
      //initialize the first question
      generate(0);
      // generate from js array data with index
      function generate(index) {
        document.getElementById("question").innerHTML = jsonData[index].q;
        document.getElementById("optt1").innerHTML = jsonData[index].opt1;
        document.getElementById("optt2").innerHTML = jsonData[index].opt2;
        document.getElementById("optt3").innerHTML = jsonData[index].opt3;
        document.getElementById("optt4").innerHTML = jsonData[index].opt4;
        document.getElementById("optt5").innerHTML = jsonData[index].opt5;
      }
      // Checking what option was selected by the user and compare it with the right checkAnswer
      // if the answer is corret, increment score of corresponding section by one
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
        // When six questions are answered, enable the "submit" button and populate the result section of HTML page
        if(i >= 6){
          document.getElementById('buttonS').disabled = false;
          document.getElementById("logic").innerHTML = "Logic: "+logicCount;
          document.getElementById("abstract").innerHTML = "Abstract: "+abstractCount;
          document.getElementById("total").innerHTML = "Total: "+correctCount;

          // console logging to see if works
          console.log(logicCount);
          console.log(abstractCount);
          console.log(correctCount);
        }

        else {
        // Until then callback to generate question
        generate(i);
      }
    }

      $("#insertResults").submit(function(event) {
        //stop submit the form, we will post it manually. PREVENT THE DEFAULT behaviour ...
        event.preventDefault();
        console.log("button clicked");
        // When the "submit" button is pressed, send the session results to the database to the corresponding columns
        let data = new FormData();
        data.append('logicCount', logicCount);
        data.append('abstractCount', abstractCount);
        data.append('correctCount', correctCount);

        // Change the position of the questionForm div when all questions are answered
        // Reset the submit button and start populating quesions anew
        // $('#questionForm').animate({'margin-top': '-20%'}, 1000);
        i = 0;
        document.getElementById('buttonS').disabled = true;

        // And then this...
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
              updateValues(parsedJSON);
            },
            error:function(){
             console.log("error occurred");
            }
         });
    });

    // Assigning values to JS variables to change the appearance of the graphics.
    function updateValues(globalValues) {
     var globalLogic = globalValues[0];
     var globalAbstract = globalValues[1];
     var globalTotal = globalValues[2];
      console.log(globalLogic);
      console.log(globalAbstract);
      console.log(globalTotal);
    }

    function toggle() {
      var margin = document.getElementById("questionForm");
      if(margin.style.display === "none") {
        margin.style.display = "block";
        document.getElementById("buttonH").style.transform = "rotate(-90deg)";
        document.getElementById("hero").setAttribute("fill", "#333");
      }
      else {
        margin.style.display = "none";
        document.getElementById("buttonH").style.transform = "rotate(90deg)";
        svg.setAttributeNS(null,"fill","#ffffff");
      }
    }

// Function to enable SVG image dynamic properties change
// Borrowed from StackOverflow
// https://stackoverflow.com/questions/24933430/img-src-svg-changing-the-fill-color?answertab=votes#tab-top
// http://jsfiddle.net/wuSF7/462/
    $(function(){
      jQuery('img.svg').each(function(){
        var $img = jQuery(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');

        jQuery.get(imgURL, function(data) {
            // Get the SVG tag, ignore the rest
            var $svg = jQuery(data).find('svg');
            // Add replaced image's ID to the new SVG
            if(typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if(typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', imgClass+' replaced-svg');
            }
            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');
            // Check if the viewport is set, else we gonna set it if we can.
            if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
            }
            // Replace image with new SVG
            $img.replaceWith($svg);

        }, 'xml');
      });
    });

     </script>
    </body>
    </html>
