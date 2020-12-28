<!DOCTYPE html>
<html>
    <body>
        <?php
        set_time_limit(0);
        //include("includes/connect_sql.php");
        $bing_key = 'AkNxeBm2-0S-266tIMqhCOLnGGm8yPu-dXH_8iwQt_NoSKfDeacXw3vFJoVWJLC7';
        $template = 'https://dev.virtualearth.net/REST/v1/Routes/DistanceMatrix?origins={lat0,long0;lat1,lon1;latM,lonM}&destinations={lat0,lon0;lat1,lon1;latN,longN}&travelMode={travelMode}&startTime={startTime}&timeUnit={timeUnit}&key={BingMapsAPIKey}';

        $latitude0 = 56.309;
        $longitude0 = 43.8401;

        include("includes/connect_sql.php");
        $sql = "SELECT t.latitude,longitude FROM `pizza-hut`.houses t";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "success";
        } else {
            echo "erroe";
        }
        $from = 0;
        $until = 1;
        $total = 0;
        if (mysqli_num_rows($result) > 0) {
            //while ($row = mysqli_fetch_assoc($result)) {
                /*if (($from % 2) == 0) {
                    continue;
                }
                if ($from >= $until) {
                    break;
                }*/
                //echo $row['latitude'];
                //$example = 'https://dev.virtualearth.net/REST/v1/Routes/DistanceMatrix?origins='.$latitude0.','.$longitude0.'&destinations='.$row['latitude'].','.$row['longitude'].'&travelMode=driving&distanceUnit=kilometer&key='.$bing_key;
                //$json = json_decode(file_get_contents($example),true);
                //$travel_distance = $json["resourceSets"][0]["resources"][0]["results"][0]["travelDistance"];
                //$travel_duration = $json["resourceSets"][0]["resources"][0]["results"][0]["travelDuration"];
                //$total += ($travel_distance * 2);
                //$from += 1;
           // }
        }
        include("includes/disconnect_sql.php");
        echo "<br>".$total."km!<br>";
?>
    </body>
</html>