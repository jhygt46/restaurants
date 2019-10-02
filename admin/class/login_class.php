<?php
session_start();

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."medici/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."medici/");
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
        if(filter_var($user, FILTER_VALIDATE_EMAIL)){

            $sql = $this->con->prepare("SELECT * FROM fw_usuarios WHERE correo=? AND eliminado=?");
            $sql->bind_param("si", $user, $this->eliminado);
            $sql->execute();
            $res = $sql->get_result();
            $aux_user = $res->fetch_all(MYSQLI_ASSOC)[0];
            $id_user = $aux_user["id_user"];
            $correo = $aux_user["correo"];

            $acciones = $this->acciones($id_user, 2);

            if($acciones < 1){

                if($res->{"num_rows"} == 1){

                    // 1 ERRAR
                    // 2 PEDIR PASSWORD
                    $tipo = 2;
                    $sqlia = $this->con->prepare("INSERT INTO fw_acciones (tipo, fecha, id_user) VALUES (?, now(), ?)");
                    $sqlia->bind_param("ii", $tipo, $id_user);
                    if($sqlia->execute()){

                        // CURL 
                        $send['correo'] = $correo;
                        $send['code'] = bin2hex(openssl_random_pseudo_bytes(10));
                        $send['id'] = $id_user;

                        $sqluu = $this->con->prepare("UPDATE fw_usuarios SET pass='', mailcode=? WHERE id_user=? AND eliminado=?");
                        $sqluu->bind_param("sii", $send["code"], $send["id"], $this->eliminado);
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

                        }else{
                            $info['op'] = 2;
                            $info['message'] = "Error:";
                            $this->registrar('13', 0, 0, 0, 'usuario no cambiado:');
                        }
                        $sqluu->close();

                    }else{

                        $info['op'] = 2;
                        $info['message'] = "Error:";
                        $this->registrar('13', 0, 0, 0, 'acciones no ingresado:');

                    }
                    $sqlia->close();

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
        $pass_01 = $_POST['pass_01'];
        $pass_02 = $_POST['pass_02'];

        $sqlb = $this->con->prepare("SELECT * FROM fw_usuarios WHERE id_user=? AND mailcode=? AND eliminado=?");
        $sqlb->bind_param("is", $id, $code, $this->eliminado);
        $sqlb->execute();
        $resb = $sqlb->get_result();

        if($resb->{"num_rows"} == 1){

            $acciones = $this->acciones($id, 3);
            if($acciones < 5){
                if(strlen($pass_01) >= 8){
                    if($pass_01 == $pass_02){

                        $sql = $this->con->prepare("UPDATE fw_usuarios SET mailcode='', pass=? WHERE id_user=? AND eliminado=?");
                        $sql->bind_param("sii", md5($pass_01), $id, $this->eliminado);
                        if($sql->execute()){

                            $info['op'] = 1;
                            $info['url'] = "";
                            $info['message'] = "Felicidades! se ha creado su password";

                        }else{

                            $info['op'] = 2;
                            $info['message'] = "Error:";
                            $this->registrar('14', 0, 0, 0, 'usuario no modificado: ');

                        }
                        $sql->close();
                        
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
                $this->registrar('14', 0, 0, 0, 'demaciado intentos: ');
            }

        }
        if($resb->{"num_rows"} == 0){

            $info['op'] = 2;
            $info['message'] = "Error: usuario y codigo";
            
            $tipo = 3;
            $sql = $this->con->prepare("INSERT INTO fw_acciones (tipo, fecha, id_user) VALUES (?, now(), ?)");
            $sql->bind_param("ii", $tipo, $id);
            $sql->execute();
            $sql->close();
            $this->registrar('14', 0, 0, 0, 'new password id - code diferentes: ');

        }

        $sqlb->free_result();
        $sqlb->close();
        
        return $info;

    }
    private function registrar($id_des, $id_user, $id_loc, $id_gir, $txt){

        $sqlipd = $this->con->prepare("INSERT INTO seguimiento (id_des, id_user, id_loc, id_gir, fecha, txt) VALUES (?, ?, ?, ?, now(), ?)");
        $sqlipd->bind_param("iiiis", $id_des, $id_user, $id_loc, $id_gir, $txt);
        $sqlipd->execute();
        $sqlipd->close();

    }
    public function acciones($id_user, $tipo){

        $sql = $this->con->prepare("SELECT * FROM fw_acciones WHERE id_user=? AND tipo=? AND fecha > DATE_ADD(NOW(), INTERVAL -1 DAY)");
        $sql->bind_param("ii", $id_user, $tipo);
        $sql->execute();
        $res = $sql->get_result();
        $sql->free_result();
        $sql->close();
        return $res->{"num_rows"};

    }
    public function login_back(){

        if(filter_var($_POST['user'], FILTER_VALIDATE_EMAIL)){

            $acciones = $this->acciones($result["id_user"], 1);

            if($acciones < 5){

                $sqlu = $this->con->prepare("SELECT * FROM fw_usuarios WHERE correo=? AND eliminado=?");
                $sqlu->bind_param("si", $_POST["user"], $this->eliminado);
                $sqlu->execute();
                $res = $sqlu->get_result();
                
                if($res->{"num_rows"} == 0){
                    $info['op'] = 2;
                    $info['message'] = "Error: Correo o Contraseña invalida";
                    $this->registrar('12', 0, 0, 0, 'usuario no existe: '.$_POST["user"]);
                }
                if($res->{"num_rows"} == 1){
                    
                    $result = $res->fetch_all(MYSQLI_ASSOC)[0];
                    
                    $pass = $result['pass'];
                    $id_user = $result['id_user'];

                    if($pass == md5($_POST['pass'])){

                        if($result['id_loc'] > 0){

                            $sqlsg = $this->con->prepare("SELECT t1.code as local_code, t2.code as giro_code, t2.ssl, t2.dominio, t2.dns, t1.id_loc, t2.id_gir FROM locales t1, giros t2 WHERE t1.id_loc=? AND t1.id_gir=t2.id_gir AND t1.eliminado=? AND t2.eliminado=?");
                            $sqlsg->bind_param("iii", $result['id_loc'], $this->eliminado, $this->eliminado);
                            $sqlsg->execute();
                            $res_glocal = $sqlsg->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                            
                            if($res_glocal['dns'] == 1 && $res_glocal['ssl'] == 1){
                                $info['data'] = 'https://'.$res_glocal['dominio'].'/data/'.$res_glocal["giro_code"].'/index.js';
                            }else{
                                $info['data'] = 'https://misitiodelivery.cl/data/'.$res_glocal["giro_code"].'.js';
                            }

                            $sqlsg->free_result();
                            $sqlsg->close();

                            if($result['tipo'] == 0){
                                
                                // PUNTO DE VENTA
                                $this->registrar('10', $result['id_user'], $res_glocal['id_loc'], $res_glocal['id_gir'], '');
                                $info['op'] = 3;
                                $info['url'] = 'admin/punto_de_venta/';
                                $info['message'] = "Ingreso Exitoso Punto de Venta";
                                
                                $code_cookie_user = bin2hex(openssl_random_pseudo_bytes(30));
                                $code_cookie_local = bin2hex(openssl_random_pseudo_bytes(30));
                                
                                $info['id'] = $result['id_user'];
                                $info['user_code'] = $code_cookie_user;
                                $info['local_code'] = $code_cookie_local;
                                $ip = $this->getUserIpAddr();
                                $info['code'] = $res_glocal["local_code"];

                                $sqlul = $this->con->prepare("UPDATE locales SET cookie_ip=?, cookie_code=? WHERE id_loc=? AND eliminado=?");
                                $sqlul->bind_param("ssii", $ip, $code_cookie_local, $res_glocal['id_loc'], $this->eliminado);
                                $sqlul->execute();
                                $sqlul->close();

                                $sqluu = $this->con->prepare("UPDATE fw_usuarios SET cookie_code=? WHERE id_user=? AND eliminado=?");
                                $sqluu->bind_param("sii", $code_cookie_user, $result['id_user'], $this->eliminado);
                                $sqluu->execute();
                                $sqluu->close();

                            }
                            if($result['tipo'] == 1){
                                
                                // COCINA
                                $this->registrar('11', $result['id_user'], $res_glocal['id_loc'], $res_glocal['id_gir'], '');
                                $info['op'] = 4;
                                $info['url'] = 'admin/cocina/';
                                $info['message'] = "Ingreso Exitoso Cocina";
                                $info['code'] = $res_glocal["local_code"];

                            }

                        }else{

                            $ses['info']['id_user'] = $id_user;
                            $ses['info']['nombre'] = $result['nombre'];
                            $ses['info']['admin'] = $result['admin'];
                            $ses['info']['re_venta'] = $result['re_venta'];
                            $ses['info']['id_aux_user'] = $result['id_aux_user'];
                            $ses['id_gir'] = 0;
                            $ses['id_cat'] = 0;

                            if($result['admin'] == 0){

                                $sqlsug = $this->con->prepare("SELECT id_gir FROM fw_usuarios_giros WHERE id_user=?");
                                $sqlsug->bind_param("i", $id_user);
                                $sqlsug->execute();
                                $ressug = $sqlsug->get_result();
                                if($ressug->{"num_rows"} == 1){
                                    $ses['id_gir'] = $ressug->fetch_all(MYSQLI_ASSOC)[0]['id_gir'];
                                }
                                $sqlsug->free_result();
                                $sqlsug->close();

                            }

                            $info['op'] = 1;
                            $info['message'] = "Ingreso Exitoso";
                            $_SESSION['user'] = $ses;
                            $this->registrar('9', $result['id_user'], $res_glocal['id_loc'], $res_glocal['id_gir'], '');
                            
                        }
                        
                    }else{

                        // 1 ERRAR
                        // 2 PEDIR PASSWORD

                        $tipo = 1;
                        $sqlic = $this->con->prepare("INSERT INTO fw_acciones (tipo, fecha, id_user) VALUES (?, now(), ?)");
                        $sqlic->bind_param("ii", $tipo, $id_user);
                        $sqlic->execute();
                        $sqlic->close();  

                        $info['op'] = 2;
                        $info['message'] = "Error: Correo o Contraseña invalida";
                        $this->registrar('12', $id_user, 0, $result['id_gir'], 'pass diferente');

                    }

                }

                $sqlu->free_result();
                $sqlu->close();

            }else{
                $info['op'] = 2;
                $info['message'] = "Error: Demaciados intentos";
                $this->registrar('12', 0, 0, 0, 'demaciados intentos:');
            }
        
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

}

?>

