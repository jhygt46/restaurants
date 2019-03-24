<?php

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = "C:/AppServ/www/restaurants";
}else{
    $path = "/var/www/html/restaurants";
}

require($path."/admin/class/core_class.php");
$core = new Core();

class pointLocation{

    var $pointOnVertex = true; // Check if the point sits exactly on one of the vertices?
 
    function pointLocation(){
    
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
        if ($this->pointOnVertex == true && $this->pointOnVertex($point, $vertices) == true) {
            return "vertex";
        }
 
        // Check if the point is inside the polygon or on the boundary
        $intersections = 0; 
        $vertices_count = count($vertices);
 
        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1]; 
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] && $vertex1['y'] == $point['y'] && $point['x'] > min($vertex1['x'], $vertex2['x']) && $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                return "boundary";
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) && $point['y'] <= max($vertex1['y'], $vertex2['y']) && $point['x'] <= max($vertex1['x'], $vertex2['x']) && $vertex1['y'] != $vertex2['y']) { 
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
 
    function pointOnVertex($point, $vertices){

        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
 
    }
 
    function pointStringToCoordinates($pointString){

        $coordinates = explode(" ", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);

    }
 
}

$pointLocation = new pointLocation();
$poligons = $core->get_polygons();
$lat = $_GET['lat'];
$lng = $_GET['lng'];

foreach($poligons as $polygon){

    $poli = [];
    $puntos = json_decode($polygon['poligono']);
    foreach($puntos as $punto){
        $poli[] = $punto->{"lat"}." ".$punto->{"lng"};
    }
    $is = $pointLocation->pointInPolygon($lat." ".$lng, $poli);
    echo $polygon['nombre'].">> ".$is."<br/>";

}
