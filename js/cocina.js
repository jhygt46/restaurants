$(document).ready(function(){
    socket_init();
});

function socket_init(){
    
    var socket = io.connect('https://www.izusushi.cl', { 'secure': true });
    socket.on('cocina-'+local_code, function(id_ped) {
        console.log(id_ped);
    });
    socket.on('cocina-pos-'+local_code, function(data) {
        console.log(data);
    });
    socket.on('connect', function() {
        $('.alert_socket').hide();
    });
    socket.on('disconnect', function() {
        $('.alert_socket').show();
    });
    
}