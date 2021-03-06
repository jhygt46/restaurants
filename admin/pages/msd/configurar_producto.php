<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
$url = url();

require_once $url["dir"]."admin/class/core_class_prod.php";
$core = new Core();

/* CONFIG PAGE */
$titulo = "Configuracion ".$_GET["nombre"];
$sub_titulo1 = "Modificar Configuracion";
$accion = "configurar_producto";
/* CONFIG PAGE */

$id_cae = 0;
$hijos = false;
$sub_titulo = $sub_titulo1;
$parent_id = (isset($_GET["parent_id"]))? $_GET["parent_id"] : 0 ;
$id_cae = (isset($_GET["id"]))? $_GET["id"] : 0 ;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$alto = 500 * $core->get_alto() / 100;

if(isset($_GET["id_pro"]) && is_numeric($_GET["id_pro"]) && $_GET["id_pro"] != 0){
    
    $list_pre = $core->get_preguntas();
    //$list_lin = $core->get_lista_ingredientes();
    
    $id_pro = $_GET["id_pro"];
    $that = $core->get_producto($id_pro);
    $pre_prod = $core->get_preguntas_pro($id_pro);

}

?>

<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/crear_productos.php?parent_id=<?php echo $parent_id; ?>&id=<?php echo $_GET["id"]; ?>')"></li>
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
                    <input id="id" type="hidden" value="<?php echo $_GET["id"]; ?>" />
                    <input id="parent_id" type="hidden" value="<?php echo $parent_id; ?>" />
                    <input id="id_pro" type="hidden" value="<?php echo $id_pro; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Imagen: (500x<?php echo $alto; ?> o 115x115)</p></span>
                        <input style="padding-top: 6px" id="file_image0" type="file" />
                    </label>
                    <label class="clearfix">
                        <span><p>Preguntas:</p></span>
                        <div class="perfil_preguntas">
                            <?php 
                                if(count($list_pre) > 0){
                                foreach($list_pre as $value){ $checked = ''; for($i=0; $i<count($pre_prod); $i++){ if($value['id_pre'] == $pre_prod[$i]['id_pre']){ $checked="checked='checked'"; } } ?>
                                <div class="clearfix">
                                    <input style="margin-top: 4px; width: 18px; height: 18px; float: left" id="pregunta-<?php echo $value['id_pre']; ?>" <?php echo $checked; ?> type="checkbox" value="1" />
                                    <div style="font-size: 18px; padding-left: 4px; float: left" class='detail'><?php echo $value['nombre']; ?></div>
                                </div>
                            <?php }}else{ ?>No hay preguntas creadas<?php } ?>
                        </div>
                    </label>
                    <label class="clearfix">
                        <span><p>Disponibilidad:</p></span>
                        <select id="disponible">
                            <option value="0" <?php echo ($that['disponible'] == 0) ? "selected" : "" ; ?>>Disponible</option>
                            <option value="1" <?php echo ($that['disponible'] == 1) ? "selected" : "" ; ?>>No Disponible</option>
                            <option value="2" <?php echo ($that['disponible'] == 2) ? "selected" : "" ; ?>>Ocultar</option>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Accion:</p></span>
                        <div class="btn_borrar"><div class="btn" onclick="eliminar('eliminar_productos', '<?php echo $that['id_pro']; ?>/<?php echo $id_cae; ?>/<?php echo $parent_id; ?>', 'Producto', '<?php echo $that['nombre']; ?>')">Eliminar</div></div>
                    </label>
                    <label style="padding-top: 10px">
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>
