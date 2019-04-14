$(document).ready(function(){
    socket_init();
    console.log(data);
});

function socket_init(){
    
    var socket = io.connect('https://www.izusushi.cl', { 'secure': true });
    socket.on('cocina-'+local_code, function(id_ped) {
        console.log("agregar pedido: "+id_ped);
        agregar_pedido(id_ped);
    });
    socket.on('cocina-pos-'+local_code, function(info) {
        console.log("info");
        console.log(info);
    });
    socket.on('connect', function() {
        $('.alert_socket').hide();
    });
    socket.on('disconnect', function() {
        $('.alert_socket').show();
    });
    
}
function agregar_pedido(id){

    var send = { id_ped: id };
    $.ajax({
        url: "ajax/get_pedido.php",
        type: "POST",
        data: send,
        success: function(data){
            
            var info = JSON.parse(data);
            console.log(info);
            
        }, error: function(e){
            console.log(e);
        }
    });
    
}