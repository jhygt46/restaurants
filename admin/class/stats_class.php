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
        $from = $_POST['from'];
        $to = $_POST['to'];
        
        if(count($locales) > 1){
            for($i=0; $i<count($locales); $i++){
                $aux = explode("-", $locales[$i]);
                $aux2[] = "id_loc='".$aux[1]."'";
            }
            $sql = "SELECT * FROM pedidos_aux WHERE (".implode(" OR ", $aux2).")";
        }
        if(count($locales) == 1){
            $aux = explode("-", $locales[0]);
            $sql = "SELECT * FROM pedidos_aux WHERE id_loc='".$aux[1]."'";
        }
        if(count($locales) == 0){
            $sql = "SELECT * FROM pedidos_aux WHERE id_gir='".$this->id_gir."'";
        }
        
        $sql = $sql." AND fecha > '".$from."' AND fecha < '".$to."'";
        $pedidos = $this->con->sql($sql);
        
        $from = strtotime($from);
        $to = strtotime($to) + 86400;        
        $dif_tiempo = round(($to - $from)/86400);
        
        $infos['a'] = $dif_tiempo;
        
        if($dif_tiempo <= 50){
            // MOSTRAR DIAS
            $info['chart']['type'] = 'line';
            $info['title']['text'] = 'Ventas en dias';
            $infos['t'] = 1;
            while($to > $aux_from){
                $infos['aux'][] = $aux_from;
                $aux_from = $aux_from + 86400;
            }
            
        }
        if($dif_tiempo > 50 && $dif_tiempo < 548){
            // MOSTRAR MESES
            $info['chart']['type'] = 'line';
            $info['title']['text'] = 'Ventas en meses';
            $infos['t'] = 2;
            
        }
        if($dif_tiempo >= 548){
            // MOSTRAR AÑOS
            $info['chart']['type'] = 'line';
            $info['title']['text'] = 'Ventas en a&ntilde;s';
            $infos['t'] = 3;
        }
        
        $info['xAxis']['categories'][0] = 'Ene';
        $info['xAxis']['categories'][1] = 'Feb';
        $info['xAxis']['categories'][2] = 'Mar';
        $info['xAxis']['categories'][3] = 'Abr';
        $info['xAxis']['categories'][4] = 'May';
        $info['xAxis']['categories'][5] = 'Jun';
        $info['xAxis']['categories'][6] = 'Jul';
        $info['xAxis']['categories'][7] = 'Ago';
        $info['xAxis']['categories'][8] = 'Sep';
        $info['xAxis']['categories'][9] = 'Oct';
        $info['xAxis']['categories'][10] = 'Nov';
        $info['xAxis']['categories'][11] = 'Dic';
        
        
        $info['chart']['type'] = 'line';
        $info['subtitle']['text'] = 'Graficos en tiempo real';
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
        
        return $infos;
        
    }
   
    
}
// QUE ME DEVUELTA CATEGORIA Y SUS VALORES
?>