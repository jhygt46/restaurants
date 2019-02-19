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
$titulo = "Repartidores de ".$_GET["nombre"];
$titulo_list = "Mis Repartidores";
$sub_titulo1 = "Ingresar Repartidor";
$sub_titulo2 = "Modificar Repartidor";
$accion = "crear_repartidor";

$eliminaraccion = "eliminar_repartidor";
$id_list = "id_mot";
$eliminarobjeto = "Repartidor";
$page_mod = "pages/msd/crear_repartidor.php";
/* CONFIG PAGE */

$id_mot = 0;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$sub_titulo = $sub_titulo1;
$list = $fireapp->get_repartidores($_GET["id_loc"]);
$list_reps_giro = $fireapp->get_repartidores_giro();

if(isset($_GET["id_mot"]) && is_numeric($_GET["id_mot"]) && $_GET["id_mot"] != 0){

    $id_mot = $_GET["id_mot"];
    $that = $fireapp->get_repartidor($id_mot);
    $sub_titulo = $sub_titulo2;

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
                    <input id="id" type="hidden" value="<?php echo $id_mot; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Tipo:</p></span>
                        <select id="tipo"><option value="0">Ingresar Nuevo</option><option value="1">Repartidor Existente</option></select>
                    </label>
                    <div class="existente">
                        <label class="clearfix">
                            <span><p>Tipo:</p></span>
                            <select id="tipo"><option value="0">Ingresar Nuevo</option><option value="1">Repartidor Existente</option></select>
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
                        <a class="icono ic11" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id_mot=<?php echo $id; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>