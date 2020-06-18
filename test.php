<?php
    require dirname(__FILE__) . '/libs/ApiClient.php';
    $test = new GoneTone\ApiClient('mc.kongjune.com', '25565');
    var_dump($test->pingCall());
?>