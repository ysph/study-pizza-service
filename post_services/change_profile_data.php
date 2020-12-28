<?php
session_start();
if (!isset($_SESSION["firstName"])) {
    $check[] = "notLoggedIn";
} else {
    $out = json_decode(file_get_contents('php://input'), true);

    include("../includes/connect_sql.php");
    $sql = "UPDATE `pizza-hut`.users SET ".$out["type"]." = '".$out['data']."' WHERE user_id = ".$out['user_id'].";";
    $result = mysqli_query($conn, $sql);
    $check[] = $sql;
    if ($result) {
        $check[10] = "Success";
    } else {
        $check[10] = "Error";
    }

    include("../includes/disconnect_sql.php");
}

echo json_encode($check);