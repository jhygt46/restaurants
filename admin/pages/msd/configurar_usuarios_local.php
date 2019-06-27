<?php

require_once("/var/www/html/restaurants/admin/class/core_class_prod.php");
$core = new Core();

/* CONFIG PAGE */
$titulo = "Configuracion ".$_GET["nombre"];
$sub_titulo1 = "Modificar Configuracion";
$accion = "configurar_usuario_local";
/* CONFIG PAGE */

$id_user = 0;
$sub_titulo = $sub_titulo1;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$id_loc = (isset($_GET['id_loc'])) ? $_GET['id_loc'] : 0 ;

if(isset($_GET["id_user"]) && is_numeric($_GET["id_user"]) && $_GET["id_user"] != 0){
    
    $id_user = $_GET["id_user"];
    $that = $core->get_user_local($id_user, $id_loc);

}

?>
<script>
    function tipo_web(a) {
        var x = (a.value || a.options[a.selectedIndex].value);
        if(x == 0){
            document.getElementById('web').style.display = 'none';
        }
        if(x == 1 || x == 2){
            document.getElementById('web').style.display = 'block';
        }
    }
    function tipo_pos(a) {
        var x = (a.value || a.options[a.selectedIndex].value);
        if(x == 0){
            document.getElementById('pos').style.display = 'none';
        }
        if(x == 1 || x == 2){
            document.getElementById('pos').style.display = 'block';
        }
    }
</script>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/usuarios_local.php?id_loc=<?php echo $id_loc; ?>')"></li>
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
                    <input id="id_user" type="hidden" value="<?php echo $id_user; ?>" />
                    <input id="id_loc" type="hidden" value="<?php echo $id_loc; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Accion:</p></span>
                        <div class="btn_borrar"><div class="btn" onclick="eliminar('eliminar_usuario_local', '<?php echo $id_user; ?>', 'Usuario', '<?php echo $that['nombre']; ?>')">Eliminar</div></div>
                    </label>
                    <?php if(isset($that['tipo']) && $that['tipo'] == 0){ ?>
                        <label class="clearfix">
                            <span><p>Pedidos Web:</p></span>
                            <select id="save_web" onchange="tipo_web(this)">
                                <option value="0" <?php if($that["save_web"] == 0){ ?>selected<?php } ?>>No Modificar</option>
                                <option value="1" <?php if($that["save_web"] == 1){ ?>selected<?php } ?>>Modificar 1 vez antes de</option>
                                <option value="2" <?php if($that["save_web"] == 2){ ?>selected<?php } ?>>Modificar N vez antes de</option>
                            </select>
                        </label>
                        <label class="clearfix" id="web" style="display: <?php echo ($that["save_web"] == 1 || $that["save_web"] == 2) ? 'block' : 'none' ; ?>">
                            <span><p>(minutos)</p></span>
                            <input id="web_min" type="text" class="inputs" value="<?php echo $that['web_min']; ?>" require="" placeholder="" />
                        </label>
                        <label class="clearfix">
                            <span><p>Pedidos Punto de Venta:</p></span>
                            <select id="save_pos" onchange="tipo_pos(this)">
                                <option value="0" <?php if($that["save_pos"] == 0){ ?>selected<?php } ?>>No Modificar</option>
                                <option value="1" <?php if($that["save_pos"] == 1){ ?>selected<?php } ?>>Modificar 1 vez antes de</option>
                                <option value="1" <?php if($that["save_pos"] == 2){ ?>selected<?php } ?>>Modificar N vez antes de</option>
                            </select>
                        </label>
                        <label class="clearfix" id="pos" style="display: <?php echo ($that["save_pos"] == 1 || $that["save_pos"] == 2) ? 'block' : 'none' ; ?>">
                            <span><p>(minutos)</p></span>
                            <input id="pos_min" type="text" class="inputs" value="<?php echo $that['pos_min']; ?>" require="" placeholder="" />
                        </label>
                    <?php } ?>
                    <label style="padding-top: 10px">
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>