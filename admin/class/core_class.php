<?php
session_start();

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
            if($count['count'] == 1){
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
    public function get_giros(){
        $giros = $this->con->sql("SELECT * FROM giros WHERE id_user='".$this->id_user."' AND eliminado='0'");
        return $giros['resultado'];
    }
    public function get_giros_user(){
        if($this->admin == 0){ $giros = $this->con->sql("SELECT t2.id_gir, t2.nombre, t2.dominio FROM fw_usuarios_giros t1, giros t2 WHERE t1.id_user='".$this->id_user."' AND t1.id_gir=t2.id_gir AND t2.eliminado='0'"); }
        if($this->admin == 1){ $giros = $this->con->sql("SELECT t2.id_gir, t2.nombre, t2.dominio FROM fw_usuarios_giros_clientes t1, giros t2 WHERE t1.id_user='".$this->id_user."' AND t1.id_gir=t2.id_gir AND t2.eliminado='0'"); }
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
    public function get_preguntas(){
        $pres = $this->con->sql("SELECT * FROM preguntas WHERE id_cat='".$this->id_cat."' AND eliminado='0'");
        return $pres['resultado'];
    }
    public function get_preguntas_pro($id_pro){
        $pres = $this->con->sql("SELECT * FROM preguntas_productos WHERE id_pro='".$id_pro."'");
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
        $cats = $this->con->sql("SELECT id_cae2 as id_cae, cantidad FROM promocion_categoria WHERE id_cae1='".$id_cae."'");
        $prods = $this->con->sql("SELECT id_pro, cantidad FROM promocion_productos WHERE id_cae='".$id_cae."'");
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
        $referer = "www.runasushi.cl";
        $polygons = $this->con->sql("SELECT t3.nombre, t3.poligono, t3.precio, t3.id_loc FROM giros t1, locales t2, locales_tramos t3 WHERE t1.dominio='".$referer."' AND t1.id_gir=t2.id_gir AND t2.id_loc=t3.id_loc AND t2.eliminado='0' AND t3.eliminado='0'");
        return $polygons['resultado'];
    }
    
    public function get_data($dom){
        
        $info['op'] = 0;
        $dominio = ($dom !== null) ? $dom : $_SERVER["HTTP_HOST"];
        $path = ($_SERVER["HTTP_HOST"] == "localhost") ? "/restaurants" : "" ;
        $sql = $this->con->sql("SELECT * FROM giros WHERE dominio='".$dominio."'");
        if(count($sql['resultado']) == 1){
            
            $info['id_gir'] = $sql['resultado'][0]['id_gir'];            
            $info['titulo'] = $sql['resultado'][0]['titulo'];
            $info['logo'] = $sql['resultado'][0]['logo'];
            $info['font']['family'] = $sql['resultado'][0]['font_family'];
            $info['font']['css'] = $sql['resultado'][0]['font_css'];
            $info['code'] = $sql['resultado'][0]['code'];
            
            $info['css_base'] = $path."/css/style.css";
            $info['css_style'] = $path."/css/types/".$sql['resultado'][0]['style_page'];
            $info['css_color'] = $path."/css/colors/".$sql['resultado'][0]['style_color'];
            $info['css_modals'] = $path."/css/modals/".$sql['resultado'][0]['style_modal'];
            
            $info['js_jquery'] = $path."/js/jquery-1.3.2.min.js";
            $info['js_data'] = $path."/js/data/".$info["code"].".js";
            $info['js_html'] = $path."/js/html/".$info["code"].".js";
            
            $info['js_html_func'] = $path."/js/html_func.js";
            $info['js_base'] = $path."/js/base.js";
            
            $info['header_fixed'] = 1;
            $info['footer_fixed'] = 0;

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
    public function get_web_js_data2($id_gir){
    
        $giro = $this->con->sql("SELECT t2.id_cat, t1.code FROM giros t1, catalogo_productos t2 WHERE t1.id_gir='".$id_gir."' AND t1.id_gir=t2.id_gir");

        for($i=0; $i<$giro['count']; $i++){
            $info['catalogos'][] = $this->get_info_catalogo($giro['resultado'][$i]['id_cat']);
        }
        
        $paginas_sql = $this->con->sql("SELECT * FROM paginas WHERE id_gir='".$id_gir."'");
        for($k=0; $k<$paginas_sql['count']; $k++){
            $aux_pagina['id_pag'] = $paginas_sql['resultado'][$k]['id_pag'];
            $aux_pagina['nombre'] = $paginas_sql['resultado'][$k]['nombre'];
            $info['paginas'][] = $aux_pagina;
            unset($aux_pagina);
        }
        
        if($_SERVER['HTTP_HOST'] == "localhost"){
            $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
        }else{
            $path = "/var/www/html/restaurants/";
        }
        
        $ruta_data = $path."js/data/".$giro['resultado'][0]['code'].".js";
        file_put_contents($ruta_data, "var data=".json_encode($info));
        
    }
    
    public function get_info_preguntas($id_cat){
        
        $preguntas_sql = $this->con->sql("SELECT * FROM preguntas WHERE id_cat='".$id_cat."' AND eliminado='0'");
        for($k=0; $k<$preguntas_sql['count']; $k++){

            $aux_pre['id_pre'] = $preguntas_sql['resultado'][$k]['id_pre'];
            $aux_pre['nombre'] = $preguntas_sql['resultado'][$k]['nombre'];
            
            $pre_val_sql = $this->con->sql("SELECT * FROM preguntas_valores WHERE id_pre='".$aux_pre['id_pre']."'");
            
            for($m=0; $m<$pre_val_sql['count']; $m++){
                $aux_pre_val['cantidad'] = $pre_val_sql['resultado'][$m]['cantidad'];
                $aux_pre_val['valores'] = json_decode($pre_val_sql['resultado'][$m]['valores']);
                $aux_pre['valores'][] = $aux_pre_val;
            }
            
            $preguntas[] = $aux_pre;
            

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