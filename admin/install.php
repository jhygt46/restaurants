<?php
set_time_limit(0);

//die("INSTALADO");

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
$url = url();

require_once $url["dir"]."/admin/class/install_class.php";
$in = new Install();

$in->crearTable('server');
$in->add('id_ser', 'int(4)', 0, null, 1, 1);
$in->add('nombre', 'varchar(20) COLLATE utf8_spanish2_ci', 0);
$in->add('ip', 'varchar(15) COLLATE utf8_spanish2_ci', 0);
$in->add('code', 'varchar(60) COLLATE utf8_spanish2_ci', 0);
$in->add_tabla();

$in->crearTable('giros');
$in->add('id_gir', 'int(4)', 0, null, 1, 1);
$in->add('nombre', 'varchar(100) COLLATE utf8_spanish2_ci', 0);
$in->add('telefono', 'varchar(14) COLLATE utf8_spanish2_ci', 0);
$in->add('dominio', 'varchar(100) COLLATE utf8_spanish2_ci', 0);
$in->add('estado', 'varchar(255) COLLATE utf8_spanish2_ci', 0);
$in->add('titulo', 'varchar(50) COLLATE utf8_spanish2_ci', 0);
$in->add('code', 'varchar(20) COLLATE utf8_spanish2_ci', 0);
$in->add('catalogo', 'int(4)', 0);
$in->add('ssl', 'tinyint(1)', 0);
$in->add('solicitar_ssl', 'tinyint(1)', 0);
$in->add('dns', 'tinyint(1)', 0);
$in->add('dns_letra', 'varchar(1) COLLATE utf8_spanish2_ci', 0);
$in->add('pos', 'tinyint(1)', 0);
$in->add('inicio_html', 'TEXT', 0);
$in->add('footer_html', 'TEXT', 0);
$in->add('style_page', 'varchar(44) COLLATE utf8_spanish2_ci', 0);
$in->add('style_color', 'varchar(44) COLLATE utf8_spanish2_ci', 0);
$in->add('style_modal', 'varchar(44) COLLATE utf8_spanish2_ci', 0);
$in->add('font_family', 'varchar(50) COLLATE utf8_spanish2_ci', 0);
$in->add('font_css', 'varchar(50) COLLATE utf8_spanish2_ci', 0);
$in->add('logo', 'varchar(50) COLLATE utf8_spanish2_ci', 0);
$in->add('favicon', 'varchar(30) COLLATE utf8_spanish2_ci', 0);
$in->add('foto_retiro', 'varchar(50) COLLATE utf8_spanish2_ci', 0);
$in->add('foto_despacho', 'varchar(50) COLLATE utf8_spanish2_ci', 0);
$in->add('alto', 'tinyint(1)', 0);
$in->add('alto_pro', 'tinyint(1)', 0);
$in->add('pedido_minimo', 'int(4)', 0);
$in->add('pedido_01_titulo', 'varchar(60) COLLATE utf8_spanish2_ci', 0);
$in->add('pedido_01_subtitulo', 'varchar(60) COLLATE utf8_spanish2_ci', 0);
$in->add('pedido_02_titulo', 'varchar(60) COLLATE utf8_spanish2_ci', 0);
$in->add('pedido_02_subtitulo', 'varchar(60) COLLATE utf8_spanish2_ci', 0);
$in->add('pedido_03_titulo', 'varchar(60) COLLATE utf8_spanish2_ci', 0);
$in->add('pedido_03_subtitulo', 'varchar(60) COLLATE utf8_spanish2_ci', 0);
$in->add('pedido_04_titulo', 'varchar(60) COLLATE utf8_spanish2_ci', 0);
$in->add('pedido_04_subtitulo', 'varchar(60) COLLATE utf8_spanish2_ci', 0);
$in->add('ultima_actualizacion', 'datetime', 0);
$in->add('retiro_local', 'tinyint(1)', 0);
$in->add('despacho_domicilio', 'tinyint(1)', 0);
$in->add('lista_locales', 'TEXT', 0);
$in->add('num_ped', 'int(4)', 0);
$in->add('con_cambios', 'tinyint(1)', 0);
$in->add('pedido_wasabi', 'tinyint(1)', 0);
$in->add('pedido_gengibre', 'tinyint(1)', 0);
$in->add('pedido_palitos', 'tinyint(1)', 0);
$in->add('pedido_comentarios', 'tinyint(1)', 0);
$in->add('pedido_soya', 'tinyint(1)', 0);
$in->add('pedido_teriyaki', 'tinyint(1)', 0);
$in->add('desde', 'int(4)', 0);
$in->add('mapcode', 'varchar(100) COLLATE utf8_spanish2_ci', 0);
$in->add('item_grafico', 'tinyint(1)', 0);
$in->add('item_pos', 'tinyint(1)', 0);
$in->add('item_cocina', 'tinyint(1)', 0);
$in->add('item_pagina', 'tinyint(1)', 0);
$in->add('fecha_creado', 'datetime', 0);
$in->add('fecha_dns', 'date', 0);
$in->add('tiempo_aviso', 'smallint(2)', 0);
$in->add('ver_inicio', 'tinyint(1)', 0);
$in->add('monto', 'int(4)', 0);
$in->add('monto_vendedor', 'int(4)', 0);
$in->add('cant_pagos', 'smallint(2)', 0);
$in->add('prueba', 'tinyint(1)', 0);
$in->add('tipo_add_carro', 'tinyint(1)', 0);
$in->add('mostrar_numero', 'tinyint(1)', 0);
$in->add('id_ser', 'int(4)', 0, null, null, null, 1, 0, 0);
$in->add('eliminado', 'tinyint(1)', 0);
$in->add_tabla();

$in->crearTable('catalogo_productos');
$in->add('id_cat', 'int(4)', 0, null, 1, 1);
$in->add('nombre', 'varchar(100) COLLATE utf8_spanish2_ci', 0);
$in->add('fecha_creado', 'datetime', 0);
$in->add('id_gir', 'int(4)', 0, null, null, null, 1, 1, 0);
$in->add('eliminado', 'tinyint(1)', 0);
$in->add_tabla();

$in->crearTable('categorias');
$in->add('id_cae', 'int(4)', 0, null, 1, 1);
$in->add('parent_id', 'int(4)', 0);
$in->add('nombre', 'varchar(100) COLLATE utf8_spanish2_ci', 0);
$in->add('descripcion', 'varchar(255) COLLATE utf8_spanish2_ci', 0);
$in->add('descripcion_sub', 'varchar(255) COLLATE utf8_spanish2_ci', 0);
$in->add('precio', 'int(4)', 0);
$in->add('tipo', 'tinyint(1)', 0);
$in->add('ocultar', 'tinyint(1)', 0);
$in->add('mostrar_prods', 'tinyint(1)', 0);
$in->add('detalle_prods', 'tinyint(1)', 0);
$in->add('image', 'varchar(40) COLLATE utf8_spanish2_ci', 0);
$in->add('degradado', 'smallint(2)', 0);
$in->add('orders', 'int(4)', 0);
$in->add('id_cat', 'int(4)', 0, null, null, null, 1, 2, 0);
$in->add('id_gir', 'int(4)', 0, null, null, null, 1, 1, 0);
$in->add('eliminado', 'tinyint(1)', 0);
$in->add('aux_promo', 'tinyint(1)', 0);
$in->add_tabla();

$in->crearTable('productos');
$in->add('id_pro', 'int(4)', 0, null, 1, 1);
$in->add('numero', 'smallint(2)', 0);
$in->add('nombre', 'varchar(100) COLLATE utf8_spanish2_ci', 0);
$in->add('nombre_carro', 'varchar(255) COLLATE utf8_spanish2_ci', 0);
$in->add('descripcion', 'varchar(255) COLLATE utf8_spanish2_ci', 0);
$in->add('image', 'varchar(40) COLLATE utf8_spanish2_ci', 0);
$in->add('fecha_creado', 'datetime', 0);
$in->add('id_gir', 'int(4)', 0, null, null, null, 1, 1, 0);
$in->add('eliminado', 'tinyint(1)', 0);
$in->add('disponible', 'tinyint(1)', 0);
$in->add('tipo', 'tinyint(1)', 0);
$in->add('aux_promo', 'tinyint(1)', 0);
$in->add_tabla();

$in->crearTable('cat_pros');
$in->add('id_cae', 'int(4)', 0, null, null, null, 1, 3, 0);
$in->add('id_pro', 'int(4)', 0, null, null, null, 1, 4, 0);
$in->add('orders', 'int(4)', 0);
$in->add_tabla();

$in->crearTable('css');
$in->add('id_css', 'int(4)', 0, null, 1, 1);
$in->add('nombre', 'varchar(70) COLLATE utf8_spanish2_ci', 0);
$in->add('archivo', 'varchar(40) COLLATE utf8_spanish2_ci', 0);
$in->add('tipo', 'tinyint(1)', 0);
$in->add('id_gir', 'int(4)', 0);
$in->add('eliminado', 'tinyint(1)', 0);
$in->add_tabla();

$in->crearTable('fw_usuarios');
$in->add('id_user', 'int(4)', 0, null, 1, 1);
$in->add('nombre', 'varchar(100) COLLATE utf8_spanish2_ci', 0, 'Diego Gomez');
$in->add('correo', 'varchar(100) COLLATE utf8_spanish2_ci', 0, 'misitiodelivery@gmail.com');
$in->add('pass', 'varchar(32) COLLATE utf8_spanish2_ci', 0, 'ef3901f2629f57c096651c2f5697f01b');
$in->add('mailcode', 'varchar(32) COLLATE utf8_spanish2_ci', 0, '');
$in->add('cookie_code', 'varchar(60) COLLATE utf8_spanish2_ci', 0, '');
$in->add('fecha_creado', 'datetime', 0, '2018-08-07 00:00:00');
$in->add('admin', 'tinyint(1)', 0, '1');
$in->add('tipo', 'tinyint(1)', 0, '1');
$in->add('re_venta', 'tinyint(1)', 0, '0');
$in->add('id_aux_user', 'int(4)', 0, '0');
$in->add('save_web', 'tinyint(1)', 0, '0');
$in->add('web_min', 'smallint(2)', 0, '0');
$in->add('save_pos', 'tinyint(1)', 0, '0');
$in->add('pos_min', 'smallint(2)', 0, '0');
$in->add('del_pdir', 'tinyint(1)', 0, '0');
$in->add('telefono', 'varchar(15) COLLATE utf8_spanish2_ci', 0, '+56966166923');
$in->add('id_loc', 'int(4)', 0, '0');
$in->add('id_gir', 'int(4)', 0, '1');
$in->add('eliminado', 'tinyint(1)', 0, '0');
$in->add_tabla();

$in->crearTable('fw_acciones');
$in->add('id_acc', 'int(4)', 0, null, 1, 1);
$in->add('tipo', 'tinyint(1)', 0);
$in->add('fecha', 'datetime', 0);
$in->add('id_user', 'int(4)', 0, null, null, null, 1, 7, 0);
$in->add_tabla();

$in->crearTable('fw_usuarios_giros');
$in->add('id_user', 'int(4)', 0, null, null, null, 1, 7, 0);
$in->add('id_gir', 'int(4)', 0, null, null, null, 1, 1, 0);
$in->add_tabla();

$in->crearTable('fw_usuarios_giros_clientes');
$in->add('id_user', 'int(4)', 0, null, null, null, 1, 7, 0);
$in->add('id_gir', 'int(4)', 0, null, null, null, 1, 1, 0);
$in->add_tabla();

$in->crearTable('locales');
$in->add('id_loc', 'int(4)', 0, null, 1, 1);
$in->add('nombre', 'varchar(100) COLLATE utf8_spanish2_ci', 0);
$in->add('direccion', 'varchar(255) COLLATE utf8_spanish2_ci', 0);
$in->add('telefono', 'varchar(15) COLLATE utf8_spanish2_ci', 0);
$in->add('whatsapp', 'varchar(15) COLLATE utf8_spanish2_ci', 0);
$in->add('lat', 'double', 0);
$in->add('lng', 'double', 0);
$in->add('image', 'varchar(50) COLLATE utf8_spanish2_ci', 0);
$in->add('code', 'varchar(20) COLLATE utf8_spanish2_ci', 0);
$in->add('cookie_code', 'varchar(60) COLLATE utf8_spanish2_ci', 0);
$in->add('cookie_ip', 'varchar(15) COLLATE utf8_spanish2_ci', 0);
$in->add('correo', 'varchar(100) COLLATE utf8_spanish2_ci', 0);
$in->add('correo_ses', 'tinyint(1)', 0);
$in->add('tipo_comanda', 'tinyint(1)', 0);
$in->add('enviar_cocina', 'tinyint(1)', 0);
$in->add('t_retiro', 'smallint(2)', 0);
$in->add('t_despacho', 'smallint(2)', 0);
$in->add('sonido', 'varchar(20) COLLATE utf8_spanish2_ci', 0);
$in->add('pos', 'tinyint(1)', 0);
$in->add('activar_envio', 'tinyint(1)', 0);
$in->add('fecha_creado', 'datetime', 0);
$in->add('fecha_cocina', 'datetime', 0);
$in->add('fecha_pos', 'datetime', 0);
$in->add('id_cat', 'int(4)', 0, null, null, null, 1, 2, 0);
$in->add('id_gir', 'int(4)', 0, null, null, null, 1, 1, 0);
$in->add('eliminado', 'tinyint(1)', 0);
$in->add_tabla();

$in->crearTable('locales_tramos');
$in->add('id_lot', 'int(4)', 0, null, 1, 1);
$in->add('nombre', 'varchar(60) COLLATE utf8_spanish2_ci', 0);
$in->add('precio', 'int(4)', 0);
$in->add('poligono', 'TEXT', 0);
$in->add('id_loc', 'int(4)', 0, null, null, null, 1, 11, 0);
$in->add('eliminado', 'tinyint(1)', 0);
$in->add_tabla();

$in->crearTable('horarios');
$in->add('id_hor', 'int(4)', 0, null, 1, 1);
$in->add('dia_ini', 'tinyint(1)', 0);
$in->add('dia_fin', 'tinyint(1)', 0);
$in->add('hora_ini', 'tinyint(1)', 0);
$in->add('hora_fin', 'tinyint(1)', 0);
$in->add('min_ini', 'tinyint(1)', 0);
$in->add('min_fin', 'tinyint(1)', 0);
$in->add('tipo', 'tinyint(1)', 0);
$in->add('id_loc', 'int(4)', 0, null, null, null, 1, 11, 0);
$in->add('id_gir', 'int(4)', 0, null, null, null, 1, 1, 0);
$in->add('eliminado', 'tinyint(1)', 0);
$in->add_tabla();

$in->crearTable('motos');
$in->add('id_mot', 'int(4)', 0, null, 1, 1);
$in->add('nombre', 'varchar(40) COLLATE utf8_spanish2_ci', 0);
$in->add('correo', 'varchar(100) COLLATE utf8_spanish2_ci', 0);
$in->add('uid', 'varchar(20) COLLATE utf8_spanish2_ci', 0);
$in->add('id_gir', 'int(4)', 0, null, null, null, 1, 1, 0);
$in->add('eliminado', 'tinyint(1)', 0);
$in->add_tabla();

$in->crearTable('motos_locales');
$in->add('id_mot', 'int(4)', 0, null, null, null, 1, 14, 0);
$in->add('id_loc', 'int(4)', 0, null, null, null, 1, 11, 0);
$in->add_tabla();

$in->crearTable('paginas');
$in->add('id_pag', 'int(4)', 0, null, 1, 1);
$in->add('nombre', 'varchar(100) COLLATE utf8_spanish2_ci', 0);
$in->add('html', 'TEXT', 0);
$in->add('imagen', 'varchar(40) COLLATE utf8_spanish2_ci', 0);
$in->add('tipo', 'smallint(2)', 0);
$in->add('orders', 'smallint(2)', 0);
$in->add('id_gir', 'int(4)', 0, null, null, null, 1, 1, 0);
$in->add('eliminado', 'tinyint(1)', 0);
$in->add('visible', 'tinyint(1)', 0);
$in->add_tabla();

$in->crearTable('pedidos_usuarios');
$in->add('id_puser', 'int(4)', 0, null, 1, 1);
$in->add('codigo', 'varchar(20) COLLATE utf8_spanish2_ci', 0);
$in->add('nombre', 'varchar(30) COLLATE utf8_spanish2_ci', 0);
$in->add('telefono', 'varchar(15) COLLATE utf8_spanish2_ci', 0);
$in->add('cont', 'smallint(2)', 0);
$in->add('fecha_ultimo', 'datetime', 0);
$in->add('pedido_falso', 'tinyint(1)', 0);
$in->add('tipo', 'tinyint(1)', 0);
$in->add('id_gir', 'int(4)', 0, null, null, null, 1, 1, 0);
$in->add('eliminado', 'tinyint(1)', 0);
$in->add_tabla();

$in->crearTable('pedidos_direccion');
$in->add('id_pdir', 'int(4)', 0, null, 1, 1);
$in->add('direccion', 'varchar(230) COLLATE utf8_spanish2_ci', 0);
$in->add('calle', 'varchar(150) COLLATE utf8_spanish2_ci', 0);
$in->add('num', 'varchar(20) COLLATE utf8_spanish2_ci', 0);
$in->add('depto', 'varchar(20) COLLATE utf8_spanish2_ci', 0);
$in->add('comuna', 'varchar(40) COLLATE utf8_spanish2_ci', 0);
$in->add('lat', 'double', 0);
$in->add('lng', 'double', 0);
$in->add('id_puser', 'int(4)', 0, null, null, null, 1, 17, 0);
$in->add_tabla();

$in->crearTable('pedidos_aux');
$in->add('id_ped', 'int(4)', 0, null, 1, 1);
$in->add('num_ped', 'int(4)', 0);
$in->add('code', 'varchar(20) COLLATE utf8_spanish2_ci', 0);
$in->add('fecha', 'datetime', 0);
$in->add('despacho', 'tinyint(1)', 0);
$in->add('tipo', 'tinyint(1)', 0);
$in->add('estado', 'tinyint(1)', 0);
$in->add('carro', 'TEXT', 0);
$in->add('promos', 'TEXT', 0);
$in->add('verify_despacho', 'tinyint(1)', 0);
$in->add('pre_gengibre', 'tinyint(1)', 0);
$in->add('pre_wasabi', 'tinyint(1)', 0);
$in->add('pre_palitos', 'tinyint(1)', 0);
$in->add('pre_teriyaki', 'tinyint(1)', 0);
$in->add('pre_soya', 'tinyint(1)', 0);
$in->add('comentarios', 'varchar(255) COLLATE utf8_spanish2_ci', 0);
$in->add('costo', 'int(4)', 0);
$in->add('total', 'int(4)', 0);
$in->add('id_mot', 'int(4)', 0);
$in->add('id_puser', 'int(4)', 0);
$in->add('id_pdir', 'int(4)', 0);
$in->add('mod_despacho', 'tinyint(1)', 0);
$in->add('borrados', 'smallint(2)', 0);
$in->add('ocultar', 'tinyint(1)', 0);
$in->add('id_user', 'int(4)', 0);
$in->add('id_loc', 'int(4)', 0, null, null, null, 1, 11, 0);
$in->add('id_gir', 'int(4)', 0, null, null, null, 1, 1, 0);
$in->add('eliminado', 'tinyint(1)', 0);
$in->add_tabla();

$in->crearTable('preguntas');
$in->add('id_pre', 'int(4)', 0, null, 1, 1);
$in->add('nombre', 'varchar(100) COLLATE utf8_spanish2_ci', 0);
$in->add('mostrar', 'varchar(100) COLLATE utf8_spanish2_ci', 0);
$in->add('id_cat', 'int(4)', 0, null, null, null, 1, 2, 0);
$in->add('id_gir', 'int(4)', 0, null, null, null, 1, 1, 0);
$in->add('eliminado', 'tinyint(1)', 0);
$in->add_tabla();

$in->crearTable('preguntas_valores');
$in->add('id_prv', 'int(4)', 0, null, 1, 1);
$in->add('cantidad', 'int(4)', 0);
$in->add('nombre', 'varchar(100) COLLATE utf8_spanish2_ci', 0);
$in->add('valores', 'TEXT', 0);
$in->add('id_pre', 'int(4)', 0, null, null, null, 1, 20, 0);
$in->add_tabla();

$in->crearTable('preguntas_productos');
$in->add('id_pro', 'int(4)', 0, null, null, null, 1, 4, 0);
$in->add('id_pre', 'int(4)', 0, null, null, null, 1, 20, 0);
$in->add_tabla();

$in->crearTable('productos_precio');
$in->add('id_pro', 'int(4)', 0, null, null, null, 1, 4, 0);
$in->add('id_cat', 'int(4)', 0, null, null, null, 1, 2, 0);
$in->add('precio', 'int(4)', 0);
$in->add_tabla();

$in->crearTable('promocion_categoria');
$in->add('id_cae1', 'int(4)', 0, null, null, null, 1, 3, 0);
$in->add('id_cae2', 'int(4)', 0, null, null, null, 1, 3, 0);
$in->add('cantidad', 'int(4)', 0);
$in->add_tabla();

$in->crearTable('promocion_productos');
$in->add('id_cae', 'int(4)', 0, null, null, null, 1, 3, 0);
$in->add('id_pro', 'int(4)', 0, null, null, null, 1, 4, 0);
$in->add('cantidad', 'int(4)', 0);
$in->add('parent_id', 'int(4)', 0);
$in->add_tabla();

$in->crearTable('seguimiento');
$in->add('id_seg', 'int(4)', 0, null, 1, 1);
$in->add('txt', 'TEXT', 0);
$in->add('fecha', 'datetime', 0);
$in->add('id_des', 'int(4)', 0);
$in->add('id_user', 'int(4)', 0);
$in->add('id_loc', 'int(4)', 0);
$in->add('id_gir', 'int(4)', 0);
$in->add_tabla();

$in->crearTable('seguimiento_desc');
$in->add('id_des', 'int(4)', 0, 1, 1);
$in->add('nombre', 'varchar(100) COLLATE utf8_spanish2_ci', 0);
$in->add_tabla();

$in->crearTable('seguimiento_web');
$in->add('id', 'int(4)', 0, 1, 1);
$in->add('nombre', 'varchar(255) COLLATE utf8_spanish2_ci', 0);
$in->add('code', 'varchar(4) COLLATE utf8_spanish2_ci', 0);
$in->add('stat', 'int(4)', 0);
$in->add('fecha', 'datetime', 0);
$in->add('id_puser', 'int(4)', 0);
$in->add('id_gir', 'int(4)', 0);
$in->add_tabla();

$in->crearTable('ses_mail');
$in->add('correo', 'varchar(150) COLLATE utf8_spanish2_ci', 0, 1, 1);
$in->add_tabla();

$in->crearTable('pagos');
$in->add('id_pago', 'int(4)', 0, 1, 1);
$in->add('fecha', 'datetime', 0);
$in->add('monto', 'int(4)', 0);
$in->add('meses', 'tinyint(1)', 0);
$in->add('factura', 'tinyint(1)', 0);
$in->add('id_gir', 'int(4)', 0, null, null, null, 1, 1, 0);
$in->add_tabla();

$in->crearTable('pago_proveedores');
$in->add('id_pap', 'int(4)', 0, 1, 1);
$in->add('monto', 'int(4)', 0);
$in->add('fecha', 'datetime', 0);
$in->add('id_user', 'int(4)', 0, null, null, null, 1, 7, 0);
$in->add_tabla();

$in->crearTable('set_graficos');
$in->add('id_set', 'int(4)', 0, 1, 1);
$in->add('nombre', 'varchar(100) COLLATE utf8_spanish2_ci', 0);
$in->add('fecha_creado', 'datetime', 0);
$in->add('id_gir', 'int(4)', 0, null, null, null, 1, 1, 0);
$in->add('eliminado', 'tinyint(1)', 0);
$in->add_tabla();

$in->crearTable('set_graficos_id');
$in->add('id_set', 'int(4)', 0, 1, 1);
$in->add('id_grf', 'int(4)', 0, 1, 1);
$in->add('orders', 'int(4)', 0);
$in->add_tabla();



$in->ejecutar(true);
$in->detalle(2);
$in->process();
$in->llenar_data('http://www.misitiodelivery.cl/admin/info.php?aux=');



