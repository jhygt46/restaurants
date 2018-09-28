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

$sub_titulo1 = "Seleccione";
$accion = "configurar_catalogo";
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
                        <span>Css Pagina:</span>
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
                        <span>Css Pop-up:</span>
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
