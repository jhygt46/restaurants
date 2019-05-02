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

$sub_titulo = "Establecer Configuracion";
$accion = "configurar_giro";
/* CONFIG PAGE */

$that = $fireapp->get_giro();

$titulo = "Configuracion ".$that["nombre"];
$css = $fireapp->get_css();
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

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
                        <span><p>Titulo Web:</p></span>
                        <input id="titulo" type="text" class="inputs" value="<?php echo $that['titulo']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Logo: (260x100)</p></span>
                        <input id="file_image0" type="file" />
                    </label>
                    <label class="clearfix">
                        <span><p>Favicon:</p></span>
                        <input id="file_image1" type="file" />
                    </label>
                    <label class="clearfix">
                        <span><p>Alto Categoria-Productos:</p></span>
                        <select id="alto">
                            <?php
                                for($i=15; $i<41; $i++){
                                    $sel = '';
                                    if($i == $that['alto']){ $sel='selected'; }
                                    echo '<option value="'.$i.'" '.$sel.'>'.$i.'%</option>';
                                }
                            ?>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Pedido Minimo:</p></span>
                        <input id="pedido_minimo" type="text" class="inputs" value="<?php echo $that['pedido_minimo']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Titulo #1:</p></span>
                        <input id="titulo_01" type="text" class="inputs" value="<?php echo $that['pedido_01_titulo']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix" style="margin-top: 0px">
                        <span><p>Subtitulo #1:</p></span>
                        <input id="subtitulo_01" type="text" class="inputs" value="<?php echo $that['pedido_01_subtitulo']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Titulo #2:</p></span>
                        <input id="titulo_02" type="text" class="inputs" value="<?php echo $that['pedido_02_titulo']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix" style="margin-top: 0px">
                        <span><p>Subtitulo #2:</p></span>
                        <input id="subtitulo_02" type="text" class="inputs" value="<?php echo $that['pedido_02_subtitulo']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Titulo #3:</p></span>
                        <input id="titulo_03" type="text" class="inputs" value="<?php echo $that['pedido_03_titulo']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix" style="margin-top: 0px">
                        <span><p>Subtitulo #3:</p></span>
                        <input id="subtitulo_03" type="text" class="inputs" value="<?php echo $that['pedido_03_subtitulo']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Titulo #4:</p></span>
                        <input id="titulo_04" type="text" class="inputs" value="<?php echo $that['pedido_04_titulo']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix" style="margin-top: 0px">
                        <span><p>Subtitulo #4:</p></span>
                        <input id="subtitulo_04" type="text" class="inputs" value="<?php echo $that['pedido_04_subtitulo']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix" style="margin-top: 0px">
                        <span><p>Google Maps Code:</p></span>
                        <input id="mapcode" type="text" class="inputs" value="<?php echo $that['mapcode']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Gengibre:</p></span>
                        <input id="pedido_gengibre" type="checkbox" class="checkbox" value="1" <?php if($that['pedido_gengibre'] == 1){ ?>checked="checked"<?php } ?>>
                    </label>
                    <label class="clearfix">
                        <span><p>Wasabi:</p></span>
                        <input id="pedido_wasabi" type="checkbox" class="checkbox" value="1" <?php if($that['pedido_wasabi'] == 1){ ?> checked="checked"<?php } ?>>
                    </label>
                    <label class="clearfix">
                        <span><p>Soya:</p></span>
                        <input id="pedido_soya" type="checkbox" class="checkbox" value="1" <?php if($that['pedido_soya'] == 1){ ?> checked="checked"<?php } ?>>
                    </label>
                    <label class="clearfix">
                        <span><p>Teriyaki:</p></span>
                        <input id="pedido_teriyaki" type="checkbox" class="checkbox" value="1" <?php if($that['pedido_teriyaki'] == 1){ ?> checked="checked"<?php } ?>>
                    </label>
                    <label class="clearfix">
                        <span><p>Palitos:</p></span>
                        <input id="pedido_palitos" type="checkbox" class="checkbox" value="1" <?php if($that['pedido_palitos'] == 1){ ?> checked="checked"<?php } ?>>
                    </label>
                    <label class="clearfix">
                        <span><p>Comentarios:</p></span>
                        <input id="pedido_comentarios" type="checkbox" class="checkbox" value="1" <?php if($that['pedido_comentarios'] == 1){ ?> checked="checked"<?php } ?>>
                    </label>
                    <label class="clearfix">
                        <span><p>Estados</p></span>
                        <input id="estados" type="text" class="inputs" value="<?php echo $that['estados']; ?>" require="" placeholder="" />
                    </label>
                    <label>
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>