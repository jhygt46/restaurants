<?php

session_start();
require_once "/var/www/html/config/config.php";

class Core{
    
    public $con = null;
    public $id_user = null;
    public $admin = null;
    public $re_venta = null;
    public $id_aux_user = null;
    public $id_gir = null;
    public $id_cat = null;
    public $eliminado = 0;

    public function __construct(){

        global $db_host;
        global $db_user;
        global $db_password;
        global $db_database;

        $this->con = new mysqli($db_host[0], $db_user[0], $db_password[0], $db_database[0]);
        $this->id_user = $_SESSION['user']['info']['id_user'];
        $this->admin = $_SESSION['user']['info']['admin'];
        $this->re_venta = $_SESSION['user']['info']['re_venta'];
        $this->id_aux_user = $_SESSION['user']['info']['id_aux_user'];
        $this->id_gir = $_SESSION['user']['id_gir'];
        $this->id_cat = $_SESSION['user']['id_cat'];
        
    }
    public function is_giro(){

        $id_gir = intval($_GET["id_gir"]); 
        if($this->admin == 0){
            $sql = $this->con->prepare("SELECT * FROM fw_usuarios_giros WHERE id_gir=? AND id_user=?");
            $sql->bind_param("ii", $id_gir, $this->id_user);
            $sql->execute();
            $sql->store_result();
            if($sql->{"num_rows"} == 1){
                $this->id_gir = $id_gir;
                $_SESSION['user']['id_gir'] = $id_gir;
            }else{
                die("ERROR: #A101");
            }
            $sql->close();
        }
        if($this->admin == 1){
            $id_gir = 134;
            $sql = $this->con->prepare("SELECT * FROM fw_usuarios_giros_clientes WHERE id_gir=? AND id_user=?");
            $sql->bind_param("ii", $id_gir, $this->id_user);
            $sql->execute();
            $sql->store_result();
            if($sql->{"num_rows"} == 1 || $this->id_user == 1){
                $this->id_gir = $id_gir;
                $_SESSION['user']['id_gir'] = $id_gir;
            }else{
                die("ERROR: #A102");
            }
            $sql->free_result();
            $sql->close();
        }

    }
    public function get_locales(){

        $sql = $this->con->prepare("SELECT * FROM locales WHERE id_gir=? AND eliminado=?");
        $sql->bind_param("ii", $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_giros_user(){
        
        if($this->admin == 1 && $this->id_user > 1){

            $sql = $this->con->prepare("SELECT t2.id_gir, t2.nombre, t2.dominio FROM fw_usuarios_giros_clientes t1, giros t2 WHERE t1.id_user=? AND t1.id_gir=t2.id_gir AND t2.eliminado=? ORDER BY dns_letra");
            $sql->bind_param("ii", $this->id_user, $this->eliminado);
            $sql->execute();
            $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
            $sql->free_result();
            $sql->close();

        }
        if($this->admin == 1 && $this->id_user == 1){

            $sql = $this->con->prepare("SELECT id_gir, nombre, dominio, dns_letra FROM giros WHERE eliminado=? ORDER BY dns_letra");
            $sql->bind_param("i", $this->eliminado);
            $sql->execute();
            $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
            $sql->free_result();
            $sql->close();
        
        }
        return $result;
        
    }
    public function get_giro(){

        $sql = $this->con->prepare("SELECT * FROM giros WHERE id_gir=? AND eliminado=?");
        $sql->bind_param("ii", $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $sql->free_result();
        $sql->close();
        return $result;

    }

}
?>