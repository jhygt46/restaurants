<?php

    
    $arrays["nombre"] = "Inicio";
    $arrays["link"] = "pages/base/inicio.php";
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
    
    
        
    $arrays["nombre"] = "Basica";
    $arrays["link"] = "pages/base/basica.php";
    $array[] = $arrays;

    $arrays["nombre"] = "Usuarios";
    $arrays["link"] = "pages/base/usuarios.php";
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