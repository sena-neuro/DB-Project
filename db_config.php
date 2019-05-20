<?php

function Connect()
 {
 $dbhost = "dijkstra.ug.bcc.bilkent.edu.tr:3306/mert_duman";
 $dbuser = "mert.duman";
 $dbpass = "rjptM4DQ";
 $db = "mert_duman";


 $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
 return $conn;
 }
 
function Disconnect($conn)
 {
 $conn -> close();
 }
   
?>