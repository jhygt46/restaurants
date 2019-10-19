<?php

require_once("/var/www/html/restaurants/admin/class/core_class_prod.php");
$core = new Core();

/* CONFIG PAGE */
$titulo = "Configuracion ".$_GET["nombre"];
$sub_titulo1 = "Modificar Configuracion";
$accion = "configurar_categoria";
/* CONFIG PAGE */

$id_cae = 0;
$sub_titulo = $sub_titulo1;
$parent_id = (isset($_GET["parent_id"]))? $_GET["parent_id"] : 0 ;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$alto = 500 * $core->get_alto() / 100;

if(isset($_GET["id_cae"]) && is_numeric($_GET["id_cae"]) && $_GET["id_cae"] != 0){
    
    $id_cae = $_GET["id_cae"];
    $that = $core->get_categoria($id_cae);
    echo "<pre>";
    print_r($that);
    echo "</pre>";
    
}

?>

<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/categorias.php?parent_id=<?php echo $parent_id; ?>')"></li>
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
                        <span><p>Degradado:</p></span>
                        <select id="degradado">
                            <option value="0">No</option>
                            <option value="1" <?php echo($that['degradado'] == 1)?'selected':''; ?>>Blanco A1</option>
                            <option value="2" <?php echo($that['degradado'] == 2)?'selected':''; ?>>Gris A1</option>
                            <option value="3" <?php echo($that['degradado'] == 3)?'selected':''; ?>>Negro A1</option>
                            <option value="4" <?php echo($that['degradado'] == 4)?'selected':''; ?>>Blanco A2</option>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Imagen: (500x<?php echo $alto; ?>)</p></span>
                        <input style="padding-top: 6px" id="file_image0" type="file" />
                    </label>
                    <label class="clearfix">
                        <span><p>Accion:</p></span>
                        <div class="btn_borrar"><div class="btn" onclick="eliminar('eliminar_categoria', '<?php echo $id_cae; ?>/<?php echo $parent_id; ?>', 'Categoria', '<?php echo $that['nombre']; ?>')">Eliminar</div></div>
                    </label>
                    <label style="padding-top: 10px">
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>