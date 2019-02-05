<?php
session_start();

require_once("../../class/core_class.php");
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
$page_mod = "pages/msd/preguntas.php";
/* CONFIG PAGE */

$id_pre = 0;
$sub_titulo = $sub_titulo1;
if(isset($_GET['id_cat'])){ $fireapp->is_catalogo($_GET['id_cat']); }
$list = $fireapp->get_preguntas();
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$cantidad = 1;

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
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/ver_giro.php')"></li>
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
                    <input id="id" type="hidden" value="<?php echo $id_pre; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <input id="cantidad" type="hidden" value="<?php echo $cantidad; ?>" />
                    <label class="clearfix">
                        <span><p>Nombre:</p></span>
                        <input id="nombre" class="inputs" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Texto Mostrar:</p></span>
                        <input id="mostrar" class="inputs" type="text" value="<?php echo $that['mostrar']; ?>" require="" placeholder="" />
                    </label>
                    
                        <?php 

                            for($i=0; $i<$cantidad; $i++){ 

                                $aux_cant = 0;
                                $aux_valores = "";
                                $aux_nombre = "";
                                if($that_valores[$i]['cantidad'] !== null){
                                    $aux_cant = $that_valores[$i]['cantidad'];
                                    $aux_nombre = $that_valores[$i]['nombre'];
                                    $aux_valores = implode(",", json_decode($that_valores[$i]['valores']));
                                }

                        ?>        
                            
                                <label class="clearfix">
                                    <span><p>Nombre:</p></span>
                                    <input id="nombre-<?php echo $i; ?>" class="inputs" type="text" value="<?php echo $aux_nombre; ?>" require="" placeholder="" />
                                </label>
                                <label class="clearfix" style="margin-top: 1px">
                                    <span><p>Seleccionar:</p></span>
                                    <select id="cant-<?php echo $i; ?>" rel="<?php echo $i; ?>" onchange="cambiar_cantidad(this)">
                                        <?php for($j=0; $j<10; $j++){ ?>
                                        <option value="<?php echo $j; ?>" <?php if($j == $aux_cant){ echo "selected"; } ?>><?php echo $j; ?></option>
                                        <?php } ?>
                                    </select>
                                </label>
                                <label  class="clearfix" style="margin-top: 1px">
                                    <span><p>Valores:</p></span>
                                    <input id="valores-<?php echo $i; ?>" class="inputs" type="text" value="<?php echo $aux_valores; ?>" require="" placeholder="" />
                                </label>
                            
                        <?php } ?>
                    
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
                    $dominio = $list[$i]['dominio'];
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <a class="icono ic11" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id_pre=<?php echo $id; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>