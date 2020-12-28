<?php
    session_start();
    $out = json_decode(file_get_contents('php://input'), true);
    $_SESSION["firstName"] = $out["username"];
    $_SESSION["number"] = $out["number"];
    $_SESSION["sqlnumber"] = (int)$out["sqlnumber"];

    include("../includes/connect_sql.php");

    $sql = "SELECT t.* FROM `pizza-hut`.users t where number = " . $_SESSION["sqlnumber"];
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $_SESSION["is_driver"] = $row["is_driver"];
            $_SESSION["is_admin"] = $row["is_admin"];
            break;
        }
    }
    include("../includes/disconnect_sql.php");
    $check[] =  $_SESSION["is_driver"];
    $check[] =  $_SESSION["is_admin"];
    $check[] = $_SESSION["firstName"];
    $check[] = $_SESSION["number"];
    $check[] = $_SESSION["sqlnumber"];
    $check[] = "Success";

    echo json_encode($check);
