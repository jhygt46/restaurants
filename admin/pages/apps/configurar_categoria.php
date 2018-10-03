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
$titulo = "Configuracion ".$_GET["nombre"];
$sub_titulo1 = "Modificar Configuracion";
$accion = "configurar_categoria";
/* CONFIG PAGE */


$id_cae = 0;
$sub_titulo = $sub_titulo1;
$parent_id = (isset($_GET["parent_id"]))? $_GET["parent_id"] : 0 ;

if(isset($_GET["id_cae"]) && is_numeric($_GET["id_cae"]) && $_GET["id_cae"] != 0){
    
    $id_cae = $_GET["id_cae"];
    $that = $fireapp->get_categoria($id_cae);
    
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
                    <input id="id_cae" type="hidden" value="<?php echo $id_cae; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <input id="parent_id" type="hidden" value="<?php echo $parent_id; ?>" />
                    <label>
                        <span>Ocultar Categoria:</span>
                        <select id="ocultar"><option value="0">No</option><option value="1" <?php echo($that['ocultar'] == 1)?'selected':''; ?>>Si</option></select>
                    </label>
                    <label>
                        <span>Mostrar Productos:</span>
                        <select id="mostrar_prods"><option value="0">No</option><option value="1" <?php echo($that['mostrar_prods'] == 1)?'selected':''; ?>>Si</option></select>
                    </label>
                    <label>
                        <span>Imagen:</span>
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
