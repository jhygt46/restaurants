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

        $this->con = new mysqli($this->host, $this->usuario, $this->password, $this->base_datos);
        $this->id_user = $_SESSION['user']['info']['id_user'];
        $this->admin = $_SESSION['user']['info']['admin'];
        $this->re_venta = $_SESSION['user']['info']['re_venta'];
        $this->id_aux_user = $_SESSION['user']['info']['id_aux_user'];
        $this->id_gir = $_SESSION['user']['id_gir'];
        $this->id_cat = $_SESSION['user']['id_cat'];
        
    }
    public function is_giro(){

        if(isset($_GET["id_gir"]) && is_numeric($_GET["id_gir"]) && $_GET["id_gir"] > 0){
            if($this->admin == 0){
                $sql = $this->con->prepare("SELECT * FROM fw_usuarios_giros WHERE id_gir=? AND id_user=?");
                $sql->bind_param("ii", $_GET["id_gir"], $this->id_user);
                $sql->execute();
                $sql->store_result();
                if($sql->{"num_rows"} == 1){
                    $this->id_gir = $_GET["id_gir"];
                    $_SESSION['user']['id_gir'] = $_GET["id_gir"];
                }else{
                    die("ERROR: #A101");
                }
                $sql->close();
            }
            if($this->admin == 1){
                $sql = $this->con->prepare("SELECT * FROM fw_usuarios_giros_clientes WHERE id_gir=? AND id_user=?");
                $sql->bind_param("ii", $_GET["id_gir"], $this->id_user);
                $sql->execute();
                $sql->store_result();
                if($sql->{"num_rows"} == 1 || $this->id_user == 1){
                    $this->id_gir = $_GET["id_gir"];
                    $_SESSION['user']['id_gir'] = $_GET["id_gir"];
                }else{
                    die("ERROR: #A102");
                }
                $sql->close();
            }
        }else{
            if($this->id_gir == 0){
                die("ERROR: #A103");
            }
        }

    }
    public function get_locales(){

        $sql = $this->con->prepare("SELECT * FROM locales WHERE id_gir=? AND eliminado=?");
        $sql->bind_param("ii", $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;

    }
    public function get_giro(){

        $sql = $this->con->prepare("SELECT * FROM locales WHERE id_gir=? AND eliminado=?");
        $sql->bind_param("ii", $this->id_gir, $this->eliminado);
        $sql->execute();
        
        $id_gir = ($id_gir == null) ? $this->id_gir : $id_gir ;
        $giros = $this->con->sql("SELECT * FROM giros WHERE id_gir='".$id_gir."' AND eliminado='0'");
        return $giros['resultado'][0];

    }

}
?>