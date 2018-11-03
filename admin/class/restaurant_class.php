<?php
session_start();

require_once 'mysql_class.php';

class Rest{
    
    public $con = null;
    public $pointOnVertex = true;
    
    public function __construct(){
        
        $this->con = new Conexion();
        
    }
    public function get_info(){
        
        $accion = $_POST["accion"];        
        if($accion == "enviar_pedido"){
        
        }
        if($accion == "despacho_domicilio"){
            return get_info_despacho($_POST["lat"], $_POST["lng"]);
        }
        
    }
    public function get_info_despacho($lat, $lng){
        
        $polygons = $this->get_polygons();
        $precio = 9999999;
        $info['op'] = 2;

        foreach($polygons as $polygon){

            $poli = [];
            $puntos = json_decode($polygon['poligono']);
            foreach($puntos as $punto){
                $poli[] = $punto->{'lat'}." ".$punto->{'lng'};
            }
            $is = $this->pointInPolygon($lat." ".$lng, $poli);
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
    public function get_polygons(){
        
        $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        $polygons = $this->con->sql("SELECT t3.nombre, t3.poligono, t3.precio, t3.id_loc FROM giros t1, locales t2, locales_tramos t3 WHERE t1.dominio='".$referer."' AND t1.id_gir=t2.id_gir AND t2.id_loc=t3.id_loc AND t2.eliminado='0' AND t3.eliminado='0'");
        return $polygons['resultado'];
        
    }
    public function pointOnVertex($point, $vertices) {
        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
    }
    public function pointStringToCoordinates($pointString) {
        $coordinates = explode(" ", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }
    public function pointInPolygon($point, $polygon, $pointOnVertex = true) {
        
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
    
}
// QUE ME DEVUELTA CATEGORIA Y SUS VALORES
?>