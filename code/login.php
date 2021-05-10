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
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 20px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<center><p>
<body>
    <div class="wrapper">
    
        <h2>Login</h2>
        <style type="text/css">
        h2{ font: 40px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }

    </style>
        <form action="login.php" method="post">
            <label>Username  </label><input type = "text" name = "username" class = "box"/><span class="error">* <?php echo $nameErr;?></span><br /><br />
            <label>Password  </label><input type = "password" name = "password" class = "box" /><span class="error">* <?php echo $passErr;?></span><br/><br />
            <input type="submit" class="btn btn-primary" value="Login">

        </form>
    </div>    
</body>
</p></center>
</html>