<?php

if(!isset($core_class_iniciada)){
    if($_SERVER["HTTP_HOST"] == "localhost"){
        define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
        define("DIR", DIR_BASE."restaurants/");
    }else{
        define("DIR_BASE", "/var/www/html/");
        define("DIR", DIR_BASE."restaurants/");
    }
    require_once DIR."admin/class/core_class_prod.php";
    $core = new Core();
}

// SOLO ADMIN
if($core->id_user == 0){
    die("Error: su sesion ha expirado");
}

$list = $core->get_giros_pagos();

/*
Array
(
    [1] => Array
        (
            [fecha_dns] => 2019-09-25
            [monto] => 50000
            [dominio] => www.tockedelivery.cl
            [cpagos] => 3
        )

    [2] => Array
        (
            [fecha_dns] => 2019-07-25
            [monto] => 50000
            [dominio] => www.sitiodeprueba.cl
            [cpagos] => 0
        )

)
*/


/* CONFIG PAGE */
$titulo = "Pagos";
$titulo_list = "Pagos Atrasados";
$sub_titulo = "Ingresar Pago";
$accion = "crear_pago";

$id_list = "id_gir";
$eliminarobjeto = "Empresa";
/* CONFIG PAGE */

$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

?>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
    </div>
    <hr>
    <div class="cont_pagina">
        <div class="cont_pag">
            <form action="" method="post">
                <div class="form_titulo clearfix">
                    <div class="titulo"><?php echo $sub_titulo; ?></div>
                    <ul class="opts clearfix">
                        <li class="opt">1</li>
                        <li class="opt">2</li>
                    </ul>
                </div>
                <fieldset class="<?php echo $class; ?>">
                    <input id="id" type="hidden" value="<?php echo $id_gir; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Nombre del Giro:</p></span>
                        <input id="nombre" class="inputs" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Dominio del Giro:</p></span>
                        <input id="dominio" class="inputs" type="text" value="<?php echo $that['dominio']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Pagina:</p></span>
                        <input id="item_pagina" type="checkbox" class="checkbox" value="1" <?php if($that['item_pagina'] == 1){ ?>checked="checked"<?php } ?>>
                    </label>
                    <label class="clearfix">
                        <span><p>Punto de Venta:</p></span>
                        <input id="item_pos" type="checkbox" class="checkbox" value="1" <?php if($that['item_pos'] == 1){ ?>checked="checked"<?php } ?>>
                    </label>
                    <label class="clearfix">
                        <span><p>Cocina:</p></span>
                        <input id="item_cocina" type="checkbox" class="checkbox" value="1" <?php if($that['item_cocina'] == 1){ ?>checked="checked"<?php } ?>>
                    </label>
                    <label class="clearfix">
                        <span><p>Graficos:</p></span>
                        <input id="item_grafico" type="checkbox" class="checkbox" value="1" <?php if($that['item_grafico'] == 1){ ?>checked="checked"<?php } ?>>
                    </label>
                    <label class="clearfix">
                        <span><p>Letra Dns:</p></span>
                        <input id="dns_letra" class="inputs" type="text" value="<?php echo $that['dns_letra']; ?>" require="" placeholder="" />
                    </label>
                    <label>
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="list_titulo clearfix">
                <div class="titulo"><h1><?php echo $titulo_list; ?></h1></div>
                <ul class="opts clearfix">
                    <li class="opt">1</li>
                    <li class="opt">2</li>
                </ul>
            </div>
            <div class="listado_items">
                <?php 
                for($i=0; $i<count($list); $i++){

                    

                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php if($dns_letra == ""){ echo "<p style='font-weight: bold; color: #900; font-size: 17px'>Falta crear zona DNS para ".$dominio."</p>"; }else{ echo $nombre; } ?></div>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id_gir=<?php echo $id; ?>')"></a>
                        <a class="icono ic3" onclick="navlink('pages/msd/ver_informe.php?id_gir=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                        <a class="icono ic16" onclick="navlink('pages/msd/usuarios_admin.php?id_gir=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                        <a class="icono ic2" onclick="navlink('pages/msd/ver_giro.php?id_gir=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
