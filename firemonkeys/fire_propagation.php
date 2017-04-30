<?php
header('Content-Type: application/json');
include_once('geoPHP.inc');


$n_days = 10;
$wind_hdg = array(45, 45, 60, 10, 23, 180, 20, 90, 123, 5);
$wind_int = array(0.3, 0.4, 0.8, 0.4, 0.5, 1.5, 0.4, 0.8, 0.4, 0.5);

$color_step = 255 / $n_days;

$dates = array();
for($i = 1; $i <= $n_days; $i++){
    $dates[] = new DateTime("today + $i day");
}

$points = array(
        array(-70.76980590820312, -32.3822809650579,  1000),
        array(-70.762939453125, -32.393877575286446, 1000),
        array(-70.72311401367188, -32.406632126733044, 1000),
        array(-70.68740844726562, -32.39851580247401, 1000),
        array(-70.67779541015624, -32.3846004062099, 1000),
        array(-70.68740844726562, -32.36140331527542, 1000),
        array(-70.72448730468749, -32.34168110749221, 1000),
        array(-70.73822021484375, -32.34168110749221, 1000),
        array(-70.751953125, -32.34632201382947, 1000),
        array(-70.76980590820312, -32.36024330444844, 1000),
        array(-70.77255249023438, -32.373002604986546, 1000)
);
$geo_poly_arr = array();
foreach($points as $point){
    $geo_poly_arr[] = $point[1] . " " . $point[0];
}
$geo_poly_str = implode(",", $geo_poly_arr);
//echo $geo_poly_str;die;
$polygon = geoPHP::load("POLYGON(($geo_poly_str)", "wkt");
$centroid = $polygon->getCentroid();
$centX = $centroid->getX();
$centY = $centroid->getY();
$centroid = array($centX, $centY);

// $area = $polygon->getArea();



$czml_header = array(  "id" => "document",
                    "name" => "Fire Forecast CZML",
                    "version" => "1.0",
                    "clock" => array(
                        "interval" => $dates[0]->format('Y-m-d') . "T00:00:00Z" .  "/" . $dates[$n_days - 1]->format('Y-m-d') . "T23:59:59Z",
                        "currentTime" => $dates[0]->format('Y-m-d') . "T00:00:00Z",
                        "multiplier" => 900
                    )
            );

$czml_bodies = array();
foreach($dates as $n => $date){

    $czml_body = array(  "id"    => "fireForecastDay $n",
                        "name"  => "Fire Propagation Forecast, Day: $n",
                        "availability"  =>  $dates[0]->format('Y-m-d') . "T00:00:00Z" .  "/" . $dates[$n_days - 1]->format('Y-m-d') . "T23:59:59Z",
                        "polygon" => array()
                    );
    foreach($points as &$point){
        $point = newPosByDistanceAndHdg($point, $centroid, $wind_int[$n], $wind_hdg[$n]);
    }

    // transform to cesium format (stream of values)
    $coords_list = array();
    foreach($points as $point){
        $coords_list[] = $point[1];
        $coords_list[] = $point[0];
        $coords_list[] = $point[2];
    }

    $czml_body["polygon"]["positions"][] = array(
        "interval" => $date->format('Y-m-d') . "T00:00:00Z" . "/" . $dates[$n_days - 1]->format('Y-m-d') . "T23:59:59Z",
        "cartographicDegrees" => $coords_list
    );

    $czml_body["polygon"]["material"][] = array(
                                            "solidColor" => array(
                                                "color" => array(
                                                    array(
                                                        "interval" => $dates[0]->format('Y-m-d') . "T00:00:00Z" .  "/" . $dates[$n_days - 1]->format('Y-m-d') . "T23:59:59Z",
                                                        "rgba" => array(255, ($color_step * $n), 0, 255 - ($color_step * $n))
                                                    )
                                                )
                                            )
                                    );

    $czml_bodies[] = $czml_body;
}

$czml_header_json = json_encode($czml_header, JSON_UNESCAPED_SLASHES);

$bodies_jsons = [];
foreach($czml_bodies as $body){
    $bodies_jsons[] = json_encode($body, JSON_UNESCAPED_SLASHES);
}

$czml_bodies_json = implode(",", $bodies_jsons);

echo "[" . $czml_header_json ."," . $czml_bodies_json . "]";

function newPosByDistanceAndHdg($coords, $centroid, $d, $wind_hdg)
{
    $lat1 = $coords[1];
    $long1 = $coords[0];
    $alt1 = $coords[2];
    // lat1 = latitude of start point in degrees
    // long1 = longitude of start point in degrees
    // d = distance in KM
    // angle = bearing in degrees
    // returns: array(lat, lon)
    # Earth Radious in KM
    $R = 6378.14;

    //$angle = haversineGreatCircleAngle($lat1, $long1, $centroid[0], $centroid[1]) * (180 / M_PI);

    $angle = angleFromCoordinate($centroid[0], $centroid[1], $lat1, $long1);
    # Degree to Radian
    $latitude1 = $lat1 * (M_PI/180);
    $longitude1 = $long1 * (M_PI/180);

    $step = 10;
    $phi = deg2rad($angle) + atan2($d * sin(deg2rad($wind_hdg - $angle)), $d + $step * cos(deg2rad($wind_hdg - $angle)));
    $r = sqrt(pow($d,2) + pow($step,2) + 2 * $d * $step * cos(deg2rad($wind_hdg - $angle)));

    $angle = (rad2deg($phi) % 360 );
    //$d = $r;
    $brng = $angle * (M_PI/180);
    //echo $lat1 . " | " . $long1 . " | " . $centroid[0] . " | " . $centroid[1] . " =" . $angle . "\n";

    $latitude2 = asin(sin($latitude1)*cos($d/$R) + cos($latitude1)*sin($d/$R)*cos($brng));
    $longitude2 = $longitude1 + atan2(sin($brng)*sin($d/$R)*cos($latitude1),cos($d/$R)-sin($latitude1)*sin($latitude2));

    # back to degrees
    $latitude2 = $latitude2 * (180/M_PI);
    $longitude2 = $longitude2 * (180/M_PI);

    # 6 decimal for Leaflet and other system compatibility
   $lat2 = round ($latitude2,6);
   $long2 = round ($longitude2,6);
   $alt2 = $alt1;

   // Push in array and get back
   return array($lat2, $long2, $alt2);
 }



function angleFromCoordinate($lat1, $long1, $lat2, $long2) {

    $dLon = ($long2 - $long1);

    $y = sin($dLon) * cos($lat2);
    $x = cos($lat1) * sin($lat2) - sin($lat1) * cos($lat2) * cos($dLon);

    $brng = atan2($y, $x);

    $brng = rad2deg($brng);
    $brng = ($brng + 360) % 360;

    return $brng;
}
?>
