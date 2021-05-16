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

            $query = "SELECT librarian_id FROM librarians WHERE password = '$mypassword'  AND username = '$myusername'";
            $result = mysqli_query( $conn, $query );

            $query = "SELECT * FROM librarians JOIN accounts WHERE librarian_id = account_id AND librarian_id ='$myaccountid'" ;
            $result = mysqli_query( $conn, $query );
            if(mysqli_num_rows($result)==1){
                echo "heeeee";
                header("location: librarian.php");
            }
            else{

                header("location: home.php");
            }


            
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

    <p class="text" >Add Book</p> 

        <style type="text/css">
        </style>
        <form action="login.php" method="post">
            <label>Book Title </label><input type = "text" placeholder = "username" name = "username" class = "box"/><span class="error"> <?php echo  "</br>"; echo $nameErr;?></span><br /><br />
            <label>Published Year </label><input type = "password" placeholder = "password" name = "password" class = "box" /><span class="error"> <?php echo  "</br>"; echo $passErr;?></span><br/><br />
           
            <input type="submit" class="btn btn-primary" style="width:100%;  background-color:#ff7f50 ; padding: 10px;font-size: medium;" value="Add">
          
        </form>
   
    </div>    
</body>
</p></center>
</html>