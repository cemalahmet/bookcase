<?php
include("config.php");
$author_id = "";

$author_info_query = "SELECT author_name, biography
                        FROM authors
                            WHERE '$author_id' = author_id";
$author_infoQ = mysqli_query( $conn, $author_info_query );
$author_info = mysqli_fetch_array($author_infoQ);

$genre_query = "SELECT genre
                    FROM Author-genre
                        WHERE '$author_id' = author_id";
$genres = mysqli_query( $conn, $genre_query);

$book_info_query = "SELECT books.title, bboks.year
FROM books NATURAL JOIN writes WHERE writes.author_id = '$author_id'";
$book_info = mysqli_query( $conn, $book_info_query );

function follow($conn, $user_id, $author_id){
    $query = "select * from follows where user_id = '$user_id' and author_id = '$author_id'";
    $already_followed= mysqli_query( $conn, $query);
    if (mysqli_num_rows($already_followed) == 0) {
        $query = "insert into follows values ('$user_id', '$author_id')";
        mysqli_query( $conn, $query);
    } else {
        $query = "delete from follows where user_id = '$user_id' and author_id = '$author_id'";
        mysqli_query( $conn, $query);
    }
}