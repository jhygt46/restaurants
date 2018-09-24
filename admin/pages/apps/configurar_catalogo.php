<?php
session_start();

require_once("../../class/core_class.php");
$fireapp = new Core();
//$fireapp->seguridad_exit(array(48));


/* CONFIG PAGE */
$titulo = "Configuracion ".$_GET["nombre"];
$sub_titulo1 = "Seleccione";
$accion = "configurar_categoria";
/* CONFIG PAGE */


$id = 0;
$sub_titulo = $sub_titulo1;
if(isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] != 0){
    
    $id = $_GET["id"];
    $that = $fireapp->get_giro_catalogo($id);

    $css_types = array_diff(scandir('/var/www/html/restaurants/css/types'), array('.', '..'));
    $css_colors = array_diff(scandir('/var/www/html/restaurants/css/colors'), array('.', '..'));
    $css_modals = array_diff(scandir('/var/www/html/restaurants/css/modals'), array('.', '..'));
    
    
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
                    <input id="nombre" type="hidden" value="<?php echo $titulo; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label>
                        <span>Titulo:</span>
                        <input id="titulo" type="text" value="<?php echo $that['titulo']; ?>" />
                    </label>
                    <label>
                        <span>Google Font Family:</span>
                        <input id="titulo" type="text" value="<?php echo $that['font_family']; ?>" />
                    </label>
                    <label>
                        <span>Google Font Css:</span>
                        <input id="titulo" type="text" value="<?php echo $that['font_css']; ?>" />
                    </label>
                    <label>
                        <span>Css Pagina:</span>
                        <select>
                            <option value="">Seleccionar</option>
                            <?php foreach($css_types as $value){ $sel = ''; if($value == $that['style_page']){ $sel='selected'; } echo '<option value="'.$value.'" '.$sel.'>'.$value.'</option>'; } ?>
                        </select>
                    </label>
                    <label>
                        <span>Css Colores:</span>
                        <select>
                            <option value="">Seleccionar</option>
                            <?php foreach($css_colors as $value){ $sel = ''; if($value == $that['style_color']){ $sel='selected'; } echo '<option value="'.$value.'" '.$sel.'>'.$value.'</option>'; } ?>
                        </select>
                    </label>
                    <label>
                        <span>Css Pop-up:</span>
                        <select>
                            <option value="">Seleccionar</option>
                            <?php foreach($css_modals as $value){ $sel = ''; if($value == $that['style_modal']){ $sel='selected'; } echo '<option value="'.$value.'" '.$sel.'>'.$value.'</option>'; } ?>
                        </select>
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
