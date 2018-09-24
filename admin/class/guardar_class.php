<?php
session_start();

require_once($path."admin/class/core_class.php");

class Guardar extends Core{
    
    public $con = null;
    public $id_user = null;
    public $id_gir = null;
    
    public function __construct(){
        
        $this->con = new Conexion();
        $this->id_user = $_SESSION['user']['info']['id_user'];
        $this->id_gir = $_SESSION['user']['giro']['id_gir'];
        
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
        if($_POST['accion'] == "configurar_catalogo"){
            return $this->configurar_catalogo();
        }
        
    }
    private function configurar_catalogo(){
        
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        
        $titulo = $_POST['titulo'];
        $font_family = $_POST['font-family'];
        $font_css = $_POST['font-css'];
        $css_types = $_POST['css-types'];
        $css_colores = $_POST['css-colores'];
        $css_popup = $_POST['css-popup'];
                
        $this->con->sql("UPDATE giros SET titulo='".$titulo."', font_family='".$font_family."', font_css='".$font_css."', style_page='".$css_types."', style_color='".$css_colores."', style_modal='".$css_popup."' WHERE id_gir='".$id_gir."'");
        
        $info['op'] = 1;
        $info['mensaje'] = "Configuracion modificado exitosamente";
        
        $info['reload'] = 1;
        $info['page'] = "apps/ver_giro.php?id=".$id."&nombre=".$nombre;
        return $info;
        
    }
    private function crear_giro(){
        
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $dominio = $_POST['dominio'];

        if($id == 0){
            $aux = $this->con->sql("INSERT INTO giros (nombre, fecha_creado, dominio) VALUES ('".$nombre."', now(), '".$dominio."')");
            $info['op'] = 1;
            $info['mensaje'] = "Giro creado exitosamente";
            $this->con->sql("INSERT INTO fw_usuarios_giros (id_user, id_gir) VALUES ('".$this->id_user."', '".$aux['insert_id']."')");
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
        $this->con->sql("UPDATE giros SET eliminado='1' WHERE id_gir='".$id."' AND id_user='".$this->id_user."'");
        
        $info['tipo'] = "success";
        $info['titulo'] = "Eliminado";
        $info['texto'] = "Giro ".$_POST["nombre"]." Eliminado";
        $info['reload'] = 1;
        $info['page'] = "base/giros.php";

        return $info;
        
    }
    private function crear_catalogo(){
        
        $id = $_POST['id'];
        $id_cat = $_POST['id_cat'];
        $nombre = $_POST['nombre'];
        
        if($id_cat == 0){
            $this->con->sql("INSERT INTO catalogo_productos (nombre, fecha_creado, id_gir) VALUES ('".$nombre."', now(), '".$id."')");
            $info['op'] = 1;
            $info['mensaje'] = "Catalogo creado exitosamente";
        }
        if($id_cat > 0){
            $this->con->sql("UPDATE catalogo_productos SET nombre='".$nombre."' WHERE id_cat='".$id_cat."' AND id_gir='".$id."'");
            $info['op'] = 1;
            $info['mensaje'] = "Catalogo modificado exitosamente";
        }
        
        $info['reload'] = 1;
        $info['page'] = "apps/catalogo_productos.php?id=".$id;
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
        
        $id = $_POST['id'];
        $id_loc = $_POST['id_loc'];
        $id_cat = $_POST['id_cat'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        
        if($id_loc == 0){
            $this->con->sql("INSERT INTO locales (nombre, direccion, fecha_creado, id_gir, id_cat) VALUES ('".$nombre."', '".$direccion."', now(), '".$id."', '".$id_cat."')");
            $info['op'] = 1;
            $info['mensaje'] = "Local creado exitosamente";
        }
        if($id_loc > 0){
            $this->con->sql("UPDATE locales SET nombre='".$nombre."', direccion='".$direccion."', id_cat='".$id_cat."' WHERE id_loc='".$id_loc."' AND id_gir='".$id."'");
            $info['op'] = 1;
            $info['mensaje'] = "Local modificado exitosamente";
        }
        
        $info['reload'] = 1;
        $info['page'] = "apps/locales.php?id=".$id;
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
        $id_gir = $_POST['id_gir'];
        $admin = 0;
        
        if($this->id_user == 1){
            $admin = 1;
        }
        
        if($id == 0){
            $aux = $this->con->sql("INSERT INTO fw_usuarios (nombre, correo, fecha_creado, admin) VALUES ('".$nombre."', '".$correo."', now(), '".$admin."')");
            $info['op'] = 1;
            $info['mensaje'] = "Usuario creado exitosamente";
            if($admin == 0 || ($admin == 1 && $this->id_gir !== null)){
                $this->con->sql("INSERT INTO fw_usuarios_giros_clientes (id_user, id_gir) VALUES ('".$aux['insert_id']."', '".$this->id_gir."')");
            }
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

        $id = $_POST['id'];
        $id_cae = $_POST['id_cae'];
        $nombre = $_POST['nombre'];
        $parent_id = $_POST['parent_id'];

        if($id_cae == 0){
            $this->con->sql("INSERT INTO categorias (nombre, parent_id, id_cat) VALUES ('".$nombre."', '".$parent_id."', '".$id."')");
            $info['op'] = 1;
            $info['mensaje'] = "Categoria creada exitosamente";
        }
        if($id_cae > 0){
            $this->con->sql("UPDATE categorias SET nombre='".$nombre."' WHERE id_cae='".$id_cae."'");
            $info['op'] = 1;
            $info['mensaje'] = "Categoria modificada exitosamente";
        }
                
        $info['reload'] = 1;
        $info['page'] = "apps/categorias.php?id=".$id."&parent_id=".$parent_id;
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

        $id = $_POST['id'];
        $id_prm = $_POST['id_prm'];
        $nombre = $_POST['nombre'];
        $parent_id = $_POST['parent_id'];

        if($id_prm == 0){
            $this->con->sql("INSERT INTO promociones (nombre, parent_id, id_cat) VALUES ('".$nombre."', '".$parent_id."', '".$id."')");
            $info['op'] = 1;
            $info['mensaje'] = "Promocion creada exitosamente";
        }
        if($id_prm > 0){
            $this->con->sql("UPDATE promociones SET nombre='".$nombre."' WHERE id_prm='".$id_prm."'");
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
        
        $id = $_POST['id'];
        $id_prm = $_POST['id_prm'];
        $parent_id = $_POST['parent_id'];
        $values = $this->list_arbol_cats_prods($id);
        
        $this->con->sql("DELETE FROM promocion_categoria WHERE id_prm='".$id_prm."'");
        $this->con->sql("DELETE FROM promocion_productos WHERE id_prm='".$id_prm."'");
        
        for($i=0; $i<count($values); $i++){

            $value = $values[$i];
            if($value['id_cae'] !== null){
                $cae_val = $_POST['sel-cae-'.$value['id_cae']];
                if($cae_val > 0){
                    $this->con->sql("INSERT INTO promocion_categoria (id_prm, id_cae, cantidad) VALUES ('".$id_prm."', '".$value['id_cae']."', '".$cae_val."')");
                }
            }
            if($value['id_pro'] !== null){
                $pro_val = $_POST['sel-pro-'.$value['id_pro']];
                if($pro_val > 0){
                    $this->con->sql("INSERT INTO promocion_productos (id_prm, id_pro, cantidad) VALUES ('".$id_prm."', '".$value['id_pro']."', '".$pro_val."')");
                }
            }
            
        }      
        $info['reload'] = 1;
        $info['page'] = "apps/promociones.php?id=".$id."&parent_id=".$parent_id;
        return $info;
        
    }
    
    private function crear_productos(){

        $id_pro = $_POST['id_pro'];
        $id_cae = $_POST['id'];
        $nombre = $_POST['nombre'];
        $this->get_inputs($id_cae);
        $inputs = $this->show_inputs();
        
        if($id_pro == 0){
            $sql = $this->con->sql("INSERT INTO productos (id_pro) VALUES (NULL)");
            $id_pro = $sql['insert_id'];
            $this->con->sql("INSERT INTO cat_pros (id_cae, id_pro) VALUES ('".$id_cae."', '".$id_pro."')");
        }
        for($i=0; $i<count($inputs); $i++){
            $this->con->sql("UPDATE productos SET ".$inputs[$i]['campo']."='".$_POST[$inputs[$i]['campo']]."' WHERE id_pro='".$id_pro."'");
        }
        
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

        $id = $_POST['id'];
        $id_pre = $_POST['id_pre'];
        $nombre = $_POST['nombre'];
        $cantidad = $_POST['cantidad'];

        if($id_pre > 0){
            $this->con->sql("UPDATE preguntas SET nombre='".$nombre."' WHERE id_pre='".$id_pre."'");
            $info['op'] = 1;
            $info['mensaje'] = "Pregunta modificada exitosamente";
        }
        if($id_pre == 0){
            $aux = $this->con->sql("INSERT INTO preguntas (nombre, id_cat) VALUES ('".$nombre."', '".$id."')");
            $info['op'] = 1;
            $info['mensaje'] = "Pregunta creada exitosamente";
            $id_pre = $aux['insert_id'];
        }
        
        $this->con->sql("DELETE FROM preguntas_valores WHERE id_pre='".$id_pre."'");
        for($i=0; $i<$cantidad; $i++){
            
            $cant = $_POST["cant-".$i];
            $valores = $_POST["valores-".$i];
            $valores_json = json_encode(explode(",", $valores));
            if($cant > 0){
                $this->con->sql("INSERT INTO preguntas_valores (cantidad, valores, id_pre) VALUES ('".$cant."', '".$valores_json."', '".$id_pre."')");
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
