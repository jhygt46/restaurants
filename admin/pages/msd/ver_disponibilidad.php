<?php

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."restaurants/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."restaurants/");
}

require_once DIR."admin/class/core_class_prod.php";
$core = new Core();

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
                    $('.disponibilidad').html("Disponible");
                    $('.disponibilidad').css({ color: '#090' });
                }
                if(data == 1){
                    $('.disponibilidad').html("No disponible");
                    $('.disponibilidad').css({ color: '#900' });
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
                        <span><p class="disponibilidad" style="font-size: 20px; font-weight: bold"></p></span>
                    </label>
                    <label>
                        <div class="enviar"><a onclick="verificar_dominio()">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>