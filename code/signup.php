<?php
include("config.php");
session_start();

$myusername = "";
$myaccountid = "";
$mypassword = "";
$nameErr = "";
$passErr = "";
$mailErr = "";
$usernameErr="";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (empty($_POST["username"])) {
        $nameErr = "Name is required";
    }
    if(empty($_POST["password"])) {
        $passErr = "password is required";
    }
    if(empty($_POST["e-mail"])) {
        $mailErr = "e-mail is required";
    }
    else{
        $mypassword = $_POST['password']; 
        $myusername = $_POST['username'];
        $mymail = $_POST['e-mail'];

        $sql_u = "SELECT * FROM accounts WHERE username='$myusername'";
       
        $res_u = mysqli_query($conn, $sql_u);

        if (mysqli_num_rows($res_u) > 0) {
        $nameErr = "This username already taken"; 	}
        else{
           $query = "INSERT INTO accounts (username, e_mail, password) 
      	    	  VALUES ('$myusername', '$mymail', '$mypassword')";
           $results = mysqli_query($conn, $query);
           header("location: registered.php");
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
        .wrapper{ width: 480px; height: 580px ; padding: 30px; }
    </style>
</head>
<center><p>
<body>
    <div class="wrapper">
    <p class="login_text" >Sign Up</p> 

        <style type="text/css">
        </style>

        <form action="signup.php" method="post">
            <label>Username </label> <input type = "text" placeholder = "username" name = "username" class = "box"/><span class="error"> <?php echo  "</br>"; echo $nameErr;?></span><br /><br />

            <label>Password </label><input type = "password" placeholder = "password" name = "password" class = "box" /><span class="error"> <?php echo  "</br>"; echo $passErr;?></span><br/><br />
            <label>Email </label><input type = "email" placeholder = "email" name = "e-mail" class = "box" /><span class="error"> <?php echo  "</br>";  echo $mailErr;?></span><br/><br />
            <input type="submit" class="btn btn-primary" style="width:100%; padding: 10px;font-size: medium;" value="Sign Up">
                  
            <p class = "signup-login-text"style= "font-size: 2rem; font-weignt:100;"> Already have an accout? <a href = "login.php">Login Here</a></
    
        </form>
            
    </div>   
</body>
</p></center>
</html>