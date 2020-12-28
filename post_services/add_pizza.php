<?php

session_start();

if (!isset($_SESSION["firstName"])) {
    $check[] = "notLoggedIn";
} else {
    $out = json_decode(file_get_contents('php://input'), true);
    if (!isset($_SESSION["pizzas_name"])) {
        $_SESSION["pizzas_name"] = array();
    }
    if (!isset($_SESSION["pizzas_price"])) {
        $_SESSION["pizzas_price"] = array();
    }
    if (!isset($_SESSION["pizzas_amount"])) {
        $_SESSION["pizzas_amount"] = array();
    }
    if (!isset($_SESSION["pizzas_image"])) {
        $_SESSION["pizzas_amount"] = array();
    }
    //set variables
    if (!isset($_SESSION["pizzas_name"][$out["pizza_id"]])) {
        $_SESSION["pizzas_name"][$out["pizza_id"]] = $out["pizza_name"];
    }
    if (!isset($_SESSION["pizzas_price"][$out["pizza_id"]])) {
        $_SESSION["pizzas_price"][$out["pizza_id"]] = $out["pizza_price"];
    }
    if (!isset($_SESSION["pizzas_amount"][$out["pizza_id"]])) {
        $_SESSION["pizzas_amount"][$out["pizza_id"]] = 1;
    } else {
        $_SESSION["pizzas_amount"][$out["pizza_id"]] += 1;
    }
    if (!isset($_SESSION["pizzas_image"][$out["pizza_id"]])) {
        $_SESSION["pizzas_image"][$out["pizza_id"]] = $out["pizza_image"];
    }
    /*
    unset($_SESSION["pizzas_name"]);
    unset($_SESSION["pizzas_price"]);
    unset($_SESSION["pizzas_amount"]);
    unset($_SESSION["pizzas_image"]);*/
    $check[] = $_SESSION["pizzas_name"];
    $check[] = $_SESSION["pizzas_price"];
    $check[] = $_SESSION["pizzas_amount"];

    $check[10] = "order_is_added";
}

echo json_encode($check);