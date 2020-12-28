<?php
session_start();
if (!isset($_SESSION["firstName"])) {
    $check[] = "notLoggedIn";
} else {
    $out = json_decode(file_get_contents('php://input'), true);

    $names = implode("|", $_SESSION["pizzas_name"]);
    $prices = array();
    foreach ($_SESSION["pizzas_price"] as $key => $value) {
        array_push($prices, $_SESSION["pizzas_amount"][$key] * $value);
    }
    $prices = implode("|", $prices);

    include("../includes/connect_sql.php");
    $check[] = $prices;
    $sql = "SELECT t.* FROM `pizza-hut`.users t WHERE number = " . $out['user_number'];
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $user_number = $row['user_id'];
        }
    }
    $sql = "INSERT INTO `pizza-hut`.orders (name,price,status,user_id) VALUES ('".$names."','".$prices."','готовится','".$user_number."')";
    $result = mysqli_query($conn, $sql);
    include("../includes/disconnect_sql.php");

    $check[10] = "order_has_added";

    unset($_SESSION["pizzas_name"]);
    unset($_SESSION["pizzas_price"]);
    unset($_SESSION["pizzas_amount"]);
    unset($_SESSION["pizzas_image"]);
}

echo json_encode($check);