<?php
session_start();

require_once 'mysql_class.php';

class Core{
    
    public $con = null;
    public $id_user = null;
    public $admin = null;
    public $id_gir = null;
    public $id_cat = null;
    
    public function __construct(){
        
        $this->con = new Conexion();
        $this->id_user = $_SESSION['user']['info']['id_user'];
        $this->admin = $_SESSION['user']['info']['admin'];
        $this->id_gir = $_SESSION['user']['id_gir'];
        $this->id_cat = $_SESSION['user']['id_cat'];
        
    }
    
    public function inicio(){
        
        $data['user'] = 1;
        return $data;
        
    }
    
    

}
?>