<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
    $url = url();
    redireccion_ssl();
    esconder_index();

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>MiSitioDelivery</title>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='shortcut icon' type='image/x-icon' href='/images/favicon/locales.ico' />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $url["path"]; ?>js/misitiodelivery.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Pattaya|Lato" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $url["path"]; ?>css/misitiodelivery.css" media="all" />
</head>
<body>
    <div class="contenedor">
        <div class="logo">MiSitioDelivery</div>
        <div class="cont_btns">
            <ul class="botones clearfix">
                <li onclick="go_pagina(0)" class="selected">Empezar</li>
                <li onclick="go_pagina(1)">Clientes</li>
                <li onclick="go_pagina(2)">Contacto</li>
            </ul>
        </div>
        <!--
        <div class="video">
            <video id="video1" width="100%">
                <source src="video-tutorial.mp4" type="video/mp4">
            </video>
        </div>
        <div class="playvideo" onclick="playvideo()">Reproducir Video</div>
        -->
        <div class="empezar">
            <div class="form formempezar">
                <h1>Crear tu Sitio Ahora Mismo!</h1>
                <h2>Ingresa estos simples datos</h2>
                <h3 id="dominio_msd_ttl">Tu Dominio</h3>
                <div class="input">
                    <input type="text" id="dominio_msd" placeholder="www.tusitio.cl" />
                </div>
                <h3 id="email_msd_ttl">Tu Correo</h3>
                <div class="input">
                    <input type="email" id="email_msd" placeholder="tucorreo@gmail.com" />
                </div>
                <h3 id="telefono_msd_ttl">Tu Telefono</h3>
                <div class="input">
                    <input type="tel" id="telefono_msd" value="+569" />
                </div>
                <div class="acciones">
                    <input type="submit" id="crear_dominio" onclick="crear_dominio()" value="Empezar Prueba Gratis" class="btn_empezar btn_color_1" />
                </div>
                <div class="mes_gratis">30 dias gratis</div>
                <small class="smallrecaptcha">This site is protected by reCAPTCHA and the Google 
                    <a href="https://policies.google.com/privacy">Privacy Policy</a> and
                    <a href="https://policies.google.com/terms">Terms of Service</a> apply.
                </small>
            </div>
            <div class="empezarok">
                <h1 class="titulo">Felicitaciones!</h1>
                <h1 class="subtitulo">Tu cuenta ha sido creada</h1>
                <h2 class="leyenda">Te hemos enviado un correo con las instrucciones</h2>
            </div>
        </div>
        <div class="clientes">
            <ul class="lista_clientes"> 
                <?php /*for($i=0; $i<count($list); $i++){ ?>
                <li>
                    <div style="background: <?php echo $list[$i]['back']; ?>" class="foto"><a target="_blank" href="<?php echo $list[$i]['proto']; ?>://<?php echo $list[$i]['link']; ?>"><img src="<?php echo $list[$i]['img']; ?>" alt="" /></a></div>
                    <div class="info"><a target="_blank" style="display: block; color: #000; text-decoration: none" href="<?php echo $list[$i]['proto']; ?>://<?php echo $list[$i]['link']; ?>"><?php echo $list[$i]['nombre']; ?></a><a target="_blank" style="display: block; color: #000; text-decoration: none; font-size: 20px" href="<?php echo $list[$i]['proto']; ?>://<?php echo $list[$i]['link']; ?>"><?php echo $list[$i]['link']; ?></a></div>
                </li>
                <?php }*/ ?>
            </ul>
        </div>
        <div class="contacto">
            <div class="form formcontacto">
                <h1>Formulario de Contacto</h1>
                <h2>Pronto nos contactaremos con usted</h2>
                <h3 id="nombre_con_ttl">Nombre</h3>
                <div class="input">
                    <input type="text" id="nombre_con" placeholder="Diego" />
                </div>
                <h3 id="email_con_ttl">Correo</h3>
                <div class="input">
                    <input type="email" id="email_con" placeholder="tucorreo@gmail.com" />
                </div>
                <h3 id="telefono_con_ttl">Telefono</h3>
                <div class="input">
                    <input type="tel" id="telefono_con" value="+56 9 " />
                </div>
                <h3 id="asunto_con_ttl">Asunto</h3>
                <div class="input">
                    <TextArea id="asunto_con"></TextArea>
                </div>
                <div class="acciones">
                    <input type="submit" id="enviar_contacto" onclick="enviar_contacto()" value="Enviar Solicitud" class="btn_empezar btn_color_2" />
                </div>
                <small class="smallrecaptcha">This site is protected by reCAPTCHA and the Google 
                    <a href="https://policies.google.com/privacy">Privacy Policy</a> and
                    <a href="https://policies.google.com/terms">Terms of Service</a> apply.
                </small>
            </div>
            <div class="contactook">
                <h1 class="titulo">Muchas Gracias!</h1>
                <h1 class="subtitulo">Tu mensaje ha sido enviado</h1>
                <h2 class="leyenda">A la brevedad te responderemos tu solicitud</h2>
            </div>
        </div>       
    </div>
    <script src="https://www.google.com/recaptcha/api.js?render=6LdZp78UAAAAAK56zJAVEkaSupUdCrRhsd1wnKkO"></script>
</body>
</html>