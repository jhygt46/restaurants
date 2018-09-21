<?php
session_start();
date_default_timezone_set('America/Santiago');

if(isset($_SESSION['user']['info']['id_user'])){
        
    $page = "layout";
    include("includes/header.php");
    ?>
    <body>
        <?php include("includes/head.php"); ?>
        <div class="contenido">
            <?php include("includes/nav.php"); ?>
            <div class="cont">
                <div class="contenedor">
                    <div class='load error'>
                        <div class='msgloading'>
                            <div class='textload'>Error porfavor vuelva a intentarlo mas tarde</div>
                        </div>
                    </div>
                    <div class='load loading'>
                        <div class='msgloading'>
                            <div class="cssload-jumping">
                                <span></span><span></span><span></span><span></span><span></span>
                            </div>
                            <div class='textload'>Cargando...</div>
                        </div>
                    </div>
                    <div class="cont_notificaciones">
                        <div id="l_noti" class="noti_align" align="right">
                            <div class="noti noti_video" code="">
                                <div class="video_header">
                                    <div class="video_titulo">Paso 1: Ingresar Usuarios</div>
                                    <div class="video_cerrar video_btn" onclick="toogle_video(this)"></div>
                                    <div class="video_size video_btn" onclick="size_video(this)"></div>
                                </div>
                                <div class="noti_content video_content" id="player"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='conthtml'>
                        
                        <?php
                        
                            $include = true;
                            include("pages/base/inicio.php");
                        
                        ?>
                            
                    </div>
                </div>
            </div>
            <div class="movie">
                <div class="cont_movie">
                    <div class="objeto cursor"></div>
                    <div class="mensaje">Esto es una demostraci&oacute;n</div>
                </div>
            </div>
        </div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAq6hw0biMsUBdMBu5l-bai9d3sUI-f--g&libraries=places" async defer></script>
    </body>
</html>
    
<?php } ?>