<?php

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/admin_responsive/";
}else{
    $path = "/var/www/html/admin_responsive/";
}

require_once($path."class/new_core_class.php");
$fireapp = new Core();
$inicio = $fireapp->inicio();
/*
$inicio['user'] = 1 // SUPER ADMIN
$inicio['user'] = 2 // ADMIN
$inicio['user'] = 3 // NORMAL
*/
if($inicio['user'] == 1){
?>
<div class="pagina">
    <div class="title">
        <h1>Bienvenido Diegomez</h1>
        <ul class="clearfix">
            <li class="back" onclick="backurl()"></li>
        </ul>
    </div>
    <hr>
</div>
<?php } ?>