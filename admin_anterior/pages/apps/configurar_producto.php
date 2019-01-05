<?php
session_start();

require_once("../../class/core_class.php");
$fireapp = new Core();
//$fireapp->seguridad_exit(array(48));


/* CONFIG PAGE */
$titulo = "Configuracion ".$_GET["nombre"];
$sub_titulo1 = "Modificar Configuracion";
$accion = "configurar_producto";
/* CONFIG PAGE */


$id_cae = 0;
$hijos = false;
$sub_titulo = $sub_titulo1;

if(isset($_GET["id_pro"]) && is_numeric($_GET["id_pro"]) && $_GET["id_pro"] != 0){
    
    $list = $fireapp->get_preguntas();
    $id_pro = $_GET["id_pro"];
    $pre_prod = $fireapp->get_preguntas_pro($id_pro);
    
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
                    <input id="id" type="hidden" value="<?php echo $_GET['id']; ?>" />
                    <input id="id_pro" type="hidden" value="<?php echo $id_pro; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label>
                        <span>Imagen:</span>
                        <input id="file_image" type="file" />
                    </label>
                    <div class="newform"  style="margin-left: 16%; margin-right: 9%; width: 75%">
                        <div class='grupo'>Preguntas</div>
                        <div class="groupdetail">
                            <?php foreach($list as $value){ $checked = ''; for($i=0; $i<count($pre_prod); $i++){ if($value['id_pre'] == $pre_prod[$i]['id_pre']){ $checked="checked='checked'"; } } ?>
                            <label>
                                <input id="pregunta-<?php echo $value['id_pre']; ?>" <?php echo $checked; ?> type="checkbox" value="1" />
                                <span class='detail'><?php echo $value['nombre']; ?></span>
                            </label>
                            <?php } ?>
                        </div>
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
<br />
<br />
