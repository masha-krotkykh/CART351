<?php
//check if there has been something posted to the server to be processed
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
// need to process
 $logic = $_POST['a_logic'];
 $abstract = $_POST['a_abstract'];
 $motivation = $_POST['a_motivation'];
 $date = new DateTime('now', new DateTimeZone('America/Toronto'));
 if($date > 4 && $date < 12) {
   $timeOfDay = 'AM';
 }
 else {
   $timeOfDay = 'PM';
 }

 $general = $_POST['a_logic'] + $_POST['a_abstract'];

 //package the data and echo back...
    $myPackagedData=new stdClass();
    $myPackagedData->general = $general ;
    $myPackagedData->logic = $logic ;
    $myPackagedData->abstract = $abstract ;
    $myPackagedData->motivation = $motivation ;
    $myPackagedData->timeOfDay = $timeOfDay ;

     // Now we want to JSON encode these values to send them to $.ajax success.
    $myJSONObj = json_encode($myPackagedData);
    echo $myJSONObj;
    exit;


}//POST
?>


<!DOCTYPE html>
<html>
<head>
<title>Sample Insert into Gallery Form USING JQUERY AND AJAX </title>
<!-- get JQUERY -->
  <script src = "libs/jquery-3.3.1.min.js"></script>
<!--set some style properties::: -->
<link rel="stylesheet" type="text/css" href="css/myStyle.css">
</head>
<body>
  <!-- NEW for the result -->
<div id = "result"></div>

<div class= "formContainer">
<!--form done using more current tags... -->
<form id="insertData" action="" enctype ="multipart/form-data">
<!-- group the related elements in a form -->
<h3> FEED THE COLLECTIVE MIND </h3>
<fieldset>
  <p><label>Logic:</label><input type = "text" size="24" maxlength = "40"  name = "a_logic" required></p>
  <p><label>Abstract:</label><input type = "text" size="24" maxlength = "40" name = "a_abstract" required></p>
  <p><label>Motivation:</label><input type="text" name="a_motivation" required></p>
  <!-- <p><label>Time of the Day:</label><input type = "text" name = "a_time" required></p> -->
  <p class = "sub"><input type = "submit" name = "submit" value = "submit my info" id =buttonS /></p>
 </fieldset>
</form>
</div>
<script>


<!-- here we put our JQUERY -->

let form = $('#insertData')[0];
let data = new FormData(form);

$(document).ready (function(){
    $("#insertData").submit(function(event) {
       //stop submit the form, we will post it manually. PREVENT THE DEFAULT behaviour ...
      event.preventDefault();
     console.log("button clicked");
   });
});

// validate and process form here
function displayResponse(theResult){
  let container = $('<div>').addClass("outer");
  let title = $('<h3>');
  $(title).text("Results from user");
  $(title).appendTo(container);
  let contentContainer = $('<div>').addClass("content");
  for (let property in theResult) {
    console.log(property);
      let para = $('<p>');
      $(para).text(property+"::" +theResult[property]);
      $(para).appendTo(contentContainer);
  }
  $(contentContainer).appendTo(container);
  $(container).appendTo("#result");
}


$.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "insertDataAJAX.php",
            data: data,
            processData: false,//prevents from converting into a query string
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (response) {
            //reponse is a STRING (not a JavaScript object -> so we need to convert)
            console.log(response);
            //use the JSON .parse function to convert the JSON string into a Javascript object
     let parsedJSON = JSON.parse(response);
     console.log(parsedJSON);
     displayResponse(parsedJSON);
           },
           error:function(){
          console.log("error occurred");
        }
      });

</script>
</body>
</html>
