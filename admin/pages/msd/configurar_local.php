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
$titulo = "Configuracion ".$_GET["nombre"];
$sub_titulo1 = "Modificar Configuracion";
$accion = "configurar_local";
/* CONFIG PAGE */

$id_cae = 0;
$sub_titulo = $sub_titulo1;
$parent_id = (isset($_GET["parent_id"]))? $_GET["parent_id"] : 0 ;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

if(isset($_GET["id_loc"]) && is_numeric($_GET["id_loc"]) && $_GET["id_loc"] != 0){
    
    $id_loc = $_GET["id_loc"];
    $that = $fireapp->get_local($id_loc);
    
}

?>

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
                    <input id="id_cae" type="hidden" value="<?php echo $id_cae; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <input id="parent_id" type="hidden" value="<?php echo $parent_id; ?>" />
                    <label class="clearfix">
                        <span><p>Ocultar Categoria:</p></span>
                        <select id="ocultar"><option value="0">No</option><option value="1" <?php echo($that['ocultar'] == 1)?'selected':''; ?>>Si</option></select>
                    </label>
                    <label class="clearfix">
                        <span><p>Mostrar Productos:</p></span>
                        <select id="mostrar_prods"><option value="0">No</option><option value="1" <?php echo($that['mostrar_prods'] == 1)?'selected':''; ?>>Si</option></select>
                    </label>
                    <label class="clearfix">
                        <span><p>Ver detalle productos:</p></span>
                        <select id="detalle_prods"><option value="0">No</option><option value="1" <?php echo($that['detalle_prods'] == 1)?'selected':''; ?>>Si</option></select>
                    </label>
                    <label class="clearfix">
                        <span><p>Imagen:</p></span>
                        <input style="padding-top: 6px" id="file_image" type="file" />
                    </label>
                    <label class="clearfix">
                        <span><p>Accion:</p></span>
                        <div class="btn_borrar"><div class="btn" onclick="eliminar('eliminar_locales', '<?php echo $id_loc; ?>', 'Local', '<?php echo $that['nombre']; ?>')">Eliminar</div></div>
                    </label>
                    <label style="padding-top: 10px">
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>