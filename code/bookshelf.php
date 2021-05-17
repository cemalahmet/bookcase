<!DOCTYPE html>

<?php
include("config.php");
session_start();

$bs_name = $_GET['bs_name'];;
$owner_id = $_GET['user_id'];

$user_id = $_SESSION['login_user'];

$books_info_query = "select b_id, title, year
                    from books natural join bookshelf_includes
                        where bs_name = '$bs_name' and user_id = '$owner_id'";
$book_info = mysqli_query( $conn, $books_info_query);


if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_remove_book'])) {
        $title = $_POST['book_title'];
        $query = "select * from bookshelf_includes natural join books where bs_name = '$bs_name' and 
                                                     user_id = '$owner_id' and title = '$title'";
        $exists = mysqli_query( $conn, $query);
        if ($exists->num_rows() == 0) {
            $query = "select * from books where title = '$title'";
            $bookQ = mysqli_query( $conn, $query);
            if ($bookQ != false and $bookQ->num_rows() != 0) {
                $book = $bookQ->fetch_array(MYSQLI_ASSOC);
                $b_id = $book['b_id'];
                $query = "insert into bookshelf_includes values ('$bs_name', '$user_id', '$b_id')";
                mysqli_query( $conn, $query);
            }
        } else {
            $b_id = $exists['b_id'];
            $query = "delete from list_includes where b_id = '$b_id' and bs_name = '$bs_name' and 
                                                     user_id = '$owner_id'";
            mysqli_query( $conn, $query);
        }
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Bookshelf <?php echo "$bs_name";?> </title>
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
            <p class="login_text" > Name: <?php echo $bs_name;?></p>
            <p class="login_text" > Books:</p>
            <table border="2">
                <tr>
                    <th> no </th>
                    <th> Book Name </th>
                    <th> Year </th></tr>
                <?php
                if ($user_id == $owner_id) {
                    echo "<form action='bookshelf.php' method='post'>
                    <input type = 'text' placeholder = 'book title' name = 'book_title' class = 'box' />
                    <input type='submit' name = 'delete_list' class='btn btn-primary' style= 'width:100%;
                     background-color:#ff7f50 ; padding: 10px;font-size: medium;' value = 'add/remove book'>
                    </form>";
                }
                ?>
                <?php
                $i = 1;
                if ($book_info != false) {
                    while ($row = $book_info->fetch_array(MYSQLI_ASSOC)) {
                        echo "<tr>";
                        echo "<td>";
                        echo $i;
                        echo "<td>";
                        echo "<td>";
                        echo "<a href = 'book.php?b_id=";
                        echo $row['b_id'];
                        echo "'>";
                        echo $row['title'];
                        echo "</a>";
                        echo "</td>";
                        echo "<td>" . $row['year'] . "</td>";
                        echo "</tr>";
                        $i = $i + 1;
                    }
                }
                ?>
            </table>
            <p> <br> <br> <br> </p>
            <?php
            if ($user_id == $owner_id) {
            echo "<form action='bookshelf.php' method='post'>
                <input type='submit' name = 'delete_list 'class='btn btn-primary' style='width:100%;
          background-color:#ff7f50 ; padding: 10px;font-size: medium;' value = 'delete list'>
            </form>";
            }
            ?>
        </div>
    </div>

    </body>
    </p></center>
</html>