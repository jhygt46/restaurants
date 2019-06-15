<?php
session_start();
require_once "/var/www/html/config/config.php";

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
        $code = $_POST["code"];

        $ip = $_SERVER['REMOTE_ADDR'];
        $port = $_SERVER['SERVER_PORT'];

        $sqlgir = $this->con->prepare("SELECT t2.ip, t2.code FROM giros t1, server t2 WHERE t1.dominio=? AND t1.id_ser=t2.id_ser AND t1.eliminado=? AND t2.code=?");
        $sqlgir->bind_param("sis", $host, $this->eliminado, $code);
        $sqlgir->execute();
        $res = $sqlgir->get_result();
        $ret = false;
        
        if($res->{'num_rows'} == 1){
            $result = $res->fetch_all(MYSQLI_ASSOC)[0];
            if($ip == $result["ip"] && $port == "443"){
                $ret = true;
            }
        }

        $sqlgir->free_result();
        $sqlgir->close();
        return $ret;

    }
    public function is_giro(){

        if($_GET["id_gir"] > 0 && $this->id_gir != $_GET["id_gir"]){

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
    public function get_informe(){

        $sqlgir = $this->con->prepare("SELECT * FROM giros WHERE id_gir=? AND eliminado=?");
        $sqlgir->bind_param("ii", $this->id_gir, $this->eliminado);
        $sqlgir->execute();
        $result = $sqlgir->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $sqlgir->free_result();
        $sqlgir->close();


        // TIPO 1 POR WEB
        // TIPO 2 POR POS
        $sqlped = $this->con->prepare("SELECT * FROM pedidos_aux WHERE id_gir=? AND eliminado=?");
        $sqlped->bind_param("ii", $this->id_gir, $this->eliminado);
        $sqlped->execute();
        $pedidos = $sqlped->get_result()->fetch_all(MYSQLI_ASSOC);
        $sqlped->free_result();
        $sqlped->close();

        $info["visitas"] = $result["visitas"];

        return $info;

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
        $aux['precio'] = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]["precio"];
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
    public function get_pagina($id_pag){

        $sql = $this->con->prepare("SELECT * FROM paginas WHERE id_pag=? AND id_gir=? AND eliminado=?");
        $sql->bind_param("iii", $id_pag, $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
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

        $sql = $this->con->prepare("SELECT id_user, nombre, correo, re_venta, admin, id_aux_user FROM fw_usuarios WHERE id_user=? AND eliminado=?");
        $sql->bind_param("ii", $this->id_user, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $sql->free_result();
        $sql->close();
        return $result;

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

        $sql = $this->con->prepare("SELECT id_user, nombre FROM fw_usuarios WHERE id_loc=? AND id_gir=? AND admin='0' AND eliminado=?");
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
    public function get_data($dominio){
        
        $sql = $this->con->prepare("SELECT * FROM giros WHERE dominio=?");
        $sql->bind_param("s", $dominio);
        $sql->execute();
        $sql->store_result();

        $info['favicon'] = "misitiodelivery.ico";
        if($sql->{"num_rows"} == 1){
            
            $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            

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
        $sql = $this->con->prepare("SELECT id_loc, nombre, direccion, lat, lng FROM locales WHERE id_gir=? AND t1.eliminado=?");
        $sql->bind_param("ii", $id_gir, $eliminado);
        $sql->execute();
        $result = $sql->get_result();

        while($row = $result->fetch_assoc()){

            $locales['id_loc'] = $row['id_loc'];
            $locales['nombre'] = $row['nombre'];
            $locales['direccion'] = $row['direccion'];
            $locales['lat'] = $row['lat'];
            $locales['lng'] = $row['lng'];

            $sqlloc = $this->con->prepare("SELECT dia_ini, dia_fin, hora_ini, hora_fin, min_ini, min_fin, tipo FROM horarios WHERE id_loc=? AND id_gir=? AND t1.eliminado=?");
            $sqlloc->bind_param("iii", $row["id_loc"], $id_gir, $eliminado);
            $sqlloc->execute();
            $locales['horarios'] = $sqlloc->get_result()->fetch_all(MYSQLI_ASSOC);

            $loc[] = $locales;
            unset($locales);

        }
        return $loc;
        
    }
    public function get_web_js_data_remote(){
        
        if($this->verificar()){

            $host = $_POST["host"];
            $eliminado = 0;

            if($sqlgiro = $this->con->prepare("SELECT id_gir FROM giros WHERE dominio=? AND eliminado=?")){
                
                $sqlgiro->bind_param("si", $host, $eliminado);
                $sqlgiro->execute();		
                $id_gir = $sqlgiro->get_result()->fetch_all(MYSQLI_ASSOC)[0]["id_gir"];
                $sqlgiro->free_result();
                $sqlgiro->close();

                $sql = $this->con->prepare("SELECT * FROM catalogo_productos WHERE id_gir=? AND eliminado=?");
                $sql->bind_param("ii", $id_gir, $eliminado);
                $sql->execute();
                $result = $sql->get_result();

                $info = ["data" => [], "info" => [], "polygons" => [], "op" => 2];
                while($row = $result->fetch_assoc()){
                    $info['data']['catalogos'][] = get_info_catalogo($row['id_cat'], $con);
                    $info['op'] = 1;
                }
                $sql->free_result();
                $sql->close();

                $info['data']['paginas'] = $this->get_paginas_web($id_gir);
                $info['data']['config'] = $this->get_config($id_gir);
                $info['data']['locales'] = $this->get_locales_js($id_gir);
                $info['info'] = $this->get_data($id_gir);
                $info['polygons'] = $this->get_polygons($id_gir);
                return json_encode($info);

            }else{
                
                $error = $this->con->errno.' '.$this->con->error;
                echo $error;

            }
            
        }

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

            if($row['tipo'] == 0){

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

            if($tipo == 1){

                $aux_categoria['tipo'] = 1;
                
                $sqlpc = $this->con->prepare("SELECT id_cae2 as id_cae, cantidad FROM promocion_categoria WHERE id_cae1=?");
                $sqlpc->bind_param("ii", $row['id_cae']);
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
    public function ver_detalle($code){
        
        $sql = $this->con->prepare("SELECT t1.id_ped, t1.num_ped, t1.id_loc, t3.ssl, t3.code, t1.id_ped, t1.id_puser, t1.id_pdir, t1.despacho, t1.carro, t1.promos, t1.pre_wasabi, t1.pre_gengibre, t1.pre_embarazadas, t1.pre_soya, t1.pre_teriyaki, t1.pre_palitos, t1.comentarios, t1.costo, t1.total, t1.verify_despacho FROM pedidos_aux t1, locales t2, giros t3 WHERE t1.code=? AND t1.id_loc=t2.id_loc AND t2.id_gir=t3.id_gir AND t3.dominio=? AND t1.eliminado=? AND t1.fecha > DATE_ADD(NOW(), INTERVAL -2 DAY)");
        $sql->bind_param("isi", $code, $_SERVER["HTTP_HOST"], $this->eliminado);
        $sql->execute();
        $sql->store_result();
        $info['op'] = false;

        if($sql->{"num_rows"} == 1){

            $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            $sql->free_result();
            $sql->close();

            $path = ($result["ssl"] == 1 || $_SERVER["HTTP_HOST"] == "misitiodelivery.cl") ? "https://".$_SERVER["HTTP_HOST"] : "http://".$_SERVER["HTTP_HOST"] ;
            $info['css_base'] = $path."/css/reset.css";
            $info['css_detalle'] = $path."/css/css_detalle_01.css";

            $sqlloc = $this->con->prepare("SELECT id_loc, nombre FROM locales WHERE id_loc=? AND eliminado=?");
            $sqlloc->bind_param("ii", $result["id_loc"], $this->eliminado);
            $sqlloc->execute();
            $resulloc = $sqlloc->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            $sqlloc->free_result();
            $sqlloc->close();

            $info['local'] = $resulloc["nombre"];
            $info['js_jquery'] = $path."/js/jquery-1.3.2.min.js";
            $info['js_data'] = $path."/js/data/".$result['code'].".js";
            $info['js_detalle'] = $path."/js/detalle.js";

            $sqlpus = $this->con->prepare("SELECT nombre, telefono FROM pedidos_usuarios WHERE id_puser=?");
            $sqlpus->bind_param("i", $result["id_puser"]);
            $sqlpus->execute();
            $resulpus = $sqlpus->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            $sqlpus->free_result();
            $sqlpus->close();

            $info['puser'] = $resulpus;

            if($result["despacho"] == 1 && $result["id_pdir"] != 0){

                $sqlpdi = $this->con->prepare("SELECT * FROM pedidos_direccion WHERE id_pdir=?");
                $sqlpdi->bind_param("i", $result["id_pdir"]);
                $sqlpdi->execute();
                $resulpdi = $sqlpdi->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                $sqlpdi->free_result();
                $sqlpdi->close();

                $info['pdir'] = $resulpdi;

            }

            $info['id_ped'] = $result['id_ped'];
            $info['num_ped'] = $result['num_ped'];
            $info['carro'] = $result['carro'];
            $info['promos'] = $result['promos'];
            
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
            
            $info['op'] = true;

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
    public function get_data_pos($id, $code){

        $sql = $this->con->prepare("SELECT t2.item_pos, t2.code as js_data, t2.font_family, t2.font_css, t2.estados, t1.t_retiro, t1.t_despacho, t2.dominio, t1.lat, t1.lng, t1.code, t1.nombre, t1.tipo_comanda, t1.sonido, t2.ssl FROM locales t1, giros t2 WHERE t1.id_loc=? AND t1.cookie_code=? AND t1.id_gir=t2.id_gir AND t1.eliminado=? AND t2.eliminado=?");
        $sql->bind_param("iiii", $id, $code, $this->eliminado, $this->eliminado);
        $sql->execute();
        $sql->store_result();

        if($sql->{"num_rows"} == 0){

            if($info['ssl'] == 0){
                die("<meta http-equiv='refresh' content='0; url=https://misitiodelivery.cl/admin'>");
            }
            if($info['ssl'] == 1){
                die("<meta http-equiv='refresh' content='0; url=https://".$_SERVER["HTTP_HOST"]."/admin'>");
            }

        }
        if($sql->{"num_rows"} == 1){

            $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            $info['pedidos'] = $this->get_ultimos_pedidos_pos($id);
            $info['motos'] = $this->get_repartidores_local($id);

            $info['code'] = $result['code'];
            $info['nombre'] = $result['nombre'];
            $info['tipo_comanda'] = $result['tipo_comanda'];
            $info['sonido'] = $result['sonido'];
            $info['ssl'] = $result['ssl'];
            $info['lat'] = $result['lat'];
            $info['lng'] = $result['lng'];
            $info['js_data'] = $result['js_data'].".js";
            $info['dominio'] = $result['dominio'];
            $info['t_retiro'] = $result['t_retiro'];
            $info['t_despacho'] = $result['t_despacho'];
            $info['estados'] = explode(",", $result['estados']);
            $info['font']['family'] = $result['font_family'];
            $info['font']['css'] = $result['font_css'];
            $info['path'] = ($info['ssl'] == 1 || $_SERVER["HTTP_HOST"] == "misitiodelivery.cl") ? "https://".$_SERVER["HTTP_HOST"] : "http://".$_SERVER["HTTP_HOST"] ;
            
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
    public function get_ultimos_pedidos($id_ped){
        
        $id_loc = $_COOKIE['ID'];
        $cookie_code = $_COOKIE['CODE'];

        $sql = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND cookie_code=? AND eliminado=?");
        $sql->bind_param("isi", $id_loc, $cookie_code, $this->eliminado);
        $sql->execute();
        $sql->store_result();

        if($sql->{"num_rows"} == 1){

            $sqlped = $this->con->prepare("SELECT * FROM pedidos_aux WHERE id_ped=? AND id_loc=? AND eliminado=?");
            $sqlped->bind_param("iii", $id_ped, $id_loc, $this->eliminado);
            $sqlped->execute();
            $resultped = $sqlped->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            $sqlped->free_result();
            $sqlped->close();

            $res['id_ped'] = $resultped['id_ped'];
            $res['num_ped'] = $resultped['num_ped'];
            $res['pedido_code'] = $resultped['code'];
            $res['tipo'] = $resultped['tipo'];
            $res['estado'] = $resultped['estado'];
            $res['fecha'] = strtotime($resultped['fecha']);
            $res['despacho'] = $resultped['despacho'];

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

        $sql = $this->con->prepare("SELECT * FROM pedidos_aux WHERE id_loc=? AND eliminado=?");
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
        
        $id_loc = $_COOKIE['ID'];
        $cookie_code = $_COOKIE['CODE'];
        
        $sql = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND cookie_code=? AND eliminado=?");
        $sql->bind_param("iii", $id_loc, $cookie_code, $this->eliminado);
        $sql->execute();
        $sql->store_result();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];

        $info["alert"] = "";
        $id_gir = $result["id_gir"];
        $local_code = $result["code"];
        $enviar_cocina = $result["enviar_cocina"];

        if($sql->{"num_rows"} == 1){

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
                $sqlaux = $this->con->prepare("INSERT INTO pedidos_aux (num_ped, tipo, fecha, code, id_loc) VALUES (?, '0', now(), ?, ?)");
                $sqlaux->bind_param("iii", $num_ped, $code, $id_loc);
                $sqlaux->execute();
                $id_ped = $this->con->insert_id;
                $sqlaux->close();

                $info['id_ped'] = $id_ped;
                $info['num_ped'] = $num_ped;
                $info['pedido_code'] = $code;

            }

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

            $info['carro'] = ($sql_carro != "") ? json_decode($sql_carro) : [] ;

            if($id_puser == 0 && $sql_id_puser == 0){
                if(strlen($telefono) == 12 || strlen($telefono) == 13){

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
                if($sql_carro == "" || ($mod_despacho == 0 && $sql_tipo == 1)){
                    
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

        return $info;
        
    }


}
?>