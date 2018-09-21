<?php
session_start();

require_once("../../class/core_class.php");
$fireapp = new Core();

if($_GET["tipo"] == "admin"){
    $list = $fireapp->get_usuarios('admin');
}else{
    $fireapp->is_giro($_GET["id_gir"]);
    $list = $fireapp->get_usuarios('giro');
}




/* CONFIG PAGE */
$titulo = "Usuarios";
$titulo_list = "Lista de Usuarios";
$sub_titulo1 = "Ingresar Usuario";
$sub_titulo2 = "Modificar Usuario";
$accion = "crear_usuario";

$eliminaraccion = "eliminar_usuario";
$id_list = "id_user";
$eliminarobjeto = "Usuario";
$page_mod = "pages/base/usuarios.php";
$page_perfil = "pages/base/usuario_perfiles.php";
/* CONFIG PAGE */

$id = 0;
$sub_titulo = $sub_titulo1;
$id_gir = (isset($_GET["id_gir"]))? $_GET["id_gir"] : 0 ;
if(isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] != 0){
    
    $sub_titulo = $sub_titulo2;
    $that = $fireapp->get_usuario($_GET["id"]);
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
                    <input id="id_gir" type="hidden" value="<?php echo $id_gir; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label>
                        <span>Nombre:</span>
                        <input id="nombre" type="text" value="<?php echo $that['nombre']; ?>" require="distnada" />
                        <div class="mensaje"></div>
                    </label>
                    <label>
                        <span>Correo Electr&oacute;nico:</span>
                        <input id="correo" type="text" value="<?php echo $that['correo']; ?>" require="email" />
                        <div class="mensaje"></div>
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
        <div class="options">
            <ul class="ops clearfix">
                <li class="op">
                    <ul class="ss clearfix">
                        <li><input class="inptxt" type="text"></li>
                        <li class="ic1" onclick="opcs(this, 'name')" title="Nombre"></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="name">Listado de Usuarios</div>
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
                        <li class="nombre" name="<?php echo $nombre; ?>"><?php echo $nombre; ?></li>
                        <a title="Eliminar" class="icn borrar" onclick="eliminar('<?php echo $eliminaraccion; ?>', <?php echo $id; ?>, '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a title="Modificar" class="icn modificar" onclick="navlink('<?php echo $page_mod; ?>?id=<?php echo $id; ?>')"></a>
                        <a title="Perfiles" class="icn agregaradmin" onclick="navlink('<?php echo $page_perfil; ?>?id=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                    </ul>
                </li>
                
                <?php } ?>
                
            </ul>
            
        </div>
    </div>
</div>
<br />
<br />
