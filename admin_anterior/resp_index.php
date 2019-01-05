<?php 

    require 'class/easy_class.php';
    $easy = new Easy();

    
    //pre($easy->get_palabra_clave(1));
    
    $aux['id_tdp'] = 0;
    $aux['precio'] = 3500;
    
    $simple_txt['precio'][] = $aux;
    
    $aux['id_tdp'] = 1;
    $aux['precio'] = 3700;
    
    $simple_txt['precio'][] = $aux;
    
    pre($simple_txt);
    
    $medios_de_pago[0]['id_tdp'] = 1;
    $medios_de_pago[0]['nombre'] = "Efectivo";
    $medios_de_pago[1]['id_tdp'] = 2;
    $medios_de_pago[1]['nombre'] = "Tarjeda de Credito";
    $medios_de_pago[2]['id_tdp'] = 3;
    $medios_de_pago[2]['nombre'] = "Transferencia Electronica";
    
    pre($medios_de_pago);
    
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>ARBOL</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css" media="all" />
        <script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
        <script src="js/first_class.js" type="text/javascript"></script>
        <script src="js/base.js" type="text/javascript"></script>
    </head>
    <body onload="load()">
        <div class="contenedor" id="contenedor">
            <div class="categorias">
                
            </div>
        </div>
    </body>
</html>


<?php function pre($pre){ echo "<pre style='font-size: 12px'>"; print_r($pre); echo "</pre>"; } ?>