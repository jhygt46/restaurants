<?php
date_default_timezone_set('America/Santiago');
require_once($path_class."mysql_class.php");

class Login {
    
    public $con = null;
    
    public function __construct(){
        $this->con = new Conexion();
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
                        
                        $ses['info']['id_user'] = $user['resultado'][0]['id_user'];
                        $ses['info']['nombre'] = $user['resultado'][0]['nombre'];
                        $ses['info']['admin'] = $user['resultado'][0]['admin'];
                        
                        if($user['resultado'][0]['admin'] == 0){
                            $giros = $this->con->sql("SELECT t2.id_gir, t2.nombre FROM fw_usuarios_giros_clientes t1, giros t2 WHERE t1.id_user='".$user['resultado'][0]['id_user']."' AND t1.id_gir=t2.id_gir");
                            $ses['giro'] = $giros['resultado'][0];
                        }
                        if($user['resultado'][0]['admin'] == 1){
                            $giros = $this->con->sql("SELECT t2.id_gir, t2.nombre FROM fw_usuarios_giros t1, giros t2 WHERE t1.id_user='".$user['resultado'][0]['id_user']."' AND t1.id_gir=t2.id_gir");
                            $ses['giros'] = $giros['resultado'];
                            $ses['giro']['id_gir'] = 0;
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

