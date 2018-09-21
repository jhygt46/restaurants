var webSocket = "";
var map = [];
$(document).ready(function(){

    webSocket = io.connect("http://localhost:3000");
    webSocket.on('llamados-cbs', function(res){
        console.log(res);
        //renderllamado(res, 0);
    });
   
});
function render_notificacion(){
    
    var rand = getRndInteger(0, 99999);
    var sc = $('.sock_cont');
    var html = '<li id="'+rand+'" style="display:none"><h1>Buenas</h1><a class="close" onclick="close_notification(this)"></a><h2>Cuerpo</h2></li>';
    sc.append(html);
    sc.find('#'+rand).slideDown();
    setTimeout(function(){
        sc.find('#'+rand).slideUp();
    }, 7000);
    
}
function getRndInteger(min, max) {
    return Math.floor(Math.random() * (max - min) ) + min;
}
function renderllamado(llamados, first){
    
    var contllamados = $('.llamados .contllamados');
    var botones = $('.llamados .newllamado');
    var infollamado = contllamados.find('.contllamado');
    var cant = infollamado.length - 1;
    var div = "";
    botones.html("");
    
    
    for(var i=1; i<=llamados.length; i++){
        
        var j = i - 1;
        botones.append('<li class="cla" rel="'+llamados[j].info.id+'" onclick="view(this)">'+llamados[j].info.clave+'</li>');
        
        if(infollamado.eq(i).length == 0){
            div = contllamados.find('.contllamado').first().clone();
            div.appendTo('.contllamados');
        }
        if(infollamado.eq(i).length > 0){
            div = infollamado.eq(i);
        }
        ponerinfo(div, llamados[j]);
        
    }
    botones.append('<li class="new" onclick="nuevaventana(this)">Nuevo +</li>');

    if(first == 1){
        $('.newllamado .cla').eq(0).addClass('active');
        $('.contllamados .contllamado').eq(1).addClass('active');
    }

}

function ponerinfo(div, llamado){
    
    var id = llamado.info.id;
    var mapa = div.find('.mapallamado');
    
    div.find('.claveid').val(id);
    
    var pos = {
        center: {lat: parseFloat(llamado.info.lat), lng: parseFloat(llamado.info.lng)},
        zoom: 14
    };
    
    map[id] = new google.maps.Map(mapa[0], pos);
    
};

function view(that){
    
    $('.newllamado').find('.active').eq(0).removeClass('active');
    $(that).addClass('active');
    var id = $(that).attr('rel');
    $('.contllamados .contllamado').each(function(){
        
        var claveid = $(this).find('.claveid').val();
        if(id == claveid){
            $('.contllamados').find('.active').removeClass('active');
            $(this).addClass('active');
        }
        
    });
    
}
function nuevaventana(that){
    
    var rand = "web-"+Math.floor((Math.random() * 1000) + 1);
    $('.newllamado').append('<li class="new" onclick="nuevaventana(this)">Nuevo +</li>');
    delactivebotones();
    $(that).addClass('active');
    $(that).addClass('cla');
    $(that).removeClass('new');
    $(that).html('');
    $(that).attr('rel', rand);
    $(that).attr('onclick', 'view(this)');
    
    var contllamados = $('.contllamados');
    var div = contllamados.find('.contllamado').first().clone();
    div.find('.claveid').val(rand);
    div.appendTo('.contllamados');
    
}

function delactivebotones(){
    $('.newllamado').find('.active').removeClass('active');
}
function delactivellamados(){
    $('.contllamados').find('.active').removeClass('active');
}

function addclave(that){
    
    var clave = $(that).attr('rel');
    var parent = $(that).parents('.contllamado');
    parent.find('.clave').val(clave);
    
}
function isset(object){
    return (typeof object !=='undefined');
}
function maps(llamados){
    
}