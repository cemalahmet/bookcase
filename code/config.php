<?php
$username="kubra.okumus";
$db_name = "kubra_okumus";
$password = "TxAzirXR";
$host = "dijkstra.ug.bcc.bilkent.edu.tr";

$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>