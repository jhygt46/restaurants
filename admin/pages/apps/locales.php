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
$titulo = "Locales";
$titulo_list = "Mis Locales";
$sub_titulo1 = "Ingresar Local";
$sub_titulo2 = "Modificar Local";
$accion = "crear_locales";

$eliminaraccion = "eliminar_locales";
$id_list = "id_loc";
$eliminarobjeto = "Local";
$page_mod = "pages/apps/locales.php";
/* CONFIG PAGE */



$giro = $fireapp->get_giro();
$titulo = $titulo." de ".$giro['nombre'];
$list = $fireapp->get_locales();
$catalogos = $fireapp->get_catalogos();

$id_loc = 0;
$sub_titulo = $sub_titulo1;
if(isset($_GET["id_loc"]) && is_numeric($_GET["id_loc"]) && $_GET["id_loc"] != 0){

    $id_loc = $_GET["id_loc"];
    $that = $fireapp->get_local($id_loc);
    $sub_titulo = $sub_titulo2;

}
    




?>

<div class="title">
    <h1><?php echo $titulo; ?></h1>
    <ul class="clearfix">
        <li class="back" onclick="backurl()"></li>
    </ul>
</div>
<hr>
<div class="info">
    <div class="fc" id="info-0">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name"><?php echo $sub_titulo; ?></div>
        <div class="message"></div>
        <div class="sucont">

            <form action="" method="post" class="basic-grey">
                <fieldset>
                    <input id="id_loc" type="hidden" value="<?php echo $id_loc; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label>
                        <span>Nombre:</span>
                        <input id="nombre" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <label>
                        <span>Catalogo:</span>
                        <select id="id_cat">
                            <option value="0">Seleccionar</option>
                            <?php for($i=0; $i<count($catalogos); $i++){ ?>
                                <option value="<?php echo $catalogos[$i]["id_cat"]; ?>" <?php if($catalogos[$i]["id_cat"] == $that['id_cat']){ echo "selected"; } ?> ><?php echo $catalogos[$i]["nombre"]; ?></option>
                            <?php } ?>
                        </select>
                    </label>
                    <label>
                        <span>Tipo Despacho:</span>
                        <select id="tipo_despacho">
                            <option value="0" <?php if($that['tipo']==0){ echo "selected"; } ?>>Seleccionar</option>
                            <option value="1" <?php if($that['tipo']==1){ echo "selected"; } ?>>Por Zonas</option>
                            <option value="2" <?php if($that['tipo']==2){ echo "selected"; } ?>>Por Distancia</option>
                        </select>
                    </label>
                    <label style='margin-top:20px'>
                        <span>&nbsp;</span>
                        <a id='button' onclick="form()">Enviar</a>
                    </label>
                </fieldset>
            </form>
            
        </div>
    </div>
</div>

<div class="info">
    <div class="fc" id="info-0">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name"><?php echo $titulo_list; ?></div>
        <div class="message"></div>
        <div class="sucont">
            
            <ul class='listUser'>
                
                <?php 
                for($i=0; $i<count($list); $i++){
                    $id_n = $list[$i][$id_list];
                    $nombre = $list[$i]['nombre'];
                ?>
                
                <li class="user">
                    <ul class="clearfix">
                        <li class="nombre"><?php echo $nombre; ?></li>
                        <a title="Eliminar" class="icn borrar" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>/<?php echo $id_n; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a title="Modificar" class="icn modificar" onclick="navlink('<?php echo $page_mod; ?>?id_loc=<?php echo $id_n; ?>')"></a>
                        <a title="Zona de Despacho" class="icn despacho" onclick="navlink('pages/apps/zonas_locales.php?id_loc=<?php echo $id_n; ?>')"></a>
                        <a title="Punto de Venta" class="icn pventa" href="../locales.php" target="_blank"></a>
                    </ul>
                </li>
                
                <?php } ?>
                
            </ul>
            
        </div>
    </div>
</div>
<br />
<br />