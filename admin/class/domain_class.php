<?php
session_start();

require_once 'mysql_class.php';

class Dominio{
    
    public $con = null;

    public function __construct(){
        $this->con = new Conexion();
    }
    public function get_data(){
        
        $dominio = $_SERVER["HTTP_HOST"];
        $sql = $this->con->sql("SELECT * FROM giros WHERE dominio='".$dominio."'");
        return $sql;
        
        
        if($dominio == "localhost"){
            $info['css_style'] = "css/types/style_page_01.css";
            $info['css_color'] = "css/colors/color_set_01.css";
            $info['css_modals'] = "css/modals/style_modals_01.css";
            $info['js_info'] = "js/custom/909PDtTJXvnpzEcA6ho7VmUlbSZhaI3t.js";
        }else{
            
        }
        return $info;
        
    }
    
}
// QUE ME DEVUELTA CATEGORIA Y SUS VALORES
?>