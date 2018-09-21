<?php 

    $header['password']['js'][0] = "../../../admin/js/password.js";
    $header['password']['css'][0] = "../../../admin/css/login.css";

    $header['login']['js'][0] = "js/login.js";
    $header['login']['css'][0] = "css/login.css";

    $header['layout']['js'][0] = "js/base_1.js";
    $header['layout']['js'][1] = "js/video_notificacion.js";
    $header['layout']['js'][2] = "js/form_1.js";
    $header['layout']['js'][3] = "js/sweetalert.min.js";
    $header['layout']['js'][4] = "js/jquery-ui-timepicker-addon.js";
    $header['layout']['css'][0] = "css/layout.css";
    $header['layout']['css'][1] = "css/sweetalert.css";
    $header['layout']['css'][2] = "css/jquery-ui.min.css";
    $header['layout']['css'][3] = "css/jquery-ui-timepicker-addon.css";

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" lang="es-CL">
    <head>
        <title><?php echo (isset($_SESSION['user']['info']['titulo'])) ? "Fireapp" : "Ingresar"; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="shortcut icon" type="image/x-icon" href="images/fire.ico" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="http://www.fireapp.cl/admin/js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="http://www.fireapp.cl/admin/js/jquery-ui.js"></script>
        <!--<script src='http://localhost:3000/socket.io/socket.io.js'></script>-->
        <!--<script type="text/javascript" src="js/sockets.js"></script>-->
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/highcharts-3d.js"></script>
        <?php for($i=0; $i<count($header[$page]['js']); $i++){?>
        <script type="text/javascript" src="<?php echo $header[$page]['js'][$i]; ?>"></script>
        <?php } ?>
        <link rel="stylesheet" href="http://www.fireapp.cl/admin/css/reset.css" type="text/css" media="all">
        <?php for($i=0; $i<count($header[$page]['css']); $i++){?>
        <link rel="stylesheet" href="<?php echo $header[$page]['css'][$i]; ?>" type="text/css" media="all">
        <?php } ?>
    </head>