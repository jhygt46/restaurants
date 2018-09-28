<?php
session_start();
unset($_SESSION['user']['id_cat']);

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}

require_once($path."admin/class/core_class.php");
$fireapp = new Core();
//$fireapp->seguridad_exit(array(48));

/* CONFIG PAGE */
$titulo = "Catalogo de Productos";
$titulo_list = "Mis Catalogos";
$sub_titulo1 = "Ingresar Catalogo";
$sub_titulo2 = "Modificar Catalogo";
$accion = "crear_catalogo";

$eliminaraccion = "eliminar_catalogo";
$id_list = "id_cat";
$eliminarobjeto = "Catalogo";
$page_mod = "pages/apps/catalogo_productos.php";
/* CONFIG PAGE */



$sub_titulo = $sub_titulo1;
$id_gir = $_GET["id_gir"];
$giro = $fireapp->get_ses_giro();
$titulo = $titulo." de ".$giro['nombre'];
$list = $fireapp->get_catalogos();

if(isset($_GET["id_cat"]) && is_numeric($_GET["id_cat"]) && $_GET["id_cat"] != 0){

    $id_cat = $_GET["id_cat"];
    $that = $fireapp->get_catalogo($id_cat);
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
                    <input id="id" type="hidden" value="<?php echo $id_cat; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label>
                        <span>Nombre:</span>
                        <input id="nombre" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
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
                        <a title="Modificar" class="icn modificar" onclick="navlink('<?php echo $page_mod; ?>?id=<?php echo $id; ?>&id_cat=<?php echo $id_n; ?>')"></a>
                        <a title="Ver Catalogo" class="icn catalogo" onclick="navlink('pages/apps/ver_catalogo.php?id_cat=<?php echo $id_n; ?>')"></a>
                    </ul>
                </li>
                
                <?php } ?>
                
            </ul>
            
        </div>
    </div>
</div>
<br />
<br />