<?php

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."restaurants/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."restaurants/");
}

require_once DIR."db.php";
require_once DIR_BASE."config/config.php";
$con = new mysqli($db_host[0], $db_user[0], $db_password[0]);

//die("INSTALADO");

$tablas[0]['nombre'] = 'server';
$tablas[0]['campos'][0]['nombre'] = 'id_ser';
$tablas[0]['campos'][0]['tipo'] = 'int(4)';
$tablas[0]['campos'][0]['null'] = 0;
$tablas[0]['campos'][0]['pk'] = 1;
$tablas[0]['campos'][0]['ai'] = 1;
$tablas[0]['campos'][1]['nombre'] = 'nombre';
$tablas[0]['campos'][1]['tipo'] = 'varchar(20) COLLATE utf8_spanish2_ci';
$tablas[0]['campos'][1]['null'] = 0;
$tablas[0]['campos'][1]['values'] = ["Server 1"];
$tablas[0]['campos'][2]['nombre'] = 'ip';
$tablas[0]['campos'][2]['tipo'] = 'varchar(15) COLLATE utf8_spanish2_ci';
$tablas[0]['campos'][2]['null'] = 0;
$tablas[0]['campos'][2]['values'] = ["35.184.226.86"];
$tablas[0]['campos'][3]['nombre'] = 'code';
$tablas[0]['campos'][3]['tipo'] = 'varchar(40) COLLATE utf8_spanish2_ci';
$tablas[0]['campos'][3]['null'] = 0;
$tablas[0]['campos'][3]['values'] = ["$)7_&6p@)N>KGh[H{GttdQs'Pt$>3sYb%+/{.wM)"];

$tablas[1]['nombre'] = 'giros';
$tablas[1]['campos'][0]['nombre'] = 'id_gir';
$tablas[1]['campos'][0]['tipo'] = 'int(4)';
$tablas[1]['campos'][0]['null'] = 0;
$tablas[1]['campos'][0]['pk'] = 1;
$tablas[1]['campos'][0]['ai'] = 1;
$tablas[1]['campos'][1]['nombre'] = 'nombre';
$tablas[1]['campos'][1]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][1]['null'] = 0;
$tablas[1]['campos'][2]['nombre'] = 'telefono';
$tablas[1]['campos'][2]['tipo'] = 'varchar(14) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][2]['null'] = 0;
$tablas[1]['campos'][3]['nombre'] = 'dominio';
$tablas[1]['campos'][3]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][3]['null'] = 0;
$tablas[1]['campos'][4]['nombre'] = 'estado';
$tablas[1]['campos'][4]['tipo'] = 'varchar(255) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][4]['null'] = 0;
$tablas[1]['campos'][5]['nombre'] = 'titulo';
$tablas[1]['campos'][5]['tipo'] = 'varchar(50) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][5]['null'] = 0;
$tablas[1]['campos'][6]['nombre'] = 'code';
$tablas[1]['campos'][6]['tipo'] = 'varchar(20) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][6]['null'] = 0;
$tablas[1]['campos'][7]['nombre'] = 'catalogo';
$tablas[1]['campos'][7]['tipo'] = 'int(4)';
$tablas[1]['campos'][7]['null'] = 0;
$tablas[1]['campos'][8]['nombre'] = 'ssl';
$tablas[1]['campos'][8]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][8]['null'] = 0;
$tablas[1]['campos'][9]['nombre'] = 'solicitar_ssl';
$tablas[1]['campos'][9]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][9]['null'] = 0;
$tablas[1]['campos'][10]['nombre'] = 'dns';
$tablas[1]['campos'][10]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][10]['null'] = 0;
$tablas[1]['campos'][11]['nombre'] = 'dns_letra';
$tablas[1]['campos'][11]['tipo'] = 'varchar(1) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][11]['null'] = 0;
$tablas[1]['campos'][12]['nombre'] = 'pos';
$tablas[1]['campos'][12]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][12]['null'] = 0;
$tablas[1]['campos'][13]['nombre'] = 'inicio_html';
$tablas[1]['campos'][13]['tipo'] = 'TEXT';
$tablas[1]['campos'][13]['null'] = 0;
$tablas[1]['campos'][14]['nombre'] = 'footer_html';
$tablas[1]['campos'][14]['tipo'] = 'TEXT';
$tablas[1]['campos'][14]['null'] = 0;
$tablas[1]['campos'][15]['nombre'] = 'style_page';
$tablas[1]['campos'][15]['tipo'] = 'varchar(44) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][15]['null'] = 0;
$tablas[1]['campos'][16]['nombre'] = 'style_color';
$tablas[1]['campos'][16]['tipo'] = 'varchar(44) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][16]['null'] = 0;
$tablas[1]['campos'][17]['nombre'] = 'style_modal';
$tablas[1]['campos'][17]['tipo'] = 'varchar(44) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][17]['null'] = 0;
$tablas[1]['campos'][18]['nombre'] = 'font_family';
$tablas[1]['campos'][18]['tipo'] = 'varchar(50) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][18]['null'] = 0;
$tablas[1]['campos'][19]['nombre'] = 'font_css';
$tablas[1]['campos'][19]['tipo'] = 'varchar(50) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][19]['null'] = 0;
$tablas[1]['campos'][20]['nombre'] = 'logo';
$tablas[1]['campos'][20]['tipo'] = 'varchar(50) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][20]['null'] = 0;
$tablas[1]['campos'][21]['nombre'] = 'favicon';
$tablas[1]['campos'][21]['tipo'] = 'varchar(30) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][21]['null'] = 0;
$tablas[1]['campos'][22]['nombre'] = 'foto_retiro';
$tablas[1]['campos'][22]['tipo'] = 'varchar(50) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][22]['null'] = 0;
$tablas[1]['campos'][23]['nombre'] = 'foto_despacho';
$tablas[1]['campos'][23]['tipo'] = 'varchar(50) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][23]['null'] = 0;
$tablas[1]['campos'][24]['nombre'] = 'alto';
$tablas[1]['campos'][24]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][24]['null'] = 0;
$tablas[1]['campos'][25]['nombre'] = 'alto_pro';
$tablas[1]['campos'][25]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][25]['null'] = 0;
$tablas[1]['campos'][26]['nombre'] = 'pedido_minimo';
$tablas[1]['campos'][26]['tipo'] = 'int(4)';
$tablas[1]['campos'][26]['null'] = 0;
$tablas[1]['campos'][27]['nombre'] = 'pedido_01_titulo';
$tablas[1]['campos'][27]['tipo'] = 'varchar(60) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][27]['null'] = 0;
$tablas[1]['campos'][28]['nombre'] = 'pedido_01_subtitulo';
$tablas[1]['campos'][28]['tipo'] = 'varchar(60) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][28]['null'] = 0;
$tablas[1]['campos'][29]['nombre'] = 'pedido_02_titulo';
$tablas[1]['campos'][29]['tipo'] = 'varchar(60) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][29]['null'] = 0;
$tablas[1]['campos'][30]['nombre'] = 'pedido_02_subtitulo';
$tablas[1]['campos'][30]['tipo'] = 'varchar(60) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][30]['null'] = 0;
$tablas[1]['campos'][31]['nombre'] = 'pedido_03_titulo';
$tablas[1]['campos'][31]['tipo'] = 'varchar(60) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][31]['null'] = 0;
$tablas[1]['campos'][32]['nombre'] = 'pedido_03_subtitulo';
$tablas[1]['campos'][32]['tipo'] = 'varchar(60) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][32]['null'] = 0;
$tablas[1]['campos'][33]['nombre'] = 'pedido_04_titulo';
$tablas[1]['campos'][33]['tipo'] = 'varchar(60) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][33]['null'] = 0;
$tablas[1]['campos'][34]['nombre'] = 'pedido_04_subtitulo';
$tablas[1]['campos'][34]['tipo'] = 'varchar(60) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][34]['null'] = 0;
$tablas[1]['campos'][35]['nombre'] = 'ultima_actualizacion';
$tablas[1]['campos'][35]['tipo'] = 'datetime';
$tablas[1]['campos'][35]['null'] = 0;
$tablas[1]['campos'][36]['nombre'] = 'retiro_local';
$tablas[1]['campos'][36]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][36]['null'] = 0;
$tablas[1]['campos'][37]['nombre'] = 'despacho_domicilio';
$tablas[1]['campos'][37]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][37]['null'] = 0;
$tablas[1]['campos'][38]['nombre'] = 'lista_locales';
$tablas[1]['campos'][38]['tipo'] = 'TEXT';
$tablas[1]['campos'][38]['null'] = 0;
$tablas[1]['campos'][39]['nombre'] = 'num_ped';
$tablas[1]['campos'][39]['tipo'] = 'int(4)';
$tablas[1]['campos'][39]['null'] = 0;
$tablas[1]['campos'][40]['nombre'] = 'con_cambios';
$tablas[1]['campos'][40]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][40]['null'] = 0;
$tablas[1]['campos'][41]['nombre'] = 'pedido_wasabi';
$tablas[1]['campos'][41]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][41]['null'] = 0;
$tablas[1]['campos'][42]['nombre'] = 'pedido_gengibre';
$tablas[1]['campos'][42]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][42]['null'] = 0;
$tablas[1]['campos'][43]['nombre'] = 'pedido_palitos';
$tablas[1]['campos'][43]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][43]['null'] = 0;
$tablas[1]['campos'][44]['nombre'] = 'pedido_comentarios';
$tablas[1]['campos'][44]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][44]['null'] = 0;
$tablas[1]['campos'][45]['nombre'] = 'pedido_soya';
$tablas[1]['campos'][45]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][45]['null'] = 0;
$tablas[1]['campos'][46]['nombre'] = 'pedido_teriyaki';
$tablas[1]['campos'][46]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][46]['null'] = 0;
$tablas[1]['campos'][47]['nombre'] = 'desde';
$tablas[1]['campos'][47]['tipo'] = 'int(4)';
$tablas[1]['campos'][47]['null'] = 0;
$tablas[1]['campos'][48]['nombre'] = 'mapcode';
$tablas[1]['campos'][48]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][48]['null'] = 0;
$tablas[1]['campos'][49]['nombre'] = 'item_grafico';
$tablas[1]['campos'][49]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][49]['null'] = 0;
$tablas[1]['campos'][50]['nombre'] = 'item_pos';
$tablas[1]['campos'][50]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][50]['null'] = 0;
$tablas[1]['campos'][51]['nombre'] = 'item_cocina';
$tablas[1]['campos'][51]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][51]['null'] = 0;
$tablas[1]['campos'][52]['nombre'] = 'item_pagina';
$tablas[1]['campos'][52]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][52]['null'] = 0;
$tablas[1]['campos'][53]['nombre'] = 'fecha_creado';
$tablas[1]['campos'][53]['tipo'] = 'datetime';
$tablas[1]['campos'][53]['null'] = 0;
$tablas[1]['campos'][54]['nombre'] = 'fecha_dns';
$tablas[1]['campos'][54]['tipo'] = 'date';
$tablas[1]['campos'][54]['null'] = 0;
$tablas[1]['campos'][55]['nombre'] = 'tiempo_aviso';
$tablas[1]['campos'][55]['tipo'] = 'smallint(2)';
$tablas[1]['campos'][55]['null'] = 0;
$tablas[1]['campos'][56]['nombre'] = 'ver_inicio';
$tablas[1]['campos'][56]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][56]['null'] = 0;
$tablas[1]['campos'][57]['nombre'] = 'monto';
$tablas[1]['campos'][57]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][57]['null'] = 0;
$tablas[1]['campos'][58]['nombre'] = 'cant_pagos';
$tablas[1]['campos'][58]['tipo'] = 'int(4)';
$tablas[1]['campos'][58]['null'] = 0;
$tablas[1]['campos'][59]['nombre'] = 'id_ser';
$tablas[1]['campos'][59]['tipo'] = 'int(4)';
$tablas[1]['campos'][59]['null'] = 0;
$tablas[1]['campos'][59]['k'] = 1;
$tablas[1]['campos'][59]['kt'] = 0;
$tablas[1]['campos'][59]['kc'] = 0;
$tablas[1]['campos'][60]['nombre'] = 'eliminado';
$tablas[1]['campos'][60]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][60]['null'] = 0;


$tablas[2]['nombre'] = 'catalogo_productos';
$tablas[2]['campos'][0]['nombre'] = 'id_cat';
$tablas[2]['campos'][0]['tipo'] = 'int(4)';
$tablas[2]['campos'][0]['null'] = 0;
$tablas[2]['campos'][0]['pk'] = 1;
$tablas[2]['campos'][0]['ai'] = 1;
$tablas[2]['campos'][1]['nombre'] = 'nombre';
$tablas[2]['campos'][1]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[2]['campos'][1]['null'] = 0;
$tablas[2]['campos'][2]['nombre'] = 'fecha_creado';
$tablas[2]['campos'][2]['tipo'] = 'datetime';
$tablas[2]['campos'][2]['null'] = 0;
$tablas[2]['campos'][3]['nombre'] = 'id_gir';
$tablas[2]['campos'][3]['tipo'] = 'int(4)';
$tablas[2]['campos'][3]['null'] = 0;
$tablas[2]['campos'][3]['k'] = 1;
$tablas[2]['campos'][3]['kt'] = 1;
$tablas[2]['campos'][3]['kc'] = 0;
$tablas[2]['campos'][4]['nombre'] = 'eliminado';
$tablas[2]['campos'][4]['tipo'] = 'tinyint(1)';
$tablas[2]['campos'][4]['null'] = 0;

$tablas[3]['nombre'] = 'categorias';
$tablas[3]['campos'][0]['nombre'] = 'id_cae';
$tablas[3]['campos'][0]['tipo'] = 'int(4)';
$tablas[3]['campos'][0]['null'] = 0;
$tablas[3]['campos'][0]['pk'] = 1;
$tablas[3]['campos'][0]['ai'] = 1;
$tablas[3]['campos'][1]['nombre'] = 'parent_id';
$tablas[3]['campos'][1]['tipo'] = 'int(4)';
$tablas[3]['campos'][1]['null'] = 0;
$tablas[3]['campos'][2]['nombre'] = 'nombre';
$tablas[3]['campos'][2]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[3]['campos'][2]['null'] = 0;
$tablas[3]['campos'][3]['nombre'] = 'descripcion';
$tablas[3]['campos'][3]['tipo'] = 'varchar(255) COLLATE utf8_spanish2_ci';
$tablas[3]['campos'][3]['null'] = 0;
$tablas[3]['campos'][4]['nombre'] = 'descripcion_sub';
$tablas[3]['campos'][4]['tipo'] = 'varchar(255) COLLATE utf8_spanish2_ci';
$tablas[3]['campos'][4]['null'] = 0;
$tablas[3]['campos'][5]['nombre'] = 'precio';
$tablas[3]['campos'][5]['tipo'] = 'int(4)';
$tablas[3]['campos'][5]['null'] = 0;
$tablas[3]['campos'][6]['nombre'] = 'tipo';
$tablas[3]['campos'][6]['tipo'] = 'tinyint(1)';
$tablas[3]['campos'][6]['null'] = 0;
$tablas[3]['campos'][7]['nombre'] = 'ocultar';
$tablas[3]['campos'][7]['tipo'] = 'tinyint(1)';
$tablas[3]['campos'][7]['null'] = 0;
$tablas[3]['campos'][8]['nombre'] = 'mostrar_prods';
$tablas[3]['campos'][8]['tipo'] = 'tinyint(1)';
$tablas[3]['campos'][8]['null'] = 0;
$tablas[3]['campos'][9]['nombre'] = 'detalle_prods';
$tablas[3]['campos'][9]['tipo'] = 'tinyint(1)';
$tablas[3]['campos'][9]['null'] = 0;
$tablas[3]['campos'][10]['nombre'] = 'image';
$tablas[3]['campos'][10]['tipo'] = 'varchar(40) COLLATE utf8_spanish2_ci';
$tablas[3]['campos'][10]['null'] = 0;
$tablas[3]['campos'][11]['nombre'] = 'degradado';
$tablas[3]['campos'][11]['tipo'] = 'smallint(2)';
$tablas[3]['campos'][11]['null'] = 0;
$tablas[3]['campos'][12]['nombre'] = 'orders';
$tablas[3]['campos'][12]['tipo'] = 'int(4)';
$tablas[3]['campos'][12]['null'] = 0;
$tablas[3]['campos'][13]['nombre'] = 'id_cat';
$tablas[3]['campos'][13]['tipo'] = 'int(4)';
$tablas[3]['campos'][13]['null'] = 0;
$tablas[3]['campos'][13]['k'] = 1;
$tablas[3]['campos'][13]['kt'] = 2;
$tablas[3]['campos'][13]['kc'] = 0;
$tablas[3]['campos'][14]['nombre'] = 'id_gir';
$tablas[3]['campos'][14]['tipo'] = 'int(4)';
$tablas[3]['campos'][14]['null'] = 0;
$tablas[3]['campos'][14]['k'] = 1;
$tablas[3]['campos'][14]['kt'] = 1;
$tablas[3]['campos'][14]['kc'] = 0;
$tablas[3]['campos'][15]['nombre'] = 'eliminado';
$tablas[3]['campos'][15]['tipo'] = 'tinyint(1)';
$tablas[3]['campos'][15]['null'] = 0;

$tablas[4]['nombre'] = 'productos';
$tablas[4]['campos'][0]['nombre'] = 'id_pro';
$tablas[4]['campos'][0]['tipo'] = 'int(4)';
$tablas[4]['campos'][0]['null'] = 0;
$tablas[4]['campos'][0]['pk'] = 1;
$tablas[4]['campos'][0]['ai'] = 1;
$tablas[4]['campos'][1]['nombre'] = 'numero';
$tablas[4]['campos'][1]['tipo'] = 'smallint(2)';
$tablas[4]['campos'][1]['null'] = 0;
$tablas[4]['campos'][2]['nombre'] = 'nombre';
$tablas[4]['campos'][2]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[4]['campos'][2]['null'] = 0;
$tablas[4]['campos'][3]['nombre'] = 'nombre_carro';
$tablas[4]['campos'][3]['tipo'] = 'varchar(255) COLLATE utf8_spanish2_ci';
$tablas[4]['campos'][3]['null'] = 0;
$tablas[4]['campos'][4]['nombre'] = 'descripcion';
$tablas[4]['campos'][4]['tipo'] = 'varchar(255) COLLATE utf8_spanish2_ci';
$tablas[4]['campos'][4]['null'] = 0;
$tablas[4]['campos'][5]['nombre'] = 'image';
$tablas[4]['campos'][5]['tipo'] = 'varchar(40) COLLATE utf8_spanish2_ci';
$tablas[4]['campos'][5]['null'] = 0;
$tablas[4]['campos'][6]['nombre'] = 'fecha_creado';
$tablas[4]['campos'][6]['tipo'] = 'datetime';
$tablas[4]['campos'][6]['null'] = 0;
$tablas[4]['campos'][7]['nombre'] = 'id_gir';
$tablas[4]['campos'][7]['tipo'] = 'int(4)';
$tablas[4]['campos'][7]['null'] = 0;
$tablas[4]['campos'][7]['k'] = 1;
$tablas[4]['campos'][7]['kt'] = 1;
$tablas[4]['campos'][7]['kc'] = 0;
$tablas[4]['campos'][8]['nombre'] = 'eliminado';
$tablas[4]['campos'][8]['tipo'] = 'tinyint(1)';
$tablas[4]['campos'][8]['null'] = 0;
$tablas[4]['campos'][9]['nombre'] = 'disponible';
$tablas[4]['campos'][9]['tipo'] = 'tinyint(1)';
$tablas[4]['campos'][9]['null'] = 0;
$tablas[4]['campos'][10]['nombre'] = 'tipo';
$tablas[4]['campos'][10]['tipo'] = 'tinyint(1)';
$tablas[4]['campos'][10]['null'] = 0;

$tablas[5]['nombre'] = 'cat_pros';
$tablas[5]['campos'][0]['nombre'] = 'id_cae';
$tablas[5]['campos'][0]['tipo'] = 'int(4)';
$tablas[5]['campos'][0]['null'] = 0;
$tablas[5]['campos'][0]['pk'] = 1;
$tablas[5]['campos'][0]['k'] = 1;
$tablas[5]['campos'][0]['kt'] = 3;
$tablas[5]['campos'][0]['kc'] = 0;
$tablas[5]['campos'][1]['nombre'] = 'id_pro';
$tablas[5]['campos'][1]['tipo'] = 'int(4)';
$tablas[5]['campos'][1]['null'] = 0;
$tablas[5]['campos'][1]['pk'] = 1;
$tablas[5]['campos'][1]['k'] = 1;
$tablas[5]['campos'][1]['kt'] = 4;
$tablas[5]['campos'][1]['kc'] = 0;
$tablas[5]['campos'][2]['nombre'] = 'orders';
$tablas[5]['campos'][2]['tipo'] = 'int(4)';
$tablas[5]['campos'][2]['null'] = 0;

$tablas[6]['nombre'] = 'css';
$tablas[6]['campos'][0]['nombre'] = 'id_css';
$tablas[6]['campos'][0]['tipo'] = 'int(4)';
$tablas[6]['campos'][0]['null'] = 0;
$tablas[6]['campos'][0]['pk'] = 1;
$tablas[6]['campos'][0]['ai'] = 1;
$tablas[6]['campos'][1]['nombre'] = 'nombre';
$tablas[6]['campos'][1]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[6]['campos'][1]['null'] = 0;
$tablas[6]['campos'][2]['nombre'] = 'tipo';
$tablas[6]['campos'][2]['tipo'] = 'tinyint(1)';
$tablas[6]['campos'][2]['null'] = 0;
$tablas[6]['campos'][3]['nombre'] = 'id_gir';
$tablas[6]['campos'][3]['tipo'] = 'int(4)';
$tablas[6]['campos'][3]['null'] = 0;
$tablas[6]['campos'][4]['nombre'] = 'eliminado';
$tablas[6]['campos'][4]['tipo'] = 'tinyint(1)';
$tablas[6]['campos'][4]['null'] = 0;

$tablas[7]['nombre'] = 'fw_usuarios';
$tablas[7]['campos'][0]['nombre'] = 'id_user';
$tablas[7]['campos'][0]['tipo'] = 'int(4)';
$tablas[7]['campos'][0]['null'] = 0;
$tablas[7]['campos'][0]['pk'] = 1;
$tablas[7]['campos'][0]['ai'] = 1;
$tablas[7]['campos'][1]['nombre'] = 'nombre';
$tablas[7]['campos'][1]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[7]['campos'][1]['null'] = 0;
$tablas[7]['campos'][1]['values'] = ["Diego Gomez"];
$tablas[7]['campos'][2]['nombre'] = 'correo';
$tablas[7]['campos'][2]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[7]['campos'][2]['null'] = 0;
$tablas[7]['campos'][2]['values'] = ["misitiodelivery@gmail.com"];
$tablas[7]['campos'][3]['nombre'] = 'pass';
$tablas[7]['campos'][3]['tipo'] = 'varchar(32) COLLATE utf8_spanish2_ci';
$tablas[7]['campos'][3]['null'] = 0;
$tablas[7]['campos'][3]['values'] = ["ef3901f2629f57c096651c2f5697f01b"];
$tablas[7]['campos'][4]['nombre'] = 'mailcode';
$tablas[7]['campos'][4]['tipo'] = 'varchar(32) COLLATE utf8_spanish2_ci';
$tablas[7]['campos'][4]['null'] = 0;
$tablas[7]['campos'][4]['values'] = [""];
$tablas[7]['campos'][5]['nombre'] = 'cookie_code';
$tablas[7]['campos'][5]['tipo'] = 'varchar(60) COLLATE utf8_spanish2_ci';
$tablas[7]['campos'][5]['null'] = 0;
$tablas[7]['campos'][5]['values'] = [""];
$tablas[7]['campos'][6]['nombre'] = 'fecha_creado';
$tablas[7]['campos'][6]['tipo'] = 'datetime';
$tablas[7]['campos'][6]['null'] = 0;
$tablas[7]['campos'][6]['values'] = ["2018-08-07 00:00:00"];
$tablas[7]['campos'][7]['nombre'] = 'admin';
$tablas[7]['campos'][7]['tipo'] = 'tinyint(1)';
$tablas[7]['campos'][7]['null'] = 0;
$tablas[7]['campos'][7]['values'] = ["1"];
$tablas[7]['campos'][8]['nombre'] = 'tipo';
$tablas[7]['campos'][8]['tipo'] = 'tinyint(1)';
$tablas[7]['campos'][8]['null'] = 0;
$tablas[7]['campos'][8]['values'] = ["1"];
$tablas[7]['campos'][9]['nombre'] = 're_venta';
$tablas[7]['campos'][9]['tipo'] = 'tinyint(1)';
$tablas[7]['campos'][9]['null'] = 0;
$tablas[7]['campos'][9]['values'] = ["0"];
$tablas[7]['campos'][10]['nombre'] = 'id_aux_user';
$tablas[7]['campos'][10]['tipo'] = 'int(4)';
$tablas[7]['campos'][10]['null'] = 0;
$tablas[7]['campos'][10]['values'] = ["0"];
$tablas[7]['campos'][11]['nombre'] = 'save_web';
$tablas[7]['campos'][11]['tipo'] = 'tinyint(1)';
$tablas[7]['campos'][11]['null'] = 0;
$tablas[7]['campos'][11]['values'] = ["0"];
$tablas[7]['campos'][12]['nombre'] = 'web_min';
$tablas[7]['campos'][12]['tipo'] = 'smallint(2)';
$tablas[7]['campos'][12]['null'] = 0;
$tablas[7]['campos'][12]['values'] = ["0"];
$tablas[7]['campos'][13]['nombre'] = 'save_pos';
$tablas[7]['campos'][13]['tipo'] = 'tinyint(1)';
$tablas[7]['campos'][13]['null'] = 0;
$tablas[7]['campos'][13]['values'] = ["0"];
$tablas[7]['campos'][14]['nombre'] = 'pos_min';
$tablas[7]['campos'][14]['tipo'] = 'smallint(2)';
$tablas[7]['campos'][14]['null'] = 0;
$tablas[7]['campos'][14]['values'] = ["0"];
$tablas[7]['campos'][15]['nombre'] = 'del_pdir';
$tablas[7]['campos'][15]['tipo'] = 'tinyint(1)';
$tablas[7]['campos'][15]['null'] = 0;
$tablas[7]['campos'][15]['values'] = ["0"];
$tablas[7]['campos'][16]['nombre'] = 'id_loc';
$tablas[7]['campos'][16]['tipo'] = 'int(4)';
$tablas[7]['campos'][16]['null'] = 0;
$tablas[7]['campos'][16]['values'] = ["0"];
$tablas[7]['campos'][17]['nombre'] = 'id_gir';
$tablas[7]['campos'][17]['tipo'] = 'int(4)';
$tablas[7]['campos'][17]['null'] = 0;
$tablas[7]['campos'][17]['values'] = ["1"];
$tablas[7]['campos'][18]['nombre'] = 'eliminado';
$tablas[7]['campos'][18]['tipo'] = 'tinyint(1)';
$tablas[7]['campos'][18]['null'] = 0;
$tablas[7]['campos'][18]['values'] = ["0"];

$tablas[8]['nombre'] = 'fw_acciones';
$tablas[8]['campos'][0]['nombre'] = 'id_acc';
$tablas[8]['campos'][0]['tipo'] = 'int(4)';
$tablas[8]['campos'][0]['null'] = 0;
$tablas[8]['campos'][0]['pk'] = 1;
$tablas[8]['campos'][0]['ai'] = 1;
$tablas[8]['campos'][1]['nombre'] = 'tipo';
$tablas[8]['campos'][1]['tipo'] = 'tinyint(1)';
$tablas[8]['campos'][1]['null'] = 0;
$tablas[8]['campos'][2]['nombre'] = 'fecha';
$tablas[8]['campos'][2]['tipo'] = 'datetime';
$tablas[8]['campos'][2]['null'] = 0;
$tablas[8]['campos'][3]['nombre'] = 'id_user';
$tablas[8]['campos'][3]['tipo'] = 'int(4)';
$tablas[8]['campos'][3]['null'] = 0;
$tablas[8]['campos'][3]['k'] = 1;
$tablas[8]['campos'][3]['kt'] = 7;
$tablas[8]['campos'][3]['kc'] = 0;

$tablas[9]['nombre'] = 'fw_usuarios_giros';
$tablas[9]['campos'][0]['nombre'] = 'id_user';
$tablas[9]['campos'][0]['tipo'] = 'int(4)';
$tablas[9]['campos'][0]['null'] = 0;
$tablas[9]['campos'][0]['pk'] = 1;
$tablas[9]['campos'][0]['k'] = 1;
$tablas[9]['campos'][0]['kt'] = 7;
$tablas[9]['campos'][0]['kc'] = 0;
$tablas[9]['campos'][1]['nombre'] = 'id_gir';
$tablas[9]['campos'][1]['tipo'] = 'int(4)';
$tablas[9]['campos'][1]['null'] = 0;
$tablas[9]['campos'][1]['pk'] = 1;
$tablas[9]['campos'][1]['k'] = 1;
$tablas[9]['campos'][1]['kt'] = 1;
$tablas[9]['campos'][1]['kc'] = 0;

$tablas[10]['nombre'] = 'fw_usuarios_giros_clientes';
$tablas[10]['campos'][0]['nombre'] = 'id_user';
$tablas[10]['campos'][0]['tipo'] = 'int(4)';
$tablas[10]['campos'][0]['null'] = 0;
$tablas[10]['campos'][0]['pk'] = 1;
$tablas[10]['campos'][0]['k'] = 1;
$tablas[10]['campos'][0]['kt'] = 7;
$tablas[10]['campos'][0]['kc'] = 0;
$tablas[10]['campos'][1]['nombre'] = 'id_gir';
$tablas[10]['campos'][1]['tipo'] = 'int(4)';
$tablas[10]['campos'][1]['null'] = 0;
$tablas[10]['campos'][1]['pk'] = 1;
$tablas[10]['campos'][1]['k'] = 1;
$tablas[10]['campos'][1]['kt'] = 1;
$tablas[10]['campos'][1]['kc'] = 0;

$tablas[11]['nombre'] = 'locales';
$tablas[11]['campos'][0]['nombre'] = 'id_loc';
$tablas[11]['campos'][0]['tipo'] = 'int(4)';
$tablas[11]['campos'][0]['null'] = 0;
$tablas[11]['campos'][0]['pk'] = 1;
$tablas[11]['campos'][0]['ai'] = 1;
$tablas[11]['campos'][1]['nombre'] = 'nombre';
$tablas[11]['campos'][1]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[11]['campos'][1]['null'] = 0;
$tablas[11]['campos'][2]['nombre'] = 'direccion';
$tablas[11]['campos'][2]['tipo'] = 'varchar(255) COLLATE utf8_spanish2_ci';
$tablas[11]['campos'][2]['null'] = 0;
$tablas[11]['campos'][3]['nombre'] = 'telefono';
$tablas[11]['campos'][3]['tipo'] = 'varchar(15) COLLATE utf8_spanish2_ci';
$tablas[11]['campos'][3]['null'] = 0;
$tablas[11]['campos'][4]['nombre'] = 'whatsapp';
$tablas[11]['campos'][4]['tipo'] = 'varchar(15) COLLATE utf8_spanish2_ci';
$tablas[11]['campos'][4]['null'] = 0;
$tablas[11]['campos'][5]['nombre'] = 'lat';
$tablas[11]['campos'][5]['tipo'] = 'double';
$tablas[11]['campos'][5]['null'] = 0;
$tablas[11]['campos'][6]['nombre'] = 'lng';
$tablas[11]['campos'][6]['tipo'] = 'double';
$tablas[11]['campos'][6]['null'] = 0;
$tablas[11]['campos'][7]['nombre'] = 'image';
$tablas[11]['campos'][7]['tipo'] = 'varchar(50) COLLATE utf8_spanish2_ci';
$tablas[11]['campos'][7]['null'] = 0;
$tablas[11]['campos'][8]['nombre'] = 'code';
$tablas[11]['campos'][8]['tipo'] = 'varchar(20) COLLATE utf8_spanish2_ci';
$tablas[11]['campos'][8]['null'] = 0;
$tablas[11]['campos'][9]['nombre'] = 'cookie_code';
$tablas[11]['campos'][9]['tipo'] = 'varchar(60) COLLATE utf8_spanish2_ci';
$tablas[11]['campos'][9]['null'] = 0;
$tablas[11]['campos'][10]['nombre'] = 'cookie_ip';
$tablas[11]['campos'][10]['tipo'] = 'varchar(15) COLLATE utf8_spanish2_ci';
$tablas[11]['campos'][10]['null'] = 0;
$tablas[11]['campos'][11]['nombre'] = 'correo';
$tablas[11]['campos'][11]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[11]['campos'][11]['null'] = 0;
$tablas[11]['campos'][12]['nombre'] = 'correo_ses';
$tablas[11]['campos'][12]['tipo'] = 'tinyint(1)';
$tablas[11]['campos'][12]['null'] = 0;
$tablas[11]['campos'][13]['nombre'] = 'tipo_comanda';
$tablas[11]['campos'][13]['tipo'] = 'tinyint(1)';
$tablas[11]['campos'][13]['null'] = 0;
$tablas[11]['campos'][14]['nombre'] = 'enviar_cocina';
$tablas[11]['campos'][14]['tipo'] = 'tinyint(1)';
$tablas[11]['campos'][14]['null'] = 0;
$tablas[11]['campos'][15]['nombre'] = 't_retiro';
$tablas[11]['campos'][15]['tipo'] = 'smallint(2)';
$tablas[11]['campos'][15]['null'] = 0;
$tablas[11]['campos'][16]['nombre'] = 't_despacho';
$tablas[11]['campos'][16]['tipo'] = 'smallint(2)';
$tablas[11]['campos'][16]['null'] = 0;
$tablas[11]['campos'][17]['nombre'] = 'sonido';
$tablas[11]['campos'][17]['tipo'] = 'varchar(20) COLLATE utf8_spanish2_ci';
$tablas[11]['campos'][17]['null'] = 0;
$tablas[11]['campos'][18]['nombre'] = 'pos';
$tablas[11]['campos'][18]['tipo'] = 'tinyint(1)';
$tablas[11]['campos'][18]['null'] = 0;
$tablas[11]['campos'][19]['nombre'] = 'activar_envio';
$tablas[11]['campos'][19]['tipo'] = 'tinyint(1)';
$tablas[11]['campos'][19]['null'] = 0;
$tablas[11]['campos'][20]['nombre'] = 'fecha_creado';
$tablas[11]['campos'][20]['tipo'] = 'datetime';
$tablas[11]['campos'][20]['null'] = 0;
$tablas[11]['campos'][21]['nombre'] = 'fecha_cocina';
$tablas[11]['campos'][21]['tipo'] = 'datetime';
$tablas[11]['campos'][21]['null'] = 0;
$tablas[11]['campos'][22]['nombre'] = 'fecha_pos';
$tablas[11]['campos'][22]['tipo'] = 'datetime';
$tablas[11]['campos'][22]['null'] = 0;
$tablas[11]['campos'][23]['nombre'] = 'id_cat';
$tablas[11]['campos'][23]['tipo'] = 'int(4)';
$tablas[11]['campos'][23]['null'] = 0;
$tablas[11]['campos'][23]['k'] = 1;
$tablas[11]['campos'][23]['kt'] = 2;
$tablas[11]['campos'][23]['kc'] = 0;
$tablas[11]['campos'][24]['nombre'] = 'id_gir';
$tablas[11]['campos'][24]['tipo'] = 'int(4)';
$tablas[11]['campos'][24]['null'] = 0;
$tablas[11]['campos'][24]['k'] = 1;
$tablas[11]['campos'][24]['kt'] = 1;
$tablas[11]['campos'][24]['kc'] = 0;
$tablas[11]['campos'][25]['nombre'] = 'eliminado';
$tablas[11]['campos'][25]['tipo'] = 'tinyint(1)';
$tablas[11]['campos'][25]['null'] = 0;

$tablas[12]['nombre'] = 'locales_tramos';
$tablas[12]['campos'][0]['nombre'] = 'id_lot';
$tablas[12]['campos'][0]['tipo'] = 'int(4)';
$tablas[12]['campos'][0]['null'] = 0;
$tablas[12]['campos'][0]['pk'] = 1;
$tablas[12]['campos'][0]['ai'] = 1;
$tablas[12]['campos'][1]['nombre'] = 'nombre';
$tablas[12]['campos'][1]['tipo'] = 'varchar(60) COLLATE utf8_spanish2_ci';
$tablas[12]['campos'][1]['null'] = 0;
$tablas[12]['campos'][2]['nombre'] = 'precio';
$tablas[12]['campos'][2]['tipo'] = 'int(4)';
$tablas[12]['campos'][2]['null'] = 0;
$tablas[12]['campos'][3]['nombre'] = 'poligono';
$tablas[12]['campos'][3]['tipo'] = 'TEXT';
$tablas[12]['campos'][3]['null'] = 0;
$tablas[12]['campos'][4]['nombre'] = 'id_loc';
$tablas[12]['campos'][4]['tipo'] = 'int(4)';
$tablas[12]['campos'][4]['null'] = 0;
$tablas[12]['campos'][4]['k'] = 1;
$tablas[12]['campos'][4]['kt'] = 11;
$tablas[12]['campos'][4]['kc'] = 0;
$tablas[12]['campos'][5]['nombre'] = 'eliminado';
$tablas[12]['campos'][5]['tipo'] = 'tinyint(1)';
$tablas[12]['campos'][5]['null'] = 0;

$tablas[13]['nombre'] = 'horarios';
$tablas[13]['campos'][0]['nombre'] = 'id_hor';
$tablas[13]['campos'][0]['tipo'] = 'int(4)';
$tablas[13]['campos'][0]['null'] = 0;
$tablas[13]['campos'][0]['pk'] = 1;
$tablas[13]['campos'][0]['ai'] = 1;
$tablas[13]['campos'][1]['nombre'] = 'dia_ini';
$tablas[13]['campos'][1]['tipo'] = 'tinyint(1)';
$tablas[13]['campos'][1]['null'] = 0;
$tablas[13]['campos'][2]['nombre'] = 'dia_fin';
$tablas[13]['campos'][2]['tipo'] = 'tinyint(1)';
$tablas[13]['campos'][2]['null'] = 0;
$tablas[13]['campos'][3]['nombre'] = 'hora_ini';
$tablas[13]['campos'][3]['tipo'] = 'tinyint(1)';
$tablas[13]['campos'][3]['null'] = 0;
$tablas[13]['campos'][4]['nombre'] = 'hora_fin';
$tablas[13]['campos'][4]['tipo'] = 'tinyint(1)';
$tablas[13]['campos'][4]['null'] = 0;
$tablas[13]['campos'][5]['nombre'] = 'min_ini';
$tablas[13]['campos'][5]['tipo'] = 'tinyint(1)';
$tablas[13]['campos'][5]['null'] = 0;
$tablas[13]['campos'][6]['nombre'] = 'min_fin';
$tablas[13]['campos'][6]['tipo'] = 'tinyint(1)';
$tablas[13]['campos'][6]['null'] = 0;
$tablas[13]['campos'][7]['nombre'] = 'tipo';
$tablas[13]['campos'][7]['tipo'] = 'tinyint(1)';
$tablas[13]['campos'][7]['null'] = 0;
$tablas[13]['campos'][8]['nombre'] = 'id_loc';
$tablas[13]['campos'][8]['tipo'] = 'int(4)';
$tablas[13]['campos'][8]['null'] = 0;
$tablas[13]['campos'][8]['k'] = 1;
$tablas[13]['campos'][8]['kt'] = 11;
$tablas[13]['campos'][8]['kc'] = 0;
$tablas[13]['campos'][9]['nombre'] = 'id_gir';
$tablas[13]['campos'][9]['tipo'] = 'int(4)';
$tablas[13]['campos'][9]['null'] = 0;
$tablas[13]['campos'][9]['k'] = 1;
$tablas[13]['campos'][9]['kt'] = 1;
$tablas[13]['campos'][9]['kc'] = 0;
$tablas[13]['campos'][10]['nombre'] = 'eliminado';
$tablas[13]['campos'][10]['tipo'] = 'tinyint(1)';
$tablas[13]['campos'][10]['null'] = 0;

$tablas[14]['nombre'] = 'motos';
$tablas[14]['campos'][0]['nombre'] = 'id_mot';
$tablas[14]['campos'][0]['tipo'] = 'int(4)';
$tablas[14]['campos'][0]['null'] = 0;
$tablas[14]['campos'][0]['pk'] = 1;
$tablas[14]['campos'][0]['ai'] = 1;
$tablas[14]['campos'][1]['nombre'] = 'nombre';
$tablas[14]['campos'][1]['tipo'] = 'varchar(40) COLLATE utf8_spanish2_ci';
$tablas[14]['campos'][1]['null'] = 0;
$tablas[14]['campos'][2]['nombre'] = 'correo';
$tablas[14]['campos'][2]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[14]['campos'][2]['null'] = 0;
$tablas[14]['campos'][3]['nombre'] = 'uid';
$tablas[14]['campos'][3]['tipo'] = 'varchar(20) COLLATE utf8_spanish2_ci';
$tablas[14]['campos'][3]['null'] = 0;
$tablas[14]['campos'][4]['nombre'] = 'id_gir';
$tablas[14]['campos'][4]['tipo'] = 'int(4)';
$tablas[14]['campos'][4]['null'] = 0;
$tablas[14]['campos'][4]['k'] = 1;
$tablas[14]['campos'][4]['kt'] = 1;
$tablas[14]['campos'][4]['kc'] = 0;
$tablas[14]['campos'][5]['nombre'] = 'eliminado';
$tablas[14]['campos'][5]['tipo'] = 'tinyint(1)';
$tablas[14]['campos'][5]['null'] = 0;

$tablas[15]['nombre'] = 'motos_locales';
$tablas[15]['campos'][0]['nombre'] = 'id_mot';
$tablas[15]['campos'][0]['tipo'] = 'int(4)';
$tablas[15]['campos'][0]['null'] = 0;
$tablas[15]['campos'][0]['pk'] = 1;
$tablas[15]['campos'][0]['k'] = 1;
$tablas[15]['campos'][0]['kt'] = 14;
$tablas[15]['campos'][0]['kc'] = 0;
$tablas[15]['campos'][1]['nombre'] = 'id_loc';
$tablas[15]['campos'][1]['tipo'] = 'int(4)';
$tablas[15]['campos'][1]['null'] = 0;
$tablas[15]['campos'][1]['pk'] = 1;
$tablas[15]['campos'][1]['k'] = 1;
$tablas[15]['campos'][1]['kt'] = 11;
$tablas[15]['campos'][1]['kc'] = 0;

$tablas[16]['nombre'] = 'paginas';
$tablas[16]['campos'][0]['nombre'] = 'id_pag';
$tablas[16]['campos'][0]['tipo'] = 'int(4)';
$tablas[16]['campos'][0]['null'] = 0;
$tablas[16]['campos'][0]['pk'] = 1;
$tablas[16]['campos'][0]['ai'] = 1;
$tablas[16]['campos'][1]['nombre'] = 'nombre';
$tablas[16]['campos'][1]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[16]['campos'][1]['null'] = 0;
$tablas[16]['campos'][2]['nombre'] = 'html';
$tablas[16]['campos'][2]['tipo'] = 'TEXT';
$tablas[16]['campos'][2]['null'] = 0;
$tablas[16]['campos'][3]['nombre'] = 'imagen';
$tablas[16]['campos'][3]['tipo'] = 'varchar(40) COLLATE utf8_spanish2_ci';
$tablas[16]['campos'][3]['null'] = 0;
$tablas[16]['campos'][4]['nombre'] = 'tipo';
$tablas[16]['campos'][4]['tipo'] = 'smallint(2)';
$tablas[16]['campos'][4]['null'] = 0;
$tablas[16]['campos'][5]['nombre'] = 'orders';
$tablas[16]['campos'][5]['tipo'] = 'smallint(2)';
$tablas[16]['campos'][5]['null'] = 0;
$tablas[16]['campos'][6]['nombre'] = 'id_gir';
$tablas[16]['campos'][6]['tipo'] = 'int(4)';
$tablas[16]['campos'][6]['null'] = 0;
$tablas[16]['campos'][6]['k'] = 1;
$tablas[16]['campos'][6]['kt'] = 1;
$tablas[16]['campos'][6]['kc'] = 0;
$tablas[16]['campos'][7]['nombre'] = 'eliminado';
$tablas[16]['campos'][7]['tipo'] = 'tinyint(1)';
$tablas[16]['campos'][7]['null'] = 0;
$tablas[16]['campos'][8]['nombre'] = 'visible';
$tablas[16]['campos'][8]['tipo'] = 'tinyint(1)';
$tablas[16]['campos'][8]['null'] = 0;

$tablas[17]['nombre'] = 'pedidos_usuarios';
$tablas[17]['campos'][0]['nombre'] = 'id_puser';
$tablas[17]['campos'][0]['tipo'] = 'int(4)';
$tablas[17]['campos'][0]['null'] = 0;
$tablas[17]['campos'][0]['pk'] = 1;
$tablas[17]['campos'][0]['ai'] = 1;
$tablas[17]['campos'][1]['nombre'] = 'codigo';
$tablas[17]['campos'][1]['tipo'] = 'varchar(20) COLLATE utf8_spanish2_ci';
$tablas[17]['campos'][1]['null'] = 0;
$tablas[17]['campos'][2]['nombre'] = 'nombre';
$tablas[17]['campos'][2]['tipo'] = 'varchar(30) COLLATE utf8_spanish2_ci';
$tablas[17]['campos'][2]['null'] = 0;
$tablas[17]['campos'][3]['nombre'] = 'telefono';
$tablas[17]['campos'][3]['tipo'] = 'varchar(15) COLLATE utf8_spanish2_ci';
$tablas[17]['campos'][3]['null'] = 0;
$tablas[17]['campos'][4]['nombre'] = 'cont';
$tablas[17]['campos'][4]['tipo'] = 'smallint(2)';
$tablas[17]['campos'][4]['null'] = 0;
$tablas[17]['campos'][5]['nombre'] = 'fecha_ultimo';
$tablas[17]['campos'][5]['tipo'] = 'datetime';
$tablas[17]['campos'][5]['null'] = 0;
$tablas[17]['campos'][6]['nombre'] = 'pedido_falso';
$tablas[17]['campos'][6]['tipo'] = 'tinyint(1)';
$tablas[17]['campos'][6]['null'] = 0;
$tablas[17]['campos'][7]['nombre'] = 'tipo';
$tablas[17]['campos'][7]['tipo'] = 'tinyint(1)';
$tablas[17]['campos'][7]['null'] = 0;
$tablas[17]['campos'][8]['nombre'] = 'id_gir';
$tablas[17]['campos'][8]['tipo'] = 'int(4)';
$tablas[17]['campos'][8]['null'] = 0;
$tablas[17]['campos'][8]['k'] = 1;
$tablas[17]['campos'][8]['kt'] = 1;
$tablas[17]['campos'][8]['kc'] = 0;
$tablas[17]['campos'][9]['nombre'] = 'eliminado';
$tablas[17]['campos'][9]['tipo'] = 'tinyint(1)';
$tablas[17]['campos'][9]['null'] = 0;

$tablas[18]['nombre'] = 'pedidos_direccion';
$tablas[18]['campos'][0]['nombre'] = 'id_pdir';
$tablas[18]['campos'][0]['tipo'] = 'int(4)';
$tablas[18]['campos'][0]['null'] = 0;
$tablas[18]['campos'][0]['pk'] = 1;
$tablas[18]['campos'][0]['ai'] = 1;
$tablas[18]['campos'][1]['nombre'] = 'direccion';
$tablas[18]['campos'][1]['tipo'] = 'varchar(230) COLLATE utf8_spanish2_ci';
$tablas[18]['campos'][1]['null'] = 0;
$tablas[18]['campos'][2]['nombre'] = 'calle';
$tablas[18]['campos'][2]['tipo'] = 'varchar(150) COLLATE utf8_spanish2_ci';
$tablas[18]['campos'][2]['null'] = 0;
$tablas[18]['campos'][3]['nombre'] = 'num';
$tablas[18]['campos'][3]['tipo'] = 'varchar(20) COLLATE utf8_spanish2_ci';
$tablas[18]['campos'][3]['null'] = 0;
$tablas[18]['campos'][4]['nombre'] = 'depto';
$tablas[18]['campos'][4]['tipo'] = 'varchar(20) COLLATE utf8_spanish2_ci';
$tablas[18]['campos'][4]['null'] = 0;
$tablas[18]['campos'][5]['nombre'] = 'comuna';
$tablas[18]['campos'][5]['tipo'] = 'varchar(40) COLLATE utf8_spanish2_ci';
$tablas[18]['campos'][5]['null'] = 0;
$tablas[18]['campos'][6]['nombre'] = 'lat';
$tablas[18]['campos'][6]['tipo'] = 'double';
$tablas[18]['campos'][6]['null'] = 0;
$tablas[18]['campos'][7]['nombre'] = 'lng';
$tablas[18]['campos'][7]['tipo'] = 'double';
$tablas[18]['campos'][7]['null'] = 0;
$tablas[18]['campos'][8]['nombre'] = 'id_puser';
$tablas[18]['campos'][8]['tipo'] = 'int(4)';
$tablas[18]['campos'][8]['null'] = 0;
$tablas[18]['campos'][8]['k'] = 1;
$tablas[18]['campos'][8]['kt'] = 17;
$tablas[18]['campos'][8]['kc'] = 0;

$tablas[19]['nombre'] = 'pedidos_aux';
$tablas[19]['campos'][0]['nombre'] = 'id_ped';
$tablas[19]['campos'][0]['tipo'] = 'int(4)';
$tablas[19]['campos'][0]['null'] = 0;
$tablas[19]['campos'][0]['pk'] = 1;
$tablas[19]['campos'][0]['ai'] = 1;
$tablas[19]['campos'][1]['nombre'] = 'num_ped';
$tablas[19]['campos'][1]['tipo'] = 'int(4)';
$tablas[19]['campos'][1]['null'] = 0;
$tablas[19]['campos'][2]['nombre'] = 'code';
$tablas[19]['campos'][2]['tipo'] = 'varchar(20) COLLATE utf8_spanish2_ci';
$tablas[19]['campos'][2]['null'] = 0;
$tablas[19]['campos'][3]['nombre'] = 'fecha';
$tablas[19]['campos'][3]['tipo'] = 'datetime';
$tablas[19]['campos'][3]['null'] = 0;
$tablas[19]['campos'][4]['nombre'] = 'despacho';
$tablas[19]['campos'][4]['tipo'] = 'tinyint(1)';
$tablas[19]['campos'][4]['null'] = 0;
$tablas[19]['campos'][5]['nombre'] = 'tipo';
$tablas[19]['campos'][5]['tipo'] = 'tinyint(1)';
$tablas[19]['campos'][5]['null'] = 0;
$tablas[19]['campos'][6]['nombre'] = 'estado';
$tablas[19]['campos'][6]['tipo'] = 'tinyint(1)';
$tablas[19]['campos'][6]['null'] = 0;
$tablas[19]['campos'][7]['nombre'] = 'carro';
$tablas[19]['campos'][7]['tipo'] = 'TEXT';
$tablas[19]['campos'][7]['null'] = 0;
$tablas[19]['campos'][8]['nombre'] = 'promos';
$tablas[19]['campos'][8]['tipo'] = 'TEXT';
$tablas[19]['campos'][8]['null'] = 0;
$tablas[19]['campos'][9]['nombre'] = 'verify_despacho';
$tablas[19]['campos'][9]['tipo'] = 'tinyint(1)';
$tablas[19]['campos'][9]['null'] = 0;
$tablas[19]['campos'][10]['nombre'] = 'pre_gengibre';
$tablas[19]['campos'][10]['tipo'] = 'tinyint(1)';
$tablas[19]['campos'][10]['null'] = 0;
$tablas[19]['campos'][11]['nombre'] = 'pre_wasabi';
$tablas[19]['campos'][11]['tipo'] = 'tinyint(1)';
$tablas[19]['campos'][11]['null'] = 0;
$tablas[19]['campos'][12]['nombre'] = 'pre_palitos';
$tablas[19]['campos'][12]['tipo'] = 'tinyint(1)';
$tablas[19]['campos'][12]['null'] = 0;
$tablas[19]['campos'][13]['nombre'] = 'pre_teriyaki';
$tablas[19]['campos'][13]['tipo'] = 'tinyint(1)';
$tablas[19]['campos'][13]['null'] = 0;
$tablas[19]['campos'][14]['nombre'] = 'pre_soya';
$tablas[19]['campos'][14]['tipo'] = 'tinyint(1)';
$tablas[19]['campos'][14]['null'] = 0;
$tablas[19]['campos'][15]['nombre'] = 'comentarios';
$tablas[19]['campos'][15]['tipo'] = 'varchar(255) COLLATE utf8_spanish2_ci';
$tablas[19]['campos'][15]['null'] = 0;
$tablas[19]['campos'][16]['nombre'] = 'costo';
$tablas[19]['campos'][16]['tipo'] = 'int(4)';
$tablas[19]['campos'][16]['null'] = 0;
$tablas[19]['campos'][17]['nombre'] = 'total';
$tablas[19]['campos'][17]['tipo'] = 'int(4)';
$tablas[19]['campos'][17]['null'] = 0;
$tablas[19]['campos'][18]['nombre'] = 'id_mot';
$tablas[19]['campos'][18]['tipo'] = 'int(4)';
$tablas[19]['campos'][18]['null'] = 0;
$tablas[19]['campos'][19]['nombre'] = 'id_puser';
$tablas[19]['campos'][19]['tipo'] = 'int(4)';
$tablas[19]['campos'][19]['null'] = 0;
$tablas[19]['campos'][20]['nombre'] = 'id_pdir';
$tablas[19]['campos'][20]['tipo'] = 'int(4)';
$tablas[19]['campos'][20]['null'] = 0;
$tablas[19]['campos'][21]['nombre'] = 'mod_despacho';
$tablas[19]['campos'][21]['tipo'] = 'tinyint(1)';
$tablas[19]['campos'][21]['null'] = 0;
$tablas[19]['campos'][22]['nombre'] = 'borrados';
$tablas[19]['campos'][22]['tipo'] = 'smallint(2)';
$tablas[19]['campos'][22]['null'] = 0;
$tablas[19]['campos'][23]['nombre'] = 'ocultar';
$tablas[19]['campos'][23]['tipo'] = 'tinyint(1)';
$tablas[19]['campos'][23]['null'] = 0;
$tablas[19]['campos'][24]['nombre'] = 'id_loc';
$tablas[19]['campos'][24]['tipo'] = 'int(4)';
$tablas[19]['campos'][24]['null'] = 0;
$tablas[19]['campos'][24]['k'] = 1;
$tablas[19]['campos'][24]['kt'] = 11;
$tablas[19]['campos'][24]['kc'] = 0;
$tablas[19]['campos'][25]['nombre'] = 'id_gir';
$tablas[19]['campos'][25]['tipo'] = 'int(4)';
$tablas[19]['campos'][25]['null'] = 0;
$tablas[19]['campos'][25]['k'] = 1;
$tablas[19]['campos'][25]['kt'] = 1;
$tablas[19]['campos'][25]['kc'] = 0;
$tablas[19]['campos'][26]['nombre'] = 'eliminado';
$tablas[19]['campos'][26]['tipo'] = 'tinyint(1)';
$tablas[19]['campos'][26]['null'] = 0;

$tablas[20]['nombre'] = 'preguntas';
$tablas[20]['campos'][0]['nombre'] = 'id_pre';
$tablas[20]['campos'][0]['tipo'] = 'int(4)';
$tablas[20]['campos'][0]['null'] = 0;
$tablas[20]['campos'][0]['pk'] = 1;
$tablas[20]['campos'][0]['ai'] = 1;
$tablas[20]['campos'][1]['nombre'] = 'nombre';
$tablas[20]['campos'][1]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[20]['campos'][1]['null'] = 0;
$tablas[20]['campos'][2]['nombre'] = 'mostrar';
$tablas[20]['campos'][2]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[20]['campos'][2]['null'] = 0;
$tablas[20]['campos'][3]['nombre'] = 'id_cat';
$tablas[20]['campos'][3]['tipo'] = 'int(4)';
$tablas[20]['campos'][3]['null'] = 0;
$tablas[20]['campos'][3]['k'] = 1;
$tablas[20]['campos'][3]['kt'] = 2;
$tablas[20]['campos'][3]['kc'] = 0;
$tablas[20]['campos'][4]['nombre'] = 'id_gir';
$tablas[20]['campos'][4]['tipo'] = 'int(4)';
$tablas[20]['campos'][4]['null'] = 0;
$tablas[20]['campos'][4]['k'] = 1;
$tablas[20]['campos'][4]['kt'] = 1;
$tablas[20]['campos'][4]['kc'] = 0;
$tablas[20]['campos'][5]['nombre'] = 'eliminado';
$tablas[20]['campos'][5]['tipo'] = 'tinyint(1)';
$tablas[20]['campos'][5]['null'] = 0;

$tablas[21]['nombre'] = 'preguntas_valores';
$tablas[21]['campos'][0]['nombre'] = 'id_prv';
$tablas[21]['campos'][0]['tipo'] = 'int(4)';
$tablas[21]['campos'][0]['null'] = 0;
$tablas[21]['campos'][0]['pk'] = 1;
$tablas[21]['campos'][0]['ai'] = 1;
$tablas[21]['campos'][1]['nombre'] = 'cantidad';
$tablas[21]['campos'][1]['tipo'] = 'int(4)';
$tablas[21]['campos'][1]['null'] = 0;
$tablas[21]['campos'][2]['nombre'] = 'nombre';
$tablas[21]['campos'][2]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[21]['campos'][2]['null'] = 0;
$tablas[21]['campos'][3]['nombre'] = 'valores';
$tablas[21]['campos'][3]['tipo'] = 'TEXT';
$tablas[21]['campos'][3]['null'] = 0;
$tablas[21]['campos'][4]['nombre'] = 'id_pre';
$tablas[21]['campos'][4]['tipo'] = 'int(4)';
$tablas[21]['campos'][4]['null'] = 0;
$tablas[21]['campos'][4]['k'] = 1;
$tablas[21]['campos'][4]['kt'] = 20;
$tablas[21]['campos'][4]['kc'] = 0;

$tablas[22]['nombre'] = 'preguntas_productos';
$tablas[22]['campos'][0]['nombre'] = 'id_pro';
$tablas[22]['campos'][0]['tipo'] = 'int(4)';
$tablas[22]['campos'][0]['null'] = 0;
$tablas[22]['campos'][0]['pk'] = 1;
$tablas[22]['campos'][0]['k'] = 1;
$tablas[22]['campos'][0]['kt'] = 4;
$tablas[22]['campos'][0]['kc'] = 0;
$tablas[22]['campos'][1]['nombre'] = 'id_pre';
$tablas[22]['campos'][1]['tipo'] = 'int(4)';
$tablas[22]['campos'][1]['null'] = 0;
$tablas[22]['campos'][1]['pk'] = 1;
$tablas[22]['campos'][1]['k'] = 1;
$tablas[22]['campos'][1]['kt'] = 20;
$tablas[22]['campos'][1]['kc'] = 0;

$tablas[23]['nombre'] = 'productos_precio';
$tablas[23]['campos'][0]['nombre'] = 'id_pro';
$tablas[23]['campos'][0]['tipo'] = 'int(4)';
$tablas[23]['campos'][0]['null'] = 0;
$tablas[23]['campos'][0]['pk'] = 1;
$tablas[23]['campos'][0]['k'] = 1;
$tablas[23]['campos'][0]['kt'] = 4;
$tablas[23]['campos'][0]['kc'] = 0;
$tablas[23]['campos'][1]['nombre'] = 'id_cat';
$tablas[23]['campos'][1]['tipo'] = 'int(4)';
$tablas[23]['campos'][1]['null'] = 0;
$tablas[23]['campos'][1]['pk'] = 1;
$tablas[23]['campos'][1]['k'] = 1;
$tablas[23]['campos'][1]['kt'] = 2;
$tablas[23]['campos'][1]['kc'] = 0;
$tablas[23]['campos'][2]['nombre'] = 'precio';
$tablas[23]['campos'][2]['tipo'] = 'int(4)';
$tablas[23]['campos'][2]['null'] = 0;

$tablas[24]['nombre'] = 'promocion_categoria';
$tablas[24]['campos'][0]['nombre'] = 'id_cae1';
$tablas[24]['campos'][0]['tipo'] = 'int(4)';
$tablas[24]['campos'][0]['null'] = 0;
$tablas[24]['campos'][0]['pk'] = 1;
$tablas[24]['campos'][0]['k'] = 1;
$tablas[24]['campos'][0]['kt'] = 3;
$tablas[24]['campos'][0]['kc'] = 0;
$tablas[24]['campos'][1]['nombre'] = 'id_cae2';
$tablas[24]['campos'][1]['tipo'] = 'int(4)';
$tablas[24]['campos'][1]['null'] = 0;
$tablas[24]['campos'][1]['pk'] = 1;
$tablas[24]['campos'][1]['k'] = 1;
$tablas[24]['campos'][1]['kt'] = 3;
$tablas[24]['campos'][1]['kc'] = 0;
$tablas[24]['campos'][2]['nombre'] = 'cantidad';
$tablas[24]['campos'][2]['tipo'] = 'int(4)';
$tablas[24]['campos'][2]['null'] = 0;

$tablas[25]['nombre'] = 'promocion_productos';
$tablas[25]['campos'][0]['nombre'] = 'id_cae';
$tablas[25]['campos'][0]['tipo'] = 'int(4)';
$tablas[25]['campos'][0]['null'] = 0;
$tablas[25]['campos'][0]['pk'] = 1;
$tablas[25]['campos'][0]['k'] = 1;
$tablas[25]['campos'][0]['kt'] = 3;
$tablas[25]['campos'][0]['kc'] = 0;
$tablas[25]['campos'][1]['nombre'] = 'id_pro';
$tablas[25]['campos'][1]['tipo'] = 'int(4)';
$tablas[25]['campos'][1]['null'] = 0;
$tablas[25]['campos'][1]['pk'] = 1;
$tablas[25]['campos'][1]['k'] = 1;
$tablas[25]['campos'][1]['kt'] = 4;
$tablas[25]['campos'][1]['kc'] = 0;
$tablas[25]['campos'][2]['nombre'] = 'cantidad';
$tablas[25]['campos'][2]['tipo'] = 'int(4)';
$tablas[25]['campos'][2]['null'] = 0;
$tablas[25]['campos'][3]['nombre'] = 'parent_id';
$tablas[25]['campos'][3]['tipo'] = 'int(4)';
$tablas[25]['campos'][3]['null'] = 0;

$tablas[26]['nombre'] = 'seguimiento';
$tablas[26]['campos'][0]['nombre'] = 'id_seg';
$tablas[26]['campos'][0]['tipo'] = 'int(4)';
$tablas[26]['campos'][0]['null'] = 0;
$tablas[26]['campos'][0]['pk'] = 1;
$tablas[26]['campos'][0]['ai'] = 1;
$tablas[26]['campos'][1]['nombre'] = 'txt';
$tablas[26]['campos'][1]['tipo'] = 'TEXT';
$tablas[26]['campos'][1]['null'] = 0;
$tablas[26]['campos'][2]['nombre'] = 'fecha';
$tablas[26]['campos'][2]['tipo'] = 'datetime';
$tablas[26]['campos'][2]['null'] = 0;
$tablas[26]['campos'][3]['nombre'] = 'id_des';
$tablas[26]['campos'][3]['tipo'] = 'int(4)';
$tablas[26]['campos'][3]['null'] = 0;
$tablas[26]['campos'][4]['nombre'] = 'id_user';
$tablas[26]['campos'][4]['tipo'] = 'int(4)';
$tablas[26]['campos'][4]['null'] = 0;
$tablas[26]['campos'][5]['nombre'] = 'id_loc';
$tablas[26]['campos'][5]['tipo'] = 'int(4)';
$tablas[26]['campos'][5]['null'] = 0;
$tablas[26]['campos'][6]['nombre'] = 'id_gir';
$tablas[26]['campos'][6]['tipo'] = 'int(4)';
$tablas[26]['campos'][6]['null'] = 0;

$tablas[27]['nombre'] = 'seguimiento_desc';
$tablas[27]['campos'][0]['nombre'] = 'id_des';
$tablas[27]['campos'][0]['tipo'] = 'int(4)';
$tablas[27]['campos'][0]['null'] = 0;
$tablas[27]['campos'][0]['pk'] = 1;
$tablas[27]['campos'][0]['ai'] = 1;
$tablas[27]['campos'][1]['nombre'] = 'nombre';
$tablas[27]['campos'][1]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[27]['campos'][1]['null'] = 0;

$tablas[28]['nombre'] = 'seguimiento_web';
$tablas[28]['campos'][0]['nombre'] = 'id';
$tablas[28]['campos'][0]['tipo'] = 'int(4)';
$tablas[28]['campos'][0]['null'] = 0;
$tablas[28]['campos'][0]['pk'] = 1;
$tablas[28]['campos'][0]['ai'] = 1;
$tablas[28]['campos'][1]['nombre'] = 'nombre';
$tablas[28]['campos'][1]['tipo'] = 'varchar(255) COLLATE utf8_spanish2_ci';
$tablas[28]['campos'][1]['null'] = 0;
$tablas[28]['campos'][2]['nombre'] = 'code';
$tablas[28]['campos'][2]['tipo'] = 'varchar(4) COLLATE utf8_spanish2_ci';
$tablas[28]['campos'][2]['null'] = 0;
$tablas[28]['campos'][3]['nombre'] = 'stat';
$tablas[28]['campos'][3]['tipo'] = 'int(4)';
$tablas[28]['campos'][3]['null'] = 0;
$tablas[28]['campos'][4]['nombre'] = 'fecha';
$tablas[28]['campos'][4]['tipo'] = 'datetime';
$tablas[28]['campos'][4]['null'] = 0;
$tablas[28]['campos'][5]['nombre'] = 'id_puser';
$tablas[28]['campos'][5]['tipo'] = 'int(4)';
$tablas[28]['campos'][5]['null'] = 0;
$tablas[28]['campos'][6]['nombre'] = 'id_gir';
$tablas[28]['campos'][6]['tipo'] = 'int(4)';
$tablas[28]['campos'][6]['null'] = 0;

$tablas[29]['nombre'] = 'ses_mail';
$tablas[29]['campos'][0]['nombre'] = 'correo';
$tablas[29]['campos'][0]['tipo'] = 'varchar(150) COLLATE utf8_spanish2_ci';
$tablas[29]['campos'][0]['null'] = 0;
$tablas[29]['campos'][0]['pk'] = 1;

$tablas[30]['nombre'] = 'pagos';
$tablas[30]['campos'][0]['nombre'] = 'id_pago';
$tablas[30]['campos'][0]['tipo'] = 'int(4)';
$tablas[30]['campos'][0]['null'] = 0;
$tablas[30]['campos'][0]['pk'] = 1;
$tablas[30]['campos'][0]['ai'] = 1;
$tablas[30]['campos'][1]['nombre'] = 'fecha';
$tablas[30]['campos'][1]['tipo'] = 'datetime';
$tablas[30]['campos'][1]['null'] = 0;
$tablas[30]['campos'][2]['nombre'] = 'monto';
$tablas[30]['campos'][2]['tipo'] = 'int(4)';
$tablas[30]['campos'][2]['null'] = 0;
$tablas[30]['campos'][3]['nombre'] = 'meses';
$tablas[30]['campos'][3]['tipo'] = 'tinyint(1)';
$tablas[30]['campos'][3]['null'] = 0;
$tablas[30]['campos'][4]['nombre'] = 'factura';
$tablas[30]['campos'][4]['tipo'] = 'tinyint(1)';
$tablas[30]['campos'][4]['null'] = 0;
$tablas[30]['campos'][5]['nombre'] = 'id_gir';
$tablas[30]['campos'][5]['tipo'] = 'int(4)';
$tablas[30]['campos'][5]['null'] = 0;
$tablas[30]['campos'][5]['k'] = 1;
$tablas[30]['campos'][5]['kt'] = 1;
$tablas[30]['campos'][5]['kc'] = 0;

// TRUE SOLO MUESTRA - FALSE LAS CREA
$show = true;

for($i=0; $i<count($tablas); $i++){

    $tabla = "CREATE TABLE IF NOT EXISTS `".$tablas[$i]["nombre"]."` (";
    $aux_t = [];
    for($j=0; $j<count($tablas[$i]["campos"]); $j++){
        $aux = "`".$tablas[$i]["campos"][$j]["nombre"]."` ".$tablas[$i]["campos"][$j]["tipo"];
        $aux .= ($tablas[$i]["campos"][$j]["null"] == 0) ? " NOT NULL" : " NULL" ;
        $aux_t[] = $aux;
    }
    $tabla .= implode(",", $aux_t).") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;";
    $tables[] = $tabla;
    $tables_name[] = $tablas[$i]["nombre"];
    
}
for($i=0; $i<count($tablas); $i++){

    $key = "ALTER TABLE `".$tablas[$i]["nombre"]."`";
    $aux_t = [];
    $aux_c = [];
    $pk = [];
    $c = 1;

    for($j=0; $j<count($tablas[$i]["campos"]); $j++){
        if(isset($tablas[$i]["campos"][$j]['pk'])){
            $pk[] = "`".$tablas[$i]["campos"][$j]["nombre"]."`";
        }
        if(isset($tablas[$i]["campos"][$j]['k'])){
            $aux_t[] = " ADD KEY `".$tablas[$i]["campos"][$j]["nombre"]."` (`".$tablas[$i]["campos"][$j]["nombre"]."`)";
            if(isset($tablas[$i]["campos"][$j]['kt']) && isset($tablas[$i]["campos"][$j]['kc'])){
                $aux_c[] = " ADD CONSTRAINT `".$tablas[$i]["nombre"]."_ibfk_".$c."` FOREIGN KEY (`".$tablas[$i]["campos"][$j]["nombre"]."`) REFERENCES `".$tablas[$tablas[$i]["campos"][$j]['kt']]["nombre"]."` (`".$tablas[$tablas[$i]["campos"][$j]['kt']]["campos"][$tablas[$i]["campos"][$j]['kc']]["nombre"]."`) ON DELETE CASCADE ON UPDATE CASCADE";
                $c++;
            }
        }
        if(isset($tablas[$i]["campos"][$j]['ai'])){
            $ai = $key;
            $ai .= " MODIFY `".$tablas[$i]["campos"][$j]["nombre"]."` ".$tablas[$i]["campos"][$j]["tipo"]."";
            $ai .= ($tablas[$i]["campos"][$j]["null"] == 0) ? " NOT NULL" : " NULL" ;
            $ai .= " AUTO_INCREMENT, AUTO_INCREMENT=1;";
            $ais[] = $ai;
        }
    }
    
    if(count($aux_t) > 0 || count($pk) > 0){
        $aux_key = $key;
        if(count($pk) > 0){
            $aux_key .= " ADD PRIMARY KEY (".implode(",", $pk).")";
            if(count($aux_t) > 0){
                $aux_key .= ",";
            }
        }
        if(count($aux_t) > 0){
            $aux_key .= implode(",", $aux_t);
        }
        $keys[] = $aux_key;
    }
    if(count($aux_c) > 0){
        $cons[] = $key.implode(",", $aux_c).";";
    }

}



if($con->query("CREATE DATABASE IF NOT EXISTS ".$db_database[0]." CHARACTER SET UTF8 COLLATE UTF8_GENERAL_CI")){
    echo "BASE CREADA: ".$db_database[0]."<br/><br/>TABLAS<br/><br/>";
    $con->select_db($db_database[0]);
    for($i=0; $i<count($tables); $i++){
        if(!$show){
            if($con->query($tables[$i])){
                echo "Tabla creada: ".$tables_name[$i]."<br/><br/>";
            }else{
                echo "<strong>ERROR: ".$tables_name[$i]." NO FUE CREADA</strong> => ".$con->error."<br/><br/>";
            }
        }else{ echo $tables[$i]."<br/><br/>"; }
    }
    echo "<br/><br/>KEYS<br/><br/>";
    for($i=0; $i<count($keys); $i++){
        if(!$show){
            if($con->query($keys[$i])){
                echo $keys[$i]." <br/><br/>";
                echo "ALTER CREADO: <br/><br/>";
            }else{
                echo "<strong>ERROR: KEY </strong> => ".$con->error." <br/><br/>";
            }
        }else{ echo $keys[$i]."<br/><br/>"; }
    }
    echo "<br/><br/>AUTOINCREMENTS<br/><br/>";
    for($i=0; $i<count($ais); $i++){
        if(!$show){
            if($con->query($ais[$i])){
                echo $ais[$i]."<br/><br/>";
                echo "ALTER CREADO: <br/><br/>";
            }else{
                echo "<strong>ERROR: AUTO</strong> => ".$con->error." <br/><br/>";
            }
        }else{ echo $ais[$i]."<br/><br/>"; }
    }
    echo "<br/><br/>FILTROS<br/><br/>";
    for($i=0; $i<count($cons); $i++){
        if(!$show){
            if($con->query($cons[$i])){
                echo $cons[$i]."<br/><br/>";
                echo "ALTER CREADO: <br/><br/>";
            }else{
                echo "<strong>ERROR: FILTRO</strong> => ".$con->error." <br/><br/>";
            }
        }else{ echo $cons[$i]."<br/><br/>"; }
    }
    echo "<br/><br/>INSERT<br/><br/>";
    for($i=0; $i<count($tablas); $i++){
        $campos = [];
        $matriz = [];
        for($j=0; $j<count($tablas[$i]["campos"]); $j++){
            $cant = count($tablas[$i]["campos"][$j]["values"]);
            if($cant > 0){
                $campos[] = $tablas[$i]["campos"][$j]["nombre"];
                for($k=0; $k<$cant; $k++){
                    $matriz[$k][] = "'".$tablas[$i]["campos"][$j]["values"][$k]."'";
                }
            }
        }
        for($j=0; $j<count($matriz); $j++){
            if(!$show){
                $sql = "INSERT INTO ".$tablas[$i]["nombre"]." (".implode(", ", $campos).") VALUES (".implode(", ", $matriz[$j]).")";
                if($con->query($sql)){
                    echo $sql." <br/><br/>";
                    echo "INSERTADO <br/><br/>";
                }else{
                    echo "<strong>ERROR: INSERT</strong> => ".$con->error." <br/><br/>";
                }
            }else{ echo $sql."<br/><br/>"; }
        }
    }
}else{
    echo "ERROR CREAR BASE: ".$con->error."<br/>";
}

/*

*/