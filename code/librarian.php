<?php
include("config.php");
session_start();

$userId = $_SESSION['login_user'];

$myusername = "";
$myaccountid = "";
$mypassword = "";
$nameErr = "";
$passErr = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (empty($_POST["username"])) {
        $nameErr = "Name is required";
    }
    if(empty($_POST["password"])) {
        $passErr = "password is required";
    }
else{
    $mypassword = $_POST['password']; 
    $myusername = $_POST['username'];

    $query = "SELECT account_id, username, password FROM accounts WHERE password = '$mypassword'  AND username = '$myusername'";
    $result = mysqli_query( $conn, $query );


    $count = mysqli_num_rows($result);

    if(mysqli_num_rows($result)==1){
        $row = $result->fetch_assoc();
        $myaccountid = $row['account_id'];
        $_SESSION['login_user'] = $myaccountid;
        header("location: home.php");        
    }
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Login</title> 
    <link rel="stylesheet" href="librarian.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
       .wrapper{ background: rgba(218, 220, 228);  }
    </style>
</head>
<center><p>
<body>
    <div class="wrapper">
        <ul>
            <li><a href = "login.php">Log out</a></li>
            <li><a href = "#">Profile</a></li>
            <li><a href = "challenges.php">Challenges</a></li>
            <style type="text/css">
             .wrapper ul li{  display: inline; padding: 1% }
    </style>

        </ul>
        <div class="main-text">

        <p class="login_text" >Librarian Page</p> 
        
        <button class="block" onclick="location.href='addChallange.php'">Create A Challenge</button>
        <button class="block" onclick="location.href='addbook.php'">Add Book</button>      
        <button class="block" onclick="location.href='addAuthor.php'">Add Author</button>      
        <button class="block" onclick="location.href='addbook.php'">Update Author Info</button>
        
        <button class="block" onclick="location.href='addbook.php'">Update Book Info</button>
              
         </div> 
    </div>    
</body>
</p></center>
</html>