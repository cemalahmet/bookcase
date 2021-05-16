<!DOCTYPE html>
<?php
include("config.php");
session_start();

$b_id = "";
$edition_no = "";
$user_id = $_SESSION['login_user'];

    $book_info_query =  "SELECT * FROM books NATURAL JOIN editions NATURAL JOIN publishes NATURAL JOIN publishers
                                WHERE '$b_id' = b_id AND '$edition_no' = edition_no";
    $book_infoQ = mysqli_query( $conn, $book_info_query );
    $book_info = mysqli_fetch_array($book_infoQ);

    $rating_query = "select avg(rating) as rating, count(*) as cnt from rate where '$b_id' = b_id";
    $ratingQ = mysqli_query( $conn, $rating_query );
    $rating = mysqli_fetch_array($ratingQ);

    $genres_query = "select genre from book_genre where '$b_id' = b_id";
    $genres = mysqli_query( $conn, $genres_query);

    $comments_query = "select comment_id, comment from comments natural join comment_on  where '$b_id' = b-id";
    $comments = mysqli_query( $conn, $comments_query );

    function like_comment($conn, $user_id, $comment_id)  {
        $query = "select comment_id from likes where comment_id = '$comment_id' and user_id = '$user_id'";
        $already_liked = mysqli_query( $conn, $query);
        if (mysqli_num_rows($already_liked) == 0) {
            $query = "insert into likes values ('$comment_id', '$user_id')";
            mysqli_query( $conn, $query);
        } else {
            $query = "delete from likes where comment_id = '$comment_id' and user_id = '$user_id'";
            mysqli_query( $conn, $query);
        }
    }

    function rate_book($conn, $b_id, $user_id, $rating) {
        $query = "select * from rate where b_id = '$b_id' and user_id = '$user_id'";
        $already_rated = mysqli_query( $conn, $query);
        if (mysqli_num_rows($already_rated) == 0) {
            $query = "insert into rate values ('$b_id', '$user_id', '$rating')";
            mysqli_query( $conn, $query);
        } else {
            $query = "update rate set rating = '$rating' where b_id = '$b_id' and user_id = '$user_id'";
            mysqli_query( $conn, $query);
        }
    }

    function add_to_list($conn, $b_id, $list_name, $user_id) {
        $query = "insert into list_includes values ('$list_name', '$user_id', '$b_id')";
        mysqli_query( $conn, $query);
    }

    function make_comment($conn, $b_id, $user_id, $comment, $date) {
        $query = "insert into comments values ('$comment', '$date')";
        mysqli_query($conn, $query);

        $query = "select comment_id from comments where comments.date = '$date' and comment = '$comment'";
        $result = mysqli_query($conn, $query);
        $row = $result->fetch_assoc();
        $comment_id = $row['comment_id'];

        $query = "insert into comment_on values ('$b_id', '$comment_id', '$user_id')";
        mysqli_query($conn, $query);
    }

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['make_comment'])) {
        $comment = $_POST["comment"];
        $newDate = date('Y-m-d H:i:s');
        make_comment($comment, $b_id, $user_id, $comment, $newDate);
    }
    if (isset($_POST['rate'])) {
        $rating = $_POST['rating'];
        rate_book($conn, $b_id, $user_id, $rating);
    }
    if (isset($_post['like'])) {
        $comment_id = $_POST['comment_id'];
        like_comment($conn, $user_id, $comment_id);
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Book Page?> </title>
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
            <li><a href = "user.php?profile_id=<?php echo $user_id ?>" >Profile</a></li>
            <li><a href = "home.php">Home</a></li>
            <style type="text/css">
                .wrapper ul li {  display: inline; padding: 1% }
            </style>

        </ul>
        <div class="main-text">

            <p class="login_text">
                <?php
                    echo $book_info["title"];
                    echo " (";
                    echo $book_info["year"];
                    echo ")"
                ?> </p>
            <p class="login_text"> Genres: </p>
            <p>
            <?php
            if ($genres == false or mysqli_num_rows($genres) == 0)
                echo "no genres";
            else {
                $row = $genres->fetch_array(MYSQLI_ASSOC);

                echo $row['genre'];
                while($row = $genres->fetch_array(MYSQLI_ASSOC)) {
                    echo ", ";
                    echo $row['genre'];
                }
            }
            ?> </p>
            <p> <?php
                echo "Avg Rating : ";
                if ($rating == false or mysqli_num_rows($rating)) {
                    echo "0 from 0 ratings";
                } else {
                    echo $rating['rating'];
                    echo " from ";
                    echo $rating['cnt'];
                    echo " ratings";
                }
            ?>
            </p>
            <label>Rate book </label>
            <form action='book.php' method='post'>
                <input type = "text" placeholder = "rating" name = "rating" class = "box"/>
                <input type= "submit" name="rate" class="btn btn-primary" style="width:100%;  background-color:#1e90ff ; padding: 10px;font-size: medium;" value="Login">
            </form>

            <label>Comment </label>
            <form action='book.php' method='post'>
            <input type = "text" placeholder = "comment" name = "comment" class = "box"/>
            <input type="submit" name="make_comment" class="btn btn-primary" style="width:100%;  background-color:#1e90ff ; padding: 10px;font-size: medium;" value="Login">
            </form>

            <p class="login_text"> Comments: </p>
            <?php
            while($row = $comments->fetch_array(MYSQLI_ASSOC)) {
                echo "<p>";
                echo $row['comment'];
                echo "</p>";
                echo "<p>";
                $comment_id = $row["comment_id"];
                $query = "select count(*) as cnt from likes where comment_id = '$comment_id'";
                $like_count = mysqli_query($conn, $query);
                if ($like_count == false or mysqli_num_rows($like_count) == 0)
                    echo "0 likes ";
                else {
                    $likes = $like_count->fetch_array(MYSQLI_ASSOC);
                    echo $likes;
                    echo " likes ";
                }

                echo "<form action='book.php' method='post'>";
                $_POST["comment_id"] = $comment_id;
                echo "<input type='submit' class='btn btn-primary' name = 'like' value =";

                $query = "select * from likes where user_id = '$user_id' and comment_id = '$comment_id'";
                $already_followed = mysqli_query( $conn, $query);

                if ($already_followed == false or mysqli_num_rows($already_followed) == 0)
                    echo "unlike>";
                else
                    echo "like>";

                echo "</form>";
                echo "</p>";
            }
            ?>
        </div>
    </div>
    </body>
    </p></center>
</html>