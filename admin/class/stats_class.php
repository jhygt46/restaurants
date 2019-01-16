<?php
session_start();

require_once($path."class/core_class.php");

class Stats extends Core{
    
    public $con = null;
    public $id_gir = null;
    
    public function __construct(){
        
        $this->con = new Conexion();
        $this->id_gir = $_SESSION['user']['id_gir'];
        
    }
    public function process(){
        
        $accion = $_POST["accion"];        
        if($accion == "get_stats"){
            return $this->get_stats();
        }
        
    }
    
    
    public function get_stats(){
        
        $tipo = $_POST['tipo'];
        $locales = json_decode($_POST['locales']);
        $info['locales'] = $locales;
        
        
        $info['chart']['type'] = 'line';
        $info['title']['text'] = 'Monthly Average Temperature';
        $info['subtitle']['text'] = 'Source: WorldClimate.com';
        $info['xAxis']['categories'][0] = 'Jan';
        $info['xAxis']['categories'][1] = 'Feb';
        $info['xAxis']['categories'][2] = 'Mar';
        $info['xAxis']['categories'][3] = 'Apr';
        $info['xAxis']['categories'][4] = 'May';
        $info['xAxis']['categories'][5] = 'Jun';
        $info['xAxis']['categories'][6] = 'Jul';
        $info['xAxis']['categories'][7] = 'Aug';
        $info['xAxis']['categories'][8] = 'Sep';
        $info['xAxis']['categories'][9] = 'Oct';
        $info['xAxis']['categories'][10] = 'Nov';
        $info['xAxis']['categories'][11] = 'Dec';
        $info['yAxis']['title']['text'] = null;
        
        $info['plotOptions']['line']['dataLabels']['enabled'] = true;
        $info['plotOptions']['line']['enableMouseTracking'] = false;
        
        $info['series'][0]['name'] = 'Tokyo';
        $info['series'][0]['data'][0] = 7.0;
        $info['series'][0]['data'][1] = 7.2;
        $info['series'][0]['data'][2] = 7.3;
        $info['series'][0]['data'][3] = 7.8;
        $info['series'][0]['data'][4] = 7.2;
        $info['series'][0]['data'][5] = 7.1;
        $info['series'][0]['data'][6] = 7.9;
        $info['series'][0]['data'][7] = 7.5;
        $info['series'][0]['data'][8] = 7.7;
        $info['series'][0]['data'][9] = 7.9;
        $info['series'][0]['data'][10] = 7.3;
        $info['series'][0]['data'][11] = 7.1;
        
        $info['series'][1]['name'] = 'London';
        $info['series'][1]['data'][0] = 8.0;
        $info['series'][1]['data'][1] = 8.2;
        $info['series'][1]['data'][2] = 8.3;
        $info['series'][1]['data'][3] = 8.8;
        $info['series'][1]['data'][4] = 8.2;
        $info['series'][1]['data'][5] = 8.1;
        $info['series'][1]['data'][6] = 8.9;
        $info['series'][1]['data'][7] = 8.5;
        $info['series'][1]['data'][8] = 8.7;
        $info['series'][1]['data'][9] = 8.9;
        $info['series'][1]['data'][10] = 8.3;
        $info['series'][1]['data'][11] = 8.1;
        
        return $info;
        
    }
   
    
}
// QUE ME DEVUELTA CATEGORIA Y SUS VALORES
?>