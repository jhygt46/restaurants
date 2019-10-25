<?php

require_once("/var/www/html/restaurants/admin/class/core_class_prod.php");
$core = new Core();
$core->is_catalogo();

/* CONFIG PAGE */
$titulo = "Categorias";
$titulo_list = "Mis Categorias";
$sub_titulo1 = "Ingresar Categoria";
$sub_titulo2 = "Modificar Categoria";
$accion = "crear_categoria";

$id_list = "id_cae";
$page_mod = "pages/msd/categorias.php";
/* CONFIG PAGE */

$id_cae = 0;
$sub_titulo = $sub_titulo1;

if(isset($_GET["parent_id"]) && $_GET["parent_id"] > 0){
    $parent_id = $_GET["parent_id"];
    $that_2 = $core->get_categoria($parent_id);
    $titulo = "Categoria ".$that_2["nombre"];
}else{
    $parent_id = 0;
    $titulo = "Categorias";
}

$list = $core->get_categorias();
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

for($i=0; $i<count($list); $i++){
    if($list[$i]['id_cae'] == $parent_id){
        $p_id = $list[$i]['parent_id'];
    }
}

$that = ["nombre" => "", "descripcion" => "", "descripcion_sub" => "", "precio" => 0, "tipo" => 0];
if(isset($_GET["id_cae"]) && is_numeric($_GET["id_cae"]) && $_GET["id_cae"] != 0){

    $id_cae = $_GET["id_cae"];
    $that = $core->get_categoria($id_cae);
    $sub_titulo = $sub_titulo2;
    
}

?>

<?php if(isset($_GET['sortable'])){ ?>
<script>
    $('.listado_items').sortable({
        stop: function(e, ui){
            var order = [];
            $(this).find('.l_item').each(function(){
                order.push($(this).attr('rel'));
            });
            var send = {accion: 'ordercat', values: order};
            $.ajax({
                url: "ajax/index.php",
                type: "POST",
                data: send
            });
        }
    });
</script>
<?php } ?>

<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <?php if($parent_id > 0){ ?>
            <li class="back" onclick="navlink('pages/msd/categorias.php?parent_id=<?php echo $p_id; ?>')"></li>
            <?php }else{ ?>
            <li class="back" onclick="navlink('pages/msd/ver_giro.php?id_gir=<?php echo $_SESSION['user']['id_gir']; ?>')"></li>
            <?php } ?>
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
                    <input id="id" type="hidden" value="<?php echo $id_cae; ?>" />
                    <input id="parent_id" type="hidden" value="<?php echo $parent_id; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Nombre:</p></span>
                        <input id="nombre" name="categoria_nombre" class="inputs" type="text" value="<?php echo $that["nombre"]; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Descripcion Inicio:</p></span>
                        <input id="descripcion" name="categoria_descripcion" class="inputs" type="text" value="<?php echo $that["descripcion"]; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Descripcion Subtitulo:</p></span>
                        <input id="descripcion_sub" name="categoria_descripcion" class="inputs" type="text" value="<?php echo $that["descripcion_sub"]; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Precio:</p></span>
                        <input id="precio" name="categoria_precio" class="inputs" type="text" value="<?php echo $that["precio"]; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Tipo:</p></span>
                        <select id="tipo"><option value="0" <?php echo ($that['tipo'] == 0) ? 'selected' : '' ; ?>>Categoria</option><option value="1" <?php echo ($that['tipo'] == 1) ? 'selected' : '' ; ?>>Promocion</option></select>
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
                    <li class="opt"><div onclick="navlink('pages/msd/categorias.php?parent_id=<?php echo $parent_id; ?>&sortable=1')" class="order"></div></li>
                </ul>
            </div>
            <div class="listado_items">
                <?php 
                for($i=0; $i<count($list); $i++){
                    
                    $id_n = $list[$i][$id_list];
                    $nombre = $list[$i]['nombre'];
                    $tipo = $list[$i]['tipo'];
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
                        
                        $aux['sub_categoria'] = false;
                        $aux['productos'] = false;
                        $aux['promocion'] = false;
                        
                        if($tipo == 0){
                            if(!$childs){
                                $aux['productos'] = true;
                            }
                            if($childs || (!$childs && !$prods)){
                                $aux['sub_categoria'] = true;
                            }
                        }
                        if($tipo == 1){
                            $aux['promocion'] = true;
                        }

                ?>
                <div class="l_item" rel="<?php echo $id_n; ?>">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id_cae=<?php echo $id_n; ?>&parent_id=<?php echo $parent_id; ?>')"></a>
                        <a class="icono ic7" onclick="navlink('pages/msd/configurar_categoria.php?id_cae=<?php echo $id_n; ?>&nombre=<?php echo $nombre; ?>&parent_id=<?php echo $parent_id; ?>')"></a>
                        <?php if($aux['sub_categoria']){ ?><a class="icono ic8" onclick="navlink('pages/msd/categorias.php?parent_id=<?php echo $id_n; ?>')"></a><?php } ?>
                        <?php if($aux['promocion']){ ?><a class="icono ic9" onclick="navlink('pages/msd/crear_promocion.php?id_cae=<?php echo $id_n; ?>&parent_id=<?php echo $parent_id; ?>')"></a><?php } ?>
                        <?php if($aux['productos']){ ?><a class="icono ic10" onclick="navlink('pages/msd/crear_productos.php?id=<?php echo $id_n; ?>&parent_id=<?php echo $parent_id; ?>')"></a><?php } ?>
                    </div>
                </div>
                <?php }} ?>
            </div>
        </div>
    </div>
</div>