<!DOCTYPE html>
<?php
include("config.php");
session_start();

$author_id = $_GET['author_id'];
$user_id = $_SESSION['login_user'];

$author_info_query = "SELECT author_name, biography
                          FROM authors
                              WHERE '$author_id' = author_id";
$author_infoQ = mysqli_query( $conn, $author_info_query );
$author_info = mysqli_fetch_array($author_infoQ);

$genre_query = "SELECT genre
                      FROM author_genre
                          WHERE '$author_id' = author_id";
$genres = mysqli_query( $conn, $genre_query);

$book_info_query = "SELECT books.title, bboks.year
  FROM books NATURAL JOIN writes WHERE writes.author_id = '$author_id'";
$book_info = mysqli_query( $conn, $book_info_query );

function follow($conn, $user_id, $author_id) {
    $query = "select * from follows where user_id = '$user_id' and author_id = '$author_id'";
    $already_followed = mysqli_query( $conn, $query);
    if ($already_followed == false or mysqli_num_rows($already_followed) == 0) {
        $query = "insert into follows values ('$user_id', '$author_id')";
        mysqli_query( $conn, $query);
    } else {
        $query = "delete from follows where user_id = '$user_id' and author_id = '$author_id'";
        mysqli_query( $conn, $query);
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    follow($conn, $user_id, $author_id);
    header("location: author.php");
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Author <?php echo $author_id; echo ": "; $author_info["author_name"];?> </title>
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

        <p class="login_text" > Name: <?php echo $author_info["author_name"];?></p>
        <form action="author.php" method="post">
          <input type="submit" class="btn btn-primary" style="width:100%;
          background-color:#ff7f50 ; padding: 10px;font-size: medium;" value =
          <?php $query = "select * from follows where user_id = '$user_id' and author_id = '$author_id'";
                $already_followed = mysqli_query( $conn, $query);
                if (mysqli_num_rows($already_followed) == 0) {
                  echo "follow";
                } else {
                  echo "unfollow";
                } ?>
            >
        </form>
        <p class="login_text" > Biography: </p>
        <p> <?php echo $author_info["biography"];?></p>
        <p class="login_text"> Genres: </p>
        <?php
        if (mysqli_num_rows($genres) == 0)
          echo "no genres";
        else {
          $row = $genres->fetch_array(MYSQLI_ASSOC);

          echo $row['genre'];
          while($row = $genres->fetch_array(MYSQLI_ASSOC)){
                  echo ", ";
                  echo $row['genre'];
          }
        }
        ?>
        <p class="login_text" > Books:</p>
        <table border="2">
        <tr>
        <th>Book Name</th>
        <th>Year</th></tr>

        <?php
        if ($row != false and mysqli_num_rows($book_info) != 0) {
            while ($row = $book_info->fetch_array(MYSQLI_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['title'] . "</td>";
                echo "<td>" . $row['year'] . "</td>";
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
