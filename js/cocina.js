$(document).ready(function(){
    socket_init();
});

function socket_init(){
    
    var socket = io.connect('https://www.izusushi.cl', { 'secure': true });
    socket.on('cocina-'+local_code, function(id) {
        agregar_pedido(id);
    });
    socket.on('cocina-pos-'+local_code, function(info) {
        modificar_pedido(info);
    });
    socket.on('connect', function() {
        $('.alert_socket').hide();
    });
    socket.on('disconnect', function() {
        $('.alert_socket').show();
    });
    
}
function modificar_pedido(info){

    console.log("MOD PEDIDO POS");
    console.log(info);

}
function agregar_pedido(id){

    console.log("ADD PEDIDO: "+id);

    /*
    var send = { id_ped: id };
    $.ajax({
        url: "ajax/get_pedido.php",
        type: "POST",
        data: send,
        success: function(data){
            
            var info = JSON.parse(data);
            
        }, error: function(e){
            console.log(e);
        }
    });
    */

}