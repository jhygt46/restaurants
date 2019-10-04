<?php
session_start();

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."restaurants/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."restaurants/");
}

require_once DIR."db.php";
require_once DIR_BASE."config/config.php";

class Guardar{
    
    public $con = null;
    public $id_user = null;
    public $admin = null;
    public $id_gir = null;
    public $id_cat = null;
    public $re_venta = null;
    public $id_aux_user = null;
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
        $this->id_cat = (isset($_SESSION['user']['id_cat'])) ? $_SESSION['user']['id_cat'] : 0 ;
        $this->id_gir = (isset($_SESSION['user']['id_gir'])) ? $_SESSION['user']['id_gir'] : 0 ;

    }
    public function process(){
        
        if($this->id_user > 0){

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
            if($_POST['accion'] == "configurar_local"){
                return $this->configurar_local();
            }
            if($_POST['accion'] == "configurar_usuario_local"){
                return $this->configurar_usuario_local();
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
            if($_POST['accion'] == "crear_usuario_admin"){
                return $this->crear_usuario_admin();
            }
            if($_POST['accion'] == "crear_usuarios_local"){
                return $this->crear_usuarios_local();
            }
            if($_POST['accion'] == "eliminar_usuario"){
                return $this->eliminar_usuario();
            }
            if($_POST['accion'] == "eliminar_usuario_admin"){
                return $this->eliminar_usuario_admin();
            }
            if($_POST['accion'] == "eliminar_usuario_local"){
                return $this->eliminar_usuario_local();
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
            if($_POST['accion'] == "configurar_inicio"){
                return $this->configurar_inicio();
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
            if($_POST['accion'] == "orderprods"){
                return $this->orderprods();
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
            if($_POST['accion'] == "solicitar_ssl"){
                return $this->solicitar_ssl();
            }
            if($_POST['accion'] == "add_ses"){
                return $this->add_ses();
            }
            if($_POST['accion'] == "add_dns"){
                return $this->add_dns();
            }
            if($_POST['accion'] == "add_dns"){
                return $this->add_ssl();
            }

        }

    }
    private function registrar($id_des, $id_loc, $id_gir, $txt){

        $sqlipd = $this->con->prepare("INSERT INTO seguimiento (id_des, id_user, id_loc, id_gir, fecha, txt) VALUES (?, ?, ?, ?, now(), ?)");
        $sqlipd->bind_param("iiiis", $id_des, $this->id_user, $id_loc, $id_gir, $txt);
        $sqlipd->execute();
        $sqlipd->close();

    }
    private function add_ses(){

        $info['tipo'] = "error";
        $info['titulo'] = "ERROR";
        $info['texto'] = "Correo no pudo ser agregado";
        
        if($this->id_user == 1){
            $id_loc = $_POST['id'];
            if($sql = $this->con->prepare("SELECT correo FROM locales WHERE id_loc=? AND eliminado=?")){
                if($sql->bind_param("ii", $id_loc, $this->eliminado)){
                    if($sql->execute()){
                        $correo = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]["correo"];
                        if($sqlloc = $this->con->prepare("UPDATE locales SET correo_ses='1' WHERE id_loc=?")){
                            if($sqlloc->bind_param("i", $id_loc)){
                                if($sqlloc->execute()){
                                    if($sqlsma = $this->con->prepare("INSERT INTO ses_mail (correo) VALUES (?)")){
                                        if($sqlsma->bind_param("s", $correo)){
                                            if($sqlsma->execute()){
                                                $info['tipo'] = "success";
                                                $info['titulo'] = "Modificado";
                                                $info['texto'] = "Correo ".$correo." agregado";
                                                $info['reload'] = 1;
                                                $info['page'] = "msd/panel.php";
                                                $sqlsma->close();
                                            }else{ $this->registrar(6, 0, 0, 'insert correo ses_mail1 '.htmlspecialchars($sqlsma->error)); }
                                        }else{ $this->registrar(6, 0, 0, 'insert correo ses_mail2 '.htmlspecialchars($sqlsma->error)); }
                                    }else{ $this->registrar(6, 0, 0, 'insert correo ses_mail3 '.htmlspecialchars($this->con->error)); }
                                    $sqlloc->close();
                                }else{ $this->registrar(6, 0, 0, 'update correo_ses locales1 '.htmlspecialchars($sqlloc->error)); }
                            }else{ $this->registrar(6, 0, 0, 'update correo_ses locales2 '.htmlspecialchars($sqlloc->error)); }
                        }else{ $this->registrar(6, 0, 0, 'update correo_ses locales3 '.htmlspecialchars($this->con->error)); }
                        $sql->free_result();
                        $sql->close();
                    }else{ $this->registrar(6, 0, 0, 'correo locales1 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, 0, 'correo locales2 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'correo locales3 '.htmlspecialchars($this->con->error)); }
        }else{  $this->registrar(1, 0, 0, 'add ses'); }
        return $info;

    }
    private function add_dns(){

        $info['tipo'] = "error";
        $info['titulo'] = "ERROR";
        $info['texto'] = "DNS no ha sido configurada";
        if($this->id_user == 1){
            $id_gir = $_POST['id'];
            if($sql = $this->con->prepare("UPDATE giros SET dns='1' WHERE id_gir=?")){
                if($sql->bind_param("i", $id_gir)){
                    if($sql->execute()){
                        $info['tipo'] = "success";
                        $info['titulo'] = "DNS";
                        $info['texto'] = "DNS configurada";
                        $info['reload'] = 1;
                        $info['page'] = "msd/panel.php";
                        $sql->close();
                    }else{ $this->registrar(6, 0, 0, 'Error Sql1: (ADD DNS) '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, 0, 'Error Sql2: (ADD DNS) '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'Error Sql3: (ADD DNS) '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(1, 0, 0, '(ADD DNS)'); }
        return $info;

    }
    private function add_ssl(){

        $info['tipo'] = "error";
        $info['titulo'] = "SSL";
        $info['texto'] = "ssl no ha sido configurada";
        if($this->id_user == 1){
            $id_gir = $_POST['id'];
            if($sql = $this->con->prepare("UPDATE giros SET ssl='1' WHERE id_gir=?")){
                if($sql->bind_param("i", $id_gir)){
                    if($sql->execute()){
                        $info['tipo'] = "success";
                        $info['titulo'] = "SSL";
                        $info['texto'] = "ssl configurada";
                        $info['reload'] = 1;
                        $info['page'] = "msd/panel.php";
                        $sql->close();
                    }else{ $this->registrar(6, 0, 0, 'Error Sql1: (ADD SSL) '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, 0, 'Error Sql2: (ADD SSL) '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'Error Sql3: (ADD SSL) '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(1, 0, 0, '(ADD SSL) '); }
        return $info;
        
    }
    private function ordercat(){

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            if(isset($this->id_cat) && is_numeric($this->id_cat) && $this->id_cat > 0){
                $this->con_cambios(null);
                $values = $_POST['values'];
                for($i=0; $i<count($values); $i++){
                    if($sql = $this->con->prepare("UPDATE categorias SET orders='".$i."' WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?")){
                        if($sql->bind_param("iiii", $values[$i], $this->id_cat, $this->id_gir, $this->eliminado)){
                            if($sql->execute()){
                                $sql->close();
                            }else{ $this->registrar(6, 0, 0, 'Error Sql1: (ORDER CAT) '.$sql->error); }
                        }else{ $this->registrar(6, 0, 0, 'Error Sql2: (ORDER CAT) '.$sql->error); }
                    }else{ $this->registrar(6, 0, 0, 'Error Sql3: (ORDERCAT) '.$this->con->error); }
                }
            }else{ $this->registrar(3, 0, 0, '(ORDER CAT)'); }
        }else{ $this->registrar(2, 0, 0, '(ORDER CAT)'); }
        
    }
    private function orderprods(){

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            if(isset($this->id_cat) && is_numeric($this->id_cat) && $this->id_cat > 0){
                $id_cae = $_POST['id_cae'];
                $sql = $this->con->prepare("SELECT * FROM categorias WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?");
                $sql->bind_param("iii", $id_cae, $this->id_cat, $this->id_gir, $this->eliminado);
                $sql->execute();
                $res = $sql->get_result();
                if($res->{"num_rows"} == 1){
                    $this->con_cambios(null);
                    $values = $_POST['values'];
                    for($i=0; $i<count($values); $i++){
                        if($sqlcp = $this->con->prepare("UPDATE cat_pros SET orders='".$i."' WHERE id_pro=? AND id_cae=?")){
                            if($sqlcp->bind_param("ii", $values[$i], $id_cae)){
                                if($sqlcp->execute()){
                                    $sqlcp->close();
                                }else{ $this->registrar(6, 0, 0, 'Error Sql1: (ORDER PROD) '.$sqlcp->error); }
                            }else{ $this->registrar(6, 0, 0, 'Error Sql2: (ORDER PROD) '.$sqlcp->error); }
                        }else{ $this->registrar(6, 0, 0, 'Error Sql3: (ORDER PROD) '.$this->con->error); }
                    }
                }
                if($res->{"num_rows"} == 0){ $this->registrar(7, 0, $this->id_gir, '(XSS) id_cae: ('.$id_cae.')'); }
            }else{ $this->registrar(3, 0, 0, '(ORDER PROD)'); }
        }else{ $this->registrar(2, 0, 0, '(ORDER PROD)'); }

    }
    public function uploadfavIcon($filename){

        $filepath = '/var/www/html/restaurants/images/favicon/';
        $filename = ($filename !== null) ? $filename : bin2hex(openssl_random_pseudo_bytes(10)) ;
        $file_formats = array("ico", "ICO");
        $name = $_FILES['file_image1']['name']; // filename to get file's extension
        $size = $_FILES['file_image1']['size'];
        if(strlen($name)){
            $extension = substr($name, strrpos($name, '.')+1);
            if(in_array($extension, $file_formats)){ // check it if it's a valid format or not
                if ($size < (20 * 1024)) { // check it if it's bigger than 2 mb or no
                    $imagename = $filename.".".$extension;
                    $imagename_new = $filename."x.".$extension;
                    $tmp = $_FILES['file_image1']['tmp_name'];
                    if(move_uploaded_file($tmp, $filepath.$imagename_new)){
                        $data = getimagesize($filepath.$imagename_new);
                        if($data['mime'] == 'image/vnd.microsoft.icon'){
                            $info['op'] = 1;
                            $info['mensaje'] = "favicon subido";
                            @unlink($filepath.$imagename);
                            rename($filepath.$imagename_new, $filepath.$imagename);
                        }else{
                            $info['op'] = 2;
                            $info['mensaje'] = "Formato invalido! solo archivos con extension .ico";
                            @unlink($filepath.$imagename_new);
                            $this->registrar(8, 0, $this->id_gir, 'Favicon sin Formato image/vnd.microsoft.icon');
                        }
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "No se pudo subir el favicon";
                        $this->registrar(8, 0, $this->id_gir, 'Favicon no UPLOAD');
                    }
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "El favicon sobrepasa los 20KB establecidos";
                    $this->registrar(8, 0, $this->id_gir, 'Favicon Size Limit');
                }
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Formato Invalido";
                $this->registrar(8, 0, $this->id_gir, 'Favicon no Extension');
            }
        }else{
            $info['op'] = 2;
            $info['mensaje'] =  "No ha seleccionado una imagen";
        }
        return $info;

    }
    public function uploadLogo($filename){

        $filepath = '/var/www/html/restaurants/images/logos/';
        $filename = ($filename !== null) ? $filename : bin2hex(openssl_random_pseudo_bytes(10)) ;
        $file_formats = array("png", "PNG", "jpg", "JPG", "jpeg", "JPEG");
        $name = $_FILES['file_image0']['name']; // filename to get file's extension
        $size = $_FILES['file_image0']['size'];
        if(strlen($name)){
            $extension = substr($name, strrpos($name, '.')+1);
            if(in_array($extension, $file_formats)) { // check it if it's a valid format or not
                if ($size < (20 * 1024)) { // check it if it's bigger than 2 mb or no
                    $imagename = $filename.".".$extension;
                    $imagename_new = $filename."x.".$extension;
                    $tmp = $_FILES['file_image0']['tmp_name'];
                    if(move_uploaded_file($tmp, $filepath.$imagename_new)){
                        $data = getimagesize($filepath.$imagename_new);
                        if($data[0] == 260 && $data[1] == 100){
                            if($data['mime'] == 'image/png' || $data['mime'] == 'image/jpeg'){
                                $info['op'] = 1;
                                $info['mensaje'] = "Logo subido";
                                @unlink($filepath.$imagename);
                                rename($filepath.$imagename_new, $filepath.$imagename);
                            }else{
                                $info['op'] = 2;
                                $info['mensaje'] = "Formato invalido! solo archivos con extension .png .jpg .jpeg";
                                @unlink($filepath.$imagename_new);
                                $this->registrar(8, 0, $this->id_gir, 'Logo sin Formato image/png o image/jpeg');
                            }
                        }else{
                            unlink($filepath.$imagename_new);
                            $info['op'] = 2;
                            $info['mensaje'] = "El Logo debe ser de 260px/100px";
                            $this->registrar(8, 0, $this->id_gir, 'Logo No 260px/100px');
                        }
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "No se pudo subir la imagen";
                        $this->registrar(8, 0, $this->id_gir, 'Logo No Upload');
                    }
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Imagen sobrepasa los 20KB establecidos";
                    $this->registrar(8, 0, $this->id_gir, 'Logo size limit');
                }
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Formato Invalido";
                $this->registrar(8, 0, $this->id_gir, 'Logo no Extension');
            }
        }else{
            $info['op'] = 2;
            $info['mensaje'] =  "No ha seleccionado una imagen";
        }
        return $info;

    }
    public function uploadsubCategoria($filepath, $filename){

        $filename = ($filename !== null) ? $filename : bin2hex(openssl_random_pseudo_bytes(10)) ;
        $file_formats = array("jpg", "jpeg", "JPG", "JPEG");
        $name = $_FILES['file_image0']['name'];
        $size = $_FILES['file_image0']['size'];
        if (strlen($name)){
            $extension = substr($name, strrpos($name, '.') + 1);
            if (in_array($extension, $file_formats)){
                if ($size < (25 * 1024)){
                    $imagename = $filename.".".$extension;
                    $imagename_new = $filename."x.".$extension;
                    $tmp = $_FILES['file_image0']['tmp_name'];
                    if(move_uploaded_file($tmp, $filepath.$imagename)){
                            $data = getimagesize($filepath.$imagename);
                            if($data['mime'] == "image/jpeg"){

                                $destino = imagecreatetruecolor($data[0], $data[1]);
                                $origen = imagecreatefromjpeg($filepath.$imagename);
                                imagecopy($destino, $origen, 0, 0, 0, 0, $data[0], $data[1]);
                                imagejpeg($destino, $filepath.$imagename_new);
                                imagedestroy($destino);
                                $info['op'] = 1;
                                $info['mensaje'] = "Imagen subida";
                                $info['image'] = $imagename_new;

                            }else{
                                $info['op'] = 2;
                                $info['mensaje'] = "Formato invalido! solo archivos con extension .jpg .jpeg";
                                $this->registrar(8, 0, $this->id_gir, 'SubCat sin Formato image/jpeg');
                            }
                            @unlink($filepath.$imagename);

                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "No se pudo subir la imagen";
                        $this->registrar(8, 0, $this->id_gir, 'SubCat No Upload');
                    }
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Imagen sobrepasa los 25KB establecidos";
                    $this->registrar(8, 0, $this->id_gir, 'SubCat size limit');
                }
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Formato Invalido";
                $this->registrar(8, 0, $this->id_gir, 'SubCat no Extension');
            }
        }else{
            $info['op'] = 2;
            $info['mensaje'] =  "No ha seleccionado una imagen";
        }
        return $info;

    }
    public function uploadCategoria($filepath, $filename, $alto){

        $width = 500;
        $filename = ($filename !== null) ? $filename : bin2hex(openssl_random_pseudo_bytes(10)) ;
        $file_formats = array("jpg", "jpeg", "JPG", "JPEG");
        $name = $_FILES['file_image0']['name'];
        $size = $_FILES['file_image0']['size'];
        if (strlen($name)){
            $extension = substr($name, strrpos($name, '.') + 1);
            if (in_array($extension, $file_formats)){
                if ($size < (25 * 1024)){
                    $imagename = $filename.".".$extension;
                    $imagename_new = $filename."x.".$extension;
                    $tmp = $_FILES['file_image0']['tmp_name'];
                    if(move_uploaded_file($tmp, $filepath.$imagename)){
                            $data = getimagesize($filepath.$imagename);
                            if($data['mime'] == "image/jpeg"){
                                $height = $width * $alto / 100;
                                $destino = imagecreatetruecolor($width, $height);
                                $origen = imagecreatefromjpeg($filepath.$imagename);
                                imagecopy($destino, $origen, 0, 0, 0, 0, $width, $height);
                                imagejpeg($destino, $filepath.$imagename_new);
                                imagedestroy($destino);
                                $info['op'] = 1;
                                $info['mensaje'] = "Imagen subida";
                                $info['image'] = $imagename_new;
                            }else{
                                $info['op'] = 2;
                                $info['mensaje'] = "La imagen no es jpg / jpeg";
                                $this->registrar(8, 0, $this->id_gir, 'SubCat sin Formato image/jpeg');
                            }
                            @unlink($filepath.$imagename);
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "No se pudo subir la imagen";
                        $this->registrar(8, 0, $this->id_gir, 'Categoria no Upload');
                    }
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Imagen sobrepasa los 25KB establecidos";
                    $this->registrar(8, 0, $this->id_gir, 'Categoria size limit');
                }
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Formato Invalido";
                $this->registrar(8, 0, $this->id_gir, 'Categoria no Extension');
            }
        }else{
            $info['op'] = 2;
            $info['mensaje'] =  "No ha seleccionado una imagen";
        }
        return $info;

    }
    public function uploadPagina($filepath, $filename){

        $filename = ($filename !== null) ? $filename : bin2hex(openssl_random_pseudo_bytes(10)) ;
        $file_formats = array("jpg", "jpeg", "JPG", "JPEG");
        $name = $_FILES['file_image0']['name']; // filename to get file's extension
        $size = $_FILES['file_image0']['size'];
        if (strlen($name)){
            $extension = substr($name, strrpos($name, '.') + 1);
            if (in_array($extension, $file_formats)) { // check it if it's a valid format or not
                if ($size < (200 * 1024)) { // check it if it's bigger than 2 mb or no
                    $imagename = $filename.".".$extension;
                    $imagename_new = $filename."x.".$extension;
                    $tmp = $_FILES['file_image0']['tmp_name'];
                    if (move_uploaded_file($tmp, $filepath.$imagename)){
                            $data = getimagesize($filepath.$imagename);
                            if($data['mime'] == "image/jpeg"){
                                $destino = imagecreatetruecolor($data[0], $data[1]);
                                $origen = imagecreatefromjpeg($filepath.$imagename);
                                imagecopy($destino, $origen, 0, 0, 0, 0, $data[0], $data[1]);
                                imagejpeg($destino, $filepath.$imagename_new);
                                imagedestroy($destino);
                                $info['op'] = 1;
                                $info['mensaje'] = "Imagen subida";
                                $info['image'] = $imagename_new;
                            }else{
                                $info['op'] = 2;
                                $info['mensaje'] = "La imagen no es jpg";
                                $this->registrar(8, 0, $this->id_gir, 'Pagina sin formato image/jpeg');
                            }
                            unlink($filepath.$imagename);
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "No se pudo subir la imagen";
                        $this->registrar(8, 0, $this->id_gir, 'Pagina no upload');
                    }
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Imagen sobrepasa los 2MB establecidos";
                    $this->registrar(8, 0, $this->id_gir, 'Pagina size limit');
                }
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Formato Invalido";
                $this->registrar(8, 0, $this->id_gir, 'Pagina no Extension');
            }
        }else{
            $info['op'] = 2;
            $info['mensaje'] =  "No ha seleccionado una imagen";
        }
        return $info;

    }
    private function configurar_inicio(){

        $info['op'] = 2;
        $info['mensaje'] = "Se produjo un error";

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $texto = $_POST['html'];
            if($sql = $this->con->prepare("UPDATE giros SET inicio_html=? WHERE id_gir=? AND eliminado=?")){
                if($sql->bind_param("sii", $texto, $this->id_gir, $this->eliminado)){
                    if($sql->execute()){
                        $info['op'] = 1;
                        $info['mensaje'] = "Pagina de Inicio modificado exitosamente";
                        $info['reload'] = 1;
                        $sql->close();
                        $this->con_cambios(null);
                        $seguir = $_POST['seguir'];
                        if($seguir == 0){
                            $info['page'] = 'msd/ver_giro.php';
                        }
                        if($seguir == 1){
                            $info['page'] = 'msd/configurar_pag_inicio.php';
                        }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'Config Pagina de Inicio '.$sql->error); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'Config Pagina de Inicio '.$sql->error); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'Config Pagina de Inicio '.$this->con->error); }
        }else{ $this->registrar(2, 0, 0, 'Config Footer'); }
        return $info;

    }
    private function configurar_footer(){
        
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $this->con_cambios(null);
            $texto = $_POST['html'];
            $seguir = $_POST['seguir'];

            $sql = $this->con->prepare("UPDATE giros SET footer_html=? WHERE id_gir=? AND eliminado=?");
            $sql->bind_param("sii", $texto, $this->id_gir, $this->eliminado);

            if($sql->execute()){
                $info['op'] = 1;
                $info['mensaje'] = "Footer modificado exitosamente";
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Se produjo un error";
                $this->registrar(2, 0, $this->id_gir, 'Config Footer');
            }
            
            $sql->close();
            $info['reload'] = 1;
            
            if($seguir == 0){
                $info['page'] = 'msd/ver_giro.php';
            }
            if($seguir == 1){
                $info['page'] = 'msd/configurar_footer.php';
            }

        }else{

            $this->registrar(2, 0, 0, 'Config Footer');
            $info['op'] = 2;
            $info['mensaje'] = "Error";

        }

        return $info;
        
    }
    private function con_cambios($id_gir){

        $id = ($id_gir === null) ? $this->id_gir : $id_gir ;

        $sql = $this->con->prepare("SELECT * FROM giros WHERE id_gir=? AND eliminado=?");
        $sql->bind_param("ii", $id, $this->eliminado);
        $sql->execute();
        $data = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        if($dns == 0){
            $url = "http://35.192.157.227/?url=".$data["dominio"];
        }
        if($dns == 1){
            if($data["ssl"] == 0){
                $url = "http://".$data["dominio"];
            }
            if($data["ssl"] == 1){
                $url = "https://".$data["dominio"];
            }
        }
        $sql->free_result();
        $sql->close();

        $send['accion'] = "xS3w1Dm8Po87Wltd";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
        curl_exec($ch);
        curl_close($ch);
        return;

    }
    private function solicitar_ssl(){
        
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $solicitud = $_POST["solicitud"];
            if($solicitud == 0){
                
                $sql = $this->con->prepare("UPDATE giros SET solicitar_ssl='0' WHERE id_gir=? AND eliminado=?");
                $sql->bind_param("ii", $this->id_gir, $this->eliminado);
                if($sql->execute()){
                    $info['op'] = 1;
                    $info['mensaje'] = "Solicitud enviada con exito";
                    $info['reload'] = 1;
                    $info['page'] = "msd/ver_giro.php";
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error";
                    $this->registrar(6, 0, 0, 'Error Sql: (solicitar ssl)');
                }
                $sql->close();
            
            }
            if($solicitud == 1){

                $sql = $this->con->prepare("UPDATE giros SET solicitar_ssl='1' WHERE id_gir=? AND eliminado=?");
                $sql->bind_param("ii", $this->id_gir, $this->eliminado);
                if($sql->execute()){
                    $info['op'] = 1;
                    $info['mensaje'] = "Solicitud enviada con exito";
                    $info['reload'] = 1;
                    $info['page'] = "msd/ver_giro.php";
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error";
                    $this->registrar(6, 0, 0, 'Error Sql: (solicitar ssl)');
                }
                $sql->close();
            
            }

        }else{

            $this->registrar(2, 0, 0, 'socilitar ssl');
            $info['op'] = 2;
            $info['mensaje'] = "Error";
        }
        return $info;
        
    }
    private function configurar_estilos(){
        
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $font_family = $_POST['font-family'];
            $font_css = $_POST['font-css'];
            $css_page = $_POST['css_page'];
            $css_color = $_POST['css_color'];
            $css_modal = $_POST['css_modal'];
            $this->con_cambios(null);
            
            $sql = $this->con->prepare("UPDATE giros SET style_modal=?, style_color=?, style_page=?, font_css=?, font_family=? WHERE id_gir=? AND eliminado=?");
            $sql->bind_param("sssssii", $css_modal, $css_color, $css_page, $font_css, $font_family, $this->id_gir, $this->eliminado);
            if($sql->execute()){
                $info['op'] = 1;
                $info['mensaje'] = "Configuracion de Estilos Modificado Exitosamente";
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error";
                $this->registrar(6, 0, 0, 'Error Sql: (conf estilos)');
            }

            $sql->close();
            $info['reload'] = 1;
            $info['page'] = "msd/ver_giro.php";

        }else{

            $this->registrar(2, 0, 0, 'conf estilos');
            $info['op'] = 2;
            $info['mensaje'] = "Error";
        
        }
        return $info;
        
    }
    private function configurar_giro(){
        
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

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

            $mapcode = $_POST['mapcode'];
            $estados = $_POST['estados'];
            $alto = $_POST['alto'];

            $this->con_cambios(null);

            $sql = $this->con->prepare("SELECT * FROM giros WHERE id_gir=? AND eliminado=?");
            $sql->bind_param("ii", $this->id_gir, $this->eliminado);
            $sql->execute();
            $resultgiro = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            $sql->free_result();
            $sql->close();

            $dominio = $resultgiro['dominio'];
            $foto_logo = $this->uploadLogo($dominio);
            $foto_favicon = $this->uploadfavIcon($dominio);

            if($foto_logo['op'] == 1){

                $sqla = $this->con->prepare("UPDATE giros SET logo='".$dominio.".png' WHERE id_gir=? AND eliminado=?");
                $sqla->bind_param("ii", $this->id_gir, $this->eliminado);
                $sqla->execute();
                $sqla->close();

            }
            if($foto_favicon['op'] == 1){
                
                $sqlb = $this->con->prepare("UPDATE giros SET favicon='".$dominio.".ico' WHERE id_gir=? AND eliminado=?");
                $sqlb->bind_param("ii", $this->id_gir, $this->eliminado);
                $sqlb->execute();
                $sqlb->close();

            }
            
            $sqlgir1 = $this->con->prepare("UPDATE giros SET alto=?, pedido_gengibre=?, pedido_wasabi=?, pedido_soya=?, pedido_teriyaki=?, pedido_palitos=?, pedido_comentarios=?, titulo=?, pedido_minimo=?, mapcode=?, estados=? WHERE id_gir=? AND eliminado=?");
            $sqlgir1->bind_param("issssssssssii", $alto, $pedido_gengibre, $pedido_wasabi, $pedido_soya, $pedido_teriyaki, $pedido_palitos, $pedido_comentarios, $titulo, $pedido_minimo, $mapcode, $estados, $this->id_gir, $this->eliminado);
            $sqlgir2 = $this->con->prepare("UPDATE giros SET pedido_01_titulo=?, pedido_01_subtitulo=?, pedido_02_titulo=?, pedido_02_subtitulo=?, pedido_03_titulo=?, pedido_03_subtitulo=?, pedido_04_titulo=?, pedido_04_subtitulo=? WHERE id_gir=? AND eliminado=?");
            $sqlgir2->bind_param("ssssssssii", $pedido_01_titulo, $pedido_01_subtitulo, $pedido_02_titulo, $pedido_02_subtitulo, $pedido_03_titulo, $pedido_03_subtitulo, $pedido_04_titulo, $pedido_04_subtitulo, $this->id_gir, $this->eliminado);
            
            if($sqlgir1->execute() && $sqlgir2->execute()){
                $info['op'] = 1;
                $info['mensaje'] = "Configuracion Base Modificado Exitosamente";
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error";
                $this->registrar(6, 0, 0, 'Error Sql: (conf giro)');
                
            }

            $sqlgir1->close();
            $sqlgir2->close();
            
            $info['reload'] = 1;
            $info['page'] = "msd/ver_giro.php";

        }else{

            $this->registrar(2, 0, 0, 'conf giro');
            $info['op'] = 2;
            $info['mensaje'] = "Error";
        
        }
        return $info;
        
    }
    public function get_alto(){
        
        if($sql = $this->con->prepare("SELECT alto FROM giros WHERE id_gir=? AND eliminado=?")){
            if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]["alto"];
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{
                    die('execute() failed: ' . htmlspecialchars($sql->error));
                    //$this->registrar(6, 0, 0, 'Error Sql: (GET ALTO)');
                }
            }else{
                die('bind_param() failed: ' . htmlspecialchars($sql->error));
            }
        }else{
            die('prepare() failed: ' . htmlspecialchars($this->con->error));
        }
        
    }
    public function list_arbol_cats_prods(){

        $sql = $this->con->prepare("SELECT t1.id_cae, t1.nombre as cat_nombre, t1.parent_id, t2.id_pro, t3.nombre as prod_nombre FROM categorias t1 LEFT JOIN cat_pros t2 ON t1.id_cae=t2.id_cae LEFT JOIN productos t3 ON t2.id_pro=t3.id_pro WHERE t1.id_cat=? AND t1.eliminado=? AND tipo=?");
        $sql->bind_param("iii", $this->id_cat, $this->eliminado, $this->eliminado);
        if(!$sql->execute()){
            $this->registrar(6, 0, 0, 'Error Sql: (LIST ARBOL CATS PRODS)');
        }
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();

        return $result;

    }
    private function configurar_categoria(){
        
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            if(isset($this->id_cat) && is_numeric($this->id_cat) && $this->id_cat > 0){

                $id_cae = $_POST['id_cae'];
                $parent_id = $_POST['parent_id'];
                $mostar_prods = $_POST['mostrar_prods'];
                $ocultar = $_POST['ocultar'];
                $detalle_prods = $_POST['detalle_prods'];
                $degradado = $_POST['degradado'];

                $this->con_cambios(null);
                $alto = $this->get_alto();

                $sql = $this->con->prepare("SELECT * FROM categorias WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?");
                $sql->bind_param("iiii", $id_cae, $this->id_cat, $this->id_gir, $this->eliminado);
                $sql->execute();
                $categoria = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                $sql->free_result();
                $sql->close();

                if($categoria['parent_id'] == 0){
                    $image = $this->uploadCategoria('/var/www/html/restaurants/images/categorias/', null, $alto);
                }
                if($categoria['parent_id'] > 0){
                    $image = $this->uploadsubCategoria('/var/www/html/restaurants/images/categorias/', null);
                }
                
                if($image['op'] == 1){
                
                    @unlink('/var/www/html/restaurants/images/categorias/'.$categoria['image']);
                    $sqlg = $this->con->prepare("UPDATE giros SET image='".$image["image"]."' WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?");
                    $sqlg->bind_param("iiii", $id_cae, $this->id_cat, $this->id_gir, $this->eliminado);
                    $sqlg->execute();
                    $sqlg->close();
             
                }

                $sqlmc = $this->con->prepare("UPDATE categorias SET ocultar=?, mostrar_prods=?, degradado=?, detalle_prods=? WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?");
                $sqlmc->bind_param("iissiiii", $ocultar, $mostar_prods, $degradado, $detalle_prods, $id_cae, $this->id_cat, $this->id_gir, $this->eliminado);
                
                if($sqlmc->execute()){
                    $info['op'] = 1;
                    $info['mensaje'] = "Configuracion modificado exitosamente";
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error";
                    $this->registrar(6, 0, 0, 'Error Sql: (conf categoria)');
                }

                $sqlmc->close();
                $info['reload'] = 1;
                $info['page'] = "msd/categorias.php?parent_id=".$parent_id;
            
            }else{

                $this->registrar(3, 0, 0, 'conf categoria');
                $info['op'] = 2;
                $info['mensaje'] = "Error";
            
            }
        }else{

            $this->registrar(2, 0, 0, 'conf categoria');
            $info['op'] = 2;
            $info['mensaje'] = "Error";
        
        }

        return $info;
        
    }
    private function get_preguntas(){

        $sql = $this->con->prepare("SELECT id_pre, nombre, mostrar FROM preguntas WHERE id_cat=? AND id_gir=? AND eliminado=?");
        $sql->bind_param("iii", $this->id_cat, $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();
        return $result;

    }
    private function configurar_producto(){
        
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $id_pro = $_POST['id_pro'];
            $id = $_POST['id'];
            $parent_id = $_POST['parent_id'];
            $this->con_cambios(null);
            $alto = $this->get_alto();

            $image = $this->uploadCategoria('/var/www/html/restaurants/images/productos/', null, $alto);
            if($image['op'] == 1){

                $sql = $this->con->prepare("SELECT image FROM productos WHERE id_pro=? AND id_gir=? AND id_gir=? AND eliminado=?");
                $sql->bind_param("iii", $id_pro, $this->id_gir, $this->eliminado);
                $sql->execute();
                $pro_image = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]["image"];
                $sql->free_result();
                $sql->close();

                @unlink('/var/www/html/restaurants/images/productos/'.$pro_image);

                $sqlpro = $this->con->prepare("UPDATE productos SET image=? WHERE id_pro=? AND id_gir=? AND eliminado=?");
                $sqlpro->bind_param("siii", $image["image"], $id_pro, $this->id_gir, $this->eliminado);
                $sqlpro->execute();
                $sqlpro->close();

            }

            $list = $this->get_preguntas();
            for($i=0; $i<count($list); $i++){
                $pre = $_POST['pregunta-'.$list[$i]['id_pre']];
                if($pre == 0){
                    
                    $sqldpr = $this->con->prepare("DELETE FROM preguntas_productos WHERE id_pro=? AND id_pre=?");
                    $sqldpr->bind_param("ii", $id_pro, $list[$i]["id_pre"]);
                    $sqldpr->execute();
                    $sqldpr->close();

                }
                if($pre == 1){

                    $sqlipr = $this->con->prepare("INSERT INTO preguntas_productos (id_pro, id_pre) VALUES (?, ?)");
                    $sqlipr->bind_param("ii", $id_pro, $list[$i]["id_pre"]);
                    $sqlipr->execute();
                    $sqlipr->close();

                }
            }

            $info['op'] = 1;
            $info['mensaje'] = "Configuracion Modificada Exitosamente";
            $info['reload'] = 1;
            $info['page'] = "msd/crear_productos.php?id=".$id."&parent_id=".$parent_id;

        }else{

            $info['op'] = 2;
            $info['mensaje'] = "Error";
            $this->registrar(2, 0, 0, 'conf producto');
        
        }
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
        
        $dominio = $_POST['dominio'];
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $item_pagina = $_POST['item_pagina'];
        $item_pos = $_POST['item_pos'];
        $item_cocina = $_POST['item_cocina'];
        $item_grafico = $_POST['item_grafico'];
        $dns_letra = ($_POST['dns_letra'] != "") ? $_POST['dns_letra'] : null ;

        if($this->admin == 1){
            if($this->verificar_dominio($dominio)){

                $sql = $this->con->prepare("SELECT id_gir FROM giros WHERE dominio=?");
                $sql->bind_param("s", $dominio);
                $sql->execute();
                $res = $sql->get_result();
                $result = $res->fetch_all(MYSQLI_ASSOC)[0];

                if($res->{"num_rows"} == 0 || ($res->{"num_rows"} == 1 && $id == $result["id_gir"])){
                    
                    if($id == 0){

                        $code = bin2hex(openssl_random_pseudo_bytes(10));
                        $sqligir = $this->con->prepare("INSERT INTO giros (nombre, dominio, fecha_creado, code, dns_letra, item_grafico, item_pos, item_cocina, item_pagina, catalogo, eliminado, id_ser) VALUES (?, ?, now(), ?, ?, ?, ?, ?, ?, '1', '0', '1')");
                        $sqligir->bind_param("ssssiiii", $nombre, $dominio, $code, $dns_letra, $item_grafico, $item_pos, $item_cocina, $item_pagina);
                        if($sqligir->execute()){

                            $id_gir = $this->con->insert_id;
                            $sqligir->close();

                            $sqlicat = $this->con->prepare("INSERT INTO catalogo_productos (nombre, fecha_creado, id_gir) VALUES ('Catalog 01', now(), ?)");
                            $sqlicat->bind_param("i", $id_gir);
                            $sqliugc = $this->con->prepare("INSERT INTO fw_usuarios_giros_clientes (id_user, id_gir) VALUES (?, ?)");
                            $sqliugc->bind_param("ii", $this->id_user, $id_gir);

                            if($sqlicat->execute() && $sqliugc->execute()){
                                $info['op'] = 1;
                                $info['mensaje'] = "Giro creado exitosamente";
                            }else{
                                $info['op'] = 2;
                                $info['mensaje'] = "Error: B2";
                                $this->registrar(6, 0, 0, 'Giros: err ingreso');
                            }

                            $sqliugc->close();
                            $sqlicat->close();

                            if(isset($this->id_aux_user) && is_numeric($this->id_aux_user) && $this->id_aux_user > 0){
                                $sqliugf = $this->con->prepare("INSERT INTO fw_usuarios_giros_clientes (id_user, id_gir) VALUES (?, ?)");
                                $sqliugf->bind_param("ii", $this->id_aux_user, $id_gir);
                                $sqliugf->execute();
                                $sqliugf->close();
                            }

                        }else{

                            $info['op'] = 2;
                            $info['mensaje'] = htmlspecialchars($sqligir->error);
                            $this->registrar(6, 0, 0, 'Giros: err ingreso');

                        }

                    }
                    if($id > 0){

                        $sql = $this->con->prepare("SELECT * FROM fw_usuarios_giros_clientes WHERE id_user=? AND id_gir=?");
                        $sql->bind_param("ii", $this->id_user, $id);
                        $sql->execute();
                        $sql->store_result();
                        if($sql->{"num_rows"} == 1 || $this->id_user == 1){
                            
                            $sqlugi = $this->con->prepare("UPDATE giros SET dns_letra=?, item_grafico=?, item_pos=?, item_cocina=?, item_pagina=?, nombre=?, dominio=? WHERE id_gir=? AND eliminado=?");
                            $sqlugi->bind_param("siiiissii", $dns_letra, $item_grafico, $item_pos, $item_cocina, $item_pagina, $nombre, $dominio, $id, $this->eliminado);
                            if($sqlugi->execute()){
                                $info['op'] = 1;
                                $info['mensaje'] = "Giro modificado exitosamente";
                                $this->con_cambios($id);
                            }else{
                                $info['op'] = 2;
                                $info['mensaje'] = "Error: Permisos A2";
                                $this->registrar(6, 0, 0, 'update giros');
                            }
                            $sqlugi->close();
                            
                        }else{
                            $info['op'] = 2;
                            $info['mensaje'] = "Error: Permisos A1";
                            $this->registrar(7, 0, 0, 'update user error');
                        }

                    }

                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error: F03";
                    $this->registrar(7, 0, 0, 'mod dominio exist');
                }
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error: F02";
            }
        }else{
            $info['op'] = 2;
            $info['mensaje'] = "Error: F01";
            $this->registrar(4, 0, 0, 'crear giro');
        }

        $info['reload'] = 1;
        $info['page'] = "msd/giros.php";
        return $info;

    }
    private function eliminar_giro(){

        $id_gir = $_POST['id'];
        $nombre = $_POST['nombre'];

        if($this->admin == 1){

            $sql = $this->con->prepare("SELECT * FROM fw_usuarios_giros_clientes WHERE id_user=? AND id_gir=?");
            $sql->bind_param("ii", $this->id_user, $id_gir);
            $sql->execute();
            $sql->store_result();

            if($sql->{"num_rows"} == 1){
                
                $sqlugi = $this->con->prepare("UPDATE giros SET eliminado='1' WHERE id_gir=?");
                $sqlugi->bind_param("i", $id_gir);
                if($sqlugi->execute()){

                    $info['tipo'] = "success";
                    $info['titulo'] = "Eliminado";
                    $info['texto'] = "Giro ".$nombre." Eliminado";
                    $info['reload'] = 1;
                    $info['page'] = "msd/giros.php";

                }else{

                    $info['tipo'] = "error";
                    $info['titulo'] = "Error";
                    $info['texto'] = "Giro ".$nombre." no pudo ser eliminado";
                    $this->registrar(6, 0, 0, 'borrar giro');

                }
                $sqlugi->close();

            }else{

                $info['tipo'] = "error";
                $info['titulo'] = "Error";
                $info['texto'] = "Giro ".$nombre." no pudo ser eliminado";
                $this->registrar(7, 0, 0, 'sin permisos');

            }
            
        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Giro ".$nombre." no pudo ser eliminado";
            $this->registrar(4, 0, 0, 'crear giro');

        }

        return $info;
        
    }
    private function eliminar_repartidor(){

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $id = explode("/", $_POST['id']);
            $nombre = $_POST["nombre"];

            $sqlugi = $this->con->prepare("UPDATE motos SET eliminado='1' WHERE id_mot=? AND id_gir=?");
            $sqlugi->bind_param("ii", $id[1], $this->id_gir);
            if($sqlugi->execute()){

                $info['tipo'] = "success";
                $info['titulo'] = "Eliminado";
                $info['texto'] = "Repartidor ".$nombre." Eliminado";
                $info['reload'] = 1;
                $info['page'] = "msd/crear_repartidor.php?id_loc=".$id[0]."&nombre=".$id[2];

            }else{

                $info['tipo'] = "error";
                $info['titulo'] = "Error";
                $info['texto'] = "Repartidor ".$nombre." no pudo ser eliminado";
                $this->registrar(6, 0, 0, 'del repartidor');

            }
            $sqlugi->close();
        
        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Repartidor ".$nombre." no pudo ser eliminado";
            $this->registrar(2, 0, 0, 'del repartidor');

        }
        return $info;

    }
    private function eliminar_tramos(){
        
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $id = explode("/", $_POST['id']);
            $nombre = $_POST["nombre"];

            $sql = $this->con->prepare("SELECT * FROM locales_tramos t1, locales t2 WHERE t1.id_lot=? AND t1.id_loc=t2.id_loc AND t2.id_gir=?");
            $sql->bind_param("ii", $id[1], $this->id_gir);
            $sql->execute();
            $sql->store_result();
            if($sql->{"num_rows"} == 1){

                $sqlugi = $this->con->prepare("UPDATE locales_tramos SET eliminado='1' WHERE id_lot=?");
                $sqlugi->bind_param("i", $id[1]);
                if($sqlugi->execute()){
                 
                    $info['tipo'] = "success";
                    $info['titulo'] = "Eliminado";
                    $info['texto'] = "Tramo ".$nombre." Eliminado";
                    $info['reload'] = 1;
                    $info['page'] = "msd/zonas_locales.php?id_loc=".$id[0];

                }else{

                    $info['tipo'] = "error";
                    $info['titulo'] = "Error";
                    $info['texto'] = "Tramo ".$nombre." no pudo ser eliminado";
                    $this->registrar(6, 0, 0, 'del locales tramos');

                }

            }
            if($sql->{"num_rows"} == 0){

                $info['tipo'] = "error";
                $info['titulo'] = "Error";
                $info['texto'] = "Tramo ".$nombre." no pudo ser eliminado";
                $this->registrar(7, 0, 0, 'del locales tramos');

            }

            
        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Tramo ".$nombre." no pudo ser eliminado";
            $this->registrar(2, 0, 0, 'del tramo');

        }

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

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            
            $sql = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND id_gir=? AND eliminado=?");
            $sql->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado);
            $sql->execute();
            $sql->store_result();

            if($sql->{"num_rows"} == 1){

                $this->con_cambios(null);
                if($id_hor == 0){

                    $sqligir = $this->con->prepare("INSERT INTO horarios (dia_ini, dia_fin, hora_ini, hora_fin, min_ini, min_fin, tipo, id_loc, id_gir, eliminado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $sqligir->bind_param("iiiiiiiiii", $dia_ini, $dia_fin, $hora_ini, $hora_fin, $min_ini, $min_fin, $tipo, $id_loc, $this->id_gir, $this->eliminado);
                    if($sqligir->execute()){
                        $info['op'] = 1;
                        $info['mensaje'] = "Horario creado exitosamente";
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "Error:";
                        $this->registrar(6, 0, 0, 'crear horario');
                    }
                    $sqligir->close();
                    
                }
                if($id_hor > 0){

                    $sqligir = $this->con->prepare("UPDATE horarios SET dia_ini=?, dia_fin=?, hora_ini=?, hora_fin=?, min_ini=?, min_fin=?, tipo=? WHERE id_hor=? AND id_loc=? AND id_gir=? AND eliminado=?");
                    $sqligir->bind_param("iiiiiiiiiii", $dia_ini, $dia_fin, $hora_ini, $hora_fin, $min_ini, $min_fin, $tipo, $this->id_hor, $id_loc, $this->id_gir, $this->eliminado);
                    if($sqligir->execute()){
                        $info['op'] = 1;
                        $info['mensaje'] = "Horario modificado exitosamente";
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "Error:";
                        $this->registrar(6, 0, 0, 'mod horario');
                    }
                    $sqligir->close();

                }

                $sqlre = $this->con->prepare("SELECT * FROM horarios WHERE id_gir=? AND eliminado=? AND (tipo='0' OR tipo='1')");
                $sqlre->bind_param("ii", $this->id_gir, $this->eliminado);
                $sqlre->execute();
                $sqlre->store_result();
                if($sqlre->{"num_rows"} == 0){
                    $sqlure = $this->con->prepare("UPDATE giros SET retiro_local='0' WHERE id_gir=? AND eliminado=?");
                    $sqlure->bind_param("ii", $this->id_gir, $this->eliminado);
                    $sqlure->execute();
                    $sqlure->close();
                }
                if($sqlre->{"num_rows"} > 0){
                    $sqlure = $this->con->prepare("UPDATE giros SET retiro_local='1' WHERE id_gir=? AND eliminado=?");
                    $sqlure->bind_param("ii", $this->id_gir, $this->eliminado);
                    $sqlure->execute();
                    $sqlure->close();
                }
                $sqlre->close();

                $sqlde = $this->con->prepare("SELECT * FROM horarios WHERE id_gir=? AND eliminado=? AND (tipo='0' OR tipo='2')");
                $sqlde->bind_param("ii", $this->id_gir, $this->eliminado);
                $sqlde->execute();
                $sqlde->store_result();
                if($sqlde->{"num_rows"} == 0){
                    $sqlude = $this->con->prepare("UPDATE giros SET despacho_domicilio='0' WHERE id_gir=? AND eliminado=?");
                    $sqlude->bind_param("ii", $this->id_gir, $this->eliminado);
                    $sqlude->execute();
                    $sqlude->close();
                }
                if($sqlde->{"num_rows"} > 0){
                    $sqlude = $this->con->prepare("UPDATE giros SET despacho_domicilio='1' WHERE id_gir=? AND eliminado=?");
                    $sqlude->bind_param("ii", $this->id_gir, $this->eliminado);
                    $sqlude->execute();
                    $sqlude->close();
                }
                $sqlde->close();

                $info['reload'] = 1;
                $info['page'] = "msd/crear_horario.php?id_loc=".$id_loc."&nombre=".$loc_nombre;

            }else{

                $info['op'] = 2;
                $info['mensaje'] = "Error:";
                $this->registrar(7, 0, 0, 'sin locales');
    
            }

        }else{

            $info['op'] = 2;
            $info['mensaje'] = "Error:";
            $this->registrar(2, 0, 0, 'crear horario');

        }
        return $info;

    }
    private function crear_repartidor(){

        $tipo = $_POST['tipo'];
        $id_loc = $_POST['id_loc'];
        $loc_nombre = $_POST['loc_nombre'];

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $sql = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND id_gir=? AND eliminado=?");
            $sql->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado);
            $sql->execute();
            $sql->store_result();

            if($sql->{"num_rows"} == 1){

                if($tipo == 0){

                    $nombre = $_POST['nombre'];
                    $correo = $_POST['correo'];
                    $uid = bin2hex(openssl_random_pseudo_bytes(10));

                    $sqlimo = $this->con->prepare("INSERT INTO motos (nombre, correo, uid, id_gir) VALUES (?, ?, ?, ?)");
                    $sqlimo->bind_param("sssi", $nombre, $correo, $uid, $this->id_gir);
                    if($sqlimo->execute()){

                        $id_mot = $this->con->insert_id;
                        $sqliml = $this->con->prepare("INSERT INTO motos_locales (id_mot, id_loc) VALUES (?, ?)");
                        $sqliml->bind_param("ii", $id_mot, $id_loc);
                        if($sqliml->execute()){
                            $info['op'] = 1;
                            $info['mensaje'] = "Repartidor ingresado exitosamente";
                            $info['reload'] = 1;
                            $info['page'] = "msd/crear_repartidor.php?id_loc=".$id_loc."&nombre=".$loc_nombre;
                        }else{
                            $info['op'] = 2;
                            $info['mensaje'] = "Error";
                            $this->registrar(6, 0, 0, 'motos_locales');
                        }
                        $sqliml->close();
                        
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "Error";
                        $this->registrar(6, 0, 0, 'insert motos');
                    }
                    
                    $sqlimo->close();

                }
                if($tipo == 1){

                    $id_mot = $_POST['repartidor'];
                    $sqlmot = $this->con->prepare("SELECT * FROM motos WHERE id_mot=? AND id_gir=? AND eliminado=?");
                    $sqlmot->bind_param("iii", $id_mot, $this->id_gir, $this->eliminado);
                    $sqlmot->execute();
                    $sqlmot->store_result();
                    if($sqlmot->{"num_rows"} == 1){

                        $sqliml = $this->con->prepare("INSERT INTO motos_locales (id_mot, id_loc) VALUES (?, ?)");
                        $sqliml->bind_param("ii", $id_mot, $id_loc);
                        if($sqliml->execute()){
                            $info['op'] = 1;
                            $info['mensaje'] = "Repartidor modificado exitosamente";
                            $info['reload'] = 1;
                            $info['page'] = "msd/crear_repartidor.php?id_loc=".$id_loc."&nombre=".$loc_nombre;
                        }else{
                            $info['op'] = 2;
                            $info['mensaje'] = "Error";
                        }
                        $sqliml->close();

                    }
                    if($sqlmot->{"num_rows"} == 0){
                        $info['op'] = 2;
                        $info['mensaje'] = "Error";
                    }
                    
                }

            }else{

                $info['op'] = 2;
                $info['mensaje'] = "Error:";
                $this->registrar(7, 0, 0, 'locales');
    
            }

        }else{

            $info['op'] = 2;
            $info['mensaje'] = "Error: Dominio Invalido";
            $this->registrar(2, 0, 0, 'crear repartidor');

        }
        return $info;

    }
    private function crear_catalogo(){
        
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $id_cat = $_POST['id'];
            $nombre = $_POST['nombre'];

            if($id_cat == 0){

                $sql = $this->con->prepare("INSERT INTO catalogo_productos (nombre, fecha_creado, id_gir) VALUES (?, now(), ?)");
                $sql->bind_param("si", $nombre, $this->id_gir);
                if($sql->execute()){
                    $info['op'] = 1;
                    $info['mensaje'] = "Catalogo creado exitosamente";
                    $info['reload'] = 1;
                    $info['page'] = "msd/ver_giro.php";
                    $this->con_cambios(null);
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error";
                    $this->registrar(6, 0, 0, 'insertar catalogo');
                }
                $sql->close();

            }
            if($id_cat > 0){

                $sql = $this->con->prepare("UPDATE catalogo_productos SET nombre=? WHERE id_cat=? AND id_gir=?");
                $sql->bind_param("sii", $nombre, $id_cat, $this->id_gir);
                if($sql->execute()){
                    $info['op'] = 1;
                    $info['mensaje'] = "Catalogo modificado exitosamente";
                    $info['reload'] = 1;
                    $info['page'] = "msd/ver_giro.php";
                    $this->con_cambios(null);
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error";
                    $this->registrar(6, 0, 0, 'mod catalogo');
                }
                $sql->close();

            }

        }else{

            $info['op'] = 2;
            $info['mensaje'] = "Error";
            $this->registrar(2, 0, 0, 'crear catalogo');

        }
        return $info;
        
    }
    private function eliminar_catalogo(){
        
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $id = explode("/", $_POST['id']);
            $nombre = $_POST["nombre"];

            $sqlcat = $this->con->prepare("SELECT * FROM catalogo_productos WHERE id_cat=? AND id_gir=? AND eliminado=?");
            $sqlcat->bind_param("iii", $id[1], $this->id_gir, $this->eliminado);
            $sqlcat->execute();
            $sqlcat->store_result();
            if($sqlcat->{"num_rows"} == 1){

                $sql = $this->con->prepare("UPDATE catalogo_productos SET eliminado='1' WHERE id_cat=? AND id_gir=?");
                $sql->bind_param("ii", $id[1], $this->id_gir);
                if($sql->execute()){
                    $info['tipo'] = "success";
                    $info['titulo'] = "Eliminado";
                    $info['texto'] = "Catalogo ".$nombre." Eliminado";
                    $info['reload'] = 1;
                    $info['page'] = "msd/ver_giro.php?id=".$id[0];
                }else{
                    $info['tipo'] = "error";
                    $info['titulo'] = "Error";
                    $info['texto'] = "Catalogo ".$nombre." no pudo ser eliminado";
                    $this->registrar(6, 0, 0, 'mod catalogo');
                }
                $sql->close();

            }
            if($sqlcat->{"num_rows"} == 0){

                $info['tipo'] = "error";
                $info['titulo'] = "Error";
                $info['texto'] = "Catalogo ".$nombre." no pudo ser eliminado";
                $this->registrar(7, 0, 0, 'catalogo');

            }

        
        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Catalogo ".$nombre." no pudo ser eliminado";
            $this->registrar(2, 0, 0, 'del catalogo');

        }

        return $info;
        
    }
    private function configurar_usuario_local(){

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $id_user = $_POST['id_user'];
            $id_loc = $_POST['id_loc'];

            $save_web = $_POST['save_web'];
            $web_min = $_POST['web_min'];
            
            $save_pos = $_POST['save_pos'];
            $pos_min = $_POST['pos_min'];

            $sqlloc = $this->con->prepare("SELECT * FROM fw_usuarios WHERE id_user=? AND id_loc=? AND id_gir=? AND eliminado=?");
            $sqlloc->bind_param("iiii", $id_user, $id_loc, $this->id_gir, $this->eliminado);
            $sqlloc->execute();
            $sqlloc->store_result();
            if($sqlloc->{"num_rows"} == 1){
                $sql = $this->con->prepare("UPDATE fw_usuarios SET save_web=?, web_min=?, save_pos=?, pos_min=? WHERE id_user=?");
                $sql->bind_param("iiiii", $save_web, $web_min, $save_pos, $pos_min, $id_user);
                if($sql->execute()){
                    $info['op'] = 1;
                    $info['mensaje'] = "Usuario editado exitosamente";
                    $info['reload'] = 1;
                    $info['page'] = "msd/usuarios_local.php?id_loc=".$id_loc;
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error";
                    $this->registrar(6, 0, 0, 'conf usuario local');
                }
                $sql->close();
            }
            if($sqlloc->{"num_rows"} == 0){
                $info['op'] = 2;
                $info['mensaje'] = "Error";
                $this->registrar(7, 0, 0, 'sin acceso usuario local');
            }

        }else{
            $info['op'] = 2;
            $info['mensaje'] = "Error";
            $this->registrar(2, 0, 0, 'conf usuario local');
        }

        return $info;

    }
    private function configurar_local(){

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $t_retiro = $_POST['t_retiro'];
            $t_despacho = $_POST['t_despacho'];
            $sonido = $_POST['sonido'];
            $pos = $_POST['pos'];
            $id_loc = $_POST['id_loc'];

            $sqlloc = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND id_gir=? AND eliminado=?");
            $sqlloc->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado);
            $sqlloc->execute();
            $sqlloc->store_result();
            if($sqlloc->{"num_rows"} == 1){
                $sql = $this->con->prepare("UPDATE locales SET pos=?, sonido=?, t_retiro=?, t_despacho=? WHERE id_loc=? AND id_gir=?");
                $sql->bind_param("isiiii", $pos, $sonido, $t_retiro, $t_despacho, $id_loc, $this->id_gir);
                if($sql->execute()){
                    $info['op'] = 1;
                    $info['mensaje'] = "Local editado exitosamente";
                    $info['reload'] = 1;
                    $info['page'] = "msd/locales.php";
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error";
                    $this->registrar(6, 0, 0, 'conf local');
                }
                $sql->close();
            }
            if($sqlloc->{"num_rows"} == 0){
                $info['op'] = 2;
                $info['mensaje'] = "Error";
                $this->registrar(7, 0, 0, 'sin acceso local');
            }

        }else{
            $info['op'] = 2;
            $info['mensaje'] = "Error";
            $this->registrar(2, 0, 0, 'conf local');
        }

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

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $this->con_cambios(null);
            $code = bin2hex(openssl_random_pseudo_bytes(10));

            $sql = $this->con->prepare("SELECT * FROM catalogo_productos WHERE id_cat=? AND id_gir=? AND eliminado=?");
            $sql->bind_param("iii", $id_cat, $this->id_gir, $this->eliminado);
            $sql->execute();
            $res = $sql->get_result();

            if($res->{"num_rows"} == 1){

                $sqlses = $this->con->prepare("SELECT * FROM ses_mail WHERE correo=?");
                $sqlses->bind_param("s", $correo);
                $sqlses->execute();
                $sqlses->store_result();
                $correo_ses = ($sqlses->{"num_rows"} == 0) ? 0 : 1 ;
                $sqlses->close();

                if($id_loc == 0){

                    $sqlloc = $this->con->prepare("INSERT INTO locales (nombre, correo_ses, direccion, lat, lng, code, fecha_creado, correo, id_cat, id_gir) VALUES (?, ?, ?, ?, ?, ?, now(), ?, ?, ?)");
                    $sqlloc->bind_param("sisddssii", $nombre, $correo_ses, $direccion, $lat, $lng, $code, $correo, $id_cat, $this->id_gir);
                    if($sqlloc->execute()){
                        $info['op'] = 1;
                        $info['mensaje'] = "Local creado exitosamente";
                        $info['reload'] = 1;
                        $info['page'] = "msd/locales.php";
                        $this->locales_giro();
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "Error";
                        $this->registrar(6, 0, 0, 'insertar locales');
                    }
                    $sqlloc->close();

                }
                if($id_loc > 0){

                    $sqlloc = $this->con->prepare("UPDATE locales SET nombre=?, correo_ses=?, direccion=?, lat=?, lng=?, correo=? WHERE id_loc=? AND id_cat=? AND id_gir=? AND eliminado=?");
                    $sqlloc->bind_param("sisddsiiii", $nombre, $correo_ses, $direccion, $lat, $lng, $correo, $id_loc, $id_cat, $this->id_gir, $this->eliminado);
                    if($sqlloc->execute()){
                        $info['op'] = 1;
                        $info['mensaje'] = "Local modificado exitosamente";
                        $info['reload'] = 1;
                        $info['page'] = "msd/locales.php";
                        $info['locales_giro'] = $this->locales_giro();
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "Error";
                        $this->registrar(6, 0, 0, 'mod locales');
                    }
                    $sqlloc->close();

                }

            }
            if($res->{"num_rows"} == 0){

                $info['op'] = 2;
                $info['mensaje'] = "Error";
                $this->registrar(7, 0, 0, 'no catalogo');

            }
            $sql->close();
            
        }else{

            $info['op'] = 2;
            $info['mensaje'] = "Error";
            $this->registrar(2, 0, 0, 'crear locales');

        }

        return $info;
        
    }
    private function crear_locales_tramos(){
        
        $id_lot = $_POST['id_lot'];
        $id_loc = $_POST['id_loc'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $pol = $_POST['posiciones'];
        
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){


            $sqlloc = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND id_gir=? AND eliminado=?");
            $sqlloc->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado);
            $sqlloc->execute();
            $res = $sqlloc->get_result();

            if($res->{"num_rows"} == 1){

                if($id_lot == 0){

                    $sqllt = $this->con->prepare("INSERT INTO locales_tramos (nombre, precio, poligono, id_loc) VALUES (?, ?, ?, ?)");
                    $sqllt->bind_param("sisi", $nombre, $precio, $pol, $id_loc);
                    if($sqllt->execute()){
                        $info['op'] = 1;
                        $info['mensaje'] = "Tramo creado exitosamente";
                        $info['reload'] = 1;
                        $info['page'] = "msd/zonas_locales.php?id_loc=".$id_loc;
                        $this->con_cambios(null);
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "Error";
                        $this->registrar(6, 0, 0, 'ins locales tramos');
                    }
                    $sqllt->close();

                }
                if($id_lot > 0){

                    $sqllt = $this->con->prepare("UPDATE locales_tramos SET nombre=?, precio=?, poligono=? WHERE id_lot=? AND id_loc=?");
                    $sqllt->bind_param("sisii", $nombre, $precio, $pol, $id_lot, $id_loc);
                    if($sqllt->execute()){
                        $info['op'] = 1;
                        $info['mensaje'] = "Tramo modificado exitosamente";
                        $info['reload'] = 1;
                        $info['page'] = "msd/zonas_locales.php?id_loc=".$id_loc;
                        $this->con_cambios(null);
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "Error";
                        $this->registrar(6, 0, 0, 'mod locales tramos');
                    }
                    $sqllt->close();

                }
                
                $sqlmin = $this->con->prepare("SELECT MIN(t3.precio) as min FROM giros t1, locales t2, locales_tramos t3 WHERE t1.id_gir=? AND t1.id_gir=t2.id_gir AND t2.id_loc=t3.id_loc AND t3.eliminado=? AND t2.eliminado=?");
                $sqlmin->bind_param("iii", $this->id_gir, $this->eliminado, $this->eliminado);
                $sqlmin->execute();
                $min = $sqlmin->get_result()->fetch_all(MYSQLI_ASSOC)[0]["min"];
                $sqlmin->free_result();
                $sqlmin->close();
    
                if($min !== null){

                    $sqlug = $this->con->prepare("UPDATE giros SET despacho_domicilio='1', desde=? WHERE id_gir=? AND eliminado=?");
                    $sqlug->bind_param("iii", $min, $this->id_gir, $this->eliminado);
                    if(!$sqlug->execute()){
                        $this->registrar(6, 0, 0, 'Error Sql: (Giros despacho=1)');
                    }
                    $sqlug->close();

                }else{

                    $sqlug = $this->con->prepare("UPDATE giros SET despacho_domicilio='0' WHERE id_gir=? AND eliminado=?");
                    $sqlug->bind_param("ii", $this->id_gir, $this->eliminado);
                    if(!$sqlug->execute()){
                        $this->registrar(6, 0, 0, 'Error Sql: (Giros despacho=0)');
                    }
                    $sqlug->close();

                }

            }
            if($res->{"num_rows"} == 0){
                
                $info['op'] = 2;
                $info['mensaje'] = "Error";
                $this->registrar(7, 0, 0, 'acceso a locales');

            }

            $sqlloc->close();

        }else{

            $info['op'] = 2;
            $info['mensaje'] = "Error";
            $this->registrar(2, 0, 0, 'local tramo');

        }

        return $info;
        
    }
    private function locales_giro(){

        $sql = $this->con->prepare("SELECT id_loc, lat, lng, nombre, direccion FROM locales WHERE id_gir=? AND eliminado=?");
        $sql->bind_param("ii", $this->id_gir, $this->eliminado);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->free_result();
        $sql->close();

        $sqlug = $this->con->prepare("UPDATE giros SET lista_locales=? WHERE id_gir=? AND eliminado=?");
        $sqlug->bind_param("sii", json_encode($result, JSON_UNESCAPED_UNICODE), $this->id_gir, $this->eliminado);
        if(!$sqlug->execute()){
            $this->registrar(6, 0, 0, 'Error Sql: (lista_locales)');
            return "MALA NELSON";
        }else{
            return "BUENA NELSON";
        }
        $sqlug->close();

    }
    private function eliminar_locales(){
                
        $id_loc = $_POST['id'];
        $nombre = $_POST["nombre"];

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $sql = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND id_gir=? AND eliminado=?");
            $sql->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado);
            $sql->execute();
            $sql->store_result();
            if($sql->{"num_rows"} == 1){

                $sqlult = $this->con->prepare("UPDATE locales SET eliminado='1' WHERE id_loc=?");
                $sqlult->bind_param("i", $id_loc);
                if($sqlult->execute()){

                    $this->locales_giro();
                    $info['tipo'] = "success";
                    $info['titulo'] = "Eliminado";
                    $info['texto'] = "Local ".$nombre." Eliminado";
                    $info['reload'] = 1;
                    $info['page'] = "msd/locales.php";

                }else{
                    
                    $info['tipo'] = "error";
                    $info['titulo'] = "Error";
                    $info['texto'] = "Local ".$nombre." no pudo ser eliminado";
                    $this->registrar(6, 0, 0, 'del locales');

                }
                $sqlult->close();

            }
            if($sql->{"num_rows"} == 0){

                $info['tipo'] = "error";
                $info['titulo'] = "Error";
                $info['texto'] = "Local ".$nombre." no pudo ser eliminado";
                $this->registrar(7, 0, 0, 'selec locales');

            }
            
        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Local ".$nombre." no pudo ser eliminado";
            $this->registrar(2, 0, 0, 'del locales');

        }
        return $info;
        
    }
    private function crear_usuario_admin(){

        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];

        if($this->admin == 1){
            if(filter_var($correo, FILTER_VALIDATE_EMAIL)){

                $sqlugc = $this->con->prepare("SELECT * FROM fw_usuarios_giros_clientes WHERE id_user=? AND id_gir=?");
                $sqlugc->bind_param("ii", $this->id_user, $this->id_gir);
                $sqlugc->execute();
                $sqlugc->store_result();
                if($sqlugc->{"num_rows"} == 1){

                    $sqlus = $this->con->prepare("SELECT * FROM fw_usuarios WHERE correo=?");
                    $sqlus->bind_param("s", $correo);
                    $sqlus->execute();
                    $sqlus->store_result();
                    $result = $sqlus->get_result()->fetch_all(MYSQLI_ASSOC)[0];

                    if($sqlus->{"num_rows"} == 0 || ($sqlus->{"num_rows"} == 1 && $id == $result["id_user"])){
                    
                        if($id == 0){
                        
                            $admin = 0;
                            $sqlius = $this->con->prepare("INSERT INTO fw_usuarios (nombre, fecha_creado, correo, admin) VALUES (?, now(), ?, ?)");
                            $sqlius->bind_param("ssi", $nombre, $correo, $admin);
                            if($sqlius->execute()){

                                $id_user = $this->con->insert_id;
                                $sqliug = $this->con->prepare("INSERT INTO fw_usuarios_giros (id_user, id_gir) VALUES (?, ?)");
                                $sqliug->bind_param("ii", $id_user, $this->id_gir);
                                if($sqliug->execute()){

                                    $info['op'] = 1;
                                    $info['mensaje'] = "Usuario creado exitosamente";
                                    $info['reload'] = 1;
                                    $info['page'] = "msd/usuarios_admin.php";

                                }else{

                                    $info['op'] = 2;
                                    $info['mensaje'] = "Error: ";
                                    $this->registrar(6, 0, 0, 'ins usuarios giro');

                                }
                                $sqliug->close();

                            }else{

                                $info['op'] = 2;
                                $info['mensaje'] = "Error: ";
                                $this->registrar(6, 0, 0, 'insert usuario');

                            }
                            
                            $sqlius->close();
    
                        }
                        if($id > 0){
                              
                            $sqluus = $this->con->prepare("UPDATE fw_usuarios SET nombre=?, correo=? WHERE id_user=? AND eliminado=?");
                            $sqluus->bind_param("ssii", $nombre, $correo, $id, $this->eliminado);
                            if($sqluus->execute()){
                                $info['op'] = 1;
                                $info['mensaje'] = "Usuario modificado exitosamente";
                                $info['reload'] = 1;
                                $info['page'] = "msd/usuarios_admin.php";
                            }else{
                                $info['op'] = 2;
                                $info['mensaje'] = "Error: ";
                                $this->registrar(6, 0, 0, 'mod usuario');
                            }
                            $sqluus->close();
    
                        }

                    }else{

                        $info['op'] = 2;
                        $info['mensaje'] = "Error: ";
                        $this->registrar(7, 0, 0, 'correo no exist');

                    }

                }else{

                    $info['op'] = 2;
                    $info['mensaje'] = "Error: ";
                    $this->registrar(7, 0, 0, 'user admin giro');

                }

            }else{

                $info['op'] = 2;
                $info['mensaje'] = "Error: ";

            }
        }else{

            $info['op'] = 2;
            $info['mensaje'] = "Error: ";
            $this->registrar(4, 0, 0, 'user admin giro');

        }

        return $info;

    }
    private function crear_usuarios_local(){

        $id = $_POST['id'];
        $id_loc = $_POST['id_loc'];
        $nombre = $_POST['v_nombre'];
        $correo = $_POST['v_correo'];
        $tipo = $_POST['v_tipo'];
        $pass1 = $_POST['v_pass1'];
        $pass2 = $_POST['v_pass2'];

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            if(filter_var($correo, FILTER_VALIDATE_EMAIL)){

                $sqlsl = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND id_gir=?");
                $sqlsl->bind_param("ii", $id_loc, $this->id_gir);
                $sqlsl->execute();
                $sqlsl->store_result();
                if($sqlsl->{"num_rows"} == 1){

                    $sqlus = $this->con->prepare("SELECT id_user FROM fw_usuarios WHERE correo=?");
                    $sqlus->bind_param("s", $correo);
                    $sqlus->execute();
                    $res = $sqlus->get_result();
                    $id_user = $res->fetch_all(MYSQLI_ASSOC)[0]["id_user"];

                    if($res->{"num_rows"} == 0 || ($res->{"num_rows"} == 1 && $id == $id_user)){
                    
                        if($id == 0){

                            if($pass1 == $pass2){
                                if(strlen($pass1) > 7){
        
                                    $admin = 0;
                                    $sqlius = $this->con->prepare("INSERT INTO fw_usuarios (nombre, fecha_creado, correo, pass, tipo, admin, id_loc, id_gir) VALUES (?, now(), ?, ?, ?, ?, ?, ?)");
                                    $sqlius->bind_param("sssiiii", $nombre, $correo, md5($pass1), $tipo, $admin, $id_loc, $this->id_gir);
                                    
                                    if($sqlius->execute()){
                                        $info['op'] = 1;
                                        $info['mensaje'] = "Usuario creado exitosamente";
                                        $info['reload'] = 1;
                                        $info['page'] = "msd/usuarios_local.php?id_loc=".$id_loc;
                                    }else{
                                        $info['op'] = 2;
                                        $info['mensaje'] = "Password diferentes";
                                        $this->registrar(6, 0, 0, 'ins user local');
                                    }
                                    $sqlius->close();
                                    
                                }else{
                                    $info['op'] = 2;
                                    $info['mensaje'] = "Password debe tener al menos 8 caracteres";
                                }
                            }else{
                                $info['op'] = 2;
                                $info['mensaje'] = "Password diferentes";
                            }

                        }
                        if($id > 0){
                            
                            if($pass1 == $pass2 && strlen($pass1) > 7){
                                $sqluup = $this->con->prepare("UPDATE fw_usuarios SET pass=? WHERE id_user=? AND eliminado=?");
                                $sqluup->bind_param("sii", md5($pass1), $id, $this->eliminado);
                                $sqluup->execute();
                                $sqluup->close();
                            }
        
                            $sqluus = $this->con->prepare("UPDATE fw_usuarios SET nombre=?, correo=?, tipo=? WHERE id_user=? AND eliminado=?");
                            $sqluus->bind_param("ssiii", $nombre, $correo, $tipo, $id, $this->eliminado);
                            if($sqluus->execute()){

                                $info['op'] = 1;
                                $info['mensaje'] = "Usuario modificado exitosamente";
                                $info['reload'] = 1;
                                $info['page'] = "msd/usuarios_local.php?id_loc=".$id_loc;

                            }else{
                                $info['op'] = 2;
                                $info['mensaje'] = "Error: ";
                                $this->registrar(6, 0, 0, 'mod user local');
                            }
                            $sqluus->close();

                        }

                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "Error: ";
                        $this->registrar(7, 0, 0, 'mod correo exist');
                    }

                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error: ";
                    $this->registrar(7, 0, 0, 'no local');
                }

            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error: ";
            }

        }else{
            $info['op'] = 2;
            $info['mensaje'] = "Error: ";
            $this->registrar(2, 0, 0, 'ins user local');
        }
        
        return $info;

    }
    private function crear_usuario(){

        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $tipo = $_POST['tipo'];
        $info['tipo'] = $tipo;

        if($this->re_venta == 1 || $this->id_user == 1){

            $sqlus = $this->con->prepare("SELECT id_user FROM fw_usuarios WHERE correo=?");
            $sqlus->bind_param("s", $correo);
            $sqlus->execute();
            $sqlus->store_result();
            $id_user = $sqlus->get_result()->fetch_all(MYSQLI_ASSOC)[0]["id_user"];

            if($sqlus->{"num_rows"} == 0 || ($sqlus->{"num_rows"} == 1 && $id == $id_user)){

                if($id > 0){

                    $sqluus = $this->con->prepare("UPDATE fw_usuarios SET nombre=?, correo=? WHERE id_user=? AND eliminado=?");
                    $sqluus->bind_param("ssii", $nombre, $correo, $id_user, $this->eliminado);
                    if($sqluus->execute()){
                        $info['op'] = 1;
                        $info['mensaje'] = "Usuarios modificado exitosamente";
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "Error";
                        $this->registrar(6, 0, 0, 'update usuarios');
                    }
                    $sqluus->close();

                    if($tipo == 0 && $this->id_user == 1){
                        $re = 0;
                        $sqlup = $this->con->prepare("UPDATE fw_usuarios SET re_venta=? WHERE id_user=? AND eliminado=?");
                        $sqlup->bind_param("iii", $re, $id_user, $this->eliminado);
                        if(!$sqlup->execute()){
                            $this->registrar(6, 0, 0, 'up usuarios perm');
                        }
                        $sqlup->execute();
                    }
                    if($tipo == 1 && $this->id_user == 1){
                        $re = 1;
                        $sqlup = $this->con->prepare("UPDATE fw_usuarios SET re_venta=? WHERE id_user=? AND eliminado=?");
                        $sqlup->bind_param("iii", $re, $id_user, $this->eliminado);
                        if(!$sqlup->execute()){
                            $this->registrar(6, 0, 0, 'up usuarios perm');
                        }
                        $sqlup->execute();
                    }

                }
                if($id == 0){

                    $admin = 1;
                    if($tipo == 0){
                        
                        $sqlius = $this->con->prepare("INSERT INTO fw_usuarios (nombre, fecha_creado, correo, admin, id_aux_user) VALUES (?, now(), ?, ?, ?)");
                        $sqlius->bind_param("ssii", $nombre, $correo, $admin, $this->id_user);    
                        if($sqlius->execute()){
                            $info['op'] = 1;
                            $info['mensaje'] = "Usuarios agregado exitosamente";
                        }else{
                            $info['op'] = 2;
                            $info['mensaje'] = "Error";
                            $this->registrar(6, 0, 0, 'ins usuarios');
                        }
                        $sqlius->close();

                    }
                    if($tipo == 1 && $this->id_user == 1){

                        $reventa = 1;
                        $sqlius = $this->con->prepare("INSERT INTO fw_usuarios (nombre, fecha_creado, correo, admin, re_venta) VALUES (?, now(), ?, ?, ?)");
                        $sqlius->bind_param("ssii", $nombre, $correo, $admin, $reventa);    
                        if($sqlius->execute()){
                            $info['op'] = 1;
                            $info['mensaje'] = "Usuarios agregado exitosamente";
                            $info['reload'] = 1;
                            $info['page'] = "msd/usuarios.php";
                        }else{
                            $info['op'] = 2;
                            $info['mensaje'] = "Error exitosamente";
                            $this->registrar(6, 0, 0, 'ins usuarios');
                        }
                        $sqlius->close();

                    }
                }

            }else{

                $info['op'] = 2;
                $info['mensaje'] = "Error:";
                $this->registrar(7, 0, 0, 'correo exist');

            }

        }else{

            $info['op'] = 2;
            $info['mensaje'] = "Error: #A398";
            $this->registrar(1, 0, 0, 'crear usuario');

        }

        return $info;
        
    }
    private function eliminar_horario(){

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $id = explode("/", $_POST['id']);

            $sql = $this->con->prepare("UPDATE horarios SET eliminado='1' WHERE id_hor=? AND id_gir=?");
            $sql->bind_param("ii", $id[0], $this->id_gir);
            if($sql->execute()){
                $info['tipo'] = "success";
                $info['titulo'] = "Eliminado";
                $info['texto'] = "Horario ".$_POST["nombre"]." Eliminado";
                $info['reload'] = 1;
                $info['page'] = "msd/crear_horario.php?id_loc=".$id[1]."&nombre=".$id[2];
            }else{
                $info['tipo'] = "error";
                $info['titulo'] = "Error";
                $info['texto'] = "Error:";
                $this->registrar(6, 0, 0, 'Error Sql: (del horario)');
            }
            $sql->close();

            $sqlre = $this->con->prepare("SELECT * FROM horarios WHERE id_gir=? AND eliminado=? AND (tipo='0' OR tipo='1')");
            $sqlre->bind_param("ii", $this->id_gir, $this->eliminado);
            if($sqlre->execute()){
                $sqlre->store_result();
                if($sqlre->{"num_rows"} == 0){
                    $sqlure = $this->con->prepare("UPDATE giros SET retiro_local='0' WHERE id_gir=? AND eliminado=?");
                    $sqlure->bind_param("ii", $this->id_gir, $this->eliminado);
                    if(!$sqlure->execute()){
                        $this->registrar(6, 0, 0, 'Error Sql: (up giro)');
                    }
                    $sqlure->close();
                }
                if($sqlre->{"num_rows"} > 0){
                    $sqlure = $this->con->prepare("UPDATE giros SET retiro_local='1' WHERE id_gir=? AND eliminado=?");
                    $sqlure->bind_param("ii", $this->id_gir, $this->eliminado);
                    if(!$sqlure->execute()){
                        $this->registrar(6, 0, 0, 'Error Sql: (up giro)');
                    }
                    $sqlure->close();
                }
            }else{
                $this->registrar(6, 0, 0, 'Error Sql: (select horario)');
            }
            $sqlre->close();

            $sqlde = $this->con->prepare("SELECT * FROM horarios WHERE id_gir=? AND eliminado=? AND (tipo='0' OR tipo='2')");
            $sqlde->bind_param("ii", $this->id_gir, $this->eliminado);
            if($sqlde->execute()){
                $sqlde->store_result();
                if($sqlde->{"num_rows"} == 0){
                    $sqlude = $this->con->prepare("UPDATE giros SET despacho_domicilio='0' WHERE id_gir=? AND eliminado=?");
                    $sqlude->bind_param("ii", $this->id_gir, $this->eliminado);
                    if(!$sqlude->execute()){
                        $this->registrar(6, 0, 0, 'Error Sql: (up giro)');
                    }
                    $sqlude->close();
                }
                if($sqlde->{"num_rows"} > 0){
                    $sqlude = $this->con->prepare("UPDATE giros SET despacho_domicilio='1' WHERE id_gir=? AND eliminado=?");
                    $sqlude->bind_param("ii", $this->id_gir, $this->eliminado);
                    if(!$sqlude->execute()){
                        $this->registrar(6, 0, 0, 'Error Sql: (up giro)');
                    }
                    $sqlude->close();
                }
            }else{
                $this->registrar(6, 0, 0, 'Error Sql: (select horario)');
            }
            $sqlde->close();
            
        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Error:";
            $this->registrar(2, 0, 0, 'del horario');

        }

        return $info;

    }
    private function eliminar_usuario(){
                
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];

        if($this->re_venta == 1 || $this->id_user == 1){

            $sql = $this->con->prepare("SELECT id_aux_user FROM fw_usuarios WHERE id_user=?");
            $sql->bind_param("i", $id);
            $sql->execute();
            $id_aux_user = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]["id_aux_user"];
            $sql->free_result();
            $sql->close();
            
            if($this->id_user == 1 || ($this->re_venta == 1 && $id_aux_user == $this->id_user)){
                
                $sqlu = $this->con->prepare("UPDATE fw_usuarios SET eliminado='1' WHERE id_user=?");
                $sqlu->bind_param("i", $id);
                if($sqlu->execute()){
                    $info['tipo'] = "success";
                    $info['titulo'] = "Eliminado";
                    $info['texto'] = "Usuario ".$nombre." Eliminado";
                    $info['reload'] = 1;
                    $info['page'] = "msd/usuarios.php";
                }else{
                    $info['tipo'] = "error";
                    $info['titulo'] = "Error";
                    $info['texto'] = "Usuario ".$nombre." no pudo ser eliminado";
                    $this->registrar(6, 0, 0, 'del user');
                }
                $sqlu->close();

            }else{

                $info['tipo'] = "error";
                $info['titulo'] = "Error";
                $info['texto'] = "Usuario ".$nombre." no pudo ser eliminado";
                $this->registrar(7, 0, 0, 'del user');

            }
            
        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Usuario ".$nombre." no pudo ser eliminado";
            $this->registrar(1, 0, 0, 'del usuario');

        }

        return $info;
        
    }
    private function eliminar_usuario_admin(){
                
        $id = $_POST['id'];

        if($this->admin == 1){

            $sql = $this->con->prepare("SELECT t2.id_gir, t1.nombre FROM fw_usuarios t1, fw_usuarios_giros t2 WHERE t1.id_user=? AND t1.id_user=t2.id_user");
            $sql->bind_param("i", $id);
            $sql->execute();
            $sql->store_result();
            $info = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            $sql->free_result();
            $sql->close();
            $nombre = $info["nombre"];

            if($sql->{"num_rows"} == 1){

                $sqlus = $this->con->prepare("SELECT * FROM fw_usuarios_giros_clientes WHERE id_user=? AND id_gir=?");
                $sqlus->bind_param("ii", $this->id_user, $info["id_gir"]);
                $sqlus->execute();
                $sqlus->store_result();
                if($sqlus->{"num_rows"} == 1){

                    $sqluus = $this->con->prepare("UPDATE fw_usuarios SET eliminado='1' WHERE id_user=?");
                    $sqluus->bind_param("i", $id);
                    if($sqluus->execute()){

                        $info['tipo'] = "success";
                        $info['titulo'] = "Eliminado";
                        $info['texto'] = "Usuario ".$nombre." Eliminado";
                        $info['reload'] = 1;
                        $info['page'] = "msd/usuarios.php";

                    }else{

                        $info['tipo'] = "error";
                        $info['titulo'] = "Error";
                        $info['texto'] = "Usuario ".$nombre." no pudo ser eliminado";
                        $this->registrar(6, 0, 0, 'del user admin');

                    }
                    $sqluus->close();

                }
                if($sqlus->{"num_rows"} == 0){

                    $info['tipo'] = "error";
                    $info['titulo'] = "Error";
                    $info['texto'] = "Usuario ".$nombre." no pudo ser eliminado";
                    $this->registrar(7, 0, 0, 'no permisos del user admin');

                }
                $sqlus->free_result();
                $sqlus->close();

            }
            if($sql->{"num_rows"} == 0){

                $info['tipo'] = "error";
                $info['titulo'] = "Error";
                $info['texto'] = "Usuario ".$nombre." no pudo ser eliminado";
                $this->registrar(7, 0, 0, 'del user admin');

            }
            
        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Usuario no pudo ser eliminado";
            $this->registrar(4, 0, 0, 'del user admin');

        }

        return $info;
        
    }
    private function eliminar_usuario_local(){
                
        $id = $_POST['id'];
        $id_loc = $_POST['id_loc'];

        $sql = $this->con->prepare("UPDATE fw_usuarios SET eliminado='1' WHERE id_user=? AND admin='0' AND id_loc=? AND id_gir=?");
        $sql->bind_param("iii", $id, $id_loc, $this->id_gir);
        if($sql->execute()){

            $info['tipo'] = "success";
            $info['titulo'] = "Eliminado";
            $info['texto'] = "Usuario ".$nombre." Eliminado";
            $info['reload'] = 1;
            $info['page'] = "msd/usuarios.php";

        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Usuario ".$nombre." no pudo ser eliminado";
            $this->registrar(6, 0, 0, 'del user local');

        }
        $sql->close();
        return $info;
        
    }
    private function asignar_rubro(){
        /*
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
        */
    }
    private function crear_categoria(){

        $id_cae = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $descripcion_sub = $_POST['descripcion_sub'];
        $precio = $_POST['precio'];
        $parent_id = $_POST['parent_id'];
        $tipo = $_POST['tipo'];

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            if($id_cae == 0){

                $sql = $this->con->prepare("SELECT * FROM categorias WHERE parent_id=?");
                $sql->bind_param("i", $parent_id);
                $sql->execute();
                $sql->store_result();
                $orders = $sql->{"num_rows"};
                $sql->free_result();
                $sql->close();

                $sqlic = $this->con->prepare("INSERT INTO categorias (nombre, parent_id, tipo, id_cat, id_gir, descripcion, descripcion_sub, precio, orders) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $sqlic->bind_param("siiiissii", $nombre, $parent_id, $tipo, $this->id_cat, $this->id_gir, $descripcion, $descripcion_sub, $precio, $orders);
                if($sqlic->execute()){
                    $info['op'] = 1;
                    $info['mensaje'] = "Categoria creada exitosamente";
                    $info['reload'] = 1;
                    $info['page'] = "msd/categorias.php?parent_id=".$parent_id;
                    $info['cambios'] = $this->con_cambios(null);
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error";
                    $this->registrar(6, 0, 0, 'ins user');
                }
                $sqlic->close();
                
            }
            if($id_cae > 0){

                $sqluc = $this->con->prepare("UPDATE categorias SET nombre=?, tipo=?, descripcion=?, descripcion_sub=?, precio=? WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?");
                $sqluc->bind_param("sissiiiii", $nombre, $tipo, $descripcion, $descripcion_sub, $precio, $id_cae, $this->id_cat, $this->id_gir, $this->eliminado);
                if($sqluc->execute()){
                    $info['op'] = 1;
                    $info['mensaje'] = "Categoria modificada exitosamente";
                    $info['reload'] = 1;
                    $info['page'] = "msd/categorias.php?parent_id=".$parent_id;
                    $info['cambios'] = $this->con_cambios(null);
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error";
                    $this->registrar(6, 0, 0, 'update cat');
                }
                $sqluc->close();

            }

        }else{

            $info['op'] = 2;
            $info['mensaje'] = "Error:";
            $this->registrar(2, 0, 0, 'crear cat');

        }
        return $info;
        
    }
    private function eliminar_categoria(){
                
        $id = explode("/", $_POST['id']);
        $nombre = $_POST["nombre"];

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $sql = $this->con->prepare("UPDATE categorias SET eliminado='1' WHERE id_cae=? AND id_gir=?");
            $sql->bind_param("ii", $id[0], $this->id_gir);
            if($sql->execute()){

                $this->con_cambios(null);
                $info['tipo'] = "success";
                $info['titulo'] = "Eliminado";
                $info['texto'] = "Categoria ".$_POST["nombre"]." Eliminado";
                $info['reload'] = 1;
                $info['page'] = "msd/categorias.php?id=".$id[0]."&parent_id=".$id[1];

            }else{

                $info['tipo'] = "error";
                $info['titulo'] = "Error";
                $info['texto'] = "Categoria ".$nombre." no pudo ser eliminado";
                $this->registrar(6, 0, 0, 'del cat');

            }
            $sql->close();
            
        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Categoria ".$nombre." no pudo ser eliminado";
            $this->registrar(2, 0, 0, 'del cat');

        }
        return $info;
        
    }
    private function eliminar_pagina(){
                
        $id = $_POST['id'];
        $nombre = $_POST["nombre"];

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $sql = $this->con->prepare("UPDATE paginas SET eliminado='1' WHERE id_pag=? AND id_gir=?");
            $sql->bind_param("ii", $id, $this->id_gir);
            if($sql->execute()){

                $this->con_cambios(null);
                $info['tipo'] = "success";
                $info['titulo'] = "Eliminado";
                $info['texto'] = "Pagina ".$nombre." Eliminado";
                $info['reload'] = 1;
                $info['page'] = "msd/ver_giro.php?id_gir=".$this->id_gir;

            }else{

                $info['tipo'] = "error";
                $info['titulo'] = "Error";
                $info['texto'] = "Pagina ".$nombre." no pudo ser eliminado";
                $this->registrar(6, 0, 0, 'del pag');
    
            }
            $sql->close();
        
        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Pagina ".$nombre." no pudo ser eliminado";
            $this->registrar(2, 0, 0, 'del pag');

        }
        return $info;
        
    }
    private function asignar_prods_promocion(){
        
        $id_cae = $_POST['id_cae'];
        $parent_id = $_POST['parent_id'];
        $precio = $_POST['precio'];

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $sql = $this->con->prepare("SELECT * FROM categorias WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?");
            $sql->bind_param("iiii", $id_cae, $this->id_cat, $this->id_gir, $this->eliminado);
            $sql->execute();
            $res = $sql->get_result();
            if($res->{"num_rows"} == 1){

                $sqluc = $this->con->prepare("UPDATE categorias SET precio=? WHERE id_cae=?");
                $sqluc->bind_param("ii", $precio, $id_cae);

                $sqlepc = $this->con->prepare("DELETE FROM promocion_categoria WHERE id_cae1=?");
                $sqlepc->bind_param("i", $id_cae);

                $sqlepp = $this->con->prepare("DELETE FROM promocion_productos WHERE id_cae=?");
                $sqlepp->bind_param("i", $id_cae);

                if($sqluc->execute() && $sqlepc->execute() && $sqlepp->execute()){

                    $values = $this->list_arbol_cats_prods();
                    for($i=0; $i<count($values); $i++){

                        $value = $values[$i];
                        if($value['id_cae'] !== null){
                            $cae_val = $_POST['sel-cae-'.$value['id_cae']];
                            if($cae_val > 0){

                                $sqlipc = $this->con->prepare("INSERT INTO promocion_categoria (id_cae1, id_cae2, cantidad) VALUES (?, ?, ?)");
                                $sqlipc->bind_param("iii", $id_cae, $value["id_cae"], $cae_val);
                                $sqlipc->execute();
                                if(!$sqlipc->execute()){
                                    $this->registrar(6, 0, 0, 'Error Sql: (promo cats)');
                                }
                                $sqlipc->close();

                            }
                        }
                        if($value['id_pro'] !== null){
                            $pro_val = $_POST['sel-pro-'.$value['id_pro']];
                            if($pro_val > 0){

                                $sqlipp = $this->con->prepare("INSERT INTO promocion_productos (id_cae, id_pro, cantidad) VALUES (?, ?, ?)");
                                $sqlipp->bind_param("iii", $id_cae, $value["id_pro"], $pro_val);
                                if(!$sqlipp->execute()){
                                    $this->registrar(6, 0, 0, 'Error Sql: (promo prods)');
                                }
                                $sqlipp->close();

                            }
                        }
                        
                    }

                    $this->con_cambios(null);
                    $info['op'] = 1;
                    $info['mensaje'] = "Productos Asignados";
                    $info['reload'] = 1;
                    $info['page'] = "msd/categorias.php?parent_id=".$parent_id;

                }else{

                    $this->registrar(6, 0, 0, 'error sql');

                }

                $sqluc->close();
                $sqlepc->close();
                $sqlepp->close();

            }
            if($res->{"num_rows"} == 0){

                $info['op'] = 2;
                $info['mensaje'] = "Error";
                $this->registrar(7, 0, 0, 'no select cat');

            }
            $sql->free_result();
            $sql->close();

        }else{

            $info['op'] = 2;
            $info['mensaje'] = "Error";
            $this->registrar(2, 0, 0, 'asig prods proms');

        }

        return $info;
        
    }
    private function crear_productos(){

        $id_pro = $_POST['id_pro'];
        $id_cae = $_POST['id'];
        $parent_id = $_POST['parent_id'];
        $tipo = $_POST['tipo'];
        
        $numero = $_POST['numero'];
        $nombre = $_POST['nombre'];
        $nombre_carro = $_POST['nombre_carro'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
        
            $sql = $this->con->prepare("SELECT * FROM categorias WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?");
            $sql->bind_param("iiii", $id_cae, $this->id_cat, $this->id_gir, $this->eliminado);
            if(!$sql->execute()){
                $this->registrar(6, 0, 0, 'Error Sql: (select cat)');
            }
            $res = $sql->get_result();
            if($res->{"num_rows"} == 1){

                $sqlcp = $this->con->prepare("SELECT * FROM cat_pros WHERE id_cae=?");
                $sqlcp->bind_param("i", $id_cae);
                if(!$sqlcp->execute()){
                    $this->registrar(6, 0, 0, 'Error Sql: (select cat_pros)');
                }
                $rescp = $sqlcp->get_result();
                $orders = $rescp->{"num_rows"};
                $sqlcp->free_result();
                $sqlcp->close();

                if($tipo == 0){
                    if($id_pro == 0){

                        $sqlip = $this->con->prepare("INSERT INTO productos (numero, nombre, nombre_carro, descripcion, fecha_creado, id_gir) VALUES (?, ?, ?, ?, now(), ?)");
                        $sqlip->bind_param("isssi", $numero, $nombre, $nombre_carro, $descripcion, $this->id_gir);
                        if(!$sqlip->execute()){
                            $this->registrar(6, 0, 0, 'Error Sql: (productos)');
                        }
                        $id_pro = $this->con->insert_id;
                        $sqlip->close();

                        $sqlipr = $this->con->prepare("INSERT INTO cat_pros (id_cae, id_pro, orders) VALUES (?, ?, ?)");
                        $sqlipr->bind_param("iii", $id_cae, $id_pro, $orders);
                        if(!$sqlipr->execute()){
                            $this->registrar(6, 0, 0, 'Error Sql: (insert cat_pros)');
                        }
                        $sqlipr->close();

                        $sqlipp = $this->con->prepare("INSERT INTO productos_precio (id_cat, id_pro, precio) VALUES (?, ?, ?)");
                        $sqlipp->bind_param("iii", $this->id_cat, $id_pro, $precio);
                        if(!$sqlipp->execute()){
                            $this->registrar(6, 0, 0, 'Error Sql: (insert productos_precio)');
                        }
                        $sqlipp->close();

                    }
                    if($id_pro > 0){

                        $sqlup = $this->con->prepare("UPDATE productos SET numero=?, nombre=?, nombre_carro=?, descripcion=? WHERE id_pro=? AND id_gir=? AND eliminado=?");
                        $sqlup->bind_param("isssiii", $numero, $nombre, $nombre_carro, $descripcion, $id_pro, $this->id_gir, $this->eliminado);
                        if(!$sqlup->execute()){
                            $this->registrar(6, 0, 0, 'Error Sql: (update productos)');
                        }
                        $sqlup->close();

                        $sqlupp = $this->con->prepare("UPDATE productos_precio SET precio=? WHERE id_pro=? AND id_cat=?");
                        $sqlupp->bind_param("iii", $precio, $id_pro, $this->id_cat);
                        if(!$sqlupp->execute()){
                            $this->registrar(6, 0, 0, 'Error Sql: (update productos_precio)');
                        }
                        $sqlupp->close();

                    }
                }
                if($tipo == 1){
                    $all_prods = $this->get_productos();
                    for($i=0; $i<count($all_prods); $i++){
                        $pro = $_POST['prod-'.$all_prods[$i]['id_pro']];
                        if($pro == 1){
                            $sqlxip = $this->con->prepare("INSERT INTO cat_pros (id_cae, id_pro, orders) VALUES (?, ?, ?)");
                            $sqlxip->bind_param("iii", $id_cae, $all_prods[$i]["id_pro"], $orders);
                            $sqlxip->execute();
                            if(!$sqlxip->execute()){
                                $this->registrar(6, 0, 0, 'Error Sql: (add cat_pros)');
                            }
                            $sqlxip->close();
                        }
                    }
                }
                
                $this->con_cambios(null);

                $info['op'] = 1;
                $info['mensaje'] = "Producto modificado exitosamente";
                $info['reload'] = 1;
                $info['page'] = "msd/crear_productos.php?id=".$id_cae."&parent_id=".$parent_id;

            }
            if($res->{"num_rows"} == 0){

                $info['op'] = 2;
                $info['mensaje'] = "Error";
                $this->registrar(7, 0, 0, 'no cat selected');

            }
            $sql->free_result();
            $sql->close();
        
        }else{

            $info['op'] = 2;
            $info['mensaje'] = "Error";
            $this->registrar(2, 0, 0, 'crear prod');

        }

        return $info;
        
    }
    private function eliminar_productos(){
                
        $id = explode("/", $_POST['id']);
        $nombre = $_POST["nombre"];

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $sql = $this->con->prepare("SELECT * FROM categorias WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?");
            $sql->bind_param("iiii", $id[1], $this->id_cat, $this->id_gir, $this->eliminado);
            $sql->execute();
            $sql->store_result();
            if($sql->{"num_rows"} == 1){

                $sqldcp = $this->con->prepare("DELETE FROM cat_pros WHERE id_pro=? AND id_cae=?");
                $sqldcp->bind_param("ii", $id[0], $id[1]);
                
                $sqlup = $this->con->prepare("UPDATE productos SET eliminado='1' WHERE id_pro=? AND id_gir=?");
                $sqlup->bind_param("ii", $id[0], $this->id_gir);
                
                if($sqldcp->execute() && $sqlup->execute()){

                    $this->con_cambios(null);
                    $info['tipo'] = "success";
                    $info['titulo'] = "Eliminado";
                    $info['texto'] = "Producto ".$nombre." Eliminado";
                    $info['reload'] = 1;
                    $info['page'] = "msd/crear_productos.php?id=".$id[1]."&parent_id=".$id[2];

                }else{

                    $info['tipo'] = "error";
                    $info['titulo'] = "Error";
                    $info['texto'] = "Producto ".$nombre." no pudo ser eliminado";
                    $this->registrar(6, 0, 0, 'Error sql: up cat_pros & productos');

                }
                
                $sqldcp->close();
                $sqlup->close();

            }
            if($sql->{"num_rows"} == 0){

                $info['tipo'] = "error";
                $info['titulo'] = "Error";
                $info['texto'] = "Producto ".$nombre." no pudo ser eliminado";
                $this->registrar(7, 0, 0, 'select categoria');

            }
            $sql->free_result();
            $sql->close();
        
        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Producto ".$nombre." no pudo ser eliminado";
            $this->registrar(2, 0, 0, 'Error Sql: (del pro)');

        }

        return $info;
        
    }
    private function crear_preguntas(){

        $id_pre = $_POST['id'];
        $nombre = $_POST['nombre'];
        $mostrar = $_POST['mostrar'];
        $cantidad = $_POST['cantidad'];

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $this->con_cambios(null);
            if($id_pre > 0){

                $sql = $this->con->prepare("UPDATE preguntas SET nombre=?, mostrar=? WHERE id_pre=? AND id_cat=? AND id_gir=? AND eliminado=?");
                $sql->bind_param("ssiiii", $nombre, $mostrar, $id_pre, $this->id_cat, $this->id_gir, $this->eliminado);
                if($sql->execute()){
                    $info['op'] = 1;
                    $info['mensaje'] = "Pregunta modificada exitosamente";
                    $info['reload'] = 1;
                    $info['page'] = "msd/preguntas.php";
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error";
                    $this->registrar(6, 0, 0, 'error sql: update preguntas');
                }
                $sql->close();

            }
            if($id_pre == 0){

                $sql = $this->con->prepare("INSERT INTO preguntas (nombre, mostrar, id_cat, id_gir) VALUES (?, ?, ?, ?)");
                $sql->bind_param("ssii", $nombre, $mostrar, $this->id_cat, $this->id_gir);
                if($sql->execute()){
                    $info['op'] = 1;
                    $info['mensaje'] = "Pregunta creada exitosamente";
                    $info['reload'] = 1;
                    $info['page'] = "msd/preguntas.php";
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error";
                    $this->registrar(6, 0, 0, 'error sql: insert preguntas');
                }
                $id_pre = $this->con->insert_id;
                $sql->close();

            }

            $sqldpv = $this->con->prepare("DELETE FROM preguntas_valores WHERE id_pre=?");
            $sqldpv->bind_param("i", $id_pre);
            $sqldpv->execute();
            if(!$sqldpv->execute()){
                $this->registrar(6, 0, 0, 'Error Sql: (del preguntas valores)');
            }
            $sqldpv->close();

            for($i=0; $i<$cantidad; $i++){
                
                $cant = $_POST["cant-".$i];
                $valores = $_POST["valores-".$i];
                $nombre = $_POST["nombre-".$i];
                $valores_json = json_encode(explode(",", $valores), JSON_UNESCAPED_UNICODE);
                if($cant > 0){

                    $sqlipv = $this->con->prepare("INSERT INTO preguntas_valores (cantidad, nombre, valores, id_pre) VALUES (?, ?, ?, ?)");
                    $sqlipv->bind_param("issi", $cant, $nombre, $valores_json, $id_pre);
                    if(!$sqlipv->execute()){
                        $this->registrar(6, 0, 0, 'Error Sql: (ins preguntas valores)');
                    }
                    $sqlipv->close();

                }
                
            }

        }else{

            $info['op'] = 2;
            $info['mensaje'] = "Error";
            $this->registrar(2, 0, 0, 'crear pagina');

        }

        return $info;
        
    }
    private function eliminar_preguntas(){
                
        $id = $_POST['id'];
        $nombre = $_POST["nombre"];

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $sql = $this->con->prepare("UPDATE preguntas SET eliminado='1' WHERE id_pre=? AND id_cat=? AND id_gir=?");
            $sql->bind_param("iii", $id, $this->id_cat, $this->id_gir);
            if($sql->execute()){

                $this->con_cambios(null);
                $info['tipo'] = "success";
                $info['titulo'] = "Eliminado";
                $info['texto'] = "Preguntas ".$nombre." Eliminado";
                $info['reload'] = 1;
                $info['page'] = "msd/preguntas.php";

            }else{

                $info['tipo'] = "error";
                $info['titulo'] = "Error";
                $info['texto'] = "Pregunta ".$nombre." no pudo ser eliminado";
                $this->registrar(6, 0, 0, 'error sql: del preguntas');

            }
            $sql->close();            
            
        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Pregunta ".$nombre." no pudo ser eliminado";
            $this->registrar(2, 0, 0, 'del preguntas');

        }

        return $info;
        
    }
    private function crear_pagina(){
        
        $id_pag = $_POST['id'];
        $nombre = $_POST['nombre'];
        $html = $_POST['html'];
        $tipo = $_POST['tipo'];

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $this->con_cambios(null);
            $image = $this->uploadPagina('/var/www/html/restaurants/images/paginas/', null);

            if($id_pag == 0){

                $sql = $this->con->prepare("INSERT INTO paginas (nombre, html, tipo, id_gir) VALUES (?, ?, ?, ?)");
                $sql->bind_param("ssii", $nombre, $html, $tipo, $this->id_gir);
                if($sql->execute()){
                    if($image['op'] == 1){
                        $id_pag = $this->con->insert_id;
                        $sqlupa = $this->con->prepare("UPDATE paginas SET imagen=? WHERE id_pag=? AND id_gir=? AND eliminado=?");
                        $sqlupa->bind_param("siii", $image["image"], $id_pag, $this->id_gir, $this->eliminado);
                        if(!$sqlupa->execute()){
                            $this->registrar(6, 0, 0, 'Error Sql: (update imagen pagina)');
                        }
                        $sqlupa->close();
                    }
                    $info['op'] = 1;
                    $info['mensaje'] = "Paginas creado exitosamente";
                    $info['reload'] = 1;
                    $info['page'] = "msd/configurar_contenido.php";
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error: D301";
                    $this->registrar(6, 0, 0, 'Error Sql: (ins pagina)');
                }
                $sql->close();

            }
            if($id_pag > 0){

                $sql = $this->con->prepare("SELECT imagen FROM paginas WHERE id_pag=? AND id_gir=? AND eliminado=?");
                $sql->bind_param("iii", $id_pag, $this->id_gir, $this->eliminado);
                if(!$sql->execute()){
                    $this->registrar(6, 0, 0, 'Error Sql: (select imagen pagina)');
                }
                $imagen = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]["imagen"];
                $sql->free_result();
                $sql->close();

                $sqlupa = $this->con->prepare("UPDATE paginas SET nombre=?, html=?, tipo=? WHERE id_pag=? AND id_gir=? AND eliminado=?");
                $sqlupa->bind_param("ssiiii", $nombre, $html, $tipo, $id_pag, $this->id_gir, $this->eliminado);
                if($sqlupa->execute()){
                    if($image["op"] == 1){

                        @unlink("/var/www/html/restaurants/images/paginas/".$imagen);
                        $sqlupi = $this->con->prepare("UPDATE paginas SET image=? WHERE id_pag=? AND id_gir=? AND eliminado=?");
                        $sqlupi->bind_param("siii", $image["image"], $id_pag, $this->id_gir, $this->eliminado);
                        if(!$sqlupi->execute()){
                            $this->registrar(6, 0, 0, 'Error Sql: (update imagen pagina)');
                        }
                        $sqlupi->close();

                    }
                    $info['op'] = 1;
                    $info['mensaje'] = "Paginas modificado exitosamente";
                    $info['reload'] = 1;
                    $info['page'] = "msd/configurar_contenido.php";
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error: D302";
                    $this->registrar(6, 0, 0, 'Error Sql: (update pagina)');
                }
                $sqlupa->close();

            }

        }else{

            $info['op'] = 2;
            $info['mensaje'] = "Error: D303";
            $this->registrar(2, 0, 0, 'crear pagina');

        }

        return $info;
        
    }

}