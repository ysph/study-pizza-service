<?php
session_start();

if (!isset($_SESSION["firstName"])) {
    $check[] = "notLoggedIn";
} else {
    $check[] = "logged";
    $check[] = $_SESSION["is_admin"];
    $check[] = $_SESSION["is_driver"];
}

echo json_encode($check);