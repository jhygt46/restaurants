<?php
session_start();

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}

require_once($path."admin/class/core_class.php");
$fireapp = new Core();

$loc_nombre = $_GET["nombre"];
/* CONFIG PAGE */
$titulo = "Horarios de Atencion de ".$loc_nombre;
$titulo_list = "Mis Horarios";
$sub_titulo1 = "Ingresar Horario";
$sub_titulo2 = "Modificar Horario";
$accion = "crear_horario";

$eliminaraccion = "eliminar_horario";
$id_list = "id_hor";
$eliminarobjeto = "Horario";
$page_mod = "pages/msd/crear_horario.php";
/* CONFIG PAGE */

$id_hor = 0;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$sub_titulo = $sub_titulo1;
$dias = ["", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo"];

if(isset($_GET["id_loc"]) && is_numeric($_GET["id_loc"]) && $_GET["id_loc"] != 0){
    
    $id_loc = $_GET["id_loc"];
    $list = $fireapp->get_horarios($id_loc);
    
    if(isset($_GET["id_hor"]) && is_numeric($_GET["id_hor"]) && $_GET["id_hor"] != 0){

        $id_hor = $_GET["id_hor"];
        $that = $fireapp->get_horario($id_loc, $id_hor);
        $sub_titulo = $sub_titulo2;

    }

}

?>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/locales.php')"></li>
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
                    <input id="id" type="hidden" value="<?php echo $id_hor; ?>" />
                    <input id="id_loc" type="hidden" value="<?php echo $id_loc; ?>" />
                    <input id="loc_nombre" type="hidden" value="<?php echo $loc_nombre; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Dia Inicio:</p></span>
                        <select id="dia_ini">
                            <?php for($i=1; $i<count($dias); $i++){ $s=''; if($that['dia_ini'] == $i){ $s='selected'; } ?>
                            <option value="<?php echo $i; ?>" <?php echo $s; ?>><?php echo $dias[$i]; ?></option>
                            <?php } ?>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Dia Fin:</p></span>
                        <select id="dia_fin">
                        <?php for($i=1; $i<count($dias); $i++){ $s=''; if($that['dia_fin'] == $i){ $s='selected'; } ?>
                            <option value="<?php echo $i; ?>" <?php echo $s; ?>><?php echo $dias[$i]; ?></option>
                            <?php } ?>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Hora Inicio:</p></span>
                        <select id="hora_ini" style="width: 41%">
                            <?php for($i=0; $i<34; $i++){ $s=''; if($that['hora_ini'] == $i){ $s='selected'; } $j = $i; $m = $i; if($i> 24){ $j = $i - 24; } ?>
                                <option value="<?php echo $m; ?>" <?php echo $s; ?>><?php echo $j; ?> hrs</option>
                            <?php } ?>
                        </select>
                        <select id="min_ini" style="width: 41%; margin-left: 2%">
                            <?php for($i=0; $i<60; $i++){ $s=''; if($that['min_ini'] == $i){ $s='selected'; } ?>
                                <option value="<?php echo $i; ?>" <?php echo $s; ?>><?php echo $i; ?> min</option>
                            <?php } ?>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Hora Fin:</p></span>
                        <select id="hora_fin" style="width: 41%">
                            <?php for($i=0; $i<34; $i++){ $s=''; if($that['hora_fin'] == $i){ $s='selected'; } $j = $i; $m = $i; if($i> 24){ $j = $i - 24; } ?>
                                <option value="<?php echo $m; ?>" <?php echo $s; ?>><?php echo $j; ?> hrs</option>
                            <?php } ?>
                        </select>
                        <select id="min_fin" style="width: 41%; margin-left: 2%">
                            <?php for($i=0; $i<60; $i++){ $s=''; if($that['min_fin'] == $i){ $s='selected'; } ?>
                                <option value="<?php echo $i; ?>" <?php echo $s; ?>><?php echo $i; ?> min</option>
                            <?php } ?>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Tipo:</p></span>
                        <select id="tipo">
                            <option value="0" <?php if($that['tipo'] == 0){ echo "selected"; } ?>>Ambas</option>
                            <option value="1" <?php if($that['tipo'] == 1){ echo "selected"; } ?>>Solo Retiro</option>
                            <option value="2" <?php if($that['tipo'] == 2){ echo "selected"; } ?>>Solo Despacho</option>
                        </select>
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

                for($i=0; $i<count($list); $i++){
                    $id = $list[$i][$id_list];
                    
                    switch ($list[$i]['dia_ini']) {   
                        case 1:
                            $dia_ini = "Lunes";
                            break;
                        case 2:
                            $dia_ini = "Martes";
                            break;
                        case 3:
                            $dia_ini = "Miercoles";
                            break;
                        case 4:
                            $dia_ini = "Jueves";
                            break;
                        case 5:
                            $dia_ini = "Viernes";
                            break;
                        case 6:
                            $dia_ini = "Sabado";
                            break;
                        case 7:
                            $dia_ini = "Domingo";
                            break;  
                    }
                    switch ($list[$i]['dia_fin']) {   
                        case 1:
                            $dia_fin = "Lunes";
                            break;
                        case 2:
                            $dia_fin = "Martes";
                            break;
                        case 3:
                            $dia_fin = "Miercoles";
                            break;
                        case 4:
                            $dia_fin = "Jueves";
                            break;
                        case 5:
                            $dia_fin = "Viernes";
                            break;
                        case 6:
                            $dia_fin = "Sabado";
                            break;
                        case 7:
                            $dia_fin = "Domingo";
                            break;  
                    }

                    $hora_ini = $list[$i]['hora_ini'].":".$list[$i]['min_ini'];
                    $hora_fin = $list[$i]['hora_fin'].":".$list[$i]['min_fin'];
                    $nombre = "De ".$dia_ini." a ".$dia_fin." desde las ".$hora_ini." hasta ".$hora_fin;

                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <a class="icono ic11" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>/<?php echo $id_loc; ?>/<?php echo $loc_nombre; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id_hor=<?php echo $id; ?>&id_loc=<?php echo $id_loc; ?>&nombre=<?php echo $loc_nombre; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>