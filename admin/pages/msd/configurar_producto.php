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
$accion = "configurar_producto";
/* CONFIG PAGE */


$id_cae = 0;
$hijos = false;
$sub_titulo = $sub_titulo1;
$parent_id = (isset($_GET["parent_id"]))? $_GET["parent_id"] : 0 ;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

if(isset($_GET["id_pro"]) && is_numeric($_GET["id_pro"]) && $_GET["id_pro"] != 0){
    
    $list_pre = $fireapp->get_preguntas();
    $list_lin = $fireapp->get_lista_ingredientes();
    
    $id_pro = $_GET["id_pro"];
    
    $pre_prod = $fireapp->get_preguntas_pro($id_pro);
    $lin_prod = $fireapp->get_lista_ingredientes_pro($id_pro);
    
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
                        <span><p>Imagen:</p></span>
                        <input style="padding-top: 6px" id="file_image" type="file" />
                    </label>
                    <label class="clearfix">
                        <span><p>Preguntas:</p></span>
                        <div class="perfil_preguntas">
                            <?php foreach($list_pre as $value){ $checked = ''; for($i=0; $i<count($pre_prod); $i++){ if($value['id_pre'] == $pre_prod[$i]['id_pre']){ $checked="checked='checked'"; } } ?>
                                <div class="clearfix">
                                    <input style="margin-top: 4px; width: 18px; height: 18px; float: left" id="pregunta-<?php echo $value['id_pre']; ?>" <?php echo $checked; ?> type="checkbox" value="1" />
                                    <div style="font-size: 18px; padding-left: 4px; float: left" class='detail'><?php echo $value['nombre']; ?></div>
                                </div>
                            <?php } ?>
                        </div>
                    </label>
                    <label class="clearfix">
                        <span><p>Lista de Ingredientes:</p></span>
                        <div class="perfil_preguntas">
                            <?php foreach($list_lin as $value){ $checked = ''; for($i=0; $i<count($lin_prod); $i++){ if($value['id_lin'] == $lin_prod[$i]['id_lin']){ $checked="checked='checked'"; } } ?>
                                <div class="clearfix">
                                    <input style="margin-top: 4px; width: 18px; height: 18px; float: left" id="lista_ing-<?php echo $value['id_lin']; ?>" <?php echo $checked; ?> type="checkbox" value="1" />
                                    <div style="font-size: 18px; padding-left: 4px; float: left" class='detail'><?php echo $value['nombre']; ?></div>
                                </div>
                            <?php } ?>
                        </div>
                    </label>
                    <label>
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>