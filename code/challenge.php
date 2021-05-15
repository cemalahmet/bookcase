<?php
include("config.php");
$challenge_name = "";

$challenge_info_query = "select info
                            from challenges
                                where '$challenge_name' = challenge_name";
$challenge_infoQ = mysqli_query( $conn, $challenge_info_query );
$challenge_info = mysqli_fetch_array($challenge_infoQ);

$participants_info_query = "select accounts.username as participants, joins.goal as goals
                                from joins, accounts
                                where joins.user_id = accounts.account_id and joins.challange_name = '$challenge_name'";
$participants_info = mysqli_query( $conn, $participants_info_query);


function join_challenge($conn, $challenge_name, $user_id, $goal){
    $query = "select * from joins where challenge_name = '$challenge_name' and user_id = '$user_id'";
    $already_joined= mysqli_query( $conn, $query);
    if (mysqli_num_rows($already_joined) == 0) {
        $query = "insert into joins values ('$challenge_name', '$user_id', '$goal')";
        mysqli_query( $conn, $query);
    }
}
