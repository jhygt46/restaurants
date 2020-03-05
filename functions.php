<?php

function esconder_index(){
    if(strpos($_SERVER["REQUEST_URI"], "index.php") !== false){
        header('HTTP/1.1 404 Not Found', true, 404);
        include('./errors/404.html');
        exit;
    }
}
function esconder($index){
    if(strpos($_SERVER["REQUEST_URI"], $index) !== false){
        header('HTTP/1.1 404 Not Found', true, 404);
        include('./errors/404.html');
        exit;
    }
}
function redireccion_ssl(){
    if((empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") && $_SERVER['HTTP_HOST'] != "localhost") {
        $location = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $location);
        exit;
    }
}
function url(){
    $url = explode("/", $_SERVER["REQUEST_URI"]);
    for($i=0; $i<count($url); $i++){
        if(($_SERVER["HTTP_HOST"] == "localhost" && $i != 1 && $url[$i] != "") || ($_SERVER["HTTP_HOST"] != "localhost" && $url[$i] != "")){
            $aux['url'][] = $url[$i];
        }
    }
    if($_SERVER["HTTP_HOST"] == "localhost"){
        $aux['dir_base'] = $_SERVER["DOCUMENT_ROOT"]."/";
        $aux['dir'] = $aux['dir_base'].$url[1]."/";
        $aux['path'] = "/".$url[1]."/";
    }else{
        $a = explode("/", $_SERVER["DOCUMENT_ROOT"]);
        array_pop($a);
        $aux['dir_base'] = implode("/", $a)."/";
        $aux['dir'] = $_SERVER["DOCUMENT_ROOT"]."/";
        $aux['path'] = "/";
    }
    return $aux;
}
function print_table($con, $table){
    $res = $con->query("SELECT * FROM ".$table);
    if($res && $res->num_rows > 0){
        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
    }else{
        echo "[]";
    }
}

class functions{
    
    public function __construct(){
        
    }
    public function error_report($url, $params, $error){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        if(!curl_errno($ch)){
            $resp = json_decode(curl_exec($ch));
            curl_close($ch);
            if($resp->{'op'} != 1){ $this->error_local_report($error); }
        }else{
            $this->error_local_report($error);
        }

    }
    public function error_local_report($error){
        $url = $this->url();
        file_put_contents($url['dir']."error.log", $error);
    }
    public function crear_directorio($dir, $nuevo, $error){

        if(!is_dir($dir)){
            if($this->writable($dir)){
                if(mkdir($dir, 0777)){

                    return true;
                }else{
                    // REPORTAR ERROR
                    return false;
                }
            }else{
                // REPORTAR ERROR
                return false;
            }
        }else{
            return true;
        }

    }
    public function writable($dir, $file = null){
        if(!is_dir($dir) || !is_writable($dir)){
            return false;
        }else{
            if($file != null){
                if(is_writable($dir.$file)){
                    return true;
                }else{
                    return false;
                }
            }
            return true;
        }
    }
    public function get_file($dir, $file, $put = null){
        if(file_exists($dir.$file)){
            return file_get_contents($dir.$file);
        }else{
            if($put != null){
                if($this->writable($dir, $file)){
                    file_put_contents($dir.$file, $put);
                    return $put;
                }else{
                    return null;
                }
            }
            die("ERROR ARCHIVO ".$file);
        }
    }
    public function url(){

        $url = explode("/", $_SERVER["REQUEST_URI"]);
        for($i=0; $i<count($url); $i++){
            if(($_SERVER["HTTP_HOST"] == "localhost" && $i != 1 && $url[$i] != "") || ($_SERVER["HTTP_HOST"] != "localhost" && $url[$i] != "")){
                $aux['url'][] = $url[$i];
            }
        }
        if($_SERVER["HTTP_HOST"] == "localhost"){
            $aux['dir_base'] = $_SERVER["DOCUMENT_ROOT"]."/";
            $aux['dir'] = $aux['dir_base'].$url[1]."/";
            $aux['path'] = "/".$url[1]."/";
            $aux['dir_var'] = "C:/AppServ/var/";
        }else{
            $a = explode("/", $_SERVER["DOCUMENT_ROOT"]);
            array_pop($a);
            $aux['dir_base'] = implode("/", $a)."/";
            $aux['dir'] = $_SERVER["DOCUMENT_ROOT"]."/";
            $aux['path'] = "/";
            $aux['dir_var'] = "/var/";
        }
        return $aux;
    }


}









?>