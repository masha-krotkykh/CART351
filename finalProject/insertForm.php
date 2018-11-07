<!DOCTYPE html>
<html>
<head>
<title>Sample Insert into Gallery Form </title>
<!--set some style properties::: -->
<link rel="stylesheet" type="text/css" href="css/myStyle.css">
</head>
<body
<?php
session_start();
?>
<?php
//check if there has been something posted to the server to be processed
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  // need to process
  $_SESSION['a_logic'] = $_POST['a_logic'];
  $_SESSION['a_abstract'] = $_POST['a_abstract'];
  $_SESSION['a_motivation'] = $_POST['a_motivation'];
}
?>
<?php
if(isset($_SESSION['a_logic'])&&$_SESSION['a_abstract']&&$_SESSION['a_motivation']){

  $logic =   $_SESSION['a_logic'];
  $abstract = $_SESSION['a_abstract'];
  $motivation = $_SESSION['a_motivation'];
  $date = new DateTime('now', new DateTimeZone('America/Toronto'));
  // $time = date("H:i");
  $general = $_POST['a_logic'] + $_POST['a_abstract'];

  echo "<div class = 'outer'>";
  echo "<h3> Results from user </h3>";
  echo "<div class ='content'>";
  echo "<p> General Cognitive Productivity: ".$general."</p>";
  echo "<p> Logic Thinking: ".$logic."</p>";
  echo "<p> Abstract Thinking: ".$abstract."</p>";
  echo "<p> Motivation Level: ".$motivation."</p>";
  // echo "<p> Current Time: ".$time."</p>";
  echo $date->format('H:i');
  echo "</div>";
  echo "</div>";

  // remove all session variables
  session_unset();
  //destroy a session
  session_destroy();

}
?>
              <!-- <?php
              //check if there has been something posted to the server to be processed
              if($_SERVER['REQUEST_METHOD'] == 'POST')
              {
                // need to process
                $logic = $_POST['a_logic'];
                $abstract = $_POST['a_abstract'];
                $motivation = $_POST['a_motivation'];
                $date = new DateTime('now', new DateTimeZone('America/Toronto'));
                // $time = date("H:i");
                $general = $_POST['a_logic'] + $_POST['a_abstract'];

                 echo "<div class = 'outer'>";
                 echo "<h3> Results from user </h3>";
                 echo "<div class ='content'>";
                 echo "<p> General Cognitive Productivity: ".$general."</p>";
                 echo "<p> Logic Thinking: ".$logic."</p>";
                 echo "<p> Abstract Thinking: ".$abstract."</p>";
                 echo "<p> Motivation Level: ".$motivation."</p>";
                 // echo "<p> Current Time: ".$time."</p>";
                 echo $date->format('H:i');
                 echo "</div>";
                 echo "</div>";
              }
              ?> -->


<div class= "formContainer">
<!--form done using more current tags... -->
<form action="insertForm.php" method="post" enctype ="multipart/form-data">
<!-- group the related elements in a form -->
<h3>FEED THE COLLECTIVE MIND</h3>
<fieldset>

<p><label>Logic:</label><input type = "text" size="24" maxlength = "40"  name = "a_logic" required></p>
<p><label>Abstract:</label><input type = "text" size="24" maxlength = "40" name = "a_abstract" required></p>
<p><label>Motivation:</label><input type="text" name="a_motivation" required></p>
<p><label>Time of the Day:</label><input type = "text" name = "a_time" required></p>
<p class = "sub"><input type = "submit" name = "submit" value = "submit my info" id =buttonS /></p>
 </fieldset>
</form>
</div>
</body>
</html>
