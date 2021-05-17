<?php
include("config.php");
session_start();

$name = "";
$bio = "";
$genre1 = "Fantasy";
$genre2 = "Mystery";
$genre3 = "Classic";
$genre4 = "Action";
$genre5 = "Romance";

$emptyErr = "";


if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (empty($_POST['name']) || empty($_POST['bio'])  ) {
        $emptyErr = "Something is missing!";
    }
    else{
        $name = $_POST['name'];
        $bio = $_POST['bio'];
        

        $query = "SELECT * FROM authors WHERE author_name = '$name'";
        $result1 = mysqli_query( $conn, $query );



        if((mysqli_num_rows($result1) > 0 ) ){
            $emptyErr = "This author is already registered in the system!";
        }
        else{
            $query = "INSERT INTO authors (author_name, biography) 
                    VALUES ('$name', '$bio')";
            $results = mysqli_query($conn, $query);
            
            $query = "SELECT * FROM authors WHERE author_name  = '$name' and biography = '$bio'";
            $result1 = mysqli_query( $conn, $query );

            $row = $result1->fetch_assoc();
            $author_id = $row['author_id'];

            // GENRE INSERTION

            if(!empty($_POST['genre1'])){
                $query = "INSERT INTO author_genre (author_id, genre) 
                        VALUES ('$author_id', '$genre1')";
                $results = mysqli_query($conn, $query);
            }
            if(!empty($_POST['genre2'])){
                $query = "INSERT INTO author_genre (author_id, genre) 
                        VALUES ('$author_id', '$genre2')";
                $results = mysqli_query($conn, $query);
            }
            if(!empty($_POST['genre3'])){
                $query = "INSERT INTO author_genre (author_id, genre) 
                        VALUES ('$author_id', '$genre3')";
                $results = mysqli_query($conn, $query);
            }
            if(!empty($_POST['genre4'])){
                $query = "INSERT INTO author_genre (author_id, genre) 
                        VALUES ('$author_id', '$genre4')";
                $results = mysqli_query($conn, $query);
            }
            if(!empty($_POST['genre5'])){
                $query = "INSERT INTO author_genre (author_id, genre) 
                        VALUES ('$author_id', '$genre5')";
                $results = mysqli_query($conn, $query);
            }

            header("location: authorAdded.php");
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

    <p class="text" >Add Author</p> 
    <div id="center_button">
        <button onclick="location.href='librarian.php'">Back</button>
    </div>  

        <style type="text/css">
        </style>
        <form action="addAuthor.php" method="post">

            <label>Author Name: </label><input type = "text"  name = "name" class = "box"/><span class="error"><?php echo  "</br>"; echo $emptyErr;?></span><br/><br />
            <label>Biography: </label><input type = "text"  name = "bio" class = "box" /><span class="error"></span><br/><br />
            <label>Genre: </label>
            <label for="c1"> Fantasy</label>
            <input type="checkbox" id= "c1" name='genre1' value="Fantasy" style="text-align: left; float: left; font-size: medium;">
            <label for="c1"> Mystery</label>
            <input type="checkbox" id= "c2" name='genre2' value="Mystery" style="text-align: left; float: left; font-size: medium;">
            <label for="c1"> Classic</label>
            <input type="checkbox" id= "c3" name='genre3' value="Classic" style="text-align: left; float: left; font-size: medium;">
            <label for="c1"> Action</label>
            <input type="checkbox" id= "c4" name='genre4' value="Action" style="text-align: left; float: left; font-size: medium;">
            <label for="c1"> Romance</label>
            <input type="checkbox" id= "c5" name='genre5' value="Romance" style="text-align: left; float: left; font-size: medium;"><br />

            
            <input type="submit" style="width:10%;  background-color:#ff7f50 ; padding: 10px;font-size: medium;" value="Add">
            </form>
        </form>
   
    </div>    
</body>
</p></center>
</html>