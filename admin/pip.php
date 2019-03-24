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
        return $intersections;
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

/*
$points = array("50 70","70 40","-20 30","100 10","-10 -10","40 -20","110 -20");
$polygon = array("-50 30","50 70","100 50","80 10","110 -10","110 -30","-20 -50","-30 -40","10 -10","-10 10","-30 -20","-50 30");

foreach($poligons as $polygon){

    $poli = [];
    $puntos = json_decode($polygon['poligono']);
    foreach($puntos as $punto){
        $poli[] = $punto->{"lat"}." ".$punto->{"lng"};
    }
    $is = $pointLocation->pointInPolygon($lat." ".$lng, $poli);
    echo $polygon['nombre'].": ".$is."<br/>";

}
*/

foreach($poligons as $polygon){
    $vertices_x = [];
    $vertices_y = [];
    $puntos = json_decode($polygon['poligono']);
    $points_polygon = count($puntos);
    foreach($puntos as $punto){
        $vertices_x[] = $punto->{"lat"};
        $vertices_y[] = $punto->{"lng"};
    }
    if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $lng, $lat)){
        echo "Is in polygon!";
    }else{
        echo "Is not in polygon";
    }
}


function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y){
    $i = $j = $c = $point = 0;
    for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
        $point = $i;
        if($point == $points_polygon)
            $point = 0;
            if ( (($vertices_y[$point]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) && ($longitude_x < ($vertices_x[$j] - $vertices_x[$point]) * ($latitude_y - $vertices_y[$point]) / ($vertices_y[$j] - $vertices_y[$point]) + $vertices_x[$point]) ) )
                $c = !$c;
    }
    return $c;
}








