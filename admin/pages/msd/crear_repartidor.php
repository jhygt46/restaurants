<?php

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."restaurants/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."restaurants/");
}

require_once DIR."admin/class/core_class_prod.php";
$core = new Core();

/* CONFIG PAGE */
$titulo = "Repartidores de ".$_GET["nombre"];
$titulo_list = "Mis Repartidores";
$sub_titulo = "Ingresar Repartidor";
$accion = "crear_repartidor";

$id_list = "id_mot";
/* CONFIG PAGE */

$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$list_reps_giro = $core->get_repartidores_giro();

if(isset($_GET["id_loc"]) && is_numeric($_GET["id_loc"]) && $_GET["id_loc"] != 0){
    
    $id_loc = $_GET["id_loc"];
    $list = $core->get_repartidores($id_loc);

}

function in_arr($arr, $id){
    for($i=0; $i<count($arr); $i++){
        if($arr[$i]['id_mot'] == $id){
            return true;
        }
    }
    return false;
}

?>
<script>
    $('#tipo').change(function(){
        if($(this).val() == 0){
            $('.existente').hide();
            $('.nuevo').show();
        }
        if($(this).val() == 1){
            $('.existente').show();
            $('.nuevo').hide();
        }
    });
</script>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/locales.php')"></li>
        </ul>
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
                    <input id="id_loc" type="hidden" value="<?php echo $id_loc; ?>" />
                    <input id="loc_nombre" type="hidden" value="<?php echo $_GET["nombre"]; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Tipo:</p></span>
                        <select id="tipo"><option value="0">Ingresar Nuevo</option><option value="1">Repartidor Existente</option></select>
                    </label>
                    <div class="existente" style="display: none">
                        <label class="clearfix">
                            <span><p>Repartidor:</p></span>
                            <select id="repartidor">
                                <option value="0">Seleccionar</option>
                                <?php for($i=0; $i<count($list_reps_giro); $i++){ if(!in_arr($list, $list_reps_giro[$i]['id_mot'])){ ?>
                                <option value="<?php echo $list_reps_giro[$i]['id_mot']; ?>"><?php echo $list_reps_giro[$i]['nombre']; ?></option>
                                <?php }} ?>
                            </select>
                        </label>
                    </div>
                    <div class="nuevo">
                        <label class="clearfix">
                            <span><p>Nombre:</p></span>
                            <input id="nombre" class="inputs" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                        </label>
                        <label class="clearfix">
                            <span><p>Correo:</p></span>
                            <input id="correo" class="inputs" type="text" value="<?php echo $that['correo']; ?>" require="" placeholder="" />
                        </label>
                    </div>
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
                    $id = $list[$i][$id_list];
                    $nombre = $list[$i]['nombre'];
                    $dominio = $list[$i]['dominio'];
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <a class="icono ic7" onclick="navlink('pages/msd/configurar_repartidor.php?id_mot=<?php echo $id; ?>&id_loc=<?php echo $id_loc; ?>&nombre=<?php echo $_GET["nombre"]; ?>')"></a>
                        <a class="icono ic15" onclick="navlink('pages/msd/configurar_repartidor_app.php?id_mot=<?php echo $id; ?>&id_loc=<?php echo $id_loc; ?>&nombre=<?php echo $_GET["nombre"]; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>