<?php
include("config.php");
session_start();

$myusername = "";
$myaccountid = "";
$mypassword = "";
$nameErr = "";
$passErr = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (empty($_POST["username"])) {
        $nameErr = "Name is required";
    }
    else if(empty($_POST["password"])) {
        $passErr = "Password is required";
    }
    else{
        $mypassword = $_POST['password'];
        $myusername = $_POST['username'];

        $query = "SELECT account_id, username, password FROM accounts WHERE password = '$mypassword'  AND username = '$myusername'";
        $result = mysqli_query( $conn, $query );


        //$count = mysqli_num_rows($result);

        if(mysqli_num_rows($result)==1){
            $row = $result->fetch_assoc();
            $myaccountid = $row['account_id'];
            $_SESSION['login_user'] = $myaccountid;
            header("location: home.php");
        }
        else if(mysqli_num_rows($result)==0){
            $passErr = "Something went wrong!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Login</title> 
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 25px sans-serif; }
        .wrapper{width: 480px; height: 480px ; padding: 30px; }
    </style>
</head>
<center><p>
<body>
    <div class="wrapper">

    <p class="login_text" >Login</p> 

        <style type="text/css">
        </style>
        <form action="login.php" method="post">
            <label>Username </label><input type = "text" placeholder = "username" name = "username" class = "box"/><span class="error"> <?php echo  "</br>"; echo $nameErr;?></span><br /><br />
            <label>Password </label><input type = "password" placeholder = "password" name = "password" class = "box" /><span class="error"> <?php echo  "</br>"; echo $passErr;?></span><br/><br />
           
            <input type="submit" class="btn btn-primary" style="width:100%;  background-color:#1e90ff ; padding: 10px;font-size: medium;" value="Login">
          
            <p class = "login-signup-text"style= "font-size: 2rem; font-weignt:100;"> Don't have an accout? <a href = "signup.php">Sign Up Here</a></
    
        </form>
   
    </div>    
</body>
</p></center>
</html>