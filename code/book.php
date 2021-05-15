<?php
include("config.php");
session_start();

$b_id = "";
$edition_no = "";
$user_id = "";

    $book_info_query =  "SELECT * FROM books NATURAL JOIN editions NATURAL JOIN publishes NATURAL JOIN publishers 
                                WHERE '$b_id' = b_id AND '$edition_no' = edition_no";
    $book_infoQ = mysqli_query( $conn, $book_info_query );
    $book_info = mysqli_fetch_array($book_infoQ);

    $rating_query = "select avg(rating) from rate where '$b_id' = b_id";
    $ratingQ = mysqli_query( $conn, $rating_query );
    $rating = mysqli_fetch_array($ratingQ);

    $genres_query = "select genre from book_genre where '$b_id' = b_id";
    $genres = mysqli_query( $conn, $genres_query);

    $comments_query = "with like-count(comment-id, cnt) as 
                            (select comment-id, count(*) from comments natural join likes group by comment-id) 
                        select comment, cnt from comments natural join comment_on natural join like-count where '$b_id' = b-id";
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> <?php echo $book_info["title"]; ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 25px sans-serif; }
        .wrapper{width: 480px; height: 480px ; padding: 30px; }
    </style>
</head>

</html>
