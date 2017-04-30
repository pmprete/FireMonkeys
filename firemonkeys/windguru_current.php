<?php
    $station_id = $_GET['station_id'];
    header('Content-Type: application/json');
    $headers =  "Host: www.windguru.cz\r\n" .
                "Referer: http://stackoverflow.com\r\n".
                "Accept-Encoding: gzip, deflate, sdch, br\r\n".
                "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36\r\n".
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\r\n".
                "Connection: keep-alive\r\n".
                "Cache-Control: max-age=0\r\n";
                
    $options = array(
            'http' => array(
                'method'  => "GET",
                'header'  => $headers
            )
    );

    $url = "https://www.windguru.cz/int/iapi.php?q=station_data_current&id_station=" . $station_id . "&date_format=Y-m-d+H%3Ai%3As+T";
    $context  = stream_context_create($options);
    //$result = file_get_contents($url, false, $context);

    $fp = fopen($url, 'r', false, $context);
    fpassthru($fp);
    fclose($fp);

    //echo $result;
?>