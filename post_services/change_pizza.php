<?php

session_start();
if (!isset($_SESSION["firstName"])) {
    $check[] = "notLoggedIn";
} else {
    $out = json_decode(file_get_contents('php://input'), true);

    if ($_SESSION["pizzas_amount"][$out["pizza_id"]] <= 1 && $out["change_type"] == 'dec') {
        unset($_SESSION["pizzas_name"][$out["pizza_id"]]);
        unset($_SESSION["pizzas_price"][$out["pizza_id"]]);
        unset($_SESSION["pizzas_amount"][$out["pizza_id"]]);
        unset($_SESSION["pizzas_image"][$out["pizza_id"]]);
    } else {
        if ($out["change_type"] == 'dec') {
            $_SESSION["pizzas_amount"][$out["pizza_id"]] -= 1;
        }
        if ($out["change_type"] == 'inc') {
            $_SESSION["pizzas_amount"][$out["pizza_id"]] += 1;
        }
    }
    $check[] = $_SESSION["pizzas_amount"][$out["pizza_id"]];
    $check[10] = "order_changed";
}

echo json_encode($check);