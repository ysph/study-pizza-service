<?php

$out = json_decode(file_get_contents('php://input'), true);

include("../includes/connect_sql.php");

//$check[] = $out["latitude"];
//$check[] = $out["longitude"];
//$check[] = $out["housename"];
//$check[] = $out["street_id"];

$sql = 'INSERT INTO `pizza-hut`.houses (latitude, longitude, name, street_id) VALUES ('.(float)$out["latitude"].','.(float)$out["longitude"].',"'.$out["housename"].'", '.(int)$out["street_id"].')';
$result = mysqli_query($conn, $sql);

if ($result) {$check[] = "Success";} else {$check[] = "Error";}

include("../includes/disconnect_sql.php");

echo json_encode($check);