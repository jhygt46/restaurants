<?php

    //if($fireapp->seguridad_if(array(0))){
    $arrays["nombre"] = "Inicio";
    $arrays["link"] = "pages/base/inicio.php";
    $array[] = $arrays;
    //}
    if(isset($array)){
        $aux["ico"] = 3;
        $aux["show"] = true;
        $aux["categoria"] = "Mi Cuenta";
        $aux["subcategoria"] = $array;
        $menu[] = $aux;
        unset($aux);
        unset($array);
    }
    
    // ADMIN CIA //
    //if($fireapp->seguridad_if(array(0))){
    $arrays["nombre"] = "Basica";
    $arrays["link"] = "pages/base/basica.php";
    $array[] = $arrays;
    $arrays["nombre"] = "Usuarios";
    $arrays["link"] = "pages/base/usuarios.php?tipo=admin";
    $array[] = $arrays;
    //}

    if(isset($array)){
        $aux["ico"] = 4;
        $aux["categoria"] = "Configuracion";
        $aux["subcategoria"] = $array;
        $menu[] = $aux;
        unset($aux);
        unset($array);
    }

?>