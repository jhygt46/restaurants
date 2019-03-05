<?php

date_default_timezone_set('America/Santiago');
require_once($path."admin/class/mysql_class.php");

class Login {
    
    public $con = null;
    
    public function __construct(){
        $this->con = new Conexion();
    }
    public function recuperar_password(){
        
        $user = $_POST['user'];
        $db_user = $this->con->sql("SELECT * FROM fw_usuarios WHERE correo='".$user."' AND eliminado='0'");
        $id_user = $db_user["resultado"][0]["id_user"];
        $intentos = $this->con->sql("SELECT * FROM fw_acciones WHERE id_user='".$id_user."' AND tipo='3' AND fecha > DATE_ADD(NOW(), INTERVAL -1 DAY)");

        if($intentos['count'] < 3){

            if($db_user['count'] == 1){
                
                // 1 INGRESAR
                // 2 ERRAR
                // 3 PEDIR PASSWORD
                $this->con->sql("INSERT INTO fw_acciones (tipo, fecha, id_user) VALUES ('3', now(), '".$id_user."')");

                // CURL 
                $send['correo'] = $user;
                $send['code'] = bin2hex(openssl_random_pseudo_bytes(10));
                $send['id'] = $id_user;

                $this->con->sql("UPDATE fw_usuarios SET pass='', mailcode='".$send["code"]."' WHERE id_user='".$send["id"]."'");

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/mail_recuperar');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                curl_exec($ch);
                curl_close($ch);

                $info['op'] = 1;
                $info['message'] = "Correo Enviado";

            }else{

                $info['op'] = 2;
                $info['message'] = "Error: con correo";

            }

        }else{
            $info['op'] = 2;
            $info['message'] = "Error: El correo ya ha sido enviado";
        }

        return $info; 

    }
    public function nueva_password(){

        $id= $_POST['id'];
        $code= $_POST['code'];
        $pass_01 = $_POST['pass_01'];
        $pass_02 = $_POST['pass_02'];

        $aux = $this->con->sql("SELECT * FROM fw_usuarios WHERE id_user='".$id."' AND mailcode='".$code."'");
        if($aux['count'] == 1){
            $maildate = strtotime($aux['resultado'][0]['maildate']) + 7200;
            $now = time();
            if($now < $maildate){
                if(strlen($pass_01) >= 8){
                    if($pass_01 == $pass_02){
                        $this->con->sql("UPDATE fw_usuarios SET maildate='', mailcode='', pass='".md5($pass_01)."' WHERE id_user='".$id."'");
                        $info['op'] = 1;
                        $info['url'] = "";
                    }else{
                        $info['op'] = 2;
                        $info['message'] = "Error:";
                    }
                }else{
                    $info['op'] = 2;
                    $info['message'] = "Error:";
                }
            }else{
                $info['op'] = 2;
                $info['message'] = "Error:";
            }
        }else{
            $info['op'] = 2;
            $info['message'] = "Error:";
        }

        return $info;

    }
    public function login_back(){

        if(filter_var($_POST['user'], FILTER_VALIDATE_EMAIL)){
            
            $user = $this->con->sql("SELECT * FROM fw_usuarios WHERE correo='".$_POST["user"]."' AND eliminado='0'");
            $id_user = $user["resultado"][0]["id_user"];
            $intentos = $this->con->sql("SELECT * FROM fw_acciones WHERE id_user='".$id_user."' AND tipo='2' AND fecha > DATE_ADD(NOW(), INTERVAL -2 DAY)");

            if($intentos['count'] < 5){

                if($user['count'] == 0){
                    $info['op'] = 2;
                    $info['message'] = "Error: Correo o Contraseña invalida";
                }

                if($user['count'] == 1){

                    $pass = $user['resultado'][0]['pass'];
                    $id_user = $user['resultado'][0]['id_user'];

                    if($pass == md5($_POST['pass'])){

                        $ses['info']['id_user'] = $id_user;
                        $ses['info']['nombre'] = $user['resultado'][0]['nombre'];
                        $ses['info']['admin'] = $user['resultado'][0]['admin'];
                        $ses['info']['re_venta'] = $user['resultado'][0]['re_venta'];
                        $ses['id_gir'] = 0;
                        
                        // 1 INGRESAR
                        // 2 ERRAR
                        // 3 PEDIR PASSWORD
                        $this->con->sql("INSERT INTO fw_acciones (tipo, fecha, id_user) VALUES ('1', now(), '".$id_user."')");

                        if($ses['info']['admin'] == 0){
                            $aux_gir = $this->con->sql("SELECT id_gir FROM fw_usuarios_giros WHERE id_user='".$id_user."'");
                            if($aux_gir['count'] == 1){
                                $ses['id_gir'] = $aux_gir['resultado'][0]['id_gir'];
                            }
                        }

                        $_SESSION['user'] = $ses;
                        
                        $info['op'] = 1;
                        $info['message'] = "Ingreso Exitoso";

                        unset($_COOKIE['ID']);
                        unset($_COOKIE['CODE']);
                        
                    }else{

                        // 1 INGRESAR
                        // 2 ERRAR
                        // 3 PEDIR PASSWORD
                        $this->con->sql("INSERT INTO fw_acciones (tipo, fecha, id_user) VALUES ('2', now(), '".$id_user."')");
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

