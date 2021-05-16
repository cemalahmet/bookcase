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
    <title> Librarian Challenges</title> 
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
            <li><a href = "challenges">Challenges</a></li>
            <style type="text/css">
             .wrapper ul li{  display: inline; padding: 1% }
    </style>

        </ul>
        <div class="main-text">

        <p class="login_text" >Challenges you created </p> 
        <div id="center_button">
        <button onclick="location.href='librarian.php'">Back</button>
    </div> 

    <?php
    $userId = $_SESSION['login_user'];

    $sql = "SELECT challange_name, Info, due_date FROM challenges WHERE librarian_id = $userId";
    $result = mysqli_query($conn,$sql);
    ?>

    <table border="3" class="table_legenda" width="100%">
     <tr>
    <th><p style="font-family: Arial, Helvetica, sans-serif;;font-size:170%;padding: 10px 10px;  ">Challenge Name</p></th>
    <th><p style="font-family: Arial, Helvetica, sans-serif;;font-size:170%;padding: 10px 10px;  ">Challenge Info</p></th>
    <th><p style="font-family: Arial, Helvetica, sans-serif;;font-size:170%;padding: 10px 10px;  ">Due Date</p></th>
    </tr>
    

    
    <?php
    if ($result_set = $conn->query($sql)) {
        while($row = $result_set->fetch_array(MYSQLI_ASSOC)){
            $challange_name=$row['challange_name'];
            $Info=$row['Info'];
            $due_date=$row['due_date'];
            ?>
            <tr>
            <td> <p style="font-family: Arial, Helvetica, sans-serif;;font-size:170%;padding: 10px 10px;  ">  <?php echo "$challange_name"?>  </p></td>
            <td> <p style="font-family: Arial, Helvetica, sans-serif;;font-size:170% ;padding: 10px 10px; "><?php echo "$Info"?> </p></td>
            <td> <p style="font-family: Arial, Helvetica, sans-serif;;font-size:170%; padding: 10px 10px; "><?php echo "$due_date"?></p> </td>
            <td><p style="font-family: Arial, Helvetica, sans-serif;;font-size:170%;padding: 10px 10px;  "><?php $link = '<a href="deleteChallenge.php?row=' . "$challange_name" . "\""; echo("$link".'>Delete</a>');?></td>
            </tr> 
        <?php 

        }
    }
    ?>

    </table>
    <?php  
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        header("location: index.php"); 
    }
    ?>
        
       
         </div> 
    </div>    
</body>
</p></center>
</html>