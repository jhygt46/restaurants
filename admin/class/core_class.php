<?php
session_start();

require_once 'mysql_class.php';

class Core{
    
    public $con = null;
    public $id_user = null;
    public $id_org = null;
    public $require = null;
    public $aux = null;
    public $id_gir = null;

    public function __construct(){
        $this->con = new Conexion();
        $this->id_user = $_SESSION['user']['info']['id_user'];
        $this->id_gir = $_SESSION['user']['giro']['id_gir'];
        $this->id_cat = $_SESSION['user']['id_cat'];
        $this->require = [];
        $this->aux = "";
        /*
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
        */
    }
    public function test(){
        print_r($this->con->sql("SELECT * FROM fw_usuarios"));
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
        if($_SESSION['user']['info']['admin'] == 0){
            if($_SESSION['user']['giro']['id_gir'] != $id_gir){
                exit;
            }
        }
        if($_SESSION['user']['info']['admin'] == 1){
            for($i=0; $i<count($_SESSION['user']['giros']); $i++){
                if($_SESSION['user']['giros'][$i]['id_gir'] == $id_gir){
                    $_SESSION['user']['giro']['id_gir'] = $id_gir;
                    $this->id_gir = $id_gir;
                }
            }
        }
    }
    public function get_giros(){
        $giros = $this->con->sql("SELECT * FROM giros WHERE id_user='".$this->id_user."' AND eliminado='0'");
        return $giros['resultado'];
    }
    public function get_giros_user(){
        $giros = $this->con->sql("SELECT * FROM fw_usuarios_giros t1, giros t2 WHERE t1.id_user='".$this->id_user."' AND t1.id_gir=t2.id_gir");
        return $giros['resultado'];
    }
    public function get_giro($id){
        $giros = $this->con->sql("SELECT * FROM giros WHERE id_gir='".$id."' AND eliminado='0'");
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
    public function get_usuarios($tipo){
        if($tipo == "admin"){
            $usuarios = $this->con->sql("SELECT id_user, nombre FROM fw_usuarios WHERE admin='1' AND eliminado='0'");
        }
        if($tipo == "giro"){
            $usuarios = $this->con->sql("SELECT t1.id_user, t1.nombre FROM fw_usuarios t1, fw_usuarios_giros_clientes t2 WHERE t2.id_gir='".$this->id_gir."' AND t2.id_user=t1.id_user AND t1.eliminado='0'");
        }
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
    public function get_catalogo($id_cat){
        $giros = $this->con->sql("SELECT * FROM catalogo_productos WHERE id_cat='".$id_cat."' AND eliminado='0'");
        return $giros['resultado'][0];
    }
    public function get_locales($id_gir){
        $giros = $this->con->sql("SELECT * FROM locales WHERE id_gir='".$id_gir."' AND eliminado='0'");
        return $giros['resultado'];
    }
    public function get_local($id_gir, $id_loc){
        $giros = $this->con->sql("SELECT * FROM locales WHERE id_gir='".$id_gir."' AND id_loc='".$id_loc."' AND eliminado='0'");
        return $giros['resultado'][0];
    }
    public function get_categorias($id_cat){
        $cats = $this->con->sql("SELECT DISTINCT t1.id_cae, t1.nombre, t1.parent_id, t2.id_pro FROM categorias t1 LEFT JOIN cat_pros t2 ON t1.id_cae=t2.id_cae WHERE t1.id_cat='".$id_cat."' AND t1.eliminado='0'");
        return $this->process_categorias($cats['resultado'], 'id_cae');
    }
    public function get_ingredientes($id_cat){
        $cats = $this->con->sql("SELECT * FROM ingredientes WHERE id_cat='".$id_cat."' AND eliminado='0'");
        return $this->process_categorias($cats['resultado'], 'id_ing');
    }
    public function get_preguntas($id_cat){
        $pres = $this->con->sql("SELECT * FROM preguntas WHERE id_cat='".$id_cat."' AND eliminado='0'");
        return $pres['resultado'];
    }
    public function get_pregunta($id_cat, $id_pre){
        $pre = $this->con->sql("SELECT * FROM preguntas WHERE id_cat='".$id_cat."' AND id_pre='".$id_pre."' AND eliminado='0'");
        return $pre['resultado'][0];
    }
    public function get_pregunta_valores($id_pre){
        $pre = $this->con->sql("SELECT * FROM preguntas_valores WHERE id_pre='".$id_pre."'");
        return $pre['resultado'];
    }
    public function get_categoria($id_cat, $id_cae){
        $cats = $this->con->sql("SELECT * FROM categorias WHERE id_cat='".$id_cat."' AND id_cae='".$id_cae."' AND eliminado='0'");
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
    public function get_promociones($id_cat){
        $promos = $this->con->sql("SELECT DISTINCT t1.id_prm, t1.nombre, t1.parent_id, t2.id_pro FROM promociones t1 LEFT JOIN promocion_productos t2 ON t1.id_prm=t2.id_prm WHERE t1.id_cat='".$id_cat."' AND t1.eliminado='0'");
        return $this->process_categorias($promos['resultado'], 'id_prm');
    }
    public function get_promocion($id_prm){
        $cats = $this->con->sql("SELECT id_cae, cantidad FROM promocion_categoria WHERE id_prm='".$id_prm."'");
        $prods = $this->con->sql("SELECT id_pro, cantidad FROM promocion_productos WHERE id_prm='".$id_prm."'");
        $aux['categorias'] = $cats['resultado'];
        $aux['productos'] = $prods['resultado'];
        return $aux;
    }
    public function get_productos($id_cae){
        $productos = $this->con->sql("SELECT * FROM productos t1, cat_pros t2 WHERE t2.id_cae='".$id_cae."' AND t2.id_pro=t1.id_pro");
        return $productos['resultado'];
    }
    public function get_producto($id_pro){
        $productos = $this->con->sql("SELECT * FROM productos WHERE id_pro='".$id_pro."'");
        return $productos['resultado'][0];
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
            $info['code'] = $sql['resultado'][0]['code'];
            $info['font']['family'] = $sql['resultado'][0]['font_family'];
            $info['font']['css'] = $sql['resultado'][0]['font_css'];
            $info['logo'] = $sql['resultado'][0]['logo'];
            
        }

        return $info;
        
    }
    public function get_web_js_data($id_gir){
        
        $giros_sql = $this->con->sql("SELECT * FROM giros WHERE id_gir='".$id_gir."'");
        $code = $giros_sql['resultado'][0]['code'];
        
        $cat_sql = $this->con->sql("SELECT t3.id_cae, t3.parent_id, t3.nombre FROM giros t1, catalogo_productos t2, categorias t3 WHERE t1.id_gir='".$id_gir."' AND t1.id_gir=t2.id_gir AND t2.id_cat=t3.id_cat");
        $cats = $cat_sql['resultado'];
        
        
        for($i=0; $i<count($cats); $i++){
            
            $aux['id_cae'] = $cats[$i]['id_cae'];
            $aux['parent_id'] = $cats[$i]['parent_id'];
            $aux['nombre'] = $cats[$i]['nombre'];
            
            $prods_sql = $this->con->sql("SELECT * FROM cat_pros t1, productos t2 WHERE t1.id_cae='".$aux['id_cae']."' AND t1.id_pro=t2.id_pro");
            $prods = $prods_sql['resultado'];
            
            for($j=0; $j<count($prods); $j++){
                
                $aux_productos['id_pro'] = $prods[$j]['id_pro'];
                $aux_productos['nombre'] = $prods[$j]['nombre'];
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
            
            $aux_return['categorias'][] = $aux;
            unset($aux);
        }

        $promo_sql = $this->con->sql("SELECT t3.id_prm, t3.nombre, t3.parent_id FROM giros t1, catalogo_productos t2, promociones t3 WHERE t1.id_gir='".$id_gir."' AND t1.id_gir=t2.id_gir AND t2.id_cat=t3.id_cat");
        $promos = $promo_sql['resultado'];
        
        for($i=0; $i<count($promos); $i++){
        
            $promo_cats = $this->con->sql("SELECT * FROM promocion_categoria WHERE id_prm='".$promos[$i]['id_prm']."'");
            $promo_c = $promo_cats['resultado'];
            $promo_prods = $this->con->sql("SELECT * FROM promocion_productos WHERE id_prm='".$promos[$i]['id_prm']."'");
            $promo_p = $promo_prods['resultado'];
            
            $aux['id_prm'] = $promos[$i]['id_prm'];
            $aux['parent_id'] = $promos[$i]['parent_id'];
            $aux['nombre'] = $promos[$i]['nombre'];
            
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
            
            $aux_return['promociones'][] = $aux;
            unset($aux);
            
        }
        
        $preguntas_sql = $this->con->sql("SELECT t3.id_pre, t3.mostrar FROM giros t1, catalogo_productos t2, preguntas t3 WHERE t1.id_gir='".$id_gir."' AND t1.id_gir=t2.id_gir AND t2.id_cat=t3.id_cat");
        $preguntas = $preguntas_sql['resultado'];
        
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
        
        
        file_put_contents("/var/www/html/restaurants/js/data/".$code.".js", "var data=".json_encode($aux_return));
        
        
    }
    
    
    public function get_arbol_productos($id_cat, $that){
        $cats = $this->con->sql("SELECT t1.id_cae, t1.nombre as cat_nombre, t1.parent_id, t2.id_pro, t3.nombre as prod_nombre FROM categorias t1 LEFT JOIN cat_pros t2 ON t1.id_cae=t2.id_cae LEFT JOIN productos t3 ON t2.id_pro=t3.id_pro WHERE t1.id_cat='".$id_cat."' AND t1.eliminado='0'");
        return $this->process_arbol_draw($cats['resultado'], 0, $that);
    }
    public function list_arbol_cats_prods($id_cat){
        $cats = $this->con->sql("SELECT t1.id_cae, t1.nombre as cat_nombre, t1.parent_id, t2.id_pro, t3.nombre as prod_nombre FROM categorias t1 LEFT JOIN cat_pros t2 ON t1.id_cae=t2.id_cae LEFT JOIN productos t3 ON t2.id_pro=t3.id_pro WHERE t1.id_cat='".$id_cat."' AND t1.eliminado='0'");
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