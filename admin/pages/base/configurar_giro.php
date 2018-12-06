<?php
session_start();

require_once("../../class/core_class.php");
$fireapp = new Core();


/* CONFIG PAGE */
$titulo = $_GET["nombre"];
$sub_titulo1 = "Seleccione Rubro";
$accion = "asignar_rubro";

$eliminaraccion = "eliminar_rubro";
$id_list = "id_rub";
$eliminarobjeto = "Rubro";
/* CONFIG PAGE */


$id = 0;
$sub_titulo = $sub_titulo1;
if(isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] != 0){
    
    $that = $fireapp->get_palabras_giro($_GET["id"]);
    //$apps = $fireapp->get_apps_giro($_GET["id"]);
    $count = (count($that) > 20) ? 20 : count($that);
    $id = $_GET["id"];
    
}

if($_SESSION['user']['info']['id_user'] == 1){
    echo "<div style='padding-top: 20px'>";
    echo "<div style='font-size: 28px; font-weight: bold'>".$accion."</div>";
    echo "<div style='font-size: 28px; font-weight: bold'>".$eliminaraccion."</div>";
    echo "<div style='font-size: 28px; font-weight: bold'>configurar_giro.php</div>";
    echo "</div>";
}


?>
<script>
    
    function buscar_rubro(id){
    
        var aux = document.getElementById(id);
        var txt = aux.value.toLowerCase();
        var nombre = "";
        var checked = false;
        var count = 0;
        
        if(txt.length >= 1){
            $('.list_rubros').find('.item_rubro').each(function(){
                nombre = $(this).find('.item_nombre').html().toLowerCase();
                checked = $(this).find('input').is(':checked');
                if((nombre.search(txt) != -1 || checked) && count < 20){
                    $(this).show();
                    count++;
                }else{
                    $(this).hide();
                }
            });
        }else{
            
        }
    }
    
</script>
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
                        <span>Buscar:</span>
                        <input id="buscar" type="text" onkeyup="buscar_rubro('buscar')" />
                    </label>
                    <div class="list_rubros">
                        <?php for($i=0; $i<$count; $i++){ ?>
                            <label class="item_rubro"><span class="item_nombre"><?php echo $that[$i]["nombre"]; ?></span><input id="rubro-<?php echo $that[$i]["id_pal"]; ?>" <?php if($that[$i]["id_gir"] !== null){ echo'checked="checked"'; } ?>  type="checkbox" value="1" /></label>
                        <?php } ?>
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
