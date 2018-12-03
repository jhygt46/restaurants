<?php
session_start();

require_once($path."admin/class/core_class.php");

class Guardar extends Core{
    
    public $con = null;
    public $id_user = null;
    public $admin = null;
    public $id_gir = null;
    public $id_cat = null;
    
    public function __construct(){
        
        $this->con = new Conexion();
        $this->id_user = $_SESSION['user']['info']['id_user'];
        $this->admin = $_SESSION['user']['info']['admin'];
        $this->id_gir = $_SESSION['user']['id_gir'];
        $this->id_cat = $_SESSION['user']['id_cat'];
    }
    public function process(){
        
        if($_POST['accion'] == "crear_giro"){
            return $this->crear_giro();
        }
        if($_POST['accion'] == "eliminar_giro"){
            return $this->eliminar_giro();
        }
        if($_POST['accion'] == "crear_catalogo"){
            return $this->crear_catalogo();
        }
        if($_POST['accion'] == "eliminar_catalogo"){
            return $this->eliminar_catalogo();
        }
        if($_POST['accion'] == "crear_locales"){
            return $this->crear_locales();
        }
        if($_POST['accion'] == "crear_locales_tramos"){
            return $this->crear_locales_tramos();
        }
        if($_POST['accion'] == "eliminar_locales"){
            return $this->eliminar_locales();
        }
        if($_POST['accion'] == "crear_usuario"){
            return $this->crear_usuario();
        }
        if($_POST['accion'] == "eliminar_usuario"){
            return $this->eliminar_usuario();
        }
        if($_POST['accion'] == "asignar_rubro"){
            return $this->asignar_rubro();
        }
        if($_POST['accion'] == "crear_categoria"){
            return $this->crear_categoria();
        }
        if($_POST['accion'] == "eliminar_categoria"){
            return $this->eliminar_categoria();
        }
        if($_POST['accion'] == "crear_ingredientes"){
            return $this->crear_ingredientes();
        }
        if($_POST['accion'] == "eliminar_ingrediente"){
            return $this->eliminar_ingrediente();
        }
        if($_POST['accion'] == "crear_promociones"){
            return $this->crear_promociones();
        }
        if($_POST['accion'] == "eliminar_promociones"){
            return $this->eliminar_promociones();
        }
        if($_POST['accion'] == "crear_productos"){
            return $this->crear_productos();
        }
        if($_POST['accion'] == "eliminar_productos"){
            return $this->eliminar_productos();
        }
        if($_POST['accion'] == "asignar_prods_promocion"){
            return $this->asignar_prods_promocion();
        }
        if($_POST['accion'] == "crear_preguntas"){
            return $this->crear_preguntas();
        }
        if($_POST['accion'] == "eliminar_preguntas"){
            return $this->eliminar_preguntas();
        }
        if($_POST['accion'] == "configurar_principal"){
            return $this->configurar_principal();
        }
        if($_POST['accion'] == "crear_pagina"){
            return $this->crear_pagina();
        }
        if($_POST['accion'] == "configurar_footer"){
            return $this->configurar_footer();
        }
        if($_POST['accion'] == "refresh"){
            return $this->refresh();
        }
        if($_POST['accion'] == "configurar_categoria"){
            return $this->configurar_categoria();
        }
        if($_POST['accion'] == "ordercat"){
            return $this->ordercat();
        }
        if($_POST['accion'] == "configurar_producto"){
            return $this->configurar_producto();
        }
        if($_POST['accion'] == "eliminar_tramos"){
            return $this->eliminar_tramos();
        }
        
    }
    private function ordercat(){
        
        $values = $_POST['values'];
        for($i=0; $i<count($values); $i++){
            $this->con->sql("UPDATE categorias SET orders='".$i."' WHERE id_cae='".$values[$i]."' AND id_cat='".$this->id_cat."'");
        }

    }
    public function refresh(){
        
        if($this->id_gir > 0){
            $this->get_web_js_data2($this->id_gir);
        }
        
    }
    public function ingresarimagen($filepath, $filename, $i){

        $filename = ($filename !== null) ? $filename : bin2hex(openssl_random_pseudo_bytes(10)) ;
        $file_formats = array("jpg", "png", "gif", "ico");
        //$filepath = "/var/www/html/restaurants/images/logos/";

        $name = $_FILES['file_image'.$i]['name']; // filename to get file's extension
        $size = $_FILES['file_image'.$i]['size'];

        if (strlen($name)){
            $extension = substr($name, strrpos($name, '.')+1);
            if (in_array($extension, $file_formats)) { // check it if it's a valid format or not
                if ($size < (2048 * 1024)) { // check it if it's bigger than 2 mb or no
                    $imagename = $filename.".".$extension;
                    $tmp = $_FILES['file_image0']['tmp_name'];
                    if (move_uploaded_file($tmp, $filepath . $imagename)){
                        $info['op'] = 1;
                        $info['mensaje'] = "Imagen subida";
                        $info['image'] = $imagename;
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "No se pudo subir la imagen";
                    }
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Imagen sobrepasa los 2MB establecidos";
                }
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Formato Invalido";
            }
        }else{
            $info['op'] = 2;
            $info['mensaje'] =  "No ha seleccionado una imagen";
        }
        return $info;

    }

    private function configurar_footer(){
        
        $texto = $_POST['texto'];        
        $this->con->sql("UPDATE giros SET footer_html='".$texto."' WHERE id_gir='".$this->id_gir."'");
        
        $info['op'] = 1;
        $info['mensaje'] = "Footer modificado exitosamente";
        
        $info['reload'] = 1;
        $info['page'] = "apps/configurar_giro.php";
        return $info;
        
    }
    private function con_cambios(){
        $this->con->sql("UPDATE giros SET con_cambios='1' WHERE id_gir='".$this->id_gir."'");
    }
    private function configurar_principal(){
        
        $titulo = $_POST['titulo'];
        $font_family = $_POST['font-family'];
        $font_css = $_POST['font-css'];
        $css_types = $_POST['css-types'];
        $css_colores = $_POST['css-colores'];
        $css_popup = $_POST['css-popup'];
        
        $giro = $this->con->sql("SELECT * FROM giros WHERE id_gir='".$this->id_gir."'");
        $this->con_cambios();
        
        $foto_logo = $this->ingresarimagen('/var/www/html/restaurants/images/logos/', $giro['resultado'][0]['dominio'], 0);
        $foto_favicon = $this->ingresarimagen('/var/www/html/restaurants/images/favicon/', $giro['resultado'][0]['dominio'], 1);
        
        if($foto_logo['op'] == 1){
            $info['foto_logo'] = $this->con->sql("UPDATE giros SET logo='".$foto_logo['image']."' WHERE id_gir='".$this->id_gir."'");
        }
        if($foto_favicon['op'] == 1){
            $info['foto_favicon'] = $this->con->sql("UPDATE giros SET favicon='".$foto_favicon['image']."' WHERE id_gir='".$this->id_gir."'");
        }
        
        $info['db1'] = $this->con->sql("UPDATE giros SET titulo='".$titulo."', font_family='".$font_family."', font_css='".$font_css."', style_page='".$css_types."', style_color='".$css_colores."', style_modal='".$css_popup."', pedido_01_titulo='".$_POST['pedido_01_titulo']."', pedido_01_subtitulo='".$_POST['pedido_01_subtitulo']."', pedido_02_titulo='".$_POST['pedido_02_titulo']."', pedido_02_subtitulo='".$_POST['pedido_02_subtitulo']."', pedido_03_titulo='".$_POST['pedido_03_titulo']."', pedido_03_subtitulo='".$_POST['pedido_03_subtitulo']."', pedido_04_titulo='".$_POST['pedido_04_titulo']."', pedido_04_subtitulo='".$_POST['pedido_04_subtitulo']."' WHERE id_gir='".$this->id_gir."'");
        
        $info['op'] = 1;
        $info['mensaje'] = "Configuracion modificado exitosamente";
        
        $info['reload'] = 1;
        $info['page'] = "apps/configurar_giro.php";
        return $info;
        
    }
    private function configurar_categoria(){
        
        $id_cae = $_POST['id_cae'];
        $parent_id = $_POST['parent_id'];
        $mostar_prods = $_POST['mostrar_prods'];
        $ocultar = $_POST['ocultar'];
        $detalle_prods = $_POST['detalle_prods'];
        
        $image = $this->ingresarimagen('/var/www/html/restaurants/images/categorias/', null, 0);
        if($image['op'] == 1){
            $this->con->sql("UPDATE categorias SET image='".$image['image']."' WHERE id_cae='".$id_cae."'");
        }
        $this->con->sql("UPDATE categorias SET detalle_prods='".$detalle_prods."', ocultar='".$ocultar."', mostrar_prods='".$mostar_prods."' WHERE id_cae='".$id_cae."'");
        $info['op'] = 1;
        $info['mensaje'] = "Configuracion modificado exitosamente";
        
        $info['reload'] = 1;
        $info['page'] = "apps/categorias.php?parent_id=".$parent_id;
        return $info;
        
    }
    private function configurar_producto(){
        
        $id_pro = $_POST['id_pro'];
        $id = $_POST['id'];
        $list = $this->get_preguntas();
        for($i=0; $i<count($list); $i++){
            $pre = $_POST['pregunta-'.$list[$i]['id_pre']];
            if($pre == 0){
                $this->con->sql("DELETE FROM preguntas_productos WHERE id_pro='".$id_pro."' AND id_pre='".$list[$i]['id_pre']."'");
            }
            if($pre == 1){
                $this->con->sql("INSERT INTO preguntas_productos (id_pro, id_pre) VALUES ('".$id_pro."', '".$list[$i]['id_pre']."')");
            }
        }
        
        $info['op'] = 1;
        $info['mensaje'] = "Preguntas asociadas exitosamente";
        $info['reload'] = 1;
        $info['page'] = "apps/crear_productos.php?id=".$id;
        return $info;
        
    }
    
    
    
    private function crear_giro(){
        
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $dominio = $_POST['dominio'];
        $code = bin2hex(openssl_random_pseudo_bytes(10));
        
        if($id == 0){
            
            $aux = $this->con->sql("INSERT INTO giros (nombre, fecha_creado, dominio, catalogo, code, con_cambios, titulo, style_page, style_color, style_modal, font_family, font_css) VALUES ('".$nombre."', now(), '".$dominio."', '1', '".$code."', '1', '".$nombre."', 'css_tipo_01.css', 'css_colores_01.css', 'css_fontsize_01.css', 'K2D', 'K2D')");
            $this->con->sql("INSERT INTO catalogo_productos (nombre, fecha_creado, id_gir) VALUES ('Catalogo 01', now(), '".$aux['insert_id']."')");
            
            $info['op'] = 1;
            $info['mensaje'] = "Giro creado exitosamente";
            
            if($this->admin == 0){
                $this->con->sql("INSERT INTO fw_usuarios_giros (id_user, id_gir) VALUES ('".$this->id_user."', '".$aux['insert_id']."')");
            }
            if($this->admin == 1){
                $this->con->sql("INSERT INTO fw_usuarios_giros_clientes (id_user, id_gir) VALUES ('".$this->id_user."', '".$aux['insert_id']."')");
            }
            
        }
        if($id > 0){
            
            $this->con->sql("UPDATE giros SET nombre='".$nombre."', dominio='".$dominio."' WHERE id_gir='".$id."'");
            $info['op'] = 1;
            $info['mensaje'] = "Giro modificado exitosamente";
            
        }

        $info['reload'] = 1;
        $info['page'] = "base/giros.php";
        return $info;
        
    }
    private function eliminar_giro(){
                
        $id = $_POST['id'];
        $this->con->sql("UPDATE giros SET eliminado='1' WHERE id_gir='".$id."'");
        
        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Giro ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "base/giros.php";

        return $info;
        
    }
    private function eliminar_tramos(){
        
        $id = explode("/", $_POST['id']);
        $this->con->sql("UPDATE locales_tramos SET eliminado='1' WHERE id_lot='".$id[1]."'");
        
        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Giro ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "apps/zonas_locales.php?id_loc=".$id[0];

        return $info;
        
    }
    
    private function crear_pagina(){
        
        $id_pag = $_POST['id'];
        $nombre = $_POST['nombre'];
        $titulo = $_POST['titulo'];
        $subtitulo = $_POST['subtitulo'];
        $html = $_POST['html'];
        
        if($id_pag == 0){
            $this->con->sql("INSERT INTO paginas (nombre, titulo, subtitulo, html, id_gir) VALUES ('".$nombre."', '".$titulo."', '".$subtitulo."', '".$html."', '".$this->id_gir."')");
            $info['op'] = 1;
            $info['mensaje'] = "Paginas creado exitosamente";
        }
        if($id_pag > 0){
            $this->con->sql("UPDATE paginas SET nombre='".$nombre."', titulo='".$titulo."', subtitulo='".$subtitulo."', html='".$html."' WHERE id_pag='".$id_pag."' AND id_gir='".$this->id_gir."'");
            $info['op'] = 1;
            $info['mensaje'] = "Paginas modificado exitosamente";
        }
        
        $info['reload'] = 1;
        $info['page'] = "apps/configurar_giro.php";
        return $info;
        
    }

    private function crear_catalogo(){
        
        $id_cat = $_POST['id'];
        $nombre = $_POST['nombre'];
        
        if($id_cat == 0){
            $info['db1'] = $this->con->sql("INSERT INTO catalogo_productos (nombre, fecha_creado, id_gir, eliminado) VALUES ('".$nombre."', now(), '".$this->id_gir."', '0')");
            $info['op'] = 1;
            $info['mensaje'] = "Catalogo creado exitosamente";
        }
        if($id_cat > 0){
            $info['db2'] = $this->con->sql("UPDATE catalogo_productos SET nombre='".$nombre."' WHERE id_cat='".$id_cat."' AND id_gir='".$this->id_gir."'");
            $info['op'] = 1;
            $info['mensaje'] = "Catalogo modificado exitosamente";
        }
        
        $info['reload'] = 1;
        $info['page'] = "apps/catalogo_productos.php";
        return $info;
        
    }
    private function eliminar_catalogo(){
                
        $id = explode("/", $_POST['id']);
        $this->con->sql("UPDATE catalogo_productos SET eliminado='1' WHERE id_cat='".$id[1]."'");
        
        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Catalogo ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "apps/catalogo_productos.php?id=".$id[0];

        return $info;
        
    }
    private function crear_locales(){
        
        $id_loc = $_POST['id_loc'];
        $id_cat = $_POST['id_cat'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $direccion = $_POST['direccion'];
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $code = bin2hex(openssl_random_pseudo_bytes(10));
        
        if($id_loc == 0){
            $info['db1'] = $this->con->sql("INSERT INTO locales (nombre, direccion, lat, lng, code, fecha_creado, correo, id_gir, id_cat) VALUES ('".$nombre."', '".$direccion."', '".$lat."', '".$lng."', '".$code."', now(), '".$correo."', '".$this->id_gir."', '".$id_cat."')");
            $info['op'] = 1;
            $info['mensaje'] = "Local creado exitosamente";
        }
        if($id_loc > 0){
            $info['db2'] = $this->con->sql("UPDATE locales SET nombre='".$nombre."', correo='".$correo."', lat='".$lat."', lng='".$lng."', direccion='".$direccion."', id_cat='".$id_cat."' WHERE id_loc='".$id_loc."' AND id_gir='".$this->id_gir."'");
            $info['op'] = 1;
            $info['mensaje'] = "Local modificado exitosamente";
        }
        
        $info['reload'] = 1;
        $info['page'] = "apps/locales.php";
        return $info;
        
    }
    private function crear_locales_tramos(){
        
        $id_lot = $_POST['id_lot'];
        $id_loc = $_POST['id_loc'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $pol = $_POST['posiciones'];
        
        if($id_lot == 0){
            $info['db1'] = $this->con->sql("INSERT INTO locales_tramos (nombre, precio, poligono, id_loc, eliminado) VALUES ('".$nombre."', '".$precio."', '".$pol."', '".$id_loc."', '0')");
            $info['op'] = 1;
            $info['mensaje'] = "Tramo creado exitosamente";
        }
        if($id_lot > 0){
            $info['db1'] = $this->con->sql("UPDATE locales_tramos SET nombre='".$nombre."', precio='".$precio."', poligono='".$pol."' WHERE id_lot='".$id_lot."'");
            $info['op'] = 1;
            $info['mensaje'] = "Tramo modificado exitosamente";
        }
        
        $info['reload'] = 1;
        $info['page'] = "apps/zonas_locales.php?id_loc=".$id_loc;
        return $info;
        
    }
    private function eliminar_locales(){
                
        $id = explode("/", $_POST['id']);
        $this->con->sql("UPDATE locales SET eliminado='1' WHERE id_loc='".$id[1]."'");
        
        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Local ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "apps/locales.php?id=".$id[0];

        return $info;
        
    }
    private function crear_usuario(){
        
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        
        if($id == 0){
            $aux = $this->con->sql("INSERT INTO fw_usuarios (nombre, fecha_creado, correo) VALUES ('".$nombre."', now(), '".$correo."')");
            $info['op'] = 1;
            $info['mensaje'] = "Usuario creado exitosamente";
            $this->con->sql("INSERT INTO fw_usuarios_giros (id_user, id_gir) VALUES ('".$aux['insert_id']."', '".$this->id_gir."')");
        }
        if($id > 0){
            $this->con->sql("UPDATE fw_usuarios SET nombre='".$nombre."' WHERE id_user='".$id."'");
            $info['op'] = 1;
            $info['mensaje'] = "Usuarios modificado exitosamente";
        }
        
        $info['reload'] = 1;
        $info['page'] = "base/usuarios.php";
        return $info;
        
    }
    private function eliminar_usuario(){
                
        $id = $_POST['id'];
        $this->con->sql("UPDATE fw_usuarios SET eliminado='1' WHERE id_user='".$id."'");
        
        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Usuario ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "base/usuarios.php";

        return $info;
        
    }
    private function asignar_rubro(){
        
        $id_gir = $_POST['id'];
        $nombre = $_POST['nombre'];
        $this->con->sql("DELETE FROM palabra_giros WHERE id_gir='".$id_gir."'");
        $palabras = $this->con->sql("SELECT id_pal FROM palabras_claves WHERE is_giros='1'");
        
        for($i=0; $i<$palabras['count']; $i++){
            $id_pal = $palabras['resultado'][$i]['id_pal'];
            $post = $_POST["rubro-".$id_pal];
            if($post == 1){
                $this->con->sql("INSERT INTO palabra_giros (id_gir, id_pal) VALUES ('".$id_gir."', '".$id_pal."')");
            }
        }

        $info['reload'] = 1;
        $info['page'] = "base/configurar_giro.php?id=".$id_gir."&nombre=".$nombre;
        return $info;
        
    }
    private function crear_categoria(){

        $id_cae = $_POST['id_cae'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $descripcion_sub = $_POST['descripcion_sub'];
        $precio = $_POST['precio'];
        $parent_id = $_POST['parent_id'];
        $tipo = $_POST['tipo'];

        if($id_cae == 0){
            $info['db1'] = $this->con->sql("INSERT INTO categorias (nombre, parent_id, tipo, id_cat, descripcion, descripcion_sub, precio) VALUES ('".$nombre."', '".$parent_id."', '".$tipo."', '".$this->id_cat."', '".$descripcion."', '".$descripcion_sub."', '".$precio."')");
            $info['op'] = 1;
            $info['mensaje'] = "Categoria creada exitosamente";
        }
        if($id_cae > 0){
            $info['db2'] = $this->con->sql("UPDATE categorias SET nombre='".$nombre."', tipo='".$tipo."', descripcion='".$descripcion."', descripcion_sub='".$descripcion_sub."', precio='".$precio."' WHERE id_cae='".$id_cae."' AND id_cat='".$this->id_cat."'");
            $info['op'] = 1;
            $info['mensaje'] = "Categoria modificada exitosamente";
        }
                
        $info['reload'] = 1;
        $info['page'] = "apps/categorias.php?parent_id=".$parent_id;
        return $info;
        
    }
    private function crear_ingredientes(){

        $id = $_POST['id'];
        $id_ing = $_POST['id_ing'];
        $nombre = $_POST['nombre'];
        $parent_id = $_POST['parent_id'];

        if($id_ing == 0){
            $info['db'] = $this->con->sql("INSERT INTO ingredientes (nombre, parent_id, id_cat) VALUES ('".$nombre."', '".$parent_id."', '".$id."')");
            $info['op'] = 1;
            $info['mensaje'] = "Ingrediente creada exitosamente";
        }
        if($id_ing > 0){
            $this->con->sql("UPDATE ingredientes SET nombre='".$nombre."' WHERE id_ing='".$id_ing."'");
            $info['op'] = 1;
            $info['mensaje'] = "Ingrediente modificada exitosamente";
        }
                
        $info['reload'] = 1;
        $info['page'] = "apps/ingredientes.php?id=".$id."&parent_id=".$parent_id;
        return $info;
        
    }
    private function eliminar_categoria(){
                
        $id = explode("/", $_POST['id']);
        $this->con->sql("UPDATE categorias SET eliminado='1' WHERE id_cae='".$id[1]."'");
        
        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Categoria ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "apps/categorias.php?id=".$id[0]."&parent_id=".$id[2];

        return $info;
        
    }
    private function eliminar_ingredientes(){
                
        $id = explode("/", $_POST['id']);
        $this->con->sql("UPDATE ingredientes SET eliminado='1' WHERE id_ing='".$id[1]."'");
        
        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Ingredientes ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "apps/ingredientes.php?id=".$id[0]."&parent_id=".$id[2];

        return $info;
        
    }
    private function crear_promociones(){

        $id_prm = $_POST['id_prm'];
        $nombre = $_POST['nombre'];
        $parent_id = $_POST['parent_id'];

        if($id_prm == 0){
            $this->con->sql("INSERT INTO promociones (nombre, parent_id, id_cat) VALUES ('".$nombre."', '".$parent_id."', '".$this->id_cat."')");
            $info['op'] = 1;
            $info['mensaje'] = "Promocion creada exitosamente";
        }
        if($id_prm > 0){
            $this->con->sql("UPDATE promociones SET nombre='".$nombre."' WHERE id_prm='".$id_prm."' AND id_cat='".$this->id_cat."'");
            $info['op'] = 1;
            $info['mensaje'] = "Promocion modificada exitosamente";
        }
                
        $info['reload'] = 1;
        $info['page'] = "apps/promociones.php?id=".$id."&parent_id=".$parent_id;
        return $info;
        
    }
    private function eliminar_promociones(){
                
        $id = explode("/", $_POST['id']);
        $this->con->sql("UPDATE promociones SET eliminado='1' WHERE id_prm='".$id[1]."'");
        
        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Promocion ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "apps/promociones.php?id=".$id[0]."&parent_id=".$id[2];

        return $info;
        
    }
    
    public function asignar_prods_promocion(){
        
        $id_cae = $_POST['id_cae'];
        $parent_id = $_POST['parent_id'];
        $values = $this->list_arbol_cats_prods();
        
        $this->con->sql("DELETE FROM promocion_categoria WHERE id_cae1='".$id_cae."'");
        $this->con->sql("DELETE FROM promocion_productos WHERE id_cae='".$id_cae."'");
        
        for($i=0; $i<count($values); $i++){

            $value = $values[$i];
            if($value['id_cae'] !== null){
                $cae_val = $_POST['sel-cae-'.$value['id_cae']];
                if($cae_val > 0){
                    $info['db_cat'][] = $this->con->sql("INSERT INTO promocion_categoria (id_cae1, id_cae2, cantidad) VALUES ('".$id_cae."', '".$value['id_cae']."', '".$cae_val."')");
                }
            }
            if($value['id_pro'] !== null){
                $pro_val = $_POST['sel-pro-'.$value['id_pro']];
                if($pro_val > 0){
                    $info['db_pro'][] = $this->con->sql("INSERT INTO promocion_productos (id_cae, id_pro, cantidad) VALUES ('".$id_cae."', '".$value['id_pro']."', '".$pro_val."')");
                }
            }
            
        }      
        $info['reload'] = 1;
        $info['page'] = "apps/categorias.php?parent_id=".$parent_id;
        return $info;
        
    }
    
    private function crear_productos(){

        $id_pro = $_POST['id_pro'];
        $id_cae = $_POST['id'];
        $tipo = $_POST['tipo'];
        
        $numero = $_POST['numero'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        
        if($tipo == 0){
            if($id_pro == 0){
                $pro = $this->con->sql("INSERT INTO productos (numero, nombre, descripcion, fecha_creado, id_gir, eliminado) VALUES ('".$numero."', '".$nombre."', '".$descripcion."', now(), '".$this->id_gir."', '0')");
                $this->con->sql("INSERT INTO cat_pros (id_cae, id_pro) VALUES ('".$id_cae."', '".$pro['insert_id']."')");
                $info['db1'] = $this->con->sql("INSERT INTO productos_precio (id_cat, id_pro, precio) VALUES ('".$this->id_cat."', '".$pro['insert_id']."', '".$precio."')");    
            }
            if($id_pro > 0){
                $this->con->sql("UPDATE productos SET numero='".$numero."', nombre='".$nombre."', descripcion='".$descripcion."' WHERE id_pro='".$id_pro."'");
                $info['db2'] = $this->con->sql("UPDATE productos_precio SET precio='".$precio."' WHERE id_cat='".$this->id_cat."' AND id_pro='".$id_pro."'");
            }
        }
        if($tipo == 1){
            $all_prods = $this->get_productos();
            for($i=0; $i<count($all_prods); $i++){
                $pro = $_POST['prod-'.$all_prods[$i]['id_pro']];
                if($pro == 1){
                    $info['db1'] = $this->con->sql("INSERT INTO cat_pros (id_cae, id_pro) VALUES ('".$id_cae."', '".$all_prods[$i]['id_pro']."')");
                }
            }
        }
        if($tipo == 2){
            
        }
        
        /*
        $this->get_inputs($id_cae);
        $inputs = $this->show_inputs();
        
        
        for($i=0; $i<count($inputs); $i++){
            $this->con->sql("UPDATE productos SET ".$inputs[$i]['campo']."='".$_POST[$inputs[$i]['campo']]."' WHERE id_pro='".$id_pro."'");
        }
        */
        $info['op'] = 1;
        $info['mensaje'] = "Producto modificado exitosamente";
        $info['reload'] = 1;
        $info['page'] = "apps/crear_productos.php?id=".$id_cae;
        
        return $info;
        
    }
    private function eliminar_productos(){
                
        $id = explode("/", $_POST['id']);
        $this->con->sql("DELETE FROM cat_pros WHERE id_pro='".$id[1]."' AND id_cae='".$id[0]."'");

        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Producto ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "apps/crear_productos.php?id=".$id[0];

        return $info;
        
    }
    private function crear_preguntas(){

        $id_pre = $_POST['id_pre'];
        $nombre = $_POST['nombre'];
        $mostrar = $_POST['mostrar'];
        $cantidad = $_POST['cantidad'];

        if($id_pre > 0){
            $this->con->sql("UPDATE preguntas SET nombre='".$nombre."', mostrar='".$mostrar."' WHERE id_pre='".$id_pre."'");
            $info['op'] = 1;
            $info['mensaje'] = "Pregunta modificada exitosamente";
        }
        if($id_pre == 0){
            $aux = $this->con->sql("INSERT INTO preguntas (nombre, mostrar, id_cat) VALUES ('".$nombre."', '".$mostrar."', '".$this->id_cat."')");
            $info['op'] = 1;
            $info['mensaje'] = "Pregunta creada exitosamente";
            $id_pre = $aux['insert_id'];
        }
        
        $this->con->sql("DELETE FROM preguntas_valores WHERE id_pre='".$id_pre."'");
        for($i=0; $i<$cantidad; $i++){
            
            $cant = $_POST["cant-".$i];
            $valores = $_POST["valores-".$i];
            $nombre = $_POST["nombre-".$i];
            $valores_json = json_encode(explode(",", $valores));
            if($cant > 0){
                $this->con->sql("INSERT INTO preguntas_valores (cantidad, nombre, valores, id_pre) VALUES ('".$cant."', '".$nombre."', '".$valores_json."', '".$id_pre."')");
            }
            
        }
        
        $info['reload'] = 1;
        $info['page'] = "apps/preguntas.php?id=".$id;
        return $info;
        
    }
    private function eliminar_preguntas(){
                
        $id = explode("/", $_POST['id']);
        $this->con->sql("DELETE FROM preguntas WHERE id_pre='".$id[1]."' AND id_cat='".$id[0]."'");

        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Preguntas ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "apps/preguntas.php?id=".$id[0];

        return $info;
        
    }
    
}
