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
    
    $http_code = file_get_contents("http://".$giro_dominio."?accion=verificar_dominio_online");
    $https_code = file_get_contents("https://".$giro_dominio."?accion=verificar_dominio_online");
    
    if($http_code == "X403-Y202-Z703"){
        echo "SITIO SIN SEGURIDAD ARRIBA";
    }
    if($https_code == "X403-Y202-Z703"){
        echo "SITIO CON SEGURIDAD ARRIBA";
    }
    
    
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