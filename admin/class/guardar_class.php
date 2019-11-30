<?php
session_start();

if(strpos($_SERVER["REQUEST_URI"], "guardar_class.php") !== false){
    header('HTTP/1.1 404 Not Found', true, 404);
    include('../../errors/404.html');
    exit;
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
                echo json_encode($this->crear_giro());
            }
            if($_POST['accion'] == "eliminar_giro"){
                echo json_encode($this->eliminar_giro());
            }
            if($_POST['accion'] == "eliminar_pagina"){
                echo json_encode($this->eliminar_pagina());
            }
            if($_POST['accion'] == "crear_catalogo"){
                echo json_encode($this->crear_catalogo());
            }
            if($_POST['accion'] == "eliminar_catalogo"){
                echo json_encode($this->eliminar_catalogo());
            }
            if($_POST['accion'] == "crear_locales"){
                echo json_encode($this->crear_locales());
            }
            if($_POST['accion'] == "configurar_local"){
                echo json_encode($this->configurar_local());
            }
            if($_POST['accion'] == "configurar_usuario_local"){
                echo json_encode($this->configurar_usuario_local());
            }
            if($_POST['accion'] == "crear_locales_tramos"){
                echo json_encode($this->crear_locales_tramos());
            }
            if($_POST['accion'] == "eliminar_locales"){
                echo json_encode($this->eliminar_locales());
            }
            if($_POST['accion'] == "crear_usuario"){
                echo json_encode($this->crear_usuario());
            }
            if($_POST['accion'] == "crear_usuario_admin"){
                echo json_encode($this->crear_usuario_admin());
            }
            if($_POST['accion'] == "crear_usuarios_local"){
                echo json_encode($this->crear_usuarios_local());
            }
            if($_POST['accion'] == "eliminar_usuario"){
                echo json_encode($this->eliminar_usuario());
            }
            if($_POST['accion'] == "eliminar_usuario_admin"){
                echo json_encode($this->eliminar_usuario_admin());
            }
            if($_POST['accion'] == "eliminar_usuario_local"){
                echo json_encode($this->eliminar_usuario_local());
            }
            if($_POST['accion'] == "asignar_rubro"){
                echo json_encode($this->asignar_rubro());
            }
            if($_POST['accion'] == "crear_categoria"){
                echo json_encode($this->crear_categoria());
            }
            if($_POST['accion'] == "eliminar_categoria"){
                echo json_encode($this->eliminar_categoria());
            }
            if($_POST['accion'] == "crear_ingredientes"){
                echo json_encode($this->crear_ingredientes());
            }
            if($_POST['accion'] == "eliminar_ingrediente"){
                echo json_encode($this->eliminar_ingrediente());
            }
            if($_POST['accion'] == "crear_promociones"){
                echo json_encode($this->crear_promociones());
            }
            if($_POST['accion'] == "eliminar_promociones"){
                echo json_encode($this->eliminar_promociones());
            }
            if($_POST['accion'] == "crear_productos"){
                echo json_encode($this->crear_productos());
            }
            if($_POST['accion'] == "eliminar_productos"){
                echo json_encode($this->eliminar_productos());
            }
            if($_POST['accion'] == "asignar_prods_promocion"){
                echo json_encode($this->asignar_prods_promocion());
            }
            if($_POST['accion'] == "crear_preguntas"){
                echo json_encode($this->crear_preguntas());
            }
            if($_POST['accion'] == "eliminar_preguntas"){
                echo json_encode($this->eliminar_preguntas());
            }
            if($_POST['accion'] == "configurar_giro"){
                echo json_encode($this->configurar_giro());
            }
            if($_POST['accion'] == "configurar_estilos"){
                echo json_encode($this->configurar_estilos());
            }
            if($_POST['accion'] == "crear_pagina"){
                echo json_encode($this->crear_pagina());
            }
            if($_POST['accion'] == "configurar_footer"){
                echo json_encode($this->configurar_footer());
            }
            if($_POST['accion'] == "configurar_inicio"){
                echo json_encode($this->configurar_inicio());
            }
            if($_POST['accion'] == "refresh"){
                echo json_encode($this->refresh());
            }
            if($_POST['accion'] == "configurar_categoria"){
                echo json_encode($this->configurar_categoria());
            }
            if($_POST['accion'] == "ordercat"){
                echo json_encode($this->ordercat());
            }
            if($_POST['accion'] == "orderpag"){
                echo json_encode($this->orderpag());
            }
            if($_POST['accion'] == "orderprods"){
                echo json_encode($this->orderprods());
            }
            if($_POST['accion'] == "configurar_producto"){
                echo json_encode($this->configurar_producto());
            }
            if($_POST['accion'] == "eliminar_tramos"){
                echo json_encode($this->eliminar_tramos());
            }
            if($_POST['accion'] == "crear_lista_ingredientes"){
                echo json_encode($this->crear_lista_ingredientes());
            }
            if($_POST['accion'] == "crear_repartidor"){
                echo json_encode($this->crear_repartidor());
            }
            if($_POST['accion'] == "crear_horario"){
                echo json_encode($this->crear_horario());
            }
            if($_POST['accion'] == "eliminar_repartidor"){
                echo json_encode($this->eliminar_repartidor());
            }
            if($_POST['accion'] == "eliminar_horario"){
                echo json_encode($this->eliminar_horario());
            }
            if($_POST['accion'] == "solicitar_ssl"){
                echo json_encode($this->solicitar_ssl());
            }
            if($_POST['accion'] == "add_ses"){
                echo json_encode($this->add_ses());
            }
            if($_POST['accion'] == "add_dns"){
                echo json_encode($this->add_dns());
            }
            if($_POST['accion'] == "add_dns"){
                echo json_encode($this->add_ssl());
            }
            if($_POST['accion'] == "crear_pago"){
                echo json_encode($this->crear_pago());
            }
        }

    }
    private function crear_pago(){

        if($this->id_user == 1){

            $info['op'] = 2;
            $info['mensaje'] = "Factura no se pudo guardar";
            $factura = $_POST["factura"];
            
            if($sqlx = $this->con->prepare("SELECT * FROM pagos WHERE factura=?")){
                if($sqlx->bind_param("i", $factura)){
                    if($sqlx->execute()){
                        
                        $resx = $sqlx->get_result();
                        if($resx->{"num_rows"} == 0){
                        
                            $id_gir = $_POST["id_gir"];
                            $fecha = $_POST["fecha"];
                            $meses = $_POST["meses"];
                            $monto = $_POST["monto"];

                            if($sql = $this->con->prepare("INSERT INTO pagos (fecha, monto, factura, meses, id_gir) VALUES (?, ?, ?, ?, ?)")){
                                if($sql->bind_param("siiii", $fecha, $monto, $factura, $meses, $id_gir)){
                                    if($sql->execute()){

                                        if($sqls = $this->con->prepare("SELECT count(*) as cantidad FROM pagos WHERE id_gir=?")){
                                            if($sqls->bind_param("i", $id_gir)){
                                                if($sqls->execute()){

                                                    $res = $sqls->get_result();
                                                    $cantidad = $res->fetch_all(MYSQLI_ASSOC)[0]['cantidad'];

                                                    if($sqli = $this->con->prepare("UPDATE giros SET cant_pagos=? WHERE id_gir=?")){
                                                        if($sqli->bind_param("ii", $cantidad, $id_gir)){
                                                            if($sqli->execute()){

                                                                $back = $_POST["back"];
                                                                $info['op'] = 1;
                                                                $info['mensaje'] = "Factura creada exitosamente";
                                                                $info['reload'] = 1;
                                                                $info['page'] = "msd/ver_pagos.php?id_gir=".$id_gir."&back=".$back;
                                                                $sqli->close();

                                                            }else{ $this->registrar(6, 0, 0, 'crear_giro() #1 '.htmlspecialchars($sqli->error)); }
                                                        }else{ $this->registrar(6, 0, 0, 'crear_giro() #1 '.htmlspecialchars($sqli->error)); }
                                                    }else{ $this->registrar(6, 0, 0, 'crear_giro() #1 '.htmlspecialchars($this->con->error)); }

                                                    $sqls->free_result();
                                                    $sqls->close();

                                                }else{ $this->registrar(6, 0, 0, 'crear_giro() #1 '.htmlspecialchars($sqls->error)); }
                                            }else{ $this->registrar(6, 0, 0, 'crear_giro() #1 '.htmlspecialchars($sqls->error)); }
                                        }else{ $this->registrar(6, 0, 0, 'crear_giro() #1 '.htmlspecialchars($this->con->error)); }

                                        
                                        $sql->close();
                                    }else{ $this->registrar(6, 0, 0, 'crear_giro() #1 '.htmlspecialchars($sql->error)); }
                                }else{ $this->registrar(6, 0, 0, 'crear_giro() #1 '.htmlspecialchars($sql->error)); }
                            }else{ $this->registrar(6, 0, 0, 'crear_giro() #1 '.htmlspecialchars($this->con->error)); }

                        }
                        if($resx->{"num_rows"} == 1){
                            $info['op'] = 2;
                            $info['mensaje'] = "Factura #".$factura." ya existe";
                        }
                        $sqlx->free_result();
                        $sqlx->close();

                    }else{ $this->registrar(6, 0, 0, 'crear_giro() #1 '.htmlspecialchars($sqlx->error)); }
                }else{ $this->registrar(6, 0, 0, 'crear_giro() #1 '.htmlspecialchars($sqlx->error)); }
            }else{ $this->registrar(6, 0, 0, 'crear_giro() #1 '.htmlspecialchars($this->con->error)); }

        }else{ $this->registrar(6, 0, 0, 'crear_giro() #1 '.htmlspecialchars($this->con->error)); }
        return $info;

    }
    private function registrar($id_des, $id_loc, $id_gir, $txt){
        $ruta_data = "/var/www/html/error.log";
        if($sqlipd = $this->con->prepare("INSERT INTO seguimiento (id_des, id_user, id_loc, id_gir, fecha, txt) VALUES (?, ?, ?, ?, now(), ?)")){
            if($sqlipd->bind_param("iiiis", $id_des, $this->id_user, $id_loc, $id_gir, $txt)){
                if($sqlipd->execute()){
                    $sqlipd->close();
                }else{ file_put_contents($ruta_data, "NO SE PUDO GAURDAR EL SEGUIMIENTO: id_des: ".$id_des." /id_loc: ".$id_loc." /id_gir: ".$id_gir." /txt: ".$txt); }
            }else{ file_put_contents($ruta_data, "NO SE PUDO GAURDAR EL SEGUIMIENTO: id_des: ".$id_des." /id_loc: ".$id_loc." /id_gir: ".$id_gir." /txt: ".$txt); }
        }else{ file_put_contents($ruta_data, "NO SE PUDO GAURDAR EL SEGUIMIENTO: id_des: ".$id_des." /id_loc: ".$id_loc." /id_gir: ".$id_gir." /txt: ".$txt); } 
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

        $info['op'] = 2;
        $info['mensaje'] = "Error:";
        if($this->admin == 1){
            $dominio = $_POST['dominio'];
            if($this->verificar_dominio($dominio)){
                if($sql = $this->con->prepare("SELECT id_gir FROM giros WHERE dominio=?")){
                    if($sql->bind_param("s", $dominio)){
                        if($sql->execute()){
                            $id = $_POST['id'];
                            $res = $sql->get_result();
                            $result = $res->fetch_all(MYSQLI_ASSOC)[0];
                            if($res->{"num_rows"} == 0 || ($res->{"num_rows"} == 1 && $id == $result["id_gir"])){
                                
                                $nombre = $_POST['nombre'];
                                $item_pagina = $_POST['item_pagina'];
                                $item_pos = $_POST['item_pos'];
                                $item_cocina = $_POST['item_cocina'];
                                $item_grafico = $_POST['item_grafico'];
                                $dns_letra = ($_POST['dns_letra'] != "") ? $_POST['dns_letra'] : null ;
                                $prueba = $_POST['prueba'];

                                if($_POST['plan'] == 0){ $monto_vendedor = "40000"; $monto = "40000"; }
                                if($_POST['plan'] == 1){ $monto_vendedor = "45000"; $monto = "50000"; }
                                
                                
                                
                                if($id == 0){
                                    $code = $this->pass_generate(20);
                                    if($sqligir = $this->con->prepare("INSERT INTO giros (nombre, dominio, fecha_creado, code, dns_letra, item_grafico, item_pos, item_cocina, item_pagina, catalogo, style_page, style_color, style_modal, font_family, font_css, alto, alto_pro, logo, favicon, eliminado, id_ser, prueba, monto_vendedor, monto) VALUES (?, ?, now(), ?, ?, ?, ?, ?, ?, '1', 'css_tipo_01.css', 'css_colores_01.css', 'css_fontsize_01.css', 'K2D', 'K2D', '25', '20', 'sinlogo.png', 'default.ico', '0', '1', ?, ?, ?)")){
                                        if($sqligir->bind_param("ssssiiiiiii", $nombre, $dominio, $code, $dns_letra, $item_grafico, $item_pos, $item_cocina, $item_pagina, $prueba, $monto_vendedor, $monto)){
                                            if($sqligir->execute()){
                                                $id_gir = $this->con->insert_id;
                                                if($sqlicat = $this->con->prepare("INSERT INTO catalogo_productos (nombre, fecha_creado, id_gir) VALUES ('Catalog 01', now(), ?)")){
                                                    if($sqlicat->bind_param("i", $id_gir)){
                                                        if($sqlicat->execute()){
                                                            if($sqliugc = $this->con->prepare("INSERT INTO fw_usuarios_giros_clientes (id_user, id_gir) VALUES (?, ?)")){
                                                                if($sqliugc->bind_param("ii", $this->id_user, $id_gir)){
                                                                    if($sqliugc->execute()){
                                                                        $info['op'] = 1;
                                                                        $info['mensaje'] = "Giro creado exitosamente";
                                                                        $info['reload'] = 1;
                                                                        $info['page'] = "msd/giros.php";
                                                                        $sqliugc->close();
                                                                    }else{ $this->registrar(6, 0, 0, 'crear_giro() #1 '.htmlspecialchars($sqliugc->error)); }
                                                                }else{ $this->registrar(6, 0, 0, 'crear_giro() #1 '.htmlspecialchars($sqliugc->error)); }
                                                            }else{ $this->registrar(6, 0, 0, 'crear_giro() #1 '.htmlspecialchars($this->con->error)); }
                                                            $sqlicat->close();
                                                        }else{ $this->registrar(6, 0, 0, 'crear_giro() #2 '.htmlspecialchars($sqlicat->error)); }
                                                    }else{ $this->registrar(6, 0, 0, 'crear_giro() #2 '.htmlspecialchars($sqlicat->error)); }
                                                }else{ $this->registrar(6, 0, 0, 'crear_giro() #2 '.htmlspecialchars($this->con->error)); }
                                                $sqligir->close();
                                                if(isset($this->id_aux_user) && is_numeric($this->id_aux_user) && $this->id_aux_user > 0){
                                                    if($sqliugf = $this->con->prepare("INSERT INTO fw_usuarios_giros_clientes (id_user, id_gir) VALUES (?, ?)")){
                                                        if($sqliugf->bind_param("ii", $this->id_aux_user, $id_gir)){
                                                            if($sqliugf->execute()){
                                                                $sqliugf->close();
                                                            }else{ $this->registrar(6, 0, 0, 'crear_giro() #3 '.htmlspecialchars($sqliugf->error)); }
                                                        }else{ $this->registrar(6, 0, 0, 'crear_giro() #3 '.htmlspecialchars($sqliugf->error)); }
                                                    }else{ $this->registrar(6, 0, 0, 'crear_giro() #3 '.htmlspecialchars($this->con->error)); }
                                                }else{  }
                                            }else{ $this->registrar(6, 0, 0, 'crear_giro() #4 '.htmlspecialchars($sqligir->error)); }
                                        }else{ $this->registrar(6, 0, 0, 'crear_giro() #4 '.htmlspecialchars($sqligir->error)); }
                                    }else{ $this->registrar(6, 0, 0, 'crear_giro() #4 '.htmlspecialchars($this->con->error)); }
                                }

                                if($id > 0){
                                    if($sqlx = $this->con->prepare("SELECT * FROM fw_usuarios_giros_clientes WHERE id_user=? AND id_gir=?")){
                                        if($sqlx->bind_param("ii", $this->id_user, $id)){
                                            if($sqlx->execute()){
                                                $resx = $sqlx->get_result();
                                                if($resx->{"num_rows"} == 1 || $this->id_user == 1){
                                                    if($sqlugi = $this->con->prepare("UPDATE giros SET dns_letra=?, item_grafico=?, item_pos=?, item_cocina=?, item_pagina=?, nombre=?, dominio=?, monto_vendedor=?, monto=? WHERE id_gir=? AND eliminado=?")){
                                                        if($sqlugi->bind_param("siiiissiiii", $dns_letra, $item_grafico, $item_pos, $item_cocina, $item_pagina, $nombre, $dominio, $monto_vendedor, $monto, $id, $this->eliminado)){
                                                            if($sqlugi->execute()){
                                                                $info['op'] = 1;
                                                                $info['mensaje'] = "Giro modificado exitosamente";
                                                                $info['reload'] = 1;
                                                                $info['page'] = "msd/giros.php";
                                                                $sqlugi->close();
                                                                $this->con_cambios($id);
                                                            }else{ $this->registrar(6, 0, 0, 'crear_giro() #5 '.htmlspecialchars($sqlugi->error)); }
                                                        }else{ $this->registrar(6, 0, 0, 'crear_giro() #5 '.htmlspecialchars($sqlugi->error)); }
                                                    }else{ $this->registrar(6, 0, 0, 'crear_giro() #5 '.htmlspecialchars($this->con->error)); }
                                                }else{ $this->registrar(7, 0, 0, 'crear_giro() XSS'); }
                                                $sqlx->free_result();
                                                $sqlx->close();
                                            }else{ $this->registrar(6, 0, 0, 'crear_giro() #6 '.htmlspecialchars($sqlx->error)); }
                                        }else{ $this->registrar(6, 0, 0, 'crear_giro() #6 '.htmlspecialchars($sqlx->error)); }
                                    }else{ $this->registrar(6, 0, 0, 'crear_giro() #6 '.htmlspecialchars($this->con->error)); }
                                }

                            }else{ $this->registrar(15, 0, 0, 'crear_giro() dominio existe'); }
                            $sql->free_result();
                            $sql->close();
                        }else{ $this->registrar(6, 0, 0, 'crear_giro() #7 '.htmlspecialchars($sql->error)); }
                    }else{ $this->registrar(6, 0, 0, 'crear_giro() #7 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, 0, 'crear_giro() #7 '.htmlspecialchars($this->con->error)); }
            }else{  }
        }else{ $this->registrar(4, 0, 0, 'crear_giro()'); }
        return $info;
    }
    private function eliminar_giro(){
        $info['tipo'] = "error";
        $info['titulo'] = "Error";
        $info['texto'] = "El Giro no pudo ser eliminado";
        $id_gir = $_POST['id'];
        if($this->admin == 1){
            if($sql = $this->con->prepare("SELECT * FROM fw_usuarios_giros_clientes WHERE id_user=? AND id_gir=?")){
                if($sql->bind_param("ii", $this->id_user, $id_gir)){
                    if($sql->execute()){
                        $res = $sql->get_result();
                        if($res->{"num_rows"} == 1 || $this->id_user == 1){
                            if($sqlugi = $this->con->prepare("UPDATE giros SET eliminado='1' WHERE id_gir=?")){
                                if($sqlugi->bind_param("i", $id_gir)){
                                    if($sqlugi->execute()){
                                        $info['tipo'] = "success";
                                        $info['titulo'] = "Eliminado";
                                        $info['texto'] = "Giro Eliminado";
                                        $info['reload'] = 1;
                                        $info['page'] = "msd/giros.php";
                                        $sqlugi->close();
                                    }else{ $this->registrar(6, 0, $id_gir, 'eliminar_giro() #1 '.htmlspecialchars($sqlugi->error)); }
                                }else{ $this->registrar(6, 0, $id_gir, 'eliminar_giro() #1 '.htmlspecialchars($sqlugi->error)); }
                            }else{ $this->registrar(6, 0, $id_gir, 'eliminar_giro() #1 '.htmlspecialchars($this->con->error)); }
                        }else{ $this->registrar(7, 0, $id_gir, 'eliminar_giro() XSS'); }
                        $sql->free_result();
                        $sql->close();
                    }else{ $this->registrar(6, 0, $id_gir, 'eliminar_giro() #2 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $id_gir, 'eliminar_giro() #2 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $id_gir, 'eliminar_giro() #2 '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(4, 0, $id_gir, 'eliminar_giro()'); }
        return $info;
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
                                            }else{ $this->registrar(6, $id_loc, 0, 'add_ses() #1 '.htmlspecialchars($sqlsma->error)); }
                                        }else{ $this->registrar(6, $id_loc, 0, 'add_ses() #1 '.htmlspecialchars($sqlsma->error)); }
                                    }else{ $this->registrar(6, $id_loc, 0, 'add_ses() #1 '.htmlspecialchars($this->con->error)); }
                                    $sqlloc->close();
                                }else{ $this->registrar(6, $id_loc, 0, 'add_ses() #2 '.htmlspecialchars($sqlloc->error)); }
                            }else{ $this->registrar(6, $id_loc, 0, 'add_ses() #2 '.htmlspecialchars($sqlloc->error)); }
                        }else{ $this->registrar(6, $id_loc, 0, 'add_ses() #2 '.htmlspecialchars($this->con->error)); }
                        $sql->free_result();
                        $sql->close();
                    }else{ $this->registrar(6, $id_loc, 0, 'add_ses() #3 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, $id_loc, 0, 'add_ses() #3 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, $id_loc, 0, 'add_ses() #3 '.htmlspecialchars($this->con->error)); }
        }else{  $this->registrar(1, 0, 0, 'add_ses()'); }
        return $info;
    }
    private function add_dns(){
        $info['tipo'] = "error";
        $info['titulo'] = "ERROR";
        $info['texto'] = "DNS no ha sido configurada";
        $id_gir = $_POST['id'];
        if($this->id_user == 1){
            if($sql = $this->con->prepare("UPDATE giros SET dns='1' WHERE id_gir=?")){
                if($sql->bind_param("i", $id_gir)){
                    if($sql->execute()){
                        $info['tipo'] = "success";
                        $info['titulo'] = "DNS";
                        $info['texto'] = "DNS configurada";
                        $info['reload'] = 1;
                        $info['page'] = "msd/panel.php";
                        $sql->close();
                    }else{ $this->registrar(6, 0, $id_gir, 'add_dns() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $id_gir, 'add_dns() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $id_gir, 'add_dns() '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(1, 0, $id_gir, 'add_dns()'); }
        return $info;
    }
    private function add_ssl(){
        $info['tipo'] = "error";
        $info['titulo'] = "SSL";
        $info['texto'] = "ssl no ha sido configurada";
        $id_gir = $_POST['id'];
        if($this->id_user == 1){
            if($sql = $this->con->prepare("UPDATE giros SET ssl='1' WHERE id_gir=?")){
                if($sql->bind_param("i", $id_gir)){
                    if($sql->execute()){
                        $info['tipo'] = "success";
                        $info['titulo'] = "SSL";
                        $info['texto'] = "ssl configurada";
                        $info['reload'] = 1;
                        $info['page'] = "msd/panel.php";
                        $sql->close();
                    }else{ $this->registrar(6, 0, $id_gir, 'add_ssl() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $id_gir, 'add_ssl() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $id_gir, 'add_ssl() '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(1, 0, $id_gir, 'add_ssl()'); }
        return $info;
    }
    private function ordercat(){
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            if(isset($this->id_cat) && is_numeric($this->id_cat) && $this->id_cat > 0){
                $values = $_POST['values'];
                for($i=0; $i<count($values); $i++){
                    if($sql = $this->con->prepare("UPDATE categorias SET orders='".$i."' WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?")){
                        if($sql->bind_param("iiii", $values[$i], $this->id_cat, $this->id_gir, $this->eliminado)){
                            if($sql->execute()){
                                $sql->close();
                            }else{ $this->registrar(6, 0, $this->id_gir, 'ordercat() '.htmlspecialchars($sql->error)); }
                        }else{ $this->registrar(6, 0, $this->id_gir, 'ordercat() '.htmlspecialchars($sql->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'ordercat() '.htmlspecialchars($this->con->error)); }
                }
                $this->con_cambios(null);
            }else{ $this->registrar(3, 0, $this->id_gir, 'ordercat()'); }
        }else{ $this->registrar(2, 0, 0, 'ordercat()'); }
    }
    private function orderpag(){
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $values = $_POST['values'];
            for($i=0; $i<count($values); $i++){
                if($sql = $this->con->prepare("UPDATE paginas SET orders='".$i."' WHERE id_pag=? AND id_gir=? AND eliminado=?")){
                    if($sql->bind_param("iii", $values[$i], $this->id_gir, $this->eliminado)){
                        if($sql->execute()){
                            $sql->close();
                        }else{ $this->registrar(6, 0, $this->id_gir, 'orderpag() '.htmlspecialchars($sql->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'orderpag() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'orderpag() '.htmlspecialchars($this->con->error)); }
            }
            $this->con_cambios(null);
        }else{ $this->registrar(2, 0, 0, 'orderpag()'); }
    }
    private function orderprods(){
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            if(isset($this->id_cat) && is_numeric($this->id_cat) && $this->id_cat > 0){
                $id_cae = $_POST['id_cae'];
                if($sql = $this->con->prepare("SELECT * FROM categorias WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?")){
                    if($sql->bind_param("iii", $id_cae, $this->id_cat, $this->id_gir, $this->eliminado)){
                        if($sql->execute()){
                            $res = $sql->get_result();
                            if($res->{"num_rows"} == 1){
                                $values = $_POST['values'];
                                for($i=0; $i<count($values); $i++){
                                    if($sqlcp = $this->con->prepare("UPDATE cat_pros SET orders='".$i."' WHERE id_pro=? AND id_cae=?")){
                                        if($sqlcp->bind_param("ii", $values[$i], $id_cae)){
                                            if($sqlcp->execute()){
                                                $sqlcp->close();
                                            }else{ $this->registrar(6, 0, $this->id_gir, 'orderprods() #1 '.htmlspecialchars($sqlcp->error)); }
                                        }else{ $this->registrar(6, 0, $this->id_gir, 'orderprods() #1 '.htmlspecialchars($sqlcp->error)); }
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'orderprods() #1 '.htmlspecialchars($this->con->error)); }
                                }
                                $this->con_cambios(null);
                            }
                            if($res->{"num_rows"} == 0){ 
                                $this->registrar(7, 0, $this->id_gir, 'orderprods() XSS');
                            }
                            $sql->free_result();
                            $sql->close();
                        }else{ $this->registrar(6, 0, $this->id_gir, 'orderprods() #2 '.htmlspecialchars($sql->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'orderprods() #2 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'orderprods() #2 '.htmlspecialchars($this->con->error)); }
            }else{ $this->registrar(3, 0, $this->id_gir, 'orderprods()'); }
        }else{ $this->registrar(2, 0, 0, 'orderprods()'); }
    }
    public function uploadfavIcon($filename){

        $filepath = '/var/www/html/restaurants/images/favicon/';
        $filename = ($filename !== null) ? $filename : $this->pass_generate(20) ;
        $file_formats = array("ICO");
        $name = $_FILES['file_image1']['name'];
        $size = $_FILES['file_image1']['size'];
        if(strlen($name)){
            $extension = substr($name, strrpos($name, '.') + 1);
            $extension2 = strtoupper($extension);
            if(in_array($extension2, $file_formats)){
                if($size < (20 * 1024)){
                    $imagename = $filename.".".$extension;
                    $imagename_new = $filename."x.".strtolower($extension);
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
                        $this->registrar(8, 0, $this->id_gir, 'Favicon no Upload');
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
        $filename = ($filename !== null) ? $filename : $this->pass_generate(20) ;
        $file_formats = array("PNG", "JPG", "JPEG");
        $name = $_FILES['file_image0']['name']; 
        $size = $_FILES['file_image0']['size'];
        if(strlen($name)){
            $extension = substr($name, strrpos($name, '.')+1);
            $extension2 = strtoupper($extension);
            if(in_array($extension2, $file_formats)) { 
                if($size < (20 * 1024)){
                    $imagename = $filename.".".$extension;
                    $imagename_new = $filename."x.".strtolower($extension);
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

        $filename = ($filename !== null) ? $filename : $this->pass_generate(20) ;
        $file_formats = array("JPG", "JPEG");
        $name = $_FILES['file_image0']['name'];
        $size = $_FILES['file_image0']['size'];
        if(strlen($name)){
            $extension = substr($name, strrpos($name, '.') + 1);
            $extension2 = strtoupper($extension);
            if(in_array($extension2, $file_formats)){
                if($size < (25 * 1024)){
                    $imagename = $filename.".".$extension;
                    $imagename_new = $filename."x.".strtolower($extension);
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
        $filename = ($filename !== null) ? $filename : $this->pass_generate(20) ;
        $file_formats = array("JPG", "JPEG");
        $name = $_FILES['file_image0']['name'];
        $size = $_FILES['file_image0']['size'];
        if(strlen($name)){
            $extension = substr($name, strrpos($name, '.') + 1);
            $extension2 = strtoupper($extension);
            if(in_array($extension2, $file_formats)){
                if($size < (25 * 1024)){
                    $imagename = $filename.".".$extension;
                    $imagename_new = $filename."x.".strtolower($extension);
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
    public function uploadProducto($filepath, $filename, $alto){

        $filename = ($filename !== null) ? $filename : $this->pass_generate(20) ;
        $file_formats = array("JPG", "JPEG");
        $name = $_FILES['file_image0']['name'];
        $size = $_FILES['file_image0']['size'];
        if(strlen($name)){
            $extension = substr($name, strrpos($name, '.') + 1);
            $extension2 = strtoupper($extension);
            if(in_array($extension2, $file_formats)){
                if($size < (25 * 1024)){
                    $imagename = $filename.".".$extension;
                    $imagename_new = $filename."x.".strtolower($extension);
                    $tmp = $_FILES['file_image0']['tmp_name'];
                    if(move_uploaded_file($tmp, $filepath.$imagename)){
                            $data = getimagesize($filepath.$imagename);
                            if($data['mime'] == "image/jpeg"){

                                if($data[0] == $data[1]){

                                    $width = 115;
                                    $height = 115;
                                    $destino = imagecreatetruecolor($width, $height);
                                    $origen = imagecreatefromjpeg($filepath.$imagename);
                                    imagecopy($destino, $origen, 0, 0, 0, 0, $width, $height);
                                    imagejpeg($destino, $filepath.$imagename_new);
                                    imagedestroy($destino);
                                    $info['tipo'] = 1;

                                }else{

                                    $width = 500;
                                    $height = $width * $alto / 100;
                                    $destino = imagecreatetruecolor($width, $height);
                                    $origen = imagecreatefromjpeg($filepath.$imagename);
                                    imagecopy($destino, $origen, 0, 0, 0, 0, $width, $height);
                                    imagejpeg($destino, $filepath.$imagename_new);
                                    imagedestroy($destino);
                                    $info['tipo'] = 2;

                                }

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
    public function uploadLocales($filepath, $filename){

        $width = 380;
        $height = 120;
        $filename = ($filename !== null) ? $filename : $this->pass_generate(20) ;
        $file_formats = array("JPG", "JPEG");
        $name = $_FILES['file_image0']['name'];
        $size = $_FILES['file_image0']['size'];
        if(strlen($name)){
            $extension = substr($name, strrpos($name, '.') + 1);
            $extension2 = strtoupper($extension);
            if(in_array($extension2, $file_formats)){
                if($size < (25 * 1024)){
                    $imagename = $filename.".".$extension;
                    $imagename_new = $filename."x.".strtolower($extension);
                    $tmp = $_FILES['file_image0']['tmp_name'];
                    if(move_uploaded_file($tmp, $filepath.$imagename)){
                            $data = getimagesize($filepath.$imagename);
                            if($data['mime'] == "image/jpeg"){
                                //$height = $width * $alto / 100;
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
    public function uploadRetiro($filepath, $filename){

        $width = 380;
        $height = 120;
        $filename = ($filename !== null) ? $filename : $this->pass_generate(20) ;
        $file_formats = array("JPG", "JPEG");
        $name = $_FILES['file_image2']['name'];
        $size = $_FILES['file_image2']['size'];
        if(strlen($name)){
            $extension = substr($name, strrpos($name, '.') + 1);
            $extension2 = strtoupper($extension);
            if(in_array($extension2, $file_formats)){
                if($size < (25 * 1024)){
                    $imagename = $filename.".".$extension;
                    $imagename_new = $filename."x.".strtolower($extension);
                    $tmp = $_FILES['file_image0']['tmp_name'];
                    if(move_uploaded_file($tmp, $filepath.$imagename)){
                            $data = getimagesize($filepath.$imagename);
                            if($data['mime'] == "image/jpeg"){
                                //$height = $width * $alto / 100;
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
    public function uploadDespacho($filepath, $filename){

        $width = 380;
        $height = 120;
        $filename = ($filename !== null) ? $filename : $this->pass_generate(20) ;
        $file_formats = array("JPG", "JPEG");
        $name = $_FILES['file_image3']['name'];
        $size = $_FILES['file_image3']['size'];
        if(strlen($name)){
            $extension = substr($name, strrpos($name, '.') + 1);
            $extension2 = strtoupper($extension);
            if(in_array($extension2, $file_formats)){
                if($size < (25 * 1024)){
                    $imagename = $filename.".".$extension;
                    $imagename_new = $filename."x.".strtolower($extension);
                    $tmp = $_FILES['file_image0']['tmp_name'];
                    if(move_uploaded_file($tmp, $filepath.$imagename)){
                            $data = getimagesize($filepath.$imagename);
                            if($data['mime'] == "image/jpeg"){
                                //$height = $width * $alto / 100;
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

        $filename = ($filename !== null) ? $filename : $this->pass_generate(20) ;
        $file_formats = array("JPG", "JPEG");
        $name = $_FILES['file_image0']['name']; // filename to get file's extension
        $size = $_FILES['file_image0']['size'];
        if(strlen($name)){
            $extension = substr($name, strrpos($name, '.') + 1);
            $extension2 = strtoupper($extension);
            if(in_array($extension2, $file_formats)){
                if($size < (200 * 1024)){
                    $imagename = $filename.".".$extension;
                    $imagename_new = $filename."x.".strtolower($extension);
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
            $ver_inicio = $_POST['ver_inicio'];
            if($sql = $this->con->prepare("UPDATE giros SET ver_inicio=?, inicio_html=? WHERE id_gir=? AND eliminado=?")){
                if($sql->bind_param("isii", $ver_inicio, $texto, $this->id_gir, $this->eliminado)){
                    if($sql->execute()){
                        $info['op'] = 1;
                        $info['mensaje'] = "Pagina de Inicio modificado exitosamente";
                        $info['reload'] = 1;
                        if($_POST['seguir'] == 0){ $info['page'] = 'msd/ver_giro.php'; }else{ $info['page'] = 'msd/configurar_pag_inicio.php'; }
                        $sql->close();
                        $this->con_cambios(null);
                    }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_inicio() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_inicio() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_inicio() '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'configurar_inicio()'); }
        return $info;
    }
    private function configurar_footer(){
        $info['op'] = 2;
        $info['mensaje'] = "Se produjo un error";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $texto = $_POST['html'];
            if($sql = $this->con->prepare("UPDATE giros SET footer_html=? WHERE id_gir=? AND eliminado=?")){
                if($sql->bind_param("sii", $texto, $this->id_gir, $this->eliminado)){
                    if($sql->execute()){
                        $info['op'] = 1;
                        $info['mensaje'] = "Footer modificado exitosamente";
                        $info['reload'] = 1;
                        if($_POST['seguir'] == 0){ $info['page'] = 'msd/ver_giro.php'; }else{ $info['page'] = 'msd/configurar_footer.php'; }
                        $sql->close();
                        $this->con_cambios(null);
                    }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_footer() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_footer() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_footer() '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'configurar_footer()'); }
        return $info;
    }
    private function con_cambios($id_gir){

        $id = ($id_gir === null) ? $this->id_gir : $id_gir ;
        if($sql = $this->con->prepare("SELECT t1.dns, t1.ssl, t2.ip, t1.dominio FROM giros t1, server t2 WHERE t1.id_gir=? AND t1.eliminado=? AND t1.id_ser=t2.id_ser")){
            if($sql->bind_param("ii", $id, $this->eliminado)){
                if($sql->execute()){
                    $data = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                    if($data["dns"] == 0){
                        $url = "http://".$data["ip"]."/?url=".$data["dominio"];
                    }
                    if($data["dns"] == 1){
                        if($data["ssl"] == 0){
                            $url = "http://".$data["dominio"];
                        }
                        if($data["ssl"] == 1){
                            $url = "https://".$data["dominio"];
                        }
                    }
                    $send['accion'] = "xS3w1Dm8Po87Wltd";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                    if(!curl_errno($ch)){
                        curl_exec($ch);
                        curl_close($ch);
                    }else{
                        $this->registrar(15, 0, $this->id_gir, 'con_cambios() curl error');
                    }
                    
                    $sql->free_result();
                    $sql->close();
                }else{ $this->registrar(6, 0, $this->id_gir, 'con_cambios() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'con_cambios() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'con_cambios() '.htmlspecialchars($this->con->error)); }

    }
    private function solicitar_ssl(){
        $info['op'] = 2;
        $info['mensaje'] = "Error";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $solicitud = $_POST["solicitud"];
            if($solicitud == 0){
                if($sql = $this->con->prepare("UPDATE giros SET solicitar_ssl='0' WHERE id_gir=? AND eliminado=?")){
                    if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                        if($sql->execute()){
                            $info['op'] = 1;
                            $info['mensaje'] = "Solicitud enviada con exito";
                            $info['reload'] = 1;
                            $info['page'] = "msd/ver_giro.php";
                            $sql->close();
                        }else{ $this->registrar(6, 0, $this->id_gir, 'solicitar_ssl() #1 '.htmlspecialchars($sql->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'solicitar_ssl() #1 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'solicitar_ssl() #1 '.htmlspecialchars($this->con->error)); }
            }
            if($solicitud == 1){
                if($sql = $this->con->prepare("UPDATE giros SET solicitar_ssl='1' WHERE id_gir=? AND eliminado=?")){
                    if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                        if($sql->execute()){
                            $info['op'] = 1;
                            $info['mensaje'] = "Solicitud enviada con exito";
                            $info['reload'] = 1;
                            $info['page'] = "msd/ver_giro.php";
                            $sql->close();
                        }else{ $this->registrar(6, 0, $this->id_gir, 'solicitar_ssl() #2 '.htmlspecialchars($sql->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'solicitar_ssl() #2 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'solicitar_ssl() #2 '.htmlspecialchars($this->con->error)); }
            }
        }else{ $this->registrar(2, 0, 0, 'solicitar_ssl()'); }
        return $info;
    }
    private function configurar_estilos(){
        $info['op'] = 2;
        $info['mensaje'] = "Error";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $font_family = $_POST['font-family'];
            $font_css = $_POST['font-css'];
            $css_page = $_POST['css_page'];
            $css_color = $_POST['css_color'];
            $css_modal = $_POST['css_modal'];
            if($sql = $this->con->prepare("UPDATE giros SET style_modal=?, style_color=?, style_page=?, font_css=?, font_family=? WHERE id_gir=? AND eliminado=?")){
                if($sql->bind_param("sssssii", $css_modal, $css_color, $css_page, $font_css, $font_family, $this->id_gir, $this->eliminado)){
                    if($sql->execute()){
                        $info['op'] = 1;
                        $info['mensaje'] = "Configuracion de Estilos Modificado Exitosamente";
                        $info['reload'] = 1;
                        $info['page'] = "msd/ver_giro.php";
                        $sql->close();
                        $this->con_cambios(null);
                    }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_estilos() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_estilos() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_estilos() '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'configurar_estilos()'); }
        return $info;
    }
    private function configurar_giro(){
        $info['op'] = 2;
        $info['mensaje'] = "Error";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            if($sql = $this->con->prepare("SELECT dominio FROM giros WHERE id_gir=? AND eliminado=?")){
                if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                    if($sql->execute()){
                        $dominio = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]['dominio'];
                        $foto_logo = $this->uploadLogo($dominio);
                        $foto_favicon = $this->uploadfavIcon($dominio);
                        $foto_retiro = $this->uploadRetiro('/var/www/html/restaurants/images/categorias/', null);
                        $foto_despacho = $this->uploadDespacho('/var/www/html/restaurants/images/categorias/', null);
                        if($foto_retiro['op'] == 1){
                            if($sqlc = $this->con->prepare("UPDATE giros SET foto_retiro='".$foto_retiro["image"]."' WHERE id_gir=? AND eliminado=?")){
                                if($sqlc->bind_param("ii", $this->id_gir, $this->eliminado)){
                                    if($sqlc->execute()){
                                        $sqlc->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() foto_retiro '.htmlspecialchars($sqlc->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() foto_retiro '.htmlspecialchars($sqlc->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() foto_retiro '.htmlspecialchars($this->con->error)); }
                        }
                        if($foto_despacho['op'] == 1){
                            if($sqld = $this->con->prepare("UPDATE giros SET foto_despacho='".$foto_despacho["image"]."' WHERE id_gir=? AND eliminado=?")){
                                if($sqld->bind_param("ii", $this->id_gir, $this->eliminado)){
                                    if($sqld->execute()){
                                        $sqld->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() foto_despacho '.htmlspecialchars($sqld->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() foto_despacho '.htmlspecialchars($sqld->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() foto_despacho '.htmlspecialchars($this->con->error)); }
                        }
                        if($foto_logo['op'] == 1){
                            if($sqla = $this->con->prepare("UPDATE giros SET logo='".$dominio.".png' WHERE id_gir=? AND eliminado=?")){
                                if($sqla->bind_param("ii", $this->id_gir, $this->eliminado)){
                                    if($sqla->execute()){
                                        $sqla->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() logo '.htmlspecialchars($sqla->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() logo '.htmlspecialchars($sqla->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() logo '.htmlspecialchars($this->con->error)); }
                        }
                        if($foto_favicon['op'] == 1){
                            if($sqlb = $this->con->prepare("UPDATE giros SET favicon='".$dominio.".ico' WHERE id_gir=? AND eliminado=?")){
                                if($sqlb->bind_param("ii", $this->id_gir, $this->eliminado)){
                                    if($sqlb->execute()){
                                        $sqlb->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() icono '.htmlspecialchars($sqlb->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() icono '.htmlspecialchars($sqlb->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() icono '.htmlspecialchars($this->con->error)); }
                        }

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
                        $alto_pro = $_POST['alto_pro'];
                        $tiempo_aviso = $_POST['tiempo_aviso'];

                        if($sqlgir = $this->con->prepare("UPDATE giros SET tiempo_aviso=?, alto=?, alto_pro=?, pedido_gengibre=?, pedido_wasabi=?, pedido_soya=?, pedido_teriyaki=?, pedido_palitos=?, pedido_comentarios=?, titulo=?, pedido_minimo=?, mapcode=?, estado=?, pedido_01_titulo=?, pedido_01_subtitulo=?, pedido_02_titulo=?, pedido_02_subtitulo=?, pedido_03_titulo=?, pedido_03_subtitulo=?, pedido_04_titulo=?, pedido_04_subtitulo=? WHERE id_gir=? AND eliminado=?")){
                            if($sqlgir->bind_param("iiissssssssssssssssssii", $tiempo_aviso, $alto, $alto_pro, $pedido_gengibre, $pedido_wasabi, $pedido_soya, $pedido_teriyaki, $pedido_palitos, $pedido_comentarios, $titulo, $pedido_minimo, $mapcode, $estados, $pedido_01_titulo, $pedido_01_subtitulo, $pedido_02_titulo, $pedido_02_subtitulo, $pedido_03_titulo, $pedido_03_subtitulo, $pedido_04_titulo, $pedido_04_subtitulo, $this->id_gir, $this->eliminado)){
                                if($sqlgir->execute()){
                                    $info['op'] = 1;
                                    $info['mensaje'] = "Configuracion Base Modificado Exitosamente";
                                    $info['reload'] = 1;
                                    $info['page'] = "msd/ver_giro.php";
                                    $sqlgir->close();
                                    $this->con_cambios(null);
                                }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() datos '.htmlspecialchars($sqlgir->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() datos '.htmlspecialchars($sqlgir->error)); }
                        }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() datos '.htmlspecialchars($this->con->error)); }
                        $sql->free_result();
                        $sql->close();

                    }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() dominio '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() dominio '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_giro() dominio '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'configurar_giro()'); }
        return $info;
    }
    public function get_alto(){
        if($sql = $this->con->prepare("SELECT alto FROM giros WHERE id_gir=? AND eliminado=?")){
            if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $alto = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]["alto"];
                    $sql->free_result();
                    $sql->close();
                    return $alto;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_alto() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_alto() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_alto() '.htmlspecialchars($this->con->error)); }
    }
    public function list_arbol_cats_prods(){
        if($sql = $this->con->prepare("SELECT t1.id_cae, t1.nombre as cat_nombre, t1.parent_id, t2.id_pro, t3.nombre as prod_nombre FROM categorias t1 LEFT JOIN cat_pros t2 ON t1.id_cae=t2.id_cae LEFT JOIN productos t3 ON t2.id_pro=t3.id_pro WHERE t1.id_cat=? AND t1.eliminado=? AND t1.tipo=?")){
            if($sql->bind_param("iii", $this->id_cat, $this->eliminado, $this->eliminado)){
                if($sql->execute()){
                    $data = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $data;
                }else{ $this->registrar(6, 0, $this->id_gir, 'list_arbol_cats_prods() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'list_arbol_cats_prods() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'list_arbol_cats_prods() '.htmlspecialchars($this->con->error)); }
    }
    private function configurar_categoria(){
        $info['op'] = 2;
        $info['mensaje'] = "Error";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            if(isset($this->id_cat) && is_numeric($this->id_cat) && $this->id_cat > 0){
                $id_cae = $_POST['id_cae'];
                if($sql = $this->con->prepare("SELECT * FROM categorias WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?")){
                    if($sql->bind_param("iiii", $id_cae, $this->id_cat, $this->id_gir, $this->eliminado)){
                        if($sql->execute()){
                            $categoria = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                            $alto = $this->get_alto();
                            if($categoria['parent_id'] == 0){
                                $image = $this->uploadCategoria('/var/www/html/restaurants/images/categorias/', null, $alto);
                            }
                            if($categoria['parent_id'] > 0){
                                $image = $this->uploadsubCategoria('/var/www/html/restaurants/images/categorias/', null);
                            }
                            if($image['op'] == 1){
                                @unlink('/var/www/html/restaurants/images/categorias/'.$categoria['image']);
                                if($sqlg = $this->con->prepare("UPDATE categorias SET image='".$image["image"]."' WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?")){
                                    if($sqlg->bind_param("iiii", $id_cae, $this->id_cat, $this->id_gir, $this->eliminado)){
                                        if($sqlg->execute()){
                                            $sqlg->close();
                                        }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_categoria() image'.htmlspecialchars($sqlg->error)); }
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_categoria() image'.htmlspecialchars($sqlg->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_categoria() image'.htmlspecialchars($sqlg->error)); }
                            }
                            $parent_id = $_POST['parent_id'];
                            $mostar_prods = $_POST['mostrar_prods'];
                            $ocultar = $_POST['ocultar'];
                            $detalle_prods = $_POST['detalle_prods'];
                            $degradado = $_POST['degradado'];
                            if($sqlmc = $this->con->prepare("UPDATE categorias SET ocultar=?, mostrar_prods=?, degradado=?, detalle_prods=? WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?")){
                                if($sqlmc->bind_param("iissiiii", $ocultar, $mostar_prods, $degradado, $detalle_prods, $id_cae, $this->id_cat, $this->id_gir, $this->eliminado)){
                                    if($sqlmc->execute()){
                                        $info['op'] = 1;
                                        $info['mensaje'] = "Configuracion modificado exitosamente";
                                        $info['reload'] = 1;
                                        $info['page'] = "msd/categorias.php?parent_id=".$parent_id;
                                        $this->con_cambios(null);
                                        $sqlmc->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_categoria() update'.htmlspecialchars($sqlmc->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_categoria() update'.htmlspecialchars($sqlmc->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_categoria() update'.htmlspecialchars($this->con->error)); }
                            $sql->free_result();
                            $sql->close();
                        }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_categoria() select'.htmlspecialchars($sql->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_categoria() select'.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_categoria() select'.htmlspecialchars($this->con->error)); }
            }else{ $this->registrar(3, 0, $this->id_gir, 'configurar_categoria()'); }
        }else{ $this->registrar(2, 0, 0, 'configurar_categoria()'); }
        return $info;
    }
    private function configurar_local(){
        $info['op'] = 2;
        $info['mensaje'] = "Error";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $t_retiro = $_POST['t_retiro'];
            $t_despacho = $_POST['t_despacho'];
            $sonido = $_POST['sonido'];
            $pos = $_POST['pos'];
            $activar_envio = $_POST['activar_envio'];
            $id_loc = $_POST['id_loc'];
            if($sqlloc = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND id_gir=? AND eliminado=?")){
                if($sqlloc->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado)){
                    if($sqlloc->execute()){
                        $res = $sqlloc->get_result();
                        if($res->{"num_rows"} == 1){
                            $loc_image = $res->fetch_all(MYSQLI_ASSOC)[0];
                            $image = $this->uploadLocales('/var/www/html/restaurants/images/categorias/', null);
                            if($image['op'] == 1){
                                @unlink('/var/www/html/restaurants/images/categorias/'.$loc_image['image']);
                                if($sqlg = $this->con->prepare("UPDATE locales SET image='".$image["image"]."' WHERE id_loc=? AND id_gir=? AND eliminado=?")){
                                    if($sqlg->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado)){
                                        if($sqlg->execute()){
                                            $sqlg->close();
                                        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'configurar_local() image'.htmlspecialchars($sqlg->error)); }
                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'configurar_local() image'.htmlspecialchars($sqlg->error)); }
                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'configurar_local() image'.htmlspecialchars($sqlg->error)); }
                            }
                            if($sql = $this->con->prepare("UPDATE locales SET activar_envio=?, pos=?, sonido=?, t_retiro=?, t_despacho=? WHERE id_loc=? AND id_gir=?")){
                                if($sql->bind_param("iisiiii", $activar_envio, $pos, $sonido, $t_retiro, $t_despacho, $id_loc, $this->id_gir)){
                                    if($sql->execute()){
                                        $info['op'] = 1;
                                        $info['mensaje'] = "Local editado exitosamente";
                                        $info['reload'] = 1;
                                        $info['page'] = "msd/locales.php";
                                        $this->locales_giro();
                                        $sql->close();
                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'configurar_local() #1 '.htmlspecialchars($sql->error)); }
                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'configurar_local() #1 '.htmlspecialchars($sql->error)); }
                            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'configurar_local() #1 '.htmlspecialchars($this->con->error)); }
                        }
                        if($res->{"num_rows"} == 0){ $this->registrar(7, $id_loc, $this->id_gir, 'configurar_local()'); }
                        $sqlloc->free_result();
                        $sqlloc->close();
                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'configurar_local() #2 '.htmlspecialchars($sqlloc->error)); }
                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'configurar_local() #2 '.htmlspecialchars($sqlloc->error)); }
            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'configurar_local() #2 '.htmlspecialchars($sqlloc->error)); }
        }else{ $this->registrar(2, 0, 0, 'configurar_local()'); }
        return $info;
    }
    private function get_preguntas(){
        if($sql = $this->con->prepare("SELECT id_pre, nombre, mostrar FROM preguntas WHERE id_cat=? AND id_gir=? AND eliminado=?")){
            if($sql->bind_param("iii", $this->id_cat, $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_preguntas() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_preguntas() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_preguntas() '.htmlspecialchars($this->con->error)); }
    }
    private function configurar_producto(){

        $info['op'] = 2;
        $info['mensaje'] = "Error";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id = $_POST['id'];
            $id_pro = $_POST['id_pro'];
            $disponible = $_POST['disponible'];
            $parent_id = $_POST['parent_id'];
            $alto = $this->get_alto();
            $image = $this->uploadProducto('/var/www/html/restaurants/images/productos/', null, $alto);
            if($image['op'] == 1){
                if($sql = $this->con->prepare("SELECT image FROM productos WHERE id_pro=? AND id_gir=? AND id_gir=? AND eliminado=?")){
                    if($sql->bind_param("iii", $id_pro, $this->id_gir, $this->eliminado)){
                        if($sql->execute()){
                            $pro_image = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]["image"];
                            @unlink('/var/www/html/restaurants/images/productos/'.$pro_image);
                            if($sqlpro = $this->con->prepare("UPDATE productos SET image=?, tipo=? WHERE id_pro=? AND id_gir=? AND eliminado=?")){
                                if($sqlpro->bind_param("siiii", $image["image"], $image["tipo"], $id_pro, $this->id_gir, $this->eliminado)){
                                    if($sqlpro->execute()){
                                        $sqlpro->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_producto() update image '.htmlspecialchars($sqlpro->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_producto() update image '.htmlspecialchars($sqlpro->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_producto() update image '.htmlspecialchars($this->con->error)); }
                            $sql->free_result();
                            $sql->close();
                        }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_producto() select image '.htmlspecialchars($sql->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_producto() select image '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_producto() select image '.htmlspecialchars($this->con->error)); }
            }
            $list = $this->get_preguntas();
            for($i=0; $i<count($list); $i++){
                $pre = $_POST['pregunta-'.$list[$i]['id_pre']];
                if($pre == 0){
                    if($sqldpr = $this->con->prepare("DELETE FROM preguntas_productos WHERE id_pro=? AND id_pre=?")){
                        if($sqldpr->bind_param("ii", $id_pro, $list[$i]["id_pre"])){
                            if($sqldpr->execute()){
                                $sqldpr->close();
                            }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_producto() preguntas #1 '.htmlspecialchars($sqldpr->error)); }
                        }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_producto() preguntas #1 '.htmlspecialchars($sqldpr->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_producto() preguntas #1 '.htmlspecialchars($this->con->error)); }
                }
                if($pre == 1){
                    if($sqlipr = $this->con->prepare("INSERT INTO preguntas_productos (id_pro, id_pre) VALUES (?, ?)")){
                        if($sqlipr->bind_param("ii", $id_pro, $list[$i]["id_pre"])){
                            if($sqlipr->execute()){
                                $sqlipr->close();
                            }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_producto() preguntas #2 '.htmlspecialchars($sqlipr->error)); }
                        }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_producto() preguntas #2 '.htmlspecialchars($sqlipr->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_producto() preguntas #2 '.htmlspecialchars($this->con->error)); }
                }
            }

            if($sqlprod = $this->con->prepare("UPDATE productos SET disponible=? WHERE id_pro=? AND id_gir=? AND eliminado=?")){
                if($sqlprod->bind_param("iiii", $disponible, $id_pro, $this->id_gir, $this->eliminado)){
                    if($sqlprod->execute()){
                        $info['op'] = 1;
                        $info['mensaje'] = "Configuracion Modificada Exitosamente";
                        $info['reload'] = 1;
                        $info['page'] = "msd/crear_productos.php?id=".$id."&parent_id=".$parent_id;
                        $this->con_cambios(null);
                        $sqlprod->close();
                    }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_producto() update image '.htmlspecialchars($sqlprod->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_producto() update image '.htmlspecialchars($sqlprod->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'configurar_producto() update image '.htmlspecialchars($this->con->error)); }
            
        }else{ $this->registrar(2, 0, 0, 'configurar_producto()'); }
        return $info;
        
    }
    private function eliminar_repartidor(){
        $info['tipo'] = "error";
        $info['titulo'] = "Error";
        $info['texto'] = "Repartidor no pudo ser eliminado";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id = explode("/", $_POST['id']);
            if($sql = $this->con->prepare("UPDATE motos SET eliminado='1' WHERE id_mot=? AND id_gir=?")){
                if($sql->bind_param("ii", $id[1], $this->id_gir)){
                    if($sql->execute()){
                        $info['tipo'] = "success";
                        $info['titulo'] = "Eliminado";
                        $info['texto'] = "Repartidor Eliminado";
                        $info['reload'] = 1;
                        $info['page'] = "msd/crear_repartidor.php?id_loc=".$id[0]."&nombre=".$id[2];
                        $sql->close();
                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_repartidor() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_repartidor() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_repartidor() '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'eliminar_repartidor()'); }
        return $info;
    }
    private function eliminar_tramos(){
        
        $info['tipo'] = "error";
        $info['titulo'] = "Error";
        $info['texto'] = "Tramo no pudo ser eliminado";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id = explode("/", $_POST['id']);
            if($sql = $this->con->prepare("SELECT * FROM locales_tramos t1, locales t2 WHERE t1.id_lot=? AND t1.id_loc=t2.id_loc AND t2.id_gir=?")){
                if($sql->bind_param("ii", $id[1], $this->id_gir)){
                    if($sql->execute()){
                        $res = $sql->get_result();
                        if($res->{"num_rows"} == 1){
                            if($sqlugi = $this->con->prepare("UPDATE locales_tramos SET eliminado='1' WHERE id_lot=?")){
                                if($sqlugi->bind_param("i", $id[1])){
                                    if($sqlugi->execute()){
                                        $info['tipo'] = "success";
                                        $info['titulo'] = "Eliminado";
                                        $info['texto'] = "Tramo Eliminado";
                                        $info['reload'] = 1;
                                        $info['page'] = "msd/zonas_locales.php?id_loc=".$id[0];
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_tramos() '.htmlspecialchars($sqlugi->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_tramos() '.htmlspecialchars($sqlugi->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_tramos() '.htmlspecialchars($this->con->error)); }
                        }
                        if($res->{"num_rows"} == 0){ $this->registrar(7, 0, $this->id_gir, 'eliminar_tramos()'); }
                        $sql->free_result();
                        $sql->close();
                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_tramos() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_tramos() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_tramos() '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'del tramo'); }
        return $info;
        
    }
    private function crear_horario(){
        $info['op'] = 2;
        $info['mensaje'] = "Error:";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id_loc = $_POST['id_loc'];
            if($sql = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND id_gir=? AND eliminado=?")){
                if($sql->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado)){
                    if($sql->execute()){
                        $res = $sql->get_result();
                        if($res->{"num_rows"} == 1){
                            
                            $tipo = $_POST['tipo'];
                            $id_hor = $_POST['id'];
                            $dia_ini = $_POST['dia_ini'];
                            $dia_fin = $_POST['dia_fin'];
                            $hora_ini = $_POST['hora_ini'];
                            $min_ini = $_POST['min_ini'];
                            $hora_fin = $_POST['hora_fin'];
                            $min_fin = $_POST['min_fin'];
                            $loc_nombre = $_POST['loc_nombre'];

                            if($id_hor == 0){
                                if($sqligir = $this->con->prepare("INSERT INTO horarios (dia_ini, dia_fin, hora_ini, hora_fin, min_ini, min_fin, tipo, id_loc, id_gir, eliminado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")){
                                    if($sqligir->bind_param("iiiiiiiiii", $dia_ini, $dia_fin, $hora_ini, $hora_fin, $min_ini, $min_fin, $tipo, $id_loc, $this->id_gir, $this->eliminado)){
                                        if($sqligir->execute()){
                                            $info['op'] = 1;
                                            $info['mensaje'] = "Horario creado exitosamente";
                                            $info['reload'] = 1;
                                            $info['page'] = "msd/crear_horario.php?id_loc=".$id_loc."&nombre=".$loc_nombre;
                                            $sqligir->close();
                                            $this->con_cambios(null);
                                        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #1 '.htmlspecialchars($sqligir->error)); }
                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #1 '.htmlspecialchars($sqligir->error)); }
                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #1 '.htmlspecialchars($this->con->error)); }
                            }
                            if($id_hor > 0){
                                if($sqligir = $this->con->prepare("UPDATE horarios SET dia_ini=?, dia_fin=?, hora_ini=?, hora_fin=?, min_ini=?, min_fin=?, tipo=? WHERE id_hor=? AND id_loc=? AND id_gir=? AND eliminado=?")){
                                    if($sqligir->bind_param("iiiiiiiiiii", $dia_ini, $dia_fin, $hora_ini, $hora_fin, $min_ini, $min_fin, $tipo, $id_hor, $id_loc, $this->id_gir, $this->eliminado)){
                                        if($sqligir->execute()){
                                            $info['op'] = 1;
                                            $info['mensaje'] = "Horario modificado exitosamente";
                                            $info['reload'] = 1;
                                            $info['page'] = "msd/crear_horario.php?id_loc=".$id_loc."&nombre=".$loc_nombre;
                                            $sqligir->close();
                                            $this->con_cambios(null);
                                        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #2  '.htmlspecialchars($sqligir->error)); }
                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #2  '.htmlspecialchars($sqligir->error)); }
                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #2 '.htmlspecialchars($this->con->error)); }
                            }

                            if($sqlre = $this->con->prepare("SELECT * FROM horarios WHERE id_gir=? AND eliminado=? AND (tipo='0' OR tipo='1')")){
                                if($sqlre->bind_param("ii", $this->id_gir, $this->eliminado)){
                                    if($sqlre->execute()){
                                        $resre = $sqlre->get_result();
                                        if($resre->{"num_rows"} == 0){
                                            if($sqlure = $this->con->prepare("UPDATE giros SET retiro_local='0' WHERE id_gir=? AND eliminado=?")){
                                                if($sqlure->bind_param("ii", $this->id_gir, $this->eliminado)){
                                                    if($sqlure->execute()){
                                                        $sqlure->close();
                                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #3 '.htmlspecialchars($sqlure->error)); }
                                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #3 '.htmlspecialchars($sqlure->error)); }
                                            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #3 '.htmlspecialchars($this->con->error)); }
                                        }
                                        if($resre->{"num_rows"} > 0){
                                            if($sqlure = $this->con->prepare("UPDATE giros SET retiro_local='1' WHERE id_gir=? AND eliminado=?")){
                                                if($sqlure->bind_param("ii", $this->id_gir, $this->eliminado)){
                                                    if($sqlure->execute()){
                                                        $sqlure->close();
                                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #4 '.htmlspecialchars($sqlure->error)); }
                                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #4 '.htmlspecialchars($sqlure->error)); }
                                            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #4 '.htmlspecialchars($this->con->error)); }
                                        }
                                        $sqlre->free_result();
                                        $sqlre->close();
                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #5 '.htmlspecialchars($sqlre->error)); }
                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #5 '.htmlspecialchars($sqlre->error)); }
                            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #5 '.htmlspecialchars($this->con->error)); }
                            
                            if($sqlde = $this->con->prepare("SELECT * FROM horarios WHERE id_gir=? AND eliminado=? AND (tipo='0' OR tipo='2')")){
                                if($sqlde->bind_param("ii", $this->id_gir, $this->eliminado)){
                                    if($sqlde->execute()){
                                        $resde = $sqlde->get_result();
                                        if($resde->{"num_rows"} == 0){
                                            if($sqlude = $this->con->prepare("UPDATE giros SET despacho_domicilio='0' WHERE id_gir=? AND eliminado=?")){
                                                if($sqlude->bind_param("ii", $this->id_gir, $this->eliminado)){
                                                    if($sqlude->execute()){
                                                        $sqlude->close();
                                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #6 '.htmlspecialchars($sqlude->error)); }
                                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #6 '.htmlspecialchars($sqlude->error)); }
                                            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #6 '.htmlspecialchars($this->con->error)); }
                                        }
                                        if($resde->{"num_rows"} > 0){
                                            if($sqlude = $this->con->prepare("UPDATE giros SET despacho_domicilio='1' WHERE id_gir=? AND eliminado=?")){
                                                if($sqlude->bind_param("ii", $this->id_gir, $this->eliminado)){
                                                    if($sqlude->execute()){
                                                        $sqlude->close();
                                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #7 '.htmlspecialchars($sqlude->error)); }
                                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #7 '.htmlspecialchars($sqlude->error)); }
                                            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #7 '.htmlspecialchars($this->con->error)); }
                                        }
                                        $sqlde->close();
                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #8 '.htmlspecialchars($sqlde->error)); }
                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #8 '.htmlspecialchars($sqlde->error)); }
                            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #8 '.htmlspecialchars($this->con->error)); }
                            
                        }else{ $this->registrar(7, $id_loc, $this->id_gir, 'crear_horario()'); }
                        $sql->free_result();
                        $sql->close();
                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #9 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #9 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_horario() #9 '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'crear_horario()'); }
        return $info;
    }
    private function crear_repartidor(){
        
        $info['op'] = 2;
        $info['mensaje'] = "Error";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $tipo = $_POST['tipo'];
            $id_loc = $_POST['id_loc'];
            $loc_nombre = $_POST['loc_nombre'];
            if($sql = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND id_gir=? AND eliminado=?")){
                if($sql->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado)){
                    if($sql->execute()){
                        $res = $sql->get_result();
                        if($res->{"num_rows"} == 1){
                            if($tipo == 0){
                                $nombre = $_POST['nombre'];
                                $correo = $_POST['correo'];
                                $uid = $this->pass_generate(20);
                                if($sqlimo = $this->con->prepare("INSERT INTO motos (nombre, correo, uid, id_gir) VALUES (?, ?, ?, ?)")){
                                    if($sqlimo->bind_param("sssi", $nombre, $correo, $uid, $this->id_gir)){
                                        if($sqlimo->execute()){
                                            $id_mot = $this->con->insert_id;
                                            if($sqliml = $this->con->prepare("INSERT INTO motos_locales (id_mot, id_loc) VALUES (?, ?)")){
                                                if($sqliml->bind_param("ii", $id_mot, $id_loc)){
                                                    if($sqliml->execute()){
                                                        $info['op'] = 1;
                                                        $info['mensaje'] = "Repartidor ingresado exitosamente";
                                                        $info['reload'] = 1;
                                                        $info['page'] = "msd/crear_repartidor.php?id_loc=".$id_loc."&nombre=".$loc_nombre;
                                                        $sqliml->close();
                                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_repartidor() #1 '.htmlspecialchars($sqliml->error)); }
                                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_repartidor() #1 '.htmlspecialchars($sqliml->error)); }
                                            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_repartidor() #1 '.htmlspecialchars($this->con->error)); }
                                            $sqlimo->close();
                                        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_repartidor() #2 '.htmlspecialchars($sqlimo->error)); }
                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_repartidor() #2 '.htmlspecialchars($sqlimo->error)); }
                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_repartidor() #2 '.htmlspecialchars($this->con->error)); }
                            }
                            if($tipo == 1){
                                $id_mot = $_POST['repartidor'];
                                if($sqlmot = $this->con->prepare("SELECT * FROM motos WHERE id_mot=? AND id_gir=? AND eliminado=?")){
                                    if($sqlmot->bind_param("iii", $id_mot, $this->id_gir, $this->eliminado)){
                                        if($sqlmot->execute()){
                                            $resmot = $sqlmot->get_result();
                                            if($resmot->{"num_rows"} == 1){
                                                if($sqliml = $this->con->prepare("INSERT INTO motos_locales (id_mot, id_loc) VALUES (?, ?)")){
                                                    if($sqliml->bind_param("ii", $id_mot, $id_loc)){
                                                        if($sqliml->execute()){
                                                            $info['op'] = 1;
                                                            $info['mensaje'] = "Repartidor modificado exitosamente";
                                                            $info['reload'] = 1;
                                                            $info['page'] = "msd/crear_repartidor.php?id_loc=".$id_loc."&nombre=".$loc_nombre;
                                                            $sqliml->close();
                                                        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_repartidor() #3 '.htmlspecialchars($sqliml->error)); }
                                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_repartidor() #3 '.htmlspecialchars($sqliml->error)); }
                                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_repartidor() #3 '.htmlspecialchars($this->con->error)); }
                                            }
                                            if($resmot->{"num_rows"} == 0){ /*ERROR*/ }
                                        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_repartidor() #4 '.htmlspecialchars($sqlmot->error)); }
                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_repartidor() #4 '.htmlspecialchars($sqlmot->error)); }
                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_repartidor() #4  '.htmlspecialchars($this->con->error)); }
                            }
                        }else{ $this->registrar(7, $id_loc, $this->id_gir, 'crear_repartidor()'); }
                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_repartidor() #5 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_repartidor() #5 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_repartidor() #5 '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'crear_repartidor()'); }
        return $info;

    }
    private function crear_catalogo(){
        $info['op'] = 2;
        $info['mensaje'] = "Error";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id_cat = $_POST['id'];
            $nombre = $_POST['nombre'];
            if($id_cat == 0){
                if($sql = $this->con->prepare("INSERT INTO catalogo_productos (nombre, fecha_creado, id_gir) VALUES (?, now(), ?)")){
                    if($sql->bind_param("si", $nombre, $this->id_gir)){
                        if($sql->execute()){
                            $info['op'] = 1;
                            $info['mensaje'] = "Catalogo creado exitosamente";
                            $info['reload'] = 1;
                            $info['page'] = "msd/ver_giro.php";
                            $this->con_cambios(null);
                            $sql->close();
                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_catalogo() #1 '.htmlspecialchars($sql->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_catalogo() #1 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_catalogo() #1 '.htmlspecialchars($this->con->error)); }
            }
            if($id_cat > 0){
                if($sql = $this->con->prepare("UPDATE catalogo_productos SET nombre=? WHERE id_cat=? AND id_gir=?")){
                    if($sql->bind_param("sii", $nombre, $id_cat, $this->id_gir)){
                        if($sql->execute()){
                            $info['op'] = 1;
                            $info['mensaje'] = "Catalogo modificado exitosamente";
                            $info['reload'] = 1;
                            $info['page'] = "msd/ver_giro.php";
                            $this->con_cambios(null);
                            $sql->close();
                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_catalogo() #2 '.htmlspecialchars($sql->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_catalogo() #2 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_catalogo() #2 '.htmlspecialchars($this->con->error)); }
            }
        }else{ $this->registrar(2, 0, 0, 'crear_catalogo()'); }
        return $info;
    }
    private function eliminar_catalogo(){
        $info['tipo'] = "error";
        $info['titulo'] = "Error";
        $info['texto'] = "Catalogo no pudo ser eliminado";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id = explode("/", $_POST['id']);
            $nombre = $_POST["nombre"];
            if($sqlcat = $this->con->prepare("SELECT * FROM catalogo_productos WHERE id_cat=? AND id_gir=? AND eliminado=?")){
                if($sqlcat->bind_param("iii", $id[1], $this->id_gir, $this->eliminado)){
                    if($sqlcat->execute()){
                        $res = $sqlcat->get_result();
                        if($res->{"num_rows"} == 1){
                            if($sql = $this->con->prepare("UPDATE catalogo_productos SET eliminado='1' WHERE id_cat=? AND id_gir=?")){
                                if($sql->bind_param("ii", $id[1], $this->id_gir)){
                                    if($sql->execute()){
                                        $info['tipo'] = "success";
                                        $info['titulo'] = "Eliminado";
                                        $info['texto'] = "Catalogo ".$nombre." Eliminado";
                                        $info['reload'] = 1;
                                        $info['page'] = "msd/ver_giro.php?id=".$id[0];
                                        $sql->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_catalogo() #1 '.htmlspecialchars($sql->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_catalogo() #1 '.htmlspecialchars($sql->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_catalogo() #1 '.htmlspecialchars($this->con->error)); }
                        }
                        if($res->{"num_rows"} == 0){ $this->registrar(7, 0, $this->id_gir, 'eliminar_catalogo() XSS'); }
                        $sqlcat->free_result();
                        $sqlcat->close();
                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_catalogo() #2 '.htmlspecialchars($sqlcat->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_catalogo() #2 '.htmlspecialchars($sqlcat->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_catalogo() #2 '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'eliminar_catalogo()'); }
        return $info;
    }
    private function configurar_usuario_local(){
        $info['op'] = 2;
        $info['mensaje'] = "Error";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id_user = $_POST['id_user'];
            $id_loc = $_POST['id_loc'];
            $save_web = $_POST['save_web'];
            $web_min = $_POST['web_min'];
            $save_pos = $_POST['save_pos'];
            $pos_min = $_POST['pos_min'];
            if($sqlloc = $this->con->prepare("SELECT * FROM fw_usuarios WHERE id_user=? AND id_loc=? AND id_gir=? AND eliminado=?")){
                if($sqlloc->bind_param("iiii", $id_user, $id_loc, $this->id_gir, $this->eliminado)){
                    if($sqlloc->execute()){
                        $res = $sqlloc->get_result();
                        if($res->{"num_rows"} == 1){
                            if($sql = $this->con->prepare("UPDATE fw_usuarios SET save_web=?, web_min=?, save_pos=?, pos_min=? WHERE id_user=?")){
                                if($sql->bind_param("iiiii", $save_web, $web_min, $save_pos, $pos_min, $id_user)){
                                    if($sql->execute()){
                                        $info['op'] = 1;
                                        $info['mensaje'] = "Usuario editado exitosamente";
                                        $info['reload'] = 1;
                                        $info['page'] = "msd/usuarios_local.php?id_loc=".$id_loc;
                                        $sql->close();
                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'configurar_usuario_local() #1 '.htmlspecialchars($sql->error)); }
                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'configurar_usuario_local() #1 '.htmlspecialchars($sql->error)); }
                            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'configurar_usuario_local() #1 '.htmlspecialchars($this->con->error)); }
                        }
                        if($res->{"num_rows"} == 0){ $this->registrar(7, $id_loc, $this->id_gir, 'configurar_usuario_local()'); }
                        $sqlloc->free_result();
                        $sqlloc->close();
                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'configurar_usuario_local() #2 '.htmlspecialchars($sqlloc->error)); }
                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'configurar_usuario_local() #2 '.htmlspecialchars($sqlloc->error)); }
            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'configurar_usuario_local() #2 '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'configurar_usuario_local()'); }
        return $info;
    }
    private function crear_locales(){
        $info['op'] = 2;
        $info['mensaje'] = "Error";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $code = $this->pass_generate(20);
            $id_cat = $_POST['id_cat'];
            $id_loc = $_POST['id'];
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $direccion = $_POST['direccion'];
            $lat = $_POST['lat'];
            $lng = $_POST['lng'];
            $telefono = $_POST['telefono'];
            $whatsapp = $_POST['whatsapp'];
            if($sql = $this->con->prepare("SELECT * FROM catalogo_productos WHERE id_cat=? AND id_gir=? AND eliminado=?")){
                if($sql->bind_param("iii", $id_cat, $this->id_gir, $this->eliminado)){
                    if($sql->execute()){
                        $res = $sql->get_result();
                        if($res->{"num_rows"} == 1){
                            if($sqlses = $this->con->prepare("SELECT * FROM ses_mail WHERE correo=?")){
                                if($sqlses->bind_param("s", $correo)){
                                    if($sqlses->execute()){
                                        $resses = $sqlses->get_result();
                                        $correo_ses = ($resses->{"num_rows"} == 0) ? 0 : 1 ;
                                        if($id_loc == 0){
                                            if($sqlloc = $this->con->prepare("INSERT INTO locales (telefono, whatsapp, nombre, correo_ses, direccion, lat, lng, code, fecha_pos, fecha_cocina, fecha_creado, correo, id_cat, id_gir) VALUES (?, ?, ?, ?, ?, ?, ?, ?, now(), now(), now(), ?, ?, ?)")){
                                                if($sqlloc->bind_param("sssisddssii", $telefono, $whatsapp, $nombre, $correo_ses, $direccion, $lat, $lng, $code, $correo, $id_cat, $this->id_gir)){
                                                    if($sqlloc->execute()){
                                                        $info['op'] = 1;
                                                        $info['mensaje'] = "Local creado exitosamente";
                                                        $info['reload'] = 1;
                                                        $info['page'] = "msd/locales.php";
                                                        $this->locales_giro();
                                                        $this->con_cambios(null);
                                                        $sqlloc->close();
                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_locales() #1 '.htmlspecialchars($sqlloc->error)); }
                                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_locales() #1 '.htmlspecialchars($sqlloc->error)); }
                                            }else{ $this->registrar(6, 0, $this->id_gir, 'crear_locales() #1 '.htmlspecialchars($this->con->error)); }
                                        }
                                        if($id_loc > 0){
                                            if($sqlloc = $this->con->prepare("UPDATE locales SET telefono=?, whatsapp=?, nombre=?, correo_ses=?, direccion=?, lat=?, lng=?, correo=? WHERE id_loc=? AND id_cat=? AND id_gir=? AND eliminado=?")){
                                                if($sqlloc->bind_param("sssisddsiiii", $telefono, $whatsapp, $nombre, $correo_ses, $direccion, $lat, $lng, $correo, $id_loc, $id_cat, $this->id_gir, $this->eliminado)){
                                                    if($sqlloc->execute()){
                                                        $info['op'] = 1;
                                                        $info['mensaje'] = "Local modificado exitosamente";
                                                        $info['reload'] = 1;
                                                        $info['page'] = "msd/locales.php";
                                                        $this->locales_giro();
                                                        $this->con_cambios(null);
                                                        $sqlloc->close();
                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_locales() #2 '.htmlspecialchars($sqlloc->error)); }
                                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_locales() #2 '.htmlspecialchars($sqlloc->error)); }
                                            }else{ $this->registrar(6, 0, $this->id_gir, 'crear_locales() #2 '.htmlspecialchars($this->con->error)); }
                                        }
                                        $sqlses->free_result();
                                        $sqlses->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_locales() #3 '.htmlspecialchars($sqlses->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_locales() #3 '.htmlspecialchars($sqlses->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'crear_locales() #3 '.htmlspecialchars($this->con->error)); }
                        }
                        if($res->{"num_rows"} == 0){ $this->registrar(7, 0, $this->id_gir, 'crear_locales() XSS'); }
                        $sql->free_result();
                        $sql->close();
                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_locales() #4 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_locales() #4 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'crear_locales() #4 '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'crear_locales()'); }
        return $info;
    }
    private function crear_locales_tramos(){
        $info['op'] = 2;
        $info['mensaje'] = "Error";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id_lot = $_POST['id_lot'];
            $id_loc = $_POST['id_loc'];
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $pol = $_POST['posiciones'];
            if($sqlloc = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND id_gir=? AND eliminado=?")){
                if($sqlloc->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado)){
                    if($sqlloc->execute()){
                        $res = $sqlloc->get_result();
                        if($res->{"num_rows"} == 1){
                            if($id_lot == 0){
                                if($sqllt = $this->con->prepare("INSERT INTO locales_tramos (nombre, precio, poligono, id_loc) VALUES (?, ?, ?, ?)")){
                                    if($sqllt->bind_param("sisi", $nombre, $precio, $pol, $id_loc)){
                                        if($sqllt->execute()){
                                            $info['op'] = 1;
                                            $info['mensaje'] = "Tramo creado exitosamente";
                                            $info['reload'] = 1;
                                            $info['page'] = "msd/zonas_locales.php?id_loc=".$id_loc;
                                            $this->con_cambios(null);
                                            $sqllt->close();
                                        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #1 '.htmlspecialchars($sqllt->error)); }
                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #1 '.htmlspecialchars($sqllt->error)); }
                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #1 '.htmlspecialchars($this->con->error)); }
                            }
                            if($id_lot > 0){
                                if($sqllt = $this->con->prepare("UPDATE locales_tramos SET nombre=?, precio=?, poligono=? WHERE id_lot=? AND id_loc=?")){
                                    if($sqllt->bind_param("sisii", $nombre, $precio, $pol, $id_lot, $id_loc)){
                                        if($sqllt->execute()){
                                            $info['op'] = 1;
                                            $info['mensaje'] = "Tramo modificado exitosamente";
                                            $info['reload'] = 1;
                                            $info['page'] = "msd/zonas_locales.php?id_loc=".$id_loc;
                                            $this->con_cambios(null);
                                            $sqllt->close();
                                        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #2 '.htmlspecialchars($sqllt->error)); }
                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #2 '.htmlspecialchars($sqllt->error)); }
                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #2 '.htmlspecialchars($this->con->error)); }
                            }
                            if($sqlmin = $this->con->prepare("SELECT MIN(t3.precio) as min FROM giros t1, locales t2, locales_tramos t3 WHERE t1.id_gir=? AND t1.id_gir=t2.id_gir AND t2.id_loc=t3.id_loc AND t3.eliminado=? AND t2.eliminado=?")){
                                if($sqlmin->bind_param("iii", $this->id_gir, $this->eliminado, $this->eliminado)){
                                    if($sqlmin->execute()){
                                        $min = $sqlmin->get_result()->fetch_all(MYSQLI_ASSOC)[0]["min"];
                                        if($min !== null){
                                            if($sqlug = $this->con->prepare("UPDATE giros SET despacho_domicilio='1', desde=? WHERE id_gir=? AND eliminado=?")){
                                                if($sqlug->bind_param("iii", $min, $this->id_gir, $this->eliminado)){
                                                    if($sqlug->execute()){
                                                        $sqlug->close();
                                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #3 '.htmlspecialchars($sqlug->error)); }
                                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #3 '.htmlspecialchars($sqlug->error)); }
                                            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #3 '.htmlspecialchars($this->con->error)); }
                                        }else{
                                            if($sqlug = $this->con->prepare("UPDATE giros SET despacho_domicilio='0' WHERE id_gir=? AND eliminado=?")){
                                                if($sqlug->bind_param("ii", $this->id_gir, $this->eliminado)){
                                                    if($sqlug->execute()){
                                                        $sqlug->close();
                                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #4 '.htmlspecialchars($sqlug->error)); }
                                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #4 '.htmlspecialchars($sqlug->error)); }
                                            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #4 '.htmlspecialchars($this->con->error)); }
                                        }
                                        $sqlmin->free_result();
                                        $sqlmin->close();
                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #5 '.htmlspecialchars($sqlmin->error)); }
                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #5 '.htmlspecialchars($sqlmin->error)); }
                            }else{$this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #5 '.htmlspecialchars($this->con->error));  }
                        }
                        if($res->{"num_rows"} == 0){ $this->registrar(7, $id_loc, $this->id_gir, 'crear_locales_tramos()'); }
                        $sqlloc->free_result();
                        $sqlloc->close();
                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #6 '.htmlspecialchars($sqlloc->error)); }
                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #6 '.htmlspecialchars($sqlloc->error)); }
            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_locales_tramos() #6 '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, $this->id_gir, 'crear_locales_tramos()'); }
        return $info;
        
    }
    private function locales_giro(){
        if($sql = $this->con->prepare("SELECT id_loc, lat, lng, nombre, direccion, image, telefono, whatsapp FROM locales WHERE id_gir=? AND eliminado=?")){
            if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    if($sqlug = $this->con->prepare("UPDATE giros SET lista_locales=? WHERE id_gir=? AND eliminado=?")){
                        if($sqlug->bind_param("sii", json_encode($result, JSON_UNESCAPED_UNICODE), $this->id_gir, $this->eliminado)){
                            if($sqlug->execute()){
                                $sqlug->close();
                            }else{ $this->registrar(6, 0, $this->id_gir, 'locales_giro() #1 '.htmlspecialchars($sqlug->error)); }
                        }else{ $this->registrar(6, 0, $this->id_gir, 'locales_giro() #1 '.htmlspecialchars($sqlug->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'locales_giro() #1 '.htmlspecialchars($this->con->error)); }
                    $sql->free_result();
                    $sql->close();
                }else{ $this->registrar(6, 0, $this->id_gir, 'locales_giro() #2 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'locales_giro() #2 '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'locales_giro() #2 '.htmlspecialchars($this->con->error)); }
    }
    private function eliminar_locales(){     
        $info['tipo'] = "error";
        $info['titulo'] = "Error";
        $info['texto'] = "Local no pudo ser eliminado";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id_loc = $_POST['id'];
            if($sql = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND id_gir=? AND eliminado=?")){
                if($sql->bind_param("iii", $id_loc, $this->id_gir, $this->eliminado)){
                    if($sql->execute()){
                        $res = $sql->get_result();
                        if($res->{"num_rows"} == 1){
                            if($sqlult = $this->con->prepare("UPDATE locales SET eliminado='1' WHERE id_loc=?")){
                                if($sqlult->bind_param("i", $id_loc)){
                                    if($sqlult->execute()){
                                        $this->locales_giro();
                                        $info['tipo'] = "success";
                                        $info['titulo'] = "Eliminado";
                                        $info['texto'] = "Local Eliminado";
                                        $info['reload'] = 1;
                                        $info['page'] = "msd/locales.php";
                                        $sqlult->close();
                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'eliminar_locales() '.htmlspecialchars($sqlult->error)); }
                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'eliminar_locales() '.htmlspecialchars($sqlult->error)); }
                            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'eliminar_locales() '.htmlspecialchars($this->con->error)); }
                        }
                        if($res->{"num_rows"} == 0){ $this->registrar(7, $id_loc, $this->id_gir, 'eliminar_locales()'); }
                        $sql->free_result();
                        $sql->close();
                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'eliminar_locales() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'eliminar_locales() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'eliminar_locales() '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'eliminar_locales()'); }
        return $info;
    }
    private function crear_usuario_admin(){
        $info['op'] = 2;
        $info['mensaje'] = "Error";
        if($this->admin == 1){
            $correo = $_POST['correo'];
            if(filter_var($correo, FILTER_VALIDATE_EMAIL)){
                $id = $_POST['id'];
                $nombre = $_POST['nombre'];
                if($sqlugc = $this->con->prepare("SELECT * FROM fw_usuarios_giros_clientes WHERE id_user=? AND id_gir=?")){
                    if($sqlugc->bind_param("ii", $this->id_user, $this->id_gir)){
                        if($sqlugc->execute()){
                            $resugc = $sqlugc->get_result();
                            if($resugc->{"num_rows"} == 1){
                                if($sqlus = $this->con->prepare("SELECT * FROM fw_usuarios WHERE correo=?")){
                                    if($sqlus->bind_param("s", $correo)){
                                        if($sqlus->execute()){
                                            $resus = $sqlus->get_result();
                                            $result = $resus->fetch_all(MYSQLI_ASSOC)[0];
                                            if($resus->{"num_rows"} == 0 || ($resus->{"num_rows"} == 1 && $id == $result["id_user"])){
                                                if($id == 0){
                                                    $admin = 0;
                                                    if($sqlius = $this->con->prepare("INSERT INTO fw_usuarios (nombre, fecha_creado, correo, admin) VALUES (?, now(), ?, ?)")){
                                                        if($sqlius->bind_param("ssi", $nombre, $correo, $admin)){
                                                            if($sqlius->execute()){
                                                                $id_user = $this->con->insert_id;
                                                                if($sqliug = $this->con->prepare("INSERT INTO fw_usuarios_giros (id_user, id_gir) VALUES (?, ?)")){
                                                                    if($sqliug->bind_param("ii", $id_user, $this->id_gir)){
                                                                        if($sqliug->execute()){
                                                                            $info['op'] = 1;
                                                                            $info['mensaje'] = "Usuario creado exitosamente";
                                                                            $info['reload'] = 1;
                                                                            $info['page'] = "msd/usuarios_admin.php";
                                                                            $sqliug->close();
                                                                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_usuario_admin() #1 '.htmlspecialchars($sqliug->error)); }
                                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_usuario_admin() #1 '.htmlspecialchars($sqliug->error)); }
                                                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_usuario_admin() #1 '.htmlspecialchars($this->con->error)); }
                                                                $sqlius->close();
                                                            }else{ $this->registrar(6, 0, $this->id_gir, 'crear_usuario_admin() #2 '.htmlspecialchars($sqlius->error)); }
                                                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_usuario_admin() #2 '.htmlspecialchars($sqlius->error)); }
                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_usuario_admin() #2 '.htmlspecialchars($this->con->error)); }
                                                }
                                                if($id > 0){
                                                    if($sqluus = $this->con->prepare("UPDATE fw_usuarios SET nombre=?, correo=? WHERE id_user=? AND eliminado=?")){
                                                        if($sqluus->bind_param("ssii", $nombre, $correo, $id, $this->eliminado)){
                                                            if($sqluus->execute()){
                                                                $info['op'] = 1;
                                                                $info['mensaje'] = "Usuario modificado exitosamente";
                                                                $info['reload'] = 1;
                                                                $info['page'] = "msd/usuarios_admin.php";
                                                                $sqluus->close();
                                                            }else{ $this->registrar(6, 0, $this->id_gir, 'crear_usuario_admin() #3 '.htmlspecialchars($sqluus->error)); }
                                                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_usuario_admin() #3 '.htmlspecialchars($sqluus->error)); }
                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_usuario_admin() #3 '.htmlspecialchars($this->con->error)); }
                                                }
                                            }else{ }
                                            $sqlus->free_result();
                                            $sqlus->close();
                                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_usuario_admin() #4 '.htmlspecialchars($sqlus->error)); }
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_usuario_admin() #4 '.htmlspecialchars($sqlus->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_usuario_admin() #4 '.htmlspecialchars($this->con->error)); }
                            }
                            if($resugc->{"num_rows"} == 0){ $this->registrar(7, 0, $this->id_gir, 'crear_usuario_admin() fw_usuarios_giros_clientes'); }
                            $sqlugc->free_result();
                            $sqlugc->close();
                         }else{ $this->registrar(6, 0, $this->id_gir, 'crear_usuario_admin() #5 '.htmlspecialchars($sqlugc->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_usuario_admin() #5 '.htmlspecialchars($sqlugc->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_usuario_admin() #5 '.htmlspecialchars($this->con->error)); }
            }else{  }
        }else{ $this->registrar(4, 0, 0, 'crear_usuario_admin()'); }
        return $info;
    }
    private function crear_usuarios_local(){
        $info['op'] = 2;
        $info['mensaje'] = "Error";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $correo = $_POST['v_correo'];
            if(filter_var($correo, FILTER_VALIDATE_EMAIL)){
                $id_loc = $_POST['id_loc'];
                if($sqlsl = $this->con->prepare("SELECT * FROM locales WHERE id_loc=? AND id_gir=?")){
                    if($sqlsl->bind_param("ii", $id_loc, $this->id_gir)){
                        if($sqlsl->execute()){
                            $ressl = $sqlsl->get_result();
                            $id = $_POST['id'];
                            $nombre = $_POST['v_nombre'];
                            $tipo = $_POST['v_tipo'];
                            $save_web = $_POST['save_web'];
                            $web_min = $_POST['web_min'];
                            $save_pos = $_POST['save_pos'];
                            $pos_min = $_POST['pos_min'];
                            $del_pdir = $_POST['del_pdir'];
                            $pass1 = $_POST['v_pass1'];
                            $pass2 = $_POST['v_pass2'];
                            if($ressl->{"num_rows"} == 1){
                                if($sqlus = $this->con->prepare("SELECT id_user FROM fw_usuarios WHERE correo=?")){
                                    if($sqlus->bind_param("s", $correo)){
                                        if($sqlus->execute()){
                                            $res = $sqlus->get_result();
                                            $id_user = $res->fetch_all(MYSQLI_ASSOC)[0]["id_user"];
                                            if($res->{"num_rows"} == 0 || ($res->{"num_rows"} == 1 && $id == $id_user)){
                                                if($id == 0){
                                                    if($pass1 == $pass2){
                                                        if(strlen($pass1) > 7){
                                                            $admin = 0;
                                                            if($sqlius = $this->con->prepare("INSERT INTO fw_usuarios (nombre, fecha_creado, correo, pass, tipo, admin, save_web, web_min, save_pos, pos_min, del_pdir, id_loc, id_gir) VALUES (?, now(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")){
                                                                if($sqlius->bind_param("sssiiiiiiiii", $nombre, $correo, md5($pass1), $tipo, $admin, $save_web, $web_min, $save_pos, $pos_min, $del_pdir, $id_loc, $this->id_gir)){
                                                                    if($sqlius->execute()){
                                                                        $info['op'] = 1;
                                                                        $info['mensaje'] = "Usuario creado exitosamente";
                                                                        $info['reload'] = 1;
                                                                        $info['page'] = "msd/usuarios_local.php?id_loc=".$id_loc;
                                                                        $sqlius->close();
                                                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_usuarios_local() #1 '.htmlspecialchars($sqlius->error)); }
                                                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_usuarios_local() #1 '.htmlspecialchars($sqlius->error)); }
                                                            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_usuarios_local() #1 '.htmlspecialchars($this->con->error)); }
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
                                                        if($sqluup = $this->con->prepare("UPDATE fw_usuarios SET pass=? WHERE id_user=? AND eliminado=? AND id_loc=? AND id_gir=?")){
                                                            if($sqluup->bind_param("siiii", md5($pass1), $id, $this->eliminado, $id_loc, $this->id_gir)){
                                                                if($sqluup->execute()){
                                                                    $sqluup->close();
                                                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_usuarios_local() #2 '.htmlspecialchars($sqluup->error)); }
                                                            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_usuarios_local() #2 '.htmlspecialchars($sqluup->error)); }
                                                        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_usuarios_local() #2 '.htmlspecialchars($this->con->error)); }
                                                    }
                                                    if($sqluus = $this->con->prepare("UPDATE fw_usuarios SET nombre=?, correo=?, tipo=?, del_pdir=?, save_web=?, web_min=?, save_pos=?, pos_min=? WHERE id_user=? AND eliminado=? AND id_loc=? AND id_gir=?")){
                                                        if($sqluus->bind_param("ssiiiiiiiiii", $nombre, $correo, $tipo, $del_pdir, $save_web, $web_min, $save_pos, $pos_min, $id, $this->eliminado, $id_loc, $this->id_gir)){
                                                            if($sqluus->execute()){
                                                                $info['op'] = 1;
                                                                $info['mensaje'] = "Usuario modificado exitosamente";
                                                                $info['reload'] = 1;
                                                                $info['page'] = "msd/usuarios_local.php?id_loc=".$id_loc;
                                                                $sqluus->close();
                                                            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_usuarios_local() #3 '.htmlspecialchars($sqluus->error)); }
                                                        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_usuarios_local() #3 '.htmlspecialchars($sqluus->error)); }
                                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_usuarios_local() #3 '.htmlspecialchars($this->con->error)); }
                                                }
                                            }else{ $this->registrar(7, $id_loc, $this->id_gir, 'crear_usuarios_local() correo existente '); }
                                            $sqlus->free_result();
                                            $sqlus->close();
                                        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_usuarios_local() #4 '.htmlspecialchars($sqlus->error)); }
                                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_usuarios_local() #4 '.htmlspecialchars($sqlus->error)); }
                                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_usuarios_local() #4 '.htmlspecialchars($this->con->error)); }
                            }
                            if($ressl->{"num_rows"} == 0){ $this->registrar(7, $id_loc, $this->id_gir, 'crear_usuarios_local() local'); }
                            $sqlsl->free_result();
                            $sqlsl->close();
                        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_usuarios_local() #5 '.htmlspecialchars($sqlsl->error)); }
                    }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_usuarios_local() #5 '.htmlspecialchars($sqlsl->error)); }
                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'crear_usuarios_local() #5 '.htmlspecialchars($this->con->error)); }
            }else{  }
        }else{ $this->registrar(2, 0, 0, 'crear_usuarios_local()'); }
        return $info;
    }
    private function eliminar_usuario(){
        $info['tipo'] = "error";
        $info['titulo'] = "Error";
        $info['texto'] = "Usuario no pudo ser eliminado";
        if($this->re_venta == 1 || $this->id_user == 1){
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            if($sql = $this->con->prepare("SELECT id_aux_user FROM fw_usuarios WHERE id_user=?")){
                if($sql->bind_param("i", $id)){
                    if($sql->execute()){
                        $id_aux_user = $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0]["id_aux_user"];
                        if($this->id_user == 1 || ($this->re_venta == 1 && $id_aux_user == $this->id_user)){
                            if($sqlu = $this->con->prepare("UPDATE fw_usuarios SET eliminado='1' WHERE id_user=?")){
                                if($sqlu->bind_param("i", $id)){
                                    if($sqlu->execute()){
                                        $info['tipo'] = "success";
                                        $info['titulo'] = "Eliminado";
                                        $info['texto'] = "Usuario Eliminado";
                                        $info['reload'] = 1;
                                        $info['page'] = "msd/usuarios.php";
                                        $sqlu->close();
                                    }else{  $this->registrar(6, 0, 0, 'eliminar_usuario() #1 '.htmlspecialchars($sqlu->error)); }
                                }else{  $this->registrar(6, 0, 0, 'eliminar_usuario() #1 '.htmlspecialchars($sqlu->error)); }
                            }else{  $this->registrar(6, 0, 0, 'eliminar_usuario() #1 '.htmlspecialchars($this->con->error)); }
                        }else{ $this->registrar(7, 0, 0, 'eliminar_usuario()'); }
                        $sql->free_result();
                        $sql->close();
                    }else{ $this->registrar(6, 0, 0, 'eliminar_usuario() #2 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, 0, 'eliminar_usuario() #2 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'eliminar_usuario() #2 '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(1, 0, 0, 'eliminar_usuario()'); }
        return $info;
    }
    private function eliminar_pagina(){
        $info['tipo'] = "error";
        $info['titulo'] = "Error";
        $info['texto'] = "Pagina no pudo ser eliminado";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id = $_POST['id'];
            $nombre = $_POST["nombre"];
            if($sql = $this->con->prepare("UPDATE paginas SET eliminado='1' WHERE id_pag=? AND id_gir=?")){
                if($sql->bind_param("ii", $id, $this->id_gir)){
                    if($sql->execute()){
                        $info['tipo'] = "success";
                        $info['titulo'] = "Eliminado";
                        $info['texto'] = "Pagina ".$nombre." Eliminado";
                        $info['reload'] = 1;
                        $info['page'] = "msd/ver_giro.php?id_gir=".$this->id_gir;
                        $this->con_cambios(null);
                        $sql->close();
                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_pagina() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_pagina() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_pagina() '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, $this->id_gir, 'eliminar_pagina()'); }
        return $info;
    }
    private function eliminar_preguntas(){
        $info['tipo'] = "error";
        $info['titulo'] = "Error";
        $info['texto'] = "Pagina no pudo ser eliminado";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id = $_POST['id'];
            if($sql = $this->con->prepare("UPDATE preguntas SET eliminado='1' WHERE id_pre=? AND id_cat=? AND id_gir=?")){
                if($sql->bind_param("iii", $id, $this->id_cat, $this->id_gir)){
                    if($sql->execute()){
                        $info['tipo'] = "success";
                        $info['titulo'] = "Eliminado";
                        $info['texto'] = "Preguntas Eliminado";
                        $info['reload'] = 1;
                        $info['page'] = "msd/preguntas.php";
                        $this->con_cambios(null);
                        $sql->close();
                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_preguntas() '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_preguntas() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_preguntas() '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'eliminar_preguntas()'); }
        return $info;
    }
    private function crear_categoria(){
        $info['op'] = 2;
        $info['mensaje'] = "Error:";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id_cae = $_POST['id'];
            $parent_id = $_POST['parent_id'];
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $descripcion_sub = $_POST['descripcion_sub'];
            $precio = $_POST['precio'];
            $tipo = $_POST['tipo'];
            if($id_cae == 0){
                if($sql = $this->con->prepare("SELECT * FROM categorias WHERE parent_id=?")){
                    if($sql->bind_param("i", $parent_id)){
                        if($sql->execute()){
                            $res = $sql->get_result();
                            $orders = $res->{"num_rows"};
                            if($sqlic = $this->con->prepare("INSERT INTO categorias (nombre, parent_id, tipo, id_cat, id_gir, descripcion, descripcion_sub, precio, orders, degradado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, '1')")){
                                if($sqlic->bind_param("siiiissii", $nombre, $parent_id, $tipo, $this->id_cat, $this->id_gir, $descripcion, $descripcion_sub, $precio, $orders)){
                                    if($sqlic->execute()){
                                        $info['op'] = 1;
                                        $info['mensaje'] = "Categoria creada exitosamente";
                                        $info['reload'] = 1;
                                        $info['page'] = "msd/categorias.php?parent_id=".$parent_id;
                                        $this->con_cambios(null);
                                        $sqlic->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_categoria() #1 '.htmlspecialchars($sqlic->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_categoria() #1 '.htmlspecialchars($sqlic->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'crear_categoria() #1 '.htmlspecialchars($this->con->error)); }
                            $sql->free_result();
                            $sql->close();
                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_categoria() #2 '.htmlspecialchars($sql->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_categoria() #2 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_categoria() #2 '.htmlspecialchars($this->con->error)); }
            }
            if($id_cae > 0){
                if($sqluc = $this->con->prepare("UPDATE categorias SET nombre=?, tipo=?, descripcion=?, descripcion_sub=?, precio=? WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?")){
                    if($sqluc->bind_param("sissiiiii", $nombre, $tipo, $descripcion, $descripcion_sub, $precio, $id_cae, $this->id_cat, $this->id_gir, $this->eliminado)){
                        if($sqluc->execute()){
                            $info['op'] = 1;
                            $info['mensaje'] = "Categoria modificada exitosamente";
                            $info['reload'] = 1;
                            $info['page'] = "msd/categorias.php?parent_id=".$parent_id;
                            $this->con_cambios(null);
                            $sqluc->close();
                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_categoria() #3 '.htmlspecialchars($sqluc->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_categoria() #3 '.htmlspecialchars($sqluc->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_categoria() #3 '.htmlspecialchars($this->con->error)); }
            }
        }else{ $this->registrar(2, 0, 0, 'crear_categoria()'); }
        return $info;
    }
    private function eliminar_productos(){

        $info['tipo'] = "error";
        $info['titulo'] = "Error";
        $info['texto'] = "Producto no pudo ser eliminado";

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){

            $id = explode("/", $_POST['id']);
            $nombre = $_POST["nombre"];

            if($sql = $this->con->prepare("SELECT * FROM categorias WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?")){
                if($sql->bind_param("iiii", $id[1], $this->id_cat, $this->id_gir, $this->eliminado)){
                    if($sql->execute()){

                        $res = $sql->get_result();
                        if($res->{"num_rows"} == 1){

                            if($sqlc = $this->con->prepare("SELECT * FROM productos WHERE id_pro=? AND id_gir=? AND eliminado=?")){
                                if($sqlc->bind_param("iii", $id[0], $this->id_gir, $this->eliminado)){
                                    if($sqlc->execute()){
                
                                        $resc = $sqlc->get_result();
                                        if($resc->{"num_rows"} == 1){

                                            $tipo = 1;
                                            if($sqla = $this->con->prepare("SELECT t1.nombre FROM categorias t1, promocion_productos t2 WHERE t1.id_cae=? AND t1.tipo=? AND t1.eliminado=? AND t1.id_cae=t2.parent_id AND t2.id_pro=?")){
                                                if($sqla->bind_param("iiii", $id[1], $tipo, $this->eliminado, $id[0])){
                                                    if($sqla->execute()){
                                                        
                                                        $resa = $sqla->get_result();
                                                        if($resa->{"num_rows"} == 0){

                                                            if($sqld = $this->con->prepare("DELETE FROM cat_pros WHERE id_pro=? AND id_cae=?")){
                                                                if($sqld->bind_param("ii", $id[0], $id[1])){
                                                                    if($sqld->execute()){

                                                                        if($sqlb = $this->con->prepare("SELECT t1.nombre FROM categorias t1, cat_pros t2 WHERE t2.id_pro=? t2.id_cae=t1.id_cae")){
                                                                            if($sqlb->bind_param("i", $id[0])){
                                                                                if($sqlb->execute()){

                                                                                    $resb = $sqlb->get_result();
                                                                                    if($resb->{"num_rows"} == 0){

                                                                                        if($sqlup = $this->con->prepare("UPDATE productos SET eliminado='1' WHERE id_pro=? AND id_gir=?")){
                                                                                            if($sqlup->bind_param("ii", $id[0], $this->id_gir)){
                                                                                                if($sqlup->execute()){
                                                                                                    $info['tipo'] = "success";
                                                                                                    $info['titulo'] = "Eliminado";
                                                                                                    $info['texto'] = "Producto ".$nombre." Eliminado";
                                                                                                    $info['reload'] = 1;
                                                                                                    $info['page'] = "msd/crear_productos.php?id=".$id[1]."&parent_id=".$id[2];
                                                                                                    $sqlup->close();
                                                                                                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #1 '.htmlspecialchars($sqlup->error)); }
                                                                                            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #1 '.htmlspecialchars($sqlup->error)); }
                                                                                        }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #1 '.htmlspecialchars($this->con->error)); }

                                                                                    }
                                                                                    if($resb->{"num_rows"} > 0){
                                                                                        
                                                                                        $nombre2 = $resb->fetch_all(MYSQLI_ASSOC)[0]["nombre"];

                                                                                        $info['tipo'] = "success";
                                                                                        $info['titulo'] = "Eliminado";
                                                                                        $info['texto'] = "Producto ".$nombre." ha sido desvinculado, pero no ha sido eliminado, ya que se encuentra en la categoria ".$nombre2;
                                                                                        $info['reload'] = 1;
                                                                                        $info['page'] = "msd/crear_productos.php?id=".$id[1]."&parent_id=".$id[2];

                                                                                    }
                                                                                    $sqlb->free_result();
                                                                                    $sqlb->close();

                                                                                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #2 '.htmlspecialchars($sqlb->error)); }
                                                                            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #2 '.htmlspecialchars($sqlb->error)); }
                                                                        }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #2 '.htmlspecialchars($this->con->error)); }
                                                                        $sqld->close();

                                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #2 '.htmlspecialchars($sqld->error)); }
                                                                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #2 '.htmlspecialchars($sqld->error)); }
                                                            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #2 '.htmlspecialchars($this->con->error)); }

                                                        }
                                                        if($resa->{"num_rows"} == 1){

                                                            $nombre1 = $resa->fetch_all(MYSQLI_ASSOC)[0]["nombre"];
                                                            $info['tipo'] = "error";
                                                            $info['titulo'] = "Error";
                                                            $info['texto'] = "El producto no pudo ser eliminado, por que existe una Promocion relacionada al producto (".$nombre1.")";

                                                        }
                                                        $sqla->free_result();
                                                        $sqla->close();

                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #3 '.htmlspecialchars($sqla->error)); }
                                                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #3 '.htmlspecialchars($sqla->error)); }
                                            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #3 '.htmlspecialchars($this->con->error)); }

                                        }
                                        $sqlc->free_result();
                                        $sqlc->close();

                                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #3 '.htmlspecialchars($sqlc->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #3 '.htmlspecialchars($sqlc->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #3 '.htmlspecialchars($this->con->error)); }

                        }

                        if($sql->{"num_rows"} == 0){ $this->registrar(7, 0, $this->id_gir, 'eliminar_productos()'); }
                        
                        $sql->free_result();
                        $sql->close();

                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #3 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #3 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_productos() #3 '.htmlspecialchars($this->con->error)); }

        }else{ $this->registrar(2, 0, 0, 'eliminar_productos()'); }

        return $info;
    }
    private function eliminar_categoria(){

        $info['tipo'] = "error";
        $info['titulo'] = "Error";
        $info['texto'] = "Categoria no pudo ser eliminado";

        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id = explode("/", $_POST['id']);
            if($sqla = $this->con->prepare("SELECT t1.nombre FROM categorias t1, promocion_categoria t2 WHERE t2.id_cae2=? AND t1.id_cae=t2.id_cae1 AND t1.id_gir=? AND t1.eliminado=?")){
                if($sqla->bind_param("iii", $id[0], $this->id_gir, $this->eliminado)){
                    if($sqla->execute()){

                        $res = $sqla->get_result();
                        if($res->{"num_rows"} > 0){
                            $nombre = $res->fetch_all(MYSQLI_ASSOC)[0]["nombre"];
                            $info['tipo'] = "error";
                            $info['titulo'] = "Error";
                            $info['texto'] = "La categoria no pudo ser eliminado, por que existe una Promocion relacionada a la categoria (".$nombre.")";
                        }
                        if($res->{"num_rows"} == 0){
                            if($sql = $this->con->prepare("UPDATE categorias SET eliminado='1' WHERE id_cae=? AND id_gir=?")){
                                if($sql->bind_param("ii", $id[0], $this->id_gir)){
                                    if($sql->execute()){
                                        $info['tipo'] = "success";
                                        $info['titulo'] = "Eliminado";
                                        $info['texto'] = "Categoria Eliminado";
                                        $info['reload'] = 1;
                                        $info['page'] = "msd/categorias.php?id=".$id[0]."&parent_id=".$id[1];
                                        $this->con_cambios(null);
                                        $sql->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_categoria() #2 '.htmlspecialchars($sql->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_categoria() #2 '.htmlspecialchars($sql->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_categoria() #2 '.htmlspecialchars($sql->error)); }
                        }
                        $sqla->free_result();
                        $sqla->close();

                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_categoria() #1 '.htmlspecialchars($sqla->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_categoria() #1 '.htmlspecialchars($sqla->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_categoria() #1 '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'eliminar_categoria()'); }
        return $info;

    }
    private function crear_pagina(){
        $info['op'] = 2;
        $info['mensaje'] = "Error:";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $image = $this->uploadPagina('/var/www/html/restaurants/images/paginas/', null);
            $id_pag = $_POST['id'];
            $nombre = $_POST['nombre'];
            $html = $_POST['html'];
            $tipo = $_POST['tipo'];
            $visible = $_POST['visible'];
            if($id_pag == 0){
                if($sqlorders = $this->con->prepare("SELECT orders FROM paginas WHERE id_gir=?")){
                    if($sqlorders->bind_param("i", $this->id_gir)){
                        if($sqlorders->execute()){
                            $resorder = $sqlorders->get_result();
                            $orders = $resorder->{"num_rows"};
                            if($sql = $this->con->prepare("INSERT INTO paginas (nombre, html, tipo, id_gir, orders, visible) VALUES (?, ?, ?, ?, ?, ?)")){
                                if($sql->bind_param("ssiiii", $nombre, $html, $tipo, $this->id_gir, $orders, $visible)){
                                    if($sql->execute()){
                                        if($image['op'] == 1){
                                            $id_pag = $this->con->insert_id;
                                            if($sqlupa = $this->con->prepare("UPDATE paginas SET imagen=? WHERE id_pag=? AND id_gir=? AND eliminado=?")){
                                                if($sqlupa->bind_param("siii", $image["image"], $id_pag, $this->id_gir, $this->eliminado)){
                                                    if($sqlupa->execute()){
                                                        $sqlupa->close();
                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #1 '.htmlspecialchars($sqlupa->error)); }
                                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #1 '.htmlspecialchars($sqlupa->error)); }
                                            }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #1 '.htmlspecialchars($this->con->error)); }
                                        }
                                        $info['op'] = 1;
                                        $info['mensaje'] = "Paginas creado exitosamente";
                                        $info['reload'] = 1;
                                        $info['page'] = "msd/configurar_contenido.php";
                                        $this->con_cambios(null);
                                        $sql->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #2 '.htmlspecialchars($sql->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #2 '.htmlspecialchars($sql->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #2 '.htmlspecialchars($this->con->error)); }
                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #0 '.htmlspecialchars($sqlorders->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #0 '.htmlspecialchars($sqlorders->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #0 '.htmlspecialchars($this->con->error)); }
            }
            if($id_pag > 0){
                if($sql = $this->con->prepare("SELECT imagen FROM paginas WHERE id_pag=? AND id_gir=? AND eliminado=?")){
                    if($sql->bind_param("iii", $id_pag, $this->id_gir, $this->eliminado)){
                        if($sql->execute()){
                            $res = $sql->get_result();
                            if($res->{"num_rows"} == 1){
                                if($sqlupa = $this->con->prepare("UPDATE paginas SET nombre=?, html=?, tipo=?, visible=? WHERE id_pag=? AND id_gir=? AND eliminado=?")){
                                    if($sqlupa->bind_param("ssiiiii", $nombre, $html, $tipo, $visible, $id_pag, $this->id_gir, $this->eliminado)){
                                        if($sqlupa->execute()){
                                            if($image["op"] == 1){
                                                $imagen = $res->fetch_all(MYSQLI_ASSOC)[0]["imagen"];
                                                @unlink("/var/www/html/restaurants/images/paginas/".$imagen);
                                                if($sqlupi = $this->con->prepare("UPDATE paginas SET image=? WHERE id_pag=? AND id_gir=? AND eliminado=?")){
                                                    if($sqlupi->bind_param("siii", $image["image"], $id_pag, $this->id_gir, $this->eliminado)){
                                                        if($sqlupi->execute()){
                                                            $sqlupi->close();
                                                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #3 '.htmlspecialchars($sqlupi->error)); }
                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #3 '.htmlspecialchars($sqlupi->error)); }
                                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #3 '.htmlspecialchars($this->con->error)); }
                                            }
                                            $info['op'] = 1;
                                            $info['mensaje'] = "Paginas modificado exitosamente";
                                            $info['reload'] = 1;
                                            $info['page'] = "msd/configurar_contenido.php";
                                            $this->con_cambios(null);
                                            $sqlupa->close();
                                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #4 '.htmlspecialchars($sqlupa->error)); }
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #4 '.htmlspecialchars($sqlupa->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #4 '.htmlspecialchars($this->con->error)); }
                            }
                            if($res->{"num_rows"} == 0){ $this->registrar(7, 0, $this->id_gir, 'crear_pagina()'); }
                            $sql->free_result();
                            $sql->close();
                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #5 '.htmlspecialchars($sql->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #5 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_pagina() #5 '.htmlspecialchars($this->con->error)); }
            }
        }else{ $this->registrar(2, 0, 0, 'crear_pagina()'); }
        return $info;
    }
    private function crear_preguntas(){
        $info['op'] = 2;
        $info['mensaje'] = "Error";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id_pre = $_POST['id'];
            $nombre = $_POST['nombre'];
            $mostrar = $_POST['mostrar'];
            $cantidad = $_POST['cantidad'];
            if($id_pre > 0){
                if($sql = $this->con->prepare("UPDATE preguntas SET nombre=?, mostrar=? WHERE id_pre=? AND id_cat=? AND id_gir=? AND eliminado=?")){
                    if($sql->bind_param("ssiiii", $nombre, $mostrar, $id_pre, $this->id_cat, $this->id_gir, $this->eliminado)){
                        if($sql->execute()){
                            $info['op'] = 1;
                            $info['mensaje'] = "Pregunta modificada exitosamente";
                            $info['reload'] = 1;
                            $info['page'] = "msd/preguntas.php";
                            $this->con_cambios(null);
                            $sql->close();
                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_preguntas() #1 '.htmlspecialchars($sql->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_preguntas() #1 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_preguntas() #1 '.htmlspecialchars($this->con->error)); }
            }
            if($id_pre == 0){
                if($sql = $this->con->prepare("INSERT INTO preguntas (nombre, mostrar, id_cat, id_gir) VALUES (?, ?, ?, ?)")){
                    if($sql->bind_param("ssii", $nombre, $mostrar, $this->id_cat, $this->id_gir)){
                        if($sql->execute()){
                            $info['op'] = 1;
                            $info['mensaje'] = "Pregunta creada exitosamente";
                            $info['reload'] = 1;
                            $info['page'] = "msd/preguntas.php";
                            $id_pre = $this->con->insert_id;
                            $sql->close();
                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_preguntas() #2 '.htmlspecialchars($sql->error)); }
                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_preguntas() #2 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_preguntas() #2 '.htmlspecialchars($this->con->error)); }
            }
            if($sqldpv = $this->con->prepare("DELETE FROM preguntas_valores WHERE id_pre=?")){
                if($sqldpv->bind_param("i", $id_pre)){
                    if($sqldpv->execute()){
                        for($i=0; $i<$cantidad; $i++){
                            $cant = $_POST["cant-".$i];
                            $valores = $_POST["valores-".$i];
                            $nombre = $_POST["nombre-".$i];
                            $valores_json = json_encode(explode(",", $valores), JSON_UNESCAPED_UNICODE);
                            if($cant > 0){
                                if($sqlipv = $this->con->prepare("INSERT INTO preguntas_valores (cantidad, nombre, valores, id_pre) VALUES (?, ?, ?, ?)")){
                                    if($sqlipv->bind_param("issi", $cant, $nombre, $valores_json, $id_pre)){
                                        if($sqlipv->execute()){
                                            $sqlipv->close();
                                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_preguntas() #3 '.htmlspecialchars($sqlipv->error)); }
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_preguntas() #3 '.htmlspecialchars($sqlipv->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_preguntas() #3 '.htmlspecialchars($this->con->error)); }
                            }
                        }
                        $sqldpv->close();
                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_preguntas() #4 '.htmlspecialchars($sqldpv->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_preguntas() #4 '.htmlspecialchars($sqldpv->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'crear_preguntas() #4 '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'crear_preguntas()'); }
        return $info;
    }
    private function crear_productos(){

        $info['op'] = 2;
        $info['mensaje'] = "Error";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id_cae = $_POST['id'];
            if($sql = $this->con->prepare("SELECT * FROM categorias WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?")){
                if($sql->bind_param("iiii", $id_cae, $this->id_cat, $this->id_gir, $this->eliminado)){
                    if($sql->execute()){
                        $res = $sql->get_result();
                        if($res->{"num_rows"} == 1){
                            if($sqlcp = $this->con->prepare("SELECT * FROM cat_pros WHERE id_cae=?")){
                                if($sqlcp->bind_param("i", $id_cae)){
                                    if($sqlcp->execute()){
                                        $rescp = $sqlcp->get_result();
                                        $orders = $rescp->{"num_rows"};
                                        $tipo = $_POST['tipo'];
                                        if($tipo == 0){
                                            $precio = $_POST['precio'];
                                            $parent_id = $_POST['parent_id'];
                                            $id_pro = $_POST['id_pro'];
                                            $numero = $_POST['numero'];
                                            $nombre = $_POST['nombre'];
                                            $nombre_carro = $_POST['nombre_carro'];
                                            $descripcion = $_POST['descripcion'];
                                            if($id_pro == 0){
                                                if($sqlip = $this->con->prepare("INSERT INTO productos (numero, nombre, nombre_carro, descripcion, fecha_creado, id_gir) VALUES (?, ?, ?, ?, now(), ?)")){
                                                    if($sqlip->bind_param("isssi", $numero, $nombre, $nombre_carro, $descripcion, $this->id_gir)){
                                                        if($sqlip->execute()){
                                                            $id_pro = $this->con->insert_id;
                                                            if($sqlipr = $this->con->prepare("INSERT INTO cat_pros (id_cae, id_pro, orders) VALUES (?, ?, ?)")){
                                                                if($sqlipr->bind_param("iii", $id_cae, $id_pro, $orders)){
                                                                    if($sqlipr->execute()){
                                                                        $sqlipr->close();
                                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #1 '.htmlspecialchars($sqlipr->error)); }
                                                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #1 '.htmlspecialchars($sqlipr->error)); }
                                                            }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #1 '.htmlspecialchars($this->con->error)); }
                                                            if($sqlipp = $this->con->prepare("INSERT INTO productos_precio (id_cat, id_pro, precio) VALUES (?, ?, ?)")){
                                                                if($sqlipp->bind_param("iii", $this->id_cat, $id_pro, $precio)){
                                                                    if($sqlipp->execute()){
                                                                        $sqlipp->close();
                                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #2 '.htmlspecialchars($sqlipp->error)); }
                                                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #2 '.htmlspecialchars($sqlipp->error)); }
                                                            }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #2 '.htmlspecialchars($this->con->error)); }            
                                                            $sqlip->close();
                                                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #3 '.htmlspecialchars($sqlip->error)); }
                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #3 '.htmlspecialchars($sqlip->error)); }
                                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #3 '.htmlspecialchars($this->con->error)); }
                                            }
                                            if($id_pro > 0){
                                                if($sqlup = $this->con->prepare("UPDATE productos SET numero=?, nombre=?, nombre_carro=?, descripcion=? WHERE id_pro=? AND id_gir=? AND eliminado=?")){
                                                    if($sqlup->bind_param("isssiii", $numero, $nombre, $nombre_carro, $descripcion, $id_pro, $this->id_gir, $this->eliminado)){
                                                        if($sqlup->execute()){
                                                            $sqlup->close();
                                                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #4 '.htmlspecialchars($sqlup->error)); }
                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #4 '.htmlspecialchars($sqlup->error)); }
                                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #4 '.htmlspecialchars($this->con->error)); }
                                                if($sqlupp = $this->con->prepare("UPDATE productos_precio SET precio=? WHERE id_pro=? AND id_cat=?")){
                                                    if($sqlupp->bind_param("iii", $precio, $id_pro, $this->id_cat)){
                                                        if($sqlupp->execute()){
                                                            $sqlupp->close();
                                                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #5 '.htmlspecialchars($sqlupp->error)); }
                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #5 '.htmlspecialchars($sqlupp->error)); }
                                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #5 '.htmlspecialchars($this->con->error)); }
                                            }
                                        }
                                        if($tipo == 1){
                                            $all_prods = $this->get_productos();
                                            for($i=0; $i<count($all_prods); $i++){
                                                $pro = $_POST['prod-'.$all_prods[$i]['id_pro']];
                                                if($pro == 1){
                                                    if($sqlxip = $this->con->prepare("INSERT INTO cat_pros (id_cae, id_pro, orders) VALUES (?, ?, ?)")){
                                                        if($sqlxip->bind_param("iii", $id_cae, $all_prods[$i]["id_pro"], $orders)){
                                                            if($sqlxip->execute()){
                                                                $sqlxip->close();
                                                            }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #6 '.htmlspecialchars($sqlxip->error)); }
                                                        }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #6 '.htmlspecialchars($sqlxip->error)); }
                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #6 '.htmlspecialchars($this->con->error)); }
                                                }
                                            }
                                        }
                                        $info['op'] = 1;
                                        $info['mensaje'] = "Producto modificado exitosamente";
                                        $info['reload'] = 1;
                                        $info['page'] = "msd/crear_productos.php?id=".$id_cae."&parent_id=".$parent_id;
                                        $this->con_cambios(null);
                                        $sqlcp->free_result();
                                        $sqlcp->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #7 '.htmlspecialchars($sqlcp->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #7 '.htmlspecialchars($sqlcp->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #7 '.htmlspecialchars($this->con->error)); }
                        }
                        if($res->{"num_rows"} == 0){ $this->registrar(7, 0, $this->id_gir, 'crear_productos()'); }
                        $sql->free_result();
                        $sql->close();
                    }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #8 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #8 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'crear_productos() #8 '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'crear_productos()'); }
        return $info;
    }
    private function get_productos(){
        if($sql = $this->con->prepare("SELECT * FROM productos WHERE id_gir=? AND eliminado=?")){
            if($sql->bind_param("ii", $this->id_gir, $this->eliminado)){
                if($sql->execute()){
                    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                    $sql->free_result();
                    $sql->close();
                    return $result;
                }else{ $this->registrar(6, 0, $this->id_gir, 'get_productos() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'get_productos() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, 0, $this->id_gir, 'get_productos() '.htmlspecialchars($this->con->error)); }
    }
    private function eliminar_horario(){
        $info['tipo'] = "error";
        $info['titulo'] = "Error";
        $info['texto'] = "Error:";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id = explode("/", $_POST['id']);
            if($sql = $this->con->prepare("UPDATE horarios SET eliminado='1' WHERE id_hor=? AND id_gir=?")){
                if($sql->bind_param("ii", $id[0], $this->id_gir)){
                    if($sql->execute()){
                        $info['tipo'] = "success";
                        $info['titulo'] = "Eliminado";
                        $info['texto'] = "Horario ".$_POST["nombre"]." Eliminado";
                        $info['reload'] = 1;
                        $info['page'] = "msd/crear_horario.php?id_loc=".$id[1]."&nombre=".$id[2];
                        $sql->close();
                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #1 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #1 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #1 '.htmlspecialchars($sql->error)); }
            if($sqlre = $this->con->prepare("SELECT * FROM horarios WHERE id_gir=? AND eliminado=? AND (tipo='0' OR tipo='1')")){
                if($sqlre->bind_param("ii", $this->id_gir, $this->eliminado)){
                    if($sqlre->execute()){
                        $resre = $sqlre->get_result();
                        if($resre->{"num_rows"} == 0){
                            if($sqlure = $this->con->prepare("UPDATE giros SET retiro_local='0' WHERE id_gir=? AND eliminado=?")){
                                if($sqlure->bind_param("ii", $this->id_gir, $this->eliminado)){
                                    if($sqlure->execute()){
                                        $sqlure->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #2 '.htmlspecialchars($sqlure->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #2 '.htmlspecialchars($sqlure->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #2 '.htmlspecialchars($this->con->error)); }
                        }
                        if($resre->{"num_rows"} > 0){
                            if($sqlure = $this->con->prepare("UPDATE giros SET retiro_local='1' WHERE id_gir=? AND eliminado=?")){
                                if($sqlure->bind_param("ii", $this->id_gir, $this->eliminado)){
                                    if($sqlure->execute()){
                                        $sqlure->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #3 '.htmlspecialchars($sqlure->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #3 '.htmlspecialchars($sqlure->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #3 '.htmlspecialchars($this->con->error)); }
                        }
                        $sqlre->free_result();
                        $sqlre->close();
                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #4 '.htmlspecialchars($sqlre->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #4 '.htmlspecialchars($sqlre->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #4 '.htmlspecialchars($this->con->error)); }
            if($sqlde = $this->con->prepare("SELECT * FROM horarios WHERE id_gir=? AND eliminado=? AND (tipo='0' OR tipo='2')")){
                if($sqlde->bind_param("ii", $this->id_gir, $this->eliminado)){
                    if($sqlde->execute()){
                        $resde = $sqlde->get_result();
                        if($resde->{"num_rows"} == 0){
                            if($sqlude = $this->con->prepare("UPDATE giros SET despacho_domicilio='0' WHERE id_gir=? AND eliminado=?")){
                                if($sqlude->bind_param("ii", $this->id_gir, $this->eliminado)){
                                    if($sqlude->execute()){
                                        $sqlude->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #5 '.htmlspecialchars($sqlude->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #5 '.htmlspecialchars($sqlude->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #5 '.htmlspecialchars($this->con->error)); }
                        }
                        if($resde->{"num_rows"} > 0){
                            if($sqlude = $this->con->prepare("UPDATE giros SET despacho_domicilio='1' WHERE id_gir=? AND eliminado=?")){
                                if($sqlude->bind_param("ii", $this->id_gir, $this->eliminado)){
                                    if($sqlude->execute()){
                                        $sqlude->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #6 '.htmlspecialchars($sqlude->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #6 '.htmlspecialchars($sqlude->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #6 '.htmlspecialchars($this->con->error)); }
                        }
                        $sqlde->free_result();
                        $sqlde->close();
                    }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #7 '.htmlspecialchars($sqlde->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #7 '.htmlspecialchars($sqlde->error)); }
            }else{ $this->registrar(6, 0, $this->id_gir, 'eliminar_horario() #7 '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(2, 0, 0, 'eliminar_horario()'); }
        return $info;
    }
    private function eliminar_usuario_local(){   
        $id = $_POST['id'];
        $id_loc = $_POST['id_loc'];
        $info['tipo'] = "error";
        $info['titulo'] = "Error";
        $info['texto'] = "Usuario no pudo ser eliminado";
        if($sql = $this->con->prepare("UPDATE fw_usuarios SET eliminado='1' WHERE id_user=? AND admin='0' AND id_loc=? AND id_gir=?")){
            if($sql->bind_param("iii", $id, $id_loc, $this->id_gir)){
                if($sql->execute()){
                    $info['tipo'] = "success";
                    $info['titulo'] = "Eliminado";
                    $info['texto'] = "Usuario Eliminado";
                    $info['reload'] = 1;
                    $info['page'] = "msd/usuarios.php";
                    $sql->close();
                }else{ $this->registrar(6, $id_loc, $this->id_gir, 'eliminar_usuario_local() '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, $id_loc, $this->id_gir, 'eliminar_usuario_local() '.htmlspecialchars($sql->error)); }
        }else{ $this->registrar(6, $id_loc, $this->id_gir, 'eliminar_usuario_local()'.htmlspecialchars($sql->error)); }
        return $info;
    }
    private function eliminar_usuario_admin(){   
        $info['tipo'] = "error";
        $info['titulo'] = "Error";
        $info['texto'] = "Usuario no pudo ser eliminado";
        if($this->admin == 1){
            $id = $_POST['id'];
            if($sql = $this->con->prepare("SELECT t2.id_gir, t1.nombre FROM fw_usuarios t1, fw_usuarios_giros t2 WHERE t1.id_user=? AND t1.id_user=t2.id_user")){
                if($sql->bind_param("i", $id)){
                    if($sql->execute()){
                        $res = $sql->get_result();
                        $info = $res->fetch_all(MYSQLI_ASSOC)[0];
                        if($sql->{"num_rows"} == 1){
                            if($sqlus = $this->con->prepare("SELECT * FROM fw_usuarios_giros_clientes WHERE id_user=? AND id_gir=?")){
                                if($sqlus->bind_param("ii", $this->id_user, $info["id_gir"])){
                                    if($sqlus->execute()){
                                        $resus = $sqlus->get_result();
                                        if($resus->{"num_rows"} == 1){
                                            if($sqluus = $this->con->prepare("UPDATE fw_usuarios SET eliminado='1' WHERE id_user=?")){
                                                if($sqluus->bind_param("i", $id)){
                                                    if($sqluus->execute()){
                                                        $info['tipo'] = "success";
                                                        $info['titulo'] = "Eliminado";
                                                        $info['texto'] = "Usuario Eliminado";
                                                        $info['reload'] = 1;
                                                        $info['page'] = "msd/usuarios.php";
                                                        $sqluus->close();
                                                    }else{ $this->registrar(6, 0, 0, 'eliminar_usuario_admin() #1 '.htmlspecialchars($sqluus->error)); }
                                                }else{ $this->registrar(6, 0, 0, 'eliminar_usuario_admin() #1 '.htmlspecialchars($sqluus->error)); }
                                            }else{ $this->registrar(6, 0, 0, 'eliminar_usuario_admin() #1 '.htmlspecialchars($this->con->error)); }
                                        }
                                        if($resus->{"num_rows"} == 0){ $this->registrar(7, 0, 0, 'no permisos del user admin'); }
                                        $sqlus->free_result();
                                        $sqlus->close();
                                    }else{ $this->registrar(6, 0, 0, 'eliminar_usuario_admin() #2 '.htmlspecialchars($sqlus->error)); }
                                }else{ $this->registrar(6, 0, 0, 'eliminar_usuario_admin() #2 '.htmlspecialchars($sqlus->error)); }
                            }else{$this->registrar(6, 0, 0, 'eliminar_usuario_admin() #2 '.htmlspecialchars($this->con->error));  }
                        }
                        if($sql->{"num_rows"} == 0){ $this->registrar(7, 0, 0, 'eliminar_usuario_admin()'); }
                        $sql->free_result();
                        $sql->close();
                    }else{ $this->registrar(6, 0, 0, 'eliminar_usuario_admin() #3 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, 0, 'eliminar_usuario_admin() #3 '.htmlspecialchars($sql->error)); }
            }else{ $this->registrar(6, 0, 0, 'eliminar_usuario_admin() #3 '.htmlspecialchars($this->con->error)); }
        }else{ $this->registrar(4, 0, 0, 'eliminar_usuario_admin()'); }
        return $info;
    }
    private function crear_usuario(){

        $info['op'] = 2;
        $info['mensaje'] = "Error";

        if($this->id_user == 1){

            $correo = $_POST['correo'];
            $tipo = $_POST['tipo'];

            if($sqlus = $this->con->prepare("SELECT id_user FROM fw_usuarios WHERE correo=?")){
                if($sqlus->bind_param("s", $correo)){
                    if($sqlus->execute()){

                        $resus = $sqlus->get_result();
                        $id_user = $resus->fetch_all(MYSQLI_ASSOC)[0]["id_user"];
                        $id = $_POST['id'];
                        $nombre = $_POST['nombre'];

                        if($resus->{"num_rows"} == 0 || ($resus->{"num_rows"} == 1 && $id == $id_user)){
                            
                            if($id > 0){

                                if($sqluus = $this->con->prepare("UPDATE fw_usuarios SET nombre=?, correo=? WHERE id_user=? AND eliminado=?")){
                                    if($sqluus->bind_param("ssii", $nombre, $correo, $id_user, $this->eliminado)){
                                        if($sqluus->execute()){
                                            $info['op'] = 1;
                                            $info['mensaje'] = "Usuarios modificado exitosamente";
                                            $info['reload'] = 1;
                                            $info['page'] = "msd/usuarios.php";
                                            $sqluus->close();
                                        }else{ $this->registrar(6, 0, 0, 'crear_usuario() #1 '.htmlspecialchars($sqluus->error)); }
                                    }else{ $this->registrar(6, 0, 0, 'crear_usuario() #1 '.htmlspecialchars($sqluus->error)); }
                                }else{ $this->registrar(6, 0, 0, 'crear_usuario() #1 '.htmlspecialchars($this->con->error)); }
                                
                                if($tipo == 0 && $this->id_user == 1){
                                    $re = 0;
                                    if($sqlup = $this->con->prepare("UPDATE fw_usuarios SET re_venta=? WHERE id_user=? AND eliminado=?")){
                                        if($sqlup->bind_param("iii", $re, $id_user, $this->eliminado)){
                                            if($sqlup->execute()){
                                                $sqlup->close();
                                            }else{ $this->registrar(6, 0, 0, 'crear_usuario() #2 '.htmlspecialchars($sqlup->error)); }
                                        }else{ $this->registrar(6, 0, 0, 'crear_usuario() #2 '.htmlspecialchars($sqlup->error)); }
                                    }else{ $this->registrar(6, 0, 0, 'crear_usuario() #2 '.htmlspecialchars($this->con->error)); }
                                }

                                if($tipo == 1 && $this->id_user == 1){
                                    $re = 1;
                                    if($sqlup = $this->con->prepare("UPDATE fw_usuarios SET re_venta=? WHERE id_user=? AND eliminado=?")){
                                        if($sqlup->bind_param("iii", $re, $id_user, $this->eliminado)){
                                            if($sqlup->execute()){
                                                $sqlup->close();
                                            }else{ $this->registrar(6, 0, 0, 'crear_usuario() #3 '.htmlspecialchars($sqlup->error)); }
                                        }else{ $this->registrar(6, 0, 0, 'crear_usuario() #3 '.htmlspecialchars($sqlup->error)); }
                                    }else{ $this->registrar(6, 0, 0, 'crear_usuario() #3 '.htmlspecialchars($this->con->error)); }
                                }

                            }

                            if($id == 0){

                                $admin = 1;

                                if($tipo == 0){
                                    if($sqlius = $this->con->prepare("INSERT INTO fw_usuarios (nombre, fecha_creado, correo, admin, id_aux_user) VALUES (?, now(), ?, ?, ?)")){
                                        if($sqlius->bind_param("ssii", $nombre, $correo, $admin, $this->id_user)){
                                            if($sqlius->execute()){
                                                $info['op'] = 1;
                                                $info['mensaje'] = "Usuarios agregado exitosamente";
                                                $info['reload'] = 1;
                                                $info['page'] = "msd/usuarios.php";
                                                $sqlius->close();
                                            }else{ $this->registrar(6, 0, 0, 'crear_usuario() #4 '.htmlspecialchars($sqlius->error)); }
                                        }else{ $this->registrar(6, 0, 0, 'crear_usuario() #4 '.htmlspecialchars($sqlius->error)); }
                                    }else{ $this->registrar(6, 0, 0, 'crear_usuario() #4 '.htmlspecialchars($this->con->error)); }
                                }

                                if($tipo == 1 && $this->id_user == 1){
                                    $reventa = 1;
                                    if($sqlius = $this->con->prepare("INSERT INTO fw_usuarios (nombre, fecha_creado, correo, admin, re_venta) VALUES (?, now(), ?, ?, ?)")){
                                        if($sqlius->bind_param("ssii", $nombre, $correo, $admin, $reventa)){   
                                            if($sqlius->execute()){
                                                $info['op'] = 1;
                                                $info['mensaje'] = "Usuarios agregado exitosamente";
                                                $info['reload'] = 1;
                                                $info['page'] = "msd/usuarios.php";
                                                $sqlius->close();
                                            }else{ $this->registrar(6, 0, 0, 'crear_usuario() #5 '.htmlspecialchars($sqlius->error)); }
                                        }else{ $this->registrar(6, 0, 0, 'crear_usuario() #5 '.htmlspecialchars($sqlius->error)); }
                                    }else{ $this->registrar(6, 0, 0, 'crear_usuario() #5 '.htmlspecialchars($this->con->error)); }
                                }

                            }

                        }else{ $this->registrar(7, 0, 0, 'crear_usuario()'); }

                        $sqlus->free_result();
                        $sqlus->close();

                    }else{ $this->registrar(6, 0, 0, 'crear_usuario() #6 '.htmlspecialchars($sqlus->error)); }
                }else{ $this->registrar(6, 0, 0, 'crear_usuario() #6 '.htmlspecialchars($sqlus->error)); }
            }else{ $this->registrar(6, 0, 0, 'crear_usuario() #6 '.htmlspecialchars($this->con->error)); }
        
        }else{ $this->registrar(1, 0, 0, 'crear_usuario()'); }
        return $info;
    }
    private function asignar_prods_promocion(){

        $info['op'] = 2;
        $info['mensaje'] = "Error";
        if(isset($this->id_gir) && is_numeric($this->id_gir) && $this->id_gir > 0){
            $id_cae = $_POST['id_cae'];
            if($sql = $this->con->prepare("SELECT * FROM categorias WHERE id_cae=? AND id_cat=? AND id_gir=? AND eliminado=?")){
                if($sql->bind_param("iiii", $id_cae, $this->id_cat, $this->id_gir, $this->eliminado)){
                    if($sql->execute()){
                        $res = $sql->get_result();
                        if($res->{"num_rows"} == 1){
                            $precio = $_POST['precio'];
                            if($sqluc = $this->con->prepare("UPDATE categorias SET precio=? WHERE id_cae=?")){
                                if($sqluc->bind_param("ii", $precio, $id_cae)){
                                    if($sqluc->execute()){
                                        if($sqlepc = $this->con->prepare("DELETE FROM promocion_categoria WHERE id_cae1=?")){
                                            if($sqlepc->bind_param("i", $id_cae)){
                                                if($sqlepc->execute()){
                                                    if($sqlepp = $this->con->prepare("DELETE FROM promocion_productos WHERE id_cae=?")){
                                                        if($sqlepp->bind_param("i", $id_cae)){
                                                            if($sqlepp->execute()){
                                                                $values = $this->list_arbol_cats_prods();
                                                                $parent_id = $_POST['parent_id'];
                                                                for($i=0; $i<count($values); $i++){
                                                                    $value = $values[$i];
                                                                    if($value['id_cae'] !== null){
                                                                        $cae_val = $_POST['sel-cae-'.$value['id_cae']];
                                                                        if($cae_val > 0){

                                                                            if($sqlspc = $this->con->prepare("SELECT * FROM promocion_categoria WHERE id_cae1=? AND id_cae2=?")){
                                                                                if($sqlspc->bind_param("ii", $id_cae, $value["id_cae"])){
                                                                                    if($sqlspc->execute()){
                                                                                        $resspc = $sqlspc->get_result();
                                                                                        if($resspc->{'num_rows'} == 0){
                                                                                            if($sqlipc = $this->con->prepare("INSERT INTO promocion_categoria (id_cae1, id_cae2, cantidad) VALUES (?, ?, ?)")){
                                                                                                if($sqlipc->bind_param("iii", $id_cae, $value["id_cae"], $cae_val)){
                                                                                                    if($sqlipc->execute()){
                                                                                                        $sqlipc->close();
                                                                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #1 '.htmlspecialchars($sqlipc->error)); }
                                                                                                }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #1 '.htmlspecialchars($sqlipc->error)); }
                                                                                            }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #1 '.htmlspecialchars($this->con->error)); }
                                                                                        }
                                                                                        $sqlspc->free_result();
                                                                                        $sqlspc->close();
                                                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #1 '.htmlspecialchars($sqlspc->error)); }
                                                                                }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #1 '.htmlspecialchars($sqlspc->error)); }
                                                                            }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #1 '.htmlspecialchars($this->con->error)); }
                                                                            
                                                                        }
                                                                    }
                                                                    if($value['id_pro'] !== null){
                                                                        $pro_val = $_POST['sel-pro-'.$value['id_pro'].'-'.$value['id_cae']];
                                                                        if($pro_val > 0){

                                                                            if($sqlspp = $this->con->prepare("SELECT * FROM promocion_categoria WHERE id_cae1=? AND id_cae2=?")){
                                                                                if($sqlspp->bind_param("ii", $id_cae, $value["id_cae"])){
                                                                                    if($sqlspp->execute()){
                                                                                        $resspp = $sqlspp->get_result();
                                                                                        if($resspp->{'num_rows'} == 0){

                                                                                            if($sqlipp = $this->con->prepare("INSERT INTO promocion_productos (id_cae, id_pro, cantidad, parent_id) VALUES (?, ?, ?, ?)")){
                                                                                                if($sqlipp->bind_param("iiii", $id_cae, $value["id_pro"], $pro_val, $value['id_cae'])){
                                                                                                    if($sqlipp->execute()){
                                                                                                        $sqlipp->close();
                                                                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #2 '.htmlspecialchars($sqlipp->error)); }
                                                                                                }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #2 '.htmlspecialchars($sqlipp->error)); }
                                                                                            }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #2 '.htmlspecialchars($this->con->error)); }

                                                                                        }
                                                                                        if($resspp->{'num_rows'} == 1){
                                                                                            
                                                                                            if($sqlxpp = $this->con->prepare("UPDATE promocion_productos SET cantidad=?, parent_id=? WHERE id_cae=? AND id_pro=?")){
                                                                                                if($sqlxpp->bind_param("iiii", $pro_val, $value['id_cae'], $id_cae, $value["id_pro"])){
                                                                                                    if($sqlxpp->execute()){
                                                                                                        $sqlxpp->close();
                                                                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #2 '.htmlspecialchars($sqlxpp->error)); }
                                                                                                }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #2 '.htmlspecialchars($sqlxpp->error)); }
                                                                                            }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #2 '.htmlspecialchars($this->con->error)); }

                                                                                        }
                                                                                        $sqlspp->free_result();
                                                                                        $sqlspp->close();
                                                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #2 '.htmlspecialchars($sqlspp->error)); }
                                                                                }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #2 '.htmlspecialchars($sqlspp->error)); }
                                                                            }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #2 '.htmlspecialchars($this->con->error)); }

                                                                        }
                                                                    }
                                                                }
                                                                $info['op'] = 1;
                                                                $info['mensaje'] = "Productos Asignados";
                                                                $info['reload'] = 1;
                                                                $info['page'] = "msd/categorias.php?parent_id=".$parent_id;
                                                                $this->con_cambios(null);
                                                                $sqlepp->close();
                                                            }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #3 '.htmlspecialchars($sqlepp->error)); }
                                                        }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #3 '.htmlspecialchars($sqlepp->error)); }
                                                    }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #3 '.htmlspecialchars($this->con->error)); }
                                                    $sqlepc->close();
                                                }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #4 '.htmlspecialchars($sqlepc->error)); }
                                            }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #4 '.htmlspecialchars($sqlepc->error)); }
                                        }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #4 '.htmlspecialchars($this->con->error)); }
                                        $sqluc->close();
                                    }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #5 '.htmlspecialchars($sqluc->error)); }
                                }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #5 '.htmlspecialchars($sqluc->error)); }
                            }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #5 '.htmlspecialchars($this->con->error)); }
                        }
                        if($res->{"num_rows"} == 0){ $this->registrar(7, 0, $this->id_gir, 'asignar_prods_promocion()'); }
                        $sql->free_result();
                        $sql->close();
                    }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #6 '.htmlspecialchars($sql->error)); }
                }else{ $this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #6 '.htmlspecialchars($sql->error)); }
            }else{$this->registrar(6, 0, $this->id_gir, 'asignar_prods_promocion() #6 '.htmlspecialchars($this->con->error));  }
        }else{ $this->registrar(2, 0, 0, 'asignar_prods_promocion()'); }
        return $info;
    }
    public function pass_generate($n){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for($i=0; $i<$n; $i++){
            $r .= $chars{rand(0, strlen($chars)-1)};
        }
        return $r;
    }
}