<?php

$path = $_SERVER['DOCUMENT_ROOT'];
if($_SERVER['HTTP_HOST'] == "localhost"){
    $path_db = $path."/restaurants/admin/db_config.php";
    $path_cf = $path."/config/config.php";
}else{
    $path_db = $path."/admin/db_config.php";
    $path_cf = "/var/www/html/config/config.php";
}

require_once $path_db;
require_once $path_cf;

class Conexion {
    
    public $conn = null;
    public $host = null;
    public $usuario = null;
    public $password = null;
    public $base_datos = null;

    public function __construct(){

        global $db_host;
        global $db_user;
        global $db_password;
        global $db_database;

        $this->host	= $db_host;
        $this->usuario = $db_user;
        $this->password = $db_password;
        $this->base_datos = $db_database;
        $this->conn = new mysqli($this->host[0], $this->usuario[0], $this->password[0], $this->base_datos[0]);
        
    }

    public function sql($sql){
        
        $info['query'] = $sql;
        if(!$this->conn->connect_errno){
            
            if($res = $this->conn->query($sql)){
                
                $info['estado'] = true;
                if(preg_match("/select/i", $sql)){
                    $i=0;
                    while($row = $res->fetch_assoc()){
                        $info['resultado'][] = $row;
                        $i++;
                    }
                    $info['count'] = $i;
                }
                if(preg_match("/insert/i", $sql)){
                    $info['insert_id'] = $this->conn->insert_id;
                }
                
            }else{
                $info['estado'] = false;
                $info['error'] = 'Query Error';
            }
            
            
        }else{
            
            $info['estado'] = false;
            $info['errno'] = $this->conn->connect_errno;
            $info['error'] = $this->conn->connect_error;
            
        }
        return $info;
    }

    public function __destruct(){
        $this->conn->close();
    }


}

?>