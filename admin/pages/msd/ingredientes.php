<?php
session_start();

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}

require_once($path."admin/class/core_class.php");
$fireapp = new Core();
if(isset($_GET['id_cat'])){ $fireapp->is_catalogo($_GET['id_cat']); }
$list = $fireapp->get_lista_ingredientes();

/* CONFIG PAGE */
$titulo = "Ingredientes";
$titulo_list = "Mis Listas";
$sub_titulo1 = "Ingresar Lista de Ingredientes";
$sub_titulo2 = "Modificar Lista de Ingredientes";
$accion = "crear_lista_ingredientes";

$eliminaraccion = "eliminar_lista_ingredientes";
$id_list = "id_lin";
$eliminarobjeto = "Lista de Ingredientes";
$page_mod = "pages/msd/ingredientes.php";
/* CONFIG PAGE */

$id = 0;
$sub_titulo = $sub_titulo1;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$ingredientes = $fireapp->get_ingredientes_base();

if(isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] != 0){
    
    $sub_titulo = $sub_titulo2;
    $lista = $fireapp->get_lista_ingrediente($_GET["id"]);
    $valores = $fireapp->get_lista_precio_ingredientes($_GET["id"]);
    $id = $_GET["id"];
    
}

?>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/ver_giro.php?id_gir=<?php echo $_SESSION['user']['id_gir']; ?>')"></li>
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
                        <span><p>Nombre:</p></span>
                        <input id="nombre" class="inputs" type="text" value="<?php echo $lista['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <div style="display: block; height: 200px; overflow-y: auto">
                        <?php for($i=0; $i<count($ingredientes); $i++){ ?>             
                        <?php 
                            $value = "";
                            for($j=0; $j<count($valores); $j++){
                                if($valores[$j]['id_ing'] == $ingredientes[$i]['id_ing']){
                                    $value = $valores[$j]['valor'];
                                }
                            }
                        ?> 
                        <label class="clearfix">
                            <span><p><?php echo $ingredientes[$i]['nombre']; ?></p></span>
                            <input id="ing-<?php echo $ingredientes[$i]['id_ing']; ?>" class="inputs" style="width: 50px" type="text" value="<?php echo $value; ?>" require="" placeholder="" />
                        </label>
                        <?php } ?>
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
                    <li class="opt">1</li>
                    <li class="opt">2</li>
                </ul>
            </div>
            <div class="listado_items">
                <?php 
                for($i=0; $i<count($list); $i++){
                    $id = $list[$i][$id_list];
                    $nombre = $list[$i]['nombre'];
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id=<?php echo $id; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>