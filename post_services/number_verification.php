<?php
session_start();
if (!isset($_POST["number"])) {
    $out[0] = 0;
    $str = htmlspecialchars($_POST["telephone"]);
    $number = substr($str, 1);
    include("../includes/connect_sql.php");

    $sql = "SELECT t.* FROM `pizza-hut`.users t where number = " . $number;
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row["number"] == $number) {
                $out[0] = 1;
            }
            $out[1] = $row["name"];
            $_SESSION["firstName"] = $row["name"];
            $_SESSION["number"] = $row["number"];
            $temp_str = str_replace('-', '', $row["number"]);
            $temp_str = str_replace('+', '', $temp_str);
            $temp_str = str_replace(' ', '', $temp_str);
            $_SESSION["unpretty_number"] = $temp_str;
            $_SESSION["our_user_id"] = $row['user_id'];
        }
    }

    echo json_encode($out);
    include("../includes/disconnect_sql.php");
}
