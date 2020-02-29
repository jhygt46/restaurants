<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
esconder("stats_class.php");
$url = url();

require_once $url["dir"]."db.php";
require_once $url["dir_base"]."config/config.php";

class Stats{
    
    public $con = null;
    public $id_gir = null;
    
    public function __construct(){
        
        $this->con = new mysqli($db_host[0], $db_user[0], $db_password[0], $db_database[0]);
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
            $sql = "SELECT * FROM pedidos_aux WHERE id_loc='".$locales[0]->{"id_loc"}."'";
        }
        if(count($locales) == 0){
            $sql = "SELECT * FROM pedidos_aux WHERE id_gir='".$this->id_gir."'";
        }
        
        $sql = $sql." AND fecha > '".$from."' AND fecha < '".$to."'";
        
        if($aux_pedidos = $this->con->query($sql)){
            while($row = $aux_pedidos->fetch_assoc()){
                $pedidos[] = $row;
            }
        }

        //$pedidos = $aux_pedidos['resultado'];
        
        $from = strtotime($from);
        $to = strtotime($to) + 86400;        
        $dif_tiempo = round(($to - $from)/86400);
        $aux_from = $from;
        $mes = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        
        if($dif_tiempo <= 50){
            // MOSTRAR DIAS
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
                foreach($infos['fecha'] as $fecha){
                    $aux['data'][] = $this->pedidos_total_fecha($pedidos, $fecha, $lapse, $locales[$j]->{'id_loc'});
                }
                $info['series'][] = $aux;
                unset($aux);
            }
        }
        if($tipo == 1){
            $info['title']['text'] = 'Total Pedidos Despacho Domicilio';          
            for($j=0; $j<count($locales); $j++){
                $aux['name'] = $locales[$j]->{'nombre'};
                foreach($infos['fecha'] as $fecha){
                    $aux['data'][] = $this->pedidos_despacho_fecha($pedidos, $fecha, $lapse, $locales[$j]->{'id_loc'}, 1);
                }
                $info['series'][] = $aux;
                unset($aux);
            }
        }
        if($tipo == 2){
            $info['title']['text'] = 'Total Pedidos Retiro Local';          
            for($j=0; $j<count($locales); $j++){
                $aux['name'] = $locales[$j]->{'nombre'};
                foreach($infos['fecha'] as $fecha){
                    $aux['data'][] = $this->pedidos_despacho_fecha($pedidos, $fecha, $lapse, $locales[$j]->{'id_loc'}, 0);
                }
                $info['series'][] = $aux;
                unset($aux);
            }
        }
        
        return $info;
        
    }
    public function pedidos_despacho_fecha($pedidos, $fecha_ini, $intervalo, $id_loc, $tipo){
        
        $total = 0;
        for($i=0; $i<count($pedidos); $i++){
            $fecha_pedido = strtotime($pedidos[$i]['fecha']);
            $fecha_fin = strtotime($intervalo, $fecha_ini);            
            if($fecha_pedido >= $fecha_ini && $fecha_pedido < $fecha_fin){
                if($id_loc == $pedidos[$i]['id_loc'] && $pedidos[$i]['despacho'] == $tipo){
                    $total = $total + 1;
                }
            }
        }
        return $total;
        
    }
    public function pedidos_total_fecha($pedidos, $fecha_ini, $intervalo, $id_loc){
        
        $total = 0;
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
    
}

?>