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
   echo ("Opened or created quiz results data base successfully<br \>");
   $theQuery = 'CREATE TABLE quizResults (pieceID INTEGER PRIMARY KEY NOT NULL, logic REAL, abstract REAL, total REAL, inputTime INTEGER)';
   $ok = $db ->exec($theQuery);
  	// make sure the query executed
  	if (!$ok)
  	die($db->lastErrorMsg());
  	// if everything executed error less we will arrive at this statement
  	echo "Table quizResults created successfully<br \>";
}
catch(Exception $e)
{
   die($e);
}
?>
