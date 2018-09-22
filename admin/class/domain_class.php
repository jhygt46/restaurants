<?php
session_start();

require_once 'mysql_class.php';

class Dominio{
    
    public $con = null;

    public function __construct(){
        $this->con = new Conexion();
    }
    public function get_data($dom){
        
        $info['op'] = 0;
        $dominio = ($dom !== null) ? $dom : $_SERVER["HTTP_HOST"];
        $sql = $this->con->sql("SELECT * FROM giros WHERE dominio='".$dominio."'");
        if(count($sql['resultado']) == 1){
            
            $info['op'] = 1;
            $info['css_style'] = "/css/types/".$sql['resultado'][0]['style_page'];
            $info['css_color'] = "/css/colors/".$sql['resultado'][0]['style_color'];
            $info['css_modals'] = "/css/modals/".$sql['resultado'][0]['style_modal'];
            
        }

        return $info;
        
    }
    
}
// QUE ME DEVUELTA CATEGORIA Y SUS VALORES
?>