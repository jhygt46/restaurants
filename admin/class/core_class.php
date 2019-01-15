<?php
session_start();
date_default_timezone_set('America/Santiago');

require_once 'mysql_class.php';

class Core{
    
    public $con = null;
    public $id_user = null;
    public $admin = null;
    public $id_gir = null;
    public $id_cat = null;
    public $require = [];
    public function __construct(){
        $this->con = new Conexion();
        $this->id_user = $_SESSION['user']['info']['id_user'];
        $this->admin = $_SESSION['user']['info']['admin'];
        $this->id_gir = $_SESSION['user']['id_gir'];
        $this->id_cat = $_SESSION['user']['id_cat'];
        //echo $_SERVER['PHP_SELF'];
    }
    public function seguridad_if($arr){
        
        for($i=0; $i<count($arr); $i++){
            if(in_array($arr[$i], $_SESSION['user']['permisos'])){
                return true;
            }
        }
        
    }
    public function seguridad_exit($arr){
        
        for($i=0; $i<count($arr); $i++){
            if(in_array($arr[$i], $_SESSION['user']['permisos'])){
                return true;
            }
        }
        die("<div style='font-size: 3em; padding-top: 20px'>Error: Acceso Restringido</div><div style='font-size: 1.8em'>No tiene los permisos para acceder a esta pagina</div>");
        
    }
    public function in_apps($arr, $id){
        for($i=0; $i<count($arr); $i++){
            if($arr[$i]['id_app'] == $id){ return true; }
        }
        return false;
    }
    public function in_campo($arr, $id){
        for($i=0; $i<count($arr); $i++){
            if($arr[$i]['id_prc'] == $id){ return true; }
        }
        return false;
    }
    public function set_catalogo($id_cat){
        $this->id_cat = $id_cat;
        $_SESSION['user']['id_cat'] = $id_cat;
    }
    public function is_giro($id_gir){
        if($this->admin == 0){
            $count = $this->con->sql("SELECT * FROM fw_usuarios_giros WHERE id_gir='".$id_gir."' AND id_user='".$this->id_user."'");
            if($count['count'] == 1){
                $this->id_gir = $id_gir;
                $_SESSION['user']['id_gir'] = $id_gir;
            }else{
                die("ERROR: NO PUEDE SELECCIONAR EL GIRO");
            }
        }
        if($this->admin == 1){
            $count = $this->con->sql("SELECT * FROM fw_usuarios_giros_clientes WHERE id_gir='".$id_gir."' AND id_user='".$this->id_user."'");
            if(($count['count'] == 1 && $this->id_user > 1) || $this->id_user == 1){
                $this->id_gir = $id_gir;
                $_SESSION['user']['id_gir'] = $id_gir;
            }else{
                die("ERROR: NO PUEDE SELECCIONAR EL GIRO");
            }
        }
    }
    public function is_catalogo($id_cat){
                
        if($this->admin == 0){
            $count = $this->con->sql("SELECT * FROM fw_usuarios_giros t1, catalogo_productos t2 WHERE t2.id_cat='".$id_cat."' AND t2.id_gir=t1.id_gir AND t1.id_user='".$this->id_user."'");
            if($count['count'] == 1){
                $this->id_cat = $id_cat;
                $_SESSION['user']['id_cat'] = $id_cat;
            }else{
                die("ERROR: NO PUEDE SELECCIONAR EL GIRO");
            }
        }
        if($this->admin == 1){
            $count = $this->con->sql("SELECT * FROM fw_usuarios_giros_clientes t1, catalogo_productos t2  WHERE t2.id_cat='".$id_cat."' AND t2.id_gir=t1.id_gir AND t1.id_user='".$this->id_user."'");
            if($count['count'] == 1){
                $this->id_cat = $id_cat;
                $_SESSION['user']['id_cat'] = $id_cat;
            }else{
                die("ERROR: NO PUEDE SELECCIONAR EL GIRO");
            }
        }
        
    }
    public function paso_giro(){
        
        $giro = $this->con->sql("SELECT * FROM giros WHERE id_gir='".$this->id_gir."'");
        $catalogos = $this->con->sql("SELECT * FROM catalogo_productos WHERE id_gir='".$this->id_gir."' AND eliminado='0'");

        $url = "pages/apps/catalogo_productos.php";
        if($giro['resultado'][0]['catalogo'] == 1){
            if(count($catalogos['resultado']) == 1){
                $url = "pages/apps/ver_catalogo.php?id_cat=".$catalogos['resultado'][0]['id_cat']."&nombre=".$catalogos['resultado'][0]['nombre'];
            }
        }
        
        return $url;
        
    }
    public function get_css(){
        $css = $this->con->sql("SELECT * FROM css WHERE id_gir='".$this->id_gir."' OR id_gir='0'");
        return $css['resultado'];
    }
    public function get_footer(){
        $footer = $this->con->sql("SELECT footer_html FROM giros WHERE id_gir='".$this->id_gir."'");
        return $footer['resultado'][0]['footer_html'];
    }
    public function get_giros_user(){
        if($this->admin == 0){ $giros = $this->con->sql("SELECT t2.id_gir, t2.nombre, t2.dominio FROM fw_usuarios_giros t1, giros t2 WHERE t1.id_user='".$this->id_user."' AND t1.id_gir=t2.id_gir AND t2.eliminado='0'"); }
        if($this->admin == 1 && $this->id_user > 1){ $giros = $this->con->sql("SELECT t2.id_gir, t2.nombre, t2.dominio FROM fw_usuarios_giros_clientes t1, giros t2 WHERE t1.id_user='".$this->id_user."' AND t1.id_gir=t2.id_gir AND t2.eliminado='0'"); }
        if($this->admin == 1 && $this->id_user == 1){ $giros = $this->con->sql("SELECT id_gir, nombre, dominio FROM giros WHERE eliminado='0'"); }
        return $giros['resultado'];
    }
    public function get_giro(){
        $giros = $this->con->sql("SELECT * FROM giros WHERE id_gir='".$this->id_gir."' AND eliminado='0'");
        return $giros['resultado'][0];
    }
    public function get_ses_giro(){
        $giros = $this->con->sql("SELECT * FROM giros WHERE id_gir='".$this->id_gir."' AND eliminado='0'");
        return $giros['resultado'][0];
    }
    public function get_palabras_giro($id_gir){
        $pal_giro = $this->con->sql("SELECT t1.id_pal, t1.nombre, t1.parent_id, t2.id_gir FROM palabras_claves t1 LEFT JOIN palabra_giros t2 ON t1.id_pal=t2.id_pal AND t2.id_gir='".$id_gir."' WHERE t1.is_giros='1'");
        return $pal_giro['resultado'];
    }
    public function get_palabras_categoria($id_cae){
        $sql = $this->con->sql("SELECT t1.id_gir, t1.nombre FROM giros t1, catalogo_productos t2, categorias t3 WHERE t3.id_cae='".$id_cae."' AND t3.id_cat=t2.id_cat AND t2.id_gir=t1.id_gir");
        return $sql['resultado'][0];
    }
    public function get_apps_giro($parent_id){
        $sql = $this->con->sql("SELECT DISTINCT t3.id_app, t3.nombre, t2.simple_txt, t2.parent_id_app, t3.simple_txt as simple_txt_app FROM palabra_giros t1, palabra_posibles_apps t2, apps t3 WHERE t1.id_gir='".$this->id_gir."' AND t1.id_pal=t2.id_pal AND t2.id_app=t3.id_app AND t2.parent_id_app=".$parent_id);
        return $sql['resultado'];
    }
    public function get_usuarios(){
        $usuarios = $this->con->sql("SELECT t1.id_user, t1.nombre FROM fw_usuarios t1, fw_usuarios_giros t2 WHERE t2.id_gir='".$this->id_gir."' AND t2.id_user=t1.id_user AND t1.eliminado='0'");
        return $usuarios['resultado'];
    }
    public function get_usuario($id){
        $usuarios = $this->con->sql("SELECT id_user, nombre, correo FROM fw_usuarios WHERE id_user='".$id."' AND id_org='".$this->id_org."' AND eliminado='0'");
        return $usuarios['resultado'][0];
    }
    public function get_catalogos(){
        $giros = $this->con->sql("SELECT * FROM catalogo_productos WHERE id_gir='".$this->id_gir."' AND eliminado='0'");
        return $giros['resultado'];
    }
    public function get_catalogo(){
        $giros = $this->con->sql("SELECT * FROM catalogo_productos WHERE id_cat='".$this->id_cat."' AND eliminado='0'");
        return $giros['resultado'][0];
    }
    public function get_locales(){
        $giros = $this->con->sql("SELECT * FROM locales WHERE id_gir='".$this->id_gir."' AND eliminado='0'");
        return $giros['resultado'];
    }
    public function get_local($id_loc){
        $giros = $this->con->sql("SELECT * FROM locales WHERE id_gir='".$this->id_gir."' AND id_loc='".$id_loc."' AND eliminado='0'");
        return $giros['resultado'][0];
    }
    public function get_local_tramos($id_loc){
        $loc = $this->con->sql("SELECT * FROM locales_tramos WHERE id_loc='".$id_loc."' AND eliminado='0'");
        return $loc['resultado'];
    }
    public function get_local_tramo($id_lot){
        $lot = $this->con->sql("SELECT * FROM locales_tramos WHERE id_lot='".$id_lot."' AND eliminado='0'");
        return $lot['resultado'][0];
    }
    public function get_categorias(){
        $cats = $this->con->sql("SELECT DISTINCT t1.id_cae, t1.nombre, t1.parent_id, t2.id_pro, t1.tipo FROM categorias t1 LEFT JOIN cat_pros t2 ON t1.id_cae=t2.id_cae WHERE t1.id_cat='".$this->id_cat."' AND t1.eliminado='0' ORDER BY t1.orders");
        return $this->process_categorias($cats['resultado'], 'id_cae');
    }
    public function get_ingredientes($id_cat){
        $cats = $this->con->sql("SELECT * FROM ingredientes WHERE id_cat='".$id_cat."' AND eliminado='0'");
        return $this->process_categorias($cats['resultado'], 'id_ing');
    }
    public function get_lista_precio_ingredientes($id_lin){
        $ing = $this->con->sql("SELECT * FROM lista_precio_ingrediente WHERE id_lin='".$id_lin."'");
        return $ing['resultado'];
    }
    public function get_ingredientes_base(){
        $ing = $this->con->sql("SELECT * FROM ingredientes WHERE parent_id != 0 AND eliminado='0'");
        return $ing['resultado'];
    }
    public function get_lista_ingredientes(){
        $pres = $this->con->sql("SELECT * FROM lista_ingredientes WHERE id_cat='".$this->id_cat."' AND eliminado='0'");
        return $pres['resultado'];
    }
    public function get_lista_ingrediente($id_lin){
        $lin = $this->con->sql("SELECT * FROM lista_ingredientes WHERE id_lin='".$id_lin."' AND eliminado='0'");
        return $lin['resultado'][0];
    }
    public function get_preguntas(){
        $pres = $this->con->sql("SELECT * FROM preguntas WHERE id_cat='".$this->id_cat."' AND eliminado='0'");
        return $pres['resultado'];
    }
    public function get_preguntas_pro($id_pro){
        $pres = $this->con->sql("SELECT * FROM preguntas_productos WHERE id_pro='".$id_pro."'");
        return $pres['resultado'];
    }
    public function get_lista_ingredientes_pro($id_pro){
        $pres = $this->con->sql("SELECT * FROM lista_ingredientes_productos WHERE id_pro='".$id_pro."'");
        return $pres['resultado'];
    }
    public function get_pregunta($id_pre){
        $pre = $this->con->sql("SELECT * FROM preguntas WHERE id_cat='".$this->id_cat."' AND id_pre='".$id_pre."' AND eliminado='0'");
        return $pre['resultado'][0];
    }
    public function get_pregunta_valores($id_pre){
        $pre = $this->con->sql("SELECT * FROM preguntas_valores WHERE id_pre='".$id_pre."'");
        return $pre['resultado'];
    }
    public function get_categoria($id_cae){
        $cats = $this->con->sql("SELECT * FROM categorias WHERE id_cat='".$this->id_cat."' AND id_cae='".$id_cae."' AND eliminado='0'");
        return $cats['resultado'][0];
    }
    public function get_ingrediente($id_cat, $id_ing){
        $cats = $this->con->sql("SELECT * FROM ingredientes WHERE id_cat='".$id_cat."' AND id_ing='".$id_ing."' AND eliminado='0'");
        return $cats['resultado'][0];
    }
    public function get_categoria_2($id_cae){
        $cats = $this->con->sql("SELECT nombre FROM categorias WHERE id_cae='".$id_cae."' AND eliminado='0'");
        return $cats['resultado'][0];
    }
    public function get_promociones(){
        $promos = $this->con->sql("SELECT DISTINCT t1.id_prm, t1.nombre, t1.parent_id, t2.id_pro FROM promociones t1 LEFT JOIN promocion_productos t2 ON t1.id_prm=t2.id_prm WHERE t1.id_cat='".$this->id_cat."' AND t1.eliminado='0'");
        return $this->process_categorias($promos['resultado'], 'id_prm');
    }
    public function get_promocion($id_cae){
        $pre = $this->con->sql("SELECT precio FROM categorias WHERE id_cae='".$id_cae."'");
        $cats = $this->con->sql("SELECT id_cae2 as id_cae, cantidad FROM promocion_categoria WHERE id_cae1='".$id_cae."'");
        $prods = $this->con->sql("SELECT id_pro, cantidad FROM promocion_productos WHERE id_cae='".$id_cae."'");
        $aux['precio'] = $pre['resultado'][0]['precio'];
        $aux['categorias'] = $cats['resultado'];
        $aux['productos'] = $prods['resultado'];
        return $aux;
    }
    public function get_productos_categoria($id_cae){
        $productos = $this->con->sql("SELECT * FROM productos t1, cat_pros t2 WHERE t2.id_cae='".$id_cae."' AND t2.id_pro=t1.id_pro AND t1.eliminado='0'");
        return $productos['resultado'];
    }
    public function get_productos(){
        $productos = $this->con->sql("SELECT * FROM productos WHERE id_gir='".$this->id_gir."' AND eliminado='0'");
        return $productos['resultado'];
    }
    public function get_producto($id_pro){
        $productos = $this->con->sql("SELECT * FROM productos t1, productos_precio t2 WHERE t1.id_pro='".$id_pro."' AND t1.id_pro=t2.id_pro AND t2.id_cat='".$this->id_cat."'");
        return $productos['resultado'][0];
    }
    public function get_paginas(){
        $paginas = $this->con->sql("SELECT * FROM paginas WHERE id_gir='".$this->id_gir."' AND eliminado='0'");
        return $paginas['resultado'];
    }
    public function get_pagina($id_pag){
        $pagina = $this->con->sql("SELECT * FROM paginas WHERE id_pag='".$id_pag."' AND id_gir='".$this->id_gir."' AND eliminado='0'");
        return $pagina['resultado'][0];
    }
    public function get_polygons(){
        $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        $polygons = $this->con->sql("SELECT t3.nombre, t3.poligono, t3.precio, t3.id_loc FROM giros t1, locales t2, locales_tramos t3 WHERE t1.dominio='".$referer."' AND t1.id_gir=t2.id_gir AND t2.id_loc=t3.id_loc AND t2.eliminado='0' AND t3.eliminado='0'");
        return $polygons['resultado'];
    }
    public function ver_detalle($code){
        
        $info['op'] = false;
        $host = ($_SERVER["HTTP_HOST"] == "localhost") ? "www.izusushi.cl" : $_SERVER["HTTP_HOST"] ;
        $sql = $this->con->sql("SELECT * FROM pedidos_aux t1, locales t2, giros t3 WHERE t1.code='".$code."' AND t1.id_loc=t2.id_loc AND t2.id_gir=t3.id_gir AND t3.dominio='".$host."' AND t1.fecha > DATE_ADD(NOW(), INTERVAL -2 DAY) ");
        $path = ($_SERVER["HTTP_HOST"] == "localhost") ? "/restaurants" : "" ;
        $info['css_base'] = $path."/css/reset.css";
        $info['css_detalle'] = $path."/css/css_detalle_01.css";
            
        if($sql['count'] == 1){
        
            $info['js_jquery'] = $path."/js/jquery-1.3.2.min.js";
            $info['js_data'] = $path."/js/data/".$sql['resultado'][0]['code'].".js";
            $info['js_detalle'] = $path."/js/detalle.js";

            $info['id_ped'] = $sql['resultado'][0]['id_ped'];
            $aux_pep = $this->con->sql("SELECT * FROM pedidos_persona_posicion WHERE id_pep='".$sql['resultado'][0]['id_pep']."'");
            $info['pep'] = $aux_pep['resultado'][0];
            
            $info['carro'] = $sql['resultado'][0]['carro'];
            $info['promos'] = $sql['resultado'][0]['promos'];
            
            $info['pre_wasabi'] = $sql['resultado'][0]['pre_wasabi'];
            $info['pre_gengibre'] = $sql['resultado'][0]['pre_gengibre'];
            $info['pre_embarazadas'] = $sql['resultado'][0]['pre_embarazadas'];
            $info['pre_palitos'] = $sql['resultado'][0]['pre_palitos'];
            $info['pre_teriyaki'] = $sql['resultado'][0]['pre_teriyaki'];
            $info['pre_soya'] = $sql['resultado'][0]['pre_soya'];
            $info['despacho'] = $sql['resultado'][0]['despacho'];
            $info['comentarios'] = $sql['resultado'][0]['comentarios'];
            
            $info['costo'] = $sql['resultado'][0]['costo'];
            $info['total'] = $sql['resultado'][0]['total'];
            
            $info['verify_despacho'] = $sql['resultado'][0]['verify_despacho'];
            $info['verify_direccion'] = $aux_pep['resultado'][0]['verificado'];
            
            $info['op'] = true;
            
        }
        
        return $info;
        
    }
    public function add_pedido_moto($id_mot, $id_ped, $code){
        
        $data['id_mot'] = $id_mot;
        $data['id_ped'] = $id_ped;
        $data['code'] = $code;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://35.196.220.197/add_pedido_moto');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $info['db_h'] = curl_exec($ch);
        curl_close($ch);
        
    }
    public function rm_pedido_moto($id_mot, $id_ped){
        
        $data['id_mot'] = $id_mot;
        $data['id_ped'] = $id_ped;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://35.196.220.197/rm_pedido_moto');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $info['db_h'] = curl_exec($ch);
        curl_close($ch);
        
    }
    public function set_web_pedido(){
       
        $pedido = json_decode($_POST['pedido']);
        $carro = $pedido->{'carro'};
        $promos = $pedido->{'promos'};
        
        $id_ped = intval($pedido->{'id_ped'});
        $despacho = intval($pedido->{'despacho'});
        $estado = intval($pedido->{'estado'});
        
        $pre_gengibre = intval($pedido->{'pre_gengibre'});
        $pre_wasabi = intval($pedido->{'pre_wasabi'});
        $pre_embarazadas = intval($pedido->{'pre_embarazadas'});
        $pre_palitos = intval($pedido->{'pre_palitos'});
        $pre_soya = intval($pedido->{'pre_soya'});
        $pre_teriyaki = intval($pedido->{'pre_teriyaki'});
        
        $id_loc = $_COOKIE['ID'];
        $cookie_code = $_COOKIE['CODE'];
        
        $aux_local = $this->con->sql("SELECT * FROM locales WHERE id_loc='".$id_loc."' AND cookie_code='".$cookie_code."'");
        if($aux_local['count'] == 1){
            
            if($id_ped > 0){
                $sql_pedido = $this->con->sql("SELECT code FROM pedidos_aux WHERE id_ped='".$id_ped."' AND id_loc='".$id_loc."'");
                $code = $sql_pedido['resultado'][0]['code'];
            }
            if($id_ped == 0){
                $code = bin2hex(openssl_random_pseudo_bytes(10));
                $insert = $this->con->sql("INSERT INTO pedidos_aux (tipo, fecha, code, id_loc) VALUES ('0', now(), '".$code."', '".$id_loc."')");
                $id_ped = $insert['insert_id'];
            }
            
            $info['db'][] = $this->con->sql("UPDATE pedidos_aux SET carro='".json_encode($carro)."', promos='".json_encode($promos)."', despacho='".$despacho."', estado='".$estado."', pre_gengibre='".$pre_gengibre."', pre_wasabi='".$pre_wasabi."', pre_embarazadas='".$pre_embarazadas."', pre_palitos='".$pre_palitos."', pre_soya='".$pre_soya."', pre_teriyaki='".$pre_teriyaki."' WHERE id_ped='".$id_ped."' AND id_loc='".$id_loc."'");

        }
        
        $info['aux'] = $aux_local;
        
        /*
        $id_mot = intval($pedido->{'id_mot'});
        if($id_mot == 0 && $id_mot_aux != 0){
            // BORRAR PEDIDO MOTO
            $this->rm_pedido_moto($id_mot, $id_ped);
        }
        if($id_mot > 0){
            if($id_mot != $id_mot_aux){
                // ADD PEDIDO MOTO
                $this->add_pedido_moto($id_mot, $id_ped, $code);
                
            }
        }
        */
        
        return $info;
        
    }
    public function get_ultimos_pedidos($id_loc, $id_ped){
        
        $sql = ($id_ped == null) ? $this->con->sql("SELECT * FROM pedidos_aux WHERE id_loc='".$id_loc."' ORDER BY id_ped DESC") : $this->con->sql("SELECT * FROM pedidos_aux WHERE id_loc='".$id_loc."' AND id_ped='".$id_ped."'") ;
        for($i=0; $i<$sql['count']; $i++){
            
            $id_pep = $sql['resultado'][$i]['id_pep'];
            
            $res['id_ped'] = $sql['resultado'][$i]['id_ped'];
            $res['pedido_code'] = $sql['resultado'][$i]['code'];
            $res['tipo'] = $sql['resultado'][$i]['tipo'];
            $res['estado'] = $sql['resultado'][$i]['estado'];
            $res['fecha'] = strtotime($sql['resultado'][$i]['fecha']);
            $res['despacho'] = $sql['resultado'][$i]['despacho'];
            $res['carro'] = json_decode($sql['resultado'][$i]['carro']);
            $res['promos'] = json_decode($sql['resultado'][$i]['promos']);
            $res['pre_wasabi'] = $sql['resultado'][$i]['pre_wasabi'];
            $res['pre_gengibre'] = $sql['resultado'][$i]['pre_gengibre'];
            $res['pre_embarazadas'] = $sql['resultado'][$i]['pre_embarazadas'];
            $res['pre_palitos'] = $sql['resultado'][$i]['pre_palitos'];
            $res['pre_soya'] = $sql['resultado'][$i]['pre_soya'];
            $res['pre_teriyaki'] = $sql['resultado'][$i]['pre_teriyaki'];
            $res['verify_despacho'] = $sql['resultado'][$i]['verify_despacho'];
            
            $sql2 = $this->con->sql("SELECT * FROM pedidos_persona_posicion WHERE id_pep='".$id_pep."'");
            $res['nombre'] = $sql2['resultado'][0]['nombre'];
            $res['telefono'] = $sql2['resultado'][0]['telefono'];
            
            if($res['despacho'] == 1){
                
                $res['direccion'] = $sql2['resultado'][0]['direccion'];
                $res['lat'] = $sql2['resultado'][0]['lat'];
                $res['lng'] = $sql2['resultado'][0]['lng'];
                $res['calle'] = $sql2['resultado'][0]['calle'];
                $res['num'] = $sql2['resultado'][0]['num'];
                $res['depto'] = $sql2['resultado'][0]['depto'];
                $res['comuna'] = $sql2['resultado'][0]['num'];
                
            }
            
            
            $aux[] = $res;
            unset($res);
            
        }
        return $aux;
        
    }
    public function socket_code($id_loc, $id_gir){
        
        $aux = $this->con->sql("SELECT * FROM locales WHERE id_loc='".$id_loc."' && id_gir='".$id_gir."'");
        return $aux['resultado'][0]['code'];
        
    }
    public function get_data($dom){
        
        $dominio = ($dom !== null) ? $dom : $_SERVER["HTTP_HOST"];
        $path = ($_SERVER["HTTP_HOST"] == "localhost") ? "/restaurants" : "" ;
        $sql = $this->con->sql("SELECT * FROM giros WHERE dominio='".$dominio."'");
        
        if($sql['count'] == 1){
            
            $info['dominio'] = $dominio;
            $info['id_gir'] = $sql['resultado'][0]['id_gir'];            
            $info['titulo'] = $sql['resultado'][0]['titulo'];
            $info['logo'] = $sql['resultado'][0]['logo'];
            $info['favicon'] = $sql['resultado'][0]['favicon'];
            $info['font']['family'] = $sql['resultado'][0]['font_family'];
            $info['font']['css'] = $sql['resultado'][0]['font_css'];
            $info['code'] = $sql['resultado'][0]['code'];
            $info['footer_html'] = $sql['resultado'][0]['footer_html'];
            $info['retiro_local'] = $sql['resultado'][0]['retiro_local'];
            $info['despacho_domicilio'] = $sql['resultado'][0]['despacho_domicilio'];
            $info['lista_locales'] = $sql['resultado'][0]['lista_locales'];
            $info['con_cambios'] = $sql['resultado'][0]['con_cambios'];
            $info['desde'] = $sql['resultado'][0]['desde'];
            
            $info['pedido_wasabi'] = $sql['resultado'][0]['pedido_wasabi'];
            $info['pedido_gengibre'] = $sql['resultado'][0]['pedido_gengibre'];
            $info['pedido_embarazadas'] = $sql['resultado'][0]['pedido_embarazadas'];
            $info['pedido_palitos'] = $sql['resultado'][0]['pedido_palitos'];
            $info['pedido_comentarios'] = $sql['resultado'][0]['pedido_comentarios'];
            $info['pedido_soya'] = $sql['resultado'][0]['pedido_soya'];
            $info['pedido_teriyaki'] = $sql['resultado'][0]['pedido_teriyaki'];
            
            $info['css_reset'] = $path."/css/reset.css";
            $info['css_base'] = $path."/css/css_base.css";
            $info['css_tipo'] = $path."/css/".$sql['resultado'][0]['style_page'];
            $info['css_color'] = $path."/css/".$sql['resultado'][0]['style_color'];
            $info['css_font_size'] = $path."/css/".$sql['resultado'][0]['style_modal'];
            $info['css_pos'] = $path."/css/pos.css";
            
            $info['js_jquery'] = $path."/js/jquery-1.3.2.min.js";
            $info['js_data'] = $path."/js/data/".$info["code"].".js";
            
            $info['js_html_func'] = $path."/js/html_func.js";
            $info['js_base'] = $path."/js/base.js";
            $info['js_base_lista'] = $path."/js/base_lista.js";
            $info['js_pos'] = $path."/js/pos.js";
            $info['js_pos_lista'] = $path."/js/pos_lista.js";
            
            $info['header_fixed'] = 1;
            $info['footer_fixed'] = 0;
            
            $info['pedido_01_titulo'] = $sql['resultado'][0]['pedido_01_titulo'];
            $info['pedido_01_subtitulo'] = $sql['resultado'][0]['pedido_01_subtitulo'];
            $info['pedido_02_titulo'] = $sql['resultado'][0]['pedido_02_titulo'];
            $info['pedido_02_subtitulo'] = $sql['resultado'][0]['pedido_02_subtitulo'];
            
            $info['pedido_03_titulo'] = $sql['resultado'][0]['pedido_03_titulo'];
            $info['pedido_03_subtitulo'] = $sql['resultado'][0]['pedido_03_subtitulo'];
            $info['pedido_04_titulo'] = $sql['resultado'][0]['pedido_04_titulo'];
            $info['pedido_04_subtitulo'] = $sql['resultado'][0]['pedido_04_subtitulo'];
            
            $info['ultima_actualizacion'] = $sql['resultado'][0]['ultima_actualizacion'];
            
            $sql_motos = $this->con->sql("SELECT * FROM motos WHERE id_gir='".$info['id_gir']."'");
            $info['motos'] = $sql_motos['resultado'];
            
        }
        
        if($sql['count'] == 0){
            
            $info['id_gir'] = 0;
            
        }

        return $info;
        
    }
    public function get_info_catalogo($id_cat){
        
        $aux_prods = [];
        $categorias = $this->con->sql("SELECT * FROM categorias WHERE id_cat='".$id_cat."' AND eliminado='0' ORDER BY orders");
        for($i=0; $i<$categorias['count']; $i++){
            
            $aux_categoria['id_cae'] = $categorias['resultado'][$i]['id_cae'];
            $aux_categoria['parent_id'] = $categorias['resultado'][$i]['parent_id'];
            $aux_categoria['nombre'] = $categorias['resultado'][$i]['nombre'];
            $aux_categoria['ocultar'] = $categorias['resultado'][$i]['ocultar'];
            $aux_categoria['image'] = $categorias['resultado'][$i]['image'];
            $aux_categoria['mostrar_prods'] = $categorias['resultado'][$i]['mostrar_prods'];
            $aux_categoria['detalle_prods'] = $categorias['resultado'][$i]['detalle_prods'];
            $aux_categoria['descripcion'] = $categorias['resultado'][$i]['descripcion'];
            $aux_categoria['descripcion_sub'] = $categorias['resultado'][$i]['descripcion_sub'];
            $aux_categoria['precio'] = $categorias['resultado'][$i]['precio'];
            
            if($categorias['resultado'][$i]['tipo'] == 0){
                
                $aux_categoria['tipo'] = 0;
                $prods_sql = $this->con->sql("SELECT * FROM cat_pros t1, productos t2, productos_precio t3 WHERE t1.id_cae='".$categorias['resultado'][$i]['id_cae']."' AND t1.id_pro=t2.id_pro AND t1.id_pro=t3.id_pro AND t3.id_cat='".$id_cat."'");
                for($j=0; $j<$prods_sql['count']; $j++){
                    $aux_categoria['productos'][] = $prods_sql['resultado'][$j]['id_pro'];
                    if(!in_array($prods_sql['resultado'][$j]['id_pro'], $aux_prods)){
                        
                        $aux_productos['id_pro'] = $prods_sql['resultado'][$j]['id_pro'];
                        $aux_productos['nombre'] = $prods_sql['resultado'][$j]['nombre'];
                        $aux_productos['numero'] = $prods_sql['resultado'][$j]['numero'];
                        $aux_productos['descripcion'] = $prods_sql['resultado'][$j]['descripcion'];
                        $aux_productos['precio'] = $prods_sql['resultado'][$j]['precio'];
                        
                        $aux_prods[] = $prods_sql['resultado'][$j]['id_pro'];
                        
                        $pre_pro_sql = $this->con->sql("SELECT * FROM preguntas_productos WHERE id_pro='".$prods_sql['resultado'][$j]['id_pro']."'");
                        for($k=0; $k<$pre_pro_sql['count']; $k++){
                            $aux_productos['preguntas'][] = $pre_pro_sql['resultado'][$k]['id_pre'];
                        }

                        $info['productos'][] = $aux_productos;
                        unset($aux_productos);
                        
                    }
                }
                
            }
            
            if($categorias['resultado'][$i]['tipo'] == 1){
                
                $aux_categoria['tipo'] = 1;    
                $promo_cats = $this->con->sql("SELECT id_cae2 as id_cae, cantidad FROM promocion_categoria WHERE id_cae1='".$categorias['resultado'][$i]['id_cae']."'");
                $promo_prods = $this->con->sql("SELECT id_pro, cantidad FROM promocion_productos WHERE id_cae='".$categorias['resultado'][$i]['id_cae']."'");

                for($j=0; $j<$promo_cats['count']; $j++){
                    $aux_prm_cat['id_cae'] = $promo_cats['resultado'][$j]['id_cae'];
                    $aux_prm_cat['cantidad'] = $promo_cats['resultado'][$j]['cantidad'];
                    $aux_categoria['categorias'][] = $aux_prm_cat;
                    unset($aux_prm_cat);
                }

                for($j=0; $j<$promo_prods['count']; $j++){
                    $aux_prm_pro['id_pro'] = $promo_prods['resultado'][$j]['id_pro'];
                    $aux_prm_pro['cantidad'] = $promo_prods['resultado'][$j]['cantidad'];
                    $aux_categoria['productos'][] = $aux_prm_pro;
                    unset($aux_prm_pro);
                }
                
            }
            
            $info['categorias'][] = $aux_categoria;
            
            unset($aux_categoria);
            
        }
        
        $info['preguntas'] = $this->get_info_preguntas($id_cat);
        
        return $info;
        
    }
    public function get_config($id_gir){
        
        $aux_config = $this->con->sql("SELECT retiro_local, despacho_domicilio, desde FROM giros WHERE id_gir='".$id_gir."' AND eliminado='0'");
        $aux = $aux_config['resultado'][0];
        return $aux;
        
    }
    public function get_web_js_data2($id_gir){
    
        $giro = $this->con->sql("SELECT t2.id_cat, t1.code FROM giros t1, catalogo_productos t2 WHERE t1.id_gir='".$id_gir."' AND t1.id_gir=t2.id_gir");
        
        $aux_pagina = $this->con->sql("SELECT id_pag, nombre, titulo, subtitulo, html FROM paginas WHERE id_gir='".$id_gir."' AND eliminado='0'");
        $info['paginas'] = $aux_pagina['resultado'];
        
        $info['config'] = $this->get_config($id_gir);
        
        for($i=0; $i<$giro['count']; $i++){
            $info['catalogos'][] = $this->get_info_catalogo($giro['resultado'][$i]['id_cat']);
        }
        
        if($_SERVER['HTTP_HOST'] == "localhost"){
            $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
        }else{
            $path = "/var/www/html/restaurants/";
        }

        $ruta_data = $path."js/data/".$giro['resultado'][0]['code'].".js";
        file_put_contents($ruta_data, "var data=".json_encode($info));
        $this->con->sql("UPDATE giros SET ultima_actualizacion=now(), con_cambios='0' WHERE id_gir='".$id_gir."'");
        
    }
    public function set_pedido($pedido, $carro, $promos){
        
        $aux_ped = json_decode($pedido);
        
        $info['despacho'] = $aux_ped->{'despacho'};
        $info['lat'] = $aux_ped->{'lat'};
        $info['lng'] = $aux_ped->{'lng'};
        $info['direccion'] = $aux_ped->{'direccion'};
        $info['comuna'] = $aux_ped->{'comuna'};
        $info['num'] = $aux_ped->{'num'};
        $info['estado'] = 0;
        $info['calle'] = $aux_ped->{'calle'};
        $info['costo'] = $aux_ped->{'costo'};
        $info['total'] = $aux_ped->{'total'};
        $info['id_loc'] = $aux_ped->{'id_loc'};
        
        $code = bin2hex(openssl_random_pseudo_bytes(10));
        
        $pedido_sql = $this->con->sql("INSERT INTO pedidos (code, fecha, lat, lng, despacho, costo, total, comuna, calle, num, id_loc, aux_02, aux_03) VALUES ('".$code."', now(), '".$info['lat']."', '".$info['lng']."', '".$info['despacho']."', '".$info['costo']."', '".$info['total']."', '".$info['comuna']."', '".$info['calle']."', '".$info['num']."', '".$info['id_loc']."', '".$carro."', '".$promos."')");
        
        $info['id_ped'] = $pedido_sql['insert_id'];
        $info['local_code'] = "anb7sd-12s9ksm";
        $info['pedido_code'] = $code;
        
        return $info;
        
    }
    public function get_info_preguntas($id_cat){
        
        $preguntas_sql = $this->con->sql("SELECT * FROM preguntas WHERE id_cat='".$id_cat."' AND eliminado='0'");
        for($k=0; $k<$preguntas_sql['count']; $k++){

            $aux_pre['id_pre'] = $preguntas_sql['resultado'][$k]['id_pre'];
            $aux_pre['nombre'] = $preguntas_sql['resultado'][$k]['mostrar'];
            
            $pre_val_sql = $this->con->sql("SELECT * FROM preguntas_valores WHERE id_pre='".$aux_pre['id_pre']."'");
            
            for($m=0; $m<$pre_val_sql['count']; $m++){
                $aux_pre_val['cantidad'] = $pre_val_sql['resultado'][$m]['cantidad'];
                $aux_pre_val['nombre'] = $pre_val_sql['resultado'][$m]['nombre'];
                $aux_pre_val['valores'] = json_decode($pre_val_sql['resultado'][$m]['valores']);
                $aux_pre['valores'][] = $aux_pre_val;
            }
            
            $preguntas[] = $aux_pre;
            unset($aux_pre);

        }
        return $preguntas;
        
    }
    public function get_web_js_data($id_gir){
        
        $giros_sql = $this->con->sql("SELECT code, catalogo FROM giros WHERE id_gir='".$id_gir."'");
        $code = $giros_sql['resultado'][0]['code'];
        
        $cat_sql = $this->con->sql("SELECT t3.id_cae, t3.parent_id, t3.nombre, t3.ocultar, t3.tipo, t3.mostrar_prods, t3.detalle_prods, t3.image, t3.descripcion FROM giros t1, catalogo_productos t2, categorias t3 WHERE t1.id_gir='".$id_gir."' AND t1.id_gir=t2.id_gir AND t2.id_cat=t3.id_cat ORDER BY t3.orders");
        $cats = $cat_sql['resultado'];
        
        for($i=0; $i<count($cats); $i++){
            
            $aux['id_cae'] = intval($cats[$i]['id_cae']);
            $aux['parent_id'] = intval($cats[$i]['parent_id']);
            $aux['descripcion'] = $cats[$i]['descripcion'];
            $aux['nombre'] = $cats[$i]['nombre'];
            $aux['ocultar'] = intval($cats[$i]['ocultar']);
            $aux['mostrar_prods'] = intval($cats[$i]['mostrar_prods']);
            $aux['detalle_prods'] = intval($cats[$i]['detalle_prods']);
            $aux['image'] = $cats[$i]['image'];
            
            if($cats[$i]['tipo'] == 0){
                
                $aux['tipo'] = 0;

                $prods_sql = $this->con->sql("SELECT * FROM cat_pros t1, productos t2 WHERE t1.id_cae='".$aux['id_cae']."' AND t1.id_pro=t2.id_pro");
                $prods = $prods_sql['resultado'];

                for($j=0; $j<count($prods); $j++){

                    $aux_productos['id_pro'] = $prods[$j]['id_pro'];
                    $aux_productos['nombre'] = $prods[$j]['nombre'];
                    $aux_productos['numero'] = $prods[$j]['numero'];
                    $aux_productos['descripcion'] = $prods[$j]['descripcion'];
                    $aux_productos['simple_txt'] = json_decode($prods[$j]['simple_txt']);

                    $aux['productos'][] = $aux_productos['id_pro'];

                    $pre_pro_sql = $this->con->sql("SELECT * FROM preguntas_productos WHERE id_pro='".$aux_productos['id_pro']."'");
                    $pre_pro = $pre_pro_sql['resultado'];
                    for($k=0; $k<count($pre_pro); $k++){
                        $aux_productos['preguntas'][] = $pre_pro[$k]['id_pre'];
                    }
                    
                    $aux_return['productos'][] = $aux_productos;
                    unset($aux_productos);

                }
                
                
            }
            if($cats[$i]['tipo'] == 1){
                
                $promo_cats = $this->con->sql("SELECT id_cae2 as id_cae, cantidad FROM promocion_categoria WHERE id_cae1='".$cats[$i]['id_cae']."'");
                $promo_c = $promo_cats['resultado'];
                $promo_prods = $this->con->sql("SELECT * FROM promocion_productos WHERE id_cae='".$cats[$i]['id_cae']."'");
                $promo_p = $promo_prods['resultado'];

                $aux['tipo'] = 1;
                
                for($j=0; $j<count($promo_c); $j++){
                    $aux_prm_cat['id_cae'] = $promo_c[$j]['id_cae'];
                    $aux_prm_cat['cantidad'] = $promo_c[$j]['cantidad'];
                    $aux['categorias'][] = $aux_prm_cat;
                    unset($aux_prm_cat);
                }

                for($j=0; $j<count($promo_p); $j++){
                    $aux_prm_pro['id_pro'] = $promo_p[$j]['id_pro'];
                    $aux_prm_pro['cantidad'] = $promo_p[$j]['cantidad'];
                    $aux['productos'][] = $aux_prm_pro;
                    unset($aux_prm_pro);
                }
                
            }
            $aux_return['categorias'][] = $aux;
            unset($aux);
            
        }
        
        $preguntas_sql = $this->con->sql("SELECT t3.id_pre, t3.mostrar FROM giros t1, catalogo_productos t2, preguntas t3 WHERE t1.id_gir='".$id_gir."' AND t1.id_gir=t2.id_gir AND t2.id_cat=t3.id_cat");
        $preguntas = $preguntas_sql['resultado'];
        $aux_return['preguntas'] = [];
        for($k=0; $k<count($preguntas); $k++){

            $aux_pregunta['id_pre'] = $preguntas[$k]['id_pre'];
            $aux_pregunta['nombre'] = $preguntas[$k]['mostrar'];
            
            $pre_val_sql = $this->con->sql("SELECT * FROM preguntas_valores WHERE id_pre='".$aux_pregunta['id_pre']."'");
            $pre_val = $pre_val_sql['resultado'];
            
            for($m=0; $m<count($pre_val); $m++){
                $aux_pre_val['cantidad'] = $pre_val[$m]['cantidad'];
                $aux_pre_val['valores'] = json_decode($pre_val[$m]['valores']);
                $aux_pregunta['valores'][] = $aux_pre_val;
            }
            
            $aux_return['preguntas'][] = $aux_pregunta;
            unset($aux_pregunta);

        }

        
        
        if($_SERVER['HTTP_HOST'] == "localhost"){
            $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
        }else{
            $path = "/var/www/html/restaurants/";
        }
        
        //require($path."html/html.php");
        
        $ruta_data = $path."js/data/".$code.".js";
        file_put_contents($ruta_data, "var data=".json_encode($aux_return));
        
        //$ruta_html = $path."js/html/".$code.".js";
        //file_put_contents($ruta_html, "var html=".json_encode($html));
        return $aux_return;

    }
    public function get_arbol_productos($that){
        $cats = $this->con->sql("SELECT t1.id_cae, t1.nombre as cat_nombre, t1.parent_id, t2.id_pro, t3.nombre as prod_nombre FROM categorias t1 LEFT JOIN cat_pros t2 ON t1.id_cae=t2.id_cae LEFT JOIN productos t3 ON t2.id_pro=t3.id_pro WHERE t1.id_cat='".$this->id_cat."' AND t1.eliminado='0' AND tipo='0'");
        return $this->process_arbol_draw($cats['resultado'], 0, $that);
    }
    public function list_arbol_cats_prods(){
        $cats = $this->con->sql("SELECT t1.id_cae, t1.nombre as cat_nombre, t1.parent_id, t2.id_pro, t3.nombre as prod_nombre FROM categorias t1 LEFT JOIN cat_pros t2 ON t1.id_cae=t2.id_cae LEFT JOIN productos t3 ON t2.id_pro=t3.id_pro WHERE t1.id_cat='".$this->id_cat."' AND t1.eliminado='0' AND tipo='0'");
        return $cats['resultado'];
    }
    public function get_inputs($id_cae){
        $cats = $this->con->sql("SELECT t3.id_prc, t3.nombre, t3.campo FROM categorias t1, productos_campos_categorias t2, productos_campos t3 WHERE t1.id_cae='".$id_cae."' AND t1.id_cae=t2.id_cae AND t2.id_prc=t3.id_prc");
        for($i=0; $i<$cats['count']; $i++){
            $aux['id_prc'] = $cats['resultado'][$i]['id_prc'];
            $aux['nombre'] = $cats['resultado'][$i]['nombre'];
            $aux['campo'] = $cats['resultado'][$i]['campo'];
            $this->require[] = $aux;
            unset($aux);
        }
        if($cats['resultado'][0]['parent_id'] != 0){
            $this->get_inputs($cats['resultado'][0]['parent_id']);
        }else{
            $giros = $this->aux = $this->con->sql("SELECT t4.id_prc, t4.nombre, t4.campo FROM categorias t1, catalogo_productos t2, productos_campos_giros t3, productos_campos t4 WHERE t1.id_cae='".$id_cae."' AND t1.id_cat=t2.id_cat AND t2.id_gir=t3.id_gir AND t3.id_prc=t4.id_prc");
            for($j=0; $j<$giros['count']; $j++){
                $aux['id_prc'] = $giros['resultado'][$j]['id_prc'];
                $aux['nombre'] = $giros['resultado'][$j]['nombre'];
                $aux['campo'] = $giros['resultado'][$j]['campo'];
                $this->require[] = $aux;
                unset($aux);
            }
        }
    }
    public function show_inputs(){
        return $this->require;
    }
    public function process_categorias($cats, $id){
        $res = [];
        for($i=0; $i<count($cats); $i++){
            if(count($res) == 0){
                $res[] = $cats[$i];
            }else{
                $repeat = false;
                for($j=0; $j<count($res); $j++){
                    if($res[$j][$id] == $cats[$i][$id]){
                        $repeat = true;
                    }
                }
                if(!$repeat){ $res[] = $cats[$i]; }
            }
        }
        return $res;
    }
    public function process_arbol($cats, $parent_id){
        
        $res = [];
        $in = [];
        for($i=0; $i<count($cats); $i++){
            
            $cat = $cats[$i];
            if($cat['parent_id'] == $parent_id && !in_array($cat['id_cae'], $in)){
                
                $aux['id_cae'] = $cat['id_cae'];
                $aux['nombre'] = $cat['cat_nombre'];
                
                if($cat['id_pro'] !== null){
                    $prods = $this->process_productos($cats, $cat['id_cae']);
                    if(count($prods) > 0){ $aux['prods'] = $prods; }
                }else{
                    $childs = $this->process_arbol($cats, $cat['id_cae']);
                    if(count($childs) > 0){ $aux['childs'] = $childs; }
                }
                $res[] = $aux;
                $in[] = $aux['id_cae'];
                unset($aux);
                
            }
            
        }
        return $res;
        
    }
    public function get_select($id, $cantidad, $selected){
        
        $select = "<select id='".$id."' class='select_arbol'>";
        for($i=0; $i<$cantidad; $i++){ if($i == $selected){ $select .="<option value='".$i."' selected>".$i."</option>"; }else{ $select .="<option value='".$i."'>".$i."</option>"; } }
        $select .="</select>";
        return $select;
        
    }
    public function process_arbol_draw($cats, $parent_id, $that){
        
        $in = [];
        $div = "<div class='parent_arbol'>";
        for($i=0; $i<count($cats); $i++){
            $cat = $cats[$i];
            if($cat['parent_id'] == $parent_id && !in_array($cat['id_cae'], $in)){
                
                $cantidad = 0;
                $child_display = 'block';
                for($x=0; $x<count($that['categorias']); $x++){
                    if($cat['id_cae'] == $that['categorias'][$x]['id_cae']){
                        $cantidad = $that['categorias'][$x]['cantidad'];
                        $child_display = ($cantidad > 0) ? 'none' : 'block'; 
                    }
                }
                
                $aux['id_cae'] = $cat['id_cae'];
                $aux['nombre'] = $cat['cat_nombre'];
                $div .= "<div class='bottom_arbol'><div class='clearfix'><div class='cantidad_arbol'>".$this->get_select("sel-cae-".$aux['id_cae'], 1000, $cantidad)."</div><div class='nombre_arbol' style='font-size: 16px'>".$aux['nombre']."</div></div>";
                if($cat['id_pro'] !== null){
                    $prods = $this->process_productos_draw($cats, $cat['id_cae'], $that);
                    if(count($prods) > 0){ 
                        $div .= "<div class='left_arbol' style='display: ".$child_display."'>".$prods."</div>";
                    }
                }else{
                    $childs = $this->process_arbol_draw($cats, $cat['id_cae'], $that);
                    if(count($childs) > 0){ 
                        $div .= "<div class='left_arbol' style='display: ".$child_display."'>".$childs."</div>";
                    }
                }
                $div .= "</div>";
                $in[] = $aux['id_cae'];
            }
            
        }
        $div .= "</div>";
        return $div;
        
    }
    public function process_productos_draw($cats, $id_cae, $that){
        
        $div = "<div style='parent_arbol'>";
        for($i=0; $i<count($cats); $i++){
            
            $cat = $cats[$i];
            if($cat['id_cae'] == $id_cae && $cat['id_pro'] !== null){
                $cantidad = 0;
                for($x=0; $x<count($that['productos']); $x++){
                    if($that['productos'][$x]['id_pro'] == $cat['id_pro']){
                        $cantidad = $that['productos'][$x]['cantidad'];
                    }
                }
                $div .= "<div class='clearfix'><div class='cantidad_arbol'>".$this->get_select("sel-pro-".$cat['id_pro'], 1000, $cantidad)."</div><div class='nombre_arbol'>".$cat['prod_nombre']."</div></div>";
            }
        }
        $div .= "</div>";
        return $div;
        
    }
    public function process_productos($cats, $id_cae){
        
        $res = [];
        for($i=0; $i<count($cats); $i++){
            $cat = $cats[$i];
            if($cat['id_cae'] == $id_cae && $cat['id_pro'] !== null){
                            
                $aux['id_pro'] = $cat['id_pro'];
                $aux['nombre'] = $cat['prod_nombre'];
                $res[] = $aux;
                unset($aux);
                
            }
        }
        return $res;
        
    }
    public function process_promos($promos){
        $res = [];
        for($i=0; $i<count($promos); $i++){
            if(count($res) == 0){
                $res[] = $promos[$i];
            }else{
                $repeat = false;
                for($j=0; $j<count($res); $j++){
                    if($res[$j]['id_prm'] == $cats[$i]['id_prm']){
                        $repeat = true;
                    }
                }
                if(!$repeat){ $res[] = $promos[$i]; }
            }
        }
        return $res;
    }
}
?>