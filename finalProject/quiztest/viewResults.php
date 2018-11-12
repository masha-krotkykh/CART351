<?php
// put required html mark up
echo"<html>\n";
echo"<head>\n";
echo"<title>Quiz Result Entries</title> \n";
//include CSS Style Sheet
echo"</head>\n";
// start the body ...
echo"<body>\n";
// place body content here ...
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
   $sql_select='SELECT * FROM quizResults';
   //to select one:
   // $sql_select_one = "SELECT * FROM artCollection WHERE pieceID = 1";
   //to select afer date:
   // $sql_select_new="SELECT * FROM artCollection WHERE creationDate >=Date('2002-01-01') AND Artist='Sarah'";
    // the result set
   $result = $db->query($sql_select);
   // to select one:
   // $result = $db->query($sql_select_one);
   //to select afer date:
    // $result = $db->query($sql_select_new);
   if (!$result) die("Cannot execute query.");

   // fetch first row ONLY...
   $row = $result->fetchArray(SQLITE3_ASSOC);
   $result->reset();
echo "<h3> Query Results:::</h3>";
echo"<div id='back'>";
// get a row...
while($row = $result->fetchArray(SQLITE3_ASSOC))

{
  echo "<div class ='outer'>";
  echo "<div class ='content'>";
  // go through each column in this row
  // retrieve key entry pairs
  foreach ($row as $key=>$entry)
  {
    echo "<p>".$key." :: ".$entry."</p>";
  }
}//end while
echo"</div>";

   print_r($row);
}

catch(Exception $e)
{
   die($e);
}


echo"</body>\n";
echo"</html>\n";
?>
