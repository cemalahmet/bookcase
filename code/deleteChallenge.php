<?php
include("config.php");
//include("challenges.php");
session_start();
$userId = $_SESSION['login_user'];
$row = "";
$row = $_GET['row'];
echo $row;

$delete = mysqli_query( $conn, "DELETE FROM joins WHERE challange_name = '$row'" );
$delete = mysqli_query( $conn, "DELETE FROM challenges WHERE challange_name = '$row'" );
header("Location: challenges.php");
?>