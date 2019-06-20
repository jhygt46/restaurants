<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require_once("/var/www/html/restaurants/admin/class/restaurant_class.php");
$rest = new Rest();
echo json_encode($rest->get_info());

?>


