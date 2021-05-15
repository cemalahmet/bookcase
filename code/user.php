<?php

include("config.php");
session_start();
$user_id = "ayseghjk";
$pwd = "21000004";

$user_info_query = "select * from users where user_id = '$user_id'";
$user_infoQ = mysqli_query($conn, $user_info_query);
$user_info = mysqli_fetch_array($user_infoQ);

$rating_info_query = "select count(*) as no_ratings, avg(rating) as avg_ratings from rate
                            where '$user_id' = user_id";
$rating_infoQ = mysqli_query($conn, $rating_info_query);
$rating_info = mysqli_fetch_array($rating_infoQ);

$bookshelves_query = "select (bs_name, count(*)) from bookshelves, bookshelf_include where user_id = '$user_id' group by bs_name";
$bookshelves = mysqli_query($conn, $bookshelves_query);

$lists_query = "select list_name from lists where user_id = '$user_id'";
$lists = mysqli_query($conn, $bookshelves_query);

$friends_query = "select username from friends, accounts where (user_id = '$user_id'and account_id= friend_id) or (friend_id = '$user_id' and account_id = user_id)";
$friends = mysqli_query($conn, $bookshelves_query);

$authors_query = "select A.author_name from authors as A, follows as F where F.user_id = '$user_id' and F.author_id = A.author_id";
$authors = mysqli_query($conn, $authors_query);

function add_friend() {
    $query = "insert into Friends values(@user-id, @friend-id)";
    mysqli_query($conn, $query);
}