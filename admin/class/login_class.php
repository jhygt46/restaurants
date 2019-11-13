<?php
session_start();

if(strpos($_SERVER["REQUEST_URI"], "login_class.php") !== false){
    header('HTTP/1.1 404 Not Found', true, 404);
    include('../../errors/404.html');
    exit;
}

require_once DIR."db.php";
require_once DIR_BASE."config/config.php";

date_default_timezone_set('America/Santiago');

class Login {
    
    public $con = null;
    public $eliminado = 0;
    public function __construct(){
        
        global $db_host;
        global $db_user;
        global $db_password;
        global $db_database;

        $this->con = new mysqli($db_host[0], $db_user[0], $db_password[0], $db_database[0]);
        
    }
    public function recuperar_password(){

        $user = $_POST['user'];
        $info['op'] = 2;
        $info['message'] = "Error:";
        if(filter_var($user, FILTER_VALIDATE_EMAIL)){
            if($sql = $this->con->prepare("SELECT * FROM fw_usuarios WHERE correo=? AND eliminado=?")){
                if($sql->bind_param("si", $user, $this->eliminado)){
                    if($sql->execute()){
                        $res = $sql->get_result();
                        $aux_user = $res->fetch_all(MYSQLI_ASSOC)[0];
                        $id_user = $aux_user["id_user"];
                        $correo = $aux_user["correo"];
                        $acciones = $this->acciones($id_user, 2);
                        if($acciones < 1){
                            if($res->{"num_rows"} == 1){
                                $tipo = 2;
                                if($sqlia = $this->con->prepare("INSERT INTO fw_acciones (tipo, fecha, id_user) VALUES (?, now(), ?)")){
                                    if($sqlia->bind_param("ii", $tipo, $id_user)){
                                        if($sqlia->execute()){
                                            $send['correo'] = $correo;
                                            $send['code'] = $this->pass_generate(20);
                                            $send['id'] = $id_user;
                                            if($sqluu = $this->con->prepare("UPDATE fw_usuarios SET pass='', mailcode=? WHERE id_user=? AND eliminado=?")){
                                                if($sqluu->bind_param("sii", $send["code"], $send["id"], $this->eliminado)){
                                                    if($sqluu->execute()){
                                                        $ch = curl_init();
                                                        curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/mail_recuperar');
                                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                                                        $resp = json_decode(curl_exec($ch));
                                                        if($resp->{'op'} == 1){
                                                            $info['op'] = 1;
                                                            $info['message'] = "Correo Enviado";
                                                        }else{
                                                            $info['op'] = 2;
                                                            $info['message'] = "Error";
                                                            $this->registrar('13', 0, 0, 0, 'email no enviado:');
                                                        }
                                                        curl_close($ch);
                                                        $sqluu->close();
                                                    }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sqluu->error); }
                                                }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sqluu->error); }
                                            }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$this->con->error); }
                                            $sqlia->close();
                                        }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sqlia->error); }
                                    }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sqlia->error); }
                                }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$this->con->error); }
                            }
                            if($res->{"num_rows"} == 0){
                                $info['op'] = 2;
                                $info['message'] = "Error:";
                                $this->registrar('13', 0, 0, 0, 'usuario no encontrado: '.$user);
                            }
                        }else{
                            $info['op'] = 2;
                            $info['message'] = "Error: El correo ya ha sido enviado";
                            $this->registrar('13', 0, 0, 0, 'demaciadas acciones usuario: '.$user);
                        }
                        $sql->free_result();
                        $sql->close();
                    }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sql->error); }
                }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sql->error); }
            }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$this->con->error); }
        }else{
            $info['op'] = 2;
            $info['message'] = "Error: debe ingresar correo valido";
            $this->registrar('13', 0, 0, 0, 'email invalido usuario: '.$user);
        }
        return $info; 

    }
    public function nueva_password(){

        $id = $_POST['id'];
        $code = $_POST['code'];
        if($sqlb = $this->con->prepare("SELECT * FROM fw_usuarios WHERE id_user=? AND mailcode=? AND eliminado=?")){
            if($sqlb->bind_param("is", $id, $code, $this->eliminado)){
                if($sqlb->execute()){
                    $resb = $sqlb->get_result();
                    if($resb->{"num_rows"} == 1){
                        $acciones = $this->acciones($id, 3);
                        if($acciones < 5){
                            $pass_01 = $_POST['pass_01'];
                            $pass_02 = $_POST['pass_02'];
                            if(strlen($pass_01) >= 8){
                                if($pass_01 == $pass_02){
                                    if($sql = $this->con->prepare("UPDATE fw_usuarios SET mailcode='', pass=? WHERE id_user=? AND eliminado=?")){
                                        if($sql->bind_param("sii", md5($pass_01), $id, $this->eliminado)){
                                            if($sql->execute()){
                                                $info['op'] = 1;
                                                $info['url'] = "";
                                                $info['message'] = "Felicidades! se ha creado su password";
                                                $sql->close();
                                            }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sql->error); }
                                        }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sql->error); }
                                    }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$this->con->error); }
                                }else{
                                    $info['op'] = 2;
                                    $info['message'] = "Error: password diferentes";
                                }
                            }else{
                                $info['op'] = 2;
                                $info['message'] = "Error: password debe tener mas de 8 caracteres";
                            }
                        }else{
                            $info['op'] = 2;
                            $info['message'] = "Error: Demaciados intentos";
                        }
                    }
                    if($resb->{"num_rows"} == 0){
                        $tipo = 3;
                        if($sql = $this->con->prepare("INSERT INTO fw_acciones (tipo, fecha, id_user) VALUES (?, now(), ?)")){
                            if($sql->bind_param("ii", $tipo, $id)){
                                if($sql->execute()){
                                    $info['op'] = 2;
                                    $info['message'] = "Error: usuario y codigo";
                                    $sql->close();
                                    $this->registrar('14', 0, 0, 0, 'new password id - code diferentes: ');
                                }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sql->error); }
                            }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sql->error); }
                        }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$this->con->error); }
                    }
                    $sqlb->free_result();
                    $sqlb->close();
                }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sqlb->error); }
            }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sqlb->error); }
        }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$this->con->error); }
        return $info;

    }
    private function registrar($id_des, $id_user, $id_loc, $id_gir, $txt){

        $sqlipd = $this->con->prepare("INSERT INTO seguimiento (id_des, id_user, id_loc, id_gir, fecha, txt) VALUES (?, ?, ?, ?, now(), ?)");
        $sqlipd->bind_param("iiiis", $id_des, $id_user, $id_loc, $id_gir, $txt);
        $sqlipd->execute();
        $sqlipd->close();

    }
    public function acciones($id_user, $tipo){
        if($sql = $this->con->prepare("SELECT * FROM fw_acciones WHERE id_user=? AND tipo=? AND fecha > DATE_ADD(NOW(), INTERVAL -1 DAY)")){
            if($sql->bind_param("ii", $id_user, $tipo)){
                if($sql->execute()){
                    $res = $sql->get_result();
                    $sql->free_result();
                    $sql->close();
                    return $res->{"num_rows"};
                }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sql->error); }
            }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sql->error); }
        }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$this->con->error); }
    }
    public function login_back(){

        if(filter_var($_POST['user'], FILTER_VALIDATE_EMAIL)){
            if($sqlu = $this->con->prepare("SELECT * FROM fw_usuarios WHERE correo=? AND eliminado=?")){
                if($sqlu->bind_param("si", $_POST["user"], $this->eliminado)){
                    if($sqlu->execute()){

                        $res = $sqlu->get_result();

                        if($res->{"num_rows"} == 0){
                            $info['op'] = 2;
                            $info['message'] = "Error: Correo o Contraseña invalida";
                            $this->registrar('12', 0, 0, 0, 'usuario no existe: '.$_POST["user"]);
                        }

                        if($res->{"num_rows"} == 1){

                            $result = $res->fetch_all(MYSQLI_ASSOC)[0];
                            $id_gir = $result["id_gir"];
                            $acciones = $this->acciones($result["id_user"], 1);

                            if($acciones < 5){

                                $pass = $result['pass'];
                                $id_user = $result['id_user'];
                                if($pass == md5($_POST['pass'])){

                                    if($result['id_loc'] > 0){
                                        if($sqlsg = $this->con->prepare("SELECT t1.code as local_code, t2.code as giro_code, t2.ssl, t2.dominio, t2.dns, t1.id_loc, t2.id_gir, t1.fecha_cocina FROM locales t1, giros t2 WHERE t1.id_loc=? AND t1.id_gir=t2.id_gir AND t1.eliminado=? AND t2.eliminado=?")){
                                            if($sqlsg->bind_param("iii", $result['id_loc'], $this->eliminado, $this->eliminado)){
                                                if($sqlsg->execute()){
                                                    $res_glocal = $sqlsg->get_result()->fetch_all(MYSQLI_ASSOC)[0];

                                                    $info['data'] = 'https://misitiodelivery.cl/data/'.$res_glocal["giro_code"].'.js';
                                                    if($result['tipo'] == 0){

                                                        // PUNTO DE VENTA
                                                        $this->registrar('10', $result['id_user'], $res_glocal['id_loc'], $res_glocal['id_gir'], '');
                                                        $info['op'] = 3;
                                                        $info['url'] = 'admin/punto_de_venta/';
                                                        $info['message'] = "Ingreso Exitoso Punto de Venta";
                                                        $code_cookie_user = $this->pass_generate(60);
                                                        $code_cookie_local = $this->pass_generate(60);
                                                        $ip = $this->getUserIpAddr();
                                                        $info['id'] = $result['id_user'];
                                                        $info['user_code'] = $code_cookie_user;
                                                        $info['local_code'] = $code_cookie_local;
                                                        
                                                        $code_local = $this->pass_generate(20);
                                                        $info['code'] = $code_local;
                                                        $enviar_cocina = (time() - strtotime($res_glocal['fecha_cocina']) < 57600) ? 0 : 1 ;
                                                        
                                                        if($sqlul = $this->con->prepare("UPDATE locales SET enviar_cocina=?, code=?, cookie_ip=?, cookie_code=? WHERE id_loc=? AND eliminado=?")){
                                                            if($sqlul->bind_param("isssii", $enviar_cocina, $code_local, $ip, $code_cookie_local, $res_glocal['id_loc'], $this->eliminado)){
                                                                if($sqlul->execute()){
                                                                    if($sqluu = $this->con->prepare("UPDATE fw_usuarios SET cookie_code=? WHERE id_user=? AND eliminado=?")){
                                                                        if($sqluu->bind_param("sii", $code_cookie_user, $result['id_user'], $this->eliminado)){
                                                                            if($sqluu->execute()){
                                                                                $sqluu->close();
                                                                            }else{ $this->registrar(6, $result['id_loc'], $id_gir, 'login_back() #1 '.$sqluu->error); }
                                                                        }else{ $this->registrar(6, $result['id_loc'], $id_gir, 'login_back() #1 '.$sqluu->error); }
                                                                    }else{ $this->registrar(6, $result['id_loc'], $id_gir, 'login_back() #1 '.$this->con->error); }
                                                                    $sqlul->close();
                                                                }else{ $this->registrar(6, $result['id_loc'], $id_gir, 'login_back() #2 '.$sqlul->error); }
                                                            }else{ $this->registrar(6, $result['id_loc'], $id_gir, 'login_back() #2 '.$sqlul->error); }
                                                        }else{ $this->registrar(6, $result['id_loc'], $id_gir, 'login_back() #2 '.$this->con->error); }

                                                    }

                                                    if($result['tipo'] == 1){
                                                        // COCINA
                                                        $this->registrar('11', $result['id_user'], $res_glocal['id_loc'], $res_glocal['id_gir'], '');
                                                        $info['op'] = 4;
                                                        $info['url'] = 'admin/cocina/';
                                                        $info['message'] = "Ingreso Exitoso Cocina";
                                                        $info['code'] = $res_glocal["local_code"];

                                                        $enviar_cocina = 1;
                                                        if($sqlul = $this->con->prepare("UPDATE locales SET enviar_cocina=?, fecha_cocina=now() WHERE id_loc=? AND eliminado=?")){
                                                            if($sqlul->bind_param("iii", $enviar_cocina, $res_glocal['id_loc'], $this->eliminado)){
                                                                if($sqlul->execute()){
                                                                    $sqlul->close();
                                                                }else{ $this->registrar(6, $result['id_loc'], $id_gir, 'login_back() #3 '.$sqlul->error); }
                                                            }else{ $this->registrar(6, $result['id_loc'], $id_gir, 'login_back() #3 '.$sqlul->error); }
                                                        }else{ $this->registrar(6, $result['id_loc'], $id_gir, 'login_back() #3 '.$this->con->error); }

                                                    }

                                                    $sqlsg->free_result();
                                                    $sqlsg->close();
                                                }else{ $this->registrar(6, $result['id_loc'], $id_gir, 'login_back() #4 '.$sqlsg->error); }
                                            }else{ $this->registrar(6, $result['id_loc'], $id_gir, 'login_back() #4 '.$sqlsg->error); }
                                        }else{ $this->registrar(6, $result['id_loc'], $id_gir, 'login_back() #4 '.$this->con->error); }
                                    }
                                    if($result['id_loc'] == 0){

                                        $ses['info']['id_user'] = $id_user;
                                        $ses['info']['nombre'] = $result['nombre'];
                                        $ses['info']['admin'] = $result['admin'];
                                        $ses['info']['re_venta'] = $result['re_venta'];
                                        $ses['info']['id_aux_user'] = $result['id_aux_user'];
                                        $ses['id_gir'] = 0;
                                        $ses['id_cat'] = 0;

                                        if($result['admin'] == 0){
                                            if($sqlsug = $this->con->prepare("SELECT id_gir FROM fw_usuarios_giros WHERE id_user=?")){
                                                if($sqlsug->bind_param("i", $id_user)){
                                                    if($sqlsug->execute()){
                                                        $ressug = $sqlsug->get_result();
                                                        if($ressug->{"num_rows"} == 1){
                                                            $ses['id_gir'] = $ressug->fetch_all(MYSQLI_ASSOC)[0]['id_gir'];
                                                        }
                                                        $sqlsug->free_result();
                                                        $sqlsug->close();
                                                    }else{ $this->registrar(6, 0, $id_gir, 'login_back() #5 '.$sqlsug->error); }
                                                }else{ $this->registrar(6, 0, $id_gir, 'login_back() #5 '.$sqlsug->error); }
                                            }else{ $this->registrar(6, 0, $id_gir, 'login_back() #5 '.$this->con->error); }
                                        }

                                        $info['op'] = 1;
                                        $info['message'] = "Ingreso Exitoso";
                                        $_SESSION['user'] = $ses;
                                        $this->registrar('9', $result['id_user'], $res_glocal['id_loc'], $res_glocal['id_gir'], '');
                                        
                                    }
                                }else{

                                    $tipo = 1;
                                    if($sqlic = $this->con->prepare("INSERT INTO fw_acciones (tipo, fecha, id_user) VALUES (?, now(), ?)")){
                                        if($sqlic->bind_param("ii", $tipo, $id_user)){
                                            if($sqlic->execute()){
                                                $sqlic->close();
                                                $info['op'] = 2;
                                                $info['message'] = "Error: Correo o Contraseña invalida";
                                            }else{ $this->registrar(6, 0, $id_gir, 'login_back() #6 '.$sqlic->error); }
                                        }else{ $this->registrar(6, 0, $id_gir, 'login_back() #6 '.$sqlic->error); }
                                    }else{ $this->registrar(6, 0, $id_gir, 'login_back() #6 '.$this->con->error); }
                                
                                }
                            }else{
                                $info['op'] = 2;
                                $info['message'] = "Error: Demaciados intentos";
                                $this->registrar('12', 0, 0, 0, 'demaciados intentos:');
                            }
                        }
                        $sqlu->free_result();
                        $sqlu->close();
                    }else{ $this->registrar(6, 0, 0, 'login_back() #7 '.$sqlu->error); }
                }else{ $this->registrar(6, 0, 0, 'login_back() #7 '.$sqlu->error); }
            }else{ $this->registrar(6, 0, 0, 'login_back() #7 '.$this->con->error); }
        }else{
            $info['op'] = 2;
            $info['message'] = "Error: Correo o Contraseña invalida";
            $this->registrar('12', 0, 0, 0, 'correo invalido:');
        }
        return $info;  
        
    }
    public function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    public function pass_generate($n){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for($i=0; $i<$n; $i++){
            $r .= $chars{rand(0, strlen($chars)-1)};
        }
        return $r;
    }

}

?>

