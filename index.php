<?php 

    if(strpos($_SERVER["REQUEST_URI"], "index.php") !== false){
        header('HTTP/1.1 404 Not Found', true, 404);
        include('errors/404.html');
        exit;
    }

    if($_GET["mode"] != "developer"){
        die("INDEX");
    }

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>MiSitioDelivery</title>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='shortcut icon' type='image/x-icon' href='/images/favicon/locales.ico' />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script type="text/javascript" src="js/misitiodelivery.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Pattaya|Lato" rel="stylesheet">
    <link rel="stylesheet" href="css/reset.css" media="all" />
    <link rel="stylesheet" href="css/misitiodelivery.css" media="all" />
</head>
<body>
    <div class="contenedor">

        <div class="logo">MiSitioDelivery</div>
        <div class="cont_btns">
            <ul class="botones clearfix">
                <li onclick="go_pagina(0)" <?php if(!isset($_GET["contacto"]) && !isset($_GET["video"])){ echo "class='selected'"; } ?>>Empezar</li>
                <li onclick="go_pagina(1)">Clientes</li>
                <li onclick="go_pagina(2)" <?php if(isset($_GET["contacto"])){ echo "class='selected'"; } ?>>Contacto</li>
            </ul>
        </div>
        <?php if(isset($_GET["video"])){ ?>
            <div class="video">
                <video id="video1" width="100%">
                    <source src="video-tutorial.mp4" type="video/mp4">
                </video>
            </div>
            <div class="playvideo" onclick="playvideo()">Reproducir Video</div>
        <?php } ?>
        <div class="empezar" <?php if(isset($_GET["contacto"]) || isset($_GET["video"])){ echo "style='display: none'"; } ?>>
            <?php if($_GET["realizado"] == 0 || !isset($_GET["realizado"])){ ?>
            <div class="form">
                <h1>Crear tu Sitio Ahora Mismo!</h1>
                <h2>Ingresando s&oacute;lo 2 simples datos</h2>
                <form onsubmit="return send()" action="https://misitiodelivery.cl/ajax/index.php" method="post">
                    <h3><?php if(isset($_GET["realizado"]) && $_GET["realizado"] == 0 && $_GET["tipo"] == 1){ echo "<p style='color: #f00'>".$_GET['error']."</p>"; }else{ echo "Ingresa tu Dominio"; } ?></h3>
                    <input type="hidden" name="accion" value="crear_dominio" />
                    <div class="input">
                        <input type="text" name="dominio_msd" placeholder="www.tusitio.cl" <?php if(isset($_GET["realizado"]) && $_GET["realizado"] == 0 && $_GET["tipo"] == 1){ echo "style='border: 2px solid #933; background: #fdd'"; } ?> />
                    </div>
                    <h3><?php if(isset($_GET["realizado"]) && $_GET["realizado"] == 0 && $_GET["tipo"] == 2){ echo "<p style='color: #f00'>".$_GET['error']."</p>"; }else{ echo "Ingresa tu Correo"; } ?></h3>
                    <div class="input">
                        <input type="email" name="correo_msd" placeholder="tucorreo@gmail.com" <?php if(isset($_GET["realizado"]) && $_GET["realizado"] == 0 && $_GET["tipo"] == 2){ echo "style='border: 2px solid #933; background: #fdd'"; } ?> />
                    </div>
                    <h3><?php if(isset($_GET["realizado"]) && $_GET["realizado"] == 0 && $_GET["tipo"] == 4){ echo "<p style='color: #f00'>".$_GET['error']."</p>"; }else{ echo "Telefono"; } ?></h3>
                    <div class="input">
                        <input type="tel" name="telefono_msd" value="+569" />
                    </div>
                    <h3><?php if(isset($_GET["realizado"]) && $_GET["realizado"] == 0 && $_GET["tipo"] == 3){ echo "<p style='color: #f00'>".$_GET['error']."</p>"; }else{ echo "reCAPTCHA"; } ?></h3>
                    <div class="g-recaptcha" data-sitekey="6Lf8j3sUAAAAAFEPARLhuiWamomIvm35UBCqf65R"></div>
                    <div class="acciones">
                        <input type="submit" value="Empezar Prueba Gratis" class="btn_empezar btn_color_1" />
                        <div class="mes_gratis">30 dias gratis</div>
                    </div>
                </form>
            </div>
            <?php } ?>
            <?php if($_GET["realizado"] == 1){ ?>
                <div style="min-height: 430px">
                    <h1 style="padding-top: 40px; font-size: 60px">Felicitaciones!</h1>
                    <h1 style="font-size: 35px">Tu cuenta ha sido creada</h1>
                    <h2 style="font-size: 18px">Te hemos enviado un correo con las instrucciones</h2>
                </div>
            <?php } ?>
        </div>
        <div class="clientes">
            <ul class="lista_clientes"> 
                <?php for($i=0; $i<count($list); $i++){ ?>
                <li>
                    <div style="background: <?php echo $list[$i]['back']; ?>" class="foto"><a target="_blank" href="<?php echo $list[$i]['proto']; ?>://<?php echo $list[$i]['link']; ?>"><img src="<?php echo $list[$i]['img']; ?>" alt="" /></a></div>
                    <div class="info"><a target="_blank" style="display: block; color: #000; text-decoration: none" href="<?php echo $list[$i]['proto']; ?>://<?php echo $list[$i]['link']; ?>"><?php echo $list[$i]['nombre']; ?></a><a target="_blank" style="display: block; color: #000; text-decoration: none; font-size: 20px" href="<?php echo $list[$i]['proto']; ?>://<?php echo $list[$i]['link']; ?>"><?php echo $list[$i]['link']; ?></a></div>
                </li>
                <?php } ?>
            </ul>
        </div>
        <div class="contacto"<?php if(isset($_GET["contacto"])){ echo "style='display: block'"; } ?>>
            <?php if($_GET["contacto"] == 0 || !isset($_GET["contacto"])){ ?>
            <div class="form">
                <h1>Formulario de Contacto</h1>
                <form onsubmit="return send2()" action="https://misitiodelivery.cl/ajax/index.php" method="post">
                    <h3>Nombre</h3>
                    <input type="hidden" name="accion" value="enviar_contacto" />
                    <div class="input">
                        <input type="text" name="nombre" placeholder="Diego" />
                    </div>
                    <h3><?php if(isset($_GET["contacto"]) && $_GET["contacto"] == 0 && $_GET["tipo"] == 1){ echo "<p style='color: #f00'>".$_GET['error']."</p>"; }else{ echo "Correo"; } ?></h3>
                    <div class="input">
                        <input type="email" name="email" placeholder="tucorreo@gmail.com" <?php if(isset($_GET["contacto"]) && $_GET["contacto"] == 0 && $_GET["tipo"] == 1){ echo "style='border: 2px solid #933; background: #fdd'"; } ?> />
                    </div>
                    <h3><?php if(isset($_GET["contacto"]) && $_GET["contacto"] == 0 && $_GET["tipo"] == 3){ echo "<p style='color: #f00'>".$_GET['error']."</p>"; }else{ echo "Telefono"; } ?></h3>
                    <div class="input">
                        <input type="tel" name="telefono" value="+56 9 " />
                    </div>
                    <h3>Asunto</h3>
                    <div class="input">
                        <TextArea name="asunto" style="width: 100%; height: 60px; padding: 4px"></TextArea>
                    </div>
                    <h3><?php if(isset($_GET["contacto"]) && $_GET["contacto"] == 0 && $_GET["tipo"] == 2){ echo "<p style='color: #f00'>".$_GET['error']."</p>"; }else{ echo "reCAPTCHA"; } ?></h3>
                    <div class="g-recaptcha" data-sitekey="6Lf8j3sUAAAAAFEPARLhuiWamomIvm35UBCqf65R"></div>
                    <div class="acciones">
                        <input type="submit" value="Enviar Solicitud" class="btn_empezar btn_color_2" />
                    </div>
                </form>
            </div>
            <?php } ?>
            <?php if($_GET["contacto"] == 1){ ?>
                <div style="min-height: 430px">
                    <h1 style="padding-top: 40px; font-size: 58px">Muchas Gracias!</h1>
                    <h1 style="font-size: 35px">Tu mensaje ha sido enviado</h1>
                    <h2 style="font-size: 22px">A la brevedad te responderemos tu solicitud</h2>
                </div>
            <?php } ?>
        </div>       
    </div>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</body>
</html>