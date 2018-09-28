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
$titulo = "Categorias";
$titulo_list = "Mis Categorias";
$sub_titulo1 = "Ingresar Categoria";
$sub_titulo2 = "Modificar Categoria";
$accion = "crear_categoria";

$eliminaraccion = "eliminar_categoria";
$id_list = "id_cae";
$eliminarobjeto = "Categoria";
$page_mod = "pages/apps/categorias.php";
/* CONFIG PAGE */


$sub_titulo = $sub_titulo1;
$parent_id = (isset($_GET["parent_id"]))? $_GET["parent_id"] : 0 ;

$catalogo = $fireapp->get_catalogo();
$titulo = $titulo." de ".$catalogo['nombre'];
$list = $fireapp->get_categorias();
    
if(isset($_GET["id_cae"]) && is_numeric($_GET["id_cae"]) && $_GET["id_cae"] != 0){

    $id_cae = $_GET["id_cae"];
    $that = $fireapp->get_categoria($id, $id_cae);
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
                    <input id="id" type="hidden" value="<?php echo $id; ?>" />
                    <input id="id_cae" type="hidden" value="<?php echo $id_cae; ?>" />
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
                    if($list[$i]['id_pro'] > 0){
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
                        <a title="Modificar" class="icn modificar" onclick="navlink('<?php echo $page_mod; ?>?id=<?php echo $id; ?>&id_cae=<?php echo $id_n; ?>&parent_id=<?php echo $parent_id; ?>')"></a>
                        <a title="Configurar Categoria" class="icn conficon" onclick="navlink('pages/apps/configurar_categoria.php?id=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                        <a title="Asignar Pregunta" class="icn pregicon" onclick="navlink('pages/apps/asignar_pregunta_cat.php?id=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                        <?php if(!$childs && !$prods || $childs){ ?><a title="Play Apps" class="icn database" onclick="navlink('pages/apps/categorias.php?id=<?php echo $id; ?>&parent_id=<?php echo $id_n; ?>')"></a><?php }else{ ?><a class="icn sinicono"></a><?php } ?>
                        <?php if(!$childs){ ?><a title="Productos" class="icn prods" onclick="navlink('pages/apps/crear_productos.php?id=<?php echo $id_n; ?>')"></a><?php }else{ ?><a class="icn sinicono"></a><?php } ?>
                    </ul>
                </li>
                
                <?php }} ?>
                
            </ul>
            
        </div>
    </div>
</div>
<br />
<br />