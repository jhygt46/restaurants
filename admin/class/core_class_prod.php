<?php
session_start();

if(strpos($_SERVER["REQUEST_URI"], "core_class_prod.php") !== false){
    header('HTTP/1.1 404 Not Found', true, 404);
    include('../../errors/404.html');
    exit;
}

require_once DIR."db.php";
require_once DIR_BASE."config/config.php";

class Core{
    
    public $con = null;
    public $id_user = null;
    public $admin = null;
    public $re_venta = null;
    public $id_aux_user = null;
    public $id_gir = null;
    public $id_cat = null;
    public $eliminado = 0;
    public $codenodejs = "k8Dqa2C9lKgxT6kpNs1z6RgKb0r3WaCvN6RjK7rU";
    
    public function __construct(){

        global $db_host;
        global $db_user;
        global $db_password;
        global $db_database;

        $this->con = new mysqli($db_host[0], $db_user[0], $db_password[0], $db_database[0]);
        $this->id_user = (isset($_SESSION['user']['info']['id_user'])) ? $_SESSION['user']['info']['id_user'] : 0 ;
        $this->admin = (isset($_SESSION['user']['info']['admin'])) ? $_SESSION['user']['info']['admin'] : 0 ;
        $this->re_venta = (isset($_SESSION['user']['info']['re_venta'])) ? $_SESSION['user']['info']['re_venta'] : 0 ;
        $this->id_aux_user = (isset($_SESSION['user']['info']['id_aux_user'])) ? $_SESSION['user']['info']['id_aux_user'] : 0 ;
        $this->id_gir = (isset($_SESSION['user']['id_gir'])) ? $_SESSION['user']['id_gir'] : 0 ;
        $this->id_cat = (isset($_SESSION['user']['id_cat'])) ? $_SESSION['user']['id_cat'] : 0 ;
        
    }
    private function registrar($id_des, $id_loc, $id_gir, $txt){

        $sqlipd = $this->con->prepare("INSERT INTO seguimiento (id_des, id_user, id_loc, id_gir, fecha, txt) VALUES (?, ?, ?, ?, now(), ?)");
        $sqlipd->bind_param("iiiis", $id_des, $this->id_user, $id_loc, $id_gir, $txt);
        $sqlipd->execute();
        $sqlipd->close();

    }
    private function is_in_polygon($vertices_x, $vertices_y, $longitude_x, $latitude_y){
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
    private function get_polygons_id($id_gir){

        if($sql = $this->con->prepare("SELECT t3.nombre, t3.poligono, t3.precio, t3.id_loc FROM giros t1, locales t2, locales_tramos t3 WHERE t1.id_gir=? AND t1.id_gir=t2.id_gir AND t2.id_loc=t3.id_loc AND t2.eliminado=? AND t3.eliminado=?")){
            if($sql->bind_param("iii", $id_gir, $this->eliminado, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $id_gir, 'get_polygons_id() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $id_gir, 'get_polygons_id() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $id_gir, 'get_polygons_id() '.htmlspecialchars($this->con->error)); }
        
    }
    private function get_info_despacho($lat, $lng, $id_gir){

        $polygons = $this->get_polygons_id($id_gir);
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
                    $precio = $polygon['precio'];
                }
            }
        }
        return $info;

    }
    private function verify_despacho($despacho, $lat, $lng, $costo, $id_loc, $id_gir){

        $res = false;
        if($despacho == 1){
            $aux = $this->get_info_despacho($lat, $lng, $id_gir);
            if($aux['op'] == 1 && $aux['id_loc'] == $id_loc && $aux['precio'] == $costo){
                $res = true;
            }
        }
        if($despacho == 0){
            $res = true;
        }
        return $res;

    }
    private function verificar_pedido($despacho, $lat, $lng, $costo){

        $return['op'] = false;
        $host = $_POST["host"];
        $id_loc = $_POST["id_loc"];
        if($sqlgir = $this->con->prepare("SELECT t1.t_retiro, t1.t_despacho, t1.code as local_code, t1.correo, t2.ssl, t2.dominio, t1.activar_envio, t1.lat, t1.lng, t2.num_ped, t1.telefono, t3.ip, t3.code as server_code, t2.id_gir, t1.id_loc, t1.enviar_cocina, t1.fecha_pos FROM locales t1, giros t2, server t3 WHERE t1.id_loc=? AND t1.id_gir=t2.id_gir AND t2.dominio=? AND t2.id_ser=t3.id_ser AND t1.eliminado=? AND t1.eliminado=t2.eliminado")){
            if($sqlgir->bind_param("isi", $id_loc, $host, $this->eliminado)){
                if($sqlgir->execute()){
                    $res = $sqlgir->get_result();
                    if($res->{'num_rows'} == 1){
                        $result = $res->fetch_all(MYSQLI_ASSOC)[0];
                        $ip = $this->getUserIpAddr();
                        if($ip == $result["ip"]){
                            if($_SERVER['SERVER_PORT'] == "443"){
                                $code = substr($_POST["code"], 0, 40);
                                if($code == $result["server_code"]){
                                    if($this->verify_despacho($despacho, $lat, $lng, $costo, $id_loc, $result['id_gir'])){
                                        $return['op'] = true;
                                        $return['lat'] = $result['lat'];
                                        $return['lng'] = $result['lng'];
                                        $return['t_retiro'] = $result['t_retiro'];
                                        $return['t_despacho'] = $result['t_despacho'];
                                        $return['num_ped'] = $result['num_ped'] + 1;
                                        $return['id_loc'] = $result['id_loc'];
                                        $return['id_gir'] = $result['id_gir'];
                                        $return['code'] = $result['local_code'];
                                        $return['correo'] = $result['correo'];
                                        $return['activar_envio'] = $result['activar_envio'];
                                        $return['enviar_cocina'] = $result['enviar_cocina'];
                                        $return['telefono'] = $result['telefono'];
                                        $aux_url = ($result['ssl'] == 1) ? 'https://' : 'http://' ;
                                        $return['url'] = $aux_url.$result['dominio'];
                                        $return['id_gir'] = $result["id_gir"];
                                        $return['envio_pos'] = (time() - strtotime($result["fecha_pos"]) < 57600) ? 1 : 0 ;
                                    }else{
                                        $this->registrar(15, 0, $result["id_gir"], 'verify_despacho() pedido no verificado');
                                    }
                                }else{ $this->registrar(15, 0, $result["id_gir"], 'verificar() #1 codigo no encontrado'); }
                            }else{ $this->registrar(15, 0, $result["id_gir"], 'verificar() #1 puerto distinto a 443'); }
                        }else{ $this->registrar(15, 0, $result["id_gir"], 'verificar() #1 ip distinta'); }
                    }else{ $this->registrar(15, 0, $result["id_gir"], 'verificar() #1 host no encontrada'); }
                    $sqlgir->free_result();
                    $sqlgir->close();
                }else{ $this->registrar(6, 0, 0, 'verificar() #2 '.$sqlgir->error); }
            }else{ $this->registrar(6, 0, 0, 'verificar() #2 '.$sqlgir->error); }
        }else{ $this->registrar(6, 0, 0, 'verificar() #2 '.$this->con->error); }
        return $return;

    }
    public function enviar_pedido(){
        
        $info['op'] = 2;
        $pedido = $_POST['pedido'];
        $verificar = $this->verificar_pedido($pedido['despacho'], $pedido['lat'], $pedido['lng'], $pedido['costo']);
        if($verificar['op']){

            $id_loc = $verificar['id_loc'];
            $id_gir = $verificar['id_gir'];
            $puser = $_POST['puser'];
            $carro = $_POST['carro'];
            $promos = (isset($_POST['promos']))? $_POST['promos'] : [] ;
            $info['set_puser'] = 0;
            $pdir_id = 0;
            $id_puser = 0;

            // PEDIDOS USUARIOS Y DIRECCIONES //
            if($sql = $this->con->prepare("SELECT * FROM pedidos_usuarios WHERE id_puser=? AND codigo=?")){
                if($sql->bind_param("is", $puser["id_puser"], $puser["code"])){
                    if($sql->execute()){
                        $res = $sql->get_result();

                        // USUARIO NUEVO
                        if($res->{'num_rows'} == 0){
                            $puser_code = $this->pass_generate(20);
                            $cont = 1;

                            if($sqlipu = $this->con->prepare("INSERT INTO pedidos_usuarios (codigo, nombre, telefono, cont, fecha_ultimo, tipo, id_gir, eliminado) VALUES (?, ?, ?, ?, now(), ?, ?, ?)")){
                                if($sqlipu->bind_param("sssiiii", $puser_code, $pedido["nombre"], $pedido["telefono"], $cont, $this->eliminado, $id_gir, $this->eliminado)){
                                    if($sqlipu->execute()){
                                        $id_puser = $this->con->insert_id;
                                        $info['set_puser'] = 1;
                                        $info['puser_id'] = $id_puser;
                                        $info['puser_code'] = $puser_code;
                                        $info['puser_nombre'] = $pedido["nombre"];
                                        $info['puser_telefono'] = $pedido["telefono"];
                                        if($pedido['despacho'] == 1){
                                            $pdir_id = $this->pedido_direccion($pedido, $id_puser);
                                        }
                                        $sqlipu->close();
                                    }else{ $this->registrar(6, $id_loc, $id_gir, 'enviar_pedido() #1 '.htmlspecialchars($sqlipu->error)); }
                                }else{ $this->registrar(6, $id_loc, $id_gir, 'enviar_pedido() #1 '.htmlspecialchars($sqlipu->error)); }
                            }else{ $this->registrar(6, $id_loc, $id_gir, 'enviar_pedido() #1 '.htmlspecialchars($this->con->error)); }
                        }

                        // USUARIO EXISTENTE
                        if($res->{'num_rows'} == 1){

                            $id_puser = $puser["id_puser"];
                            $puser_res = $res->fetch_all(MYSQLI_ASSOC)[0];

                            $cont = $puser_res["cont"] + 1;
                            $dif_usuario_tiempo = time() - strtotime($puser_res["fecha_ultimo"]);
                            if($dif_usuario_tiempo < 43200){
                                $t_user = intval($dif_usuario_tiempo/3600);
                                $info['alert'] = "Usuarios hizo otro pedidos antes de ".$t_user." hrs";
                            }

                            if($sqlupu = $this->con->prepare("UPDATE pedidos_usuarios SET cont=?, fecha_ultimo=now(), nombre=?, telefono=? WHERE id_puser=?")){
                                if($sqlupu->bind_param("issi", $cont, $pedido["nombre"], $pedido["telefono"], $id_puser)){
                                    if($sqlupu->execute()){
                                        $sqlupu->close();
                                    }else{ $this->registrar(6, $id_loc, $id_gir, 'enviar_pedido() #2 '.htmlspecialchars($sqlupu->error)); }
                                }else{ $this->registrar(6, $id_loc, $id_gir, 'enviar_pedido() #2 '.htmlspecialchars($sqlupu->error)); }
                            }else{ $this->registrar(6, $id_loc, $id_gir, 'enviar_pedido() #2 '.htmlspecialchars($this->con->error)); }
                            
                            if($sqlpd = $this->con->prepare("SELECT id_pdir FROM pedidos_direccion WHERE id_puser=? AND lat=? AND lng=?")){
                                if($sqlpd->bind_param("idd", $id_puser, $pedido['lat'], $pedido['lng'])){
                                    if($sqlpd->execute()){
                                        $res_pdir = $sqlpd->get_result();
                                        if($res_pdir->{'num_rows'} == 1){
                                            $pdir_id = $res_pdir->fetch_all(MYSQLI_ASSOC)[0]["id_pdir"];
                                        }
                                        if($res_pdir->{'num_rows'} == 0 && $pedido['despacho'] == 1){
                                            $pdir_id = $this->pedido_direccion($pedido, $id_puser);
                                        }
                                        $sqlpd->free_result();
                                        $sqlpd->close();
                                    }else{ $this->registrar(6, $id_loc, $id_gir, 'enviar_pedido() #3 '.htmlspecialchars($sqlpd->error)); }
                                }else{ $this->registrar(6, $id_loc, $id_gir, 'enviar_pedido() #3 '.htmlspecialchars($sqlpd->error)); }
                            }else{ $this->registrar(6, $id_loc, $id_gir, 'enviar_pedido() #3 '.htmlspecialchars($this->con->error)); }
                        }

                        $sql->free_result();
                        $sql->close();
                    }else{ $this->registrar(6, $id_loc, $id_gir, 'enviar_pedido() #4 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, $id_loc, $id_gir, 'enviar_pedido() #4 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, $id_loc, $id_gir, 'enviar_pedido() #4 '.htmlspecialchars($this->con->error)); }
            // FIN PEDIDOS USUARIOS Y DIRECCIONES //

            /*
            $tz_object = new DateTimeZone('America/Santiago');
            $datetime = new DateTime();
            $datetime->setTimezone($tz_object);
            $fecha_stgo = $datetime->format('Y-m-d H:i:s');
            */
            
            $time_stgo = time();
            $fecha_stgo = date('Y-m-d H:i:s', $time_stgo);
            $pedido_code = $this->pass_generate(20);
            $tipo = 1;

            if($sqlipa = $this->con->prepare("INSERT INTO pedidos_aux (num_ped, code, fecha, despacho, tipo, id_loc, carro, promos, pre_gengibre, pre_wasabi, pre_palitos, pre_teriyaki, pre_soya, comentarios, costo, total, id_puser, id_pdir, id_gir) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")){
                if($sqlipa->bind_param("issiiissiiiiisiiiii", $verificar['num_ped'], $pedido_code, $fecha_stgo, $pedido['despacho'], $tipo, $id_loc, json_encode($_POST['carro']), json_encode($promos), $pedido["pre_gengibre"], $pedido["pre_wasabi"], $pedido["pre_palitos"], $pedido["pre_teriyaki"], $pedido["pre_soya"], $pedido["comentarios"], $pedido["costo"], $pedido["total"], $id_puser, $pdir_id, $id_gir)){
                    if($sqlipa->execute()){

                        $id_ped = $this->con->insert_id;
                        if($sqlugi = $this->con->prepare("UPDATE giros SET num_ped=? WHERE id_gir=? AND eliminado=?")){
                            if($sqlugi->bind_param("iii", $verificar['num_ped'], $id_gir, $this->eliminado)){
                                if($sqlugi->execute()){
                                    $sqlugi->close();
                                }else{ $this->registrar(6, 0, $id_gir, 'enviar_pedido() #5 '.htmlspecialchars($sqlugi->error)); }
                            }else{ $this->registrar(6, 0, $id_gir, 'enviar_pedido() #5 '.htmlspecialchars($sqlugi->error)); }
                        }else{ $this->registrar(6, 0, $id_gir, 'enviar_pedido() #5 '.htmlspecialchars($this->con->error)); }
                        
                        $info['op'] = 1;
                        $info['id_ped'] = $id_ped;
                        $info['num_ped'] = $verificar['num_ped'];
                        $info['lat'] = $verificar['lat'];
                        $info['lng'] = $verificar['lng'];
                        if($pedido['despacho'] == 0){ $info['t_retiro'] = $verificar['t_retiro']; }
                        if($pedido['despacho'] == 1){ $info['t_despacho'] = $verificar['t_despacho']; }
                        $info['pedido_code'] = $pedido_code;
                        $info['fecha'] = $time_stgo;
                        $info['activar_envio'] = $verificar['activar_envio'];

                        $pedido_m['accion'] = 'enviar_pedido_local';
                        $pedido_m['hash'] = 'Lrk}..75sq[e)@/22jS?ZGJ<6hyjB~d4gp2>^qHm';

                        if($verificar['activar_envio'] == 1){
                            $pedido_m['correo'] = $verificar['correo'];
                            $pedido_m['nombre'] = $pedido["nombre"];
                            $pedido_m['telefono'] = $pedido["telefono"];
                            $pedido_m['pedido_code'] = $pedido_code;
                            $pedido_m['num_ped'] = $verificar['num_ped'];
                            $pedido_m['dominio'] = $verificar['dominio'];
                        }
                        if($verificar['enviar_cocina'] == 1 || $verificar['envio_pos'] == 1){
                            $pedido_m['id_ped'] = $id_ped;
                            $pedido_m['local_code'] = $verificar['code'];
                        }
                        
                        $pedido_m['enviar_pos'] = $verificar['envio_pos'];
                        $pedido_m['activar_envio'] = $verificar['activar_envio'];
                        $pedido_m['enviar_cocina'] = $verificar['enviar_cocina'];

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/enviar_local');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pedido_m));
                        
                        if(!curl_errno($ch)){
                            $resp = json_decode(curl_exec($ch));
                            if($verificar['activar_envio'] == 1){
                                if($resp->{'op'} == 1){
                                    $info['email'] = 1;
                                }
                                if($resp->{'op'} == 2){
                                    $info['email'] = 2;
                                    $info['telefono'] = $verificar['telefono'];
                                    $info['correo'] = $verificar['correo'];
                                    $info['url'] = $verificar['url'];
                                    $this->registrar(16, $id_loc, $id_gir, 'Error no se envio mail enviar_pedido()');
                                }
                            }
                            curl_close($ch);
                        }else{
                            $this->registrar(17, $id_loc, $id_gir, 'Error Curl enviar_pedido()');
                        }
                        $sqlipa->close();
                        
                    }else{

                        $info['op'] = 2;
                        $info['telefono'] = $verificar['telefono'];
                        $info['correo'] = $verificar['correo'];
                        $info['url'] = $verificar['url'];
                        $this->registrar(6, $id_loc, $id_gir, 'enviar_pedido() #6 '.htmlspecialchars($sqlipa->error));

                    }
                }else{ $this->registrar(6, $id_loc, $id_gir, 'enviar_pedido() #6 '.htmlspecialchars($sqlipa->error)); }
            }else{ $this->registrar(6, $id_loc, $id_gir, 'enviar_pedido() #6 '.htmlspecialchars($this->con->error)); }
            
        }
        return $info;
    }
    private function verificar(){

        $return['op'] = false;
        $host = $_POST["host"];
        if($sqlgir = $this->con->prepare("SELECT t2.ip, t2.code, t1.id_gir FROM giros t1, server t2 WHERE t1.dominio=? AND t1.id_ser=t2.id_ser AND t1.eliminado=?")){
            if($sqlgir->bind_param("si", $host, $this->eliminado)){
                if($sqlgir->execute()){
                    $res = $sqlgir->get_result();
                    if($res->{'num_rows'} == 1){
                        $result = $res->fetch_all(MYSQLI_ASSOC)[0];
                        $ip = $this->getUserIpAddr();
                        if($ip == $result["ip"]){
                            if($_SERVER['SERVER_PORT'] == "443"){
                                $code = substr($_POST["code"], 0, 40);
                                if($code == $result["code"]){
                                    $return['op'] = true;
                                    $return['id_gir'] = $result["id_gir"];
                                }else{ $this->registrar(15, 0, 0, 'verificar() codigo no encontrado'); }
                            }else{ $this->registrar(15, 0, 0, 'verificar() puerto distinto a 443'); }
                        }else{ $this->registrar(15, 0, 0, 'verificar() ip distinta'); }
                    }else{ $this->registrar(15, 0, 0, 'verificar() host no encontrada'); }
                    $sqlgir->free_result();
                    $sqlgir->close();
                }else{ $this->registrar(6, 0, 0, 'verificar() '.$sqlgir->error); }
            }else{ $this->registrar(6, 0, 0, 'verificar() '.$sqlgir->error); }
        }else{ $this->registrar(6, 0, 0, 'verificar() '.$this->con->error); }
        return $return;

    }
    private function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    public function is_giro(){
        if(isset($_GET["id_gir"]) && $_GET["id_gir"] > 0 && $this->id_gir != $_GET["id_gir"]){
            $id_gir = $_GET["id_gir"];
            if($this->admin == 0){
                if($sql = $this->con->prepare("SELECT * FROM fw_usuarios_giros WHERE id_gir=? AND id_user=?")){
                    if($sql->bind_param("ii", $id_gir, $this->id_user)){
                        if($sql->execute()){
                            $res = $sql->get_result();
                            if($res->{"num_rows"} == 1){
                                $this->id_gir = $id_gir;
                                $_SESSION['user']['id_gir'] = $id_gir;
                            }else{
                                $this->registrar(7, 0, $id_gir, 'is_giro() #1 XSS');
                                die("ERROR");
                            }
                            $sql->free_result();
                            $sql->close();
                        }else{ $this->registrar(6, 0, $id_gir, 'is_giro() #1 '.$sql->error); }
                    }else{ $this->registrar(6, 0, $id_gir, 'is_giro() #1 '.$sql->error); }
                }else{ $this->registrar(6, 0, $id_gir, 'is_giro() #1 '.$this->con->error); }
            }
            if($this->admin == 1 && $this->id_user > 1){
                if($sql = $this->con->prepare("SELECT * FROM fw_usuarios_giros_clientes WHERE id_gir=? AND id_user=?")){
                    if($sql->bind_param("ii", $id_gir, $id_user)){
                        if($sql->execute()){
                            $res = $sql->get_result();
                            if($res->{"num_rows"} == 1){
                                $this->id_gir = $id_gir;
                                $_SESSION['user']['id_gir'] = $id_gir;
                            }else{
                                $this->registrar(7, 0, $id_gir, 'is_giro() #2 XSS');
                                die("ERROR");
                            }
                            $sql->free_result();
                            $sql->close();
                        }else{ $this->registrar(6, 0, $id_gir, 'is_giro() #2 '.$sql->error); }
                    }else{ $this->registrar(6, 0, $id_gir, 'is_giro() #2 '.$sql->error); }
                }else{ $this->registrar(6, 0, $id_gir, 'is_giro() #2 '.$this->con->error); }
            }
            if($this->admin == 1 && $this->id_user == 1){
                $this->id_gir = $id_gir;
                $_SESSION['user']['id_gir'] = $id_gir;
            }
        }
    }
    public function is_catalogo(){
        if($_GET["id_cat"] > 0 && $this->id_gcat != $_GET["id_cat"]){
            $id_cat = $_GET["id_cat"];
            if($this->admin == 0){
                if($sql = $this->con->prepare("SELECT * FROM fw_usuarios_giros t1, catalogo_productos t2 WHERE t2.id_cat=? AND t2.id_gir=t1.id_gir AND t1.id_user=? AND t2.eliminado=?")){
                    if($sql->bind_param("iii", $id_cat, $this->id_user, $this->eliminado)){
                        if($sql->execute()){
                            $res = $sql->get_result();
                            if($res->{"num_rows"} == 1){
                                $this->id_cat = $id_cat;
                                $_SESSION['user']['id_cat'] = $id_cat;
                            }else{
                                $this->registrar(7, 0, 0, 'is_catalogo() #1 XSS');
                                die("ERROR");
                            }
                            $sql->free_result();
                            $sql->close();
                        }else{ $this->registrar(6, 0, $this->id_gir, 'is_catalogo() #1 '.$sql->error); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'is_catalogo() #1 '.$sql->error); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'is_catalogo() #1 '.$this->con->error); }
            }
            if($this->admin == 1){
                if($sql = $this->con->prepare("SELECT * FROM fw_usuarios_giros_clientes t1, catalogo_productos t2 WHERE t2.id_cat=? AND t2.id_gir=t1.id_gir AND t1.id_user=? AND t2.eliminado=?")){
                    if($sql->bind_param("iii", $id_cat, $this->id_user, $this->eliminado)){
                        if($sql->execute()){
                            $res = $sql->get_result();
                            if($res->{"num_rows"} == 1 || $this->id_user == 1){
                                $this->id_cat = $id_cat;
                                $_SESSION['user']['id_cat'] = $id_cat;
                            }else{
                                $this->registrar(7, 0, 0, 'is_catalogo() #2 XSS');
                                die("ERROR");
                            }
                            $sql->free_result();
                            $sql->close();
                        }else{ $this->registrar(6, 0, $this->id_gir, 'is_catalogo() #2 '.$sql->error); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'is_catalogo() #2 '.$sql->error); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'is_catalogo() #2 '.$this->con->error); }
            }
        }
    }
    public function process_categorias($cats, $id){
        $res = [];
        for($i=0; $i<count($cats); $i++){
            if(count($res) == 0){
                $res[] = $cats[$i];
            }else{
                $repeat = false;
                for($j=0; $j<count($res); $j++){
                    if($res[$j][$id] == $cats[$i][$id]){
                        $repeat = true;
                    }
                }
                if(!$repeat){ $res[] = $cats[$i]; }
            }
        }
        return $res;
    }
    public function get_categorias(){
        if($sql = $this->con->prepare("SELECT DISTINCT t1.id_cae, t1.nombre, t1.parent_id, t2.id_pro, t1.tipo FROM categorias t1 LEFT JOIN cat_pros t2 ON t1.id_cae=t2.id_cae WHERE t1.id_cat=? AND t1.eliminado=? ORDER BY t1.orders")){
            if($sql->bind_param("ii", $this->id_cat, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $this->process_categorias($result, 'id_cae');
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_categorias() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_categorias() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_categorias() '.htmlspecialchars($this->con->error)); }
    }
    public function get_locales(){
        if($sql = $this->con->prepare("SELECT id_loc, nombre, code FROM locales WHERE id_gir=? AND eliminado=?")){
            if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_locales() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_locales() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_locales() '.htmlspecialchars($this->con->error)); }
    }
    public function get_horarios($id_loc){
        if($sql = $this->con->prepare("SELECT * FROM horarios WHERE id_loc=? AND id_gir=? AND eliminado=?")){
            if($sql->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_horarios() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_horarios() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_horarios() '.htmlspecialchars($this->con->error)); }
    }
    public function get_horario($id_loc, $id_hor){
        if($sql = $this->con->prepare("SELECT * FROM horarios WHERE id_hor=? AND id_loc=? AND id_gir=? AND eliminado=?")){
            if($sql->bind_param("iiii", $id_hor, $id_loc, $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_horario() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_horario() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_horario() '.htmlspecialchars($this->con->error)); }
    }
    public function get_correos_no_ses(){
        if($sql = $this->con->prepare("SELECT id_loc, correo FROM locales WHERE correo_ses='0' AND eliminado=?")){
            if($sql->bind_param("i", $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, 0, 'get_correos_no_ses() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'get_correos_no_ses() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, 0, 'get_correos_no_ses() '.htmlspecialchars($this->con->error)); }
    }
    public function get_ssl_sol(){
        if($sql = $this->con->prepare("SELECT id_gir, dominio FROM giros WHERE solicitar_ssl='1' AND eliminado=?")){
            if($sql->bind_param("i", $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, 0, 'get_ssl_sol() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'get_ssl_sol() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, 0, 'get_ssl_sol() '.htmlspecialchars($this->con->error)); }
    }
    public function get_user_local($id_user, $id_loc){
        if($sql = $this->con->prepare("SELECT id_user, tipo, save_web, web_min, save_pos, pos_min FROM fw_usuarios WHERE id_user=? AND id_loc=? AND id_gir=? AND eliminado=?")){
            if($sql->bind_param("iiii", $id_user, $id_loc, $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'get_user_local() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'get_user_local() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'get_user_local() '.htmlspecialchars($this->con->error)); }
    }
    public function get_local($id_loc){
        if($sql = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND id_gir=? AND eliminado=?")){
            if($sql->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'get_local() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'get_local() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'get_local() '.htmlspecialchars($this->con->error)); }
    }
    public function get_giros_user(){
        if($this->admin == 1 && $this->id_user > 1){
            if($sql = $this->con->prepare("SELECT t2.id_gir, t2.nombre, t2.dominio FROM fw_usuarios_giros_clientes t1, giros t2 WHERE t1.id_user=? AND t1.id_gir=t2.id_gir AND t2.eliminado=? ORDER BY dns_letra")){
                if($sql->bind_param("ii", $this->id_user, $this->eliminado)){
                    if($sql->execute()){
                        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                        $sql->free_result();
                        $sql->close();
                        return $result;
                    }else{ $this->registrar(6, 0, 0, 'get_giros_user() #1 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, 0, 'get_giros_user() #1 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'get_giros_user() #1 '.htmlspecialchars($this->con->error)); }
        }
        if($this->admin == 1 && $this->id_user == 1){
            if($sql = $this->con->prepare("SELECT id_gir, nombre, dominio, dns_letra FROM giros WHERE eliminado=? ORDER BY dns_letra")){
                if($sql->bind_param("i", $this->eliminado)){
                    if($sql->execute()){
                        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                        $sql->free_result();
                        $sql->close();
                        return $result;
                    }else{ $this->registrar(6, 0, 0, 'get_giros_user() #2 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, 0, 'get_giros_user() #2 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'get_giros_user() #2 '.htmlspecialchars($this->con->error)); }
        }
    }
    public function get_pagos_giros($id_gir){

        if($sql = $this->con->prepare("SELECT dominio, monto FROM giros WHERE t1.id_gir=? AND t1.eliminado=?")){
            if($sql->bind_param("ii", $id_gir, $this->eliminado)){
                if($sql->execute()){

                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $res['dominio'] = $result['dominio'];
                    $res['monto'] = $result['monto'];
                    $sql->free_result();
                    $sql->close();

                    if($sqlx = $this->con->prepare("SELECT id_pago FROM pagos WHERE id_gir=?")){
                        if($sqlx->bind_param("ii", $id_gir, $this->eliminado)){
                            if($sqlx->execute()){
            
                                $resultx = $sqlx->get_result()->fetch_all(MYSQLI_ASSOC);
                                $res['pagos'] = $resultx;
                                $sqlx->free_result();
                                $sqlx->close();
                                return $res;
            
                            }else{ $this->registrar(6, 0, $this->id_gir, 'get_giro() '.htmlspecialchars($sqlx->error)); }
                        }else{ $this->registrar(6, 0, $this->id_gir, 'get_giro() '.htmlspecialchars($sqlx->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'get_giro() '.htmlspecialchars($this->con->error)); }

                }else{ $this->registrar(6, 0, $this->id_gir, 'get_giro() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_giro() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_giro() '.htmlspecialchars($this->con->error)); }
    

    }
    public function get_giros_pagos(){

        if($sql = $this->con->prepare("SELECT t1.id_gir, t1.dominio, t2.meses, t1.fecha_dns, t1.monto, t2.id_pago FROM giros t1 LEFT JOIN pagos t2 ON t1.id_gir=t2.id_gir WHERE t1.eliminado=?")){
            if($sql->bind_param("i", $this->eliminado)){
                if($sql->execute()){

                    $result = $sql->get_result();
                    while($row = $result->fetch_assoc()){

                        $res[$row['id_gir']]['fecha_dns'] = $row['fecha_dns'];
                        $res[$row['id_gir']]['monto'] = $row['monto'];
                        $res[$row['id_gir']]['dominio'] = $row['dominio'];

                        if($row['id_pago'] == ""){
                            $res[$row['id_gir']]['cpagos'] = 0;
                        }else{
                            $res[$row['id_gir']]['cpagos'] = $res[$row['id_gir']]['cpagos'] + $row['meses'];
                        }

                    }
                    $sql->free_result();
                    $sql->close();
                    return $res;

                }else{ $this->registrar(6, 0, $this->id_gir, 'get_giro() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_giro() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_giro() '.htmlspecialchars($this->con->error)); }
    
    }
    public function get_pagos(){

        if($sql = $this->con->prepare("SELECT t2.meses, t1.fecha_dns, t1.monto, t2.id_pago FROM giros t1 LEFT JOIN pagos t2 ON t1.id_gir=t2.id_gir WHERE t1.id_gir=? AND t1.eliminado=?")){
            if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                if($sql->execute()){

                    $result = $sql->get_result();
                    while($row = $result->fetch_assoc()){
                        $res['fecha_dns'] = $row['fecha_dns'];
                        $res['monto'] = $row['monto'];
                        if($row['id_pago'] == ""){
                            $res['cpagos'] = 0;
                        }else{
                            $res['cpagos'] = $res['cpagos'] + $row['meses'];
                        }
                    }
                    $sql->free_result();
                    $sql->close();
                    return $res;

                }else{ $this->registrar(6, 0, $this->id_gir, 'get_giro() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_giro() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_giro() '.htmlspecialchars($this->con->error)); }
    
    }
    public function get_giro(){
        if($sql = $this->con->prepare("SELECT t1.dns, t1.dominio, t1.catalogo, t1.nombre, t1.dns_letra, t1.ssl, t2.ip, t1.titulo, t1.retiro_local, t1.despacho_domicilio, t1.alto, t1.alto_pro, t1.pedido_minimo, t1.tiempo_aviso, t1.pedido_01_titulo, t1.pedido_01_subtitulo, t1.pedido_02_titulo, t1.pedido_02_subtitulo, t1.pedido_03_titulo, t1.pedido_03_subtitulo, t1.pedido_04_titulo, t1.pedido_04_subtitulo, t1.mapcode, t1.pedido_gengibre, t1.pedido_wasabi, t1.pedido_soya, t1.pedido_teriyaki, t1.pedido_palitos, t1.pedido_comentarios, t1.estado, t1.font_family, t1.font_css, t1.style_page, t1.style_color, t1.style_modal FROM giros t1, server t2 WHERE t1.id_gir=? AND t1.id_ser=t2.id_ser AND t1.eliminado=?")){
            if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_giro() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_giro() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_giro() '.htmlspecialchars($this->con->error)); }
    }
    public function get_giro_id($id_gir){
        if($this->admin == 1){
            if($sql = $this->con->prepare("SELECT * FROM giros WHERE id_gir=? AND eliminado=?")){
                if($sql->bind_param("ii", $id_gir, $this->eliminado)){
                    if($sql->execute()){
                        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                        $sql->free_result();
                        $sql->close();
                        return $result;
                    }else{ $this->registrar(6, 0, $id_gir, 'get_giro_id() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $id_gir, 'get_giro_id() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $id_gir, 'get_giro_id() '.htmlspecialchars($this->con->error)); }
        }
    }
    public function set_giro_dns(){
        if($sql = $this->con->prepare("UPDATE giros SET dns='1', fecha_dns=now() WHERE id_gir=? AND eliminado=?")){
            if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $sql->close();
                }else{ $this->registrar(6, 0, $this->id_gir, 'set_giro_dns() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'set_giro_dns() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'set_giro_dns() '.htmlspecialchars($this->con->error)); }
    }
    public function get_catalogos(){
        if($sql = $this->con->prepare("SELECT id_cat, nombre FROM catalogo_productos WHERE id_gir=? AND eliminado=?")){
            if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_catalogos() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_catalogos() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_catalogos() '.htmlspecialchars($this->con->error)); }
    }
    public function get_categoria($id_cae){
        if($sql = $this->con->prepare("SELECT id_cae, nombre, descripcion, descripcion_sub, precio, tipo, ocultar, mostrar_prods, detalle_prods, degradado, parent_id FROM categorias WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?")){
            if($sql->bind_param("iiii", $id_cae, $this->id_cat, $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_categoria() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_categoria() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_categoria() '.htmlspecialchars($this->con->error)); }
    }
    public function get_producto($id_pro){
        if($sql = $this->con->prepare("SELECT * FROM productos t1, productos_precio t2 WHERE t1.id_pro=? AND t1.id_pro=t2.id_pro AND t2.id_cat=? AND t1.eliminado=?")){
            if($sql->bind_param("iii", $id_pro, $this->id_cat, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_producto() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_producto() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_producto() '.htmlspecialchars($this->con->error)); }
    }
    public function get_productos(){
        if($sql = $this->con->prepare("SELECT * FROM productos WHERE id_gir=? AND eliminado=?")){
            if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_productos() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_productos() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_productos() '.htmlspecialchars($this->con->error)); }
    }
    public function get_preguntas(){
        if($sql = $this->con->prepare("SELECT id_pre, nombre, mostrar FROM preguntas WHERE id_cat=? AND id_gir=? AND eliminado=?")){
            if($sql->bind_param("iii", $this->id_cat, $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_preguntas() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_preguntas() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_preguntas() '.htmlspecialchars($this->con->error)); }
    }
    public function get_css(){
        $id_gir = 0;
        if($sql = $this->con->prepare("SELECT * FROM css WHERE id_gir=? OR id_gir=?")){
            if($sql->bind_param("ii", $id_gir, $this->id_gir)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_css() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_css() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_css() '.htmlspecialchars($this->con->error)); }
    }
    public function get_alto(){
        if($sql = $this->con->prepare("SELECT alto FROM giros WHERE id_gir=? AND eliminado=?")){
            if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]["alto"];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_alto() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_alto() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_alto() '.htmlspecialchars($this->con->error)); }
    }
    public function get_usuarios_admin(){
        if($this->id_gir != 0){
            if($sql = $this->con->prepare("SELECT t1.id_user, t1.nombre FROM fw_usuarios t1, fw_usuarios_giros t2 WHERE t2.id_gir=? AND t2.id_user=t1.id_user AND t1.eliminado=?")){
                if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                    if($sql->execute()){
                        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                        $sql->free_result();
                        $sql->close();
                        return $result;
                    }else{ $this->registrar(6, 0, $this->id_gir, 'get_usuarios_admin() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_usuarios_admin() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_usuarios_admin() '.htmlspecialchars($this->con->error)); }
        }
    }
    public function get_pregunta($id_pre){
        if($sql = $this->con->prepare("SELECT * FROM preguntas WHERE id_pre=? AND id_cat=? AND eliminado=?")){
            if($sql->bind_param("iii", $id_pre, $this->id_cat, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_usuarios_admin() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_usuarios_admin() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_usuarios_admin() '.htmlspecialchars($this->con->error)); }
    }
    public function get_pregunta_valores($id_pre){
        if($sql = $this->con->prepare("SELECT * FROM preguntas_valores WHERE id_pre=?")){
            if($sql->bind_param("i", $id_pre)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_pregunta_valores() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_pregunta_valores() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_pregunta_valores() '.htmlspecialchars($this->con->error)); }
    }
    public function get_preguntas_pro($id_pro){
        if($sql = $this->con->prepare("SELECT id_pre FROM preguntas_productos t1, productos t2 WHERE t2.id_pro=? AND t2.id_pro=t1.id_pro AND t2.id_gir=? AND t2.eliminado=?")){
            if($sql->bind_param("iii", $id_pro, $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_preguntas_pro() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_preguntas_pro() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_preguntas_pro() '.htmlspecialchars($this->con->error)); }
    }
    public function get_categoria_2($id_cae){
        if($sql = $this->con->prepare("SELECT nombre FROM categorias WHERE id_cae=? AND eliminado=?")){
            if($sql->bind_param("ii", $id_cae, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_categoria_2() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_categoria_2() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_categoria_2() '.htmlspecialchars($this->con->error)); } 
    }
    public function get_productos_categoria($id_cae){
        if($sql = $this->con->prepare("SELECT * FROM productos t1, cat_pros t2 WHERE t2.id_cae=? AND t2.id_pro=t1.id_pro AND t1.eliminado=? ORDER BY t2.orders")){
            if($sql->bind_param("ii", $id_cae, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_productos_categoria() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_productos_categoria() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_productos_categoria() '.htmlspecialchars($this->con->error)); } 
    }
    public function get_paginas(){
        if($sql = $this->con->prepare("SELECT * FROM paginas WHERE id_gir=? AND eliminado=? ORDER BY orders")){
            if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_paginas() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_paginas() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_paginas() '.htmlspecialchars($this->con->error)); } 
    }
    public function get_repartidores_giro(){
        if($sql = $this->con->prepare("SELECT id_mot, nombre FROM motos WHERE id_gir=? AND eliminado=?")){
            if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_repartidores_giro() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_repartidores_giro() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_repartidores_giro() '.htmlspecialchars($this->con->error)); } 
    }
    public function get_repartidores($id_loc){
        if($sql = $this->con->prepare("SELECT t1.id_mot, t1.nombre FROM motos t1, motos_locales t2, locales t3 WHERE t3.id_gir=? AND t3.id_loc=? AND t3.id_loc=t2.id_loc AND t2.id_mot=t1.id_mot AND t1.eliminado=?")){
            if($sql->bind_param("iii", $this->id_gir, $id_loc, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_repartidores() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_repartidores() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_repartidores() '.htmlspecialchars($this->con->error)); } 
    }
    public function get_promocion($id_cae){
        if($sqlpre = $this->con->prepare("SELECT precio FROM categorias WHERE id_cae=? AND eliminado=?")){
            if($sqlpre->bind_param("ii", $id_cae, $this->eliminado)){
                if($sqlpre->execute()){
                    $aux['precio'] = $sqlpre->get_result()->fetch_all(MYSQLI_ASSOC)[0]["precio"];
                    if($sqlcat = $this->con->prepare("SELECT id_cae2 as id_cae, cantidad FROM promocion_categoria WHERE id_cae1=?")){
                        if($sqlcat->bind_param("i", $id_cae)){
                            if($sqlcat->execute()){
                                $aux['categorias'] = $sqlcat->get_result()->fetch_all(MYSQLI_ASSOC);
                                if($sqlpro = $this->con->prepare("SELECT id_pro, cantidad, parent_id FROM promocion_productos WHERE id_cae=?")){
                                    if($sqlpro->bind_param("i", $id_cae)){
                                        if($sqlpro->execute()){
                                            $aux['productos'] = $sqlpro->get_result()->fetch_all(MYSQLI_ASSOC);
                                            $sqlpro->free_result();
                                            $sqlpro->close();
                                            return $aux;
                                        }else{ $this->registrar(6, 0, $this->id_gir, 'get_promocion() #1 '.htmlspecialchars($sqlpro->error)); }
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'get_promocion() #1 '.htmlspecialchars($sqlpro->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'get_promocion() #1 '.htmlspecialchars($this->con->error)); }
                                $sqlcat->free_result();
                                $sqlcat->close();
                            }else{ $this->registrar(6, 0, $this->id_gir, 'get_promocion() #2 '.htmlspecialchars($sqlcat->error)); }
                        }else{ $this->registrar(6, 0, $this->id_gir, 'get_promocion() #2 '.htmlspecialchars($sqlcat->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'get_promocion() #2 '.htmlspecialchars($this->con->error)); }
                    $sqlpre->free_result();
                    $sqlpre->close();
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_promocion() #3 '.htmlspecialchars($sqlpre->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_promocion() #3 '.htmlspecialchars($sqlpre->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_promocion() #3 '.htmlspecialchars($this->con->error)); }
    }
    public function get_arbol_productos($that){

        if($sql = $this->con->prepare("SELECT t1.id_cae, t1.nombre as cat_nombre, t1.parent_id, t2.id_pro, t3.nombre as prod_nombre FROM categorias t1 LEFT JOIN cat_pros t2 ON t1.id_cae=t2.id_cae LEFT JOIN productos t3 ON t2.id_pro=t3.id_pro WHERE t1.id_cat=? AND t1.eliminado=? AND t1.tipo='0'")){
            if($sql->bind_param("ii", $this->id_cat, $this->eliminado)){
                if($sql->execute()){
                    $aux = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $this->process_arbol_draw($aux, 0, $that);
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_arbol_productos() #1a '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_arbol_productos() #1b '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_arbol_productos() #1c '.htmlspecialchars($this->con->error)); } 
        
    }
    public function process_arbol_draw($cats, $parent_id, $that){

        $in = [];
        $div = "<div class='parent_arbol'>";
        for($i=0; $i<count($cats); $i++){
            $cat = $cats[$i];
            if($cat['parent_id'] == $parent_id && !in_array($cat['id_cae'], $in)){
                $cantidad = 0;
                $child_display = 'block';
                for($x=0; $x<count($that['categorias']); $x++){
                    if($cat['id_cae'] == $that['categorias'][$x]['id_cae']){
                        $cantidad = $that['categorias'][$x]['cantidad'];
                        $child_display = ($cantidad > 0) ? 'none' : 'block'; 
                    }
                }
                $aux['id_cae'] = $cat['id_cae'];
                $aux['nombre'] = $cat['cat_nombre'];
                $div .= "<div class='bottom_arbol'><div class='clearfix'><div class='cantidad_arbol'>".$this->get_select("sel-cae-".$aux['id_cae'], 1000, $cantidad)."</div><div class='nombre_arbol' style='font-size: 16px'>".$aux['nombre']."</div></div>";
                if($cat['id_pro'] !== null){
                    $prods = $this->process_productos_draw($cats, $cat['id_cae'], $that);
                    if(count($prods) > 0){ 
                        $div .= "<div class='left_arbol' style='display: ".$child_display."'>".$prods."</div>";
                    }
                }else{
                    $childs = $this->process_arbol_draw($cats, $cat['id_cae'], $that);
                    if(count($childs) > 0){ 
                        $div .= "<div class='left_arbol' style='display: ".$child_display."'>".$childs."</div>";
                    }
                }
                $div .= "</div>";
                $in[] = $aux['id_cae'];
            }
        }
        $div .= "</div>";
        return $div;

    }
    public function process_productos_draw($cats, $id_cae, $that){

        $div = "<div style='parent_arbol'>";
        for($i=0; $i<count($cats); $i++){
            $cat = $cats[$i];
            if($cat['id_cae'] == $id_cae && $cat['id_pro'] !== null){
                $cantidad = 0;
                for($x=0; $x<count($that['productos']); $x++){
                    if($that['productos'][$x]['id_pro'] == $cat['id_pro'] && $that['productos'][$x]['parent_id'] == $cat['id_cae']){
                        $cantidad = $that['productos'][$x]['cantidad'];
                    }
                }
                $div .= "<div class='clearfix'><div class='cantidad_arbol'>".$this->get_select("sel-pro-".$cat['id_pro']."-".$cat['id_cae'], 100, $cantidad)."</div><div class='nombre_arbol'>".$cat['prod_nombre']."</div></div>";
            }
        }
        $div .= "</div>";
        return $div;

    }
    public function get_select($id, $cantidad, $selected){
        $select = "<select id='".$id."' class='select_arbol'>";
        for($i=0; $i<$cantidad; $i++){ if($i == $selected){ $select .="<option value='".$i."' selected>".$i."</option>"; }else{ $select .="<option value='".$i."'>".$i."</option>"; } }
        $select .="</select>";
        return $select;
    }
    public function get_pagina($id_pag){
        if($sql = $this->con->prepare("SELECT * FROM paginas WHERE id_pag=? AND id_gir=? AND eliminado=?")){
            if($sql->bind_param("iii", $id_pag, $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_pagina() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_pagina() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_pagina() '.htmlspecialchars($this->con->error)); } 
    }
    public function get_pag_inicio(){
        if($sql = $this->con->prepare("SELECT ver_inicio, inicio_html FROM giros WHERE id_gir=? AND eliminado=?")){
            if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_pag_inicio() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_pag_inicio() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_pag_inicio() '.htmlspecialchars($this->con->error)); } 
    }
    public function get_footer(){
        if($sql = $this->con->prepare("SELECT footer_html FROM giros WHERE id_gir=? AND eliminado=?")){
            if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]["footer_html"];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_footer() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_footer() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_footer() '.htmlspecialchars($this->con->error)); }
    }
    public function get_repartidor($id_mot){
        if($sql = $this->con->prepare("SELECT t1.id_mot, t1.nombre, t1.correo, t1.uid FROM motos t1, motos_locales t2, locales t3 WHERE t1.id_mot=? AND t3.id_gir=? AND t3.id_loc=t2.id_loc AND t2.id_mot=t1.id_mot")){
            if($sql->bind_param("ii", $id_mot, $this->id_gir)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_repartidor() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_repartidor() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_repartidor() '.htmlspecialchars($this->con->error)); }
    }
    public function inicio(){
        if($sql = $this->con->prepare("SELECT id_user, nombre, correo, re_venta, admin, id_aux_user FROM fw_usuarios WHERE id_user=? AND eliminado=?")){
            if($sql->bind_param("ii", $this->id_user, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ die('ERROR'); $this->registrar(6, 0, 0, 'inicio() '.htmlspecialchars($sql->error)); }
            }else{ die('ERROR'); $this->registrar(6, 0, 0, 'inicio() '.htmlspecialchars($sql->error)); }
        }else{ die('ERROR'); $this->registrar(6, 0, 0, 'inicio() '.htmlspecialchars($this->con->error)); }
    }
    public function get_cocina($id_ped){
        if($sql = $this->con->prepare("SELECT id_ped, num_ped, carro, promos FROM pedidos_aux WHERE id_ped=? AND eliminado=?")){
            if($sql->bind_param("ii", $id_ped, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, 0, 'get_cocina() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'get_cocina() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, 0, 'get_cocina() '.htmlspecialchars($this->con->error)); }
    }
    public function get_usuarios(){
        if($this->id_user == 1){
            if($sql = $this->con->prepare("SELECT id_user, nombre FROM fw_usuarios WHERE id_user<>? AND admin='1' AND eliminado=?")){
                if($sql->bind_param("ii", $this->id_user, $this->eliminado)){
                    if($sql->execute()){
                        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                        $sql->free_result();
                        $sql->close();
                        return $result;
                    }else{ $this->registrar(6, 0, 0, 'get_usuarios() #1 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, 0, 'get_usuarios() #1 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'get_usuarios() #1 '.htmlspecialchars($this->con->error)); }
        }else{
            if($this->re_venta == 1){
                if($sql = $this->con->prepare("SELECT id_user, nombre FROM fw_usuarios WHERE id_aux_user=? AND re_venta='0' AND eliminado=?")){
                    if($sql->bind_param("ii", $this->id_user, $this->eliminado)){
                        if($sql->execute()){
                            $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                            $sql->free_result();
                            $sql->close();
                            return $result;
                        }else{ $this->registrar(6, 0, 0, 'get_usuarios() #2 '.htmlspecialchars($sql->error)); }
                    }else{ $this->registrar(6, 0, 0, 'get_usuarios() #2 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, 0, 'get_usuarios() #2 '.htmlspecialchars($this->con->error)); }
            }
        }
    }
    public function get_usuarios_local($id_loc){
        if($sql = $this->con->prepare("SELECT id_user, nombre, tipo FROM fw_usuarios WHERE id_loc=? AND id_gir=? AND admin='0' AND eliminado=?")){
            if($sql->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'get_usuarios_local() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'get_usuarios_local() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'get_usuarios_local() '.htmlspecialchars($this->con->error)); }
    }
    public function get_usuario($id_user){
        if($sql = $this->con->prepare("SELECT id_user, nombre, correo, id_aux_user, save_web, web_min, save_pos, pos_min, del_pdir, tipo FROM fw_usuarios WHERE id_user=? AND eliminado=?")){
            if($sql->bind_param("ii", $id_user, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, 0, 'get_usuario() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'get_usuario() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, 0, 'get_usuario() '.htmlspecialchars($this->con->error)); }
    }
    public function get_local_tramos($id_loc){
        if($sql = $this->con->prepare("SELECT * FROM locales_tramos WHERE id_loc=? AND eliminado=?")){
            if($sql->bind_param("ii", $id_loc, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'get_local_tramos() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'get_local_tramos() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'get_local_tramos() '.htmlspecialchars($this->con->error)); }
    }
    public function get_local_tramo($id_lot, $id_loc){
        if($sql = $this->con->prepare("SELECT * FROM locales_tramos WHERE id_lot=? AND id_loc=? AND eliminado=?")){
            if($sql->bind_param("iii", $id_lot, $id_loc, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'get_local_tramo() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'get_local_tramo() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'get_local_tramo() '.htmlspecialchars($this->con->error)); }
    }
    public function get_data($id_gir){
        if($sql = $this->con->prepare("SELECT * FROM giros WHERE id_gir=?")){
            if($sql->bind_param("i", $id_gir)){
                if($sql->execute()){
                    $res = $sql->get_result();
                    //$info['favicon'] = "misitiodelivery.ico";
                    if($res->{"num_rows"} == 1){
                        $result = $res->fetch_all(MYSQLI_ASSOC)[0];
                        $info['ssl'] = $result['ssl'];
                        $info['dns'] = $result['dns'];
                        $info['id_gir'] = $result['id_gir'];            
                        $info['titulo'] = $result['titulo'];
                        $info['logo'] = $result['logo'];
                        $info['foto_retiro'] = $result['foto_retiro'];
                        $info['foto_despacho'] = $result['foto_despacho'];
                        $info['estado'] = explode(",",$result['estado']);
                        $info['mapcode'] = $result['mapcode'];
                        $info['dominio'] = "";
                        $info['url'] = $dominio;
                        if($result['favicon'] != ""){ $info['favicon'] = $result['favicon']; }
                        $info['font']['family'] = $result['font_family'];
                        $info['font']['css'] = $result['font_css'];
                        $info['code'] = $result['code'];
                        $info['footer_html'] = $result['footer_html'];
                        $info['inicio_html'] = $result['inicio_html'];
                        $info['retiro_local'] = $result['retiro_local'];
                        $info['despacho_domicilio'] = $result['despacho_domicilio'];
                        $info['lista_locales'] = $result['lista_locales'];
                        $info['con_cambios'] = $result['con_cambios'];
                        $info['desde'] = $result['desde'];
                        $info['path'] = ($info['ssl'] == 1 || $_SERVER["HTTP_HOST"] == "misitiodelivery.cl") ? "https://".$_SERVER["HTTP_HOST"] : "http://".$_SERVER["HTTP_HOST"] ;
                        $info['pedido_wasabi'] = $result['pedido_wasabi'];
                        $info['pedido_gengibre'] = $result['pedido_gengibre'];
                        $info['pedido_palitos'] = $result['pedido_palitos'];
                        $info['pedido_comentarios'] = $result['pedido_comentarios'];
                        $info['pedido_soya'] = $result['pedido_soya'];
                        $info['pedido_teriyaki'] = $result['pedido_teriyaki'];
                        $info['css_tipo'] = $result['style_page'];
                        $info['css_color'] = $result['style_color'];
                        $info['css_font_size'] = $result['style_modal'];
                        $info['js_data'] = $info["code"].".js";
                        $info['header_fixed'] = 1;
                        $info['footer_fixed'] = 0;
                        $info['pedido_01_titulo'] = $result['pedido_01_titulo'];
                        $info['pedido_01_subtitulo'] = $result['pedido_01_subtitulo'];
                        $info['pedido_02_titulo'] = $result['pedido_02_titulo'];
                        $info['pedido_02_subtitulo'] = $result['pedido_02_subtitulo'];
                        $info['pedido_03_titulo'] = $result['pedido_03_titulo'];
                        $info['pedido_03_subtitulo'] = $result['pedido_03_subtitulo'];
                        $info['pedido_04_titulo'] = $result['pedido_04_titulo'];
                        $info['pedido_04_subtitulo'] = $result['pedido_04_subtitulo'];
                        $info['item_pagina'] = $result['item_pagina'];
                        $info['ultima_actualizacion'] = $result['ultima_actualizacion'];
                        $info['ver_inicio'] = $result['ver_inicio'];
                    }else{
                        if($dominio == "misitiodelivery.cl" || $dominio == "www.misitiodelivery.cl"){
                            $info['path'] = "https://misitiodelivery.cl";
                        }
                    }
                    $sql->free_result();
                    $sql->close();
                    return $info;
                }else{ $this->registrar(6, 0, $id_gir, 'get_data() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $id_gir, 'get_data() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $id_gir, 'get_data() '.htmlspecialchars($this->con->error)); }        
    }
    public function is_pass($id_user, $code){
        if($sql = $this->con->prepare("SELECT correo FROM fw_usuarios WHERE id_user=? AND mailcode=?")){
            if($sql->bind_param("ii", $id_user, $code)){
                if($sql->execute()){
                    $res = $sql->get_result();
                    if($res->{"num_rows"} == 0){
                        $sql->free_result();
                        $sql->close();
                        header("Location: https://misitiodelivery.cl/admin/?paso=recuperar"); 
                    }
                    if($res->{"num_rows"} == 1){ 
                        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                        $sql->free_result();
                        $sql->close();
                        return $result['correo']; 
                    }
                }else{ $this->registrar(6, 0, 0, 'is_pass() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'is_pass() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, 0, 'is_pass() '.htmlspecialchars($this->con->error)); }
    }
    public function get_paginas_web($id_gir){
        $visible = 1;
        if($sqlpag = $this->con->prepare("SELECT id_pag, nombre, imagen, html, tipo FROM paginas WHERE id_gir=? AND eliminado=? AND visible=? ORDER BY orders")){
            if($sqlpag->bind_param("iii", $id_gir, $this->eliminado, $visible)){
                if($sqlpag->execute()){
                    $resultpag = $sqlpag->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sqlpag->free_result();
                    $sqlpag->close();
                    return $resultpag;
                }else{ $this->registrar(6, 0, $id_gir, 'get_paginas_web() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $id_gir, 'get_paginas_web() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $id_gir, 'get_paginas_web() '.htmlspecialchars($this->con->error)); }
    }
    public function get_locales_js($id_gir){
        $eliminado = 0;
        if($sql = $this->con->prepare("SELECT id_loc, nombre, direccion, lat, lng, telefono, whatsapp, image FROM locales WHERE id_gir=? AND eliminado=?")){
            if($sql->bind_param("ii", $id_gir, $eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result();
                    $sql->free_result();
                    $sql->close();
                    while($row = $result->fetch_assoc()){
                        $locales['id_loc'] = $row['id_loc'];
                        $locales['nombre'] = $row['nombre'];
                        $locales['direccion'] = $row['direccion'];
                        $locales['lat'] = $row['lat'];
                        $locales['lng'] = $row['lng'];
                        $locales['telefono'] = $row['telefono'];
                        $locales['whatsapp'] = $row['whatsapp'];
                        $locales['image'] = $row['image'];
                        if($sqlloc = $this->con->prepare("SELECT dia_ini, dia_fin, hora_ini, hora_fin, min_ini, min_fin, tipo FROM horarios WHERE id_loc=? AND id_gir=? AND eliminado=?")){
                            if($sqlloc->bind_param("iii", $row["id_loc"], $id_gir, $eliminado)){
                                if($sqlloc->execute()){
                                    $locales['horarios'] = $sqlloc->get_result()->fetch_all(MYSQLI_ASSOC);
                                    $sqlloc->close();
                                }else{ $this->registrar(6, 0, $id_gir, 'get_locales_js() #1 '.htmlspecialchars($sql->error)); }
                            }else{ $this->registrar(6, 0, $id_gir, 'get_locales_js() #1 '.htmlspecialchars($sql->error)); }
                        }else{ $this->registrar(6, 0, $id_gir, 'get_locales_js() #1 '.htmlspecialchars($this->con->error)); }
                        $loc[] = $locales;
                        unset($locales);
                        return $loc;
                    }
                }else{ $this->registrar(6, 0, $id_gir, 'get_locales_js() #2 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $id_gir, 'get_locales_js() #2 '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $id_gir, 'get_locales_js() #2 '.htmlspecialchars($this->con->error)); }
    }
    public function get_info_catalogo($id_cat){
        $aux_prods = [];
        if($sql = $this->con->prepare("SELECT * FROM categorias WHERE id_cat=? AND eliminado=? ORDER BY orders")){
            if($sql->bind_param("ii", $id_cat, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result();
                    while($row = $result->fetch_assoc()){
                        $aux_categoria['id_cae'] = $row['id_cae'];
                        $aux_categoria['parent_id'] = $row['parent_id'];
                        $aux_categoria['nombre'] = $row['nombre'];
                        $aux_categoria['ocultar'] = $row['ocultar'];
                        $aux_categoria['image'] = $row['image'];
                        $aux_categoria['mostrar_prods'] = $row['mostrar_prods'];
                        $aux_categoria['detalle_prods'] = $row['detalle_prods'];
                        $aux_categoria['descripcion'] = $row['descripcion'];
                        $aux_categoria['descripcion_sub'] = $row['descripcion_sub'];
                        $aux_categoria['precio'] = $row['precio'];
                        $aux_categoria['degradado'] = $row['degradado'];
                        $aux_categoria['tipo'] = $row['tipo'];
                        if($aux_categoria['tipo'] == 0){
                            $aux_categoria['tipo'] = 0;
                            if($sqlpro = $this->con->prepare("SELECT * FROM cat_pros t1, productos t2, productos_precio t3 WHERE t1.id_cae=? AND t1.id_pro=t2.id_pro AND t1.id_pro=t3.id_pro AND t3.id_cat=? ORDER BY t1.orders")){
                                if($sqlpro->bind_param("ii", $row['id_cae'], $id_cat)){
                                    if($sqlpro->execute()){
                                        $resultpro = $sqlpro->get_result();
                                        while($rowp = $resultpro->fetch_assoc()){
                                            $aux_categoria['productos'][] = $rowp['id_pro'];
                                            if(!in_array($rowp['id_pro'], $aux_prods)){
                                                $aux_productos['id_pro'] = $rowp['id_pro'];
                                                $aux_productos['nombre'] = $rowp['nombre'];
                                                $aux_productos['nombre_carro'] = $rowp['nombre_carro'];
                                                $aux_productos['numero'] = $rowp['numero'];
                                                $aux_productos['descripcion'] = $rowp['descripcion'];
                                                $aux_productos['precio'] = $rowp['precio'];
                                                $aux_productos['disponible'] = $rowp['disponible'];
                                                $aux_productos['tipo'] = $rowp['tipo'];
                                                $aux_productos['image'] = $rowp['image'];
                                                $aux_prods[] = $rowp['id_pro'];
                                                if($sqlppr = $this->con->prepare("SELECT * FROM preguntas_productos WHERE id_pro=?")){
                                                    if($sqlppr->bind_param("i", $rowp['id_pro'])){
                                                        if($sqlppr->execute()){
                                                            $resultppr = $sqlppr->get_result();
                                                            while($rowpr = $resultppr->fetch_assoc()){
                                                                $aux_productos['preguntas'][] = $rowpr['id_pre'];
                                                            }
                                                            $info['productos'][] = $aux_productos;
                                                            unset($aux_productos);
                                                            $sqlppr->free_result();
                                                            $sqlppr->close();
                                                        }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_catalogo() '.htmlspecialchars($sqlppr->error)); }
                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_catalogo() '.htmlspecialchars($sqlppr->error)); }
                                                }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_catalogo() '.htmlspecialchars($this->con->error)); }
                                            }
                                        }
                                        $sqlpro->free_result();
                                        $sqlpro->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_catalogo() '.htmlspecialchars($sqlpro->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_catalogo() '.htmlspecialchars($sqlpro->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_catalogo() '.htmlspecialchars($this->con->error)); }
                        }
                        if($aux_categoria['tipo'] == 1){
                            $aux_categoria['tipo'] = 1;
                            if($sqlpc = $this->con->prepare("SELECT id_cae2 as id_cae, cantidad FROM promocion_categoria WHERE id_cae1=?")){
                                if($sqlpc->bind_param("i", $row['id_cae'])){
                                    if($sqlpc->execute()){
                                        $resultpc = $sqlpc->get_result();
                                        while($rowpc = $resultpc->fetch_assoc()){
                                            $aux_prm_cat['id_cae'] = $rowpc['id_cae'];
                                            $aux_prm_cat['cantidad'] = $rowpc['cantidad'];
                                            $aux_categoria['categorias'][] = $aux_prm_cat;
                                            unset($aux_prm_cat);
                                        }
                                        $sqlpc->free_result();
                                        $sqlpc->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_catalogo() '.htmlspecialchars($sqlpc->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_catalogo() '.htmlspecialchars($sqlpc->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_catalogo() '.htmlspecialchars($this->con->error)); }
                            if($sqlpp = $this->con->prepare("SELECT id_pro, cantidad FROM promocion_productos WHERE id_cae=?")){
                                if($sqlpp->bind_param("i", $row['id_cae'])){
                                    if($sqlpp->execute()){
                                        $resultpp = $sqlpp->get_result();
                                        while($rowpp = $resultpp->fetch_assoc()){
                                            $aux_prm_pro['id_pro'] = $rowpp['id_pro'];
                                            $aux_prm_pro['cantidad'] = $rowpp['cantidad'];
                                            $aux_categoria['productos'][] = $aux_prm_pro;
                                            unset($aux_prm_pro);
                                        }
                                        $sqlpp->free_result();
                                        $sqlpp->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_catalogo() '.htmlspecialchars($sqlpp->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_catalogo() '.htmlspecialchars($sqlpp->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_catalogo() '.htmlspecialchars($this->con->error)); }
                        }
                        $info['categorias'][] = $aux_categoria;
                        unset($aux_categoria);
                    }
                    $sql->free_result();
                    $sql->close();
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_catalogo() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_catalogo() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_catalogo() '.htmlspecialchars($this->con->error)); }
        $info['preguntas'] = $this->get_info_preguntas($id_cat);        
        return $info;
    }
    public function get_info_preguntas($id_cat){
        if($sql = $this->con->prepare("SELECT * FROM preguntas WHERE id_cat=? AND eliminado=?")){
            if($sql->bind_param("ii", $id_cat, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result();
                    while($row = $result->fetch_assoc()){
                        $aux_pre['id_pre'] = $row['id_pre'];
                        $aux_pre['nombre'] = $row['mostrar'];
                        if($sqlpre = $this->con->prepare("SELECT * FROM preguntas_valores WHERE id_pre=?")){
                            if($sqlpre->bind_param("i", $row['id_pre'])){
                                if($sqlpre->execute()){
                                    $resultpre = $sqlpre->get_result();
                                    while($rowpre = $resultpre->fetch_assoc()){
                                        $aux_pre_val['cantidad'] = $rowpre['cantidad'];
                                        $aux_pre_val['nombre'] = $rowpre['nombre'];
                                        $aux_pre_val['valores'] = json_decode($rowpre['valores']);
                                        $aux_pre['valores'][] = $aux_pre_val;
                                    }
                                    $preguntas[] = $aux_pre;
                                    unset($aux_pre);
                                    $sqlpre->free_result();
                                    $sqlpre->close();
                                }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_preguntas() '.htmlspecialchars($sqlpre->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_preguntas() '.htmlspecialchars($sqlpre->error)); }
                        }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_preguntas() '.htmlspecialchars($this->con->error)); }
                    }
                    $sql->free_result();
                    $sql->close();
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_preguntas() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_preguntas() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_info_preguntas() '.htmlspecialchars($this->con->error)); }
        return $preguntas;
    }
    public function get_config($id_gir){
        if($sql = $this->con->prepare("SELECT retiro_local, despacho_domicilio, desde, pedido_minimo, alto, alto_pro, tiempo_aviso FROM giros WHERE id_gir=? AND eliminado=?")){
            if($sql->bind_param("ii", $id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $id_gir, 'get_config() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $id_gir, 'get_config() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $id_gir, 'get_config() '.htmlspecialchars($this->con->error)); }
    }
    public function cocina($ccn){
        if(!isset($ccn)){
            $http = "http://";
            if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on"){
                $http = "https://";
            }
            die("<meta http-equiv='refresh' content='0; url=".$http.$_SERVER["HTTP_HOST"]."/admin'>"); 
        }else{
            if($sql = $this->con->prepare("SELECT code FROM locales WHERE code=? AND eliminado=?")){
                if($sql->bind_param("ii", $ccn, $this->eliminado)){
                    if($sql->execute()){
                        $res = $sql->get_result();
                        if($res->{"num_rows"} == 0){
                            die("<meta http-equiv='refresh' content='0; url=".$http.$_SERVER["HTTP_HOST"]."/admin'>");
                        }
                        if($res->{"num_rows"} == 1){
                            return $ccn;
                        }
                        $sql->free_result();
                        $sql->close();
                    }else{ $this->registrar(6, 0, 0, 'cocina() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, 0, 'cocina() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'cocina() '.htmlspecialchars($this->con->error)); }
        }
    }
    public function cambiar_estado(){

        $info['op'] = 2;
        $verificar = $this->verificar_cookie();
        if($verificar['op']){
            $id_gir = $verificar['id_gir'];
            $id_loc = $verificar['id_loc'];
            $id_ped = $_POST['id_ped'];
            if($sql = $this->con->prepare("SELECT code FROM pedidos_aux WHERE id_ped=? AND id_loc=? AND id_gir=? AND eliminado=?")){
                if($sql->bind_param("iiii", $id_ped, $id_loc, $id_gir, $this->eliminado)){
                    if($sql->execute()){
                        $res = $sql->get_result();
                        if($res->{'num_rows'} == 1){

                            $code = $res->fetch_all(MYSQLI_ASSOC)[0]['code'];
                            $send['pedido_code'] = $code;

                            $tipo = $_POST['tipo'];
                            if($tipo == 0){
                                $aux['accion'] = 0;
                                $aux['estado'] = $_POST["estado"];
                                $send['estado'] = json_encode($aux);
                            }
                            if($tipo == 1){
                                $aux['accion'] = 1;
                                $aux['fecha'] = $_POST["fecha"];
                                $send['estado'] = json_encode($aux);
                            }
                            /*
                            if($tipo == 2){
                                $aux['accion'] = 2;
                                $aux['total'] = $_POST["total"];
                                $send['estado'] = json_encode($aux);
                            }
                            if($tipo == 3){
                                $aux['accion'] = 3;
                                $aux['mensaje'] = $_POST["mensaje"];
                                $send['estado'] = json_encode($aux);
                            }
                            if($tipo == 4){
                                $aux['accion'] = 4;
                                $aux['lat'] = $_POST["lat"];
                                $aux['lng'] = $_POST["lng"];
                                $send['estado'] = json_encode($aux);
                            }
                            */
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/cambiar_estado');
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                            if(!curl_errno($ch)){
                                curl_exec($ch);
                                $info['op'] = 1;
                                curl_close($ch);
                            }else{
                                $this->registrar(17, $id_loc, $id_gir, 'Error Curl cambiar_estado()');
                            }

                        }
                    }else{ $this->registrar(6, $id_loc, $id_gir, 'cambiar_estado() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, $id_loc, $id_gir, 'cambiar_estado() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, $id_loc, $id_gir, 'cambiar_estado() '.htmlspecialchars($this->con->error)); }
        }
        return $info;

    }
    public function get_data_pos(){

        $verificar = $this->verificar_cookie();
        if($verificar['op']){

            $id_loc = $verificar['id_loc'];
            $id_gir = $verificar['id_gir'];
            $info['id'] = $id_gir;
            $info['pedidos'] = $this->get_ultimos_pedidos_pos($id_loc);
            $info['motos'] = $this->get_repartidores_local($id_loc);
            $info['lat'] = $verificar['lat'];
            $info['lng'] = $verificar['lng'];
            $info['sonido'] = $verificar['sonido'];
            $info['t_retiro'] = $verificar['t_retiro'];
            $info['t_despacho'] = $verificar['t_despacho'];
            $info['pos'] = $verificar['pos'];

            if($sql = $this->con->prepare("SELECT t1.estado, t1.dominio, t1.ssl, t1.dns, t2.ip, t1.pedido_wasabi, t1.pedido_gengibre, t1.pedido_palitos, t1.pedido_soya, t1.pedido_teriyaki, t1.pedido_comentarios FROM giros t1, server t2 WHERE t1.id_gir=? AND t1.eliminado=? AND t1.id_ser=t2.id_ser")){
                if($sql->bind_param("ii", $id_gir, $this->eliminado)){
                    if($sql->execute()){
                        $res = $sql->get_result();
                        if($res->{'num_rows'} == 1){
                            $result = $res->fetch_all(MYSQLI_ASSOC)[0];
                            $info['estado'] = explode(",", $result['estado']);
                            $info['dominio'] = $result['dominio'];
                            $info['ssl'] = $result['ssl'];
                            $info['dns'] = $result['dns'];
                            $info['ip'] = $result['ip'];
                            $info['pedido_wasabi'] = $result['pedido_wasabi'];
                            $info['pedido_gengibre'] = $result['pedido_gengibre'];
                            $info['pedido_palitos'] = $result['pedido_palitos'];
                            $info['pedido_soya'] = $result['pedido_soya'];
                            $info['pedido_teriyaki'] = $result['pedido_teriyaki'];
                            $info['pedido_comentarios'] = $result['pedido_comentarios'];
                        }
                    }else{ $this->registrar(6, $id_loc, $id_gir, 'get_data_pos() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, $id_loc, $id_gir, 'get_data_pos() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, $id_loc, $id_gir, 'get_data_pos() '.htmlspecialchars($this->con->error)); }
            
        }else{
            die("<meta http-equiv='refresh' content='0; url=https://misitiodelivery.cl/admin'>");
        }
        return $info;    

    }
    public function get_repartidores_local($id_loc){
        if($sql = $this->con->prepare("SELECT t1.id_mot, t1.nombre FROM motos t1, motos_locales t2 WHERE t2.id_loc=? AND t2.id_mot=t1.id_mot AND t1.eliminado=?")){
            if($sql->bind_param("ii", $id_loc, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, $id_loc, 0, 'get_repartidores_local() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, $id_loc, 0, 'get_repartidores_local() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, $id_loc, 0, 'get_repartidores_local() '.htmlspecialchars($this->con->error)); }
    }
    public function del_pos_direcciones($id_pdir){

        $verificar = $this->verificar_cookie();
        if($verificar['op']){
            $id_gir = $verificar['id_gir'];
            $id_loc = $verificar['id_loc'];
            $del_pdir = $verificar['del_pdir'];
            if($sqldir = $this->con->prepare("SELECT * FROM pedidos_direccion WHERE id_pdir=?")){
                if($sqldir->bind_param("i", $id_pdir)){
                    if($sqldir->execute()){
                        $resdir = $sqldir->get_result();
                        $id_puser = $resdir->fetch_all(MYSQLI_ASSOC)[0]['id_puser'];
                        if($sqluser = $this->con->prepare("SELECT * FROM pedidos_usuarios WHERE id_puser=? AND id_gir=?")){
                            if($sqluser->bind_param("ii", $id_puser, $id_gir)){
                                if($sqluser->execute()){
                                    $resu = $sqluser->get_result();
                                    if($resu->{'num_rows'} == 1){
                                        if($del_pdir == 1){
                                            if($sqldpr = $this->con->prepare("DELETE FROM pedidos_direccion WHERE id_pdir=?")){
                                                if($sqldpr->bind_param("i", $id_pdir)){
                                                    if($sqldpr->execute()){
                                                        $sqldpr->close();
                                                        $info['op'] = 1;
                                                    }else{ $this->registrar(6, $id_loc, $id_gir, 'del_pos_direcciones() #1 '.htmlspecialchars($sqldpr->error)); }
                                                }else{ $this->registrar(6, $id_loc, $id_gir, 'del_pos_direcciones() #1 '.htmlspecialchars($sqldpr->error)); }
                                            }else{ $this->registrar(6, $id_loc, $id_gir, 'del_pos_direcciones() #1 '.htmlspecialchars($this->con->error)); }
                                        }else{
                                            $info['op'] = 2;
                                        }
                                    }else{
                                        $info['op'] = 2;
                                    }
                                    $sqluser->free_result();
                                    $sqluser->close();
                                }else{ $this->registrar(6, $id_loc, $id_gir, 'del_pos_direcciones() #2 '.htmlspecialchars($sqluser->error)); }
                            }else{ $this->registrar(6, $id_loc, $id_gir, 'del_pos_direcciones() #2 '.htmlspecialchars($sqluser->error)); }
                        }else{ $this->registrar(6, $id_loc, $id_gir, 'del_pos_direcciones() #2 '.htmlspecialchars($this->con->error)); }
                        $sqldir->free_result();
                        $sqldir->close();
                    }else{ $this->registrar(6, $id_loc, $id_gir, 'del_pos_direcciones() #3 '.htmlspecialchars($sqldir->error)); }
                }else{ $this->registrar(6, $id_loc, $id_gir, 'del_pos_direcciones() #3 '.htmlspecialchars($sqldir->error)); }
            }else{ $this->registrar(6, $id_loc, $id_gir, 'del_pos_direcciones() #3 '.htmlspecialchars($this->con->error)); }
        }
        return $info;
    }
    public function get_pos_direcciones($telefono){

        $verificar = $this->verificar_cookie();
        if($verificar['op']){
            $id_gir = $verificar['id_gir'];
            $id_loc = $verificar['id_loc'];
            if($sqlu = $this->con->prepare("SELECT t1.id_puser, t1.nombre, t2.id_pdir, t2.direccion, t2.calle, t2.num, t2.depto, t2.comuna, t2.lat, t2.lng FROM pedidos_usuarios t1, pedidos_direccion t2 WHERE t1.id_gir=? AND t1.telefono=? AND t1.id_puser=t2.id_puser")){
                if($sqlu->bind_param("is", $id_gir, $telefono)){
                    if($sqlu->execute()){
                        $resdir = $sqlu->get_result();
                        $info['cantidad'] = $resdir->{"num_rows"};
                        if($resdir->{"num_rows"} > 0){
                            while($row = $resdir->fetch_assoc()){
                                $info['id_puser'] = $row['id_puser'];
                                $info['nombre'] = $row['nombre'];
                                $aux_dir["id_pdir"] = $row['id_pdir'];
                                $aux_dir["direccion"] = $row['direccion'];
                                $aux_dir["calle"] = $row['calle'];
                                $aux_dir["num"] = $row['num'];
                                $aux_dir["depto"] = $row['depto'];
                                $aux_dir["comuna"] = $row['comuna'];
                                $aux_dir["lat"] = $row['lat'];
                                $aux_dir["lng"] = $row['lng'];
                                $info['direcciones'][] = $aux_dir;
                                unset($aux_dir);
                            }
                        }
                        $sqlu->free_result();
                        $sqlu->close();
                    }else{ $this->registrar(6, $id_loc, $id_gir, 'get_pos_direcciones() #1 '.htmlspecialchars($sqlu->error)); }
                }else{ $this->registrar(6, $id_loc, $id_gir, 'get_pos_direcciones() #1 '.htmlspecialchars($sqlu->error)); }
            }else{ $this->registrar(6, $id_loc, $id_gir, 'get_pos_direcciones() #1 '.htmlspecialchars($this->con->error)); }
        }else{ $info["mensaje"] = "NO PUDO SER VERIFICADO"; }
        return $info;
    }
    private function verificar_cookie(){

        $ip = $this->getUserIpAddr();
        $id = $_COOKIE["id"];
        $user_code = $_COOKIE["user_code"];
        $local_code = $_COOKIE["local_code"];
        $return['op'] = false;
        
        if($sql = $this->con->prepare("SELECT t2.sonido, t2.t_retiro, t2.t_despacho, t2.lat, t2.lng, t2.id_gir, t1.id_loc, t1.del_pdir, t2.code, t2.enviar_cocina, t1.save_web, t1.web_min, t1.save_pos, t1.pos_min, t2.pos FROM fw_usuarios t1, locales t2 WHERE t1.id_user=? AND t1.cookie_code=? AND t1.id_loc=t2.id_loc AND t2.cookie_code=? AND t2.cookie_ip=? AND t1.eliminado=? AND t2.eliminado=?")){
            if($sql->bind_param("isssii", $id, $user_code, $local_code, $ip, $this->eliminado, $this->eliminado)){
                if($sql->execute()){
                    $res = $sql->get_result();
                    if($res->{'num_rows'} == 1){
                        $return['op'] = true;
                        $result = $res->fetch_all(MYSQLI_ASSOC)[0];
                        $return['id_gir'] = $result['id_gir'];
                        $return['id_loc'] = $result['id_loc'];
                        $return['del_pdir'] = $result['id_loc'];
                        $return['code'] = $result['code'];
                        $return['enviar_cocina'] = $result['enviar_cocina'];
                        $return['save_web'] = $result['save_web'];
                        $return['web_min'] = $result['web_min'];
                        $return['save_pos'] = $result['save_pos'];
                        $return['pos_min'] = $result['pos_min'];
                        $return['lat'] = $result['lat'];
                        $return['lng'] = $result['lng'];
                        $return['sonido'] = $result['sonido'];
                        $return['t_retiro'] = $result['t_retiro'];
                        $return['t_despacho'] = $result['t_despacho'];
                        $return['pos'] = $result['pos'];
                    }
                    $sql->free_result();
                    $sql->close();
                }else{ $this->registrar(6, 0, 0, 'verificar_cookie() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'verificar_cookie() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, 0, 'verificar_cookie() '.htmlspecialchars($this->con->error)); }
        return $return;

    }
    public function get_ultimos_pedidos($id_ped){

        $verificar = $this->verificar_cookie();
        if($verificar['op']){
            $id_loc = $verificar['id_loc'];
            $id_gir = $verificar['id_gir'];
            if($sqlped = $this->con->prepare("SELECT * FROM pedidos_aux WHERE id_ped=? AND id_loc=? AND eliminado=? ORDER BY id_ped DESC")){
                if($sqlped->bind_param("iii", $id_ped, $id_loc, $this->eliminado)){
                    if($sqlped->execute()){
                        $row = $sqlped->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                        $res['id_ped'] = $row['id_ped'];
                        $res['num_ped'] = $row['num_ped'];
                        $res['pedido_code'] = $row['code'];
                        $res['tipo'] = $row['tipo'];
                        $res['estado'] = $row['estado'];
                        $res['fecha'] = strtotime($row['fecha']);
                        $res['despacho'] = $row['despacho'];
                        $res['carro'] = ($row['carro'] != "") ? json_decode($row['carro']) : [] ;
                        $res['promos'] = ($row['promos'] != "") ? json_decode($row['promos']) : [] ;
                        $res['pre_wasabi'] = $row['pre_wasabi'];
                        $res['pre_gengibre'] = $row['pre_gengibre'];
                        $res['pre_palitos'] = $row['pre_palitos'];
                        $res['pre_soya'] = $row['pre_soya'];
                        $res['pre_teriyaki'] = $row['pre_teriyaki'];
                        $res['verify_despacho'] = $row['verify_despacho'];
                        $res['id_mot'] = $row['id_mot'];
                        $res['eliminado'] = $row['eliminado'];
                        $res['ocultar'] = $row['ocultar'];
                        $res['costo'] = $row['costo'];
                        $res['total'] = $row['total'];
                        $res['id_puser'] = $row['id_puser'];
                        $res['id_pdir'] = $row['id_pdir'];
                        if($sqlpu = $this->con->prepare("SELECT * FROM pedidos_usuarios WHERE id_puser=?")){
                            if($sqlpu->bind_param("i", $row['id_puser'])){
                                if($sqlpu->execute()){
                                    $pedido_usuarios = $sqlpu->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                                    $res['nombre'] = $pedido_usuarios['nombre'];
                                    $res['telefono'] = $pedido_usuarios['telefono'];
                                    if($res['despacho'] == 1){
                                        if($sqlpd = $this->con->prepare("SELECT * FROM pedidos_direccion WHERE id_pdir=?")){
                                            if($sqlpd->bind_param("i", $row['id_pdir'])){
                                                if($sqlpd->execute()){
                                                    $pedido_direccion = $sqlpd->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                                                    $res['direccion'] = $pedido_direccion['direccion'];
                                                    $res['lat'] = $pedido_direccion['lat'];
                                                    $res['lng'] = $pedido_direccion['lng'];
                                                    $res['calle'] = $pedido_direccion['calle'];
                                                    $res['num'] = $pedido_direccion['num'];
                                                    $res['depto'] = $pedido_direccion['depto'];
                                                    $res['comuna'] = $pedido_direccion['num'];
                                                    $sqlpd->free_result();
                                                    $sqlpd->close();
                                                }else{ $this->registrar(6, $id_loc, $id_gir, 'get_ultimos_pedidos() #1 '.htmlspecialchars($sqlpd->error)); }
                                            }else{ $this->registrar(6, $id_loc, $id_gir, 'get_ultimos_pedidos() #1 '.htmlspecialchars($sqlpd->error)); }
                                        }else{ $this->registrar(6, $id_loc, $id_gir, 'get_ultimos_pedidos() #1 '.htmlspecialchars($this->con->error)); }
                                    }
                                    $sqlpu->free_result();
                                    $sqlpu->close();
                                }else{ $this->registrar(6, $id_loc, $id_gir, 'get_ultimos_pedidos() #2 '.htmlspecialchars($sqlpu->error)); }
                            }else{ $this->registrar(6, $id_loc, $id_gir, 'get_ultimos_pedidos() #2 '.htmlspecialchars($sqlpu->error)); }
                        }else{ $this->registrar(6, $id_loc, $id_gir, 'get_ultimos_pedidos() #2 '.htmlspecialchars($this->con->error)); }
                        $sqlped->free_result();
                        $sqlped->close();
                    }else{ $this->registrar(6, $id_loc, $id_gir, 'get_ultimos_pedidos() #3 '.htmlspecialchars($sqlped->error)); }
                }else{ $this->registrar(6, $id_loc, $id_gir, 'get_ultimos_pedidos() #3 '.htmlspecialchars($sqlped->error)); }
            }else{ $this->registrar(6, $id_loc, $id_gir, 'get_ultimos_pedidos() #3 '.htmlspecialchars($this->con->error)); }
        }
        return $res;
    }
    public function get_ultimos_pedidos_pos($id_loc){
        if($sql = $this->con->prepare("SELECT * FROM pedidos_aux WHERE id_loc=? AND eliminado=? AND fecha > DATE_ADD(NOW(), INTERVAL -2 DAY) ORDER BY id_ped DESC")){
            if($sql->bind_param("ii", $id_loc, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result();
                    while($row = $result->fetch_assoc()){
                        $res['id_ped'] = $row['id_ped'];
                        $res['num_ped'] = $row['num_ped'];
                        $res['pedido_code'] = $row['code'];
                        $res['tipo'] = $row['tipo'];
                        $res['estado'] = $row['estado'];
                        $res['fecha'] = strtotime($row['fecha']);
                        $res['despacho'] = $row['despacho'];
                        $res['carro'] = ($row['carro'] != "") ? json_decode($row['carro']) : [] ;
                        $res['promos'] = ($row['promos'] != "") ? json_decode($row['promos']) : [] ;
                        $res['pre_wasabi'] = $row['pre_wasabi'];
                        $res['pre_gengibre'] = $row['pre_gengibre'];
                        $res['pre_palitos'] = $row['pre_palitos'];
                        $res['pre_soya'] = $row['pre_soya'];
                        $res['pre_teriyaki'] = $row['pre_teriyaki'];
                        $res['verify_despacho'] = $row['verify_despacho'];
                        $res['id_mot'] = $row['id_mot'];
                        $res['eliminado'] = $row['eliminado'];
                        $res['ocultar'] = $row['ocultar'];
                        $res['costo'] = $row['costo'];
                        $res['cambios'] = 0;
                        $res['cambio_tiempo'] = 0;
                        $res['cambio_estado'] = 0;
                        $res['total'] = $row['total'];
                        $res['id_puser'] = $row['id_puser'];
                        $res['id_pdir'] = $row['id_pdir'];
                        if($sqlpu = $this->con->prepare("SELECT * FROM pedidos_usuarios WHERE id_puser=?")){
                            if($sqlpu->bind_param("i", $row['id_puser'])){
                                if($sqlpu->execute()){
                                    $pedido_usuarios = $sqlpu->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                                    $res['nombre'] = $pedido_usuarios['nombre'];
                                    $res['telefono'] = $pedido_usuarios['telefono'];
                                    $res['cont'] = $pedido_usuarios['cont'];
                                    if($res['despacho'] == 1){
                                        if($sqlpd = $this->con->prepare("SELECT * FROM pedidos_direccion WHERE id_pdir=?")){
                                            if($sqlpd->bind_param("i", $row['id_pdir'])){
                                                if($sqlpd->execute()){
                                                    $pedido_direccion = $sqlpd->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                                                    $res['direccion'] = $pedido_direccion['direccion'];
                                                    $res['lat'] = $pedido_direccion['lat'];
                                                    $res['lng'] = $pedido_direccion['lng'];
                                                    $res['calle'] = $pedido_direccion['calle'];
                                                    $res['num'] = $pedido_direccion['num'];
                                                    $res['depto'] = $pedido_direccion['depto'];
                                                    $res['comuna'] = $pedido_direccion['comuna'];
                                                    $sqlpd->free_result();
                                                    $sqlpd->close();
                                                }else{ $this->registrar(6, $id_loc, 0, 'get_ultimos_pedidos_pos() #1 '.htmlspecialchars($sqlpd->error)); }
                                            }else{ $this->registrar(6, $id_loc, 0, 'get_ultimos_pedidos_pos() #1 '.htmlspecialchars($sqlpd->error)); }
                                        }else{ $this->registrar(6, $id_loc, 0, 'get_ultimos_pedidos_pos() #1 '.htmlspecialchars($this->con->error)); }
                                    }
                                    $info[] = $res;
                                    unset($res);
                                    $sqlpu->free_result();
                                    $sqlpu->close();
                                }else{ $this->registrar(6, $id_loc, 0, 'get_ultimos_pedidos_pos() #2 '.htmlspecialchars($sqlpu->error)); }
                            }else{ $this->registrar(6, $id_loc, 0, 'get_ultimos_pedidos_pos() #2 '.htmlspecialchars($sqlpu->error)); }
                        }else{ $this->registrar(6, $id_loc, 0, 'get_ultimos_pedidos_pos() #2 '.htmlspecialchars($this->con->error)); }
                    }
                    $sql->free_result();
                    $sql->close();
                }else{ $this->registrar(6, $id_loc, 0, 'get_ultimos_pedidos_pos() #3 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, $id_loc, 0, 'get_ultimos_pedidos_pos() #3 '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, $id_loc, 0, 'get_ultimos_pedidos_pos() #3 '.htmlspecialchars($this->con->error)); }
        return $info;
    }
    public function del_pedido(){

        $verificar = $this->verificar_cookie();
        if($verificar['op']){

            $id_loc = $verificar['id_loc'];
            $id_gir = $verificar['id_gir'];
            $local_code = $verificar['code'];
            $id_ped = $_POST['id'];
            $tipo = $_POST['tipo'];
            $info['id_ped'] = $id_ped;
            $info['tipo'] = $tipo;

            if($tipo == 1){
                $local_code = $result['code'];
                if($sqlped = $this->con->prepare("UPDATE pedidos_aux SET eliminado='1' WHERE id_ped=? AND id_loc=? AND id_gir=?")){
                    if($sqlped->bind_param("iii", $id_ped, $id_loc, $id_gir)){
                        if($sqlped->execute()){

                            $send['accion'] = 'borrar_cocina_local';
                            $send['hash'] = 'hash';
                            $send['local_code'] = $local_code;
                            $send['id_ped'] = $id_ped;

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/borrar_cocina');
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));

                            if(!curl_errno($ch)){
                                $info['resp'] = json_decode(curl_exec($ch));
                                curl_close($ch);
                            }else{
                                $this->registrar(17, $id_loc, $id_gir, 'Error Curl del_pedido()');
                            }

                            $sqlped->close();
                        }else{ $this->registrar(6, $id_loc, $id_gir, 'del_pedido() #1 '.htmlspecialchars($sqlped->error)); }
                    }else{ $this->registrar(6, $id_loc, $id_gir, 'del_pedido() #1 '.htmlspecialchars($sqlped->error)); }
                }else{ $this->registrar(6, $id_loc, $id_gir, 'del_pedido() #1 '.htmlspecialchars($this->con->error)); }
            }
            if($tipo == 2){
                if($sqlped = $this->con->prepare("UPDATE pedidos_aux SET ocultar='1' WHERE id_ped=? AND id_loc=? AND id_gir=?")){
                    if($sqlped->bind_param("iii", $id_ped, $id_loc, $id_gir)){
                        if($sqlped->execute()){
                            $sqlped->close();
                        }else{ $this->registrar(6, $id_loc, $id_gir, 'del_pedido() #2 '.htmlspecialchars($sqlped->error)); }
                    }else{ $this->registrar(6, $id_loc, $id_gir, 'del_pedido() #2 '.htmlspecialchars($sqlped->error)); }
                }else{ $this->registrar(6, $id_loc, $id_gir, 'del_pedido() #2 '.htmlspecialchars($this->con->error)); }
            }
        }
        return $info;

    }
    public function permiso_modificar($sql_tipo, $sql_fecha, $mod_despacho, $save_web, $save_pos, $web_min, $pos_min){
        if($sql_tipo == 0){
            // POS
            if($save_pos == 0){
                return false;
            }
            $tiempo = $sql_fecha + $pos_min * 60;
            if(time() < $tiempo){
                if($save_pos == 1){
                    if($mod_despacho == 0){
                        return true;
                    }
                    if($mod_despacho == 1){
                        return false;
                    }    
                }
                if($save_pos == 2){
                    return true;
                }
            }else{
                return false;
            }
        }
        if($sql_tipo == 1){
            // WEB
            if($save_web == 0){
                return false;
            }
            $tiempo = $sql_fecha + $web_min * 60;
            if(time() < $tiempo){
                if($save_web == 1){
                    if($mod_despacho == 0){
                        return true;
                    }
                    if($mod_despacho == 1){
                        return false;
                    }    
                }
                if($save_web == 2){
                    return true;
                }
            }else{
                return false;
            }
        } 
    }
    private function pedido_direccion($pedido, $id_puser){
        $id = 0;
        if($pedido["direccion"] != ""){
            if($sql = $this->con->prepare("INSERT INTO pedidos_direccion (direccion, calle, num, depto, comuna, lat, lng, id_puser) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")){
                if($sql->bind_param("sssssddi", $pedido["direccion"], $pedido["calle"], $pedido["num"], $pedido["depto"], $pedido["comuna"], $pedido["lat"], $pedido["lng"], $id_puser)){
                    if($sql->execute()){
                        $id = $this->con->insert_id;
                        $sql->close();
                    }else{ $this->registrar(6, 0, 0, 'pedido_direccion() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, 0, 'pedido_direccion() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'pedido_direccion() '.htmlspecialchars($this->con->error)); }
        }
        return $id;
    }
    public function ver_acciones($acciones, $fecha_ini, $intervalo, $tipo){
        $total = 0;
        for($i=0; $i<count($acciones); $i++){
            $fecha_pedido = strtotime($acciones[$i]['fecha']);
            $fecha_fin = strtotime($intervalo, $fecha_ini);            
            if($fecha_pedido >= $fecha_ini && $fecha_pedido < $fecha_fin){
                if($tipo == $acciones[$i]['id_des']){
                    $total = $total + 1;
                }
            }
        }
        return $total;
    }
    private function get_polygons($id_gir){
        if($sql = $this->con->prepare("SELECT t3.nombre, t3.poligono, t3.precio, t3.id_loc FROM giros t1, locales t2, locales_tramos t3 WHERE t1.id_gir=? AND t1.id_gir=t2.id_gir AND t2.id_loc=t3.id_loc AND t2.eliminado=? AND t3.eliminado=?")){
            if($sql->bind_param("iii", $id_gir, $this->eliminado, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $id_gir, 'get_polygons() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $id_gir, 'get_polygons() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $id_gir, 'get_polygons() '.htmlspecialchars($this->con->error)); }           
    }
    public function enviar_error(){

        $info['op'] = 2;
        $verificar = $this->verificar();
        if($verificar['op']){
            $id_gir = $verificar['id_gir'];
            $error = $_POST['error'];
            if($error !== null){
                $codes = $_POST['codes'];
                $this->registrar($codes, 0, $id_gir, $error);
                $info['op'] = 1;
            }
        }
        return $info;

    }
    public function pedidos_total_fecha($pedidos, $fecha_ini, $intervalo, $id_loc){
        $total = 0;
        for($i=0; $i<count($pedidos); $i++){
            $fecha_pedido = strtotime($pedidos[$i]['fecha']);
            $fecha_fin = strtotime($intervalo, $fecha_ini);            
            if($fecha_pedido >= $fecha_ini && $fecha_pedido < $fecha_fin){
                if($id_loc == $pedidos[$i]['id_loc']){
                    $total = $total + $pedidos[$i]['total'];
                }
            }
        }
        return $total;
    }
    public function pedidos_despacho_fecha($pedidos, $fecha_ini, $intervalo, $id_loc, $tipo){
        $total = 0;
        for($i=0; $i<count($pedidos); $i++){
            $fecha_pedido = strtotime($pedidos[$i]['fecha']);
            $fecha_fin = strtotime($intervalo, $fecha_ini);            
            if($fecha_pedido >= $fecha_ini && $fecha_pedido < $fecha_fin){
                if($id_loc == $pedidos[$i]['id_loc'] && $pedidos[$i]['despacho'] == $tipo){
                    $total = $total + 1;
                }
            }
        }
        return $total;
    }
    public function get_stats($tipo, $locales, $from, $to){

        if($sql = $this->con->prepare("SELECT * FROM pedidos_aux WHERE id_gir=? AND fecha > ? AND fecha < ? AND eliminado=?")){
            if($sql->bind_param("issi", $this->id_gir, $from, $to, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result();
                    while($row = $result->fetch_assoc()){
                        $pedidos[] = $row;
                    }
                    $from = strtotime($from);
                    $to = strtotime($to) + 86400;        
                    $dif_tiempo = round(($to - $from)/86400);
                    $aux_from = $from;
                    $mes = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                    if($dif_tiempo <= 50){
                        // MOSTRAR DIAS
                        $info['subtitle']['text'] = 'Tiempo Real en dias';
                        $infos['tipo'] = 1;
                        $lapse = "1 day";
                        while($to > $aux_from){
                            $info['xAxis']['categories'][] = date("d", $aux_from);
                            $infos['fecha'][] = $aux_from;
                            $aux_from = $aux_from + 86400;
                        }
                    }
                    if($dif_tiempo > 50 && $dif_tiempo < 548){
                        // MOSTRAR MESES
                        $info['subtitle']['text'] = 'Tiempo Real en meses';
                        $infos['tipo'] = 2;
                        $lapse = "1 month";
                        while($to > $aux_from){
                            $aux_mes = intval(date("m", $aux_from)) - 1;
                            $info['xAxis']['categories'][] = $mes[$aux_mes];
                            $infos['fecha'][] = $aux_from;
                            $aux_from = strtotime('+1 month', $aux_from);
                        }
                    }
                    if($dif_tiempo >= 548){
                        // MOSTRAR AÑOS
                        $info['subtitle']['text'] = 'Tiempo Real en a&ntilde;os';
                        $infos['tipo'] = 3;
                        $lapse = "1 year";
                        while($to > $aux_from){
                            $info['xAxis']['categories'][] = date("Y", $aux_from);
                            $infos['fecha'][] = $aux_from;
                            $aux_from = strtotime('+1 Year', $aux_from);
                        }
                    }
                    $info['chart']['type'] = 'line';
                    $info['yAxis']['title']['text'] = null;
                    $info['plotOptions']['line']['dataLabels']['enabled'] = true;
                    $info['plotOptions']['line']['enableMouseTracking'] = false;
                    if($tipo == 0){
                        $info['title']['text'] = 'Total Ventas';            
                        for($j=0; $j<count($locales); $j++){
                            $aux['name'] = $locales[$j]->{'nombre'};
                            foreach($infos['fecha'] as $fecha){
                                $aux['data'][] = $this->pedidos_total_fecha($pedidos, $fecha, $lapse, $locales[$j]->{'id_loc'});
                            }
                            $info['series'][] = $aux;
                            unset($aux);
                        }
                    }
                    if($tipo == 1){
                        $info['title']['text'] = 'Total Pedidos Despacho Domicilio';          
                        for($j=0; $j<count($locales); $j++){
                            $aux['name'] = $locales[$j]->{'nombre'};
                            foreach($infos['fecha'] as $fecha){
                                $aux['data'][] = $this->pedidos_despacho_fecha($pedidos, $fecha, $lapse, $locales[$j]->{'id_loc'}, 1);
                            }
                            $info['series'][] = $aux;
                            unset($aux);
                        }
                    }
                    if($tipo == 2){
                        $info['title']['text'] = 'Total Pedidos Retiro Local';          
                        for($j=0; $j<count($locales); $j++){
                            $aux['name'] = $locales[$j]->{'nombre'};
                            foreach($infos['fecha'] as $fecha){
                                $aux['data'][] = $this->pedidos_despacho_fecha($pedidos, $fecha, $lapse, $locales[$j]->{'id_loc'}, 0);
                            }
                            $info['series'][] = $aux;
                            unset($aux);
                        }
                    }
                    $sql->free_result();
                    $sql->close();
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_stats() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_stats() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_stats() '.htmlspecialchars($this->con->error)); }
        return $info;
    }
    public function get_web_js_data_remote(){

        $info['op'] = 2;
        $verificar = $this->verificar();
        if($verificar['op']){
            $id_gir = $verificar['id_gir'];
            if($sql = $this->con->prepare("SELECT * FROM catalogo_productos WHERE id_gir=? AND eliminado=?")){
                if($sql->bind_param("ii", $id_gir, $this->eliminado)){
                    if($sql->execute()){
                        $info['op'] = 1;
                        $result = $sql->get_result();
                        while($row = $result->fetch_assoc()){
                            $info['data']['catalogos'][] = $this->get_info_catalogo($row['id_cat']);
                        }
                        $info['data']['config'] = $this->get_config($id_gir);
                        $info['data']['paginas'] = $this->get_paginas_web($id_gir);
                        $info['data']['locales'] = $this->get_locales_js($id_gir);
                        $info['polygons'] = $this->get_polygons($id_gir);
                        $info['info'] = $this->get_data($id_gir);
                        $ruta_file = "/var/www/html/restaurants/data/".$info['info']['code'].".js";

                        file_put_contents($ruta_file, "var data=".json_encode($info['data']));
                        /*
                        if($info['info']['dns'] == 0){
                            file_put_contents($ruta_file, "var data=".json_encode($info['data']));
                        }
                        if($info['info']['dns'] == 1 && file_exists($ruta_file)){
                            unlink($ruta_file);
                        }
                        */
                        $sql->free_result();
                        $sql->close();
                    }else{ $this->registrar(6, 0, $id_gir, 'get_web_js_data_remote() #1 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $id_gir, 'get_web_js_data_remote() #1 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $id_gir, 'get_web_js_data_remote() #1 '.htmlspecialchars($this->con->error)); }
        }
        return $info;
    }
    public function ver_detalle(){

        $info['op'] = 2;
        $verificar = $this->verificar();
        if($verificar['op']){
            $id_gir = $verificar['id_gir'];
            $pedido_code = $_POST["pedido_code"];
            if($sql = $this->con->prepare("SELECT * FROM pedidos_aux WHERE code=? AND id_gir=? AND eliminado=?")){
                if($sql->bind_param("sii", $pedido_code, $id_gir, $this->eliminado)){
                    if($sql->execute()){
                        if(file_exists("/var/www/html/pedidos_pos/".$pedido_code.".json")){
                            $info['op'] = 1;
                            $info['resp'] = file_get_contents("/var/www/html/pedidos_pos/".$pedido_code.".json");
                            unlink("/var/www/html/pedidos_pos/".$pedido_code.".json");
                        }
                    }else{ $this->registrar(6, 0, 0, 'ver_detalle() #1 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, 0, 'ver_detalle() #1 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'ver_detalle() #1 '.htmlspecialchars($this->con->error)); }
        }
        return $info;
        
    }
    public function set_web_pedido(){

        $verificar = $this->verificar_cookie();
        $info['info'] = '';
        if($verificar['op']){

            $pedido = json_decode($_POST['pedido']);
            $cambios = $pedido->{'cambios'};

            if($cambios == 1){

                $carro = $pedido->{'carro'};
                $promos = $pedido->{'promos'};
                $id_ped = intval($pedido->{'id_ped'});
                $despacho = intval($pedido->{'despacho'});
                $estado = intval($pedido->{'estado'});
                $id_puser = $pedido->{'id_puser'};
                $nombre = $pedido->{'nombre'};
                $telefono = $pedido->{'telefono'};
                $id_pdir = $pedido->{'id_pdir'};
                $direccion = $pedido->{'direccion'};
                $calle = $pedido->{'calle'};
                $num = $pedido->{'num'};
                $depto = $pedido->{'depto'};
                $comuna = $pedido->{'comuna'};
                $comentarios = $pedido->{'comentarios'};
                $lat = $pedido->{'lat'};
                $lng = $pedido->{'lng'};
                $pre_gengibre = intval($pedido->{'pre_gengibre'});
                $pre_wasabi = intval($pedido->{'pre_wasabi'});
                $pre_palitos = intval($pedido->{'pre_palitos'});
                $pre_soya = intval($pedido->{'pre_soya'});
                $pre_teriyaki = intval($pedido->{'pre_teriyaki'});
                $ocultar = intval($pedido->{'ocultar'});
                $eliminado = intval($pedido->{'eliminado'});
                $costo = intval($pedido->{'costo'});
                $total = intval($pedido->{'total'});
                $id_gir = $verificar['id_gir'];
                $id_loc = $verificar['id_loc'];
                $local_code = $verificar["code"];
                $enviar_cocina = $verificar["enviar_cocina"];
                $save_web = $verificar["save_web"];
                $web_min = $verificar["web_min"];
                $save_pos = $verificar["save_pos"];
                $pos_min = $verificar["pos_min"];

                // PEDIDO NUEVO //
                if($id_ped == 0){
                    if($sqlgir = $this->con->prepare("SELECT num_ped FROM giros WHERE id_gir=? AND eliminado=?")){
                        if($sqlgir->bind_param("ii", $id_gir, $this->eliminado)){
                            if($sqlgir->execute()){
                                $resultgir = $sqlgir->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                                $num_ped = $resultgir["num_ped"] + 1;
                                if($sqlped = $this->con->prepare("UPDATE giros SET num_ped=? WHERE id_gir=? AND eliminado=?")){
                                    if($sqlped->bind_param("iii", $num_ped, $id_gir, $this->eliminado)){
                                        if($sqlped->execute()){
                                            $code = $this->pass_generate(20);
                                            if($sqlaux = $this->con->prepare("INSERT INTO pedidos_aux (num_ped, tipo, fecha, code, id_loc, id_gir) VALUES (?, '0', now(), ?, ?, ?)")){
                                                if($sqlaux->bind_param("isii", $num_ped, $code, $id_loc, $id_gir)){
                                                    if($sqlaux->execute()){
                                                        $id_ped = $this->con->insert_id;
                                                        $info['id_ped'] = $id_ped;
                                                        $info['num_ped'] = $num_ped;
                                                        $info['pedido_code'] = $code;
                                                        $sqlaux->close();
                                                    }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #1 '.htmlspecialchars($sqlaux->error)); }
                                                }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #1 '.htmlspecialchars($sqlaux->error)); }
                                            }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #1 '.htmlspecialchars($this->con->error)); }
                                            $sqlped->close();
                                        }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #2 '.htmlspecialchars($sqlped->error)); }
                                    }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #2 '.htmlspecialchars($sqlped->error)); }
                                }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #2 '.htmlspecialchars($this->con->error)); }
                                $sqlgir->free_result();
                                $sqlgir->close();
                            }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #3 '.htmlspecialchars($sqlgir->error)); }
                        }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #3 '.htmlspecialchars($sqlgir->error)); }
                    }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #3 '.htmlspecialchars($this->con->error)); }
                }

                // DATA DEL PEDIDO//
                if($sqlpaux = $this->con->prepare("SELECT id_puser, id_pdir, carro, promos, num_ped, mod_despacho, tipo, fecha, costo, total, code FROM pedidos_aux WHERE id_ped=? AND id_loc=? AND id_gir=? AND eliminado=?")){
                    if($sqlpaux->bind_param("iiii", $id_ped, $id_loc, $id_gir, $this->eliminado)){
                        if($sqlpaux->execute()){
                            $resultpaux = $sqlpaux->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                            $sql_id_puser = $resultpaux['id_puser'];
                            $sql_id_pdir = $resultpaux['id_pdir'];
                            $sql_carro = $resultpaux['carro'];
                            $sql_promos = $resultpaux['promos'];
                            $num_ped = $resultpaux['num_ped'];
                            $mod_despacho = $resultpaux['mod_despacho'];
                            $sql_tipo = $resultpaux['tipo'];
                            $sql_total = $resultpaux['total'];
                            $sql_costo = $resultpaux['costo'];
                            $code = $resultpaux['code'];
                            $sql_fecha = strtotime($resultpaux['fecha']);
                            //$info['carro'] = ($sql_carro != "") ? json_decode($sql_carro) : [] ;
                        }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #4 '.htmlspecialchars($sqlpaux->error)); }
                    }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #4 '.htmlspecialchars($sqlpaux->error)); }
                }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #4 '.htmlspecialchars($this->con->error)); }

                // EN CASO DE USUARIO NUEVO //
                if($id_puser == 0 && $sql_id_puser == 0){
                    if(strlen($nombre) >= 2){
                        if(strlen($telefono) >= 12 && strlen($telefono) <= 14){
                            if($sqlipu = $this->con->prepare("INSERT INTO pedidos_usuarios (nombre, telefono, id_gir) VALUES (?, ?, ?)")){
                                if($sqlipu->bind_param("ssi", $nombre, $telefono, $id_gir)){
                                    if($sqlipu->execute()){
                                        $id_puser = $this->con->insert_id;
                                        if($sqlupa = $this->con->prepare("UPDATE pedidos_aux SET id_puser=? WHERE id_ped=? AND id_loc=? AND id_gir=?")){
                                            if($sqlupa->bind_param("iiii", $id_puser, $id_ped, $id_loc, $id_gir)){
                                                if($sqlupa->execute()){
                                                    $sqlupa->close();
                                                }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #5 '.htmlspecialchars($sqlupa->error)); }
                                            }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #5 '.htmlspecialchars($sqlupa->error)); }
                                        }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #5 '.htmlspecialchars($this->con->error)); }
                                        $sqlipu->close();
                                    }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #6 '.htmlspecialchars($sqlipu->error)); }
                                }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #6 '.htmlspecialchars($sqlipu->error)); }
                            }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #6 '.htmlspecialchars($this->con->error)); }
                        }else{ /* TELEFONO INVALIDO */ }
                    }else{ /* NOMBRE INVALIDO */ }
                }

                // EN CASO DE CAMBIAR USUARIO //
                if($id_puser > 0 && $sql_id_puser != $id_puser){
                    if($sqlspu = $this->con->prepare("SELECT nombre, telefono FROM pedidos_usuarios WHERE id_puser=? AND id_gir=?")){
                        if($sqlspu->bind_param("ii", $id_puser, $id_gir)){
                            if($sqlspu->execute()){
                                $resspu = $sqlspu->get_result();
                                if($resspu->{'num_rows'} == 1){
                                    $resultspu = $resspu->fetch_all(MYSQLI_ASSOC)[0];
                                    $nombre = $resultspu['nombre'];
                                    $telefono = $resultspu['telefono'];
                                    if($sqlupa = $this->con->prepare("UPDATE pedidos_aux SET id_puser=? WHERE id_ped=? AND id_loc=? AND id_gir=?")){
                                        if($sqlupa->bind_param("iiii", $id_puser, $id_ped, $id_loc, $id_gir)){
                                            if($sqlupa->execute()){
                                                $sqlupa->close();
                                            }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #6.5 '.htmlspecialchars($sqlupa->error)); }
                                        }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #6.5 '.htmlspecialchars($sqlupa->error)); }
                                    }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #6.5 '.htmlspecialchars($this->con->error)); }
                                }
                                if($resspu->{'num_rows'} == 0){
                                    // TRATANDO DE GUARDAR UNA PERSONA QUE NO ES DEL GIRO
                                }
                            }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #7 '.htmlspecialchars($sqlspu->error)); }
                        }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #7 '.htmlspecialchars($sqlspu->error)); }
                    }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #7 '.htmlspecialchars($this->con->error)); }
                }

                // EN CASO DE DIRECCION NUEVA //
                if($id_pdir == 0 && $sql_id_pdir == 0){
                    if($direccion != ""){
                        if($sqlipd = $this->con->prepare("INSERT INTO pedidos_direccion (direccion, calle, num, depto, comuna, lat, lng, id_puser) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")){
                            if($sqlipd->bind_param("ssissddi", $direccion, $calle, $num, $depto, $comuna, $lat, $lng, $id_puser)){
                                if($sqlipd->execute()){
                                    $id_pdir = $this->con->insert_id;
                                    if($sqlupd = $this->con->prepare("UPDATE pedidos_aux SET id_pdir=? WHERE id_ped=? AND id_loc=? AND id_gir=?")){
                                        if($sqlupd->bind_param("iii", $id_pdir, $id_ped, $id_loc, $id_gir)){
                                            if($sqlupd->execute()){
                                                $sqlupd->close();
                                            }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #8 '.htmlspecialchars($sqlupd->error)); }
                                        }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #8 '.htmlspecialchars($sqlupd->error)); }
                                    }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #8 '.htmlspecialchars($this->con->error)); }
                                    $sqlipd->close();
                                }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #9 '.htmlspecialchars($sqlipd->error)); }
                            }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #9 '.htmlspecialchars($sqlipd->error)); }
                        }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #9 '.htmlspecialchars($this->con->error)); }
                    }else{ /* NO HAY DIRECCION */ }
                }

                // EN CASO DE CAMBIAR DIRECCION //
                if($id_pdir > 0 && $sql_id_pdir != $id_pdir){
                    if($sqlspp = $this->con->prepare("SELECT t1.direccion, t1.calle, t1.num, t1.depto, t1.comuna FROM pedidos_direccion t1, pedidos_usuarios t2 WHERE t1.id_pdir=? AND t1.id_puser=t2.id_puser AND t2.id_gir=?")){
                        if($sqlspp->bind_param("iii", $id_pdir, $id_gir)){
                            if($sqlspp->execute()){
                                $resspp = $sqlspp->get_result();
                                if($resspp->{'num_rows'} == 1){
                                    $resultspp = $resspp->fetch_all(MYSQLI_ASSOC)[0];
                                    $direccion = $resultspp["direccion"];
                                    $calle = $resultspp["calle"];
                                    $num = $resultspp["num"];
                                    $depto = $resultspp["depto"];
                                    $comuna = $resultspp["comuna"];
                                    if($sqlupd = $this->con->prepare("UPDATE pedidos_aux SET id_pdir=? WHERE id_ped=? AND id_loc=? AND id_gir=?")){
                                        if($sqlupd->bind_param("iiii", $id_pdir, $id_ped, $id_loc, $id_gir)){
                                            if($sqlupd->execute()){
                                                $sqlupd->close();
                                            }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #9.5 '.htmlspecialchars($sqlupd->error)); }
                                        }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #9.5 '.htmlspecialchars($sqlupd->error)); }
                                    }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #9.5 '.htmlspecialchars($this->con->error)); }
                                }
                                if($resspp->{'num_rows'} == 0){
                                    // TRATANDO DE GUARDAR UNA DIRECCION QUE NO ES DE LA PERSONA
                                }
                            }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #10 '.htmlspecialchars($sqlspp->error)); }
                        }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #10 '.htmlspecialchars($sqlspp->error)); }
                    }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #10 '.htmlspecialchars($this->con->error)); }
                }

                if(count($carro) > 0){
                    if($sql_carro == "" || $this->permiso_modificar($sql_tipo, $sql_fecha, $mod_despacho, $save_web, $save_pos, $web_min, $pos_min)){
                        if($sqlutp = $this->con->prepare("UPDATE pedidos_aux SET costo=?, pre_teriyaki=?, pre_soya=?, pre_palitos=?, pre_wasabi=?, pre_gengibre=?, estado=?, despacho=?, carro=?, promos=?, mod_despacho='1', total=? WHERE id_ped=? AND id_loc=? AND id_gir=? AND eliminado=?")){
                            if($sqlutp->bind_param("iiiiiiiissiiiii", $costo, $pre_teriyaki, $pre_soya, $pre_palitos, $pre_wasabi, $pre_gengibre, $estado, $despacho, json_encode($carro), json_encode($promos), $total, $id_ped, $id_loc, $id_gir, $this->eliminado)){
                                if($sqlutp->execute()){

                                    // ENVIAR ARCHIVO //
                                    $file['pedido']['id_ped'] = $id_ped;
                                    $file['pedido']['num_ped'] = $num_ped;
                                    $file['pedido']['fecha'] = $sql_fecha;
                                    $file['pedido']['despacho'] = $despacho;
                                    $file['pedido']['id_loc'] = $id_loc;

                                    $file['pedido']['nombre'] = $nombre;
                                    $file['pedido']['telefono'] = $telefono;
                                    $file['pedido']['calle'] = $calle;
                                    $file['pedido']['num'] = $num;
                                    $file['pedido']['depto'] = $depto;
                                    $file['pedido']['comuna'] = $comuna;
                                    $file['pedido']['direccion'] = $direccion;

                                    $file['pedido']['pre_gengibre'] = $pre_gengibre;
                                    $file['pedido']['pre_wasabi'] = $pre_wasabi;
                                    $file['pedido']['pre_teriyaki'] = $pre_teriyaki;
                                    $file['pedido']['pre_soya'] = $pre_soya;
                                    $file['pedido']['pre_palitos'] = $pre_palitos;
                                    $file['pedido']['comentarios'] = $comentarios;

                                    $file['pedido']['costo'] = $costo;
                                    $file['pedido']['total'] = $total;

                                    $file['carro'] = $carro;
                                    $file['promos'] = $promos;

                                    file_put_contents("/var/www/html/pedidos_pos/".$code.".json", json_encode($file));

                                    if($total + $costo != $sql_total + $sql_costo && $sql_tipo == 1){
                                        $aux['accion'] = 2;
                                        $aux['total'] = $total + $costo;
                                        $send['estado'] = json_encode($aux);
                                        $send['pedido_code'] = $code;
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/cambiar_estado');
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                                        if(!curl_errno($ch)){
                                            $resp_node = json_decode(curl_exec($ch));
                                            $info['resp_node1'] = $resp_node;
                                        }else{
                                            $this->registrar(17, $id_loc, $id_gir, 'Error Curl cambiar_estado()');
                                        }
                                    }

                                    if($enviar_cocina == 1){

                                        $send['accion'] = 'enviar_cocina_local';
                                        $send['hash'] = 'hash';
                                        $send['local_code'] = $local_code;
                                        $send['id_ped'] = $id_ped;
                                        $send['num_ped'] = $num_ped;
                                        $send['carro'] = $carro;
                                        $send['promos'] = $promos;
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/enviar_cocina');
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                                        if(!curl_errno($ch)){
                                            $resp_node = json_decode(curl_exec($ch));
                                            $info['resp_node2'] = $resp_node;
                                            curl_close($ch);
                                        }else{
                                            $this->registrar(17, $id_loc, $id_gir, 'Error Curl cambiar_estado()');
                                        }

                                    }

                                    $sqlutp->close();
                                }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #11 '.htmlspecialchars($sqlutp->error)); }
                            }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #11 '.htmlspecialchars($sqlutp->error)); }
                        }else{ $this->registrar(6, $id_loc, $id_gir, 'set_web_pedido() #11 '.htmlspecialchars($this->con->error)); }
                    }else{
                        $info['alert'] = 'no se efectuaron los cambios';
                    }
                }
            }
        
        }
        return $info;

    }
    public function enviar_contacto(){

        $info['op'] = 2;
        $verificar = $this->verificar();
        if($verificar['op']){
            $correo = $_POST['correo'];
            $telefono = $_POST['telefono'];
            $comentario = $_POST['comentario'];
        }

    }
    public function get_informe($from, $to){
        if($sqlgir = $this->con->prepare("SELECT nombre FROM giros WHERE id_gir=? AND eliminado=?")){
            if($sqlgir->bind_param("ii", $this->id_gir, $this->eliminado)){
                if($sqlgir->execute()){
                    $result = $sqlgir->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $data["nombre"] = $result["nombre"];
                    if($sqlloc = $this->con->prepare("SELECT id_loc, nombre FROM locales WHERE id_gir=? AND eliminado=?")){
                        if($sqlloc->bind_param("ii", $this->id_gir, $this->eliminado)){
                            if($sqlloc->execute()){
                                $locales = $sqlloc->get_result()->fetch_all(MYSQLI_ASSOC);
                                if($sqlloc2 = $this->con->prepare("SELECT * FROM pedidos_aux WHERE id_gir=? AND fecha > ? AND fecha < ? AND eliminado=?")){
                                    if($sqlloc2->bind_param("issi", $this->id_gir, $from, $to, $this->eliminado)){
                                        if($sqlloc2->execute()){
                                            $resultloc = $sqlloc2->get_result();
                                            while($row = $resultloc->fetch_assoc()){
                                                $pedidos[] = $row;
                                            }
                                            $sqlloc2->free_result();
                                            $sqlloc2->close();
                                        }else{ $this->registrar(6, 0, $this->id_gir, 'get_informe() #1 '.htmlspecialchars($sqlloc2->error)); }
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'get_informe() #1 '.htmlspecialchars($sqlloc2->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'get_informe() #1 '.htmlspecialchars($this->con->error)); }
                                $from = strtotime($from);
                                $to = strtotime($to) + 86400;        
                                $dif_tiempo = round(($to - $from)/86400);
                                $aux_from = $from;
                                $mes = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                                if($dif_tiempo <= 50){
                                    $lapse = "1 day";
                                    while($to > $aux_from){
                                        $info['xAxis']['categories'][] = date("d", $aux_from);
                                        $infos['fecha'][] = $aux_from;
                                        $aux_from = $aux_from + 86400;
                                    }
                                }
                                if($dif_tiempo > 50 && $dif_tiempo < 548){
                                    $lapse = "1 month";
                                    while($to > $aux_from){
                                        $aux_mes = intval(date("m", $aux_from)) - 1;
                                        $info['xAxis']['categories'][] = $mes[$aux_mes];
                                        $infos['fecha'][] = $aux_from;
                                        $aux_from = strtotime('+1 month', $aux_from);
                                    }
                                }
                                if($dif_tiempo >= 548){
                                    $lapse = "1 year";
                                    while($to > $aux_from){
                                        $info['xAxis']['categories'][] = date("Y", $aux_from);
                                        $infos['fecha'][] = $aux_from;
                                        $aux_from = strtotime('+1 Year', $aux_from);
                                    }
                                }
                                $info['chart']['type'] = 'line';
                                $info['credits']['enabled'] = false;
                                $info['legend']['layout'] = 'vertical';
                                $info['legend']['align'] = 'right';
                                $info['legend']['verticalAlign'] = 'top';
                                $info['legend']['x'] = 0;
                                $info['legend']['y'] = 0;
                                $info['legend']['floating'] = true;
                                $info['legend']['borderWidth'] = 1;
                                $info['legend']['backgroundColor'] = '#fff';
                                $info['yAxis']['title']['text'] = null;
                                $info['plotOptions']['line']['dataLabels']['enabled'] = true;
                                $info['plotOptions']['line']['enableMouseTracking'] = false;

                                if($sqlacc = $this->con->prepare("SELECT * FROM seguimiento WHERE id_gir=?")){
                                    if($sqlacc->bind_param("i", $this->id_gir)){
                                        if($sqlacc->execute()){
                                            $acciones = $sqlacc->get_result()->fetch_all(MYSQLI_ASSOC);
                                            // CHART 1
                                            $info['title']['text'] = 'Administrador';  
                                            $data["chart1"] = $info;
                                            $tipo_nom = ["Ingresos", "Errores"];
                                            $tipo_num = [0, 1];
                                            for($i=0; $i<count($tipo_nom); $i++){
                                                $aux['name'] = $tipo_nom[$i];
                                                foreach($infos['fecha'] as $fecha){
                                                    $aux['data'][] = $this->ver_acciones($acciones, $fecha, $lapse, $tipo_num[$i]);
                                                }
                                                $data['chart1']['series'][] = $aux;
                                                unset($aux);
                                            }
                                            // CHART 2
                                            $info['title']['text'] = 'Cantidad Ventas'; 
                                            $data["chart2"] = $info;
                                            $aux['name'] = 'Pedidos';
                                            foreach($infos['fecha'] as $fecha){
                                                $aux['data'][] = $this->ver_acciones($acciones, $fecha, $lapse, 1);
                                            }
                                            $data['chart2']['series'][] = $aux;
                                            unset($aux);
                                            // CHART 3
                                            $info['title']['text'] = 'Total Ventas';
                                            $data["chart3"] = $info;           
                                            for($j=0; $j<count($locales); $j++){
                                                $aux['name'] = $locales[$j]['nombre'];
                                                foreach($infos['fecha'] as $fecha){
                                                    $aux['data'][] = $this->pedidos_total_fecha($pedidos, $fecha, $lapse, $locales[$j]['id_loc']);
                                                }
                                                $data["chart3"]['series'][] = $aux;
                                                unset($aux);
                                            }
                                            $sqlacc->free_result();
                                            $sqlacc->close();
                                        }else{ $this->registrar(6, 0, $this->id_gir, 'get_informe() #3 '.htmlspecialchars($sqlacc->error)); }
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'get_informe() #3 '.htmlspecialchars($sqlacc->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'get_informe() #3 '.htmlspecialchars($this->con->error)); }
                                $sqlloc->free_result();
                                $sqlloc->close();
                            }else{ $this->registrar(6, 0, $this->id_gir, 'get_informe() #2 '.htmlspecialchars($sqlloc->error)); }
                        }else{ $this->registrar(6, 0, $this->id_gir, 'get_informe() #2 '.htmlspecialchars($sqlloc->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'get_informe() #2 '.htmlspecialchars($this->con->error)); }
                    $sqlgir->free_result();
                    $sqlgir->close();
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_informe() #3 '.htmlspecialchars($sqlgir->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_informe() #3 '.htmlspecialchars($sqlgir->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_informe() #3 '.htmlspecialchars($this->con->error)); }
        return $data;
    }
    public function pass_generate($n){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for($i=0; $i<$n; $i++){
            $r .= $chars{rand(0, strlen($chars)-1)};
        }
        return $r;
    }
    public function crear_dominio(){

        $info['op'] = 2;
        $info['tipo'] = 0;
        $info['mensaje'] = '';

        $correo = $_POST["correo"];
        $dominio = $_POST["dominio"];
        $dominio_val = explode(".", $dominio);
        $telefono = $_POST["telefono"];

        if(count($dominio_val) == 3 && $dominio_val[0] == "www" && strlen($dominio_val[1]) > 1 && strlen($dominio_val[2]) > 1){
            if(strlen($telefono) >= 12 && strlen($telefono) <= 14){
                if(filter_var($correo, FILTER_VALIDATE_EMAIL)){

                    $url = 'https://www.google.com/recaptcha/api/siteverify';
                    $datas = [
                        'secret' => '6LdZp78UAAAAALb66uCWx7RR3cuSjhQLhy8sWZdu',
                        'response' => $_POST['token'],
                        'remoteip' => $_SERVER['REMOTE_ADDR']
                    ];
                    $options = array(
                        'http' => array(
                            'header'  => 'Content-type: application/x-www-form-urlencoded\r\n',
                            'method'  => 'POST',
                            'content' => http_build_query($datas)
                        )
                    );
                    $context  = stream_context_create($options);
                    $response = file_get_contents($url, false, $context);
                    $res = json_decode($response, true);

                    if($res['success'] == true){
                        if($sql = $this->con->prepare("SELECT * FROM fw_usuarios WHERE correo=? AND eliminado=?")){
                            if($sql->bind_param("si", $correo, $this->eliminado)){
                                if($sql->execute()){
                                    $ressql = $sql->get_result();
                                    if($ressql->{"num_rows"} == 0){
                                        if($sqld = $this->con->prepare("SELECT * FROM giros WHERE dominio=? AND eliminado=?")){
                                            if($sqld->bind_param("si", $dominio, $this->eliminado)){
                                                if($sqld->execute()){
                                                    $ressqld = $sqld->get_result();
                                                    if($ressqld->{"num_rows"} == 0){
                                                        $code = $this->pass_generate(20);
                                                        $catalogo = 1;
                                                        if($sqligi = $this->con->prepare("INSERT INTO giros (telefono, dominio, fecha_creado, code, catalogo, item_grafico, item_pos, item_cocina, item_pagina, style_page, style_color, style_modal, font_family, font_css, alto, alto_pro, logo, favicon, eliminado, id_ser) VALUES (?, ?, now(), ?, ?, '1', '1', '1', '1', 'css_tipo_01.css', 'css_colores_01.css', 'css_fontsize_01.css', 'K2D', 'K2D', '25', '20', 'sinlogo.png', 'default.ico', '0', '1')")){
                                                            if($sqligi->bind_param("sssi", $telefono, $dominio, $code, $catalogo)){
                                                                if($sqligi->execute()){
                                                                    $giro_id = $this->con->insert_id;
                                                                    $mailcode = $this->pass_generate(20);
                                                                    $admin = 0;
                                                                    if($sqlis = $this->con->prepare("INSERT INTO fw_usuarios (correo, mailcode, fecha_creado, admin) VALUES (?, ?, now(), ?)")){
                                                                        if($sqlis->bind_param("sssi", $correo, $mailcode, $admin)){
                                                                            if($sqlis->execute()){
                                                                                $usuario_id = $this->con->insert_id;
                                                                                if($sqliug = $this->con->prepare("INSERT INTO fw_usuarios_giros (id_gir, id_user) VALUES (?, ?)")){
                                                                                    if($sqliug->bind_param("ii", $giro_id, $usuario_id)){
                                                                                        if($sqliug->execute()){
                                                                                            $n_catalogo = "Catalogo 01";
                                                                                            if($sqlicp = $this->con->prepare("INSERT INTO catalogo_productos (nombre, fecha_creado, id_gir) VALUES (?, now(), ?)")){
                                                                                                if($sqlicp->bind_param("si", $n_catalogo, $giro_id)){
                                                                                                    if($sqlicp->execute()){
                                                                                                        $send['dominio'] = $dominio;
                                                                                                        $send['correo'] = $correo;
                                                                                                        $send['id'] = $usuario_id;
                                                                                                        $send['code'] = $mailcode;
                                                                                                        $ch = curl_init();
                                                                                                        curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/mail_inicio');
                                                                                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                                                                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                                                                                                        if(!curl_errno($ch)){
                                                                                                            $resp_email = json_decode(curl_exec($ch));
                                                                                                            $info['op'] = 1;
                                                                                                            $info['resp'] = $resp_email;
                                                                                                            curl_close($ch);
                                                                                                        }
                                                                                                        $sqlicp->close();
                                                                                                    }else{ $this->registrar(6, 0, 0, 'crear_dominio() #1 '.htmlspecialchars($sqlicp->error)); }
                                                                                                }else{ $this->registrar(6, 0, 0, 'crear_dominio() #1 '.htmlspecialchars($sqlicp->error)); }
                                                                                            }else{ $this->registrar(6, 0, 0, 'crear_dominio() #1 '.htmlspecialchars($this->con->error)); }
                                                                                            $sqliug->close();
                                                                                        }else{ $this->registrar(6, 0, 0, 'crear_dominio() #2 '.htmlspecialchars($sqliug->error)); }
                                                                                    }else{ $this->registrar(6, 0, 0, 'crear_dominio() #2 '.htmlspecialchars($sqliug->error)); }
                                                                                }else{ $this->registrar(6, 0, 0, 'crear_dominio() #2 '.htmlspecialchars($this->con->error)); }
                                                                                $sqlis->close();
                                                                            }else{ $this->registrar(6, 0, 0, 'crear_dominio() #3 '.htmlspecialchars($sqlis->error)); }
                                                                        }else{ $this->registrar(6, 0, 0, 'crear_dominio() #3 '.htmlspecialchars($sqlis->error)); }
                                                                    }else{ $this->registrar(6, 0, 0, 'crear_dominio() #3 '.htmlspecialchars($this->con->error)); }
                                                                    $sqligi->close();
                                                                }else{ $this->registrar(6, 0, 0, 'crear_dominio() #4 '.htmlspecialchars($sqligi->error)); }
                                                            }else{ $this->registrar(6, 0, 0, 'crear_dominio() #4 '.htmlspecialchars($sqligi->error)); }
                                                        }else{ $this->registrar(6, 0, 0, 'crear_dominio() #4 '.htmlspecialchars($this->con->error)); }
                                                    }
                                                    if($ressqld->{"num_rows"} > 0){
                                                        /* DOMINIO EXISTENTE */
                                                        $info['tipo'] = 2;
                                                        $info['mensaje'] = 'Dominio Existente';
                                                    }
                                                    $sqld->free_result();
                                                    $sqld->close();
                                                }else{ $this->registrar(6, 0, 0, 'crear_dominio() #5 '.htmlspecialchars($sqld->error)); }
                                            }else{ $this->registrar(6, 0, 0, 'crear_dominio() #5 '.htmlspecialchars($sqld->error)); }
                                        }else{ $this->registrar(6, 0, 0, 'crear_dominio() #5 '.htmlspecialchars($this->con->error)); }
                                    }
                                    if($ressql->{"num_rows"} > 0){
                                        /* ERROR CORREO YA EXISTE */
                                        $info['tipo'] = 3;
                                        $info['mensaje'] = 'Correo existente';
                                    }
                                    $sql->free_result();
                                    $sql->close();
                                }else{ $this->registrar(6, 0, 0, 'crear_dominio() #6 '.htmlspecialchars($sql->error)); }
                            }else{ $this->registrar(6, 0, 0, 'crear_dominio() #6 '.htmlspecialchars($sql->error)); }
                        }else{ $this->registrar(6, 0, 0, 'crear_dominio() #6 '.htmlspecialchars($this->con->error)); }
                    }else{ 
                        /* ERROR CAPTCHA */
                    }
                }else{ 
                    /* NO ES CORREO */
                    $info['tipo'] = 3;
                    $info['mensaje'] = 'Error con correo';
                }
            }else{
                /* TELEFONO ERROR */
                $info['tipo'] = 1;
                $info['mensaje'] = 'Telefono Error';
            }
        }else{
            /* NO ES DOMINIO */
            $info['tipo'] = 2;
            $info['mensaje'] = 'Error con dominio';
        }
        return $info;

    }
    public function enviar_contacto_msd(){

        $info['op'] = 2;
        $info['tipo'] = 0;
        $info['mensaje'] = '';

        $correo = $_POST["correo"];
        $nombre = $_POST["nombre"];
        $telefono = $_POST["telefono"];
        $asunto = $_POST["asunto"];

        if(strlen($nombre) > 2){
            if(strlen($telefono) >= 12 && strlen($telefono) <= 14){
                if(filter_var($correo, FILTER_VALIDATE_EMAIL)){

                    $url = 'https://www.google.com/recaptcha/api/siteverify';
                    $datas = [
                        'secret' => '6LdZp78UAAAAALb66uCWx7RR3cuSjhQLhy8sWZdu',
                        'response' => $_POST['token'],
                        'remoteip' => $_SERVER['REMOTE_ADDR']
                    ];
                    $options = array(
                        'http' => array(
                            'header'  => 'Content-type: application/x-www-form-urlencoded\r\n',
                            'method'  => 'POST',
                            'content' => http_build_query($datas)
                        )
                    );
                    $context  = stream_context_create($options);
                    $response = file_get_contents($url, false, $context);
                    $res = json_decode($response, true);

                    if($res['success'] == true){

                        $send['email'] = $correo;
                        $send['nombre'] = $nombre;
                        $send['telefono'] = $telefono;
                        $send['asunto'] = $asunto;
                        $send['codenodejs'] = $this->codenodejs;

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/mail_contacto');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                        if(!curl_errno($ch)){
                            $resp_email = json_decode(curl_exec($ch));
                            if($resp_email->{'op'} == 1){
                                $info['op'] = 1;
                            }
                            if($resp_email->{'op'} == 2){
                                $info['op'] = 2;
                                $info['tipo'] = 4;
                                $info['mensaje'] = 'El correo no pudo ser enviado';
                                $this->registrar(15, 0, 0, 'Mail contacto no pudo ser enviado');
                            }
                            curl_close($ch);
                        }

                    }else{
                        $this->registrar(15, 0, 0, 'Error reCaptcha v3 conctanto');
                    }

                }else{ 
                    /* NO ES CORREO */ 
                    $info['tipo'] = 3;
                    $info['mensaje'] = 'Correo invalido';
                }
            }else{ 
                /* NO ES TELEFONO */ 
                $info['tipo'] = 1;
                $info['mensaje'] = 'Telefono invalido';
            }
        }else{ 
            /* NO ES NOMBRE */ 
            $info['tipo'] = 2;
            $info['mensaje'] = 'Debe ingresar nombre';
        }
        return $info;

    }
    
}
?>