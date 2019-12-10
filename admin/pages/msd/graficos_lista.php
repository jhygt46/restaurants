<?php

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."restaurants/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."restaurants/");
}

require_once DIR."admin/class/core_class_prod.php";
$core = new Core();


/* CONFIG PAGE */
$titulo = "Lista Graficos";
$titulo_list = "Estadisticas";
$sub_titulo = "Configurar Grafico";
/* CONFIG PAGE */

$id = 0;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

if(isset($_GET["id_set"]) && is_numeric($_GET["id_set"]) && $_GET["id_set"] != 0){

    $id = $_GET["id_set"];
    $list = $core->get_graficos_lista($id);
    echo "<pre>";
    print_r($list);
    echo "</pre>";
    
}

?>

<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/graficos.php')"></li>
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
                        <span><p>Grafico 1:</p></span>
                        <input id="gra-1" type="checkbox" class="checkbox local" value="1" <?php if($i == 0){ echo "checked='checked'"; } ?>>
                    </label>
                    <label class="clearfix">
                        <span><p>Grafico 1:</p></span>
                        <input id="gra-2" type="checkbox" class="checkbox local" value="1" <?php if($i == 0){ echo "checked='checked'"; } ?>>
                    </label>
                    <label>
                        <div class="enviar"><a onclick="stats(this)">Enviar</a></div>
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
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id_loc=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>


</div>