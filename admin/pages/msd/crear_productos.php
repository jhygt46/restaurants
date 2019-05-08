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
$page_mod = "pages/msd/crear_productos.php";
/* CONFIG PAGE */

$parent_id = (isset($_GET["parent_id"]))? $_GET["parent_id"] : 0 ;
$all_prods = $fireapp->get_productos();

echo "<pre>";
print_r($all_prods);
echo "</pre>";

$id = 0;
$id_pro = 0;
$sub_titulo = $sub_titulo1;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

if(isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] != 0){
    
    $id = $_GET["id"];
    $categoria = $fireapp->get_categoria_2($id);
    $titulo = $titulo." de ".$categoria['nombre'];
    $list = $fireapp->get_productos_categoria($id);
    
    if(isset($_GET["id_pro"]) && is_numeric($_GET["id_pro"]) && $_GET["id_pro"] != 0){
        
        $id_pro = $_GET["id_pro"];
        $sub_titulo = $sub_titulo2;
        $that = $fireapp->get_producto($id_pro);
        
    }
    
}


?>
<script>
    
    var valores = [{nom: '#p1', valor: 'Salmon'}, {nom: '#p2', valor: 'Pollo Teriyaki'}];
    
    $('#nombre').keyup(function() {
        var valor = $(this).val();
        for(var i=0; i<valores.length; i++){
            valor = valor.replace(valores[i].nom, valores[i].valor);
        }
        $(this).val(valor);
    });

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
    
    <?php if(isset($_GET['sortable'])){ ?>
    $('.listado_items').sortable({
        stop: function(e, ui){
            var order = [];
            $(this).find('.l_item').each(function(){
                order.push($(this).attr('rel'));
            });
            var send = { accion: 'orderprods', values: order, id_cae: <?php echo $id; ?> };
            $.ajax({
                url: "ajax/index.php",
                type: "POST",
                data: send,
                success: function(data){ console.log(data); },
                error: function(e){}
            });
        }
    });
    <?php } ?>

</script>

<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/categorias.php?id=<?php echo $id; ?>&parent_id=<?php echo $parent_id; ?>')"></li>
        </ul>
    </div>
    <hr>
    <div class="cont_pagina">
        <div class="cont_pag">
            <form action="" method="post">
                <div class="form_titulo clearfix">
                    <div class="titulo"><h1><?php echo $sub_titulo; ?></h1></div>
                    <ul class="opts clearfix">
                        <li class="opt">1</li>
                        <li class="opt">2</li>
                    </ul>
                </div>
                <fieldset class="<?php echo $class; ?>">
                    <input id="id" type="hidden" value="<?php echo $id; ?>" />
                    <input id="id_pro" type="hidden" value="<?php echo $id_pro; ?>" />
                    <input id="parent_id" type="hidden" value="<?php echo $parent_id; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    
                    <label class="clearfix">
                        <span><p>Tipo:</p></span>
                        <select id="tipo"><option value="0">Ingresar Nuevo</option><option value="1">Producto Existente</option><!--<option value="2">Producto Existente Externo</option>--></select>
                    </label>
                    
                    <div id="tipo-0">
                        <label class="clearfix">
                            <span><p>Numero:</p></span>
                            <input id="numero" name="producto_numero" class="inputs" type="text" value="<?php echo $that["numero"]; ?>" require="" placeholder="" />
                        </label>
                        <label class="clearfix">
                            <span><p>Nombre:</p></span>
                            <input id="nombre" name="producto_nombre" class="inputs" type="text" value="<?php echo $that["nombre"]; ?>" require="" placeholder="" />
                        </label>
                        <label class="clearfix">
                            <span><p>Nombre Carro:</p></span>
                            <input id="nombre_carro" name="producto_nombre_carro" class="inputs" type="text" value="<?php echo $that["nombre_carro"]; ?>" require="" placeholder="" />
                        </label>
                        <label class="clearfix">
                            <span><p>Descripcion:</p></span>
                            <input id="descripcion" name="producto_descripcion" class="inputs" type="text" value="<?php echo $that["descripcion"]; ?>" require="" placeholder="" />
                        </label>
                        <label class="clearfix">
                            <span><p>Precio:</p></span>
                            <input id="precio" name="producto_precio" class="inputs" type="text" value="<?php echo $that["precio"]; ?>" require="" placeholder="" />
                        </label>
                    </div>
                    
                    <div id="tipo-1" class="newform" style="display: none">
                        <label class="clearfix">
                            <span><p>Lista de Productos:</p></span>
                            <div class="groupdetail">
                                <?php 
                                for($i=0; $i<count($all_prods); $i++){ 
                                    $mostrar=true;
                                    for($j=0; $j<count($list); $j++){ 
                                        if($all_prods[$i]['id_pro'] == $list[$j]['id_pro']){ 
                                            $mostrar=false;
                                        }
                                    }
                                    if($mostrar){

                                        $nombre_x = $all_prods[$i]['nombre'];
                                        if($all_prods[$i]['nombre_carro'] != ""){
                                            $nombre_x = $all_prods[$i]['nombre_carro'];
                                        }
                                ?>
                                    <div class="clearfix">
                                        <input style="float: left; width: 20px; height: 20px; margin-top: 4px" id="prod-<?php echo $all_prods[$i]['id_pro']; ?>" type="checkbox" value="1" <?php echo $check; ?> />
                                        <div style="float: left; padding-left: 10px; font-size: 18px"><?php echo $nombre_x; ?></div>
                                    </div>
                                <?php }} ?>
                            </div>
                        </label>
                    </div>
                    
                    <div id="tipo-2" style="display: none">
                        <label class="clearfix">
                            <span><p>Buscar:</p></span>
                            <input id="buscar" name="producto_buscar" class="inputs" type="text" value="" require="" placeholder="" />
                        </label>
                    </div>

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
                    <li class="opt"><div onclick="navlink('pages/msd/crear_productos.php?id=<?php echo $id; ?>&parent_id=<?php echo $parent_id; ?>&sortable=1')" class="order"></div></li>
                </ul>
            </div>
            <div class="listado_items">
                <?php 
                
                for($i=0; $i<count($list); $i++){
                    $id_n = $list[$i][$id_list];
                    $nombre = $list[$i]['nombre'];
                    $nombre_carro = $list[$i]['nombre_carro'];
                    $numero = $list[$i]['numero'];

                    if($nombre_carro != ""){
                        if($numero > 0){
                            $nombre_mostrar = $numero.".- ".$nombre_carro;
                        }else{
                            $nombre_mostrar = $nombre_carro;
                        }
                    }else{
                        if($numero > 0){
                            $nombre_mostrar = $numero.".- ".$nombre;
                        }else{
                            $nombre_mostrar = $nombre;
                        }
                    }

                ?>
                <div class="l_item" rel="<?php echo $id_n; ?>">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre_mostrar; ?></div>
                        <a title="Modificar" class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id=<?php echo $id; ?>&id_pro=<?php echo $id_n; ?>')"></a>
                        <a title="Configurar Producto" class="icono ic7" onclick="navlink('pages/msd/configurar_producto.php?id_pro=<?php echo $id_n; ?>&id=<?php echo $id; ?>&parent_id=<?php echo $parent_id; ?>')"></a>
                        <!--<a title="Configurar Producto" class="icono ic12" onclick="navlink('pages/msd/producto_ingredientes.php?id_pro=<?php echo $id_n; ?>&id=<?php echo $id; ?>&parent_id=<?php echo $parent_id; ?>')"></a>-->
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>