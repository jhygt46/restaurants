<?php
header('Content-type: text/json');
header('Content-type: application/json');

require_once "/var/www/html/config/config.php";
$con = new mysqli($db_host[0], $db_user[0], $db_password[0], $db_database[0]);

echo json_encode(enviar_pedido($con));


function enviar_pedido($con){

	$pedido = $_POST['pedido'];
	$puser = $_POST['puser'];
	$carro = $_POST['carro'];
	$promos = (isset($_POST['promos']))? $_POST['promos'] : [] ;

	$dir = false;

	$direccion = $pedido['direccion'];
	$calle = $pedido['calle'];
	$num = $pedido['num'];
	$depto = $pedido['depto'];
	$comuna = $pedido['comuna'];
	$lat = $pedido['lat'];
	$lng = $pedido['lng'];			        
	$despacho = $pedido['despacho'];

	$sql = $con->prepare("SELECT * FROM pedidos_usuarios WHERE id_puser=? AND codigo=? AND telefono=?");
	$sql->bind_param("isi", $puser["id_puser"], $puser["code"], $pedido["telefono"]);
	$sql->execute();
	$res = $sql->get_result();

	if($res->{'num_rows'} == 0){

		$puser_code = bin2hex(openssl_random_pseudo_bytes(10));
		$cont = 1;
		$sqlipu = $con->prepare("INSERT INTO pedidos_usuarios (codigo, nombre, telefono, cont) VALUES (?, ?, ?, ?)");
		$sqlipu->bind_param("sssi", $puser_code, $pedido["nombre"], $pedido["telefono"], $cont);
		$sqlipu->execute();
		$id_puser = $con->insert_id;
		$sqlipu->close();

		$info['set_puser'] = 1;
		$info['puser']['id_puser'] = $id_puser;
		$info['puser']['code'] = $puser_code;
		$info['puser']['nombre'] = $pedido["nombre"];
		$info['puser']['telefono'] = $pedido["telefono"];

	}
	
	if($res->{'num_rows'} == 1){
	
		$id_puser = intval($puser["id_puser"]);
		$cont = $res->fetch_all(MYSQLI_ASSOC)[0]["cont"] + 1;
		$sqlupu = $con->prepare("UPDATE pedidos_usuarios SET cont=? WHERE id_puser=?");
		$sqlupu->bind_param("ii", $cont, $id_puser);
		$sqlupu->execute();
		$sqlupu->close();
			
		$sqlpd = $con->prepare("SELECT * FROM pedidos_direccion WHERE id_puser=?");
		$sqlpd->bind_param("i", $id_puser);
		$sqlpd->execute();
		$list_pdir = $sqlpd->get_result()->fetch_all(MYSQLI_ASSOC);
		$sqlpd->free_result();
		$sqlpd->close();

		for($i=0; $i<count($list_pdir); $i++){
			if($list_pdir[$i]['lat'] == $pedido['lat'] && $list_pdir[$i]['lng'] == $pedido['lng']){
				$pdir_id = $list_pdir[$i]['id_pdir'];
				$dir = true;
			}
		}
		
	}	

	
	$sql->free_result();
	$sql->close();

	if(!$dir && $despacho == 1){
			            
		$sqlpdi = $con->prepare("INSERT INTO pedidos_direccion (direccion, calle, num, depto, comuna, lat, lng, id_puser) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		$sqlpdi->bind_param("sssssddi", $pedido["direccion"], $pedido["calle"], $pedido["num"], $pedido["depto"], $pedido["comuna"], $pedido["lat"], $pedido["lng"], $puser_id);
		$sqlpdi->execute();
		$pdir_id = $con->insert_id;
		$sqlpdi->close();
							                
	}

	
	$eliminado = 0;
	$sqllg = $con->prepare("SELECT t1.t_retiro, t1.t_despacho, t1.code, t1.correo, t2.dominio, t1.activar_envio, t1.lat, t1.lng, t1.id_gir, t2.num_ped FROM locales t1, giros t2 WHERE t1.id_loc=? AND t1.id_gir=t2.id_gir AND t1.eliminado=? AND t2.eliminado=?");
	$sqllg->bind_param("iii", $pedido["id_loc"], $eliminado, $eliminado);
	$sqllg->execute();
	$resultlg = $sqllg->get_result()->fetch_all(MYSQLI_ASSOC)[0];
	$sqllg->free_result();
	$sqllg->close();


	$info['lat'] = $resultlg['lat'];
	$info['lng'] = $resultlg['lng'];
	$info['t_retiro'] = $resultlg['t_retiro'];
	$info['t_despacho'] = $resultlg['t_despacho'];
	$num_ped = $resultlg['num_ped'] + 1;
	$id_gir = $resultlg['id_gir'];

	/*
	if($despacho == 1){
		$aux_verify = $this->get_info_despacho($lat, $lng);
		if($aux_verify['op'] == 1 && $aux_verify['id_loc'] == $id_loc && $aux_verify['precio'] == $costo){
			$verify_despacho = 1;
		}
	}
	*/

	$verify_despacho = 1;
	$tz_object = new DateTimeZone('America/Santiago');
	$datetime = new DateTime();
	$datetime->setTimezone($tz_object);
	$fecha_stgo = $datetime->format('Y-m-d H:i:s');
	
	$pedido_code = bin2hex(openssl_random_pseudo_bytes(10));
	$tipo = 1;
	$sqlipa = $con->prepare("INSERT INTO pedidos_aux (num_ped, code, fecha, despacho, tipo, id_loc, carro, promos, verify_despacho, pre_gengibre, pre_wasabi, pre_embarazadas, pre_palitos, pre_teriyaki, pre_soya, comentarios, costo, total, id_puser, id_pdir, id_gir) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	$sqlipa->bind_param("issiiissiiiiiiisiiiii", $num_ped, $pedido_code, $fecha_stgo, $despacho, $tipo, $pedido["id_loc"], $_POST['carro'], $promos, $verify_despacho, $pedido["pre_gengibre"], $pedido["pre_wasabi"], $pedido["pre_embarazadas"], $pedido["pre_palitos"], $pedido["pre_teriyaki"], $pedido["pre_soya"], $pedido["comentarios"], $pedido["costo"], $pedido["total"], $id_puser, $pdir_id, $id_gir);
	
	if($sqlipa->execute()){

		$id_ped = $con->insert_id;

		$sqlugi = $con->prepare("UPDATE giros SET num_ped=? WHERE id_gir=? AND eliminado=?");
		$sqlugi->bind_param("iii", $num_ped, $id_gir, $eliminado);
		$sqlugi->execute();
		$sqlugi->close();

		$info['op'] = 1;
		$info['id_ped'] = $id_ped;
		$info['num_ped'] = $num_ped;
		$info['pedido_code'] = $pedido_code;
		$info['fecha'] = time();

		$pedido['local_code'] = $resultlg['code'];
		$pedido['id_ped'] = $id_ped;
		$pedido['num_ped'] = $num_ped;
		$pedido['pedido_code'] = $pedido_code;
		
		$pedido['correo'] = $resultlg['correo'];
		$pedido['accion'] = 'enviar_pedido_local';
		$pedido['activar_envio'] = $resultlg['activar_envio'];
		$pedido['hash'] = 'Lrk}..75sq[e)@/22jS?ZGJ<6hyjB~d4gp2>^qHm';
		$pedido['dominio'] = $resultlg['dominio'];
		$pedido['nombre'] = $nombre;
		$pedido['telefono'] = $telefono;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/enviar_local');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pedido));
		curl_exec($ch);
		curl_close($ch);

	}else{

		$info['op'] = 2;
		$info['mensaje'] = 'El pedido no pudo ser enviado';

	}

	$sqlipa->close();
	return $info;

}