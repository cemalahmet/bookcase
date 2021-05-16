<?php
include("config.php");
session_start();

$userId = $_SESSION['login_user'];
$challange_name = "";

$challange_name = $_GET['row'];
$goalErr = "";

if(isset($_POST['join'])){
    if (empty($_POST["goal"])) {
        $goalErr = "Please enter a number";
    }
    else{
    $goal = $_POST['goal']; 

    $query = "INSERT INTO joins  
        VALUES ('$challange_name', '$userId', '$goal')";
    $results = mysqli_query($conn, $query);

    echo $query;
    echo $results;
    header("location: userChallenges.php");
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
            <li><a href = "userChallenges.php">Challenges</a></li>
            <style type="text/css">
             .wrapper ul li{  display: inline; padding: 1% }
    </style>

        </ul>
        <div class="main-text">

        <p class="login_text" >Join this challange</p> 
        <div id="center_button">
        <button onclick="location.href='userChallenges.php'">Back</button>
        </div> 

    <?php

    $sql = "SELECT challange_name, Info, due_date FROM challenges where challange_name = '$challange_name'";
    $result = mysqli_query($conn,$sql);
    ?>



    <table border="1" class="table_legenda" width="100%">
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
             </tr> 
        <?php 

        }
    }
  
    ?>
     <form method="post">
     <label>Your Goal </label><input type = "text" name = "goal" style="width:20%; float: left; margin-left:10px;"  class = "box"/><span class="error"> <?php  echo $goalErr;?></span><br/><br /></p> </td>
     <input type="submit" class="btn btn-primary" style="width:10%; float: left;margin-left:80px; background-color:#1e90ff ; padding: 10px;font-size: medium;" name="join" value="JOIN">

    </form>        

    </table>
    <?php  
    ?>
       
         </div> 
    </div>    
</body>
</p></center>
</html>