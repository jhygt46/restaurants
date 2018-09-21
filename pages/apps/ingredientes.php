<?php
session_start();

require_once("../../class/core_class.php");
require_once("../../idioma/es-CL.php");
$fireapp = new Core();
//$fireapp->seguridad_exit(array(48));

/* CONFIG PAGE */
$titulo = "Ingredientes";
$titulo_list = "Mis Ingredientes";
$sub_titulo1 = "Ingresar Ingredientes";
$sub_titulo2 = "Modificar Ingredientes";
$accion = "crear_ingredientes";

$eliminaraccion = "eliminar_ingredientes";
$id_list = "id_ing";
$eliminarobjeto = "Ingredientes";
$page_mod = "pages/apps/ingredientes.php";
/* CONFIG PAGE */

$id = 0;
$sub_titulo = $sub_titulo1;
$parent_id = (isset($_GET["parent_id"]))? $_GET["parent_id"] : 0 ;
if(isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] != 0){
    
    $id = $_GET["id"];
    $catalogo = $fireapp->get_catalogo($id);
    $titulo = $titulo." de ".$catalogo['nombre'];
    $list = $fireapp->get_ingredientes($id);
    
    if(isset($_GET["id_ing"]) && is_numeric($_GET["id_ing"]) && $_GET["id_ing"] != 0){
        
        $id_ing = $_GET["id_ing"];
        $that = $fireapp->get_ingrediente($id, $id_ing);
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
                    <input id="id_ing" type="hidden" value="<?php echo $id_ing; ?>" />
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
                        <a title="Modificar" class="icn modificar" onclick="navlink('<?php echo $page_mod; ?>?id=<?php echo $id; ?>&id_ing=<?php echo $id_n; ?>&parent_id=<?php echo $parent_id; ?>')"></a>
                        <a title="Play Apps" class="icn database" onclick="navlink('pages/apps/ingredientes.php?id=<?php echo $id; ?>&parent_id=<?php echo $id_n; ?>')"></a>
                    </ul>
                </li>
                
                <?php }} ?>
                
            </ul>
            
        </div>
    </div>
</div>
<br />
<br />