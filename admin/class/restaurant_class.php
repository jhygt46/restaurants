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
        if($accion == "get_users_pedido"){
            return $this->get_users_pedido($_POST["telefono"]);
        }
        if($accion == "crear_dominio"){
            return $this->crear_dominio();
        }
        if($accion == "get_pedido"){
            return $this->get_pedido();
        }
        if($accion == "enviar_contacto"){
            return $this->enviar_contacto();
        }
        if($accion == "get_motos"){
            return $this->get_motos();
        }
        if($accion == "get_moto"){
            return $this->get_moto($_POST["id_mot"]);
        }
        if($accion == "enviar_chat"){
            return $this->enviar_chat();
        }
    }
    public function get_users_pedido($telefono){

        $telefono = $_POST["telefono"];
        $referer = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST);
        if($referer == "www.misitiodelivery.cl" || $referer == "misitiodelivery.cl"){
            $referer = $_POST["referer"];
        }
        $usuario = $this->con->sql("SELECT t1.id_puser, t1.nombre, t2.id_pdir, t2.direccion, t2.calle, t2.num, t2.depto, t2.comuna, t2.lat, t2.lng FROM pedidos_usuarios t1, pedidos_direccion t2, giros t3 WHERE t3.dominio='".$referer."' AND t3.id_gir=t1.id_gir AND t1.telefono='".$telefono."' AND t1.id_puser=t2.id_puser");
        $info['cantidad'] = 0;
        if($usuario["count"] > 0){
            $info['id_puser'] = $usuario['resultado'][0]['id_puser'];
            $info['nombre'] = $usuario['resultado'][0]['nombre'];
            $info['cantidad'] = $usuario["count"];
            for($i=0; $i<$usuario["count"]; $i++){    
                $aux_dir["id_pdir"] = $usuario['resultado'][$i]['id_pdir'];
                $aux_dir["direccion"] = $usuario['resultado'][$i]['direccion'];
                $aux_dir["calle"] = $usuario['resultado'][$i]['calle'];
                $aux_dir["num"] = $usuario['resultado'][$i]['num'];
                $aux_dir["depto"] = $usuario['resultado'][$i]['depto'];
                $aux_dir["comuna"] = $usuario['resultado'][$i]['comuna'];
                $aux_dir["lat"] = $usuario['resultado'][$i]['lat'];
                $aux_dir["lng"] = $usuario['resultado'][$i]['lng'];
                $info['direcciones'][] = $aux_dir;
                unset($aux_dir);
            }
        }
        return $info;
        
    }
    public function get_motos(){

        $sql_motos = $this->con->sql("SELECT id_mot, uid FROM motos WHERE eliminado='0'");
        $res['op'] = 2;
        if($sql_motos['count'] > 0){
            $res['op'] = 1;
            for($i=0; $i<$sql_motos['count']; $i++){
                
                $aux['id_mot'] = $sql_motos['resultado'][$i]['id_mot'];
                $aux['code'] = $sql_motos['resultado'][$i]['uid'];
                
                $aux_pedidos = $this->get_pedidos_moto($aux['id_mot']);
                if($aux_pedidos['op'] == 1){
                    $aux['pedidos'] = $aux_pedidos['pedidos'];
                }
                if($aux_pedidos['op'] == 2){
                    $aux['pedidos'] = [];
                }

                $sql_locales = $this->con->sql("SELECT t2.code FROM motos_locales t1, locales t2 WHERE t1.id_mot='".$aux["id_mot"]."' AND t1.id_loc=t2.id_loc AND t2.eliminado='0'");
                if($sql_locales['count'] > 0){
                    for($j=0; $j<$sql_locales['count']; $j++){
                        $aux['locales'][] = $sql_locales['resultado'][$j]['code'];
                    }
                }
                $res['motos'][] = $aux;
                unset($aux);
            }
        }

        return $res;

    }
    public function get_moto($id_mot){

        $sql_motos = $this->con->sql("SELECT id_mot, uid FROM motos WHERE id_mot='".$id_mot."' AND eliminado='0'");
        $res['op'] = 2;
        if($sql_motos['count'] == 1){

            $res['op'] = 1;
            $res['moto']['id_mot'] = $sql_motos['resultado'][0]['id_mot'];
            $res['moto']['code'] = $sql_motos['resultado'][0]['uid'];

            $aux_pedidos = $this->get_pedidos_moto($res['moto']['id_mot']);
            if($aux_pedidos['op'] == 1){
                $res['moto']['pedidos'] = $aux_pedidos['pedidos'];
            }
            if($aux_pedidos['op'] == 2){
                $res['moto']['pedidos'] = [];
            }

            $sql_locales = $this->con->sql("SELECT t2.code FROM motos_locales t1, locales t2 WHERE t1.id_mot='".$res["moto"]["id_mot"]."' AND t1.id_loc=t2.id_loc AND t2.eliminado='0'");
            if($sql_locales['count'] > 0){
                for($j=0; $j<$sql_locales['count']; $j++){
                    $res['moto']['locales'][] = $sql_locales['resultado'][$j]['code'];
                }
            }
        }

        return $res;

    }
    private function get_pedidos_moto($id_mot){
        $sql_pedidos = $this->con->sql("SELECT fecha, code FROM pedidos_aux WHERE id_mot='".$id_mot."' AND fecha > DATE_ADD(NOW(), INTERVAL -2 HOUR)");
        $res['op'] = 2;
        if($sql_pedidos['count'] > 0){
            $res['op'] = 1;
            for($i=0; $i<$sql_pedidos['count']; $i++){
                $aux['id_ped'] = $sql_pedidos['resultado'][$i]['id_ped'];
                $aux['fecha'] = strtotime($sql_pedidos['resultado'][$i]['fecha']) * 1000;
                $aux['code'] = $sql_pedidos['resultado'][$i]['code'];
                $res['pedidos'][] = $aux;
                unset($aux);
            }
        }
        return $res;
    }
    private function get_pedido_moto($id_mot, $id_ped){
        $sql_pedidos = $this->con->sql("SELECT t1.code FROM pedidos_aux t1, motos_locales t2 WHERE t1.id_ped='".$id_ped."' AND t1.id_loc=t2.id_loc AND t2.id_mot='".$id_mot."'");
        $res['op'] = 2;
        if($sql_pedidos['count'] == 1){
            $res['op'] = 1;
            $res['code'] = $sql_pedidos['resultado'][0]['code'];
        }
        return $res;
    }
    public function enviar_contacto(){
        
        $res = $_POST["g-recaptcha-response"]; 
        if(isset($res) && $res){ 
            
            $secret = "6Lf8j3sUAAAAAP6pYvdgk9qiWoXCcKKXGsKFQXH4";
            $v = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$_POST["g-recaptcha-response"]."&remoteip=".$_SERVER["REMOTE_ADDR"]); 
            $data = json_decode(($v)); 
            if($data->{'success'}){ 
                
                $email = $_POST["email"];
                if(filter_var($email, FILTER_VALIDATE_EMAIL)) {

                    $send['email'] = $email;
                    $send['nombre'] = $_POST["nombre"];
                    $send['telefono'] = $_POST["telefono"];
                    $send['asunto'] = $_POST["asunto"];

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/mail_contacto');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                    curl_exec($ch);
                    curl_close($ch);
                    
                    header("Location: https://misitiodelivery.cl?contacto=1");

                }else{
                    header("Location: https://misitiodelivery.cl?contacto=0&tipo=1&error=Correo+Incorrecto");
                    exit;
                }
                
            }else{ 
                header("Location: https://misitiodelivery.cl?contacto=0&tipo=2&error=Error+reCAPTCHA");
                exit;
            } 
            
        }else{ 
            header("Location: https://misitiodelivery.cl?contacto=0&tipo=2&error=Error+reCAPTCHA");
            exit; 
        }
        
    }
    
    public function crear_dominio(){
        
        $res = $_POST["g-recaptcha-response"]; 
        if(isset($res) && $res){ 
            
            $secret = "6Lf8j3sUAAAAAP6pYvdgk9qiWoXCcKKXGsKFQXH4";
            $v = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$_POST["g-recaptcha-response"]."&remoteip=".$_SERVER["REMOTE_ADDR"]); 
            $data = json_decode(($v)); 
            if($data->{'success'}){ 
                
                $correo = $_POST["correo_msd"];
                if(filter_var($correo, FILTER_VALIDATE_EMAIL)) {

                    $validar_correo = $this->con->sql("SELECT * FROM fw_usuarios WHERE correo='".$correo."' AND eliminado='0'");
                    if($validar_correo['count'] == 0){

                        $dominio_val = explode(".", $_POST["dominio_msd"]);
                        if(count($dominio_val) == 3 && $dominio_val[0] == "www" && strlen($dominio_val[1]) > 1 && strlen($dominio_val[2]) > 1){

                            $dominio = $_POST["dominio_msd"];
                            $validar_dominio = $this->con->sql("SELECT * FROM giros WHERE dominio='".$dominio."' AND eliminado='0'");
                            if($validar_dominio['count'] == 0){

                                $code = bin2hex(openssl_random_pseudo_bytes(10));
                                $mailcode = bin2hex(openssl_random_pseudo_bytes(10));
                                $giros = $this->con->sql("INSERT INTO giros (dominio, code, catalogo, fecha_creado, eliminado) VALUES ('".$dominio."', '".$code."', '1', now(), '0')"); 
                                $usuarios = $this->con->sql("INSERT INTO fw_usuarios (correo, mailcode, fecha_creado, admin, eliminado) VALUES ('".$correo."', '".$mailcode."', now(), '0', '0')");
                                $this->con->sql("INSERT INTO fw_usuarios_giros (id_gir, id_user) VALUES ('".$giros['insert_id']."', '".$usuarios['insert_id']."')");
                                $this->con->sql("INSERT INTO catalogo_productos (nombre, fecha_creado, id_gir) VALUES ('Catalogo 01', now(), '".$giros['insert_id']."')");

                                $send['dominio'] = $dominio;
                                $send['correo'] = $correo;
                                $send['id'] = $usuarios['insert_id'];
                                $send['code'] = $mailcode;

                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/mail_inicio');
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                                curl_exec($ch);

                                curl_close($ch);
                                header("Location: https://misitiodelivery.cl?realizado=1");
                                exit;
                                
                            }else{
                                header("Location: https://misitiodelivery.cl?realizado=0&tipo=1&error=Dominio+Existente");
                                exit;
                            }

                        }else{
                            header("Location: https://misitiodelivery.cl?realizado=0&tipo=1&error=Dominio+Existente");
                            exit;
                        }

                    }else{
                        header("Location: https://misitiodelivery.cl?realizado=0&tipo=2&error=Correo+Existente");
                        exit;
                    }

                }else{
                    header("Location: https://misitiodelivery.cl?realizado=0&tipo=2&error=Correo+Incorrecto");
                    exit;
                }
                
            }else{ 
                header("Location: https://misitiodelivery.cl?realizado=0&tipo=3&error=Error+reCAPTCHA");
                exit;
            } 
            
        }else{ 
            header("Location: https://misitiodelivery.cl?realizado=0&tipo=3&error=Error+reCAPTCHA");
            exit; 
        }
        
    }
    
    public function enviar_pedido(){
        
        $info['op'] = 1;        

        $aux_pedido = json_decode($_POST['pedido']);
        $nombre = $aux_pedido->{'nombre'};
        $telefono = str_replace(" ", "", $aux_pedido->{'telefono'});

        if(strlen($nombre) > 2){
        if(strlen($telefono) >= 12 && strlen($telefono) <= 14){

            $puser = json_decode($_POST['puser']);
            
            $puser_id = $puser->{'id_puser'};
            $puser_code = $puser->{'code'};
            $puser_tel = $puser->{'telefono'};
            $puser_nom = $puser->{'nombre'};
            
            $direccion = $aux_pedido->{'direccion'};
            $calle = $aux_pedido->{'calle'};
            $num = $aux_pedido->{'num'};
            $depto = $aux_pedido->{'depto'};
            $comuna = $aux_pedido->{'comuna'};
            $lat = $aux_pedido->{'lat'};
            $lng = $aux_pedido->{'lng'};
            
            $despacho = $aux_pedido->{'despacho'};
            $sql_puser = $this->con->sql("SELECT * FROM pedidos_usuarios WHERE id_puser='".$puser_id."' AND codigo='".$puser_code."' AND telefono='".$puser_tel."'");
            $dir = false;
            
            if($sql_puser['count'] == 0){
                
                $puser_code = bin2hex(openssl_random_pseudo_bytes(10));
                $insert_puser = $this->con->sql("INSERT INTO pedidos_usuarios (codigo, nombre, telefono, cont) VALUES ('".$puser_code."', '".$nombre."', '".$telefono."', '1')");
                $puser_id = $insert_puser['insert_id'];
                $info['set_puser'] = 1;
                $info['puser']['id_puser'] = $puser_id;
                $info['puser']['code'] = $puser_code;
                $info['puser']['nombre'] = $nombre;
                $info['puser']['telefono'] = $telefono;
                
            }
            
            if($sql_puser['count'] == 1){
                
                $this->con->sql("UPDATE pedidos_usuarios SET cont=cont+1 WHERE id_puser='".$puser_id."'");
                $sql_pdir = $this->con->sql("SELECT * FROM pedidos_direccion WHERE id_puser='".$puser_id."'");
                $list_pdir = $sql_pdir['resultado'];
                
                for($i=0; $i<$sql_pdir['count']; $i++){
                    if($list_pdir[$i]['lat'] == $lat && $list_pdir[$i]['lng'] == $lng){
                        $pdir_id = $list_pdir[$i]['id_pdir'];
                        $dir = true;
                    }
                }
                
            }    
            if(!$dir && $despacho == 1){
                
                $insert_pdir = $this->con->sql("INSERT INTO pedidos_direccion (direccion, calle, num, depto, comuna, lat, lng, id_puser) VALUES ('".$direccion."', '".$calle."', '".$num."', '".$depto."', '".$comuna."', '".$lat."', '".$lng."', '".$puser_id."')");
                $pdir_id = $insert_pdir['insert_id'];
                
            }

            $pre_gengibre = $aux_pedido->{'pre_gengibre'};
            $pre_wasabi = $aux_pedido->{'pre_wasabi'};
            $pre_embarazadas = $aux_pedido->{'pre_embarazadas'};
            $pre_palitos = $aux_pedido->{'pre_palitos'};
            $pre_soya = $aux_pedido->{'pre_soya'};
            $pre_teriyaki = $aux_pedido->{'pre_teriyaki'};
            $comentarios = $aux_pedido->{'comentarios'};
            
            $costo = $aux_pedido->{'costo'};
            $total = $aux_pedido->{'total'};
            $verify_despacho = 0;
            
            $id_loc = $aux_pedido->{'id_loc'};
            $loc_gir = $this->con->sql("SELECT t1.t_retiro, t1.t_despacho, t1.code, t1.correo, t2.dominio, t1.activar_envio, t1.lat, t1.lng, t1.id_gir, t2.num_ped FROM locales t1, giros t2 WHERE t1.id_loc='".$id_loc."' AND t1.id_gir=t2.id_gir");
            $info['lat'] = $loc_gir['resultado'][0]['lat'];
            $info['lng'] = $loc_gir['resultado'][0]['lng'];
            $info['t_retiro'] = $loc_gir['resultado'][0]['t_retiro'];
            $info['t_despacho'] = $loc_gir['resultado'][0]['t_despacho'];
            $num_ped = $loc_gir['resultado'][0]['num_ped'] + 1;
            $id_gir = $loc_gir['resultado'][0]['id_gir'];
            
            if($despacho == 1){
                $aux_verify = $this->get_info_despacho($lat, $lng);
                if($aux_verify['op'] == 1 && $aux_verify['id_loc'] == $id_loc && $aux_verify['precio'] == $costo){
                    $verify_despacho = 1;
                }
            }
            
            $tz_object = new DateTimeZone('America/Santiago');
            $datetime = new DateTime();
            $datetime->setTimezone($tz_object);
            $fecha_stgo = $datetime->format('Y-m-d H:i:s');
            


            $pedido_code = bin2hex(openssl_random_pseudo_bytes(10));
            $pedido_sql = $this->con->sql("INSERT INTO pedidos_aux (num_ped, code, fecha, despacho, tipo, id_loc, carro, promos, verify_despacho, pre_gengibre, pre_wasabi, pre_embarazadas, pre_palitos, pre_teriyaki, pre_soya, comentarios, costo, total, id_puser, id_pdir) VALUES ('".$num_ped."', '".$pedido_code."', '".$fecha_stgo."', '".$despacho."', '1', '".$id_loc."', '".$_POST['carro']."', '".$_POST['promos']."', '".$verify_despacho."', '".$pre_gengibre."', '".$pre_wasabi."', '".$pre_embarazadas."', '".$pre_palitos."', '".$pre_teriyaki."', '".$pre_soya."', '".$comentarios."', '".$costo."', '".$total."', '".$puser_id."', '".$pdir_id."')");
            $id_ped = $pedido_sql['insert_id'];
            
            $this->con->sql("UPDATE giros SET num_ped='".$num_ped."' WHERE id_gir='".$id_gir."'");

            $info['op'] = 1;
            $info['id_ped'] = $id_ped;
            $info['num_ped'] = $num_ped;
            $info['pedido_code'] = $pedido_code;
            $info['fecha'] = time();
            
            $pedido['local_code'] = $loc_gir['resultado'][0]['code'];
            $pedido['id_ped'] = $id_ped;
            $pedido['num_ped'] = $num_ped;
            
            $pedido['correo'] = $loc_gir['resultado'][0]['correo'];
            $pedido['accion'] = 'enviar_pedido_local';
            $pedido['activar_envio'] = $loc_gir['resultado'][0]['activar_envio'];
            $pedido['hash'] = 'hash';
            $pedido['dominio'] = $loc_gir['resultado'][0]['dominio'];
            $pedido['pedido_code'] = $pedido_code;
            $pedido['nombre'] = $nombre;
            $pedido['telefono'] = $telefono;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/enviar_local');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pedido));
            $mail_nodejs = json_decode(curl_exec($ch));
            $info['mail'] = ($mail_nodejs->{'op'} == 1) ? true : false ;
            curl_close($ch);
            
        }else{
            $info['op'] = 2;
            $info['mensaje'] = "Error: numero telefonico invalido";
        }
        }else{
            $info['op'] = 2;
            $info['mensaje'] = "Error: debe ingresar su nombre";
        }
        
        return $info;
        
    }
    public function enviar_chat(){

        $id_ped = $_POST["id_ped"];
        $id_loc = $_POST["id_loc"];
        $mensaje = $_POST["mensaje"];
        $pedidos = $this->con->sql("SELECT * FROM locales t1, pedidos_aux t2 WHERE t2.id_ped='".$id_ped."' AND t2.id_loc='".$id_loc."' AND t2.id_loc=t1.id_loc");
        $info["op"] = 2;

        if($pedidos["count"] == 1){

            $pedido['local_code'] = $pedidos['resultado'][0]['code'];
            $pedido['mensaje'] = $mensaje;
            $pedido['accion'] = "enviar_mensaje_local";
            $pedido['hash'] = "hash";
            $info['pedido'] = $pedido;
            $info['op'] = 1;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/enviar_chat');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pedido));
            $info["mail_nodejs"] = json_decode(curl_exec($ch));
            curl_close($ch);

        }

        return $info;

    }
    public function get_info_despacho($lat, $lng){

        $polygons = $this->get_polygons();
        $precio = 9999999;
        $info['op'] = 2;
        foreach($polygons as $polygon){

            $lats = [];
            $lngs = [];
            $puntos = json_decode($polygon['poligono']);
            foreach($puntos as $punto){
                $lats[] = $punto->{'lat'};
                $lngs[] = $punto->{'lng'};
            }
            $is = $this->is_in_polygon($lats, $lngs, $lat, $lng);
            if($is){
                if($precio > $polygon['precio']){
                    $info['op'] = 1;
                    $info['id_loc'] = intval($polygon['id_loc']);
                    $info['precio'] = intval($polygon['precio']);
                    $info['nombre'] = $polygon['nombre'];
                    $info['lat'] = $lat;
                    $info['lng'] = $lng;
                    $precio = $polygon['precio'];
                }
            }
        }
        return $info;
        
    }
    function is_in_polygon($vertices_x, $vertices_y, $longitude_x, $latitude_y){
        $points_polygon = count($vertices_x) - 1;
        $i = $j = $c = $point = 0;
        for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
            $point = $i;
            if( $point == $points_polygon )
                $point = 0;
            if ( (($vertices_y[$point]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) && ($longitude_x < ($vertices_x[$j] - $vertices_x[$point]) * ($latitude_y - $vertices_y[$point]) / ($vertices_y[$j] - $vertices_y[$point]) + $vertices_x[$point]) ) )
                $c = !$c;
        }
        return $c;
    }
    public function get_polygons(){

        $referer = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST);
        if($referer == "www.misitiodelivery.cl" || $referer == "misitiodelivery.cl"){
            $referer = $_POST["referer"];
        }
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