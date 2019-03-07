<?php
session_start();

if(!isset($core_class_iniciada)){

    if($_SERVER['HTTP_HOST'] == "localhost"){
        $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
    }else{
        $path = "/var/www/html/restaurants/";
    }

    require_once($path."admin/class/core_class.php");
    $fireapp = new Core();

}

$list = $fireapp->get_giros_user();

/* CONFIG PAGE */
$titulo = "Empresa";
$titulo_list = "Mis Empresas";
$sub_titulo1 = "Ingresar Empresa";
$sub_titulo2 = "Modificar Empresa";
$accion = "crear_giro";

$eliminaraccion = "eliminar_giro";
$id_list = "id_gir";
$eliminarobjeto = "Empresa";
$page_mod = "pages/msd/giros.php";
/* CONFIG PAGE */

$id = 0;
$sub_titulo = $sub_titulo1;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
if(isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] != 0){
    
    $sub_titulo = $sub_titulo2;
    $that = $fireapp->get_giro($_GET["id"]);
    $id = $_GET["id"];
    
}

?>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="backurl()"></li>
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
                    <input id="id" type="hidden" value="<?php echo $id; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Nombre del Giro:</p></span>
                        <input id="nombre" class="inputs" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Dominio del Giro:</p></span>
                        <input id="dominio" class="inputs" type="text" value="<?php echo $that['dominio']; ?>" require="" placeholder="" />
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
                    $id = $list[$i][$id_list];
                    $nombre = $list[$i]['nombre'];
                    $dominio = $list[$i]['dominio'];
                    $dns_letra = $list[$i]['dns_letra'];
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php if($dns_letra == ""){ echo "<p style='color: #900; font-size: 16px'>Falta crear zona DNS para ".$dominio."</p>"; } ?></div>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id=<?php echo $id; ?>')"></a>
                        <a class="icono ic3" onclick="navlink('pages/msd/ver_informe.php?id_gir=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                        <a class="icono ic2" onclick="navlink('pages/msd/ver_giro.php?id_gir=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>