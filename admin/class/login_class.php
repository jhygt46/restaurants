<?php
session_start();

date_default_timezone_set('America/Santiago');
require_once "/var/www/html/config/config.php";

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
        
        if(filter_var($_POST['user'], FILTER_VALIDATE_EMAIL)){

            $sqlsg = $this->con->prepare("SELECT * FROM fw_usuarios WHERE correo=? AND eliminado=?");
            $sqlsg->bind_param("si", $user, $this->eliminado);
            $sqlsg->execute();
            $sqlsg->store_result();
            $id_user = $sqlsg->get_result()->fetch_all(MYSQLI_ASSOC)[0]["id_user"];


            $sql = $this->con->prepare("SELECT * FROM fw_acciones WHERE id_user=? AND tipo='3' AND fecha > DATE_ADD(NOW(), INTERVAL -1 DAY)");
            $sql->bind_param("i", $id_user);
            $sql->execute();
            $sql->store_result();
            if($sql->{"num_rows"} < 1){

                if($sqlsg->{"num_rows"} == 1){

                    $info['op'] = 1;
                    $info['message'] = "Correo Enviado";
                    // 1 INGRESAR
                    // 2 ERRAR
                    // 3 PEDIR PASSWORD

                    $tipo = 3;
                    $sqlia = $this->con->prepare("INSERT INTO fw_acciones (tipo, fecha, id_user) VALUES (?, now(), ?)");
                    $sqlia->bind_param("ii", $tipo, $id_user);
                    $sqlia->execute();
                    $sqlia->close();

                    // CURL 
                    $send['correo'] = $user;
                    $send['code'] = bin2hex(openssl_random_pseudo_bytes(10));
                    $send['id'] = $id_user;


                    $sqluu = $this->con->prepare("UPDATE fw_usuarios SET pass='', mailcode=? WHERE id_user=? AND eliminado=?");
                    $sqluu->bind_param("sii", $send["code"], $send["id"], $this->eliminado);
                    $sqluu->execute();
                    $sqluu->close();

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/mail_recuperar');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                    curl_exec($ch);
                    curl_close($ch);

                }
                if($sqlsg->{"num_rows"} == 0){
                    $info['op'] = 2;
                    $info['message'] = "Error:";
                }
                $sqlsg->free_result();
                $sqlsg->close();

            }else{
                $info['op'] = 2;
                $info['message'] = "Error: El correo ya ha sido enviado";
            }
            
            $sql->free_result();
            $sql->close();

        }else{
            $info['op'] = 2;
            $info['message'] = "Error: debe ingresar correo valido";
        }
        return $info; 

    }
    public function nueva_password(){

        $id = $_POST['id'];
        $code = $_POST['code'];
        $pass_01 = $_POST['pass_01'];
        $pass_02 = $_POST['pass_02'];

        $sqla = $this->con->prepare("SELECT * FROM fw_acciones WHERE id_user=? AND tipo='3' AND fecha > DATE_ADD(NOW(), INTERVAL -1 DAY)");
        $sqla->bind_param("i", $id);
        $sqla->execute();
        $sqla->store_result();
        $acciones = $sqla->{"num_rows"};
        $sqla->free_result();
        $sqla->close();

        $sqlb = $this->con->prepare("SELECT * FROM fw_usuarios WHERE id_user=? AND mailcode=? AND eliminado=?");
        $sqlb->bind_param("is", $id, $code, $this->eliminado);
        $sqlb->execute();
        $sqlb->store_result();
        $usuario = $sqlb->{"num_rows"};
        $sqlb->free_result();
        $sqlb->close();

        if($usuario == 1){
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
                        }
                        $sql->close();
                        
                    }else{
                        $info['op'] = 2;
                        $info['message'] = "Error: password diferentes";
                    }
                }else{
                    $info['op'] = 2;
                    $info['message'] = "Error: assword debe tener mas de 8 caracteres";
                }
            }else{
                $info['op'] = 2;
                $info['message'] = "Error: Demaciados intentos";
            }  
        }else{

            $info['op'] = 2;
            $info['message'] = "Error: usuario y codigo";
            
            // 1 INGRESAR
            // 2 ERRAR
            // 3 PEDIR PASSWORD
            $tipo = 2;
            $sql = $this->con->prepare("INSERT INTO fw_acciones (tipo, fecha, id_user) VALUES (?, now(), ?)");
            $sql->bind_param("ii", $tipo, $id);
            $sql->execute();
            $sql->close();

        }
        
        return $info;

    }
    public function login_back(){

        if(filter_var($_POST['user'], FILTER_VALIDATE_EMAIL)){
            
            $sqlu = $this->con->prepare("SELECT * FROM fw_usuarios WHERE correo=? AND eliminado=?");
            $sqlu->bind_param("si", $_POST["user"], $this->eliminado);
            $sqlu->execute();
            $res = $sqlu->get_result();
            $result = $res->fetch_all(MYSQLI_ASSOC)[0];
            $usuario = $res->{"num_rows"};
            $sqlu->free_result();
            $sqlu->close();


            $sqla = $this->con->prepare("SELECT * FROM fw_acciones WHERE id_user=? AND tipo='2' AND fecha > DATE_ADD(NOW(), INTERVAL -2 DAY)");
            $sqla->bind_param("i", $id_user);
            $sqla->execute();
            $res = $sqla->get_result();
            $acciones = $res->{"num_rows"};
            $sqla->free_result();
            $sqla->close();

            if($acciones < 5){

                if($usuario == 0){
                    $info['op'] = 2;
                    $info['message'] = "Error: Correo o Contraseña invalida";
                }

                if($usuario == 1){

                    $pass = $result['pass'];
                    $id_user = $result['id_user'];

                    if($pass == md5($_POST['pass'])){

                        if($result['id_loc'] > 0){

                            $sqlsg = $this->con->prepare("SELECT t1.code as local_code, t2.code as giro_code, t2.ssl, t2.dominio, t2.dns FROM locales t1, giros t2 WHERE t1.id_loc=? AND t1.id_gir=t2.id_gir AND t1.eliminado=? AND t2.eliminado=?");
                            $sqlsg->bind_param("iii", $result['id_loc'], $this->eliminado, $this->eliminado);
                            $sqlsg->execute();
                            $res_glocal = $sqlsg->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                            $info['local_code'] = $res_glocal["local_code"];
                            
                            if($res_glocal['dns'] == 1 && $res_glocal['ssl'] == 1){
                                $info['data'] = 'https://'.$res_glocal['dominio'].'/data/'.$res_glocal["giro_code"].'/index.js';
                            }else{
                                $info['data'] = 'https://misitiodelivery.cl/data/'.$res_glocal["giro_code"].'.js';
                            }

                            $sqlsg->free_result();
                            $sqlsg->close();

                            if($result['tipo'] == 0){
                                
                                // PUNTO DE VENTA
                                $info['op'] = 3;
                                $info['url'] = 'admin/punto_de_venta/';
                                $info['message'] = "Ingreso Exitoso Punto de Venta";
                                $code_cookie = bin2hex(openssl_random_pseudo_bytes(30));
                                
                                $info['id'] = $result['id_user'];
                                $info['user_code'] = $code_cookie;

                                $sqlul = $this->con->prepare("UPDATE fw_usuarios SET cookie_code=? WHERE id_user=? AND eliminado=?");
                                $sqlul->bind_param("sii", $code_cookie, $result['id_user'], $this->eliminado);
                                $sqlul->execute();
                                $sqlul->close();

                            }
                            if($result['tipo'] == 1){
                                
                                // COCINA
                                $info['op'] = 4;
                                $info['url'] = 'admin/cocina/';
                                $info['message'] = "Ingreso Exitoso Cocina";

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
                                $sqlsug->store_result();
                                if($sqlsug->{"num_rows"} == 1){
                                    $ses['id_gir'] = $sqlsug->get_result()->fetch_all(MYSQLI_ASSOC)[0]['id_gir'];
                                }
                                $sqlsug->free_result();
                                $sqlsug->close();

                            }

                            $_SESSION['user'] = $ses;
                            $info['op'] = 1;
                            $info['message'] = "Ingreso Exitoso";
                            
                        }

                        // 1 INGRESAR
                        // 2 ERRAR
                        // 3 PEDIR PASSWORD

                        $tipo = 1;
                        $sqlia = $this->con->prepare("INSERT INTO fw_acciones (tipo, fecha, id_user) VALUES (?, now(), ?)");
                        $sqlia->bind_param("ii", $tipo, $id_user);
                        $sqlia->execute();
                        $sqlia->close();    
                        
                    }else{

                        // 1 INGRESAR
                        // 2 ERRAR
                        // 3 PEDIR PASSWORD

                        $tipo = 2;
                        $sqlic = $this->con->prepare("INSERT INTO fw_acciones (tipo, fecha, id_user) VALUES (?, now(), ?)");
                        $sqlic->bind_param("ii", $tipo, $id_user);
                        $sqlic->execute();
                        $sqlic->close();  

                        $info['op'] = 2;
                        $info['message'] = "Error: Correo o Contraseña invalida";

                    }

                }

            }else{
                $info['op'] = 2;
                $info['message'] = "Error: Demaciados intentos";
            }
        
        }else{
            $info['op'] = 2;
            $info['message'] = "Error: Correo o Contraseña invalida";
        }
        
        return $info;  
        
    }

}

?>

