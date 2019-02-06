<?php

date_default_timezone_set('America/Santiago');
require_once($path."admin/class/mysql_class.php");

class Login {
    
    public $con = null;
    
    public function __construct(){
        $this->con = new Conexion();
    }
    public function recuperar_password(){
        
        $id = $_POST['id'];
        $code = $_POST['code'];
        $pass1 = $_POST['pass1'];
        $pass2 = $_POST['pass2'];
        
        if(isset($id) && is_numeric($id) && $id != 0){
        
            $user = $this->con->sql("SELECT * FROM fw_usuarios WHERE id_user='".$id."'");
            if($user['resultado'][0]['mailcode'] == $code && $pass1 == $pass2 && strlen($pass1) >= 8 && strlen($code) == 20){
                $this->con->sql("UPDATE fw_usuarios SET pass='".md5($pass1)."', mailcode='', usercode='".$code."' WHERE id_user='".$id."'");
                $info['op'] = 1;
                $info['url'] = "http://35.185.64.95/admin/";
            }
        
        }
        return $info;
        
    }
    public function login_back(){
        
        if(filter_var($_POST['user'], FILTER_VALIDATE_EMAIL)){
            
            $user = $this->con->sql("SELECT * FROM fw_usuarios WHERE correo='".$_POST["user"]."' AND eliminado='0'");
            if($user['count'] == 0){
                $info['op'] = 2;
                $info['message'] = "Error: Usuario no existe";
            }
            if($user['count'] == 1){
                
                $block = $user['resultado'][0]['block'];
                
                if($block == 1){
                    $fecha_block = $user['resultado'][0]['fecha_block'];
                    if(strtotime($fecha_block)+86400 < time()){
                        $block = 0;
                        $this->con->sql("UPDATE fw_usuarios SET block='0', intentos='0', fecha_block='' WHERE id_user='".$user['resultado'][0]['id_user']."'");
                        $user['resultado'][0]['intentos'] = 0;
                    }else{
                        $time = strtotime($fecha_block) - time() + 86400;
                        $hrs = @date("H:i:s", $time);
                        $info['op'] = 2;
                        $info['message'] = "Su cuenta esta Bloqueada, se desbloqueara autom&aacute;ticamente en ".$hrs;
                    }
                }
                
                if($block == 0){
                    $pass = $user['resultado'][0]['pass'];
                    if($pass == md5($_POST['pass'])){
                        
                        $id_user = $user['resultado'][0]['id_user'];

                        $ses['info']['id_user'] = $id_user;
                        $ses['info']['nombre'] = $user['resultado'][0]['nombre'];
                        $ses['info']['admin'] = $user['resultado'][0]['admin'];

                        if($user['resultado'][0]['admin'] == 0){
                            $aux_gir = $this->con->sql("SELECT id_gir FROM fw_usuarios_giros WHERE id_user='".$id_user."'");
                            $ses['id_gir'] = $aux_gir['resultado'][0]['id_gir'];
                        }

                        $_SESSION['user'] = $ses;
                        
                        $info['op'] = 1;
                        $info['message'] = "Ingreso Exitoso";
                        
                    }else{
                        $intentos = $user['resultado'][0]['intentos'] + 1;
                        $this->con->sql("UPDATE fw_usuarios SET intentos='".$intentos."' WHERE id_user='".$user['resultado'][0]['id_user']."'");
                        if($intentos > 5){
                            $this->con->sql("UPDATE fw_usuarios SET block='1', fecha_block='".@date('Y-m-d H:i:s')."' WHERE id_user='".$user['resultado'][0]['id_user']."'");
                            $info['op'] = 2;
                            $info['message'] = "Usuario Bloqueado";
                        }else{
                            $int = 6 - $intentos;
                            $info['op'] = 2;
                            $info['message'] = "Contrase&ntilde;a Invalida, le quedan ".$int." intentos";
                        }
                    }
                }
            }
        
        }else{
            $info['op'] = 2;
            $info['message'] = "Error: Correo invalido";
        }
        
        return $info;  
        
    }

}

?>

