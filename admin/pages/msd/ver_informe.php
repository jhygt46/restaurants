<?php
session_start();

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}

require_once($path."admin/class/core_class.php");
$fireapp = new Core();

/* CONFIG PAGE */
$titulo_list = "Informe ".$_GET["nombre"];
/* CONFIG PAGE */

$id_gir = 0;
if(isset($_GET["id_gir"]) && is_numeric($_GET["id_gir"]) && $_GET["id_gir"] != 0){
    
    $id_gir = $_GET["id_gir"];
    $fireapp->is_giro($id_gir);
    $giro = $fireapp->get_giro();
    
    $giro_nombre = $giro['nombre'];
    $giro_dominio = $giro['dominio'];
    
    $send['accion'] = "verificar_dominio_online";
    
    /*
    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_URL, 'http://'.$giro_dominio);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($send));
    $http = curl_exec($ch1);
    curl_close($ch1);
    
    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, 'https://'.$giro_dominio);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query($send));
    $https = curl_exec($ch2);
    curl_close($ch2);
    */
    
    //X403-Y202-Z703
    
}

if($_SESSION['user']['info']['id_user'] == 1){
    echo "<div class='panel_admin'>";
    echo "<div class='data_info'>pages/base/ver_informe.php</div>";
    echo "</div>";
}

?>
<div class="title">
    <h1><?php echo $titulo_list; ?></h1>
    <ul class="clearfix">
        <li class="reload" onclick="refresh()"></li>
        <li class="back" onclick="backurl()"></li>
    </ul>
</div>
<hr>
<div>
    BUENA NELSON
</div>