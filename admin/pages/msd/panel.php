<?php
session_start();

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}

require_once($path."admin/class/core_class.php");
$core = new Core();

if($core->id_user == 1){

    $list_dominio = $core->get_dominios_sin_dns();
    $list_correos = $core->get_correos_no_ses();
    $list_ssl_sol = $core->get_ssl_sol();

?>
    <div class="pagina">
        <div class="title">
            <h1>Pendientes</h1>
            <ul class="clearfix">
                <li class="back" onclick="navlink('pages/msd/ver_giro.php')"></li>
            </ul>
        </div>
        <hr>
        <div class="cont_pagina">
            <div class="cont_pag">
                <div class="list_titulo clearfix">
                    <div class="titulo"><h1>Dominios sin DNS</h1></div>
                    <ul class="opts clearfix">
                        <li class="opt">1</li>
                        <li class="opt">2</li>
                    </ul>
                </div>
                <div class="listado_items">
                    <?php 
                    for($i=0; $i<count($list_dominio); $i++){
                        $id = $list_dominio[$i]['id_gir'];
                        $dominio = $list_dominio[$i]['dominio'];
                    ?>
                    <div class="l_item">
                        <div class="detalle_item clearfix">
                            <div class="nombre"><?php echo $dominio; ?></div>
                            <a class="icono ic17" onclick="add_dns('<?php echo $id; ?>', '<?php echo $dominio; ?>')"></a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="cont_pagina">
            <div class="cont_pag">
                <div class="list_titulo clearfix">
                    <div class="titulo"><h1>Correos no SES</h1></div>
                    <ul class="opts clearfix">
                        <li class="opt">1</li>
                        <li class="opt">2</li>
                    </ul>
                </div>
                <div class="listado_items">
                    <?php 
                    for($i=0; $i<count($list_correos); $i++){
                        $id = $list_correos[$i]['id_loc'];
                        $correo = $list_correos[$i]['correo'];
                    ?>
                    <div class="l_item">
                        <div class="detalle_item clearfix">
                            <div class="nombre"><?php echo $correo; ?></div>
                            <a class="icono ic17" onclick="add_ses('<?php echo $id; ?>', '<?php echo $correo; ?>')"></a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="cont_pagina">
            <div class="cont_pag">
                <div class="list_titulo clearfix">
                    <div class="titulo"><h1>Solicitudes SSL</h1></div>
                    <ul class="opts clearfix">
                        <li class="opt">1</li>
                        <li class="opt">2</li>
                    </ul>
                </div>
                <div class="listado_items">
                    <?php 
                    for($i=0; $i<count($list_ssl_sol); $i++){
                        $id = $list_correos[$i]['id_gir'];
                        $dominio = $list_correos[$i]['dominio'];
                    ?>
                    <div class="l_item">
                        <div class="detalle_item clearfix">
                            <div class="nombre"><?php echo $dominio; ?></div>
                            <a class="icono ic17" onclick="add_ssl('<?php echo $id; ?>', '<?php echo $dominio; ?>')"></a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>