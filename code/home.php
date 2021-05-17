<?php
include("config.php");
session_start();

$userId = $_SESSION['login_user'];

if(isset($_POST['search'])){
    $title = $_POST['bytitle'];
    echo $title;
    $sql = "SELECT * FROM books natural join editions where books.title LIKE '%".$title."%'";
    $result = filtertable($conn, $sql);
}
else{
    $sql = "SELECT * FROM books natural join editions";
    $result = filtertable($conn, $sql);
}

function filterTable($conn, $query){
    $filter_Result = mysqli_query($conn,$query);
    return $filter_Result;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Login</title> 
    <link rel="stylesheet" href="style.css">
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
            <li><a href = "user.php?profile_id=<?php echo $userId ?>" >Profile</a></li>
            <li><a href = "userChallenges.php">Challenges</a></li>
            <style type="text/css">
             .wrapper ul li{  display: inline; padding: 1% }
    </style>

        </ul>
        <div class="main-text">

        <p class="login_text" >Welcome</p> 



    <form action = "home.php" method="post">
        <input type = "text" name= "bytitle" placeholder = "title"><br><br>
        <input type = "submit" name= "search" value= "Filter"><br><br>


        <table border="1" class="table_legenda" width="100%">
            <tr>
            <th><p style="font-family: Arial, Helvetica, sans-serif;;font-size:170%;padding: 10px 10px;  ">Title</p></th>
            <th><p style="font-family: Arial, Helvetica, sans-serif;;font-size:170%;padding: 10px 10px;  ">Page count</p></th>
            <th><p style="font-family: Arial, Helvetica, sans-serif;;font-size:170%;padding: 10px 10px;  ">Year</p></th>
            </tr>
    

    
            <?php
            if ($result != false && mysqli_num_rows($result) != 0) {
                while($row = $result->fetch_array(MYSQLI_ASSOC)){
                    $title=$row['title'];
                    $page_count =$row['page_count'];
                    $year =$row['year'];
                    ?>
                    <tr>
                        <?php
                    echo "<td> <p style='font-family: Arial, Helvetica, sans-serif;;font-size:170%;padding: 10px 10px;  '>";
                        echo "<a href = 'book.php?b_id=";
                        echo $row['b_id'];
                        echo "'>";
                        echo $title;
                        echo "</a>";
                        echo "</p>";
                        echo "</td>";
                    ?>
                    <td> <p style="font-family: Arial, Helvetica, sans-serif;;font-size:170% ;padding: 10px 10px; "><?php echo "$page_count"?> </p></td>
                    <td> <p style="font-family: Arial, Helvetica, sans-serif;;font-size:170%; padding: 10px 10px; "><?php echo "$year"?></p> </td>
                    </tr> 
            <?php 
                }
            }
            ?>

        </table>

        </form>
         </div> 
    </div>    
</body>
</p></center>
</html>