<?php
session_start();

require_once("../../class/core_class.php");
require_once("../../idioma/es-CL.php");
$fireapp = new Core();
//$fireapp->seguridad_exit(array(48));

/* CONFIG PAGE */
$titulo = "Preguntas";
$titulo_list = "Mis Preguntas";
$sub_titulo1 = "Ingresar Pregunta";
$sub_titulo2 = "Modificar Pregunta";
$accion = "crear_preguntas";

$eliminaraccion = "eliminar_preguntas";
$id_list = "id_pre";
$eliminarobjeto = "Pregunta";
$page_mod = "pages/apps/preguntas.php";
/* CONFIG PAGE */

$id_pre = 0;
$sub_titulo = $sub_titulo1;

$catalogo = $fireapp->get_catalogo();
$titulo = $titulo." de ".$catalogo['nombre'];
$list = $fireapp->get_preguntas();

if(isset($_GET["id_pre"]) && is_numeric($_GET["id_pre"]) && $_GET["id_pre"] != 0){

    $id_pre = $_GET["id_pre"];
    $that = $fireapp->get_pregunta($id_pre);
    $that_valores = $fireapp->get_pregunta_valores($id_pre);
    $sub_titulo = $sub_titulo2;
    $cantidad = (count($that_valores)>0) ? count($that_valores)+1 : 1 ;

}
    






?>

<script>

    function cambiar_cantidad(that){
        
        var value = $(that).val();
        var rel = parseInt($(that).attr('rel')) + 1;
        var cantidad = $('.opciones').find('.opcion').length;
        if(cantidad <= rel){
            $('.opciones').append('<div class="opcion"><label><span>Seleccionar:</span><select id="cant-'+rel+'" rel="'+rel+'" onchange="cambiar_cantidad(this)"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="8">8</option><option value="9">9</option></select></label><label><span>Valores:</span><input id="valores-'+rel+'" type="text" value="" require="" placeholder="" /></label></div>');
            $('#cantidad').val(rel);
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
                    <input id="id_pre" type="hidden" value="<?php echo $id_pre; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <input id="cantidad" type="hidden" value="<?php echo $cantidad; ?>" />
                    <label>
                        <span>Nombre:</span>
                        <input id="nombre" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <label>
                        <span>Texto Mostrar:</span>
                        <input id="mostrar" type="text" value="<?php echo $that['mostrar']; ?>" require="" placeholder="" />
                    </label>
                    <div style="padding-top: 10px">
                        <div class="opciones">
                            <?php 
                                
                                for($i=0; $i<$cantidad; $i++){ 
                                    
                                    $aux_cant = 0;
                                    $aux_valores = "";
                                    if($that_valores[$i]['cantidad'] !== null){
                                        $aux_cant = $that_valores[$i]['cantidad'];
                                        $aux_nombre = $that_valores[$i]['nombre'];
                                        $aux_valores = implode(",", json_decode($that_valores[$i]['valores']));
                                    }
                                
                            ?>        
                                <div class='opcion'>
                                    <label>
                                        <span>Nombre:</span>
                                        <input id="valores-<?php echo $i; ?>" type="text" value="<?php echo $aux_nombre; ?>" require="" placeholder="" />
                                    </label>
                                    <label>
                                        <span>Seleccionar:</span>
                                        <select id="cant-<?php echo $i; ?>" rel="<?php echo $i; ?>" onchange="cambiar_cantidad(this)">
                                            <?php for($j=0; $j<10; $j++){ ?>
                                            <option value="<?php echo $j; ?>" <?php if($j == $aux_cant){ echo "selected"; } ?>><?php echo $j; ?></option>
                                            <?php } ?>
                                        </select>
                                    </label>
                                    <label>
                                        <span>Valores:</span>
                                        <input id="valores-<?php echo $i; ?>" type="text" value="<?php echo $aux_valores; ?>" require="" placeholder="" />
                                    </label>
                                </div>
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

<div class="info">
    <div class="fc" id="info-0">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name"><?php echo $titulo_list; ?></div>
        <div class="message"></div>
        <div class="sucont">
            
            <ul class='listUser'>
                
                <?php 
                for($i=0; $i<count($list); $i++){
                    
                    $id_n = $list[$i][$id_list];
                    $nombre = $list[$i]['nombre'];
                        
                ?>
                
                <li class="user">
                    <ul class="clearfix">
                        <li class="nombre"><?php echo $nombre; ?></li>
                        <a title="Eliminar" class="icn borrar" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>/<?php echo $id_n; ?>/<?php echo $parent_id; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a title="Modificar" class="icn modificar" onclick="navlink('<?php echo $page_mod; ?>?id=<?php echo $id; ?>&id_pre=<?php echo $id_n; ?>&parent_id=<?php echo $parent_id; ?>')"></a>
                    </ul>
                </li>
                
                <?php } ?>
                
            </ul>
            
        </div>
    </div>
</div>
<br />
<br />