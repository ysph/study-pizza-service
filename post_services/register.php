<?php

session_start();

$out = json_decode(file_get_contents('php://input'), true);
include("../includes/connect_sql.php");

$sql = "INSERT INTO `pizza-hut`.users (name, is_driver, is_admin, number) VALUES ('".$out["firstName"]."', 0, 0, '".$out["mobile"]."')";
$result = mysqli_query($conn, $sql);

if($result) {
    $check[] = "Success";
    $check[] = $out["firstName"];
    $_SESSION["firstName"] = $out["firstName"];
    $_SESSION["number"] = $out["mobile"];
} else {
    $check[] = "Error";
}

echo json_encode($check);

include("../includes/disconnect_sql.php");