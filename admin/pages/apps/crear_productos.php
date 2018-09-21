<?php
session_start();

require_once("../../class/core_class.php");
require_once("../../idioma/es-CL.php");
$fireapp = new Core();
//$fireapp->seguridad_exit(array(48));

$titulo = "Productos";
$titulo_list = "Lista de Productos";
$sub_titulo1 = "Ingresar Producto";
$sub_titulo2 = "Modificar Producto";
$accion = "crear_productos";

$eliminaraccion = "eliminar_productos";
$id_list = "id_pro";
$eliminarobjeto = "Producto";
$page_mod = "pages/apps/crear_productos.php";
/* CONFIG PAGE */

$id = 0;
$id_pro = 0;
$sub_titulo = $sub_titulo1;
if(isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] != 0){
    
    
    $id = $_GET["id"];
    $categoria = $fireapp->get_categoria_2($id);
    $titulo = $titulo." de ".$categoria['nombre'];
    $list = $fireapp->get_productos($id);
    $inputs = $fireapp->get_inputs($id);
    $show = $fireapp->show_inputs();
    
    if(isset($_GET["id_pro"]) && is_numeric($_GET["id_pro"]) && $_GET["id_pro"] != 0){
        
        $id_pro = $_GET["id_pro"];
        $sub_titulo = $sub_titulo2;
        $that = $fireapp->get_producto($id_pro);
        
    }
    
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
                    <input id="id_pro" type="hidden" value="<?php echo $id_pro; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <?php if($fireapp->in_campo($show, 1)){ ?>
                    <label>
                        <span>Nombre:</span>
                        <input id="nombre" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="Super 8" />
                        <div class="mensaje"></div>
                    </label>
                    <?php } ?>
                    <?php if($fireapp->in_campo($show, 2)){ ?>
                    <label>
                        <span>Descripcion:</span>
                        <input id="descripcion" type="text" value="<?php echo $that['descripcion']; ?>" require="" placeholder="Oblea bañada en chocolate" />
                        <div class="mensaje"></div>
                    </label>
                    <?php } ?>
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
                        <a title="Modificar" class="icn modificar" onclick="navlink('<?php echo $page_mod; ?>?id=<?php echo $id; ?>&id_pro=<?php echo $id_n; ?>')"></a>
                    </ul>
                </li>
                
                <?php } ?>
                
            </ul>
            
        </div>
    </div>
</div>
<br />
<br />