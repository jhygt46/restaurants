<?php
session_start();

require_once("../../class/core_class.php");
require_once("../../idioma/es-CL.php");
$fireapp = new Core();
//$fireapp->seguridad_exit(array(48));

/* CONFIG PAGE */
$titulo = "Promociones";
$titulo_list = "Mis Promociones";
$sub_titulo1 = "Ingresar Promocion";
$sub_titulo2 = "Modificar Promocion";
$accion = "crear_promociones";

$eliminaraccion = "eliminar_promociones";
$id_list = "id_prm";
$eliminarobjeto = "Promocion";
$page_mod = "pages/apps/promociones.php";
/* CONFIG PAGE */


$id = 0;
$sub_titulo = $sub_titulo1;
$parent_id = 0;
if(isset($_GET["parent_id"])){
    $parent_id = $_GET["parent_id"];
}

if(isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] != 0){
    
    $id = $_GET["id"];
    $catalogo = $fireapp->get_catalogo($id);
    $titulo = $titulo." de ".$catalogo['nombre'];
    $list = $fireapp->get_promociones($id);
    
    if(isset($_GET["id_prm"]) && is_numeric($_GET["id_prm"]) && $_GET["id_prm"] != 0){
        
        $id_prm = $_GET["id_prm"];
        $that = $fireapp->get_promocion($id, $id_prm);
        $sub_titulo = $sub_titulo2;
        
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
                    <input id="id_prm" type="hidden" value="<?php echo $id_prm; ?>" />
                    <input id="parent_id" type="hidden" value="<?php echo $parent_id; ?>" />
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
                    if($list[$i]['parent_id'] == $parent_id){
                    
                    $prods = false;
                    if($list[$i]['id_prm'] > 0){
                        $prods = true;
                    }

                    $childs = false;
                    for($j=0; $j<count($list); $j++){
                        if($list[$j]['parent_id'] == $id_n){
                            $childs = true;
                        }
                    }  
                        
                ?>
                
                <li class="user">
                    <ul class="clearfix">
                        <li class="nombre"><?php echo $nombre; ?></li>
                        <a title="Eliminar" class="icn borrar" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>/<?php echo $id_n; ?>/<?php echo $parent_id; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a title="Modificar" class="icn modificar" onclick="navlink('<?php echo $page_mod; ?>?id=<?php echo $id; ?>&id_prm=<?php echo $id_n; ?>&parent_id=<?php echo $parent_id; ?>')"></a>
                        <a title="Configurar Giro" class="icn rubroicon" onclick="navlink('pages/apps/configurar_categoria.php?id=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                        <?php if(!$childs && !$prods || $childs){ ?><a title="Play Apps" class="icn database" onclick="navlink('pages/apps/promociones.php?id=<?php echo $id; ?>&parent_id=<?php echo $id_n; ?>')"></a><?php }else{ ?><a class="icn sinicono"></a><?php } ?>
                        <?php if(!$childs){ ?><a title="Productos" class="icn prods" onclick="navlink('pages/apps/crear_promocion.php?id_prm=<?php echo $id_n; ?>&id=<?php echo $id; ?>&parent_id=<?php echo $parent_id; ?>')"></a><?php }else{ ?><a class="icn sinicono"></a><?php } ?>
                    </ul>
                </li>
                
                <?php }} ?>
                
            </ul>
            
        </div>
    </div>
</div>
<br />
<br />