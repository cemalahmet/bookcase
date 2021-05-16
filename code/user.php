<!DOCTYPE html>
<?php

include("config.php");
session_start();
$user_id = $_SESSION['login_user'];


$profile_id = $_GET['profile_id'];


$user_info_query = "select * from users, accounts where user_id = '$profile_id' and account_id = user_id";
$user_infoQ = mysqli_query($conn, $user_info_query);
$user_info = mysqli_fetch_array($user_infoQ);

$rating_info_query = "select count(*) as no_ratings, avg(rating) as avg_ratings from rate
                            where '$profile_id' = user_id";
$rating_infoQ = mysqli_query($conn, $rating_info_query);
$rating_info = mysqli_fetch_array($rating_infoQ);

$bookshelves_query = "select bs_name from bookshelves where user_id = '$profile_id'";
$bookshelves = mysqli_query($conn, $bookshelves_query);

$lists_query = "select list_name from lists where user_id = '$profile_id'";
$lists = mysqli_query($conn, $lists_query);

$friends_query = "select username, account_id from friends f, accounts u where 
        (u.account_id = f.user_id and f.friend_id ='$profile_id')
        or
        (u.account_id = f.friend_id and f.user_id='$profile_id')";
$friends = mysqli_query($conn, $friends_query);

$authors_query = "select A.author_name from authors as A, follows as F where F.user_id = '$profile_id' and F.author_id = A.author_id";
$authors = mysqli_query($conn, $authors_query);

function add_friend($conn, $user_id, $friend_id) {
    $query = "select * from friends where (user_id = '$user_id' and '$friend_id' = friend_id) or (friend_id = '$user_id' and '$friend_id' = user_id)";
    $already_friends = mysqli_query($conn, $query);
    if (mysqli_num_rows($already_friends) == 0) {
      $query = "insert into friends values('$user_id', '$friend_id')";
      mysqli_query($conn, $query);
    } else {
      $query = "delete from friends where (user_id = '$user_id' and '$friend_id' = friend_id) or (friend_id = '$user_id' and '$friend_id' = user_id)";
      mysqli_query($conn, $query);
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
  add_friend($conn, $user_id, $profile_id);

    $friends_query = "select username from friends, account where (user_id = '$profile_id' and friend_id = account_id) or (friend_id = '$profile_id' and user_id = account_id)";
    $friends = mysqli_query($conn, $friends_query);
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> User Profile </title>
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

        <p class="login_text" > Name: <?php echo $user_info["username"];?></p>
        <p class="login_text" > Join Date: <?php echo $user_info["join_date"];?></p>
          <?php
          if ($user_id != $profile_id) {
            echo "<form action='user.php' method='post'>";

            echo "<input type='submit' class='btn btn-primary' style='width:100%;
          background-color:#ff7f50 ; padding: 10px;font-size: medium;' value = ";

              $query = "select * from friends where (user_id = '$user_id' and '$profile_id' = friend_id) or (friend_id = '$user_id' and '$profile_id' = user_id)";
              $already_friends = mysqli_query($conn, $query);
            if (mysqli_num_rows($already_friends) == 0) {
                  echo "add_friend>";
            } else {
                echo "remove_friend>";
            }

          echo "</form>";
        }
        echo "<p>";
        echo $rating_info['no_ratings'];
        echo "ratings. </p>";
        echo "<p> avg rating: ";
        if ($rating_info['no_ratings'] == 0) {
          echo "NA";
        } else {
          echo $rating_info['avg_ratings'];
        }
        echo "</p>";
        ?>
            <p class="login_text"> Friends: </p>
            <?php
            echo "<p>";
            if ($friends != false)
                echo mysqli_num_rows($friends);
            else
                echo "0";
            echo " friends";
            echo "</p>";
            echo "<ul>";
            if ($friends != false and mysqli_num_rows($friends) != 0) {

                while($row = $friends->fetch_array(MYSQLI_ASSOC)){
                    echo "<li> <a href = 'user.php?profile_id=";
                    echo $row['account_id'];
                    echo "'>";
                    echo $row['username'];
                    echo "</a></li>";
                }
            }
            echo "</ul>";
            ?>
            <p class="login_text"> Followed Authors: </p>
            <?php
            echo "<p>";
            if ($authors != false)
                echo mysqli_num_rows($authors);
            else
                echo "0";
            echo " followed authors";
            echo "</p>";
            echo "<ul>";

            if ($authors != false and mysqli_num_rows($authors) != 0) {

                while($row = $authors->fetch_array(MYSQLI_ASSOC)){
                    echo "<li> <a href = 'author.php?author_id=";
                    echo $row['author_id'];
                    echo "'>";
                    echo $row['author_name'];
                    echo "</a></li>";
                }
            }
            echo "</ul>";
            ?>
            <p class="login_text" > Bookshelves</p>
        <table border = "2">
            <tr><th> bookshelf </th> <th> count </th></tr>
            <?php
            if ($bookshelves != false) {
                while ($row = $bookshelves->fetch_array(MYSQLI_ASSOC)) {
                    echo "<tr>";
                    $name = $row['bs_name'];
                    echo "<td>" . $name . "</td>";
                    $query = "select count(*) as cnt from bookshelf_includes where bs_name = '$name' and user_id = '$profile_id'";
                    $cntQ = mysqli_query($conn, $query);
                    $cnt = $cntQ->fetch_array(MYSQLI_ASSOC);
                    if ($cnt == false) {
                        echo "<td> 0 </td>";
                    } else {
                        echo "<td>" . $cnt['cnt'] . "</td>";
                    }
                    echo "</tr>";
                }
            }
            ?>
        </table>
            <p class="login_text" > Lists </p>
            <table border = "2">
                <tr><th> list </th></tr>
                <?php
                if ($lists != false) {
                    while ($row = $lists->fetch_array(MYSQLI_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . $row['list_name'] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
      </div>

    </div>
</body>
</p></center>
</html>