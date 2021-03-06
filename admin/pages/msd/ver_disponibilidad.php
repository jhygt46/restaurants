<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
$url = url();

require_once $url["dir"]."admin/class/core_class_prod.php";
$core = new Core();

if($core->admin == 0){
    die('<div class="pagina"><div class="title"><h1>Error:</h1></div></div>');
}

$titulo = "Verificar Dominio";
$sub_titulo = "Ingrese el dominio y verifique si esta disponible";
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
?>
<script>
    function verificar_dominio(){
        var send = { accion: 'verificar_dominio_existente', nombre: $('#nombre').val() };
        $.ajax({
            url: "ajax/",
            type: "POST",
            data: send,
            success: function(data){
                if(data == 0){
                    $('#disponibilidad').val("Disponible");
                    $('#disponibilidad').css({ color: '#090' });
                }
                if(data == 1){
                    $('#disponibilidad').val("No disponible");
                    $('#disponibilidad').css({ color: '#900' });
                }
            },
            error: function(e){}
        });
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
                    <label class="clearfix">
                        <span><p>Dominio:</p></span>
                        <input id="nombre" type="text" class="inputs" value="www." require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Disponibilidad</p></span>
                        <input id="disponibilidad" style="border: 0px; background: none" type="text" class="inputs" value="" require="" placeholder="" />
                    </label>
                    <label>
                        <div class="enviar"><a onclick="verificar_dominio()">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>