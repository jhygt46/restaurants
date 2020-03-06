<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
if(!isset($url)){ $url = url(); }

require_once $url["dir"]."db.php";
require_once $url["dir_base"]."config/config.php";

class Install{
    
    public $con = null;
    public $tablas = [];
    public $tabla = [];
    public $ejecutar = true;
    public $detalle = 2;

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

        $this->con = new mysqli($this->host[0], $this->usuario[0], $this->password[0]);
    }
    private function get_data($tabla, $url){
        return json_decode(file_get_contents($url.$tabla));
    }
    public function add_tabla(){
        $this->tablas[] = $this->tabla;
        $this->tabla = [];
    }
    public function ejecutar($e){
        $this->ejecutar = $e;
    }
    public function detalle($e){
        $this->detalle = $e;
    }
    public function process(){

        for($i=0; $i<count($this->tablas); $i++){

            $tabla = "CREATE TABLE IF NOT EXISTS `".$this->tablas[$i]["nombre"]."` (";
            $aux_t = [];
            for($j=0; $j<count($this->tablas[$i]["campos"]); $j++){
                $aux = "`".$this->tablas[$i]["campos"][$j]["nombre"]."` ".$this->tablas[$i]["campos"][$j]["tipo"];
                $aux .= ($this->tablas[$i]["campos"][$j]["null"] == 0) ? " NOT NULL" : " NULL" ;
                $aux_t[] = $aux;
            }
            $tabla .= implode(",", $aux_t).") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;";
            $tables[] = $tabla;
            $tables_name[] = $this->tablas[$i]["nombre"];
            
        }
        for($i=0; $i<count($this->tablas); $i++){
        
            $key = "ALTER TABLE `".$this->tablas[$i]["nombre"]."`";
            $aux_t = [];
            $aux_c = [];
            $pk = [];
            $c = 1;
        
            for($j=0; $j<count($this->tablas[$i]["campos"]); $j++){
                if(isset($this->tablas[$i]["campos"][$j]['pk'])){
                    $pk[] = "`".$this->tablas[$i]["campos"][$j]["nombre"]."`";
                }
                if(isset($this->tablas[$i]["campos"][$j]['k'])){
                    $aux_t[] = " ADD KEY `".$this->tablas[$i]["campos"][$j]["nombre"]."` (`".$this->tablas[$i]["campos"][$j]["nombre"]."`)";
                    if(isset($this->tablas[$i]["campos"][$j]['kt']) && isset($this->tablas[$i]["campos"][$j]['kc'])){
                        $aux_c[] = " ADD CONSTRAINT `".$this->tablas[$i]["nombre"]."_ibfk_".$c."` FOREIGN KEY (`".$this->tablas[$i]["campos"][$j]["nombre"]."`) REFERENCES `".$this->tablas[$this->tablas[$i]["campos"][$j]['kt']]["nombre"]."` (`".$this->tablas[$this->tablas[$i]["campos"][$j]['kt']]["campos"][$this->tablas[$i]["campos"][$j]['kc']]["nombre"]."`) ON DELETE CASCADE ON UPDATE CASCADE";
                        $c++;
                    }
                }
                if(isset($this->tablas[$i]["campos"][$j]['ai'])){
                    $ai = $key;
                    $ai .= " MODIFY `".$this->tablas[$i]["campos"][$j]["nombre"]."` ".$this->tablas[$i]["campos"][$j]["tipo"]."";
                    $ai .= ($this->tablas[$i]["campos"][$j]["null"] == 0) ? " NOT NULL" : " NULL" ;
                    $ai .= " AUTO_INCREMENT, AUTO_INCREMENT=1;";
                    $ais[] = $ai;
                }
            }
            
            if(count($aux_t) > 0 || count($pk) > 0){
                $aux_key = $key;
                if(count($pk) > 0){
                    $aux_key .= " ADD PRIMARY KEY (".implode(",", $pk).")";
                    if(count($aux_t) > 0){
                        $aux_key .= ",";
                    }
                }
                if(count($aux_t) > 0){
                    $aux_key .= implode(",", $aux_t);
                }
                $keys[] = $aux_key;
            }
            if(count($aux_c) > 0){
                $cons[] = $key.implode(",", $aux_c).";";
            }
        
        }

        if($this->con->query("CREATE DATABASE IF NOT EXISTS ".$this->base_datos[0]." CHARACTER SET UTF8 COLLATE UTF8_GENERAL_CI")){
            if($this->detalle > 0){ echo "<strong>BASE DE DATOS: ".$this->base_datos[0]." - CREADA</strong><br/><br/>"; }
            if($this->detalle > 0 && count($tables) > 0){ echo "<br/>TABLAS<br/>"; }
            $this->con->select_db($this->base_datos[0]);
            for($i=0; $i<count($tables); $i++){
                if($this->ejecutar){
                    if($this->con->query($tables[$i])){
                        if($this->detalle > 1){
                            echo "Tabla creada (".$i."): ".$tables_name[$i]."<br/>";
                        }
                    }else{
                        if($this->detalle > 0){ 
                            echo $tables[$i]."<br/>";
                            echo "<strong>ERROR: ".$tables_name[$i]." NO FUE CREADA</strong> => ".$this->con->error."<br/>";
                        }
                    }
                }else{
                    if($this->detalle > 0){ 
                        echo $tables[$i]."<br/>";
                    }
                }
            }
            if($this->detalle > 0 && count($keys) > 0){ echo "<br/>KEYS<br/>"; }
            for($i=0; $i<count($keys); $i++){
                if($this->ejecutar){
                    if($this->con->query($keys[$i])){
                        if($this->detalle > 1){
                            echo "KEY CREADA: <br/>";
                        }
                    }else{
                        if($this->detalle > 0){
                            echo $keys[$i]."<br/>";
                            echo "<strong>ERROR: KEY </strong> => ".$this->con->error."<br/>";
                        }
                    }
                }else{
                    if($this->detalle > 0){ 
                        echo $keys[$i]."<br/>";
                    }
                }
            }
            if($this->detalle > 0 && count($ais) > 0){ echo "<br/>AUTOINCREMENTS<br/>"; }
            for($i=0; $i<count($ais); $i++){
                if($this->ejecutar){
                    if($this->con->query($ais[$i])){
                        if($this->detalle > 1){
                            echo "AUTOINCREMENT CREADO: <br/>";
                        }
                    }else{
                        if($this->detalle > 0){
                            echo $ais[$i]."<br/>";
                            echo "<strong>ERROR: AUTOINCREMENT</strong> => ".$this->con->error."<br/>";
                        }
                    }
                }else{
                    if($this->detalle > 0){
                        echo $ais[$i]."<br/>";
                    }
                }
            }
            if($this->detalle > 0 && count($cons) > 0){ echo "<br/>FILTROS<br/>"; }
            for($i=0; $i<count($cons); $i++){
                if($this->ejecutar){
                    if($this->con->query($cons[$i])){
                        if($this->detalle > 1){
                            echo "FILTROS CREADO: <br/>";
                        }
                    }else{
                        if($this->detalle > 0){
                            echo $cons[$i]."<br/>";
                            echo "<strong>ERROR: FILTRO</strong> => ".$this->con->error."<br/>";
                        }
                    }
                }else{
                    if($this->detalle > 0){
                        echo $cons[$i]."<br/>";
                    }
                }
            }

            if($this->detalle > 0){ echo "<br/>INSERT<br/>"; }
            
            for($i=0; $i<count($this->tablas); $i++){
        
                $campos = [];
                $matriz = [];

                for($j=0; $j<count($this->tablas[$i]["campos"]); $j++){
                    $cant = count($this->tablas[$i]["campos"][$j]["values"]);
                    if($cant > 0){
                        $campos[] = $this->tablas[$i]["campos"][$j]["nombre"];
                        for($k=0; $k<$cant; $k++){
                            $matriz[$k][] = "'".$this->tablas[$i]["campos"][$j]["values"][$k]."'";
                        }
                    }
                }
                
                for($j=0; $j<count($matriz); $j++){
                    $sql = "INSERT INTO ".$this->tablas[$i]["nombre"]." (".implode(", ", $campos).") VALUES (".implode(", ", $matriz[$j]).")";
                    if($this->ejecutar){
                        if($this->con->query($sql)){
                            if($this->detalle > 1){
                                echo "INSERTAR REGISTRO ".$this->tablas[$i]["nombre"]."<br/>";
                            }
                        }else{
                            if($this->detalle > 0){
                                echo $sql."<br/>";
                                echo "<strong>ERROR: INSERT</strong> => ".$this->con->error."<br/>";
                            }
                        }
                    }else{
                        if($this->detalle > 0){
                            echo $sql."<br/>";
                        }
                    }
                }
        
            }
        
        }else{
            echo "ERROR CREAR BASE: ".$this->con->error."<br/>";
        }

    }
    public function llenar_data($url){

        if($_SERVER["HTTP_HOST"] == "localhost"){

            for($i=0; $i<count($this->tablas); $i++){

                $tabla = $this->tablas[$i];
                $datas = $this->get_data($tabla["nombre"], $url);
                $pk = []; $k = []; $vp = [];
            
                for($j=0; $j<count($tabla['campos']); $j++){
                    $campo = $tabla['campos'][$j];
                    if(isset($campo['pk'])){
                        $pk[] = $campo['nombre'];
                    }
                    if(isset($campo['k']) || isset($campo['pk'])){
                        $k[] = $campo['nombre'];
                    }
                }
                
                for($j=0; $j<count($datas); $j++){
                
                    $data = $datas[$j];
                    $v = [];
                    for($m=0; $m<count($k); $m++){
                        $v[] = $data->{$k[$m]};
                    }
                    if(!in_array(0, $v)){
                        $sql = "INSERT INTO ".$tabla["nombre"]." (".implode(",", $k).") VALUES (".implode(",", $v).")";
                        if($this->ejecutar){
                            if(!$this->con->query($sql)){
                                echo "<strong>ERROR: INSERT LLAVES</strong> => ".$this->con->error."<br/>";
                            }
                        }else{
                            echo $sql."<br/>";
                        }
                        $where = [];
                        for($m=0; $m<count($pk); $m++){
                            $where[] = $pk[$m]."='".$data->{$k[$m]}."'";
                        }
                        foreach($data as $key => $value){
                            if(!in_array($key, $k)){
                                $sql = "UPDATE ".$tabla["nombre"]." SET ".$key."='".$value."' WHERE ".implode(" AND ", $where);
                                if($this->ejecutar){
                                    if(!$this->con->query($sql)){
                                        echo "<strong>ERROR: UPDATE DATA</strong> => ".$this->con->error."<br/>";
                                    }
                                }else{
                                    echo $sql."<br/>";
                                }
                            }
                        }
                    }else{
                        echo $sql."ERROR EN ".$tabla["nombre"]." LLAVES IGUAL 0<br/>";
                    }
                }
            }
        }
    }
    public function crearTable($nombre){
        $this->tabla['nombre'] = $nombre;
        $this->tabla['campos'] = [];
    }
    public function add($nombre, $tipo, $null, $values = NULL, $pk = NULL, $ai = NULL, $k = NULL, $kt = NULL, $kc = NULL){
        $res['nombre'] = $nombre;
        $res['tipo'] = $tipo;
        $res['null'] = $null;
        if($values !== NULL){ $res['values'][] = $values; }
        if($pk !== NULL){ $res['pk'] = $pk; }
        if($ai !== NULL){ $res['ai'] = $ai; }
        if($k !== NULL){ $res['k'] = $k; }
        if($kt !== NULL){ $res['kt'] = $kt; }
        if($kc !== NULL){ $res['kc'] = $kc; }
        $this->tabla['campos'][] = $res;
    }

}