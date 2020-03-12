<?php
session_start();

esconder("login_class.php");

require_once $url["dir"]."db.php";
require_once $url["dir_base"]."config/config.php";

date_default_timezone_set('America/Santiago');

class Login {
    
    public $con = null;
    public $eliminado = 0;
    public $cookie_secure = 0;
    public $cookie_httponly = 0;

    public function __construct(){
        
        global $db_host;
        global $db_user;
        global $db_password;
        global $db_database;
        global $cookie_secure;
        global $cookie_httponly;

        $this->cookie_secure = $cookie_secure;
        $this->cookie_httponly = $cookie_httponly;

        $this->con = new mysqli($db_host[0], $db_user[0], $db_password[0], $db_database[0]);
        
    }

    // FUNCCIONES IMPORTANTES //
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
                $acciones = $this->get_acciones($id_user, 2);

                if($acciones < 1){
                    if($res->{"num_rows"} == 1){

                        $this->set_acciones($id_user, 2);
                        $send['correo'] = $correo;
                        $send['code'] = $this->pass_generate(20);
                        $send['id'] = $id_user;

                        if($sqlu = $this->con->prepare("UPDATE fw_usuarios SET mailcode=? WHERE id_user=? AND eliminado=?")){
                        if($sqlu->bind_param("sii", $send["code"], $send["id"], $this->eliminado)){
                        if($sqlu->execute()){

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
                            $sqlu->close();
                            
                        }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sqlu->error); }
                        }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sqlu->error); }
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

                $acciones = $this->get_acciones($id, 3);
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

                $this->set_acciones($id, 3);
                $info['op'] = 2;
                $info['message'] = "Error: usuario y codigo";

            }
            $sqlb->free_result();
            $sqlb->close();
        }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sqlb->error); }
        }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$sqlb->error); }
        }else{ $this->registrar(6, 0, 0, 'ins usuarios '.$this->con->error); }
        return $info;

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
                }

                if($res->{"num_rows"} == 1){

                    $result = $res->fetch_all(MYSQLI_ASSOC)[0];
                    $id_user = $result['id_user'];
                    $pass = $result['pass'];
                    $id_gir = $result["id_gir"];
                    $id_loc = $result["id_loc"];
                    $admin = $result["admin"];
                    
                    $acciones = $this->get_acciones($id_user, 1);

                    if($acciones < 5){
                        if($pass == md5($_POST['pass'])){
                            if($id_loc > 0){

                                $tiempo = time() + 16 * 60 * 60;
                                if($sqlsg = $this->con->prepare("SELECT t1.code as local_code, t2.code as giro_code, t2.ssl, t2.dominio, t2.dns, t1.id_loc, t2.id_gir, t1.fecha_cocina FROM locales t1, giros t2 WHERE t1.id_loc=? AND t1.id_gir=t2.id_gir AND t1.eliminado=? AND t2.eliminado=?")){
                                if($sqlsg->bind_param("iii", $id_loc, $this->eliminado, $this->eliminado)){
                                if($sqlsg->execute()){
                                    
                                    $res_glocal = $sqlsg->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                                    $giro_code = $res_glocal["giro_code"];
                                    $local_code = $res_glocal["local_code"];
                                    
                                    setcookie('giro_code', $giro_code, $tiempo, '/', '', $this->cookie_secure, $this->cookie_httponly);
                                    $info['local_code'] = $local_code;

                                    if($result['tipo'] == 0){
                                        // PUNTO DE VENTA
                                        $info['tipo'] = 2;
                                        $cookie_pos = $this->pass_generate(60);
                                        if($sql = $this->con->prepare("UPDATE fw_usuarios SET cookie_pos=? WHERE id_user=?)")){
                                        if($sql->bind_param("si", $cookie_pos, $id_user)){
                                        if($sql->execute()){

                                            $info['op'] = 1;
                                            $info['message'] = "Ingreso Exitoso, redireccionando...";
                                            setcookie('user_id', $id_user, $tiempo, '/', '', $this->cookie_secure, $this->cookie_httponly);
                                            setcookie('cookie_pos', $cookie_pos, $tiempo, '/', '', $this->cookie_secure, $this->cookie_httponly);
                                            $sql->close();

                                        }else{ $this->registrar(6, $id_loc, $id_gir, 'login_pos() #1a '.$sql->error); }
                                        }else{ $this->registrar(6, $id_loc, $id_gir, 'login_pos() #1b '.$sql->error); }
                                        }else{ $this->registrar(6, $id_loc, $id_gir, 'login_pos() #1c '.$this->con->error); }

                                    }
                                    if($result['tipo'] == 1){
                                        // COCINA
                                        $info['tipo'] = 3;
                                        $cookie_coc = $this->pass_generate(60);
                                        if($sql = $this->con->prepare("UPDATE fw_usuarios SET cookie_coc=? WHERE id_user=?)")){
                                        if($sql->bind_param("si", $cookie_coc, $id_user)){
                                        if($sql->execute()){
                                            
                                            $info['op'] = 1;
                                            $info['message'] = "Ingreso Exitoso, redireccionando...";
                                            setcookie('user_id', $id_user, $tiempo, '/', '', $this->cookie_secure, $this->cookie_httponly);
                                            setcookie('cookie_coc', $cookie_coc, $tiempo, '/', '', $this->cookie_secure, $this->cookie_httponly);
                                            $sql->close();
                                            
                                        }else{ $this->registrar(6, $id_loc, $id_gir, 'login_cocina() #1a '.$sql->error); }
                                        }else{ $this->registrar(6, $id_loc, $id_gir, 'login_cocina() #1b '.$sql->error); }
                                        }else{ $this->registrar(6, $id_loc, $id_gir, 'login_cocina() #1c '.$this->con->error); }

                                    }

                                    $sqlsg->free_result();
                                    $sqlsg->close();

                                }else{ $this->registrar(6, $id_loc, $id_gir, 'login_back() #4 '.$sqlsg->error); }
                                }else{ $this->registrar(6, $id_loc, $id_gir, 'login_back() #4 '.$sqlsg->error); }
                                }else{ $this->registrar(6, $id_loc, $id_gir, 'login_back() #4 '.$this->con->error); }
                            
                            }

                            if($id_loc == 0){

                                $info['tipo'] = 1;
                                $cookie_code = $this->pass_generate(60);
                                $tiempo = ($_POST["recordar"] == 1) ? time() + 3*365*24*60*60 : 0 ;
                                if($sql = $this->con->prepare("UPDATE fw_usuarios SET cookie_code=? WHERE id_user=?")){
                                if($sql->bind_param("si", $cookie_code, $id_user)){
                                if($sql->execute()){
                                    
                                    $info['op'] = 1;
                                    $info['message'] = "Ingreso Exitoso, redireccionando...";

                                    if($admin == 0){
                                        if($sqlf = $this->con->prepare("SELECT * FROM fw_usuarios_giros WHERE id_user=?")){
                                            if($sqlf->bind_param("i", $id_user)){
                                                if($sqlf->execute()){
                                                    $res = $sqlf->get_result();
                                                    if($res->{"num_rows"} == 1){
                                                        $giro_id = $sqlf->get_result()->fetch_all(MYSQLI_ASSOC)[0]["id_gir"];
                                                        setcookie('giro_id', $giro_id, 0, '/', '', $this->cookie_secure, $this->cookie_httponly);
                                                    }
                                                    $sql->free_result();
                                                    $sql->close();
                                                }else{ $this->registrar(6, 0, $id_gir, 'is_giro() #1 '.$sql->error); }
                                            }else{ $this->registrar(6, 0, $id_gir, 'is_giro() #1 '.$sql->error); }
                                        }else{ $this->registrar(6, 0, $id_gir, 'is_giro() #1 '.$this->con->error); }
                                    }

                                    setcookie('user_id', $id_user, $tiempo, '/', '', $this->cookie_secure, $this->cookie_httponly);
                                    setcookie('user_code', $cookie_code, $tiempo, '/', '', $this->cookie_secure, $this->cookie_httponly);
                                    setcookie('user_admin', $admin, $tiempo, '/', '', $this->cookie_secure, $this->cookie_httponly);
                                    $sql->close();

                                }else{ $this->registrar(6, $id_loc, $id_gir, 'login_sistema() #1a '.$sql->error); }
                                }else{ $this->registrar(6, $id_loc, $id_gir, 'login_sistema() #1b '.$sql->error); }
                                }else{ $this->registrar(6, $id_loc, $id_gir, 'login_sistema() #1c '.$this->con->error); }

                            }

                        }else{
                            $this->set_acciones($id_user, 1);
                            $info['op'] = 2;
                            $info['message'] = "Error: Correo o Contraseña invalida";
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
    // FUNCCIONES IMPORTANTES //



    // FUNCCIONES COMPLEMENTARIAS //
    private function registrar($id_des, $id_user, $id_loc, $id_gir, $txt){

        $sqlipd = $this->con->prepare("INSERT INTO seguimiento (id_des, id_user, id_loc, id_gir, fecha, txt) VALUES (?, ?, ?, ?, now(), ?)");
        $sqlipd->bind_param("iiiis", $id_des, $id_user, $id_loc, $id_gir, $txt);
        $sqlipd->execute();
        $sqlipd->close();

    }
    private function get_acciones($id_user, $tipo){
        if($sql = $this->con->prepare("SELECT * FROM fw_acciones WHERE id_user=? AND tipo=? AND fecha > DATE_ADD(NOW(), INTERVAL -1 DAY)")){
            if($sql->bind_param("ii", $id_user, $tipo)){
                if($sql->execute()){
                    $res = $sql->get_result();
                    $sql->free_result();
                    $sql->close();
                    return $res->{"num_rows"};
                }else{ $this->registrar(6, 0, 0, 'get_acciones() #1a '.$sql->error); }
            }else{ $this->registrar(6, 0, 0, 'get_acciones() #1b '.$sql->error); }
        }else{ $this->registrar(6, 0, 0, 'get_acciones() #1c '.$this->con->error); }
    }
    private function set_acciones($id_user, $tipo){
        if($sql = $this->con->prepare("INSERT INTO fw_acciones (tipo, fecha, id_user) VALUES (?, now(), ?)")){
            if($sql->bind_param("ii", $tipo, $id_user)){
                if($sql->execute()){
                    $sql->close();
                }else{ $this->registrar(6, 0, 0, 'set_acciones() #1a '.$sql->error); }
            }else{ $this->registrar(6, 0, 0, 'set_acciones() #1b '.$sql->error); }
        }else{ $this->registrar(6, 0, 0, 'set_acciones() #1c '.$this->con->error); }
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
    private function pass_generate($n){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for($i=0; $i<$n; $i++){
            $r .= $chars{rand(0, strlen($chars)-1)};
        }
        return $r;
    }
    // FUNCCIONES COMPLEMENTARIAS //

}

?>

