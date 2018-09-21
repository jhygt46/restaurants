<?php
session_start();

require_once("../../class/core_class.php");
require_once("../../idioma/es-CL.php");
$fireapp = new Core();
//$fireapp->seguridad_exit(array(48));

/* CONFIG PAGE */
$titulo = $_GET["nombre"];
$titulo_list = "Aplicaciones";
/* CONFIG PAGE */

$id_cat = 0;
$id_app = (isset($_GET["id_app"]))? $_GET["id_app"] : 0 ;
if(isset($_GET["id_cat"]) && is_numeric($_GET["id_cat"]) && $_GET["id_cat"] != 0){
    
    $id_cat = $_GET["id_cat"];
    $fireapp->set_catalogo($id_cat);
    $apps = $fireapp->get_apps_giro($id_app);
    
}



?>
<div class="title">
    <h1><?php echo $titulo; ?></h1>
    <ul class="clearfix">
        <li class="back" onclick="backurl()"></li>
    </ul>
</div>
<hr>

<?php /*
    for($i=0; $i<count($apps); $i++){
        
    $json_app = json_decode($apps[$i]['simple_txt_app']);
    $json = json_decode($apps[$i]['simple_txt']);
    
    $url = ($json->{'url'} !== null) ? $json->{'url'} : $json_app->{'url'} ;
    $titulo = ($json->{'url'} !== null) ? $json->{'titulo'} : $json_app->{'titulo'} ;
    $subtitulo = ($json->{'url'} !== null) ? $json->{'subtitulo'} : $json_app->{'subtitulo'} ;
    
?>
<div class="info" onclick="navlink('<?php echo $url."?id_gir=".$id_gir."&id_app=".$apps[$i]['id_app']; ?>')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name"><?php echo $idioma[$titulo]; ?></div>
        <div class="name2"><?php echo $idioma[$subtitulo]; ?></div>
        <div class="go_app"></div>
    </div>
</div>
<?php } */ ?>
<div class="info" onclick="navlink('pages/apps/categorias.php?id=<?php echo $id_cat; ?>')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Carta</div>
        <div class="name2">Todos los productos agrupado por categorias</div>
        <div class="go_app"></div>
    </div>
</div>
<div class="info" onclick="navlink('pages/apps/promociones.php?id=<?php echo $id_cat; ?>')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Promociones</div>
        <div class="name2">Productos Agrupados con precios baratos</div>
        <div class="go_app"></div>
    </div>
</div>
<div class="info" onclick="navlink('pages/apps/ingredientes.php?id=<?php echo $id_cat; ?>')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Ingredientes</div>
        <div class="name2">Activa los ingredientes</div>
        <div class="go_app"></div>
    </div>
</div>
<div class="info" onclick="navlink('pages/apps/preguntas.php?id=<?php echo $id_cat; ?>')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Preguntas</div>
        <div class="name2">Para categorias y Productos</div>
        <div class="go_app"></div>
    </div>
</div>