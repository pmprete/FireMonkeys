<?php

    $lat = $_GET['lat'];
    $lng = $_GET['lng'];

    $options = array(
            'http' => array(
                'method'  => "GET"
            )
    );

    $url = "https://da89a8c7-e7b3-49be-9195-2eb86ae9463a:utdXzK9Lis@twcservice.mybluemix.net:443/api/weather/v1/geocode/$lat/$lng/forecast/daily/10day.json?units=m&language=en-US";
    $context  = stream_context_create($options);
    //$result = file_get_contents($url, false, $context);

    $fp = fopen($url, 'r', false, $context);
    fpassthru($fp);
    fclose($fp);

    //echo $result;
?>