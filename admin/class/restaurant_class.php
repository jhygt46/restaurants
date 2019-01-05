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
        if($accion == "crear_dominio"){
            return $this->crear_dominio();
        }
        if($accion == "get_pedido"){
            return $this->get_pedido();
        }
        
    }
    public function crear_dominio(){
        
        $res = $_POST["g-recaptcha-response"]; 
        if(isset($res) && $res){ 
            
            $secret = "6Lf8j3sUAAAAAP6pYvdgk9qiWoXCcKKXGsKFQXH4";
            $v = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$_POST["g-recaptcha-response"]."&remoteip=".$_SERVER["REMOTE_ADDR"]); 
            $data = json_decode(($v)); 
            if($data->{'success'}){ 
                
                $correo = $_POST["correo"];
                if(filter_var($correo, FILTER_VALIDATE_EMAIL)) {

                    $validar_correo = $this->con->sql("SELECT * FROM fw_usuarios WHERE correo='".$correo."' AND eliminado='0'");
                    if($validar_correo['count'] == 0){

                        $dominio_val = explode(".", $_POST["dominio"]);
                        if(count($dominio_val) == 3 && $dominio_val[0] == "www" && strlen($dominio_val[1]) > 1 && strlen($dominio_val[2]) > 1){

                            $dominio = $_POST["dominio"];
                            $validar_dominio = $this->con->sql("SELECT * FROM giros WHERE dominio='".$dominio."' AND eliminado='0'");
                            if($validar_dominio['count'] == 0){

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
                                curl_exec($ch);

                                $info['op'] = 1;
                                $info['mensaje'] = "FELICITACIONES";

                                curl_close($ch);
                                header("Location: http://www.misitiodelivery.cl?realizado=1");
                                exit;
                                
                            }else{
                                header("Location: http://www.misitiodelivery.cl?realizado=0&tipo=1&error=Dominio+Existente");
                                exit;
                            }

                        }else{
                            header("Location: http://www.misitiodelivery.cl?realizado=0&tipo=1&error=Dominio+Existente");
                            exit;
                        }

                    }else{
                        header("Location: http://www.misitiodelivery.cl?realizado=0&tipo=2&error=Correo+Existente");
                        exit;
                    }

                }else{
                    header("Location: http://www.misitiodelivery.cl?realizado=0&tipo=2&error=Correo+Incorrecto");
                    exit;
                }
                
            }else{ 
                header("Location: http://www.misitiodelivery.cl?realizado=0&tipo=3&error=Error+reCAPTCHA");
                exit;
            } 
            
        }else{ 
            header("Location: http://www.misitiodelivery.cl?realizado=0&tipo=3&error=Error+reCAPTCHA");
            exit; 
        }
        
    }
    
    public function enviar_pedido(){
        
        $aux_pedido = json_decode($_POST['pedido']);
        $key = "AIzaSyDNFkwj6toPpKFK0PakVNbcFeA8BE8mHZI";
        
        $nombre = $aux_pedido->{'nombre'};
        $telefono = str_replace(" ", "", $aux_pedido->{'telefono'});
        
        $pre_gengibre = $aux_pedido->{'pre_gengibre'};
        $pre_wasabi = $aux_pedido->{'pre_wasabi'};
        $pre_embarazadas = $aux_pedido->{'pre_embarazadas'};
        $pre_palitos = $aux_pedido->{'pre_palitos'};
        $pre_soya = $aux_pedido->{'pre_soya'};
        $pre_teriyaki = $aux_pedido->{'pre_teriyaki'};
        $comentarios = $aux_pedido->{'comentarios'};
        $despacho = $aux_pedido->{'despacho'};
            
        $calle = $aux_pedido->{'calle'};
        $num = $aux_pedido->{'num'};
        $depto = $aux_pedido->{'depto'};
        $comuna = $aux_pedido->{'comuna'};
        $direccion = $aux_pedido->{'direccion'};
        $lat = $aux_pedido->{'lat'};
        $lng = $aux_pedido->{'lng'};
        $costo = $aux_pedido->{'costo'};
        $total = $aux_pedido->{'total'};
        
        if($nombre != "" && $telefono != "+569 "){
            
            $verify_despacho = 0;
            $verify_direccion = 0;
            $precision = 0.001;
            
            $id_pep = $aux_pedido->{'id_pep'};
            $pep_code = $aux_pedido->{'pep_code'};
            $code = bin2hex(openssl_random_pseudo_bytes(10));
            
            if($id_pep > 0 && $pep_code != ""){
                $sql_pep = $this->con->sql("SELECT * FROM pedidos_persona_posicion WHERE id_pep='".$id_pep."' AND code='".$pep_code."'");
                if($sql_pep['count'] == 1 && $aux_pedido->{'despacho'} == 1){
                    if($sql_pep['resultado'][0]['lat'] != $lat || $sql_pep['resultado'][0]['lng'] != $lng || $sql_pep['resultado'][0]['telefono'] != $telefono){
                        
                        $geocode = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($aux_pedido->{'despacho_domicilio'}->{'direccion'})."&key=".$key));
                        if($geocode->{'status'} == "OK"){
                            
                            $dif_lat = $aux_pedido->{'lat'} - $geocode->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
                            $dif_lng = $aux_pedido->{'lng'} - $geocode->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
                            if($dif_lat < $precision && $dif_lng < $precision){
                                $verify_direccion = 1;
                            }
                            
                            $aux_pep = $this->con->sql("INSERT INTO pedidos_persona_posicion (code, nombre, telefono, direccion, lat, lng, calle, num, depto, comuna, verificado) VALUES ('".$code."', '".$nombre."', '".$telefono."', '".$direccion."', '".$lat."', '".$lng."', '".$calle."', '".$num."', '".$depto."', '".$comuna."', '".$verify_direccion."')");
                            $id_pep = $aux_pep['insert_id'];
                            $info['nuevo_pep'] = 1;
                            
                        }
                        
                    }
                }
                if($sql_pep['count'] == 0 || $aux_pedido->{'despacho'} == 0){
                    $aux_pep = $this->con->sql("INSERT INTO pedidos_persona_posicion (code, nombre, telefono, direccion, lat, lng, calle, num, depto, comuna, verificado) VALUES ('".$code."', '".$nombre."', '".$telefono."', '".$direccion."', '".$lat."', '".$lng."', '".$calle."', '".$num."', '".$depto."', '".$comuna."', '".$verify_direccion."')");
                    $id_pep = $aux_pep['insert_id'];
                    $info['nuevo_pep'] = 1;
                }
            }else{
                $aux_pep = $this->con->sql("INSERT INTO pedidos_persona_posicion (code, nombre, telefono, direccion, lat, lng, calle, num, depto, comuna, verificado) VALUES ('".$code."', '".$nombre."', '".$telefono."', '".$direccion."', '".$lat."', '".$lng."', '".$calle."', '".$num."', '".$depto."', '".$comuna."', '".$verify_direccion."')");
                $id_pep = $aux_pep['insert_id'];
                $info['nuevo_pep'] = 1;
            }
            
            if($aux_pedido->{'despacho'} == 0){
                
                $id_loc = $aux_pedido->{'id_loc'};
                $costo = 0;
                $despacho = 0;
                
            }
            if($aux_pedido->{'despacho'} == 1){
                
                $id_loc = $aux_pedido->{'id_loc'};
                $costo = $aux_pedido->{'costo'};
                $despacho = 1;
                
                $aux_verify = $this->get_info_despacho($aux_pedido->{'lat'}, $aux_pedido->{'lng'});
                if($aux_verify['op'] == 1 && $aux_verify['id_loc'] == $id_loc && $aux_verify['precio'] == $costo){
                    $verify_despacho = 1;
                }

            }
            
            $loc_gir = $this->con->sql("SELECT t1.code, t1.correo, t2.dominio, t1.activar_envio FROM locales t1, giros t2 WHERE t1.id_loc='".$id_loc."' AND t1.id_gir=t2.id_gir");
            
            $pedido_code = bin2hex(openssl_random_pseudo_bytes(10));
            $pedido_sql = $this->con->sql("INSERT INTO pedidos_aux (code, fecha, despacho, tipo, id_loc, carro, promos, verify_despacho, pre_gengibre, pre_wasabi, pre_embarazadas, pre_palitos, pre_teriyaki, pre_soya, comentarios, id_pep, costo, total) VALUES ('".$pedido_code."', now(), '".$despacho."', '1', '".$id_loc."', '".$_POST['carro']."', '".$_POST['promos']."', '".$verify_despacho."', '".$pre_gengibre."', '".$pre_wasabi."', '".$pre_embarazadas."', '".$pre_palitos."', '".$pre_teriyaki."', '".$pre_soya."', '".$comentarios."', '".$id_pep."', '".$costo."', '".$total."')");
            $id_ped = $pedido_sql['insert_id'];

            $info['id_pep'] = $id_pep;
            $info['pep_code'] = $code;
            $info['fecha'] = time();
            // POST NODE-JS MAIL Y SOCKET //
            
            // SOCKET //
            $pedido['local_code'] = $loc_gir['resultado'][0]['code'];
            $pedido['id_ped'] = $id_ped;
            
            // CORREO //
            
            $pedido['correo'] = $loc_gir['resultado'][0]['correo'];
            $pedido['numero'] = $id_ped;
            $pedido['accion'] = 'enviar_pedido_local';
            $pedido['activar_envio'] = $loc_gir['resultado'][0]['activar_envio'];
            $pedido['hash'] = 'hash';
            $pedido['dominio'] = $loc_gir['resultado'][0]['dominio'];
            $pedido['pedido_code'] = $pedido_code;
            $pedido['nombre'] = $nombre;
            $pedido['telefono'] = $telefono;
            
            
            
            $info['op'] = 1;
            $info['id_ped'] = $id_ped;
            $info['pedido_code'] = $pedido_code;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://35.196.220.197/enviar_local');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pedido));
            $result = curl_exec($ch);
            curl_close($ch);
            
            $info['nodejs'] = $result;
            

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
                    $info['id_loc'] = intval($polygon['id_loc']);
                    $info['precio'] = intval($polygon['precio']);
                    $precio = $polygon['precio'];
                }
            }
            
        }
        return $info;
        
    }
    public function get_polygons(){
        $referer = (parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST) == "localhost") ? "www.izusushi.cl" : parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST) ;
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