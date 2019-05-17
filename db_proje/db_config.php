<?php

function Connect()
 {
 $dbhost = "dijkstra.ug.bcc.bilkent.edu.tr:3306/orkun_elmas";
 $dbuser = "orkun.elmas";
 $dbpass = "hsfSIRv7";
 $db = "orkun_elmas";


 $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
 return $conn;
 }
 
function Disconnect($conn)
 {
 $conn -> close();
 }
   
?>