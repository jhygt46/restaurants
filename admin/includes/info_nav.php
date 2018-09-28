<?php

    
    $arrays["nombre"] = "Inicio";
    $arrays["link"] = "pages/base/inicio.php";
    $array[] = $arrays;
    
    $arrays["nombre"] = "Giros";
    $arrays["link"] = "pages/base/giros.php";
    $array[] = $arrays;
    
    if(isset($array)){
        $aux["ico"] = 3;
        $aux["show"] = true;
        $aux["categoria"] = "Mi Cuenta";
        $aux["subcategoria"] = $array;
        $menu[] = $aux;
        unset($aux);
        unset($array);
    }
      
    $arrays["nombre"] = "Configuracion";
    $arrays["link"] = "pages/base/basica.php";
    $array[] = $arrays;    
    
    if(isset($array)){
        $aux["ico"] = 4;
        $aux["show"] = true;
        $aux["categoria"] = "Configuracion";
        $aux["subcategoria"] = $array;
        $menu[] = $aux;
        unset($aux);
        unset($array);
    }

?>