<?php
require_once "/var/www/html/config/config.php";

class Rest{
    
    public $con = null;
    public $eliminado = 0;

    public function __construct(){
        
        global $db_host;
        global $db_user;
        global $db_password;
        global $db_database;

        $this->con = new mysqli($db_host[0], $db_user[0], $db_password[0], $db_database[0]);
        
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
        $referer = ($referer == "www.misitiodelivery.cl" || $referer == "misitiodelivery.cl") ? $_POST["referer"] : parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST) ;

        $sql = $this->con->prepare("SELECT t1.id_puser, t1.nombre, t2.id_pdir, t2.direccion, t2.calle, t2.num, t2.depto, t2.comuna, t2.lat, t2.lng FROM pedidos_usuarios t1, pedidos_direccion t2, giros t3 WHERE t3.dominio=? AND t3.id_gir=t1.id_gir AND t1.telefono=? AND t1.id_puser=t2.id_puser");
        $sql->bind_param("ss", $referer, $telefono);
        $sql->execute();
        $sql->store_result();
        $info['cantidad'] = 0;

        if($sql->{"num_rows"} > 0){

            $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
            $info['id_puser'] = $result[0]['id_puser'];
            $info['nombre'] = $result[0]['nombre'];
            $info['cantidad'] = $sql->{"num_rows"};
            for($i=0; $i<count($result); $i++){

                $aux_dir["id_pdir"] = $result[$i]['id_pdir'];
                $aux_dir["direccion"] = $result[$i]['direccion'];
                $aux_dir["calle"] = $result[$i]['calle'];
                $aux_dir["num"] = $result[$i]['num'];
                $aux_dir["depto"] = $result[$i]['depto'];
                $aux_dir["comuna"] = $result[$i]['comuna'];
                $aux_dir["lat"] = $result[$i]['lat'];
                $aux_dir["lng"] = $result[$i]['lng'];
                $info['direcciones'][] = $aux_dir;
                unset($aux_dir);

            }

        }
        $sql->free_result();
        $sql->close();

        return $info;
        
    }
    public function get_motos(){

        $sql = $this->con->prepare("SELECT id_mot, uid FROM motos WHERE eliminado=?");
        $sql->bind_param("i", $this->eliminado);
        $sql->execute();
        $res = $sql->get_result();

	    if($res->{'num_rows'} > 0){

            $resu['op'] = 1;
            $result = $res->fetch_all(MYSQLI_ASSOC);
            for($i=0; $i<count($result); $i++){
                
                $aux['id_mot'] = $result[$i]['id_mot'];
                $aux['code'] = $result[$i]['uid'];
                
                $aux_pedidos = $this->get_pedidos_moto($result[$i]['id_mot']);
                if($aux_pedidos['op'] == 1){
                    $aux['pedidos'] = $aux_pedidos['pedidos'];
                }
                if($aux_pedidos['op'] == 2){
                    $aux['pedidos'] = [];
                }

                $sqlml = $this->con->prepare("SELECT t2.code FROM motos_locales t1, locales t2 WHERE t1.id_mot=? AND t1.id_loc=t2.id_loc AND t2.eliminado=?");
                $sqlml->bind_param("ii", $result[$i]['id_mot'], $this->eliminado);
                $sqlml->execute();
                $res2 = $sqlml->get_result();
                if($res2->{"num_rows"} > 0){
                    $result2 = $res2->fetch_all(MYSQLI_ASSOC);
                    for($j=0; $j<count($result2); $j++){
                        $aux['locales'][] = $result2[$j]['code'];
                    }
                }

                $resu['motos'][] = $aux;
                unset($aux);

                $sqlml->free_result();
                $sqlml->close();
            }

        }
        if($res->{'num_rows'} == 0){

            $resu['op'] = 2;

        }

        $sql->free_result();
        $sql->close();
        return $resu;

    }
    public function get_moto($id_mot){

        $sql = $this->con->prepare("SELECT id_mot, uid FROM motos WHERE id_mot=? AND eliminado=?");
        $sql->bind_param("ii", $id_mot, $this->eliminado);
        $sql->execute();
        $sql->store_result();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        if($sql->{"num_rows"} == 1){

            $res['op'] = 1;
            $res['moto']['id_mot'] = $result['id_mot'];
            $res['moto']['code'] = $result['uid'];

            $aux_pedidos = $this->get_pedidos_moto($res['moto']['id_mot']);
            if($aux_pedidos['op'] == 1){
                $res['moto']['pedidos'] = $aux_pedidos['pedidos'];
            }
            if($aux_pedidos['op'] == 2){
                $res['moto']['pedidos'] = [];
            }

            $sqlml = $this->con->prepare("SELECT t2.code FROM motos_locales t1, locales t2 WHERE t1.id_mot=? AND t1.id_loc=t2.id_loc AND t2.eliminado=?");
            $sqlml->bind_param("ii", $result['id_mot'], $this->eliminado);
            $sqlml->execute();
            $sqlml->store_result();
            $result = $sqlml->get_result()->fetch_all(MYSQLI_ASSOC);
            if($sqlml->{"num_rows"} > 0){
                for($j=0; $j<count($result); $j++){
                    $res['moto']['locales'][] = $result[$j]['code'];
                }
            }
            $sqlml->free_result();
            $sqlml->close();

        }
        if($sql->{"num_rows"} == 0){
            $res['op'] = 2;
        }
        $sql->free_result();
        $sql->close();
        return $res;

    }
    private function get_pedidos_moto($id_mot){

        $sql = $this->con->prepare("SELECT fecha, code FROM pedidos_aux WHERE id_mot=? AND fecha > DATE_ADD(NOW(), INTERVAL -2 HOUR)");
        $sql->bind_param("i", $id_mot);
        $sql->execute();
        $sql->store_result();
        $res = $sql->get_result();

        if($res->{"num_rows"} > 0){
            $resu['op'] = 1;
            $result = $res->fetch_all(MYSQLI_ASSOC);
            for($i=0; $i<count($result); $i++){
                $aux['id_ped'] = $result[$i]['id_ped'];
                $aux['fecha'] = strtotime($result[$i]['fecha']) * 1000;
                $aux['code'] = $result[$i]['code'];
                $resu['pedidos'][] = $aux;
                unset($aux);
            }
        }
        if($res->{"num_rows"} == 0){
            $resu['op'] = 2;
        }

        $sql->free_result();
        $sql->close();
        return $resu;

    }
    private function get_pedido_moto($id_mot, $id_ped){

        $sql = $this->con->prepare("SELECT t1.code FROM pedidos_aux t1, motos_locales t2 WHERE t1.id_ped=? AND t1.id_loc=t2.id_loc AND t2.id_mot=?");
        $sql->bind_param("ii", $id_ped, $id_mot);
        $sql->execute();
        $sql->store_result();
        $code = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]["code"];
        if($sql->{"num_rows"} == 1){
            $res['op'] = 1;
            $res['code'] = $code;
        }
        if($sql->{"num_rows"} == 0){
            $res['op'] = 2;
        }
        $sql->free_result();
        $sql->close();

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

                    $sql = $this->con->prepare("SELECT * FROM fw_usuarios WHERE correo=? AND eliminado=?");
                    $sql->bind_param("si", $correo, $this->eliminado);
                    $sql->execute();
                    $sql->store_result();

                    if($sql->{"num_rows"} == 0){

                        $dominio_val = explode(".", $_POST["dominio_msd"]);
                        if(count($dominio_val) == 3 && $dominio_val[0] == "www" && strlen($dominio_val[1]) > 1 && strlen($dominio_val[2]) > 1){

                            $dominio = $_POST["dominio_msd"];
                            $sqlg = $this->con->prepare("SELECT * FROM giros WHERE dominio=? AND eliminado=?");
                            $sqlg->bind_param("si", $dominio, $this->eliminado);
                            $sqlg->execute();
                            $sqlg->store_result();

                            if($sqlg->{"num_rows"} == 0){

                                $telefono_val = $_POST["telefono_msd"];
                                $code = bin2hex(openssl_random_pseudo_bytes(10));
                                $mailcode = bin2hex(openssl_random_pseudo_bytes(10));

                                $catalogo = 1;
                                $sqligi = $this->con->prepare("INSERT INTO giros (telefono, dominio, fecha_creado, code, catalogo) VALUES (?, ?, now(), ?, ?)");
                                $sqligi->bind_param("sssi", $telefono_val, $dominio, $code, $catalogo);
                                $sqligi->execute();
                                $giro_id = $this->con->insert_id;
                                $sqligi->close();

                                $admin = 0;
                                $sqlis = $this->con->prepare("INSERT INTO fw_usuarios (correo, mailcode, fecha_creado, admin) VALUES (?, ?, now(), ?)");
                                $sqlis->bind_param("sssi", $correo, $mailcode, $admin);
                                $sqlis->execute();
                                $usuario_id = $this->con->insert_id;
                                $sqlis->close();

                                $sqliug = $this->con->prepare("INSERT INTO fw_usuarios_giros (id_gir, id_user) VALUES (?, ?)");
                                $sqliug->bind_param("ii", $giro_id, $usuario_id);
                                $sqliug->execute();
                                $sqliug->close();

                                $n_catalogo = "Catalogo 01";
                                $sqlicp = $this->con->prepare("INSERT INTO catalogo_productos (nombre, fecha_creado, id_gir) VALUES (?, now(), ?)");
                                $sqlicp->bind_param("si", $n_catalogo, $giro_id);
                                $sqlicp->execute();
                                $sqlicp->close();

                                $send['dominio'] = $dominio;
                                $send['correo'] = $correo;
                                $send['id'] = $usuario_id;
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

                            $sqlg->free_result();
                            $sqlg->close();

                        }else{
                            header("Location: https://misitiodelivery.cl?realizado=0&tipo=1&error=Dominio+Existente");
                            exit;
                        }

                    }else{
                        header("Location: https://misitiodelivery.cl?realizado=0&tipo=2&error=Correo+Existente");
                        exit;
                    }

                    $sql->free_result();
                    $sql->close();

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

            $sql = $this->con->prepare("SELECT * FROM pedidos_usuarios WHERE id_puser=? AND codigo=? AND telefono=?");
            $sql->bind_param("isi", $puser_id, $puser_code, $puser_tel);
            $sql->execute();
            $sql->store_result();
            $dir = false;
            
            if($sql->{"num_rows"} == 0){

                $puser_code = bin2hex(openssl_random_pseudo_bytes(10));
                $cont = 1;
                $sqlipu = $this->con->prepare("INSERT INTO pedidos_usuarios (codigo, nombre, telefono, cont) VALUES (?, ?, ?, ?)");
                $sqlipu->bind_param("sssi", $puser_code, $nombre, $telefono, $cont);
                $sqlipu->execute();
                $puser_id = $this->con->insert_id;
                $sqlipu->close();

                $info['set_puser'] = 1;
                $info['puser']['id_puser'] = $puser_id;
                $info['puser']['code'] = $puser_code;
                $info['puser']['nombre'] = $nombre;
                $info['puser']['telefono'] = $telefono;

            }
            if($sql->{"num_rows"} == 1){

                $sqlupu = $this->con->prepare("UPDATE pedidos_usuarios SET cont=cont+1 WHERE id_puser=?");
                $sqlupu->bind_param("i", $puser_id);
                $sqlupu->execute();
                $sqlupu->close();


                $sqlpd = $this->con->prepare("SELECT * FROM pedidos_direccion WHERE id_puser=?");
                $sqlpd->bind_param("i", $puser_id);
                $sqlpd->execute();
                $list_pdir = $sqlpd->get_result()->fetch_all(MYSQLI_ASSOC);
                $sqlpd->free_result();
                $sqlpd->close();

                for($i=0; $i<count($sql_pdir); $i++){
                    if($list_pdir[$i]['lat'] == $lat && $list_pdir[$i]['lng'] == $lng){
                        $pdir_id = $list_pdir[$i]['id_pdir'];
                        $dir = true;
                    }
                }

            }

            $sql->free_result();
            $sql->close();
   
            if(!$dir && $despacho == 1){
                
                $sqlpdi = $this->con->prepare("INSERT INTO pedidos_direccion (direccion, calle, num, depto, comuna, lat, lng, id_puser) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $sqlpdi->bind_param("sssssddi", $direccion, $calle, $num, $depto, $comuna, $lat, $lng, $puser_id);
                $sqlpdi->execute();
                $pdir_id = $this->con->insert_id;
                $sqlpdi->close();
                
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

            $sqllg = $this->con->prepare("SELECT t1.t_retiro, t1.t_despacho, t1.code, t1.correo, t2.dominio, t1.activar_envio, t1.lat, t1.lng, t1.id_gir, t2.num_ped FROM locales t1, giros t2 WHERE t1.id_loc=? AND t1.id_gir=t2.id_gir AND t1.eliminado=? AND t2.eliminado=?");
            $sqllg->bind_param("iii", $id_loc, $this->eliminado, $this->eliminado);
            $sqllg->execute();
            $resultlg = $sqllg->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            $sqllg->free_result();
            $sqllg->close();

            $info['lat'] = $resultlg['lat'];
            $info['lng'] = $resultlg['lng'];
            $info['t_retiro'] = $resultlg['t_retiro'];
            $info['t_despacho'] = $resultlg['t_despacho'];
            $num_ped = $resultlg['num_ped'] + 1;
            $id_gir = $resultlg['id_gir'];
            
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
            $tipo = 1;

            $sqlipa = $this->con->prepare("INSERT INTO pedidos_aux (num_ped, code, fecha, despacho, tipo, id_loc, carro, promos, verify_despacho, pre_gengibre, pre_wasabi, pre_embarazadas, pre_palitos, pre_teriyaki, pre_soya, comentarios, costo, total, id_puser, id_pdir) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $sqlipa->bind_param("issiiissiiiiiiisiiii", $num_ped, $pedido_code, $fecha_stgo, $despacho, $tipo, $id_loc, $_POST['carro'], $_POST['promos'], $verify_despacho, $pre_gengibre, $pre_wasabi, $pre_embarazadas, $pre_palitos, $pre_teriyaki, $pre_soya, $comentarios, $costo, $total, $puser_id, $pdir_id);
            $sqlipa->execute();
            $id_ped = $this->con->insert_id;
            $sqlipa->close();

            $sqlugi = $this->con->prepare("UPDATE giros SET num_ped=? WHERE id_gir=? AND eliminado=?");
            $sqlugi->bind_param("iii", $num_ped, $id_gir, $this->eliminado);
            $sqlugi->execute();
            $sqlugi->close();

            $info['op'] = 1;
            $info['id_ped'] = $id_ped;
            $info['num_ped'] = $num_ped;
            $info['pedido_code'] = $pedido_code;
            $info['fecha'] = time();
            
            $pedido['local_code'] = $resultlg['code'];
            $pedido['id_ped'] = $id_ped;
            $pedido['num_ped'] = $num_ped;
            $pedido['pedido_code'] = $pedido_code;
            
            $pedido['correo'] = $resultlg['correo'];
            $pedido['accion'] = 'enviar_pedido_local';
            $pedido['activar_envio'] = $resultlg['activar_envio'];
            $pedido['hash'] = 'Lrk}..75sq[e)@/22jS?ZGJ<6hyjB~d4gp2>^qHm';
            $pedido['dominio'] = $resultlg['dominio'];
            $pedido['nombre'] = $nombre;
            $pedido['telefono'] = $telefono;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/enviar_local');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pedido));
            curl_exec($ch);
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
        $code = $_POST["code"];
        $mensaje = $_POST["mensaje"];
        
        $sql = $this->con->prepare("SELECT t1.code FROM locales t1, pedidos_aux t2 WHERE t2.code='".$code."' AND t2.id_ped='".$id_ped."' AND t2.id_loc='".$id_loc."' AND t2.id_loc=t1.id_loc");
        $sql->bind_param("sii", $code, $id_ped, $id_loc);
        $sql->execute();
        $sql->store_result();
        $loc_code = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]["code"];

        if($sql->{"num_rows"} == 1){

            $info['op'] = 1;
            
            $pedido['local_code'] = $loc_code;
            $pedido['mensaje'] = $mensaje;
            $pedido['accion'] = "enviar_mensaje_local";
            $pedido['hash'] = "hash";
            $pedido['id_ped'] = $id_ped;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/enviar_chat');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pedido));
            curl_exec($ch);
            curl_close($ch);

        }
        if($sql->{"num_rows"} == 0){

            $info["op"] = 2;

        }
        $sql->free_result();
        $sql->close();

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
        for($i=0, $j=$points_polygon ; $i<$points_polygon; $j=$i++) {
            $point = $i;
            if($point == $points_polygon)
                $point = 0;
            if((($vertices_y[$point] > $latitude_y != ($vertices_y[$j] > $latitude_y)) && ($longitude_x < ($vertices_x[$j] - $vertices_x[$point]) * ($latitude_y - $vertices_y[$point]) / ($vertices_y[$j] - $vertices_y[$point]) + $vertices_x[$point])))
                $c = !$c;
        }
        return $c;
    }
    public function get_polygons(){

        $referer = ($referer == "www.misitiodelivery.cl" || $referer == "misitiodelivery.cl") ? $_POST["referer"] : parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST) ;

        $sql = $this->con->prepare("SELECT t3.nombre, t3.poligono, t3.precio, t3.id_loc FROM giros t1, locales t2, locales_tramos t3 WHERE t1.dominio=? AND t1.id_gir=t2.id_gir AND t2.id_loc=t3.id_loc AND t2.eliminado=? AND t3.eliminado=?");
        $sql->bind_param("iii", $referer, $this->eliminado, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();

        return $result;
        
    }
    
}
// QUE ME DEVUELTA CATEGORIA Y SUS VALORES
?>