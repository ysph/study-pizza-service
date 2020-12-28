<?php
    session_start();
    if (isset($_SESSION["firstName"]) || isset($_SESSION["number"])) {
        $check[] = $_SESSION["firstName"];
        $check[] = $_SESSION["number"];
        $_SESSION = Array();
        session_destroy();

        $check[] = "Session destroyed";
    } else {
        $check[] = "Session var is not set";
    }

    $check[] = "Success";

    echo json_encode($check);