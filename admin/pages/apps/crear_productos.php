<?php
session_start();

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}

require_once($path."admin/class/core_class.php");
$fireapp = new Core();

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

$all_prods = $fireapp->get_productos();


$id = 0;
$id_pro = 0;
$sub_titulo = $sub_titulo1;
if(isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] != 0){
    
    
    $id = $_GET["id"];
    $categoria = $fireapp->get_categoria_2($id);
    $titulo = $titulo." de ".$categoria['nombre'];
    $list = $fireapp->get_productos_categoria($id);
    /*
    $inputs = $fireapp->get_inputs($id);
    $show = $fireapp->show_inputs();
    */
    if(isset($_GET["id_pro"]) && is_numeric($_GET["id_pro"]) && $_GET["id_pro"] != 0){
        
        $id_pro = $_GET["id_pro"];
        $sub_titulo = $sub_titulo2;
        $that = $fireapp->get_producto($id_pro);
        
    }
    
}


?>
<script>

    $('#tipo').change(function(){
        if($(this).val() == 0){
            $('#tipo-0').show();
            $('#tipo-1').hide();
            $('#tipo-2').hide();
        }
        if($(this).val() == 1){
            $('#tipo-0').hide();
            $('#tipo-1').show();
            $('#tipo-2').hide();
        }
        if($(this).val() == 2){
            $('#tipo-0').hide();
            $('#tipo-1').hide();
            $('#tipo-2').show();
        }
    });


</script>
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
                    <label>
                        <span>Tipo:</span>
                        <select id="tipo"><option value="0">Ingresar Nuevo</option><option value="1">Producto Existente</option><option value="2">Producto Existente Externo</option></select>
                        <div class="mensaje"></div>
                    </label>
                    <div id="tipo-0">
                        
                        <label>
                            <span>Numero:</span>
                            <input id="numero" type="text" value="<?php echo $that['numero']; ?>" require="" placeholder="Super 8" />
                            <div class="mensaje"></div>
                        </label>
                        <label>
                            <span>Nombre:</span>
                            <input id="nombre" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="Super 8" />
                            <div class="mensaje"></div>
                        </label>
                        <label>
                            <span>Descripcion:</span>
                            <input id="descripcion" type="text" value="<?php echo $that['descripcion']; ?>" require="" placeholder="Oblea bañada en chocolate" />
                            <div class="mensaje"></div>
                        </label>
                        
                    </div>
                    <div  id="tipo-1" class="newform" style="margin-left: 167px; background: #ddd; display: none">
                    <?php 
                    
                    for($i=0; $i<count($all_prods); $i++){ 
                        $mostrar=true;
                        for($j=0; $j<count($list); $j++){ 
                            if($all_prods[$i]['id_pro'] == $list[$j]['id_pro']){ 
                                $mostrar=false;
                            }
                        }
                        if($mostrar){
                    
                    ?>
                        <div class="groupdetail">
                            <label>
                                <input id="prod-<?php echo $all_prods[$i]['id_pro']; ?>" type="checkbox" value="1" <?php echo $check; ?> />
                                <span class='detail'><?php echo $all_prods[$i]['id_pro']; ?> / <?php echo $all_prods[$i]['nombre']; ?></span>
                            </label>
                        </div>
                    <?php }} ?>
                    </div>
                    
                    <div id="tipo-2" style="display: none">
                        <label>
                            <span>Buscar:</span>
                            <input id="descripcion" type="text" value="" require="" placeholder="Oblea bañada en chocolate" />
                            <div class="mensaje"></div>
                        </label>
                    </div>
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
                        <li class="nombre"><?php echo $nombre; ?> / <?php echo $id_n; ?></li>
                        <a title="Eliminar" class="icn borrar" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>/<?php echo $id_n; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a title="Modificar" class="icn modificar" onclick="navlink('<?php echo $page_mod; ?>?id=<?php echo $id; ?>&id_pro=<?php echo $id_n; ?>')"></a>
                        <a title="Configurar Producto" class="icn conficon" onclick="navlink('pages/apps/configurar_producto.php?id_pro=<?php echo $id_n; ?>&nombre=<?php echo $nombre; ?>&id=<?php echo $id; ?>')"></a>
                    </ul>
                </li>
                
                <?php } ?>
                
            </ul>
            
        </div>
    </div>
</div>
<br />
<br />