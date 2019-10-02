<?php
session_start();

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."restaurants/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."restaurants/");
}

class Core{
    
    public $con = null;
    public $id_user = null;
    public $admin = null;
    public $re_venta = null;
    public $id_aux_user = null;
    public $id_gir = null;
    public $id_cat = null;
    public $eliminado = 0;

    public function __construct(){

        global $db_host;
        global $db_user;
        global $db_password;
        global $db_database;

        $this->con = new mysqli($db_host[0], $db_user[0], $db_password[0], $db_database[0]);
        $this->id_user = (isset($_SESSION['user']['info']['id_user'])) ? $_SESSION['user']['info']['id_user'] : 0 ;
        $this->admin = (isset($_SESSION['user']['info']['admin'])) ? $_SESSION['user']['info']['admin'] : 0 ;
        $this->re_venta = (isset($_SESSION['user']['info']['re_venta'])) ? $_SESSION['user']['info']['re_venta'] : 0 ;
        $this->id_aux_user = (isset($_SESSION['user']['info']['id_aux_user'])) ? $_SESSION['user']['info']['id_aux_user'] : 0 ;
        $this->id_gir = (isset($_SESSION['user']['id_gir'])) ? $_SESSION['user']['id_gir'] : 0 ;
        $this->id_cat = (isset($_SESSION['user']['id_cat'])) ? $_SESSION['user']['id_cat'] : 0 ;
        
    }
    public function verificar(){

        $host = $_POST["host"];
        $code = substr($_POST["code"], 0, 40);
        $ip = $_SERVER['REMOTE_ADDR'];
        $port = $_SERVER['SERVER_PORT'];

        if($sqlgir = $this->con->prepare("SELECT t2.ip, t2.code FROM giros t1, server t2 WHERE t1.dominio=? AND t1.id_ser=t2.id_ser AND t1.eliminado=?")){
            if($sqlgir->bind_param("si", $host, $this->eliminado)){
                if($sqlgir->execute()){
                    $res = $sqlgir->get_result();
                    if($res->{'num_rows'} == 1){
                        $result = $res->fetch_all(MYSQLI_ASSOC)[0];
                        if($ip == $result["ip"] && $port == "443" && $code == $result["code"]){
                            $sqlgir->free_result();
                            $sqlgir->close();
                            return true;
                        }
                    }
                }
            }
        }        

        $sqlgir->free_result();
        $sqlgir->close();
        return false;

    }
    public function is_giro(){

        
        if(isset($_GET["id_gir"]) && $_GET["id_gir"] > 0 && $this->id_gir != $_GET["id_gir"]){

            $id_gir = $_GET["id_gir"];
            if($this->admin == 0){
                $sql = $this->con->prepare("SELECT * FROM fw_usuarios_giros WHERE id_gir=? AND id_user=?");
                $sql->bind_param("ii", $id_gir, $this->id_user);
                $sql->execute();
                $res = $sql->get_result();
                if($res->{"num_rows"} == 1){
                    $this->id_gir = $id_gir;
                    $_SESSION['user']['id_gir'] = $id_gir;
                }else{
                    die("ERROR: #A101");
                }
                $sql->free_result();
                $sql->close();
            }
            if($this->admin == 1 && $this->id_user > 1){
                $sql = $this->con->prepare("SELECT * FROM fw_usuarios_giros_clientes WHERE id_gir=? AND id_user=?");
                $sql->bind_param("ii", $id_gir, $id_user);
                $sql->execute();
                $res = $sql->get_result();
                if($res->{"num_rows"} == 1){
                    $this->id_gir = $id_gir;
                    $_SESSION['user']['id_gir'] = $id_gir;
                }else{
                    die("ERROR: #A102");
                }
                $sql->free_result();
                $sql->close();
            }
            if($this->admin == 1 && $this->id_user == 1){
                $this->id_gir = $id_gir;
                $_SESSION['user']['id_gir'] = $id_gir;
            }
        }
        
    }
    public function is_catalogo(){
        
        if($_GET["id_cat"] > 0 && $this->id_gcat != $_GET["id_cat"]){

            $id_cat = $_GET["id_cat"];
            if($this->admin == 0){

                if($sql = $this->con->prepare("SELECT * FROM fw_usuarios_giros t1, catalogo_productos t2 WHERE t2.id_cat=? AND t2.id_gir=t1.id_gir AND t1.id_user=? AND t2.eliminado=?")){
                    $sql->bind_param("iii", $id_cat, $this->id_user, $this->eliminado);
                    $sql->execute();
                    $res = $sql->get_result();
                    if($res->{"num_rows"} == 1){
                        $this->id_cat = $id_cat;
                        $_SESSION['user']['id_cat'] = $id_cat;
                    }else{
                        die("ERROR: #A101");
                    }
                    $sql->free_result();
                    $sql->close();
                }else{
                    echo "ERROR: ".$this->con->error;
                }

            }
            if($this->admin == 1){

                if($sql = $this->con->prepare("SELECT * FROM fw_usuarios_giros_clientes t1, catalogo_productos t2 WHERE t2.id_cat=? AND t2.id_gir=t1.id_gir AND t1.id_user=? AND t2.eliminado=?")){
                    $sql->bind_param("iii", $id_cat, $this->id_user, $this->eliminado);
                    $sql->execute();
                    $res = $sql->get_result();
                    if($res->{"num_rows"} == 1 || $this->id_user == 1){
                        $this->id_cat = $id_cat;
                        $_SESSION['user']['id_cat'] = $id_cat;
                    }else{
                        die("ERROR: #A102");
                    }
                    $sql->free_result();
                    $sql->close();
                }else{
                    echo "ERROR: ".$this->con->error;
                }

            }
        }
        
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
    public function get_categorias(){
        $sql = $this->con->prepare("SELECT DISTINCT t1.id_cae, t1.nombre, t1.parent_id, t2.id_pro, t1.tipo FROM categorias t1 LEFT JOIN cat_pros t2 ON t1.id_cae=t2.id_cae WHERE t1.id_cat=? AND t1.eliminado=? ORDER BY t1.orders");
        $sql->bind_param("ii", $this->id_cat, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $this->process_categorias($result, 'id_cae');
    }
    public function get_locales(){

        if($sql = $this->con->prepare("SELECT id_loc, nombre, code FROM locales WHERE id_gir=? AND eliminado=?")){
            $sql->bind_param("ii", $this->id_gir, $this->eliminado);
            $sql->execute();
            $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
            $sql->free_result();
            $sql->close();
        }else{
            $error = $this->con->errno.' '.$this->con->error;
            echo $error;
        }
        return $result;

    }
    public function get_horarios($id_loc){

        $sql = $this->con->prepare("SELECT * FROM horarios WHERE id_loc=? AND id_gir=? AND eliminado=?");
        $sql->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_horario($id_loc, $id_hor){

        $sql = $this->con->prepare("SELECT * FROM horarios WHERE id_hor=? AND id_loc=? AND id_gir=? AND eliminado=?");
        $sql->bind_param("iiii", $id_hor, $id_loc, $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_correos_no_ses(){

        $sql = $this->con->prepare("SELECT id_loc, correo FROM locales WHERE correo_ses='0' AND eliminado=?");
        $sql->bind_param("i", $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_ssl_sol(){

        $sql = $this->con->prepare("SELECT id_gir, dominio FROM giros WHERE solicitar_ssl='1' AND eliminado=?");
        $sql->bind_param("i", $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_user_local($id_user, $id_loc){

        $sql = $this->con->prepare("SELECT id_user, tipo, save_web, web_min, save_pos, pos_min FROM fw_usuarios WHERE id_user=? AND id_loc=? AND id_gir=? AND eliminado=?");
        $sql->bind_param("iiii", $id_user, $id_loc, $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_local($id_loc){

        $sql = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND id_gir=? AND eliminado=?");
        $sql->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_giros_user(){
        
        if($this->admin == 1 && $this->id_user > 1){

            $sql = $this->con->prepare("SELECT t2.id_gir, t2.nombre, t2.dominio FROM fw_usuarios_giros_clientes t1, giros t2 WHERE t1.id_user=? AND t1.id_gir=t2.id_gir AND t2.eliminado=? ORDER BY dns_letra");
            $sql->bind_param("ii", $this->id_user, $this->eliminado);
            $sql->execute();
            $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
            $sql->free_result();
            $sql->close();

        }
        if($this->admin == 1 && $this->id_user == 1){

            $sql = $this->con->prepare("SELECT id_gir, nombre, dominio, dns_letra FROM giros WHERE eliminado=? ORDER BY dns_letra");
            $sql->bind_param("i", $this->eliminado);
            $sql->execute();
            $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
            $sql->free_result();
            $sql->close();
        
        }
        return $result;
        
    }
    public function get_giro(){

        $sql = $this->con->prepare("SELECT * FROM giros WHERE id_gir=? AND eliminado=?");
        $sql->bind_param("ii", $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_giro_id($id_gir){

        if($this->admin == 1){
            $sql = $this->con->prepare("SELECT * FROM giros WHERE id_gir=? AND eliminado=?");
            $sql->bind_param("ii", $id_gir, $this->eliminado);
            $sql->execute();
            $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            $sql->free_result();
            $sql->close();
            return $result;
        }

    }
    public function set_giro_dns(){
        
        $sql = $this->con->prepare("UPDATE giros SET dns='1' WHERE id_gir=? AND eliminado=?");
        $sql->bind_param("ii", $this->id_gir, $this->eliminado);
        $sql->execute();
        $sql->close();
    
    }
    public function get_catalogos(){

        $sql = $this->con->prepare("SELECT id_cat, nombre FROM catalogo_productos WHERE id_gir=? AND eliminado=?");
        $sql->bind_param("ii", $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_categoria($id_cae){

        $sql = $this->con->prepare("SELECT id_cae, nombre, descripcion, descripcion_sub, precio, tipo, ocultar, mostrar_prods, detalle_prods, degradado FROM categorias WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?");
        $sql->bind_param("iiii", $id_cae, $this->id_cat, $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_producto($id_pro){

        $sql = $this->con->prepare("SELECT * FROM productos t1, productos_precio t2 WHERE t1.id_pro=? AND t1.id_pro=t2.id_pro AND t2.id_cat=? AND t1.eliminado=?");
        $sql->bind_param("iii", $id_pro, $this->id_cat, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_productos(){
        
        $sql = $this->con->prepare("SELECT * FROM productos WHERE id_gir=? AND eliminado=?");
        $sql->bind_param("ii", $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_preguntas(){

        $sql = $this->con->prepare("SELECT id_pre, nombre, mostrar FROM preguntas WHERE id_cat=? AND id_gir=? AND eliminado=?");
        $sql->bind_param("iii", $this->id_cat, $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_css(){

        $id_gir = 0;
        $sql = $this->con->prepare("SELECT * FROM css WHERE id_gir=? OR id_gir=?");
        $sql->bind_param("ii", $id_gir, $this->id_gir);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_alto(){
        
        $sql = $this->con->prepare("SELECT alto FROM giros WHERE id_gir=? AND eliminado=?");
        $sql->bind_param("ii", $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]["alto"];
        $sql->free_result();
        $sql->close();
        return $result;
        
    }
    public function get_usuarios_admin(){
        if($this->id_gir != 0){
            $sql = $this->con->prepare("SELECT t1.id_user, t1.nombre FROM fw_usuarios t1, fw_usuarios_giros t2 WHERE t2.id_gir=? AND t2.id_user=t1.id_user AND t1.eliminado=?");
            $sql->bind_param("ii", $this->id_gir, $this->eliminado);
            $sql->execute();
            $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
            $sql->free_result();
            $sql->close();
            return $result;
        }
    }
    public function get_pregunta($id_pre){
        $sql = $this->con->prepare("SELECT * FROM preguntas WHERE id_pre=? AND id_cat=? AND eliminado=?");
        $sql->bind_param("iii", $id_pre, $this->id_cat, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $sql->free_result();
        $sql->close();
        return $result;
    }
    public function get_pregunta_valores($id_pre){
        $sql = $this->con->prepare("SELECT * FROM preguntas_valores WHERE id_pre=?");
        $sql->bind_param("i", $id_pre);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;
    }
    public function get_preguntas_pro($id_pro){
        
        $sql = $this->con->prepare("SELECT id_pre FROM preguntas_productos t1, productos t2 WHERE t2.id_pro=? AND t2.id_pro=t1.id_pro AND t2.id_gir=? AND t2.eliminado=?");
        $sql->bind_param("iii", $id_pro, $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_categoria_2($id_cae){

        $sql = $this->con->prepare("SELECT nombre FROM categorias WHERE id_cae=? AND eliminado=?");
        $sql->bind_param("ii", $id_cae, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_productos_categoria($id_cae){

        $sql = $this->con->prepare("SELECT * FROM productos t1, cat_pros t2 WHERE t2.id_cae=? AND t2.id_pro=t1.id_pro AND t1.eliminado=? ORDER BY t2.orders");
        $sql->bind_param("ii", $id_cae, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_paginas(){

        $sql = $this->con->prepare("SELECT * FROM paginas WHERE id_gir=? AND eliminado=?");
        $sql->bind_param("ii", $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_repartidores_giro(){

        $sql = $this->con->prepare("SELECT id_mot, nombre FROM motos WHERE id_gir=? AND eliminado=?");
        $sql->bind_param("ii", $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_repartidores($id_loc){

        $sql = $this->con->prepare("SELECT t1.id_mot, t1.nombre FROM motos t1, motos_locales t2, locales t3 WHERE t3.id_gir=? AND t3.id_loc=? AND t3.id_loc=t2.id_loc AND t2.id_mot=t1.id_mot AND t1.eliminado=?");
        $sql->bind_param("iii", $this->id_gir, $id_loc, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_promocion($id_cae){

        $sqlpre = $this->con->prepare("SELECT precio FROM categorias WHERE id_cae=? AND eliminado=?");
        $sqlpre->bind_param("ii", $id_cae, $this->eliminado);
        $sqlpre->execute();
        $aux['precio'] = $sqlpre->get_result()->fetch_all(MYSQLI_ASSOC)[0]["precio"];
        $sqlpre->free_result();
        $sqlpre->close();

        $sqlcat = $this->con->prepare("SELECT id_cae2 as id_cae, cantidad FROM promocion_categoria WHERE id_cae1=?");
        $sqlcat->bind_param("i", $id_cae);
        $sqlcat->execute();
        $aux['categorias'] = $sqlcat->get_result()->fetch_all(MYSQLI_ASSOC);
        $sqlcat->free_result();
        $sqlcat->close();

        $sqlpro = $this->con->prepare("SELECT id_pro, cantidad FROM promocion_productos WHERE id_cae=?");
        $sqlpro->bind_param("i", $id_cae);
        $sqlpro->execute();
        $aux['productos'] = $sqlpro->get_result()->fetch_all(MYSQLI_ASSOC);
        $sqlpro->free_result();
        $sqlpro->close();

        return $aux;

    }
    public function get_arbol_productos($that){
        
        $sql = $this->con->prepare("SELECT t1.id_cae, t1.nombre as cat_nombre, t1.parent_id, t2.id_pro, t3.nombre as prod_nombre FROM categorias t1 LEFT JOIN cat_pros t2 ON t1.id_cae=t2.id_cae LEFT JOIN productos t3 ON t2.id_pro=t3.id_pro WHERE t1.id_cat=? AND t1.eliminado=? AND tipo='0'");
        $sql->bind_param("ii", $this->id_cat, $this->eliminado);
        $sql->execute();
        $aux = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();

        return $this->process_arbol_draw($aux, 0, $that);

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
    public function get_select($id, $cantidad, $selected){
        
        $select = "<select id='".$id."' class='select_arbol'>";
        for($i=0; $i<$cantidad; $i++){ if($i == $selected){ $select .="<option value='".$i."' selected>".$i."</option>"; }else{ $select .="<option value='".$i."'>".$i."</option>"; } }
        $select .="</select>";
        return $select;
        
    }
    public function get_pagina($id_pag){

        $sql = $this->con->prepare("SELECT * FROM paginas WHERE id_pag=? AND id_gir=? AND eliminado=?");
        $sql->bind_param("iii", $id_pag, $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_pag_inicio(){

        $sql = $this->con->prepare("SELECT inicio_html FROM giros WHERE id_gir=? AND eliminado=?");
        $sql->bind_param("ii", $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]["inicio_html"];
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_footer(){

        $sql = $this->con->prepare("SELECT footer_html FROM giros WHERE id_gir=? AND eliminado=?");
        $sql->bind_param("ii", $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]["footer_html"];
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_repartidor($id_mot){

        $sql = $this->con->prepare("SELECT t1.id_mot, t1.nombre, t1.correo, t1.uid FROM motos t1, motos_locales t2, locales t3 WHERE t1.id_mot=? AND t3.id_gir=? AND t3.id_loc=t2.id_loc AND t2.id_mot=t1.id_mot");
        $sql->bind_param("ii", $id_mot, $this->id_gir);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function inicio(){

        if($sql = $this->con->prepare("SELECT id_user, nombre, correo, re_venta, admin, id_aux_user FROM fw_usuarios WHERE id_user=? AND eliminado=?")){
            if($sql->bind_param("ii", $this->id_user, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{
                    die('execute() failed: ' . htmlspecialchars($sql->error));
                }
            }else{
                die('bind_param() failed: ' . htmlspecialchars($sql->error));
            }
        }else{
            die('prepare() failed: ' . htmlspecialchars($this->con->error));
        }

    }
    public function get_cocina($id_ped){

        $sql = $this->con->prepare("SELECT id_ped, num_ped, carro, promos FROM pedidos_aux WHERE id_ped=? AND eliminado=?");
        $sql->bind_param("ii", $id_ped, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_usuarios(){

        if($this->id_user == 1){

            $sql = $this->con->prepare("SELECT id_user, nombre FROM fw_usuarios WHERE id_user<>? AND admin='1' AND eliminado=?");
            $sql->bind_param("ii", $this->id_user, $this->eliminado);
            $sql->execute();
            $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
            $sql->free_result();
            $sql->close();
            return $result;

        }else{
            if($this->re_venta == 1){

                $sql = $this->con->prepare("SELECT id_user, nombre FROM fw_usuarios WHERE id_aux_user=? AND re_venta='0' AND eliminado=?");
                $sql->bind_param("ii", $this->id_user, $this->eliminado);
                $sql->execute();
                $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                $sql->free_result();
                $sql->close();
                return $result;

            }
        }

    }
    public function get_usuarios_local($id_loc){

        $sql = $this->con->prepare("SELECT id_user, nombre, tipo FROM fw_usuarios WHERE id_loc=? AND id_gir=? AND admin='0' AND eliminado=?");
        $sql->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_usuario($id_user){

        $sql = $this->con->prepare("SELECT id_user, nombre, correo, id_aux_user FROM fw_usuarios WHERE id_user=? AND eliminado=?");
        $sql->bind_param("ii", $id_user, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_local_tramos($id_loc){

        $sql = $this->con->prepare("SELECT * FROM locales_tramos WHERE id_loc=? AND eliminado=?");
        $sql->bind_param("ii", $id_loc, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_local_tramo($id_lot, $id_loc){

        $sql = $this->con->prepare("SELECT * FROM locales_tramos WHERE id_lot=? AND id_loc=? AND eliminado=?");
        $sql->bind_param("iii", $id_lot, $id_loc, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_data($id_gir){
        
        $sql = $this->con->prepare("SELECT * FROM giros WHERE id_gir=?");
        $sql->bind_param("i", $id_gir);
        $sql->execute();
        $res = $sql->get_result();

        //$info['favicon'] = "misitiodelivery.ico";
        if($res->{"num_rows"} == 1){
            
            $result = $res->fetch_all(MYSQLI_ASSOC)[0];

            $info['ssl'] = $result['ssl'];
            $info['dns'] = $result['dns'];
            $info['id_gir'] = $result['id_gir'];            
            $info['titulo'] = $result['titulo'];
            $info['logo'] = $result['logo'];
            $info['estados'] = explode(",",$result['estados']);
            $info['mapcode'] = $result['mapcode'];
            $info['dominio'] = "";
            $info['url'] = $dominio;

            if($result['favicon'] != ""){ $info['favicon'] = $result['favicon']; }
            
            $info['font']['family'] = $result['font_family'];
            $info['font']['css'] = $result['font_css'];
            $info['code'] = $result['code'];
            $info['footer_html'] = $result['footer_html'];
            $info['inicio_html'] = $result['inicio_html'];
            $info['retiro_local'] = $result['retiro_local'];
            $info['despacho_domicilio'] = $result['despacho_domicilio'];
            $info['lista_locales'] = $result['lista_locales'];
            $info['con_cambios'] = $result['con_cambios'];
            $info['desde'] = $result['desde'];
            
            $info['path'] = ($info['ssl'] == 1 || $_SERVER["HTTP_HOST"] == "misitiodelivery.cl") ? "https://".$_SERVER["HTTP_HOST"] : "http://".$_SERVER["HTTP_HOST"] ;
            
            $info['pedido_wasabi'] = $result['pedido_wasabi'];
            $info['pedido_gengibre'] = $result['pedido_gengibre'];
            $info['pedido_embarazadas'] = $result['pedido_embarazadas'];
            $info['pedido_palitos'] = $result['pedido_palitos'];
            $info['pedido_comentarios'] = $result['pedido_comentarios'];
            $info['pedido_soya'] = $result['pedido_soya'];
            $info['pedido_teriyaki'] = $result['pedido_teriyaki'];
            
            $info['css_tipo'] = $result['style_page'];
            $info['css_color'] = $result['style_color'];
            $info['css_font_size'] = $result['style_modal'];
            $info['js_data'] = $info["code"].".js";
            $info['header_fixed'] = 1;
            $info['footer_fixed'] = 0;
            
            $info['pedido_01_titulo'] = $result['pedido_01_titulo'];
            $info['pedido_01_subtitulo'] = $result['pedido_01_subtitulo'];
            $info['pedido_02_titulo'] = $result['pedido_02_titulo'];
            $info['pedido_02_subtitulo'] = $result['pedido_02_subtitulo'];
            $info['pedido_03_titulo'] = $result['pedido_03_titulo'];
            $info['pedido_03_subtitulo'] = $result['pedido_03_subtitulo'];
            $info['pedido_04_titulo'] = $result['pedido_04_titulo'];
            $info['pedido_04_subtitulo'] = $result['pedido_04_subtitulo'];
            $info['item_pagina'] = $result['item_pagina'];
            
            $info['ultima_actualizacion'] = $result['ultima_actualizacion'];

        }else{

            if($dominio == "misitiodelivery.cl" || $dominio == "www.misitiodelivery.cl"){
                $info['path'] = "https://misitiodelivery.cl";
            }

        }
        $sql->free_result();
        $sql->close();
        return $info;
        
    }
    public function is_pass($id_user, $code){

        $sql = $this->con->prepare("SELECT correo FROM fw_usuarios WHERE id_user=? AND mailcode=?");
        $sql->bind_param("ii", $id_user, $code);
        $sql->execute();
        $sql->store_result();
        if($sql->{"num_rows"} == 0){
            $sql->free_result();
            $sql->close();
            header("Location: https://misitiodelivery.cl/admin/?paso=recuperar"); 
        }
        if($sql->{"num_rows"} == 1){ 
            $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            $sql->free_result();
            $sql->close();
            return $result['correo']; 
        }

    }
    public function get_paginas_web($id_gir){

        $sqlpag = $this->con->prepare("SELECT id_pag, nombre, imagen, html FROM paginas WHERE id_gir=? AND eliminado=?");
        $sqlpag->bind_param("ii", $id_gir, $this->eliminado);
        $sqlpag->execute();
        $resultpag = $sqlpag->get_result()->fetch_all(MYSQLI_ASSOC);
        $sqlpag->free_result();
        $sqlpag->close();
        return $resultpag;

    }
    public function get_locales_js($id_gir){

        $eliminado = 0;
        if($sql = $this->con->prepare("SELECT id_loc, nombre, direccion, lat, lng, telefono FROM locales WHERE id_gir=? AND eliminado=?")){
            
            $sql->bind_param("ii", $id_gir, $eliminado);
            $sql->execute();
            $result = $sql->get_result();

            while($row = $result->fetch_assoc()){

                $locales['id_loc'] = $row['id_loc'];
                $locales['nombre'] = $row['nombre'];
                $locales['direccion'] = $row['direccion'];
                $locales['lat'] = $row['lat'];
                $locales['lng'] = $row['lng'];
                $locales['telefono'] = $row['telefono'];
                $locales['whatsapp'] = $row['whatsapp'];

                $sqlloc = $this->con->prepare("SELECT dia_ini, dia_fin, hora_ini, hora_fin, min_ini, min_fin, tipo FROM horarios WHERE id_loc=? AND id_gir=? AND eliminado=?");
                $sqlloc->bind_param("iii", $row["id_loc"], $id_gir, $eliminado);
                $sqlloc->execute();
                $locales['horarios'] = $sqlloc->get_result()->fetch_all(MYSQLI_ASSOC);

                $loc[] = $locales;
                unset($locales);

            }

        }else{
            return $this->con->error;
        }
        return $loc;
        
    }
    public function get_web_js_data_remote(){

        $host = $_POST["host"];

        if($this->verificar()){

            if($sqlgiro = $this->con->prepare("SELECT id_gir FROM giros WHERE dominio=? AND eliminado=?")){
                
                $sqlgiro->bind_param("si", $host, $this->eliminado);
                $sqlgiro->execute();		
                $id_gir = $sqlgiro->get_result()->fetch_all(MYSQLI_ASSOC)[0]["id_gir"];
                $sqlgiro->free_result();
                $sqlgiro->close();

                if($sql = $this->con->prepare("SELECT * FROM catalogo_productos WHERE id_gir=? AND eliminado=?")){

                    if($sql->bind_param("ii", $id_gir, $this->eliminado)){

                        if($sql->execute()){

                            $result = $sql->get_result();
                            while($row = $result->fetch_assoc()){
                                $info['data']['catalogos'][] = $this->get_info_catalogo($row['id_cat']);
                            }
                            $info['data']['paginas'] = $this->get_paginas_web($id_gir);
                            $info['data']['config'] = $this->get_config($id_gir);
                            $info['data']['locales'] = $this->get_locales_js($id_gir);
                            $info['info'] = $this->get_data($id_gir);
                            $info['polygons'] = $this->get_polygons($id_gir);
                            $info['op'] = 1;
                            $ruta_file = "/var/www/html/restaurants/data/".$info['info']['code'].".js";
                            
                            if($info['info']['dns'] == 0){
                                file_put_contents($ruta_file, "var data=".json_encode($info['data']));
                            }
                            if($info['info']['dns'] == 1 && file_exists($ruta_file)){
                                unlink($ruta_file);
                            }
                            
                        }else{
                            $info['op'] = 2;
                            $this->enviar_error_int($sql->error, '#Y05', 0, 0, $id_gir);
                        }

                    }else{
                        $info['op'] = 2;
                        $this->enviar_error_int($sql->error, '#Y04', 0, 0, $id_gir);
                    }

                }else{
                    $info['op'] = 2;
                    $this->enviar_error_int($this->con->errno.' '.$this->con->error, '#Y03', 0, 0, $id_gir);
                }
                
                $sql->free_result();
                $sql->close();

            }else{
                $info['op'] = 2;
                $this->enviar_error_int($_SERVER["HTTP_HOST"].' -> '.$this->con->errno.' '.$this->con->error, '#Y02', 0, 0, 0);
            }
            
        }else{
            $info['op'] = 2;
            $this->enviar_error_int($_SERVER["HTTP_HOST"].' -> Enviado Pedido no verificado', '#Y01', 0, 0, 0);
        }

        return $info;

    }
    public function get_info_catalogo($id_cat){
        
        $aux_prods = [];
        $eliminado = 0;

        $sql = $this->con->prepare("SELECT * FROM categorias WHERE id_cat=? AND eliminado=? ORDER BY orders");
        $sql->bind_param("ii", $id_cat, $eliminado);
        $sql->execute();
        $result = $sql->get_result();

        while($row = $result->fetch_assoc()){

            $aux_categoria['id_cae'] = $row['id_cae'];
            $aux_categoria['parent_id'] = $row['parent_id'];
            $aux_categoria['nombre'] = $row['nombre'];
            $aux_categoria['ocultar'] = $row['ocultar'];
            $aux_categoria['image'] = $row['image'];
            $aux_categoria['mostrar_prods'] = $row['mostrar_prods'];
            $aux_categoria['detalle_prods'] = $row['detalle_prods'];
            $aux_categoria['descripcion'] = $row['descripcion'];
            $aux_categoria['descripcion_sub'] = $row['descripcion_sub'];
            $aux_categoria['precio'] = $row['precio'];
            $aux_categoria['degradado'] = $row['degradado'];
            $aux_categoria['tipo'] = $row['tipo'];

            if($aux_categoria['tipo'] == 0){

                $aux_categoria['tipo'] = 0;

                $sqlpro = $this->con->prepare("SELECT * FROM cat_pros t1, productos t2, productos_precio t3 WHERE t1.id_cae=? AND t1.id_pro=t2.id_pro AND t1.id_pro=t3.id_pro AND t3.id_cat=? ORDER BY t1.orders");
                $sqlpro->bind_param("ii", $row['id_cae'], $id_cat);
                $sqlpro->execute();
                $resultpro = $sqlpro->get_result();
                while($rowp = $resultpro->fetch_assoc()){

                    $aux_categoria['productos'][] = $rowp['id_pro'];
                    if(!in_array($rowp['id_pro'], $aux_prods)){

                        $aux_productos['id_pro'] = $rowp['id_pro'];
                        $aux_productos['nombre'] = $rowp['nombre'];
                        $aux_productos['nombre_carro'] = $rowp['nombre_carro'];
                        $aux_productos['numero'] = $rowp['numero'];
                        $aux_productos['descripcion'] = $rowp['descripcion'];
                        $aux_productos['precio'] = $rowp['precio'];

                        $aux_prods[] = $rowp['id_pro'];

                        $sqlppr = $this->con->prepare("SELECT * FROM preguntas_productos WHERE id_pro=?");
                        $sqlppr->bind_param("i", $rowp['id_pro']);
                        $sqlppr->execute();
                        $resultppr = $sqlppr->get_result();

                        while($rowpr = $resultppr->fetch_assoc()){
                            $aux_productos['preguntas'][] = $rowpr['id_pre'];
                        }

                        $info['productos'][] = $aux_productos;
                        unset($aux_productos);  

                    }

                }
            }

            if($aux_categoria['tipo'] == 1){

                $aux_categoria['tipo'] = 1;
                
                $sqlpc = $this->con->prepare("SELECT id_cae2 as id_cae, cantidad FROM promocion_categoria WHERE id_cae1=?");
                $sqlpc->bind_param("i", $row['id_cae']);
                $sqlpc->execute();
                $resultpc = $sqlpc->get_result();

                while($rowpc = $resultpc->fetch_assoc()){
                    $aux_prm_cat['id_cae'] = $rowpc['id_cae'];
                    $aux_prm_cat['cantidad'] = $rowpc['cantidad'];
                    $aux_categoria['categorias'][] = $aux_prm_cat;
                    unset($aux_prm_cat);
                }

                $sqlpp = $this->con->prepare("SELECT id_pro, cantidad FROM promocion_productos WHERE id_cae=?");
                $sqlpp->bind_param("i", $row['id_cae']);
                $sqlpp->execute();
                $resultpp = $sqlpp->get_result();

                while($rowpp = $resultpp->fetch_assoc()){
                    $aux_prm_pro['id_pro'] = $rowpp['id_pro'];
                    $aux_prm_pro['cantidad'] = $rowpp['cantidad'];
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
    public function get_info_preguntas($id_cat){
        
        $eliminado = 0;
        $sql = $this->con->prepare("SELECT * FROM preguntas WHERE id_cat=? AND eliminado=?");
        $sql->bind_param("ii", $id_cat, $eliminado);
        $sql->execute();
        $result = $sql->get_result();

        while($row = $result->fetch_assoc()){
            
            $aux_pre['id_pre'] = $row['id_pre'];
            $aux_pre['nombre'] = $row['mostrar'];
            
            $sqlpre = $this->con->prepare("SELECT * FROM preguntas_valores WHERE id_pre=?");
            $sqlpre->bind_param("i", $row['id_pre']);
            $sqlpre->execute();
            $resultpre = $sqlpre->get_result();

            while($rowpre = $resultpre->fetch_assoc()){

                $aux_pre_val['cantidad'] = $rowpre['cantidad'];
                $aux_pre_val['nombre'] = $rowpre['nombre'];
                $aux_pre_val['valores'] = json_decode($rowpre['valores']);
                $aux_pre['valores'][] = $aux_pre_val;

            }
            
            $preguntas[] = $aux_pre;
            unset($aux_pre);

        }

        return $preguntas;
        
    }
    public function get_config($id_gir){
        
        $eliminado = 0;
        $sqlgiro = $this->con->prepare("SELECT retiro_local, despacho_domicilio, desde, pedido_minimo, alto FROM giros WHERE id_gir=? AND eliminado=?");
        $sqlgiro->bind_param("si", $id_gir, $eliminado);
        $sqlgiro->execute();
        return $sqlgiro->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        
    }
    public function get_web_js_data2($id_gir){
        /*
        $sql = $this->con->prepare("SELECT t2.id_cat, t1.code FROM giros t1, catalogo_productos t2 WHERE t1.id_gir=? AND t1.id_gir=t2.id_gir AND t1.eliminado=?");
        $sql->bind_param("ii", $id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result();

        while($row = $result->fetch_assoc()){
            $info['catalogos'][] = $this->get_info_catalogo($row['id_cat']);
            $code = $row['code'];
        }

        $sqlpag = $this->con->prepare("SELECT id_pag, nombre, imagen, html FROM paginas WHERE id_gir=? AND eliminado=?");
        $sqlpag->bind_param("ii", $id_gir, $this->eliminado);
        $sqlpag->execute();
        $resultpag = $sqlpag->get_result()->fetch_all(MYSQLI_ASSOC);
        $sqlpag->free_result();
        $sqlpag->close();

        $info['paginas'] = $resultpag;
        $info['config'] = $this->get_config($id_gir);
        $info['locales'] = $this->get_locales_js($id_gir);

        $ruta_data = "/var/www/html/restaurants/js/data/".$code.".js";
        file_put_contents($ruta_data, "var data=".json_encode($info));
        
        $sqlmod = $this->con->prepare("UPDATE giros SET ultima_actualizacion=now(), con_cambios='0' WHERE id_gir=? AND eliminado=?");
        $sqlmod->bind_param("ii", $id_gir, $this->eliminado);
        $sqlmod->execute();
        $sqlmod->close();
        */
    }
    public function ver_detalle(){
        
        if($this->verificar()){

            $pedido_code = $_POST["pedido_code"];
            $host = $_POST["host"];

            $sql = $this->con->prepare("SELECT t1.id_ped, t1.num_ped, t1.id_loc, t3.ssl, t3.code, t1.id_ped, t1.id_puser, t1.id_pdir, t1.despacho, t1.carro, t1.promos, t1.pre_wasabi, t1.pre_gengibre, t1.pre_embarazadas, t1.pre_soya, t1.pre_teriyaki, t1.pre_palitos, t1.comentarios, t1.costo, t1.total, t1.verify_despacho, t1.fecha FROM pedidos_aux t1, locales t2, giros t3 WHERE t1.code=? AND t1.id_loc=t2.id_loc AND t2.id_gir=t3.id_gir AND t3.dominio=? AND t1.eliminado=? AND t1.fecha > DATE_ADD(NOW(), INTERVAL -2 DAY)");
            $sql->bind_param("ssi", $pedido_code, $host, $this->eliminado);
            $sql->execute();
            $res = $sql->get_result();

            if($res->{"num_rows"} == 1){

                $result = $res->fetch_all(MYSQLI_ASSOC)[0];
                $sql->free_result();
                $sql->close();

                $sqlpus = $this->con->prepare("SELECT nombre, telefono FROM pedidos_usuarios WHERE id_puser=?");
                $sqlpus->bind_param("i", $result["id_puser"]);
                $sqlpus->execute();
                $resulpus = $sqlpus->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                $sqlpus->free_result();
                $sqlpus->close();

                $info['puser'] = $resulpus;
                $info['nombre'] = $resulpus['nombre'];
                $info['telefono'] = $resulpus['telefono'];

                if($result["despacho"] == 1 && $result["id_pdir"] != 0){

                    $sqlpdi = $this->con->prepare("SELECT * FROM pedidos_direccion WHERE id_pdir=?");
                    $sqlpdi->bind_param("i", $result["id_pdir"]);
                    $sqlpdi->execute();
                    $resulpdi = $sqlpdi->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    $sqlpdi->free_result();
                    $sqlpdi->close();

                    $info['calle'] = $resulpdi['calle'];
                    $info['num'] = $resulpdi['num'];
                    $info['depto'] = $resulpdi['depto'];
                    $info['direccion'] = $resulpdi['direccion'];
                    $info['comuna'] = $resulpdi['comuna'];
                    $info['lat'] = $resulpdi['lat'];
                    $info['lng'] = $resulpdi['lng'];

                }

                $info['id_ped'] = $result['id_ped'];
                $info['num_ped'] = $result['num_ped'];
                $info['fecha'] = $result['fecha'];
                $info['carro'] = json_decode($result['carro']);
                $info['promos'] = json_decode($result['promos']);
                
                $info['pre_wasabi'] = $result['pre_wasabi'];
                $info['pre_gengibre'] = $result['pre_gengibre'];
                $info['pre_embarazadas'] = $result['pre_embarazadas'];
                $info['pre_palitos'] = $result['pre_palitos'];
                $info['pre_teriyaki'] = $result['pre_teriyaki'];
                $info['pre_soya'] = $result['pre_soya'];
                $info['despacho'] = $result['despacho'];
                $info['comentarios'] = $result['comentarios'];
                
                $info['costo'] = $result['costo'];
                $info['total'] = $result['total'];
                
                $info['verify_despacho'] = $result['verify_despacho'];
                $info['verify_direccion'] = 0;
                
                $info['op'] = 1;

            }

        }else{
            $info['op'] = 2;
            $this->enviar_error_int('Ver Detalle no verificado', '#Z02', 0, 0, 0);
        }
        
        return $info;
        
    }
    public function cocina($ccn){

        if(!isset($ccn)){

            $http = "http://";
            if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on"){
                $http = "https://";
            }
            die("<meta http-equiv='refresh' content='0; url=".$http.$_SERVER["HTTP_HOST"]."/admin'>"); 
        
        }else{

            $sql = $this->con->prepare("SELECT code FROM locales WHERE code=? AND eliminado=?");
            $sql->bind_param("ii", $ccn, $this->eliminado);
            $sql->execute();
            $sql->store_result();
            if($sql->{"num_rows"} == 0){
                die("<meta http-equiv='refresh' content='0; url=".$http.$_SERVER["HTTP_HOST"]."/admin'>");
            }
            if($sql->{"num_rows"} == 1){
                return $ccn;
            }

        }

    }
    public function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    public function get_data_pos(){

        $ip = $this->getUserIpAddr();
        $id = $_COOKIE["id"];
        $user_code = $_COOKIE["user_code"];
        $local_code = $_COOKIE["local_code"];

        $sql = $this->con->prepare("SELECT t2.id_loc, t2.lat, t2.lng, t2.sonido, t3.estados, t2.t_retiro, t2.t_despacho, t3.dominio, t3.ssl, t3.dns, t3.id_gir FROM fw_usuarios t1, locales t2, giros t3 WHERE t1.id_user=? AND t1.cookie_code=? AND t1.id_loc=t2.id_loc AND t2.cookie_code=? AND t2.cookie_ip=? AND t2.id_gir=t3.id_gir AND t1.eliminado=? AND t2.eliminado=? AND t3.eliminado=?");
        $sql->bind_param("isssiii", $id, $user_code, $local_code, $ip, $this->eliminado, $this->eliminado, $this->eliminado);
        $sql->execute();
        $res = $sql->get_result();
        
        if($res->{"num_rows"} == 0){
            die("<meta http-equiv='refresh' content='0; url=https://misitiodelivery.cl/admin'>");
        }

        if($res->{"num_rows"} == 1){

            $result = $res->fetch_all(MYSQLI_ASSOC)[0];
            $info['pedidos'] = $this->get_ultimos_pedidos_pos($result['id_loc']);
            $info['motos'] = $this->get_repartidores_local($result['id_loc']);

            $info['lat'] = $result['lat'];
            $info['lng'] = $result['lng'];
            $info['sonido'] = $result['sonido'];
            $info['estados'] = explode(",", $result['estados']);
            $info['t_retiro'] = $result['t_retiro'];
            $info['t_despacho'] = $result['t_despacho'];
            $info['dominio'] = $result['dominio'];
            $info['ssl'] = $result['ssl'];
            $info['dns'] = $result['dns'];
            $info['id'] = $result['id_gir'];

            return $info;

        }

    }
    public function get_repartidores_local($id_loc){

        $sql = $this->con->prepare("SELECT t1.id_mot, t1.nombre FROM motos t1, motos_locales t2 WHERE t2.id_loc=? AND t2.id_mot=t1.id_mot AND t1.eliminado=?");
        $sql->bind_param("ii", $id_loc, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    public function get_info_despacho($lat, $lng){

        $polygons = $this->get_polygons_id();
        $precio = 9999999;
        $info['op'] = 2;
        foreach($polygons as $polygon){

            $lats = [];
            $lngs = [];
            $puntos = json_decode($polygon['poligono']);
            foreach($puntos as $punto){
                $lats[] = $punto->{'lat'};
                $lngs[] = $punto->{'lng'};
            }
            $is = $this->is_in_polygon($lats, $lngs, $lat, $lng);
            if($is){
                if($precio > $polygon['precio']){
                    $info['op'] = 1;
                    $info['id_loc'] = intval($polygon['id_loc']);
                    $info['precio'] = intval($polygon['precio']);
                    $info['nombre'] = $polygon['nombre'];
                    $info['lat'] = $lat;
                    $info['lng'] = $lng;
                    $precio = $info['precio'];
                }
            }
        }
        
        return $info;
        
    }
    function is_in_polygon($vertices_x, $vertices_y, $longitude_x, $latitude_y){

        $points_polygon = count($vertices_x) - 1;
        $i = $j = $c = $point = 0;
        for($i=0, $j=$points_polygon ; $i<$points_polygon; $j=$i++) {
            $point = $i;
            if($point == $points_polygon)
                $point = 0;
            if((($vertices_y[$point] > $latitude_y != ($vertices_y[$j] > $latitude_y)) && ($longitude_x < ($vertices_x[$j] - $vertices_x[$point]) * ($latitude_y - $vertices_y[$point]) / ($vertices_y[$j] - $vertices_y[$point]) + $vertices_x[$point])))
                $c = !$c;
        }
        return $c;

    }
    public function get_polygons_id(){

        $ip = $this->getUserIpAddr();
        $id = $_COOKIE["id"];
        $user_code = $_COOKIE["user_code"];
        $local_code = $_COOKIE["local_code"];

        $sql = $this->con->prepare("SELECT t3.id_gir FROM fw_usuarios t1, locales t2, giros t3 WHERE t1.id_user=? AND t1.cookie_code=? AND t1.id_loc=t2.id_loc AND t2.cookie_code=? AND t2.cookie_ip=? AND t2.id_gir=t3.id_gir AND t1.eliminado=? AND t2.eliminado=? AND t3.eliminado=?");
        $sql->bind_param("isssiii", $id, $user_code, $local_code, $ip, $this->eliminado, $this->eliminado, $this->eliminado);
        $sql->execute();
        $res = $sql->get_result();

        if($res->{"num_rows"} == 1){

            $id_gir = $res->fetch_all(MYSQLI_ASSOC)[0]['id_gir'];
            $sqlg = $this->con->prepare("SELECT t3.nombre, t3.poligono, t3.precio, t3.id_loc FROM giros t1, locales t2, locales_tramos t3 WHERE t1.id_gir=? AND t1.id_gir=t2.id_gir AND t2.id_loc=t3.id_loc AND t2.eliminado=? AND t3.eliminado=?");
            $sqlg->bind_param("iii", $id_gir, $this->eliminado, $this->eliminado);
            $sqlg->execute();
            $result = $sqlg->get_result()->fetch_all(MYSQLI_ASSOC);
            $sqlg->free_result();
            $sqlg->close();

        }else{

            $result = [];

        }

        return $result;
        
    }
    function get_polygons($id_gir){

        $eliminado = 0;
        $sql = $this->con->prepare("SELECT t3.nombre, t3.poligono, t3.precio, t3.id_loc FROM giros t1, locales t2, locales_tramos t3 WHERE t1.id_gir=? AND t1.id_gir=t2.id_gir AND t2.id_loc=t3.id_loc AND t2.eliminado=? AND t3.eliminado=?");
        $sql->bind_param("iii", $id_gir, $eliminado, $eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;
                    
    }
    public function del_pos_direcciones($id_pdir){

        $ip = $this->getUserIpAddr();
        $id = $_COOKIE["id"];
        $user_code = $_COOKIE["user_code"];
        $local_code = $_COOKIE["local_code"];

        $sql = $this->con->prepare("SELECT t2.id_gir, t1.del_pdir FROM fw_usuarios t1, locales t2 WHERE t1.id_user=? AND t1.cookie_code=? AND t1.id_loc=t2.id_loc AND t2.cookie_code=? AND t2.cookie_ip=? AND t1.eliminado=? AND t2.eliminado=?");
        $sql->bind_param("isssii", $id, $user_code, $local_code, $ip, $this->eliminado, $this->eliminado);
        $sql->execute();
        $res = $sql->get_result();

        if($res->{'num_rows'} == 0){

            $info['op'] = 2;
            $info['mensaje'] = "Error Usuario";

        }
        if($res->{'num_rows'} == 1){
            
            $aux = $res->fetch_all(MYSQLI_ASSOC)[0];
            $id_gir = $aux['id_gir'];
            $del_pdir = $aux['del_pdir'];
            $sqldir = $this->con->prepare("SELECT * FROM pedidos_direccion WHERE id_pdir=?");
            $sqldir->bind_param("i", $id_pdir);
            $sqldir->execute();
            $resdir = $sqldir->get_result();
            $id_puser = $resdir->fetch_all(MYSQLI_ASSOC)[0]['id_puser'];
            $sqldir->free_result();
            $sqldir->close();

            $sqluser = $this->con->prepare("SELECT * FROM pedidos_usuarios WHERE id_puser=? AND id_gir=?");
            $sqluser->bind_param("ii", $id_puser, $id_gir);
            $sqluser->execute();
            $resu = $sqluser->get_result();
            $sqluser->free_result();
            $sqluser->close();

            if($resu->{'num_rows'} == 1){
                if($del_pdir == 1){
                    
                    $info['op'] = 1;
                    $sqldpr = $this->con->prepare("DELETE FROM pedidos_direccion WHERE id_pdir=?");
                    $sqldpr->bind_param("i", $id_pdir);
                    $sqldpr->execute();
                    $sqldpr->close();

                }else{
                    $info['op'] = 2;
                }
            }else{
                $info['op'] = 2;
            }

        }

        $sql->free_result();
        $sql->close();

        return $info;

    }
    public function get_pos_direcciones($telefono){

        $ip = $this->getUserIpAddr();
        $id = $_COOKIE["id"];
        $user_code = $_COOKIE["user_code"];
        $local_code = $_COOKIE["local_code"];

        $sql = $this->con->prepare("SELECT t2.id_gir FROM fw_usuarios t1, locales t2 WHERE t1.id_user=? AND t1.cookie_code=? AND t1.id_loc=t2.id_loc AND t2.cookie_code=? AND t2.cookie_ip=? AND t1.eliminado=? AND t2.eliminado=?");
        $sql->bind_param("isssii", $id, $user_code, $local_code, $ip, $this->eliminado, $this->eliminado);
        $sql->execute();
        $res = $sql->get_result();

        if($res->{'num_rows'} == 0){

            $info['op'] = 2;
            $info['mensaje'] = "Error Usuario";

        }
        if($res->{'num_rows'} == 1){
            
            $id_gir = $res->fetch_all(MYSQLI_ASSOC)[0]['id_gir'];
            $sqlu = $this->con->prepare("SELECT t1.id_puser, t1.nombre, t2.id_pdir, t2.direccion, t2.calle, t2.num, t2.depto, t2.comuna, t2.lat, t2.lng FROM pedidos_usuarios t1, pedidos_direccion t2 WHERE t1.id_gir=? AND t1.telefono=? AND t1.id_puser=t2.id_puser");
            $sqlu->bind_param("is", $id_gir, $telefono);
            $sqlu->execute();            
            $resdir = $sqlu->get_result();
            $info['cantidad'] = $resdir->{"num_rows"};

            if($resdir->{"num_rows"} > 0){
                while($row = $resdir->fetch_assoc()){

                    $info['id_puser'] = $row['id_puser'];
                    $info['nombre'] = $row['nombre'];
                    
                    $aux_dir["id_pdir"] = $row['id_pdir'];
                    $aux_dir["direccion"] = $row['direccion'];
                    $aux_dir["calle"] = $row['calle'];
                    $aux_dir["num"] = $row['num'];
                    $aux_dir["depto"] = $row['depto'];
                    $aux_dir["comuna"] = $row['comuna'];
                    $aux_dir["lat"] = $row['lat'];
                    $aux_dir["lng"] = $row['lng'];
                    $info['direcciones'][] = $aux_dir;
                    unset($aux_dir);

                }
            }

            $sqlu->free_result();
            $sqlu->close();

        }
        
        $sql->free_result();
        $sql->close();

        return $info;

    }
    public function get_ultimos_pedidos($id_ped){
        
        $ip = $this->getUserIpAddr();
        $id = $_COOKIE["id"];
        $user_code = $_COOKIE["user_code"];
        $local_code = $_COOKIE["local_code"];

        $sql = $this->con->prepare("SELECT t2.id_gir, t2.code, t2.enviar_cocina, t1.save_web, t1.web_min, t1.save_pos, t1.pos_min, t2.id_loc FROM fw_usuarios t1, locales t2 WHERE t1.id_user=? AND t1.cookie_code=? AND t1.id_loc=t2.id_loc AND t2.cookie_code=? AND t2.cookie_ip=? AND t1.eliminado=? AND t2.eliminado=?");
        $sql->bind_param("isssii", $id, $user_code, $local_code, $ip, $this->eliminado, $this->eliminado);
        $sql->execute();
        $resu = $sql->get_result();

        if($resu->{'num_rows'} == 0){

            $info['op'] = 2;

        }
        if($resu->{'num_rows'} == 1){

            $id_loc = $resu->fetch_all(MYSQLI_ASSOC)[0]["id_loc"];
            $sqlped = $this->con->prepare("SELECT * FROM pedidos_aux WHERE id_ped=? AND id_loc=? AND eliminado=? ORDER BY id_ped DESC");
            $sqlped->bind_param("iii", $id_ped, $id_loc, $this->eliminado);
            $sqlped->execute();
            $row = $sqlped->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            $sqlped->free_result();
            $sqlped->close();

            $res['id_ped'] = $row['id_ped'];
            $res['num_ped'] = $row['num_ped'];
            $res['pedido_code'] = $row['code'];
            $res['tipo'] = $row['tipo'];
            $res['estado'] = $row['estado'];
            $res['fecha'] = strtotime($row['fecha']);
            $res['despacho'] = $row['despacho'];

            $res['carro'] = ($row['carro'] != "") ? json_decode($row['carro']) : [] ;
            $res['promos'] = ($row['promos'] != "") ? json_decode($row['promos']) : [] ;

            $res['pre_wasabi'] = $row['pre_wasabi'];
            $res['pre_gengibre'] = $row['pre_gengibre'];
            $res['pre_embarazadas'] = $row['pre_embarazadas'];
            $res['pre_palitos'] = $row['pre_palitos'];
            $res['pre_soya'] = $row['pre_soya'];
            $res['pre_teriyaki'] = $row['pre_teriyaki'];
            $res['verify_despacho'] = $row['verify_despacho'];
            $res['id_mot'] = $row['id_mot'];
            $res['eliminado'] = $row['eliminado'];
            $res['ocultar'] = $row['ocultar'];
            $res['costo'] = $row['costo'];
            $res['total'] = $row['total'];
            $res['id_puser'] = $row['id_puser'];
            $res['id_pdir'] = $row['id_pdir'];

            $sqlpu = $this->con->prepare("SELECT * FROM pedidos_usuarios WHERE id_puser=?");
            $sqlpu->bind_param("i", $row['id_puser']);
            $sqlpu->execute();
            $pedido_usuarios = $sqlpu->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            $sqlpu->free_result();
            $sqlpu->close();

            $res['nombre'] = $pedido_usuarios['nombre'];
            $res['telefono'] = $pedido_usuarios['telefono'];

            if($res['despacho'] == 1){
                
                $sqlpd = $this->con->prepare("SELECT * FROM pedidos_direccion WHERE id_pdir=?");
                $sqlpd->bind_param("i", $row['id_pdir']);
                $sqlpd->execute();
                $pedido_direccion = $sqlpd->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                $sqlpd->free_result();
                $sqlpd->close();

                $res['direccion'] = $pedido_direccion['direccion'];
                $res['lat'] = $pedido_direccion['lat'];
                $res['lng'] = $pedido_direccion['lng'];
                $res['calle'] = $pedido_direccion['calle'];
                $res['num'] = $pedido_direccion['num'];
                $res['depto'] = $pedido_direccion['depto'];
                $res['comuna'] = $pedido_direccion['num'];

            }
            
        }

        $sql->free_result();
        $sql->close();
        return $res;
        
    }
    public function get_ultimos_pedidos_pos($id_loc){

        $sql = $this->con->prepare("SELECT * FROM pedidos_aux WHERE id_loc=? AND eliminado=? AND fecha > DATE_ADD(NOW(), INTERVAL -2 DAY) ORDER BY id_ped DESC");
        $sql->bind_param("ii", $id_loc, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result();

        while($row = $result->fetch_assoc()){

            $res['id_ped'] = $row['id_ped'];
            $res['num_ped'] = $row['num_ped'];
            $res['pedido_code'] = $row['code'];
            $res['tipo'] = $row['tipo'];
            $res['estado'] = $row['estado'];
            $res['fecha'] = strtotime($row['fecha']);
            $res['despacho'] = $row['despacho'];

            $res['carro'] = ($row['carro'] != "") ? json_decode($row['carro']) : [] ;
            $res['promos'] = ($row['promos'] != "") ? json_decode($row['promos']) : [] ;

            $res['pre_wasabi'] = $row['pre_wasabi'];
            $res['pre_gengibre'] = $row['pre_gengibre'];
            $res['pre_embarazadas'] = $row['pre_embarazadas'];
            $res['pre_palitos'] = $row['pre_palitos'];
            $res['pre_soya'] = $row['pre_soya'];
            $res['pre_teriyaki'] = $row['pre_teriyaki'];
            $res['verify_despacho'] = $row['verify_despacho'];
            $res['id_mot'] = $row['id_mot'];
            $res['eliminado'] = $row['eliminado'];
            $res['ocultar'] = $row['ocultar'];
            $res['costo'] = $row['costo'];
            $res['cambios'] = 0;
            $res['total'] = $row['total'];
            $res['id_puser'] = $row['id_puser'];
            $res['id_pdir'] = $row['id_pdir'];

            $sqlpu = $this->con->prepare("SELECT * FROM pedidos_usuarios WHERE id_puser=?");
            $sqlpu->bind_param("i", $row['id_puser']);
            $sqlpu->execute();
            $pedido_usuarios = $sqlpu->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            $sqlpu->free_result();
            $sqlpu->close();

            $res['nombre'] = $pedido_usuarios['nombre'];
            $res['telefono'] = $pedido_usuarios['telefono'];

            if($res['despacho'] == 1){
                
                $sqlpd = $this->con->prepare("SELECT * FROM pedidos_direccion WHERE id_pdir=?");
                $sqlpd->bind_param("i", $row['id_pdir']);
                $sqlpd->execute();
                $pedido_direccion = $sqlpd->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                $sqlpd->free_result();
                $sqlpd->close();

                $res['direccion'] = $pedido_direccion['direccion'];
                $res['lat'] = $pedido_direccion['lat'];
                $res['lng'] = $pedido_direccion['lng'];
                $res['calle'] = $pedido_direccion['calle'];
                $res['num'] = $pedido_direccion['num'];
                $res['depto'] = $pedido_direccion['depto'];
                $res['comuna'] = $pedido_direccion['num'];

            }

            $info[] = $res;
            unset($res);

        }
        $sql->free_result();
        $sql->close();
        
        return $info;
        
    }
    public function del_pedido(){

        $id_ped = $_POST['id'];
        $tipo = $_POST['tipo'];

        $id_loc = $_COOKIE['ID'];
        $cookie_code = $_COOKIE['CODE'];
        
        $info['id_ped'] = $id_ped;
        $info['tipo'] = $tipo;

        $sql = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND cookie_code=? AND eliminado=?");
        $sql->bind_param("iii", $id_loc, $cookie_code, $this->eliminado);
        $sql->execute();
        $sql->store_result();
        if($sql->{"num_rows"} == 1){
            
            $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            if($tipo == 1){
                
                $local_code = $result['code'];
                $sqlped = $this->con->prepare("UPDATE pedidos_aux SET eliminado='1' WHERE id_ped=? AND id_loc=?");
                $sqlped->bind_param("ii", $id_ped, $id_loc);

                $send['accion'] = 'borrar_cocina_local';
                $send['hash'] = 'hash';
                $send['local_code'] = $local_code;
                $send['id_ped'] = $id_ped;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/borrar_cocina');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                curl_exec($ch);
                curl_close($ch);
            }
            if($tipo == 2){
                $sqlped = $this->con->prepare("UPDATE pedidos_aux SET ocultar='1' WHERE id_ped=? AND id_loc=?");
                $sqlped->bind_param("ii", $id_ped, $id_loc);
            }

            $sqlped->execute();
            $sqlped->close();

        }

        return $info;

    }
    public function set_web_pedido(){

        $ip = $this->getUserIpAddr();
        $id = $_COOKIE["id"];
        $user_code = $_COOKIE["user_code"];
        $local_code = $_COOKIE["local_code"];

        $sql = $this->con->prepare("SELECT t2.id_gir, t2.code, t2.enviar_cocina, t1.save_web, t1.web_min, t1.save_pos, t1.pos_min, t2.id_loc FROM fw_usuarios t1, locales t2 WHERE t1.id_user=? AND t1.cookie_code=? AND t1.id_loc=t2.id_loc AND t2.cookie_code=? AND t2.cookie_ip=? AND t1.eliminado=? AND t2.eliminado=?");
        $sql->bind_param("isssii", $id, $user_code, $local_code, $ip, $this->eliminado, $this->eliminado);
        $sql->execute();
        $res = $sql->get_result();
        
        if($res->{'num_rows'} == 0){

            $info['op'] = 2;

        }
        if($res->{'num_rows'} == 1){

            $result = $res->fetch_all(MYSQLI_ASSOC)[0];
            $pedido = json_decode($_POST['pedido']);
            $carro = $pedido->{'carro'};
            $promos = $pedido->{'promos'};
            
            $id_ped = intval($pedido->{'id_ped'});
            $despacho = intval($pedido->{'despacho'});
            $estado = intval($pedido->{'estado'});
            
            $id_puser = $pedido->{'id_puser'};
            $nombre = $pedido->{'nombre'};
            $telefono = $pedido->{'telefono'};
            
            $id_pdir = $pedido->{'id_pdir'};
            $direccion = $pedido->{'direccion'};
            $calle = $pedido->{'calle'};
            $num = $pedido->{'num'};
            $depto = $pedido->{'depto'};
            $comuna = $pedido->{'comuna'};
            $lat = $pedido->{'lat'};
            $lng = $pedido->{'lng'};
            
            $pre_gengibre = intval($pedido->{'pre_gengibre'});
            $pre_wasabi = intval($pedido->{'pre_wasabi'});
            $pre_embarazadas = intval($pedido->{'pre_embarazadas'});
            $pre_palitos = intval($pedido->{'pre_palitos'});
            $pre_soya = intval($pedido->{'pre_soya'});
            $pre_teriyaki = intval($pedido->{'pre_teriyaki'});
            
            $ocultar = intval($pedido->{'ocultar'});
            $eliminado = intval($pedido->{'eliminado'});
            
            $costo = intval($pedido->{'costo'});
            $total = intval($pedido->{'total'});

            $id_gir = $result["id_gir"];
            $id_loc = $result["id_loc"];
            $local_code = $result["code"];
            $enviar_cocina = $result["enviar_cocina"];

            $save_web = $result["save_web"];
            $web_min = $result["web_min"];
            $save_pos = $result["save_pos"];
            $pos_min = $result["pos_min"];

            if($id_ped == 0){

                $sqlgir = $this->con->prepare("SELECT * FROM giros WHERE id_gir=? AND eliminado=?");
                $sqlgir->bind_param("ii", $id_gir, $this->eliminado);
                $sqlgir->execute();
                $resultgir = $sqlgir->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                $num_ped = $resultgir["num_ped"] + 1;

                $sqlped = $this->con->prepare("UPDATE giros SET num_ped=? WHERE id_gir=? AND eliminado=?");
                $sqlped->bind_param("iii", $num_ped, $id_gir, $this->eliminado);
                $sqlped->execute();
                $sqlped->close();

                $code = bin2hex(openssl_random_pseudo_bytes(10));
                if($sqlaux = $this->con->prepare("INSERT INTO pedidos_aux (num_ped, tipo, fecha, code, id_loc, id_gir) VALUES (?, '0', now(), ?, ?, ?)")){
                    if($sqlaux->bind_param("isii", $num_ped, $code, $id_loc, $id_gir)){
                        if($sqlaux->execute()){
                            $id_ped = $this->con->insert_id;
                            $sqlaux->close();
                            $info['id_ped'] = $id_ped;
                            $info['num_ped'] = $num_ped;
                            $info['pedido_code'] = $code;
                        }else{
                            $info['db_error'] = $sqlaux->error;
                        }
                    }else{
                        $info['db_error'] = $sqlaux->error;
                    }
                }else{
                    $info['db_error'] = $this->con->error;
                }

            }

            /*
            var id_mot = $('#id_mot').val();
            if(id_mot !== pedidos[seleccionado].id_mot){
                if(id_mot == 0){
                    if(pedidos[seleccionado].id_mot > 0){
                        borrar_pedido_moto(pedidos[seleccionado].id_mot, pedidos[seleccionado].pedido_code);
                    }
                }
                if(id_mot > 0){
                    if(pedidos[seleccionado].id_mot > 0){
                        borrar_pedido_moto(pedidos[seleccionado].id_mot, pedidos[seleccionado].pedido_code);
                    }
                    pedidos[seleccionado].id_mot = id_mot;
                    add_pedido_moto(id_mot, pedidos[seleccionado].pedido_code);
                }
            }
            pedidos[seleccionado].id_mot = $('#id_mot').val();
            */

            $sqlpaux = $this->con->prepare("SELECT * FROM pedidos_aux WHERE id_ped=? AND id_loc=? AND eliminado=?");
            $sqlpaux->bind_param("iii", $id_ped, $id_loc, $this->eliminado);
            $sqlpaux->execute();
            $resultpaux = $sqlpaux->get_result()->fetch_all(MYSQLI_ASSOC)[0];

            $sql_id_puser = $resultpaux['id_puser'];
            $sql_id_pdir = $resultpaux['id_pdir'];
            $sql_carro = $resultpaux['carro'];
            $sql_promos = $resultpaux['promos'];
            $num_ped = $resultpaux['num_ped'];
            $mod_despacho = $resultpaux['mod_despacho'];
            $sql_tipo = $resultpaux['tipo'];
            $sql_fecha = strtotime($resultpaux['fecha']);

            $info['carro'] = ($sql_carro != "") ? json_decode($sql_carro) : [] ;

            if($id_puser == 0 && $sql_id_puser == 0){
                if(strlen($telefono) >= 12 && strlen($telefono) <= 14){

                    $sqlipu = $this->con->prepare("INSERT INTO pedidos_usuarios (nombre, telefono, id_gir) VALUES (?, ?, ?)");
                    $sqlipu->bind_param("ssi", $nombre, $telefono, $id_gir);
                    $sqlipu->execute();
                    $id_puser = $this->con->insert_id;
                    $sqlipu->close();

                    $sqlupa = $this->con->prepare("UPDATE pedidos_aux SET id_puser=? WHERE id_ped=? AND id_loc=?");
                    $sqlupa->bind_param("iii", $id_puser, $id_ped, $id_loc);
                    $sqlupa->execute();
                    $sqlupa->close();

                }
            }

            if($id_puser > 0 && $sql_id_puser == 0){

                $sqlupa = $this->con->prepare("UPDATE pedidos_aux SET id_puser=? WHERE id_ped=? AND id_loc=?");
                $sqlupa->bind_param("iii", $id_puser, $id_ped, $id_loc);
                $sqlupa->execute();
                $sqlupa->close();

            }

            
            if($id_pdir == 0 && $sql_id_pdir == 0){
                if($direccion != ""){

                    $sqlipd = $this->con->prepare("INSERT INTO pedidos_direccion (direccion, calle, num, depto, comuna, lat, lng, id_puser) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $sqlipd->bind_param("ssissddi", $direccion, $calle, $num, $depto, $comuna, $lat, $lng, $id_puser);
                    $sqlipd->execute();
                    $id_pdir = $this->con->insert_id;
                    $sqlipd->close();

                    $sqlupd = $this->con->prepare("UPDATE pedidos_aux SET id_pdir=? WHERE id_ped=? AND id_loc=?");
                    $sqlupd->bind_param("iii", $id_pdir, $id_ped, $id_loc);
                    $sqlupd->execute();
                    $sqlupd->close();

                }
            }
            if($id_pdir > 0 && $sql_id_pdir == 0){
                
                $sqlupd = $this->con->prepare("UPDATE pedidos_aux SET id_pdir=? WHERE id_ped=? AND id_loc=?");
                $sqlupd->bind_param("iii", $id_pdir, $id_ped, $id_loc);
                $sqlupd->execute();
                $sqlupd->close();

            }

            if(count($carro) > 0){
                if($sql_carro == "" || $this->permiso_modificar($sql_tipo, $sql_fecha, $mod_despacho, $save_web, $save_pos, $web_min, $pos_min)){
                    
                    $sqlutp = $this->con->prepare("UPDATE pedidos_aux SET carro='".json_encode($carro)."', promos='".json_encode($promos)."', mod_despacho='1', total='".$total."' WHERE id_ped=? AND id_loc=?");
                    $sqlutp->bind_param("ii", $id_ped, $id_loc);
                    $sqlutp->execute();
                    $sqlutp->close();

                    $info['carro'] = $carro;

                    if($enviar_cocina == 1){
                        $send['accion'] = 'enviar_cocina_local';
                        $send['hash'] = 'hash';
                        $send['local_code'] = $local_code;
                        $send['id_ped'] = $id_ped;
                        $send['num_ped'] = $num_ped;
                        $send['carro'] = $carro;
                        $send['promos'] = $promos;
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/enviar_cocina');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                        curl_exec($ch);
                        curl_close($ch);
                    }

                }else{
                    $cant_1 = count(json_decode($sql_carro));
                    $cant_2 = count($carro);
                    if($cant_2 != $cant_1){
                        $info['alert'] = 'no se efectuaron los cambios';
                    }
                }
            }

            $sqluep = $this->con->prepare("UPDATE pedidos_aux SET despacho='".$despacho."', estado='".$estado."', pre_gengibre='".$pre_gengibre."', pre_wasabi='".$pre_wasabi."', pre_embarazadas='".$pre_embarazadas."', pre_palitos='".$pre_palitos."', pre_soya='".$pre_soya."', pre_teriyaki='".$pre_teriyaki."', costo='".$costo."' WHERE id_ped=? AND id_loc=?");
            $sqluep->bind_param("ii", $id_ped, $id_loc);
            $sqluep->execute();
            $sqluep->close();

        }

        $sql->close();
        return $info;

    }
    public function permiso_modificar($sql_tipo, $sql_fecha, $mod_despacho, $save_web, $save_pos, $web_min, $pos_min){

        if($sql_tipo == 0){
            // POS
            if($save_pos == 0){
                return false;
            }
            $tiempo = $sql_fecha + $pos_min * 60;
            if(time() < $tiempo){
                if($save_pos == 1){
                    if($mod_despacho == 0){
                        return true;
                    }
                    if($mod_despacho == 1){
                        return false;
                    }    
                }
                if($save_pos == 2){
                    return true;
                }
            }else{
                return false;
            }
        }
        if($sql_tipo == 1){
            // WEB
            if($save_web == 0){
                return false;
            }
            $tiempo = $sql_fecha + $web_min * 60;
            if(time() < $tiempo){
                if($save_web == 1){
                    if($mod_despacho == 0){
                        return true;
                    }
                    if($mod_despacho == 1){
                        return false;
                    }    
                }
                if($save_web == 2){
                    return true;
                }
            }else{
                return false;
            }
        } 

    }
    public function enviar_error_int($error, $codes, $status, $id_puser, $id_gir){

        $sql = $this->con->prepare("INSERT INTO seguimiento_web (nombre, code, stat, fecha, id_puser, id_gir) VALUES (?, ?, ?, now(), ?, ?)");
        $sql->bind_param("ssiii", $error, $codes, $status, $id_puser, $id_gir);
        if($sql->execute()){
            $sql->close();
            return true;
        }else{
            $sql->close();
            return false;
        }

    }
    public function enviar_error(){

        $error = $_POST['error'];
        $codes = $_POST['codes'];
        $status = $_POST['status'];
        $info['op'] = 2;

        if($error !== null){

            $host = $_POST['host'];
            $sqlg = $this->con->prepare("SELECT id_gir FROM giros WHERE dominio=?");
            $sqlg->bind_param("s", $host);
            if($sqlg->execute()){

                $id_gir = $sqlg->get_result()->fetch_all(MYSQLI_ASSOC)[0]['id_gir'];
                $aux_id_puser = $_POST['id_puser'];
                $code = $_POST['code'];
                
                $sql = $this->con->prepare("SELECT * FROM pedidos_usuarios WHERE id_puser=? AND codigo=?");
                $sql->bind_param("is", $aux_id_puser, $code);
                $sql->execute();
                $res = $sql->get_result();

                $id_puser = ($res->num_rows == 1) ? $aux_id_puser : 0 ;

                if($this->enviar_error_int($error, $codes, $status, $id_puser, $id_gir)){
                    $info['op'] = 1;
                }else{
                    $info['op'] = 2;
                }

            }
            $sqlg->close();
            
        }

        return $info;

    }
    private function pedido_direccion($pedido, $id_puser){

        $id = 0;
        if($direccion != ""){
            $sqlpdi = $this->con->prepare("INSERT INTO pedidos_direccion (direccion, calle, num, depto, comuna, lat, lng, id_puser) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $sqlpdi->bind_param("sssssddi", $pedido["direccion"], $pedido["calle"], $pedido["num"], $pedido["depto"], $pedido["comuna"], $pedido["lat"], $pedido["lng"], $id_puser);
            if($sqlpdi->execute()){
                // REPORTAR
                $id = $this->con->insert_id;
            }
            $sqlpdi->close();
        }
        return $id;

    }
    private function get_local_info($id_loc){

        $sqllg = $this->con->prepare("SELECT t1.t_retiro, t1.t_despacho, t1.code, t1.correo, t2.ssl, t2.dominio, t1.activar_envio, t1.lat, t1.lng, t1.id_gir, t2.num_ped, t1.telefono FROM locales t1, giros t2 WHERE t1.id_loc=? AND t1.id_gir=t2.id_gir AND t1.eliminado=? AND t2.eliminado=?");
        $sqllg->bind_param("iii", $id_loc, $this->eliminado, $this->eliminado);
        if($sqllg->execute()){

            $resultlg = $sqllg->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            $info['lat'] = $resultlg['lat'];
            $info['lng'] = $resultlg['lng'];
            $info['t_retiro'] = $resultlg['t_retiro'];
            $info['t_despacho'] = $resultlg['t_despacho'];
            $info['num_ped'] = $resultlg['num_ped'] + 1;
            $info['id_gir'] = $resultlg['id_gir'];
            $info['code'] = $resultlg['code'];
            $info['correo'] = $resultlg['correo'];
            $info['activar_envio'] = $resultlg['activar_envio'];
            $info['telefono'] = $resultlg['telefono'];
            $aux_url = ($resultlg['ssl'] == 1) ? 'https://' : 'http://' ;
            $info['url'] = $aux_url.$resultlg['dominio'];

        }else{
            // REPORTAR ERROR
        }
        $sqllg->free_result();
        $sqllg->close();
        return $info;

    }
    private function verify_despacho($pedido){

        $verify_despacho = 0;
        if($pedido['despacho'] == 1){
            $aux_verify = $this->get_info_despacho($pedido['lat'], $pedido['lng']);
            if($aux_verify['op'] == 1 && $aux_verify['id_loc'] == $pedido['id_loc'] && $aux_verify['precio'] == $pedido['costo']){
                $verify_despacho = 1;
            }
        }
        return $verify_despacho;

    }
    public function enviar_pedido(){

        if($this->verificar()){

            $puser = $_POST['puser'];
            $pedido = $_POST['pedido'];
            $carro = $_POST['carro'];
            $promos = (isset($_POST['promos']))? $_POST['promos'] : [] ;
            $info['set_puser'] = 0;
            $pdir_id = 0;

            // PEDIDOS USUARIOS Y DIRECCIONES //
            $sql = $this->con->prepare("SELECT * FROM pedidos_usuarios WHERE id_puser=? AND codigo=? AND telefono=?");
            $sql->bind_param("iss", $puser["id_puser"], $puser["code"], $pedido["telefono"]);
            if($sql->execute()){

                $res = $sql->get_result();
                if($res->{'num_rows'} == 0){
            
                    $puser_code = bin2hex(openssl_random_pseudo_bytes(10));
                    $cont = 1;
                    $sqlipu = $this->con->prepare("INSERT INTO pedidos_usuarios (codigo, nombre, telefono, cont) VALUES (?, ?, ?, ?)");
                    $sqlipu->bind_param("sssi", $puser_code, $pedido["nombre"], $pedido["telefono"], $cont);
                    if($sqlipu->execute()){
                        
                        $id_puser = $this->con->insert_id;
                        $info['set_puser'] = 1;
                        $info['puser_id'] = $id_puser;
                        $info['puser_code'] = $puser_code;
                        $info['puser_nombre'] = $pedido["nombre"];
                        $info['puser_telefono'] = $pedido["telefono"];
                        if($pedido['despacho'] == 1){
                            $pdir_id = $this->pedido_direccion($pedido, $id_puser);
                        }

                    }else{
                        $this->enviar_error_int($sqlipu->error, '#P03', 0, 0, 0);
                    }
                    $sqlipu->close();
            
                }
                
                if($res->{'num_rows'} == 1){
                
                    $id_puser = $puser["id_puser"];
                    $cont = $res->fetch_all(MYSQLI_ASSOC)[0]["cont"] + 1;
                    $sqlupu = $this->con->prepare("UPDATE pedidos_usuarios SET cont=? WHERE id_puser=?");
                    $sqlupu->bind_param("ii", $cont, $id_puser);
                    if(!$sqlupu->execute()){
                        $this->enviar_error_int($sqlupu->error, '#P02', 0, $id_puser, 0);
                    }
                    $sqlupu->close();
                        
                    $sqlpd = $this->con->prepare("SELECT id_pdir FROM pedidos_direccion WHERE id_puser=? AND lat=? AND lng=?");
                    $sqlpd->bind_param("idd", $id_puser, $pedido['lat'], $pedido['lng']);
                    if($sqlpd->execute()){
                        $res_pdir = $sqlpd->get_result();
                        if($res_pdir->{'num_rows'} == 1){
                            $pdir_id = $res_pdir->fetch_all(MYSQLI_ASSOC)[0]["id_pdir"];
                        }
                        if($res_pdir->{'num_rows'} == 0 && $pedido['despacho'] == 1){
                            $pdir_id = $this->pedido_direccion($pedido, $id_puser);
                        }
                    }
                    $sqlpd->free_result();
                    $sqlpd->close();
                    
                }

            }else{
                $this->enviar_error_int($sql->error, '#P01', 0, $puser["id_puser"], 0);
            }
            $sql->free_result();
            $sql->close();


            $local_data = $this->get_local_info($pedido["id_loc"]);
            $verify_despacho = $this->verify_despacho($pedido);
            
            /*
            $tz_object = new DateTimeZone('America/Santiago');
            $datetime = new DateTime();
            $datetime->setTimezone($tz_object);
            $fecha_stgo = $datetime->format('Y-m-d H:i:s');
            */

            $time_stgo = time();
            $fecha_stgo = date('Y-m-d H:i:s', $time_stgo);
            
            $pedido_code = bin2hex(openssl_random_pseudo_bytes(10));
            $tipo = 1;
            $sqlipa = $this->con->prepare("INSERT INTO pedidos_aux (num_ped, code, fecha, despacho, tipo, id_loc, carro, promos, verify_despacho, pre_gengibre, pre_wasabi, pre_embarazadas, pre_palitos, pre_teriyaki, pre_soya, comentarios, costo, total, id_puser, id_pdir, id_gir) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $sqlipa->bind_param("issiiissiiiiiiisiiiii", $local_data['num_ped'], $pedido_code, $fecha_stgo, $pedido['despacho'], $tipo, $pedido["id_loc"], json_encode($_POST['carro']), json_encode($promos), $verify_despacho, $pedido["pre_gengibre"], $pedido["pre_wasabi"], $pedido["pre_embarazadas"], $pedido["pre_palitos"], $pedido["pre_teriyaki"], $pedido["pre_soya"], $pedido["comentarios"], $pedido["costo"], $pedido["total"], $id_puser, $pdir_id, $local_data['id_gir']);
            
            if($sqlipa->execute()){
        
                $id_ped = $this->con->insert_id;
        
                $sqlugi = $this->con->prepare("UPDATE giros SET num_ped=? WHERE id_gir=? AND eliminado=?");
                $sqlugi->bind_param("iii", $local_data['num_ped'], $local_data['id_gir'], $this->eliminado);
                if(!$sqlugi->execute()){
                    // REPORTAR ERROR
                }
                $sqlugi->close();
        
                $info['op'] = 1;
                $info['id_ped'] = $id_ped;
                $info['num_ped'] = $local_data['num_ped'];
                $info['lat'] = $local_data['lat'];
                $info['lng'] = $local_data['lng'];
                $info['t_retiro'] = $local_data['t_retiro'];
                $info['t_despacho'] = $local_data['t_despacho'];
                $info['pedido_code'] = $pedido_code;
                $info['fecha'] = $time_stgo;
        
                $pedido_m['local_code'] = $local_data['code'];
                $pedido_m['id_ped'] = $id_ped;
                $pedido_m['num_ped'] = $local_data['num_ped'];
                $pedido_m['pedido_code'] = $pedido_code;
                
                $pedido_m['correo'] = $local_data['correo'];
                $pedido_m['accion'] = 'enviar_pedido_local';
                $pedido_m['activar_envio'] = $local_data['activar_envio'];
                $pedido_m['hash'] = 'Lrk}..75sq[e)@/22jS?ZGJ<6hyjB~d4gp2>^qHm';
                $pedido_m['dominio'] = $local_data['dominio'];
                $pedido_m['nombre'] = $pedido["nombre"];
                $pedido_m['telefono'] = $pedido["telefono"];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/enviar_local');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pedido_m));
                $resp = json_decode(curl_exec($ch));

                if($resp->{'op'} == 1){

                    $info['email'] = 1;

                }
                if($resp->{'op'} == 2){

                    $info['email'] = 2;
                    $this->enviar_error_int('Email no enviado', '#A04', 0, $id_puser, $local_data['id_gir']);
                    $info['telefono'] = $local_data['telefono'];
                    $info['correo'] = $local_data['correo'];
                    $info['url'] = $local_data['url'];

                }
                curl_close($ch);

            }else{
        
                $info['op'] = 2;
                $this->enviar_error_int($sqlipa->error, '#A03', 0, $id_puser, $local_data['id_gir']);

                $info['telefono'] = $local_data['telefono'];
                $info['correo'] = $local_data['correo'];
                $info['url'] = $local_data['url'];
        
            }
    
            $sqlipa->close();
    
        }else{
            $info['op'] = 2;
            $this->enviar_error_int('Enviado Pedido no verificado', '#Z01', 0, 0, 0);
        }
        return $info;
    
    }
    public function get_informe($from, $to){

        $sqlgir = $this->con->prepare("SELECT nombre FROM giros WHERE id_gir=? AND eliminado=?");
        $sqlgir->bind_param("ii", $this->id_gir, $this->eliminado);
        $sqlgir->execute();
        $result = $sqlgir->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $data["nombre"] = $result["nombre"];
        $sqlgir->free_result();
        $sqlgir->close();

        $sqlloc = $this->con->prepare("SELECT id_loc, nombre FROM locales WHERE id_gir=? AND eliminado=?");
        $sqlloc->bind_param("ii", $this->id_gir, $this->eliminado);
        $sqlloc->execute();
        $locales = $sqlloc->get_result()->fetch_all(MYSQLI_ASSOC);
        $sqlloc->free_result();
        $sqlloc->close();

        if($sqlloc = $this->con->prepare("SELECT * FROM pedidos_aux WHERE id_gir=? AND fecha > ? AND fecha < ? AND eliminado=?")){
            if($sqlloc->bind_param("issi", $this->id_gir, $from, $to, $this->eliminado)){
                if($sqlloc->execute()){
                    $resultloc = $sqlloc->get_result();
                    while($row = $resultloc->fetch_assoc()){
                        $pedidos[] = $row;
                    }
                }
            }
        }
        $sqlloc->free_result();
        $sqlloc->close();

        $from = strtotime($from);
        $to = strtotime($to) + 86400;        
        $dif_tiempo = round(($to - $from)/86400);
        $aux_from = $from;
        $mes = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        
        if($dif_tiempo <= 50){
            $lapse = "1 day";
            while($to > $aux_from){
                $info['xAxis']['categories'][] = date("d", $aux_from);
                $infos['fecha'][] = $aux_from;
                $aux_from = $aux_from + 86400;
            }
        }
        if($dif_tiempo > 50 && $dif_tiempo < 548){
            $lapse = "1 month";
            while($to > $aux_from){
                $aux_mes = intval(date("m", $aux_from)) - 1;
                $info['xAxis']['categories'][] = $mes[$aux_mes];
                $infos['fecha'][] = $aux_from;
                $aux_from = strtotime('+1 month', $aux_from);
            }
        }
        if($dif_tiempo >= 548){
            $lapse = "1 year";
            while($to > $aux_from){
                $info['xAxis']['categories'][] = date("Y", $aux_from);
                $infos['fecha'][] = $aux_from;
                $aux_from = strtotime('+1 Year', $aux_from);
            }
        }
        
        $info['chart']['type'] = 'line';
        $info['credits']['enabled'] = false;

        $info['legend']['layout'] = 'vertical';
        $info['legend']['align'] = 'right';
        $info['legend']['verticalAlign'] = 'top';
        $info['legend']['x'] = 0;
        $info['legend']['y'] = 0;
        $info['legend']['floating'] = true;
        $info['legend']['borderWidth'] = 1;
        $info['legend']['backgroundColor'] = '#fff';

        $info['yAxis']['title']['text'] = null;
        $info['plotOptions']['line']['dataLabels']['enabled'] = true;
        $info['plotOptions']['line']['enableMouseTracking'] = false;
        
        $sqlacc = $this->con->prepare("SELECT * FROM seguimiento WHERE id_gir=?");
        $sqlacc->bind_param("i", $this->id_gir);
        $sqlacc->execute();
        $acciones = $sqlacc->get_result()->fetch_all(MYSQLI_ASSOC);
        $sqlacc->free_result();
        $sqlacc->close();

        // CHART 1
        $info['title']['text'] = 'Administrador';  
        $data["chart1"] = $info;

        $tipo_nom = ["Ingresos", "Errores"];
        $tipo_num = [0, 1];
        for($i=0; $i<count($tipo_nom); $i++){
            $aux['name'] = $tipo_nom[$i];
            foreach($infos['fecha'] as $fecha){
                $aux['data'][] = $this->ver_acciones($acciones, $fecha, $lapse, $tipo_num[$i]);
            }
            $data['chart1']['series'][] = $aux;
            unset($aux);
        }

        // CHART 2
        $info['title']['text'] = 'Cantidad Ventas'; 
        $data["chart2"] = $info;
        $aux['name'] = 'Pedidos';
        foreach($infos['fecha'] as $fecha){
            $aux['data'][] = $this->ver_acciones($acciones, $fecha, $lapse, 1);
        }
        $data['chart2']['series'][] = $aux;
        unset($aux);

        // CHART 3
        $info['title']['text'] = 'Total Ventas';
        $data["chart3"] = $info;           
        for($j=0; $j<count($locales); $j++){
            $aux['name'] = $locales[$j]['nombre'];
            foreach($infos['fecha'] as $fecha){
                $aux['data'][] = $this->pedidos_total_fecha($pedidos, $fecha, $lapse, $locales[$j]['id_loc']);
            }
            $data["chart3"]['series'][] = $aux;
            unset($aux);
        }

        return $data;

    }
    public function ver_acciones($acciones, $fecha_ini, $intervalo, $tipo){
        
        $total = 0;
        for($i=0; $i<count($acciones); $i++){
            $fecha_pedido = strtotime($acciones[$i]['fecha']);
            $fecha_fin = strtotime($intervalo, $fecha_ini);            
            if($fecha_pedido >= $fecha_ini && $fecha_pedido < $fecha_fin){
                if($tipo == $acciones[$i]['id_des']){
                    $total = $total + 1;
                }
            }
        }
        return $total;
        
    }
    public function get_stats($tipo, $locales, $from, $to){

        if($sql = $this->con->prepare("SELECT * FROM pedidos_aux WHERE id_gir=? AND fecha > ? AND fecha < ? AND eliminado=?")){
            if($sql->bind_param("issi", $this->id_gir, $from, $to, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result();
                    while($row = $result->fetch_assoc()){
                        $pedidos[] = $row;
                    }
                }
            }
        }        

        $sql->free_result();
        $sql->close();

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