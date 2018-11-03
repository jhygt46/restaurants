<?php

header('Content-type: text/json');
header('Content-type: application/json');

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}

require_once($path."admin/class/restaurant_class.php");
$rest = new Rest();
echo "{}";
//echo json_encode($_POST)
//$info = $rest->get_info();

//echo json_encode($info);

/*
$accion = $_POST["accion"];

if($accion == "enviar_pedido"){
    
    $aux_pedido = json_decode($_POST['pedido']);
    
    $nombre = $aux_pedido->{'nombre'};
    $telefono = $aux_pedido->{'telefono'};
    
    if($nombre != "" && $telefono != ""){
        
        $pedido['pedido']['despacho'] = $aux_pedido->{'despacho'};
        $pedido['pedido']['total'] = $aux_pedido->{'total'};
        $pedido['pedido']['carro'] = json_decode($_POST['carro']);
        $pedido['pedido']['promos'] = json_decode($_POST['promos']);
        
        $pedido_code = bin2hex(openssl_random_pseudo_bytes(10));
        $pedido_insert = $fireapp->con->sql("INSERT INTO pedidos (code, fecha, despacho, total, aux_02, aux_03) VALUES ('".$pedido_code."', now(), '".$pedido['pedido']['despacho']."', '".$pedido['pedido']['total']."', '".$_POST['carro']."', '".$_POST['promos']."')");
        
        $info['op'] = 1;
        $info['id_ped'] = $pedido_insert['insert_id'];
        $info['pedido_code'] = $pedido_code;
        
        $info_local = $fireapp->con->sql("SELECT * FROM locales WHERE id_loc='".$aux_pedido->{'id_loc'}."'");
        
        
        $pedido['local_code'] = $info_local['resultado'][0]['code'];
        $info['position_lat'] = $info_local['resultado'][0]['lat'];
        $info['position_lng'] = $info_local['resultado'][0]['lng'];
        
        
        $pedido['pedido']['id_ped'] = $info['id_ped'];
        $pedido['pedido']['pedido_code'] = $pedido_code;
        $pedido['pedido']['tipo'] = 1;
        $pedido['pedido']['estado'] = 0;
        
        if($pedido['pedido']['despacho'] == 0){
            
            $pedido['pedido']['costo'] = 0;
            $fireapp->con->sql("UPDATE pedidos SET id_loc='".$pedido['pedido']['id_loc']."' WHERE id_ped='".$pedido['pedido']['id_ped']."'");
            
        }
        if($pedido['pedido']['despacho'] == 1){
            
            $pedido['pedido']['lat'] = $aux_pedido->{'lat'};
            $pedido['pedido']['lng'] = $aux_pedido->{'lng'};
            $pedido['pedido']['direccion'] = $aux_pedido->{'direccion'};
            $pedido['pedido']['calle'] = $aux_pedido->{'calle'};
            $pedido['pedido']['num'] = $aux_pedido->{'num'};
            $pedido['pedido']['depto'] = $aux_pedido->{'depto'};
            $pedido['pedido']['comuna'] = $aux_pedido->{'comuna'};
            $pedido['pedido']['costo'] = $aux_pedido->{'costo'};
            $fireapp->con->sql("UPDATE pedidos SET lat='".$pedido['pedido']['lat']."', lng='".$pedido['pedido']['lng']."', direccion='".$pedido['pedido']['direccion']."', num='".$pedido['pedido']['num']."', calle='".$pedido['pedido']['calle']."', depto='".$pedido['pedido']['depto']."', comuna='".$pedido['pedido']['comuna']."', costo='".$pedido['pedido']['costo']."', id_loc='".$pedido['pedido']['id_loc']."' WHERE id_ped='".$pedido['pedido']['id_ped']."'");
            
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://35.196.220.197/enviar_local');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pedido));
        curl_exec($ch);
        
    }else{
        
        $info['op'] = 2;
        $info['mensaje'] = "Error: debe completar todos los campos";
        
    }
    
}
if($accion == "despacho_domicilio"){
    
    $pointLocation = new pointLocation();
    $polygons = $fireapp->get_polygons();
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $precio = 9999999;
    $info['op'] = 2;

    foreach($polygons as $polygon){

        $poli = [];
        $puntos = json_decode($polygon['poligono']);
        foreach($puntos as $punto){
            $poli[] = $punto->{'lat'}." ".$punto->{'lng'};
        }
        $is = $pointLocation->pointInPolygon($lat." ".$lng, $poli);
        if($is == "inside"){
            if($precio > $polygon['precio']){
                $info['op'] = 1;
                $info['id_loc'] = $polygon['id_loc'];
                $info['precio'] = $polygon['precio'];
                $precio = $polygon['precio'];
            }
        }
    }

}



?>


