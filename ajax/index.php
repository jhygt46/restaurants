<?php

header('Content-type: text/json');
header('Content-type: application/json');

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}

require_once($path."admin/class/core_class.php");
$fireapp = new Core();

$accion = $_POST["accion"];

if($accion == "enviar_pedido"){
    
    $aux = $fireapp->set_pedido($_POST['pedido'], $_POST['carro'], $_POST['promos']);

    $pedido['local_code'] = $aux['local_code'];
    $pedido['pedido']['id_ped'] = $aux['id_ped'];
    $pedido['pedido']['despacho'] = $aux['despacho'];
    $pedido['pedido']['id_loc'] = $aux['id_loc'];
    $pedido['pedido']['lat'] = $aux['lat'];
    $pedido['pedido']['lng'] = $aux['lng'];
    $pedido['pedido']['direccion'] = $aux['direccion'];
    $pedido['pedido']['num'] = $aux['num'];
    $pedido['pedido']['calle'] = $aux['calle'];
    $pedido['pedido']['comuna'] = $aux['comuna'];
    $pedido['pedido']['costo'] = $aux['costo'];
    $pedido['pedido']['total'] = $aux['total'];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://35.196.220.197/enviar_local');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pedido));
    curl_exec($ch);
    
    $info['op'] = 1;
    $info['pedido_code'] = $aux['pedido_code'];
    
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

echo json_encode($info);

class pointLocation {
    var $pointOnVertex = true; // Check if the point sits exactly on one of the vertices?
 
    function pointLocation() {
    }
 
    function pointInPolygon($point, $polygon, $pointOnVertex = true) {
        $this->pointOnVertex = $pointOnVertex;
 
        // Transform string coordinates into arrays with x and y values
        $point = $this->pointStringToCoordinates($point);
        $vertices = array(); 
        foreach ($polygon as $vertex) {
            $vertices[] = $this->pointStringToCoordinates($vertex); 
        }
 
        // Check if the point sits exactly on a vertex
        if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
            return "vertex";
        }
 
        // Check if the point is inside the polygon or on the boundary
        $intersections = 0; 
        $vertices_count = count($vertices);
 
        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1]; 
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                return "boundary";
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) { 
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x']; 
                if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                    return "boundary";
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++; 
                }
            } 
        } 
        // If the number of edges we passed through is odd, then it's in the polygon. 
        if ($intersections % 2 != 0) {
            return "inside";
        } else {
            return "outside";
        }
    }
 
    function pointOnVertex($point, $vertices) {
        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
 
    }
 
    function pointStringToCoordinates($pointString) {
        $coordinates = explode(" ", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }
 
}


?>


