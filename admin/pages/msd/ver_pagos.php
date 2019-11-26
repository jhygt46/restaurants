<?php

date_default_timezone_set('America/Santiago');

if(!isset($core_class_iniciada)){
    if($_SERVER["HTTP_HOST"] == "localhost"){
        define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
        define("DIR", DIR_BASE."restaurants/");
    }else{
        define("DIR_BASE", "/var/www/html/");
        define("DIR", DIR_BASE."restaurants/");
    }
    require_once DIR."admin/class/core_class_prod.php";
    $core = new Core();
}

// SOLO ADMIN
if($core->id_user == 0){
    die("Error: su sesion ha expirado");
}

$list = $core->get_pagos_giros($_GET["id_gir"]);

echo "<pre>";
print_r($list);
echo "</pre>";

/* CONFIG PAGE */
$titulo = "Pagos de ".$list['dominio'];
$titulo_list = "Lista de Pagos";
$sub_titulo = "Ingresar Pago";
$accion = "crear_pago";

$id_list = "id_gir";
$eliminarobjeto = "Empresa";
$page_ver_pagos_giro = "pages/msd/ver_pagos.php";
/* CONFIG PAGE */

$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

?>
<script>

    function cambiar_meses(){
        var monto = <?php echo $list['monto']; ?>;
        var meses = $('#meses').val();
        if(meses == 1){
            $('#monto').val(monto);
        }
        if(meses == 6){
            $('#monto').val(monto * 5.5);
        }
        if(meses == 12){
            $('#monto').val(monto * 10);
        }
    }

</script>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
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
                    <input id="id" type="hidden" value="<?php echo $id_gir; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Numero de Factura:</p></span>
                        <input id="nombre" class="inputs" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Meses:</p></span>
                        <select id="meses" onchange="cambiar_meses()">
                            <option value="1">1</option>
                            <option value="6">6</option>
                            <option value="12">12</option>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Monto:</p></span>
                        <input id="monto" class="inputs" type="text" value="<?php echo $list['monto']; ?>" require="" placeholder="" />
                    </label>
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

                foreach($list as $clave => $value){

                    $fechainicial = new DateTime($value['fecha_dns']);
                    $fechafinal = new DateTime();
                    $diferencia = $fechainicial->diff($fechafinal);
                    $meses = ( $diferencia->y * 12 ) + $diferencia->m;
                    $diff_pago = $meses - $value['cpagos'];

                    if($diff_pago > 0){

                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $value['dominio']; ?></div>
                        <a class="icono ic18" onclick="navlink('<?php echo $page_ver_pagos_giro; ?>?id_gir=<?php echo $clave; ?>')"></a>
                    </div>
                </div>
                <?php }} ?>
            </div>
        </div>
    </div>
</div>
