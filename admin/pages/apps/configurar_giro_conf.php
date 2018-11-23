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

$sub_titulo = "Modificar Configuracion";
$accion = "configurar_principal";
/* CONFIG PAGE */

$that = $fireapp->get_giro();
$titulo = "Configuracion ".$that["nombre"];
$css = $fireapp->get_css();

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
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label>
                        <span>Titulo:</span>
                        <input id="titulo" type="text" value="<?php echo $that['titulo']; ?>" />
                    </label>
                    <label>
                        <span>Google Font Family:</span>
                        <input id="font-family" type="text" value="<?php echo $that['font_family']; ?>" />
                    </label>
                    <label>
                        <span>Google Font Css:</span>
                        <input id="font-css" type="text" value="<?php echo $that['font_css']; ?>" />
                    </label>
                    <label>
                        <span>Css Tipo Pagina:</span>
                        <select id="css-types">
                            <option value="">Seleccionar</option>
                            <?php 
                                foreach($css as $value){ 
                                    if($value['tipo'] == 1){
                                        $sel='';
                                        if($value['nombre'] == $that['style_page']){ $sel='selected'; }
                                        echo '<option value="'.$value['nombre'].'" '.$sel.'>'.$value['nombre'].'</option>';
                                    } 
                                }
                            ?>
                        </select>
                    </label>
                    <label>
                        <span>Css Colores:</span>
                        <select id="css-colores">
                            <option value="">Seleccionar</option>
                            <?php 
                                foreach($css as $value){ 
                                    if($value['tipo'] == 2){
                                        $sel='';
                                        if($value['nombre'] == $that['style_color']){ $sel='selected'; }
                                        echo '<option value="'.$value['nombre'].'" '.$sel.'>'.$value['nombre'].'</option>';
                                    } 
                                }
                            ?>
                        </select>
                    </label>
                    <label>
                        <span>Css Tamaño Letra:</span>
                        <select id="css-popup">
                            <option value="">Seleccionar</option>
                            <?php 
                                foreach($css as $value){ 
                                    if($value['tipo'] == 3){
                                        $sel='';
                                        if($value['nombre'] == $that['style_modal']){ $sel='selected'; }
                                        echo '<option value="'.$value['nombre'].'" '.$sel.'>'.$value['nombre'].'</option>';
                                    } 
                                }
                            ?>
                        </select>
                    </label>
                    <label>
                        <span>logo:</span>
                        <input id="file_image" type="file" />
                    </label>
                    <label>
                        <span>Pedido 01 Titulo:</span>
                        <input id="pedido_01_titulo" type="text" value="<?php echo $that['pedido_01_titulo']; ?>" />
                    </label>
                    <label>
                        <span>Pedido 01 Subtitulo:</span>
                        <input id="pedido_01_subtitulo" type="text" value="<?php echo $that['pedido_01_subtitulo']; ?>" />
                    </label>
                    <label>
                        <span>Pedido 02 Titulo:</span>
                        <input id="pedido_02_titulo" type="text" value="<?php echo $that['pedido_02_titulo']; ?>" />
                    </label>
                    <label>
                        <span>Pedido 02 Subtitulo:</span>
                        <input id="pedido_02_subtitulo" type="text" value="<?php echo $that['pedido_02_subtitulo']; ?>" />
                    </label>
                    <label>
                        <span>Pedido 03 Titulo:</span>
                        <input id="pedido_03_titulo" type="text" value="<?php echo $that['pedido_03_titulo']; ?>" />
                    </label>
                    <label>
                        <span>Pedido 03 Subtitulo:</span>
                        <input id="pedido_03_subtitulo" type="text" value="<?php echo $that['pedido_03_subtitulo']; ?>" />
                    </label>
                    <label>
                        <span>Pedido 04 Titulo:</span>
                        <input id="pedido_04_titulo" type="text" value="<?php echo $that['pedido_04_titulo']; ?>" />
                    </label>
                    <label>
                        <span>Pedido 04 Subtitulo:</span>
                        <input id="pedido_04_subtitulo" type="text" value="<?php echo $that['pedido_04_subtitulo']; ?>" />
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
<br />
<br />
