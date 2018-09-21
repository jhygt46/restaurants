<?php
session_start();

require_once 'mysql_class.php';

class Easy{
    
    public $con = null;

    public function __construct(){
        $this->con = new Conexion();
        $this->busqueda = new stdClass();
    }
    
    public function palabra_clave($id, $j){
        
        $sql = $this->con->sql("SELECT * FROM palabras_claves WHERE id='".$id."'");
        if($j == 0){
            $this->busqueda->id = $sql['resultado'][0]['id'];
            $this->busqueda->nombre = $sql['resultado'][0]['nombre'];
        }
        $parent_id = $sql['resultado'][0]['parent_id'];
        $apps = $this->get_apps($id);
        for($i=0; $i<count($apps['apps']); $i++){
            $this->busqueda->apps[] = $apps['apps'][$i];
        }
        if($parent_id != 0){
            $j++;
            $this->palabra_clave($parent_id, $j);
        }
        
    }
    public function show_apps(){
        return $this->busqueda;
        
    }
    public function get_apps($id){
        
        $sql = $this->con->sql("SELECT * FROM palabras_apps t1, aplicaciones t2 WHERE t1.id_pal='".$id."' AND t1.id_app=t2.id_app");
        
        for($i=0; $i<count($sql['resultado']); $i++){
            if($sql['resultado'][$i]['id_app'] == 1){
                $info['apps'][] = $this->app_01($sql['resultado'][$i]);
            }
            if($sql['resultado'][$i]['id_app'] == 2){
                $info['apps'][] = $this->app_02($sql['resultado'][$i]);
            }
            if($sql['resultado'][$i]['id_app'] == 3){
                $info['apps'][] = $this->app_01($sql['resultado'][$i]);
            }
        }
        return $info;
        
    }
    public function ejecutar($id){
        
        $sql = $this->con->sql("SELECT t3.id_app, t3.nombre as app_nombre, t2.simple_txt, t1.nombre FROM palabras_claves t1, palabras_apps t2, aplicaciones t3 WHERE t1.id='".$id."' AND t1.id=t2.id_pal AND t2.id_app=t3.id_app ORDER BY t2.orden");
        $info['nombre'] = $sql['resultado'][0]['nombre'];
        $info['products']['require'][0]['type'] = "input";
        $info['products']['require'][0]['name'] = "Nombre";
        $info['products']['require'][1]['type'] = "input";
        $info['products']['require'][1]['name'] = "Peso";
        for($i=0; $i<count($sql['resultado']); $i++){
            if($sql['resultado'][$i]['id_app'] == 1){
                $info['apps'][] = $this->app_01($sql['resultado'][$i]);
            }
            if($sql['resultado'][$i]['id_app'] == 2){
                $info['apps'][] = $this->app_02($sql['resultado'][$i]);
            }
            if($sql['resultado'][$i]['id_app'] == 3){
                $info['apps'][] = $this->app_01($sql['resultado'][$i]);
            }
        }
        return $info;
        
    }
    public function app_01($app){
        $json = json_decode($app['simple_txt']);
        $aux['id_app'] = $app['id_app'];
        $aux['nombre'] = $json->{'nombre'};
        $aux['values'] = $json->{'values'};
        return $aux;
    }
    public function app_02($app){
        $aux['id_app'] = $app['id_app'];
        $aux['ubicacion'] = "Buena Nelson Aveniu";
        return $aux;
    }
    public function arbol($id, $p_id){
        
        $palabra = $this->con->sql("SELECT * FROM palabras_claves WHERE id_pal='".$id."' AND parent_id='".$p_id."'");
        $hijos = $this->con->sql("SELECT * FROM palabras_claves WHERE parent_id='".$id."'");
        
        $i['id'] = $palabra['resultado'][0]['id'];
        $i['nombre'] = $palabra['resultado'][0]['nombre'];
        
        if($hijos['count'] > 0){
            for($j=0; $j<$hijos['count']; $j++){
                $x[] = $this->get_carta($hijos['resultado'][$j]['id'], $hijos['resultado'][$j]['parent_id']);
            }
        }
        $i['carta'] = $x;
        return $i;
        
    }
    public function get_carta($id, $p_id){
        
        $palabra = $this->con->sql("SELECT * FROM palabras_claves WHERE id='".$id."' AND parent_id='".$p_id."'");
        $hijos = $this->con->sql("SELECT * FROM palabras_claves WHERE parent_id='".$id."'");
        
        $i['id'] = $palabra['resultado'][0]['id'];
        $i['nombre'] = $palabra['resultado'][0]['nombre'];
        
        if(count($hijos['resultado']) > 0){
            for($j=0; $j<count($hijos['resultado']); $j++){
                $i['child'][] = $this->arbol($hijos['resultado'][$j]['id'], $hijos['resultado'][$j]['parent_id']);
            }
        }
        return $i;
        
    }
    public function cat_pros($id_cae){
        $sql = $this->con->sql("SELECT t2.id_pro, t2.nombre, t1.simple_txt FROM cat_pros t1, productos t2 WHERE t1.id_cae='".$id_cae."' AND t1.id_pro=t2.id_pro");
        for($i=0; $i<$sql['count']; $i++){
            $aux['id_pro'] = $sql['resultado'][$i]['id_pro'];
            $precios = json_decode($sql['resultado'][$i]['simple_txt']);
            $aux['precio'] = $precios->{precio};
            $aux2[] = $aux;
            unset($aux);
        }
        return $aux2;
    }
    public function childs($id_cae, $p_id){
        
        $palabra = $this->con->sql("SELECT * FROM categorias WHERE id_cae='".$id_cae."' AND parent_id='".$p_id."'");
        $hijos = $this->con->sql("SELECT * FROM categorias WHERE parent_id='".$id_cae."'");
        
        $i['id_cae'] = $palabra['resultado'][0]['id_cae'];
        $i['nombre'] = $palabra['resultado'][0]['nombre'];
            
        if($hijos['count'] > 0){
            for($j=0; $j<$hijos['count']; $j++){
                $x[] = $this->childs($hijos['resultado'][$j]['id_cae'], $hijos['resultado'][$j]['parent_id']);
            }
            $i['child'] = $x;
        }else{
            $i['productos'] = $this->cat_pros($id_cae);
        }
        return $i;
        
    }
    public function get_catalogo_categorias($id_cat){
        $sql = $this->con->sql("SELECT * FROM categorias WHERE id_cat='".$id_cat."' AND parent_id='0'");
        for($i=0; $i<$sql['count']; $i++){
            $aux[] = $this->childs($sql['resultado'][$i]['id_cae'], 0);
        }
        return $aux;
    }
    public function get_catalogo($id_gir){
        $sql = $this->con->sql("SELECT * FROM catalogo_productos WHERE id_gir='".$id_gir."'");
        for($i=0; $i<$sql['count']; $i++){
            $aux['id_cat'] = $sql['resultado'][$i]['id_cat'];
            $aux['nombre'] = $sql['resultado'][$i]['nombre'];
            $aux['categorias'] = $this->get_catalogo_categorias($aux['id_cat']);
            $aux2[] = $aux;
            unset($aux);
        }
        return $aux2;
    }
    public function get_prods($id_gir){
        $sql = $this->con->sql("SELECT t4.id_pro, t4.nombre FROM catalogo_productos t1, categorias t2, cat_pros t3, productos t4 WHERE t1.id_gir='".$id_gir."' AND t1.id_cat=t2.id_cat AND t2.id_cae=t3.id_cae AND t3.id_pro=t4.id_pro");
        return $sql['resultado'];
    }
    public function get_palabra_giros($id){
        $sql = $this->con->sql("SELECT t1.id_gir, t1.nombre FROM giros t1, palabra_giros t2 WHERE t2.id_pal='".$id."' AND t2.id_gir=t1.id_gir");
        for($i=0; $i<$sql['count']; $i++){
            $aux['id_gir'] = $sql['resultado'][$i]['id_gir'];
            $aux['nombre'] = $sql['resultado'][$i]['nombre'];
            $aux['catalogos'] = $this->get_catalogo($aux['id_gir']);
            $aux['productos'] = $this->get_prods($aux['id_gir']);
            $aux2[] = $aux;
            unset($aux);
        }
        return $aux2;
    }
    public function get_categorias($id){
        $aux = $this->con->sql("SELECT t1.id_cae, t1.nombre FROM categorias t1, palabra_categorias t2 WHERE t2.id_pal='".$id."' AND t2.id_cae=t1.id_cae");
        return $aux['resultado'];
    }
    public function get_productos($id){
        $aux = $this->con->sql("SELECT t1.id_pro, nombre FROM productos t1, palabra_productos t2 WHERE t2.id_pal='".$id."' AND t2.id_pro=t1.id_pro");
        return $aux['resultado'];
    }
    
    public function get_palabra_clave($id){
        
        $palabra = $this->con->sql("SELECT * FROM palabras_claves WHERE id_pal='".$id."'");
        $info['nombre'] = $palabra['resultado'][0]['nombre'];
        
        if($palabra['resultado'][0]['is_giros']){
            $info['values'] = $this->get_palabra_giros($id);
        }
        if($palabra['resultado'][0]['is_categorias']){
            $info['values'] = $this->get_categorias($id);
        }
        if($palabra['resultado'][0]['is_productos']){
            $info['values'] = $this->get_productos($id);
        }
        
        return $info;
        
    }
}
// QUE ME DEVUELTA CATEGORIA Y SUS VALORES
?>