<?php
include("config.php");
session_start();
$userId = $_SESSION['login_user'];

$challenge_name = "";
$info = "";
$due_date = "";
$nameErr = "";
$infoErr = "";
$dateErr = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {   
    if (empty($_POST["challenge_name"])) {
        $nameErr = "Name is required";
    }
    else if(empty($_POST["info"])) {
        $infoErr = "info is required";
    }
    else if(empty($_POST["due_date"])) {
        $dateErr = "due date is required";
    }
    else{
        $challenge_name = $_POST['challenge_name'];
        $info =  $_POST['info'];
        $due_date = $_POST['due_date'];

        echo "$due_date";

        $sql_u = "SELECT * FROM challenges WHERE challange_name='$challenge_name'";
       
        $res_u = mysqli_query($conn, $sql_u);

        if (mysqli_num_rows($res_u) > 0) {
        $nameErr = "A challenge with this name already exists";
     	}
        else{
            $query = "INSERT INTO challenges (challange_name, librarian_id, Info, due_date) 
                  VALUES ('$challenge_name', '$userId', '$info', '$due_date' )";
            $results = mysqli_query($conn, $query);

            header("location: challengeAdded.php");
         }

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Login</title> 
    <link rel="stylesheet" href="addBook.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 25px sans-serif; }
        .wrapper{ background: rgba(218, 220, 228);  }
    </style>
</head>
<center><p>
<body>
    <div class="wrapper">

    <p class="text" >Create Challange</p> 

    <div id="center_button">
        <button onclick="location.href='librarian.php'">Back</button>
    </div>  

        <style type="text/css">
        </style>
        <form action="addChallange.php" method="post">
            <label>Challenge Name </label><input type = "text"  name = "challenge_name" class = "box"/><span class="error"> <?php echo $nameErr;?></span><br /><br />
            <label>Info </label><input type = "text"  name = "info" class = "box"/><span class="error"> <?php  echo $infoErr;?></span><br/><br />
            <label>Due date </label><input type = "date"  name = "due_date" class = "box" /><span class="error"> <?php echo $dateErr;?></span><br/><br />
           
            <input type="submit" class="btn btn-primary" style="width:100%;  background-color:#ff7f50 ; padding: 10px;font-size: medium;" value="Create">
          


        </form>
   
    </div>    
</body>
</p></center>
</html>