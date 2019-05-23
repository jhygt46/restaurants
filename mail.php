<?php

require_once "/var/www/html/config/config.php";
$mysqli = new mysqli($db_host[0], $db_user[0], $db_password[0], "easyapps");

$stmt = $mysqli->prepare("INSERT INTO ztest (name, age) VALUES (?, ?)");
$stmt->bind_param("i", $a = "Diego", $a = 20);
$stmt->execute();
$stmt->close();

exit;




if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = "C:/AppServ/www/restaurants";
}else{
    $path = "/var/www/html/restaurants";
}

require($path.'/admin/class/core_class.php');

$core = new Core();
$list_fotos = $core->get_fotos_categoria();

$directorio = opendir("images/categorias"); //ruta actual
while ($archivo = readdir($directorio)){ //obtenemos un archivo y luego otro sucesivamente
    if(!is_dir($archivo)){ //verificamos si es o no un directorio
        if(foto_categoria($list_fotos, $archivo)){
            echo $archivo."<br/>";
        }else{
            //unlink($archivo);
        }
    }
}

function foto_categoria($arr, $foto){
    for($i=0; $i<count($arr); $i++){
        if($arr[$i]['image'] == $foto){
            return true;
        }
    }
    return false;
}


?>
<div style="width: 100%">
    <div style="width: 610px; margin: 0 auto">
        <div style="display: block"><a href="https://misitiodelivery.cl" target="_blank" style=""><img src="https://misitiodelivery.cl/images/mail_01.jpg" alt="" /></a></div>
        <div style="display: block; margin-top: 8px"><a href="https://misitiodelivery.cl/passw/#ID#/#CODE#/" target="_blank" style=""><img src="https://misitiodelivery.cl/images/mail_02.jpg" alt="" /></a></div>
        <div style="display: block; margin-top: 8px"><a href="https://misitiodelivery.cl/video" target="_blank" style=""><img src="https://misitiodelivery.cl/images/mail_03.jpg" alt="" /></a></div>
        <div style="display: block; margin-top: 8px"><a href="https://misitiodelivery.cl/contacto/#ID#/" target="_blank" style=""><img src="https://misitiodelivery.cl/images/mail_04.jpg" alt="" /></a></div>
        <div style="display: block; background: #999; margin-top: 8px">
            <table cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td width="300"></td>
                    <td width="220"><img src="images/siguenos.jpg" alt="" /></td>
                    <td width="40"><a href="https://www.facebook.com/misitiodelivery" ><img src="images/facebook.jpg" alt="" /></a></td>
                    <td width="40"><a href="https://www.instagram.com/misitiodelivery" ><img src="images/instragram.jpg" alt="" /></a></td>
                    <td width="10"></td>
                </tr>
            </table>
        </div>
    </div>
</div>