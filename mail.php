<?php

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = "C:/AppServ/www/restaurants";
}else{
    $path = "/var/www/html/restaurants";
}

require($path.'/admin/class/core_class.php');

$core = new Core();
$list_fotos = $core->get_fotos_categoria();

echo "<pre>";
print_r($list_fotos);
echo "</pre>";

?>


<div style="width: 100%">
    <div style="width: 610px; margin: 0 auto">
        <div style="display: block; height: 300px"><a href="https://misitiodelivery.cl" target="_blank" style=""><img src="https://misitiodelivery.cl/images/mail_01.jpg" alt="" /></a></div>
        <div style="display: block; height: 140px; margin-top: 8px"><a href="https://misitiodelivery.cl/passw/#ID#/#CODE#/" target="_blank" style=""><img src="https://misitiodelivery.cl/images/mail_02.jpg" alt="" /></a></div>
        <div style="display: block; height: 140px; margin-top: 8px"><a href="https://misitiodelivery.cl/video" target="_blank" style=""><img src="https://misitiodelivery.cl/images/mail_03.jpg" alt="" /></a></div>
        <div style="display: block; height: 140px; margin-top: 8px"><a href="https://misitiodelivery.cl/contacto/#ID#/" target="_blank" style=""><img src="https://misitiodelivery.cl/images/mail_04.jpg" alt="" /></a></div>
        <div style="display: block; height: 50px; background: #999; margin-top: 8px">
            <table cellspacing="0" cellpadding="0" border="0" height="50">
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