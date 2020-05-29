<?php

    $letras = "abcdefghijklmnopqrstuvwxyz";
    $cant = 12 ;

    $i[] = palabra("restaurants", 0);
    $i[] = palabra("hoteles", 1);
    $i[] = palabra("cines", 2);
    $i[] = palabra("spa", 3);
    $i[] = palabra("restauradores", 4);
    $i[] = palabra("respiradores", 5, 1);

    function palabra($n, $c, $p = null){
        $a['n'] = $n;
        $a['c'] = $c;
        $a['p'] = $p;
        return $a;
    }
    function xpos($letra){
        return strpos($GLOBALS['letras'], $letra);
    }
    function recursive_palabras($palabras, $l){
        $arr = [];
        for($i=0; $i<count($palabras); $i++){
            $arr[xpos($palabras[$i]['n']{0})][] = ($palabras[$i]['p'] == null) ? palabra(substr($palabras[$i]['n'], 1), $palabras[$i]['c']) : palabra(substr($palabras[$i]['n'], 1), $palabras[$i]['c'], $palabras[$i]['p']) ;
        }
        for($i=0; $i<strlen($GLOBALS['letras']); $i++){
            $count = count($arr[$i]);
            if($count > 0){
                if($l > 0){
                    $count2 = ($count > $GLOBALS['cant']) ? $GLOBALS['cant'] : $count ;
                    if($count > $count2){
                        $aux[$GLOBALS['letras']{$i}] = recursive_palabras(array_slice($arr[$i], $GLOBALS['cant']), $l+1);
                    }
                    for($j=0; $j<$count2; $j++){
                        $aux[$GLOBALS['letras']{$i}][0][] = ($arr[$i][$j]['p'] == null) ? $arr[$i][$j]['n']."-".$arr[$i][$j]['c'] : $arr[$i][$j]['n']."-".$arr[$i][$j]['c']."-".$arr[$i][$j]['p'] ;
                    }
                }else{
                    $aux[$GLOBALS['letras']{$i}] = recursive_palabras($arr[$i], $l+1); 
                }
            }
        }
        return $aux;
    }

    if($_POST["accion"] == "get_diccionario"){
        echo json_encode(recursive_palabras($i, 0));
    }

    $x[0]['ttl'] = 'Celulares';
    $x[0]['sttl'] = 'Celulares';

    $x[0]['precio'] = true;
    $x[0]['calidad'] = true;
    $x[0]['posicion'] = true;

    $x[0]['opcs'][0]['tipo'] = 1;
    $x[0]['opcs'][0]['nombre'] = "Procesadores";
    $x[0]['opcs'][0]['valores'][0]['nombre'] = "X21";
    $x[0]['opcs'][0]['valores'][1]['nombre'] = "Q5s";
    $x[0]['opcs'][0]['valores'][2]['nombre'] = "Core7";

    $x[0]['opcs'][1]['tipo'] = 2;
    $x[0]['opcs'][1]['nombre'] = "Pantalla";
    $x[0]['opcs'][1]['valores'][0]['nombre'] = "5'";
    $x[0]['opcs'][1]['valores'][1]['nombre'] = "5.5'";
    $x[0]['opcs'][1]['valores'][2]['nombre'] = "6'";

    $x[1]['ttl'] = 'Notebook';
    $x[1]['sttl'] = 'Celulares';

    $x[1]['precio_cantidad'] = true;
    $x[1]['posicion'] = true;
    $x[1]['calidad'] = true;
    $x[1]['calidad_list'][0]['nombre'] = "Rostro";
    $x[1]['calidad_list'][1]['nombre'] = "Contextura";
    $x[1]['calidad_list'][2]['nombre'] = "Simpatia";
    
    $x[1]['opcs'][0]['tipo'] = 1;
    $x[1]['opcs'][0]['nombre'] = "Marca";
    $x[1]['opcs'][0]['nom_input'] = "marca";
    $x[1]['opcs'][0]['valores'][0]['nombre'] = "Sony";
    $x[1]['opcs'][0]['valores'][1]['nombre'] = "LG";
    $x[1]['opcs'][0]['valores'][2]['nombre'] = "Samsung";

    $x[1]['opcs'][1]['tipo'] = 2;
    $x[1]['opcs'][1]['nombre'] = "Pantalla";
    $x[1]['opcs'][1]['nom_input'] = "pantalla";
    $x[1]['opcs'][1]['valores'][0]['nombre'] = "5'";
    $x[1]['opcs'][1]['valores'][1]['nombre'] = "5.5'";
    $x[1]['opcs'][1]['valores'][2]['nombre'] = "6'";

    
    if($_POST["accion"] == "get_opciones"){
        echo json_encode($x);
    }

?>