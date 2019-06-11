<?php

    $ips = ["35.192.157.227"];

    $ip = $_SERVER['REMOTE_ADDR'];
    $port = $_SERVER['SERVER_PORT'];

    $data['post'] = $_POST;
    echo json_encode($data);

?>