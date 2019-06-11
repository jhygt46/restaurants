<?php

    $data['server'] = $_SERVER;
    $data['post'] = $_POST;
    echo json_encode($data);

?>