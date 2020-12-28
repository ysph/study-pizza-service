<?php
session_start();
if (!isset($_SESSION["firstName"])) {
    $check[] = "notLoggedIn";
} else {
    $out = json_decode(file_get_contents('php://input'), true);

    unset($_SESSION["pizzas_name"][$out["pizza_id"]]);
    unset($_SESSION["pizzas_price"][$out["pizza_id"]]);
    unset($_SESSION["pizzas_amount"][$out["pizza_id"]]);
    unset($_SESSION["pizzas_image"][$out["pizza_id"]]);
    $check[10] = "order_removed";
}

echo json_encode($check);