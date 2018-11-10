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
            return $this->enviar_pedido();
        }
        if($accion == "despacho_domicilio"){
            return $this->get_info_despacho($_POST["lat"], $_POST["lng"]);
        }
        if($accion == "enviar_pedido"){
            return $this->enviar_pedido();
        }
        if($accion == "crear_dominio"){
            return $this->crear_dominio();
        }
    }
    public function crear_dominio(){
        
        $correo = $_POST["correo"];
        if(filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            
            $validar_correo = $this->con->sql("SELECT * FROM fw_usuarios WHERE correo='".$correo."' AND eliminado='0'");
            if($validar_correo['count'] == 0){
                
                $dominio_val = explode(".", $_POST["dominio"]);
                if(count($dominio_val) == 3 && $dominio_val[0] == "www" && strlen($dominio_val[1]) > 1 && strlen($dominio_val[2]) > 1){

                    $dominio = $_POST["dominio"];
                    $validar_dominio = $this->con->sql("SELECT * FROM giros WHERE dominio='".$dominio."' AND eliminado='0'");
                    if($validar_dominio['count'] == 0){
                        
                        $info['op'] = 1;
                        $info['mensaje'] = "FELICITACIONES";
                        
                        $code = bin2hex(openssl_random_pseudo_bytes(10));
                        $giros = $this->con->sql("INSERT INTO giros (dominio, code, catalogo, fecha_creado, eliminado) VALUES ('".$dominio."', '".$code."', '1', now(), '0')"); 
                        $usuarios = $this->con->sql("INSERT INTO fw_usuarios (correo, fecha_creado, admin, eliminado) VALUES ('".$correo."', now(), '1', '0')");
                        $this->con->sql("INSERT INTO fw_usuarios_giros (id_gir, id_user) VALUES ('".$giros['insert_id']."', '".$usuarios['insert_id']."')");

                        $send['dominio'] = $dominio;
                        $send['correo'] = $correo;
                        $send['id'] = $usuarios['insert_id'];
                        $send['code'] = $code;

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'http://35.196.220.197/mail_inicio');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                        $res = json_decode(curl_exec($ch));
                        $info['res1'] = $res['op'];
                        $info['res2'] = $res->{'op'};
                        curl_close($ch);
                        
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "DOMINIO EXISTENTE";
                    }

                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "DOMINIO INCORRECTO";
                }
                
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "CORREO EXISTENTE";
            }
            
        }else{
            $info['op'] = 2;
            $info['mensaje'] = "CORREO INCORRECTO";
        }
        
        return $info;
        
    }
    public function enviar_pedido(){
        
        $aux_pedido = json_decode($_POST['pedido']);
        $nombre = $aux_pedido->{'nombre'};
        $telefono = $aux_pedido->{'telefono'};

        if($nombre != "" && $telefono != "+569 "){
        
            $id_loc = $aux_pedido->{'id_loc'};
            $pedido['pedido']['despacho'] = $aux_pedido->{'despacho'};
            $pedido['pedido']['total'] = $aux_pedido->{'total'};
            $pedido['pedido']['carro'] = json_decode($_POST['carro']);
            $pedido['pedido']['promos'] = json_decode($_POST['promos']);
            $pedido['nombre'] = $nombre;
            $pedido['telefono'] = $telefono;
            
            $wasabi = $aux_pedido->{'wasabi'};
            $gengibre = $aux_pedido->{'gengibre'};
            $embarazadas = $aux_pedido->{'embarazadas'};
            $palitos = $aux_pedido->{'palitos'};
            
            $pedido_code = bin2hex(openssl_random_pseudo_bytes(10));
            $pedido_insert = $this->con->sql("INSERT INTO pedidos (code, fecha, despacho, total, aux_02, aux_03, id_loc, nombre, telefono, wasabi, gengibre, embarazada, palitos) VALUES ('".$pedido_code."', now(), '".$pedido['pedido']['despacho']."', '".$pedido['pedido']['total']."', '".$_POST['carro']."', '".$_POST['promos']."', '".$id_loc."', '".$nombre."', '".$telefono."', '".$wasabi."', '".$gengibre."', '".$embarazadas."', '".$palitos."')");
            
            $info['op'] = 1;
            $info['id_ped'] = $pedido_insert['insert_id'];
            $info['pedido_code'] = $pedido_code;

            $info_local = $this->con->sql("SELECT * FROM locales t1, giros t2 WHERE t1.id_loc='".$aux_pedido->{'id_loc'}."' AND t1.id_gir=t2.id_gir");

            $pedido['local_code'] = $info_local['resultado'][0]['code'];
            $info['position_lat'] = $info_local['resultado'][0]['lat'];
            $info['position_lng'] = $info_local['resultado'][0]['lng'];

            $pedido['pedido']['id_ped'] = $info['id_ped'];
            $pedido['pedido']['pedido_code'] = $pedido_code;
            $pedido['pedido']['tipo'] = 1;
            $pedido['pedido']['estado'] = 0;

            if($pedido['pedido']['despacho'] == 0){

                $pedido['pedido']['costo'] = 0;
                $pedido['pedido']['direccion'] = "Retiro en Local";
                $this->con->sql("UPDATE pedidos SET costo='".$pedido['pedido']['costo']."' WHERE id_ped='".$pedido['pedido']['id_ped']."'");

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
                $this->con->sql("UPDATE pedidos SET lat='".$pedido['pedido']['lat']."', lng='".$pedido['pedido']['lng']."', direccion='".$pedido['pedido']['direccion']."', num='".$pedido['pedido']['num']."', calle='".$pedido['pedido']['calle']."', depto='".$pedido['pedido']['depto']."', comuna='".$pedido['pedido']['comuna']."', costo='".$pedido['pedido']['costo']."' WHERE id_ped='".$pedido['pedido']['id_ped']."'");

            }
            
            //ENVIAR MAIL //
            $pedido['accion'] = 'enviar_pedido_local';
            $pedido['hash'] = 'hash';
            $pedido['correo'] = $info_local['resultado'][0]['correo'];
            $pedido['numero'] = $info['id_ped'];
            $pedido['dominio'] = $info_local['resultado'][0]['dominio'];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://35.196.220.197/enviar_local');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pedido));
            curl_exec($ch);
            curl_close($ch);

        }else{

            $info['op'] = 2;
            $info['mensaje'] = "Error: debe completar todos los campos";

        }
        return $info;
        
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
        return $info;
        
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