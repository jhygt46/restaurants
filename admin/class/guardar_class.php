<?php
session_start();

require_once($path."class/core_class.php");

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
        if($_POST['accion'] == "eliminar_pagina"){
            return $this->eliminar_pagina();
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
        if($_POST['accion'] == "configurar_giro"){
            return $this->configurar_giro();
        }
        if($_POST['accion'] == "configurar_estilos"){
            return $this->configurar_estilos();
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
        if($_POST['accion'] == "crear_lista_ingredientes"){
            return $this->crear_lista_ingredientes();
        }
        if($_POST['accion'] == "crear_repartidor"){
            return $this->crear_repartidor();
        }
        if($_POST['accion'] == "crear_horario"){
            return $this->crear_horario();
        }
        if($_POST['accion'] == "eliminar_repartidor"){
            return $this->eliminar_repartidor();
        }
        if($_POST['accion'] == "eliminar_horario"){
            return $this->eliminar_horario();
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
    public function copy_image($path, $image, $ext){
        
        $file = $path.$image.'.'.$ext;
        $file_aux = $path.$image.'_2.'.$ext;
        $data = getimagesize($file);
        $destino = imagecreatetruecolor($data[0], $data[1]);

        $info['file'] = $file;
        $info['file_aux'] = $file_aux;
        $info['data'] = $data;

        if($data['mime'] == "image/jpeg"){
            $origen = imagecreatefromjpeg($file);
            imagecopy($destino, $origen, 0, 0, 0, 0, $data[0], $data[1]);
            header('Content-Type: image/jpeg');
            imagejpeg($destino, $file_aux);
            imagedestroy($destino);
        }
        if($data['mime'] == "image/png"){
            $origen = imagecreatefrompng($file);
            imagecopy($destino, $origen, 0, 0, 0, 0, $data[0], $data[1]);
            header('Content-Type: image/png');
            imagepng($destino, $file_aux);
            imagedestroy($destino);
        }

        unlink($file);
        rename($file_aux, $file);
        return $file_aux;

    }
    public function ingresarimagen($filepath, $filename, $i){

        $filename = ($filename !== null) ? $filename : bin2hex(openssl_random_pseudo_bytes(10)) ;
        $file_formats = array("jpg", "png", "ico");

        $name = $_FILES['file_image'.$i]['name']; // filename to get file's extension
        $size = $_FILES['file_image'.$i]['size'];

        if (strlen($name)){
            $extension = substr($name, strrpos($name, '.')+1);
            if (in_array($extension, $file_formats)) { // check it if it's a valid format or not
                if ($size < (2048 * 1024)) { // check it if it's bigger than 2 mb or no
                    $imagename = $filename.".".$extension;
                    $tmp = $_FILES['file_image'.$i]['tmp_name'];
                    if (move_uploaded_file($tmp, $filepath.$imagename)){
                        $this->copy_image($filepath, $filename, $extension);
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
        
        $this->con_cambios();
        $texto = $_POST['texto'];        
        $sql = $this->con->sql("UPDATE giros SET footer_html='".$texto."' WHERE id_gir='".$this->id_gir."'");
        
        if($sql['estado']){
            $info['op'] = 1;
            $info['mensaje'] = "Footer modificado exitosamente";
            $info['reload'] = 1;
            $info['page'] = "apps/configurar_giro.php";
            $this->con_cambios();
        }else{
            $info['op'] = 2;
            $info['mensaje'] = "Se produjo un error: porfavor intente mas tarde";
            $info['reload'] = 1;
            $info['page'] = "apps/configurar_giro.php";
        }

        return $info;
        
    }
    private function con_cambios(){
        $this->con->sql("UPDATE giros SET con_cambios='1' WHERE id_gir='".$this->id_gir."'");
    }
    private function configurar_estilos(){
        
        $font_family = $_POST['font-family'];
        $font_css = $_POST['font-css'];
        $css_page = $_POST['css_page'];
        $css_color = $_POST['css_color'];
        $css_modal = $_POST['css_modal'];
        $this->con_cambios();
        
        $this->con->sql("UPDATE giros SET font_family='".$font_family."', font_css='".$font_css."', style_page='".$css_page."', style_color='".$css_color."', style_modal='".$css_modal."' WHERE id_gir='".$this->id_gir."'");
        
        $info['op'] = 1;
        $info['mensaje'] = "Configuracion de Estilos Modificado Exitosamente";
        $info['reload'] = 1;
        $info['page'] = "msd/ver_giro.php?id_gir=".$this->id_gir;
        return $info;
        
    }
    private function configurar_giro(){
        
        $titulo = $_POST['titulo'];
        
        $pedido_wasabi = $_POST['pedido_wasabi'];
        $pedido_gengibre = $_POST['pedido_gengibre'];
        $pedido_soya = $_POST['pedido_soya'];
        $pedido_teriyaki = $_POST['pedido_teriyaki'];
        $pedido_palitos = $_POST['pedido_palitos'];
        $pedido_comentarios = $_POST['pedido_comentarios'];
        
        $pedido_minimo = $_POST['pedido_minimo'];

        $pedido_01_titulo = $_POST['titulo_01'];
        $pedido_01_subtitulo = $_POST['subtitulo_01'];
        $pedido_02_titulo = $_POST['titulo_02'];
        $pedido_02_subtitulo = $_POST['subtitulo_02'];
        $pedido_03_titulo = $_POST['titulo_03'];
        $pedido_03_subtitulo = $_POST['subtitulo_03'];
        $pedido_04_titulo = $_POST['titulo_04'];
        $pedido_04_subtitulo = $_POST['subtitulo_04'];
        
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
        
        // MODIFICAR PEDIDOS
        $this->con->sql("UPDATE giros SET pedido_minimo='".$pedido_minimo."', titulo='".$titulo."', pedido_comentarios='".$pedido_comentarios."', pedido_palitos='".$pedido_palitos."', pedido_teriyaki='".$pedido_teriyaki."', pedido_soya='".$pedido_soya."', pedido_wasabi='".$pedido_wasabi."', pedido_gengibre='".$pedido_gengibre."' WHERE id_gir='".$this->id_gir."'");
        
        // MODIFICAR TITULO
        $this->con->sql("UPDATE giros SET pedido_01_titulo='".$pedido_01_titulo."', pedido_01_subtitulo='".$pedido_01_subtitulo."', pedido_02_titulo='".$pedido_02_titulo."', pedido_02_subtitulo='".$pedido_02_subtitulo."', pedido_03_titulo='".$pedido_03_titulo."', pedido_03_subtitulo='".$pedido_03_subtitulo."', pedido_04_titulo='".$pedido_04_titulo."', pedido_04_subtitulo='".$pedido_04_subtitulo."' WHERE id_gir='".$this->id_gir."'");
        
        
        $info['op'] = 1;
        $info['mensaje'] = "Configuracion Base Modificado Exitosamente";
        $info['reload'] = 1;
        $info['page'] = "msd/ver_giro.php";
        return $info;
        
    }
    private function configurar_categoria(){
        
        $id_cae = $_POST['id_cae'];
        $parent_id = $_POST['parent_id'];
        $mostar_prods = $_POST['mostrar_prods'];
        $ocultar = $_POST['ocultar'];
        $detalle_prods = $_POST['detalle_prods'];
        $this->con_cambios();

        $image = $this->ingresarimagen('/var/www/html/restaurants/images/categorias/', null, 0);
        if($image['op'] == 1){
            $this->con->sql("UPDATE categorias SET image='".$image['image']."' WHERE id_cae='".$id_cae."'");
        }

        $this->con->sql("UPDATE categorias SET detalle_prods='".$detalle_prods."', ocultar='".$ocultar."', mostrar_prods='".$mostar_prods."' WHERE id_cae='".$id_cae."'");
        $info['op'] = 1;
        $info['mensaje'] = "Configuracion modificado exitosamente";
        
        $info['reload'] = 1;
        $info['page'] = "msd/categorias.php?parent_id=".$parent_id;
        return $info;
        
    }
    private function configurar_producto(){
        
        $id_pro = $_POST['id_pro'];
        $id = $_POST['id'];
        $parent_id = $_POST['parent_id'];
        $list = $this->get_preguntas();
        $this->con_cambios();

        for($i=0; $i<count($list); $i++){
            $pre = $_POST['pregunta-'.$list[$i]['id_pre']];
            if($pre == 0){
                $this->con->sql("DELETE FROM preguntas_productos WHERE id_pro='".$id_pro."' AND id_pre='".$list[$i]['id_pre']."'");
            }
            if($pre == 1){
                $this->con->sql("INSERT INTO preguntas_productos (id_pro, id_pre) VALUES ('".$id_pro."', '".$list[$i]['id_pre']."')");
            }
        }
        
        $list_ing = $this->get_lista_ingredientes();
        for($i=0; $i<count($list_ing); $i++){
            $lin = $_POST['lista_ing-'.$list_ing[$i]['id_lin']];
            if($lin == 0){
                $info['db_0'][] = $this->con->sql("DELETE FROM lista_ingredientes_productos WHERE id_pro='".$id_pro."' AND id_lin='".$list_ing[$i]['id_lin']."'");
            }
            if($lin == 1){
                $info['db_1'][] = $this->con->sql("INSERT INTO lista_ingredientes_productos (id_pro, id_lin) VALUES ('".$id_pro."', '".$list_ing[$i]['id_lin']."')");
            }
        }
        
        $info['op'] = 1;
        $info['mensaje'] = "Configuracion Modificada Exitosamente";
        $info['reload'] = 1;
        $info['page'] = "msd/crear_productos.php?id=".$id."&parent_id=".$parent_id;
        return $info;
        
    }
    private function verificar_dominio($dominio){
        
        $aux = explode(".", $dominio);
        if($aux[0] == "www" && strlen(aux[1]) > 0 && strlen(aux[2]) > 0){
            return true;
        }else{
            return false;
        }
        
    }
    
    private function crear_giro(){
        
        if($this->$admin == 1){
        
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $dominio = $_POST['dominio'];
            $code = bin2hex(openssl_random_pseudo_bytes(10));

            $verificar_dominio = $this->con->sql("SELECT * FROM giros WHERE dominio='".$dominio."'");
            if($this->verificar_dominio($dominio)){
                if($id == 0){
                    if($verificar_dominio['count'] == 0){
                        $sql = $this->con->sql("INSERT INTO giros (nombre, fecha_creado, dominio, catalogo, code, con_cambios, titulo, style_page, style_color, style_modal, font_family, font_css) VALUES ('".$nombre."', now(), '".$dominio."', '1', '".$code."', '1', '".$nombre."', 'css_tipo_01.css', 'css_colores_01.css', 'css_fontsize_01.css', 'K2D', 'K2D')");
                        if($sql['estado']){
                            $id_gir = $sql['insert_id'];
                            $sql2 = $this->con->sql("INSERT INTO catalogo_productos (nombre, fecha_creado, id_gir) VALUES ('Catalogo 01', now(), '".$id_gir."')");
                            if($sql2['estado']){
                                $info['op'] = 1;
                                $info['mensaje'] = "Giro creado exitosamente";
                                if($this->admin == 0){
                                    $this->con->sql("INSERT INTO fw_usuarios_giros (id_user, id_gir) VALUES ('".$this->id_user."', '".$aux['insert_id']."')");
                                }
                                if($this->admin == 1){
                                    $this->con->sql("INSERT INTO fw_usuarios_giros_clientes (id_user, id_gir) VALUES ('".$this->id_user."', '".$aux['insert_id']."')");
                                }
                            }else{
                                $info['op'] = 2;
                                $info['mensaje'] = "Se produjo un error: intente mas tarde";
                            }
                        }else{
                            $info['op'] = 2;
                            $info['mensaje'] = "Se produjo un error: intente mas tarde";
                        }
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "Error: Dominio ya Existe";
                    }
                }
                if($id > 0){
                    if($verificar_dominio['count'] == 0 || ($verificar_dominio['count'] == 1 && $verificar_dominio['resultado'][0]['id_gir'] == $id)){
                        $sql = $this->con->sql("UPDATE giros SET nombre='".$nombre."', dominio='".$dominio."' WHERE id_gir='".$id."'");
                        if($sql['estado']){
                            $info['op'] = 1;
                            $info['mensaje'] = "Giro modificado exitosamente";
                        }else{
                            $info['op'] = 2;
                            $info['mensaje'] = "Se produjo un error: intente mas tarde";
                        }
                    }
                }

            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error: Dominio Invalido";
            }

            $info['reload'] = 1;
            $info['page'] = "msd/giros.php";
            return $info;
        
        }
        
    }
    private function eliminar_giro(){
        
        $sql = $this->con->sql("UPDATE giros SET eliminado='1' WHERE id_gir='".$this->id_gir."'");
        if($sql['estado']){
            $info['tipo'] = "success";
            $info['titulo'] = "Eliminado";
            $info['texto'] = "Giro ".$_POST["nombre"]." Eliminado";
            $info['reload'] = 1;
            $info['page'] = "base/giros.php";
        }else{
            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Giro ".$_POST["nombre"]." no pudo ser eliminado";
        }
        return $info;
        
    }
    private function eliminar_repartidor(){

        $id = explode("/", $_POST['id']);
        $info['db'] = $this->con->sql("DELETE FROM motos_locales WHERE id_loc='".$id[0]."' AND id_mot='".$id[1]."'");
        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Repartidor ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "msd/crear_repartidor.php?id_mot=".$id[1]."&id_loc=".$id[0]."&nombre=".$id[2];
        return $info;

    }
    private function eliminar_tramos(){
        
        $id = explode("/", $_POST['id']);
        $this->con->sql("UPDATE locales_tramos SET eliminado='1' WHERE id_lot='".$id[1]."'");
        
        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Giro ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "msd/zonas_locales.php?id_loc=".$id[0];

        return $info;
        
    }
    private function crear_pagina(){
        
        $id_pag = $_POST['id'];
        $nombre = $_POST['nombre'];
        $html = $_POST['html'];
        $tipo = $_POST['pagina'];
        $this->con_cambios();

        $image = $this->ingresarimagen('/var/www/html/restaurants/images/paginas/', null, 0);

        if($id_pag == 0){
            $aux_page = $this->con->sql("INSERT INTO paginas (nombre, html, tipo, id_gir) VALUES ('".$nombre."', '".$html."', '".$tipo."', '".$this->id_gir."')");
            $info['op'] = 1;
            $info['mensaje'] = "Paginas creado exitosamente";
            if($image['op'] == 1){
                $this->con->sql("UPDATE paginas SET imagen='".$image['image']."' WHERE id_pag='".$aux_page["insert_id"]."' AND id_gir='".$this->id_gir."'");
            }
        }
        if($id_pag > 0){
            $this->con->sql("UPDATE paginas SET nombre='".$nombre."', html='".$html."', tipo='".$tipo."' WHERE id_pag='".$id_pag."' AND id_gir='".$this->id_gir."'");
            $info['op'] = 1;
            $info['mensaje'] = "Paginas modificado exitosamente";
            if($image['op'] == 1){
                $this->con->sql("UPDATE paginas SET imagen='".$image['image']."' WHERE id_pag='".$id_pag."' AND id_gir='".$this->id_gir."'");
            }
        }
        
        $info['reload'] = 1;
        $info['page'] = "msd/ver_giro.php";
        return $info;
        
    }
    private function crear_horario(){

        $id_hor = $_POST['id'];
        $id_loc = $_POST['id_loc'];
        $loc_nombre = $_POST['loc_nombre'];

        $dia_ini = $_POST['dia_ini'];
        $dia_fin = $_POST['dia_fin'];

        $hora_ini = $_POST['hora_ini'];
        $min_ini = $_POST['min_ini'];

        $hora_fin = $_POST['hora_fin'];
        $min_fin = $_POST['min_fin'];

        $tipo = $_POST['tipo'];

        if($id_hor == 0){
            $info['db'] = $this->con->sql("INSERT INTO horarios (dia_ini, dia_fin, hora_ini, hora_fin, min_ini, min_fin, tipo, id_loc, id_gir) VALUES ('".$dia_ini."', '".$dia_fin."', '".$hora_ini."', '".$hora_fin."', '".$min_ini."', '".$min_fin."', '".$tipo."', '".$id_loc."', '".$this->id_gir."')");
            $info['op'] = 1;
            $info['mensaje'] = "Horario creado exitosamente";
        }
        if($id_hor > 0){
            $info['db'] = $this->con->sql("UPDATE horarios SET tipo='".$tipo."', dia_ini='".$dia_ini."', dia_fin='".$dia_fin."', hora_ini='".$hora_ini."', hora_fin='".$hora_fin."', min_ini='".$min_ini."', min_fin='".$min_fin."' WHERE id_hor='".$id_hor."'");
            $info['op'] = 1;
            $info['mensaje'] = "Horario modificado exitosamente";
        }
        
        $info['reload'] = 1;
        $info['page'] = "msd/crear_horario.php?id_loc=".$id_loc."&nombre=".$loc_nombre;
        return $info;

    }
    private function crear_repartidor(){

        $tipo = $_POST['tipo'];
        $id_loc = $_POST['id_loc'];
        $loc_nombre = $_POST['loc_nombre'];

        if($tipo == 0){
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $uid = bin2hex(openssl_random_pseudo_bytes(10));
            $aux_mot = $this->con->sql("INSERT INTO motos (nombre, correo, uid, id_gir, eliminado) VALUES ('".$nombre."', '".$correo."', '".$uid."', '".$this->id_gir."', '0')");
            $id_mot = $aux_mot['insert_id'];
            $info['mensaje'] = "Repartidor asociado exitosamente";
        }
        if($tipo == 1){
            $id_mot = $_POST['repartidor'];
            $info['mensaje'] = "Repartidor asociado exitosamente";
        }

        $info['db'] = $this->con->sql("INSERT INTO motos_locales (id_mot, id_loc) VALUES ('".$id_mot."', '".$id_loc."')");

        $info['op'] = 1;
        $info['reload'] = 1;
        $info['page'] = "msd/crear_repartidor.php?id_loc=".$id_loc."&nombre=".$loc_nombre;
        return $info;

    }
    private function crear_catalogo(){
        
        $id_cat = $_POST['id'];
        $nombre = $_POST['nombre'];
        $this->con_cambios();

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
        
        $id_loc = $_POST['id'];
        $id_cat = $_POST['id_cat'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $direccion = $_POST['direccion'];
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $code = bin2hex(openssl_random_pseudo_bytes(10));
        $this->con_cambios();

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
        
        $aux = $this->con->sql("SELECT id_loc, lat, lng, nombre, direccion FROM locales WHERE id_gir='".$this->id_gir."'");
        $this->con->sql("UPDATE giros SET retiro_local='1', lista_locales='".json_encode($aux['resultado'])."' WHERE id_gir='".$this->id_gir."'");
        
        $info['reload'] = 1;
        $info['page'] = "msd/locales.php";
        return $info;
        
    }
    private function crear_locales_tramos(){
        
        $id_lot = $_POST['id_lot'];
        $id_loc = $_POST['id_loc'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $pol = $_POST['posiciones'];
        
        if($id_lot == 0){
            $this->con->sql("INSERT INTO locales_tramos (nombre, precio, poligono, id_loc, eliminado) VALUES ('".$nombre."', '".$precio."', '".$pol."', '".$id_loc."', '0')");
            $info['op'] = 1;
            $info['mensaje'] = "Tramo creado exitosamente";
        }
        if($id_lot > 0){
            $this->con->sql("UPDATE locales_tramos SET nombre='".$nombre."', precio='".$precio."', poligono='".$pol."' WHERE id_lot='".$id_lot."'");
            $info['op'] = 1;
            $info['mensaje'] = "Tramo modificado exitosamente";
        }
        
        $aux = $this->con->sql("SELECT MIN(t3.precio) as min FROM giros t1, locales t2, locales_tramos t3 WHERE t1.id_gir='".$this->id_gir."' AND t1.id_gir=t2.id_gir AND t2.id_loc=t3.id_loc AND t3.eliminado='0' AND t2.eliminado='0'");
        $min = $aux['resultado'][0]['min'];
        
        if($min !== null){
            $this->con->sql("UPDATE giros SET despacho_domicilio='1', desde='".$min."' WHERE id_gir='".$this->id_gir."'");
        }else{
            $this->con->sql("UPDATE giros SET despacho_domicilio='0' WHERE id_gir='".$this->id_gir."'");
        }
        
        $info['reload'] = 1;
        $info['page'] = "msd/zonas_locales.php?id_loc=".$id_loc;
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
        
        $list_loc = $this->get_locales();
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $tipo = $_POST['tipo'];
        $giro = $_POST['giro'];

        if($id == 0){
            $is_correo = $this->con->sql("SELECT * FROM fw_usuarios WHERE correo='".$correo." AND eliminado='0'");
            if($is_correo['count'] == 0){
                $user = $this->con->sql("INSERT INTO fw_usuarios (nombre, fecha_creado, correo) VALUES ('".$nombre."', now(), '".$correo."')");
                $id = $user['insert_id'];
            }
        }
        if($tipo == 1){
            $this->con->sql("INSERT INTO fw_usuarios_giros (id_user, id_gir) VALUES ('".$id."', '".$this->id_gir."')");
            $this->con->sql("DELETE fw_usuarios_locales WHERE id_user='".$id."'");
            $this->con->sql("UPDATE fw_usuarios SET tipo='1' WHERE id_user='".$id."'");
        }
        if($tipo == 2){
            $id_gir = $this->id_gir;
            if($this->id_user == 1){
                $id_gir = $giro;
            }
            $this->con->sql("DELETE fw_usuarios_giros WHERE id_user='".$id."' AND id_gir='".$id_gir."'");
            $this->con->sql("DELETE fw_usuarios_locales WHERE id_user='".$id."'");
            $this->con->sql("UPDATE fw_usuarios SET tipo='2' WHERE id_user='".$id."'");
            foreach($list_loc as $value){
                $loc = $_POST['local-'.$value['id_gir']];
                if(isset($loc) && $loc >0){
                    $this->con->sql("INSERT INTO fw_usuarios_locales (id_user, id_loc) VALUES ('".$id."', '".$loc."')");
                }
            }
        }
        if($tipo == 3 && $this->admin == 1 && $this->id_user == 1){
            $this->con->sql("UPDATE fw_usuarios SET admin='1' WHERE id_user='".$id."'");
        }
        $info['op'] = 1;
        $info['mensaje'] = "Usuarios modificado exitosamente";
        $info['reload'] = 1;
        $info['page'] = "msd/usuarios.php";
        return $info;
        
    }
    private function eliminar_horario(){

        $id = explode("/", $_POST['id']);
        $this->con->sql("UPDATE horarios SET eliminado='1' WHERE id_hor='".$id[0]."'");
        
        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Horario ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "msd/crear_horario.php?id_loc=".$id[1]."&nombre=".$id[2];

        return $info;

    }
    private function eliminar_usuario(){
                
        $id = $_POST['id'];
        $this->con->sql("UPDATE fw_usuarios SET eliminado='1' WHERE id_user='".$id."'");
        
        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Usuario ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "msd/usuarios.php";

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

        $id_cae = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $descripcion_sub = $_POST['descripcion_sub'];
        $precio = $_POST['precio'];
        $parent_id = $_POST['parent_id'];
        $tipo = $_POST['tipo'];
        $this->con_cambios();

        if($id_cae == 0){
            $this->con->sql("INSERT INTO categorias (nombre, parent_id, tipo, id_cat, descripcion, descripcion_sub, precio) VALUES ('".$nombre."', '".$parent_id."', '".$tipo."', '".$this->id_cat."', '".$descripcion."', '".$descripcion_sub."', '".$precio."')");
            $info['op'] = 1;
            $info['mensaje'] = "Categoria creada exitosamente";
        }
        if($id_cae > 0){
            $this->con->sql("UPDATE categorias SET nombre='".$nombre."', tipo='".$tipo."', descripcion='".$descripcion."', descripcion_sub='".$descripcion_sub."', precio='".$precio."' WHERE id_cae='".$id_cae."' AND id_cat='".$this->id_cat."'");
            $info['op'] = 1;
            $info['mensaje'] = "Categoria modificada exitosamente";
        }
                
        $info['reload'] = 1;
        $info['page'] = "msd/categorias.php?parent_id=".$parent_id;
        return $info;
        
    }
    private function crear_ingredientes(){

        $id = $_POST['id'];
        $id_ing = $_POST['id_ing'];
        $nombre = $_POST['nombre'];
        $parent_id = $_POST['parent_id'];
        $this->con_cambios();

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
        $info['page'] = "msd/ingredientes.php?id=".$id."&parent_id=".$parent_id;
        return $info;
        
    }
    private function eliminar_categoria(){
                
        $id = explode("/", $_POST['id']);
        $this->con->sql("UPDATE categorias SET eliminado='1' WHERE id_cae='".$id[1]."'");
        $this->con_cambios();

        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Categoria ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "msd/categorias.php?id=".$id[0]."&parent_id=".$id[2];

        return $info;
        
    }
    private function eliminar_ingredientes(){
                
        $id = explode("/", $_POST['id']);
        $this->con->sql("UPDATE ingredientes SET eliminado='1' WHERE id_ing='".$id[1]."'");
        $this->con_cambios();

        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Ingredientes ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "msd/ingredientes.php?id=".$id[0]."&parent_id=".$id[2];

        return $info;
        
    }
    /*
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
    */
    private function eliminar_pagina(){
                
        $id = $_POST['id'];
        $this->con->sql("UPDATE paginas SET eliminado='1' WHERE id_pag='".$id."'");
        $this->con_cambios();
        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Pagina ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "msd/ver_giro.php?id_gir=".$this->id_gir;

        return $info;
        
    }
    
    public function asignar_prods_promocion(){
        
        $id_cae = $_POST['id_cae'];
        $parent_id = $_POST['parent_id'];
        $precio = $_POST['precio'];
        $values = $this->list_arbol_cats_prods();
        
        $this->con->sql("UPDATE categorias SET precio='".$precio."' WHERE id_cae='".$id_cae."'");
        $this->con->sql("DELETE FROM promocion_categoria WHERE id_cae1='".$id_cae."'");
        $this->con->sql("DELETE FROM promocion_productos WHERE id_cae='".$id_cae."'");
        
        for($i=0; $i<count($values); $i++){

            $value = $values[$i];
            if($value['id_cae'] !== null){
                $cae_val = $_POST['sel-cae-'.$value['id_cae']];
                if($cae_val > 0){
                    $this->con->sql("INSERT INTO promocion_categoria (id_cae1, id_cae2, cantidad) VALUES ('".$id_cae."', '".$value['id_cae']."', '".$cae_val."')");
                }
            }
            if($value['id_pro'] !== null){
                $pro_val = $_POST['sel-pro-'.$value['id_pro']];
                if($pro_val > 0){
                    $this->con->sql("INSERT INTO promocion_productos (id_cae, id_pro, cantidad) VALUES ('".$id_cae."', '".$value['id_pro']."', '".$pro_val."')");
                }
            }
            
        }      
        $info['reload'] = 1;
        $info['page'] = "msd/categorias.php?parent_id=".$parent_id;
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
        $this->con_cambios();
        
        if($tipo == 0){
            if($id_pro == 0){
                $pro = $this->con->sql("INSERT INTO productos (numero, nombre, descripcion, fecha_creado, id_gir, eliminado) VALUES ('".$numero."', '".$nombre."', '".$descripcion."', now(), '".$this->id_gir."', '0')");
                $this->con->sql("INSERT INTO cat_pros (id_cae, id_pro) VALUES ('".$id_cae."', '".$pro['insert_id']."')");
                $this->con->sql("INSERT INTO productos_precio (id_cat, id_pro, precio) VALUES ('".$this->id_cat."', '".$pro['insert_id']."', '".$precio."')");    
            }
            if($id_pro > 0){
                $this->con->sql("UPDATE productos SET numero='".$numero."', nombre='".$nombre."', descripcion='".$descripcion."' WHERE id_pro='".$id_pro."'");
                $this->con->sql("UPDATE productos_precio SET precio='".$precio."' WHERE id_cat='".$this->id_cat."' AND id_pro='".$id_pro."'");
            }
        }
        if($tipo == 1){
            $all_prods = $this->get_productos();
            for($i=0; $i<count($all_prods); $i++){
                $pro = $_POST['prod-'.$all_prods[$i]['id_pro']];
                if($pro == 1){
                    $this->con->sql("INSERT INTO cat_pros (id_cae, id_pro) VALUES ('".$id_cae."', '".$all_prods[$i]['id_pro']."')");
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
        $info['page'] = "msd/categorias.php?id=".$id_cae;
        
        return $info;
        
    }
    private function eliminar_productos(){
                
        $id = explode("/", $_POST['id']);
        $this->con->sql("DELETE FROM cat_pros WHERE id_pro='".$id[1]."' AND id_cae='".$id[0]."'");
        $this->con_cambios();

        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Producto ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "msd/crear_productos.php?id=".$id[0];

        return $info;
        
    }
    private function crear_preguntas(){

        $id_pre = $_POST['id'];
        $nombre = $_POST['nombre'];
        $mostrar = $_POST['mostrar'];
        $cantidad = $_POST['cantidad'];
        $this->con_cambios();

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
        $info['page'] = "msd/ver_giro.php?id_gir=".$this->id_gir;
        return $info;
        
    }
    private function crear_lista_ingredientes(){
        
        $id_lin = $_POST['id'];
        $nombre = $_POST['nombre'];
        $this->con_cambios();

        if($id_lin > 0){
            $this->con->sql("UPDATE lista_ingredientes SET nombre='".$nombre."' WHERE id_lin='".$id_lin."'");
            $info['op'] = 1;
            $info['mensaje'] = "Lista de Ingredientes modificada exitosamente";
        }
        if($id_lin == 0){
            $aux = $this->con->sql("INSERT INTO lista_ingredientes (nombre, id_cat, id_gir) VALUES ('".$nombre."', '".$this->id_cat."', '".$this->id_gir."')");
            $info['op'] = 1;
            $info['mensaje'] = "Lista de Ingredientes creada exitosamente";
            $id_lin = $aux['insert_id'];
        }
        
        $ingredientes = $this->get_ingredientes_base();
        for($i=0; $i<count($ingredientes); $i++){
            $id_ing = $ingredientes[$i]['id_ing'];
            $valor = $_POST["ing-".$id_ing];
            if($valor == ""){
                $this->con->sql("DELETE FROM lista_precio_ingrediente WHERE id_ing='".$id_ing."' AND id_lin='".$id_lin."'");
            }
            if($valor >= 0 && $valor != ""){
                $this->con->sql("DELETE FROM lista_precio_ingrediente WHERE id_ing='".$id_ing."' AND id_lin='".$id_lin."'");
                $this->con->sql("INSERT INTO lista_precio_ingrediente (id_ing, id_lin, valor) VALUES ('".$id_ing."', '".$id_lin."', '".$valor."')");
            }
        }
        
        $info['reload'] = 1;
        $info['page'] = "msd/ver_giro.php?id_gir=".$this->id_gir;
        return $info;
        
    }
    private function eliminar_preguntas(){
                
        $id = explode("/", $_POST['id']);
        $this->con->sql("DELETE FROM preguntas WHERE id_pre='".$id[1]."' AND id_cat='".$id[0]."'");
        $this->con_cambios();
        
        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Preguntas ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "msd/preguntas.php?id=".$id[0];

        return $info;
        
    }
    
}
