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
        
        $tipo = intval($_POST['tipo']);
        $locales = json_decode($_POST['locales']);
        $from = $_POST['from'];
        $to = $_POST['to'];
        
        if(count($locales) > 1){
            for($i=0; $i<count($locales); $i++){
                $aux2[] = "id_loc='".$locales[$i]->{'id_loc'}."'";
            }
            $sql = "SELECT * FROM pedidos_aux WHERE (".implode(" OR ", $aux2).")";
        }
        if(count($locales) == 1){
            $sql = "SELECT * FROM pedidos_aux WHERE id_loc='".$locales[0]->{'id_loc'}."'";
        }
        if(count($locales) == 0){
            $sql = "SELECT * FROM pedidos_aux WHERE id_gir='".$this->id_gir."'";
        }
        
        $sql = $sql." AND fecha > '".$from."' AND fecha < '".$to."'";
        $aux_pedidos = $this->con->sql($sql);
        $pedidos = $aux_pedidos['resultado'];
        
        $from = strtotime($from);
        $to = strtotime($to) + 86400;        
        $dif_tiempo = round(($to - $from)/86400);
        $aux_from = $from;
        $mes = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        
        if($dif_tiempo <= 50){
            // MOSTRAR DIAS
            $info['chart']['type'] = 'line';
            $info['subtitle']['text'] = 'Tiempo Real en dias';
            $infos['tipo'] = 1;
            $lapse = "1 day";
            
            while($to > $aux_from){
                $info['xAxis']['categories'][] = date("d", $aux_from);
                $infos['fecha'][] = $aux_from;
                $aux_from = $aux_from + 86400;
            }
            
        }
        if($dif_tiempo > 50 && $dif_tiempo < 548){
            // MOSTRAR MESES
            $info['chart']['type'] = 'line';
            $info['subtitle']['text'] = 'Tiempo Real en meses';
            $infos['tipo'] = 2;
            $lapse = "1 month";
            
            while($to > $aux_from){
                $aux_mes = intval(date("m", $aux_from)) - 1;
                $info['xAxis']['categories'][] = $mes[$aux_mes];
                $infos['fecha'][] = $aux_from;
                $aux_from = strtotime('+1 month', $aux_from);
            }
            
        }
        if($dif_tiempo >= 548){
            // MOSTRAR AÑOS
            $info['chart']['type'] = 'line';
            $info['subtitle']['text'] = 'Tiempo Real en a&ntilde;os';
            $infos['tipo'] = 3;
            $lapse = "1 year";
            
            while($to > $aux_from){
                $info['xAxis']['categories'][] = date("Y", $aux_from);
                $infos['fecha'][] = $aux_from;
                $aux_from = strtotime('+1 Year', $aux_from);
            }
            
        }
        
        $info['chart']['type'] = 'line';
        $info['yAxis']['title']['text'] = null;
        
        $info['plotOptions']['line']['dataLabels']['enabled'] = true;
        $info['plotOptions']['line']['enableMouseTracking'] = false;
        
        if($tipo == 0){
            $info['title']['text'] = 'Total Ventas';            
            for($j=0; $j<count($locales); $j++){
                $aux['name'] = $locales[$j]->{'nombre'};
                if($infos['tipo'] == 1){
                    foreach($infos['fecha'] as $fecha){
                        $aux['data'][] = $this->pedidos_total_fecha($pedidos, $fecha, '1 day', $locales[$j]->{'id_loc'});
                    }
                }
                if($infos['tipo'] == 2){
                    foreach($infos['fecha'] as $fecha){
                        $aux['data'][] = $this->pedidos_total_fecha($pedidos, $fecha, '1 month', $locales[$j]->{'id_loc'});
                    }
                }
                if($infos['tipo'] == 3){
                    foreach($infos['fecha'] as $fecha){
                        $aux['data'][] = $this->pedidos_total_fecha($pedidos, $fecha, '1 year', $locales[$j]->{'id_loc'});
                    }
                }
                $info['series'][] = $aux;
                unset($aux);
            }
        }
        
        if($tipo == 1){
            $info['title']['text'] = 'Total Buena Nelson';
            $info['series'][] = $this->get_series($infos, 'Buena Enestor');
            $info['series'][] = $this->get_series($infos, 'Buena Diego');
        }
        

        
        return $info;
        
    }
    public function pedidos_total_fecha($pedidos, $fecha_ini, $intervalo, $id_loc){
        
        $total = 0;
        $aux = [];
        for($i=0; $i<count($pedidos); $i++){
            
            $fecha_pedido = strtotime($pedidos[$i]['fecha']);
            $fecha_fin = strtotime($intervalo, $fecha_ini);            
            if($fecha_pedido >= $fecha_ini && $fecha_pedido < $fecha_fin){
                if($id_loc == $pedidos[$i]['id_loc']){
                    $total = $total + $pedidos[$i]['total'];
                }
            }
            
        }
        return $total;
        
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