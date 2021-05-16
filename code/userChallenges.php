<?php
include("config.php");
session_start();

$userId = $_SESSION['login_user'];






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
            <li><a href = "user.php?profile_id=<?php echo $userId ?>" >Profile</a></li>
            <li><a href = "userChallenges.php">Challenges</a></li>
            <style type="text/css">
             .wrapper ul li{  display: inline; padding: 1% }
    </style>

        </ul>
        <div class="main-text">

        <p class="login_text" >Challenges</p> 
        <div id="center_button">
        <button onclick="location.href='home.php'">Back</button>
    </div> 

    <?php
    $userId = $_SESSION['login_user'];

    $sql = "SELECT challange_name, Info, due_date FROM challenges where challange_name not in (select challange_name from joins where user_id = $userId);";
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
            <td><p style="font-family: Arial, Helvetica, sans-serif;;font-size:170%;padding: 10px 10px;  "><?php $link = '<a href="join.php?row=' . "$challange_name" . "\""; echo("$link".'>JOIN</a>');?></td>
            </tr> 
        <?php 

        }
    }
    ?>
   

    </table>
    <?php  
    ?>
        
       
         </div> 
    </div>    
</body>
</p></center>
</html>