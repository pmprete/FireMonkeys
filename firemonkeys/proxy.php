<?php
$url = urldecode($_GET["url"]);
$contents = file_get_contents($url);
echo $contents;
;?>