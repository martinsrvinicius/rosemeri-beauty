<?php
function OpenCon()
 {
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $db = "rosemeri_beauty";
 $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
 
 return $conn;
 }
 
function CloseCon($conn)
 {
 $conn -> close();
 }
  
 function OpenConPDO()
{
    /*
    Para giliana
    $dbhost = "192.168.70.8";
    $dbuser = "root";
    $dbpass = "R00t2023#";
    $db = "giliana";
    */
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $db = "rosemeri_beauty";
    $conn2 = new PDO("mysql:host=$dbhost;dbname=$db", $dbuser, $dbpass);
    return $conn2;
}
?>