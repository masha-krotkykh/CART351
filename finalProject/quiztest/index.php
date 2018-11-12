<!DOCTYPE html>
<html>
    <head>
        <title>Question Form</title>
    </head>
    <link rel="stylesheet" href="css/index.css">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
    <meta charset="utf-8">
    <body>
      <?php
      //check if there has been something posted to the server to be processed
      if($_SERVER['REQUEST_METHOD'] == 'POST')
      {
        // need to process
        $logic = $_POST['a_logic'];
        $abstract = $_POST['a_abstract'];
        $total = $_POST['a_total'];
      }
      ?>

        <div class="questionForm">
          <form action="index.php" method="post" enctype="multipart/form-data">
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
            <p id = "logic" name="a_logic"></p>
            <p id = "abstract" name="a_abstract"></p>
            <p id = "total" name="a_total"></p>
            <button input type = "submit" name = "submit" value = "submit my info" id =buttonS disabled="disabled"> submit </button>
          </form>
        </div>
        <script src="js/data.js"></script>
        <script>
          // json array sequence variable
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
                  // document.write("<body>");
                  // document.write("<div>Your logic score is : "+logicCount+"</div>");
                  // document.write("<div>Your abstract score is : "+abstractCount+"</div>");
                  // document.write("<div>Your total score is : "+correctCount+"</div>");
                  // document.write("</body>");
                  document.getElementById('buttonS').disabled = false;
                  document.getElementById("logic").innerHTML = "Logic: "+logicCount;
                  document.getElementById("abstract").innerHTML = "Abstract: "+abstractCount;
                  document.getElementById("total").innerHTML = "Total: "+correctCount;
                  console.log(logicCount);
                  console.log(abstractCount);
                  console.log(correctCount);
              }
              // callback to generate
              generate(i);
          }
        </script>
    </body>
    </html>
