<!DOCTYPE html>
<?php

include("config.php");
session_start();
$user_id = $_SESSION['login_user'];


$profile_id = $_GET['profile_id'];

if ($profile_id == "")
    $profile_id = $_SESSION['profile_id'];


$user_info_query = "select * from users, accounts where user_id = '$profile_id' and account_id = user_id";
$user_infoQ = mysqli_query($conn, $user_info_query);
$user_info = mysqli_fetch_array($user_infoQ);

$rating_info_query = "select count(*) as no_ratings, avg(rating) as avg_ratings from rate
                            where '$profile_id' = user_id";
$rating_infoQ = mysqli_query($conn, $rating_info_query);
$rating_info = mysqli_fetch_array($rating_infoQ);

$bookshelves_query = "select bs_name from bookshelves where user_id = '$profile_id'";
$bookshelves = mysqli_query($conn, $bookshelves_query);

$lists_query = "select list_name, user_id from lists where user_id = '$profile_id'";
$lists = mysqli_query($conn, $lists_query);

$friends_query = "select distinct username, account_id from friends f, accounts u where 
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

function create_list($conn, $user_id, $list_name) {
    $query = "insert into lists values ('$list_name', '$user_id')";
    mysqli_query($conn, $query);
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['profile_id'] = $profile_id;
    if (isset($_POST['add_friend'])) {
        add_friend($conn, $user_id, $profile_id);
    }
    if (isset($_POST['create_list'])) {
        $list_name = $_POST['list_name'];
        create_list($conn, $user_id, $list_name);
    }

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

            echo "<input type='submit' name='add_friend' class='btn btn-primary' style='width:100%;
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
                    echo "<td>";
                    echo "<a href = 'bookshelf.php?bs_name=";
                    echo $row['bs_name'];
                    echo "&user_id=";
                    echo $profile_id;
                    echo "'>";
                    echo $row['bs_name'];
                    echo "</a>";
                    echo "</td>";
                    $bs_name = $row['bs_name'];
                    $query = "select count(*) as cnt from bookshelf_includes where bs_name = '$bs_name' and user_id = '$profile_id'";
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
            <?php

            if ($user_id == $profile_id) {
                echo "<form action = 'user.php' method = 'post' >
                <input type = 'text' placeholder = 'list name' name = 'list_name' class = 'box' />
                <input type = 'submit' name = 'create_list' class='btn btn-primary' style = 'width:100%;  background-color:#1e90ff ; padding: 10px;font-size: medium;' value = 'Create List' >
                </form >";
            }
            ?>
            <p class="login_text" > Lists </p>
            <table border = "3">
                <tr><th> list </th></tr>
                <?php
                if ($lists != false) {
                    while ($row = $lists->fetch_array(MYSQLI_ASSOC)) {
                        echo "<tr>";
                        echo "<td>";
                        echo "<a href = 'list.php?list_name=";
                        echo $row['list_name'];
                        echo "&user_id=";
                        echo $row['user_id'];
                        echo "'>";
                        echo $row['list_name'];
                        echo "</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>

            <?php

    $sql = "SELECT challange_name, Info, due_date FROM challenges where challange_name  in (select challange_name from joins where user_id = $user_id);";
    $result = mysqli_query($conn,$sql);
    ?>

    <p class="login_text" > Joined Challenges </p>


    <table border="1" class="table_legenda" width="100%">
     <tr>
    <th><p style="font-family: Arial, Helvetica, sans-serif;;font-size:170%;padding: 10px 10px;  ">Challenge Name</p></th>
    <th><p style="font-family: Arial, Helvetica, sans-serif;;font-size:170%;padding: 10px 10px;  ">Challenge Info</p></th>
    <th><p style="font-family: Arial, Helvetica, sans-serif;;font-size:170%;padding: 10px 10px;  ">Due Date</p></th>
    </tr>
    

    
    <?php
    if ($result_set = $conn->query($sql)) {
        while($row = $result_set->fetch_array(MYSQLI_ASSOC)){
            $challange_name=$row['challange_name'];
            $Info=$row['Info'];
            $due_date=$row['due_date'];
            ?>
            <tr>
            <td> <p style="font-family: Arial, Helvetica, sans-serif;;font-size:170%;padding: 10px 10px;  ">  <?php echo "$challange_name"?>  </p></td>
            <td> <p style="font-family: Arial, Helvetica, sans-serif;;font-size:170% ;padding: 10px 10px; "><?php echo "$Info"?> </p></td>
            <td> <p style="font-family: Arial, Helvetica, sans-serif;;font-size:170%; padding: 10px 10px; "><?php echo "$due_date"?></p> </td>
            </tr> 
        <?php 

        }
    }
    ?>
   

    </table>

      </div>

    </div>
</body>
</p></center>
</html>