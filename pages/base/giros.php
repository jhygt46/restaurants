<?php
session_start();


require_once("../../class/core_class.php");
$fireapp = new Core();
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
$page_mod = "pages/base/giros.php";
/* CONFIG PAGE */

$id = 0;
$sub_titulo = $sub_titulo1;
if(isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] != 0){
    
    $sub_titulo = $sub_titulo2;
    $that = $fireapp->get_giro($_GET["id"]);
    $id = $_GET["id"];

    
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
                    <input id="id" type="hidden" value="<?php echo $id; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label>
                        <span>Nombre:</span>
                        <input id="nombre" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <label>
                        <span>Dominio:</span>
                        <input id="dominio" type="text" value="<?php echo $that['dominio']; ?>" require="" placeholder="" />
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
                    $id = $list[$i][$id_list];
                    $nombre = $list[$i]['nombre'];
                ?>
                
                <li class="user">
                    <ul class="clearfix">
                        <li class="nombre"><?php echo $nombre; ?></li>
                        <a title="Eliminar" class="icn borrar" onclick="eliminar('<?php echo $eliminaraccion; ?>', <?php echo $id; ?>, '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a title="Modificar" class="icn modificar" onclick="navlink('<?php echo $page_mod; ?>?id=<?php echo $id; ?>')"></a>
                        <a title="Website" class="icn webicon" href="http://www.mikasushi.cl" target="_blank"></a>
                        <a title="Usuarios" class="icn carusuario" onclick="navlink('pages/base/usuarios.php?id_gir=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                        <a title="Play Apps" class="icn playicon" onclick="navlink('pages/base/ver_giro.php?id_gir=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                    </ul>
                </li>
                
                <?php } ?>
                
            </ul>
            
        </div>
    </div>
</div>
<br />
<br />