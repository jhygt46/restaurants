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
        $aux_from = $from;
        $mes = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        
        if($dif_tiempo <= 50){
            // MOSTRAR DIAS
            $info['chart']['type'] = 'line';
            $info['title']['text'] = 'Ventas en dias';
            $infos['t'] = 1;
            
            while($to > $aux_from){
                $info['xAxis']['categories'][] = date("d", $aux_from);
                $infos['fecha'][] = date("Y-m-d", $aux_from);
                $aux_from = $aux_from + 86400;
            }
            
        }
        if($dif_tiempo > 50 && $dif_tiempo < 548){
            // MOSTRAR MESES
            $info['chart']['type'] = 'line';
            $info['title']['text'] = 'Ventas en meses';
            $infos['t'] = 2;
            
            while($to > $aux_from){
                $aux_mes = intval(date("m", $aux_from)) - 1;
                $info['xAxis']['categories'][] = $mes[$aux_mes];
                $infos['fecha'][] = date("Y-m", $aux_from);
                $aux_from = strtotime('+1 month', $aux_from);
            }
            
        }
        if($dif_tiempo >= 548){
            // MOSTRAR AÑOS
            $info['chart']['type'] = 'line';
            $info['title']['text'] = 'Ventas en a&ntilde;s';
            $infos['t'] = 3;
            
            while($to > $aux_from){
                $info['xAxis']['categories'][] = date("Y", $aux_from);
                $infos['fecha'][] = date("Y", $aux_from);
                $aux_from = strtotime('+1 Year', $aux_from);
            }
            
        }
        
        if($infos['t'] == 1){
            
        }
        if($infos['t'] == 2){
            
        }
        if($infos['t'] == 3){
            
        }

        $info['chart']['type'] = 'line';
        $info['subtitle']['text'] = 'Graficos en tiempo real';
        $info['yAxis']['title']['text'] = null;
        
        $info['plotOptions']['line']['dataLabels']['enabled'] = true;
        $info['plotOptions']['line']['enableMouseTracking'] = false;
        
        $info['series'][] = $this->get_series($infos, 'Buena Nelson 1', $pedidos);
        $info['series'][] = $this->get_series($infos, 'Buena Nelson 2');
        $info['series'][] = $this->get_series($infos, 'Buena Nelson 3');
        
        //foreach($infos['fecha'] as $fecha){
            foreach($pedidos['resultado'] as $pedido){
                $info['a_pedidos'][] = $pedido;
            }
        //}
        
        return $info;
        
    }
    public function get_series($infos, $name){
        $aux['name'] = $name;
        foreach($infos['fecha'] as $fecha){
            $aux['data'][] = rand(10, 2000);
            
        }
        return $aux;
    }
    
}
// QUE ME DEVUELTA CATEGORIA Y SUS VALORES
?>