<?php
include("config.php");
session_start();

$bookTitle = "";
$bookid = "";
$publishedYear = "";
$pageCount = "";
$author = "";
$edition = "";
$language = "";
$translator = "";
$series = "";
$genre1 = "Fantasy";
$genre2 = "Mystery";
$genre3 = "Classic";
$genre4 = "Action";
$genre5 = "Romance";

$emptyErr = "";


if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (empty($_POST['bookTitle']) || empty($_POST['publishedYear']) || empty($_POST['pageCount']) || empty($_POST['author']) ) {
        $emptyErr = "Something is missing!";
    }
    else{
        $publishedYear = $_POST['publishedYear'];
        $bookTitle = $_POST['bookTitle'];
        $pageCount = $_POST['pageCount'];
        $author = $_POST['author'];
        $edition = $_POST['edition'];
        $language = $_POST['language'];
        $translator = $_POST['translator'];
        $cover = $_POST['cover'];
        $series = $_POST['series'];

        $query = "SELECT title, year FROM books WHERE year = '$publishedYear'  AND title = '$bookTitle'";
        $result1 = mysqli_query( $conn, $query );

        $query = "SELECT edition_no FROM editions WHERE edition_no = '$edition'";
        $result2 = mysqli_query( $conn, $query );

        if(mysqli_num_rows($result1)==1 && mysqli_num_rows($result2)==1){
            $emptyErr = "This book already registered in the system!";
        }
        else if(mysqli_num_rows($result)==0){
            $row = $result->fetch_assoc();
            $bookid = $row['b_id'];
            $_SESSION['book_id'] = $bookid;

            $query = "INSERT INTO books (b_id, cover, title, year) 
                    VALUES ('$bookid', '$cover', '$bookTitle', '$publishedYear')";
            $results = mysqli_query($conn, $query);
            
            // EDITION INSERTION

            $query = "INSERT INTO editions (b_id, edition_no, page_count, language, translator_name) 
                    VALUES ('$bookid', '$edition', '$pageCount', '$language', '$translator')";
            $results = mysqli_query($conn, $query);

            // GENRE INSERTION

            if(!empty($_POST['genre1'])){
                $query = "INSERT INTO book_genre (b_id, genre) 
                        VALUES ('$bookid', '$genre1')";
                $results = mysqli_query($conn, $query);
            }
            if(!empty($_POST['genre2'])){
                $query = "INSERT INTO book_genre (b_id, genre) 
                        VALUES ('$bookid', '$genre2')";
                $results = mysqli_query($conn, $query);
            }
            if(!empty($_POST['genre3'])){
                $query = "INSERT INTO book_genre (b_id, genre) 
                        VALUES ('$bookid', '$genre3')";
                $results = mysqli_query($conn, $query);
            }
            if(!empty($_POST['genre4'])){
                $query = "INSERT INTO book_genre (b_id, genre) 
                        VALUES ('$bookid', '$genre4')";
                $results = mysqli_query($conn, $query);
            }
            if(!empty($_POST['genre5'])){
                $query = "INSERT INTO book_genre (b_id, genre) 
                        VALUES ('$bookid', '$genre5')";
                $results = mysqli_query($conn, $query);
            }

            // SERIES INSERTION

            if(!empty($_POST['series'])){
                $query = "SELECT s_name FROM series WHERE s_name = '$series'";
                $result = mysqli_query( $conn, $query );
                if (mysqli_num_rows($result) == 0) {
                    $query = "INSERT INTO series (s_name) 
                            VALUES ('$series')";
                    $results = mysqli_query($conn, $query);
                }
                $query = "INSERT INTO part_of_series (s_name, b_id) 
                            VALUES ('$series', '$bookid')";
                $results = mysqli_query($conn, $query);
            }

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
        <form action="add_Book.php" method="post">
            <label>Cover page: </label>
            <form action="/action_page.php">
                <input type="file" id="myFile" name="cover" style="text-align: left; float: left; font-size: medium;"><br/><br />
            </form>
            <label>Book Title: </label><input type = "text" placeholder = "book title" name = "bookTitle" class = "box"/><span class="error"><?php echo  "</br>"; echo $emptyErr;?></span><br/><br />
            <label>Published Year: </label><input type = "text" placeholder = "published year" name = "publishedYear" class = "box" /><span class="error"><?php echo  "</br>"; echo $emptyErr;?></span><br/><br />
            <label>Page Count: </label><input type = "text" placeholder = "count" name = "pageCount" class = "box" /><span class="error"><?php echo  "</br>"; echo $emptyErr;?></span><br/><br />
            <label>Author: </label><input type = "text" placeholder = "author" name = "author" class = "box" /><span class="error"><?php echo  "</br>"; echo $emptyErr;?></span><br/><br />
            <label>Edition no: </label><input type = "text" placeholder = "edition" name = "edition" class = "box" /><br/><br />
            <label>Language: </label><input type = "text" placeholder = "language" name = "language" class = "box" /><br/><br />
            <label>Translator Name: </label><input type = "text" placeholder = "translator" name = "translator" class = "box" /><br/><br />
            <label>Part of the series (optional): </label><input type = "text" placeholder = "series name" name = "series" class = "box" /><br/><br />
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
            <input type="checkbox" id= "c5" name='genre5' value="Romance" style="text-align: left; float: left; font-size: medium;">

            <span class="error"> <?php echo  "</br>"; echo $emptyErr;?></span><br/><br />
            <input type="submit" class="btn btn-primary" style="width:10%;  background-color:#ff7f50 ; padding: 10px;font-size: medium;" value="Add">
          
        </form>
   
    </div>    
</body>
</p></center>
</html>