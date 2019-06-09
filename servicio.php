<?php

require_once "/var/www/html/config/config.php";
$con = new mysqli($db_host[0], $db_user[0], $db_password[0], $db_database[0]);

echo get_web_js_data_remote($_POST["host"], $con);

function get_web_js_data_remote($host, $con){

	$eliminado = 0;
	if($sqlgiro = $con->prepare("SELECT id_gir FROM giros WHERE dominio=? AND eliminado=?")){
		
		$sqlgiro->bind_param("si", $host, $eliminado);
		$sqlgiro->execute();		
		$id_gir = $sqlgiro->get_result()->fetch_all(MYSQLI_ASSOC)[0]["id_gir"];

		$sql = $con->prepare("SELECT * FROM catalogo_productos WHERE id_gir=? AND eliminado=?");
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

		$info['data']['paginas'] = get_paginas_web($id_gir, $con);
		$info['data']['config'] = get_config($id_gir, $con);
		$info['data']['locales'] = get_locales_js($id_gir, $con);
		$info['info'] = get_data($id_gir, $con);
		$info['polygons'] = get_polygons($id_gir, $con);
		return json_encode($info);

	}else{
		
		$error = $this->con->errno.' '.$this->con->error;
		echo $error;

	}


}
function get_polygons($id_gir, $con){

	$eliminado = 0;
	$sql = $con->prepare("SELECT t3.nombre, t3.poligono, t3.precio, t3.id_loc FROM giros t1, locales t2, locales_tramos t3 WHERE t1.id_gir=? AND t1.id_gir=t2.id_gir AND t2.id_loc=t3.id_loc AND t2.eliminado=? AND t3.eliminado=?");
	$sql->bind_param("iii", $id_gir, $eliminado, $eliminado);
	$sql->execute();
	$result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
	$sql->free_result();
	$sql->close();
	return $result;
			    
}
function get_info_catalogo($id_cat, $con){
	        
	$aux_prods = [];
	$eliminado = 0;

	$sql = $con->prepare("SELECT * FROM categorias WHERE id_cat=? AND eliminado=? ORDER BY orders");
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
		$aux_categoria['tipo'] = $row['tipo'];

		if($row['tipo'] == 0){

			$sqlpro = $con->prepare("SELECT * FROM cat_pros t1, productos t2, productos_precio t3 WHERE t1.id_cae=? AND t1.id_pro=t2.id_pro AND t1.id_pro=t3.id_pro AND t3.id_cat=? ORDER BY t1.orders");
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

					$sqlppr = $con->prepare("SELECT * FROM preguntas_productos WHERE id_pro=?");
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

			$sqlpc = $con->prepare("SELECT id_cae2 as id_cae, cantidad FROM promocion_categoria WHERE id_cae1=?");
			$sqlpc->bind_param("ii", $row['id_cae']);
			$sqlpc->execute();
			$resultpc = $sqlpc->get_result();

			while($rowpc = $resultpc->fetch_assoc()){
				$aux_prm_cat['id_cae'] = $rowpc['id_cae'];
				$aux_prm_cat['cantidad'] = $rowpc['cantidad'];
				$aux_categoria['categorias'][] = $aux_prm_cat;
				unset($aux_prm_cat);
			}
			$sqlpp = $con->prepare("SELECT id_pro, cantidad FROM promocion_productos WHERE id_cae=?");
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
					        
	//$info['preguntas'] = get_info_preguntas($id_cat, $con);        
	return $info;
	      
}

function get_info_preguntas($id_cat, $con){
	            
	$eliminado = 0;
	$sql = $con->prepare("SELECT * FROM preguntas WHERE id_cat=? AND eliminado=?");
	$sql->bind_param("ii", $id_cat, $eliminado);
	$sql->execute();
	$result = $sql->get_result();

	while($row = $result->fetch_assoc()){
						                
		$aux_pre['id_pre'] = $row['id_pre'];
		$aux_pre['nombre'] = $row['mostrar'];
								            
		$sqlpre = $con->prepare("SELECT * FROM preguntas_valores WHERE id_pre=?");
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

function get_locales_js($id_gir, $con){

	$eliminado = 0;
	$sql = $con->prepare("SELECT id_loc, nombre, direccion, lat, lng FROM locales WHERE id_gir=? AND eliminado=?");
	$sql->bind_param("ii", $id_gir, $eliminado);
	$sql->execute();
	$result = $sql->get_result();

	while($row = $result->fetch_assoc()){

		$locales['id_loc'] = $row['id_loc'];
		$locales['nombre'] = $row['nombre'];
		$locales['direccion'] = $row['direccion'];
		$locales['lat'] = $row['lat'];
		$locales['lng'] = $row['lng'];

		$sqlloc = $con->prepare("SELECT dia_ini, dia_fin, hora_ini, hora_fin, min_ini, min_fin, tipo FROM horarios WHERE id_loc=? AND id_gir=? AND eliminado=?");
		$sqlloc->bind_param("iii", $row["id_loc"], $id_gir, $eliminado);
		$sqlloc->execute();
		$locales['horarios'] = $sqlloc->get_result()->fetch_all(MYSQLI_ASSOC);

		$loc[] = $locales;
		unset($locales);

	}
	return $loc;
					        
}

function get_paginas_web($id_gir, $con){

	$eliminado=0;
	$sqlpag = $con->prepare("SELECT id_pag, nombre, imagen, html FROM paginas WHERE id_gir=? AND eliminado=?");
	$sqlpag->bind_param("ii", $id_gir, $eliminado);
	$sqlpag->execute();
	$resultpag = $sqlpag->get_result()->fetch_all(MYSQLI_ASSOC);
	$sqlpag->free_result();
	$sqlpag->close();
	return $resultpag;

}

function get_config($id_gir, $con){
	        
	 $eliminado = 0;
	 $sqlgiro = $con->prepare("SELECT retiro_local, despacho_domicilio, desde, pedido_minimo, alto FROM giros WHERE id_gir=? AND eliminado=?");
	 $sqlgiro->bind_param("si", $id_gir, $eliminado);
	 $sqlgiro->execute();
	 return $sqlgiro->get_result()->fetch_all(MYSQLI_ASSOC)[0];

}

function get_data($id, $con){

	$eliminado = 0;
	$sql = $con->prepare("SELECT * FROM giros WHERE id_gir=? AND eliminado=?");
	$sql->bind_param("ii", $id, $eliminado);
	$sql->execute();
	$res = $sql->get_result();

	$info['favicon'] = "misitiodelivery.ico";
	if($res->{"num_rows"} == 1){

		$result = $res->fetch_all(MYSQLI_ASSOC)[0];

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