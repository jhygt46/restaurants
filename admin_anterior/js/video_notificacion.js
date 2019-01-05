var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var video_code = "";
var video_ttl = "";
var video_duration = 0;
var video_obj = false;
var width_noti = 400;

var player;
function onYouTubeIframeAPIReady(){
    
    player = new YT.Player('player', {
        height: '360',
        width: '640',
        videoId: '',
        events: {
            onReady: onPlayerReady,
            onStateChange: onPlayerStateChange
        }
    });
    
}

function video_time(){
    
    var time = parseInt(player.getCurrentTime());
    if(isNaN(time)){
        time = 0;
    }
    instrucciones(time);
    if(time < video_duration - 10){
        setTimeout(video_time, 1000);
    }
    
}
function onPlayerReady(event){
    
    video_duration = event.target.getDuration();
    video_obj = true;
    
}
function onPlayerStateChange(event){
    if(event.data == YT.PlayerState.PLAYING){
    }
    if(event.data == YT.PlayerState.ENDED){
    }
    if(event.data == YT.PlayerState.PAUSED){
    }
    if(event.data == YT.PlayerState.UNSTARTED){
    }
    if(event.data == YT.PlayerState.BUFFERING){
    }
    if(event.data == YT.PlayerState.CUED){
    }
}

function first_video(v_code, v_ttl, v_callback){

    if(video_obj){
        play_youtube(v_code, v_ttl, v_callback);
    }else{
        setTimeout(function(){
            first_video(v_code, v_ttl, v_callback);
        }, 250);
    }
}

function play_youtube(v_code, v_ttl, v_callback){
    
    $('.video_titulo').html(v_ttl);
    
    if(video_code != v_code){
        $('.noti_video').show();
        $('.noti_video').attr('code', v_code);
        player.loadVideoById(v_code);
        player.playVideo();
        if(v_callback != null){
            v_callback();
        }
    }
    
    video_code = v_code;
    video_ttl = v_ttl;
    
}

function instrucciones(time){
    
    if(video_code == "gI3Zn9-tuq8"){
        
        if(time == 3){
            movie_start(0);
        }
        if(time == 5){
            cursor(false);
            cursor_move(100, 100, 1000, 0);
            cursor_move(300, 500, 1000, 2000);
            cursor_move(50, 800, 1000, 4000);
        }
        if(time == 12){
            cursor(true);
        }
        if(time == 15){
            movie_stop(0);
        }
        
    }
    
}
function movie_start(t){
    setTimeout(function(){
        $('.movie').show();
    }, t);
}
function movie_stop(t){
    setTimeout(function(){
        $('.movie').hide();
    }, t);
}
function cursor_move(top, left, time_move, time_exec){
    setTimeout(function(){
        $('.movie .cursor').animate({ top: top+'px', left: left+'px' }, time_move);
    }, time_exec);
}
function cursor(bool){
    
    if(bool){
        $('.movie .cursor').animate({ top: mouse_y+'px', left: mouse_x+'px' }, 1000, function(){
            $('.contenido').removeClass('nocursor');
            $('.movie .cursor').hide();
        }); 
        
    }else{
        $('.contenido').addClass('nocursor');
        $('.movie .cursor').css({ left: mouse_x+'px', top: mouse_y+'px' });
        $('.movie .cursor').show();
    }
}

function toogle_llamado(that){
    
    var rel = $(that).parents('.noti_llamado').attr('rel');
    $(that).parents('.noti_align').find('.noti').each(function(){
        
        if($(this).hasClass('noti_video')){
            $(this).find('.video_content').slideUp();
            player.pauseVideo();
            $(this).find('.video_cerrar').addClass('rotate');
        }
        if($(this).hasClass('noti_llamado') && $(this).attr('rel') == rel){
            
            var lla_content = $(this).find('.llamado_content');
            if(lla_content.is(':visible')){
                lla_content.slideUp();
                $(this).find('.llamado_cerrar').addClass('rotate');
            }else{
                lla_content.slideDown();
                $(this).find('.llamado_cerrar').removeClass('rotate');
            }
            
        }
        if($(this).hasClass('noti_llamado') && $(this).attr('rel') != rel){
            $(this).find('.llamado_content').slideUp();
            $(this).find('.llamado_cerrar').addClass('rotate');
        }
    });
    
}
function toogle_video(that){
    
    $(that).parents('.noti_align').find('.noti_llamado').each(function(){
        $(this).find('.llamado_content').slideUp();
        $(this).find('.llamado_cerrar').addClass('rotate');
    });
    
    var content = $(that).parents('.noti_video').find('.video_content');
    if(content.is(':visible')){
        content.slideUp();
        player.pauseVideo();
        $(that).addClass('rotate');
    }else{
        content.slideDown();
        player.playVideo();
        $(that).removeClass('rotate');
    }
    
}

function size_llamado(that){
    
    var content = $(that).parents('.noti_llamado');
    var contents = content.find('.llamado_content');
    var width = contents.css('width');

    var dimensiones = new Array(400, 640, 853);
    var ilen = dimensiones.length;
    
    for(var i=0; i<ilen; i++){
        if(width == dimensiones[i]+'px'){
            var j = i + 1;
        }
    }
    if(j >= ilen){
        j = 0;
    }

    var w = dimensiones[j];
    var h = get_size(w);
    
    if( w >= width_noti){
        $('.cont_notificaciones').css({ width: w+'px'});
        width_noti = w;
    }
    
    content.css({ width: w+'px'});
    contents.css({ width: w+'px'});
    contents.css({ height: h+'px'});
    
}
function size_video(that){
    
    var content = $(that).parents('.noti_video');
    var contents = content.find('.video_content');
    var width = contents.css('width');

    var dimensiones = new Array(400, 640, 853);
    var ilen = dimensiones.length;
    
    for(var i=0; i<ilen; i++){
        if(width == dimensiones[i]+'px'){
            var j = i + 1;
        }
    }
    if(j >= ilen){
        j = 0;
    }

    var w = dimensiones[j];
    var h = get_size(w);
    
    if( w >= width_noti){
        $('.cont_notificaciones').css({ width: w+'px'});
        width_noti = w;
    }
    
    content.css({ width: w+'px'});
    contents.css({ width: w+'px'});
    contents.css({ height: h+'px'});
    
}

function get_size(width){
    
    return parseInt(width * 9 / 16);
    
}

var maps = new Array();
var maps_id = new Array();

function notificacion(id){
    
    $('.cont_notificaciones').find('.noti').each(function(){
        
        if($(this).hasClass('noti_video')){
            $(this).find('.video_content').slideUp();
            player.pauseVideo();
            $(this).find('.video_cerrar').addClass('rotate');
        }
        if($(this).hasClass('noti_llamado')){
            $(this).find('.llamado_content').slideUp();
            $(this).find('.llamado_cerrar').addClass('rotate');
        }
        
    });
    
    var t = '<div class="noti noti_llamado" rel="'+id+'"><div class="llamado_header"><div class="llamado_titulo" onclick="go_llamado('+id+')">10-0-1 Avda Fracisco Bilbao #1457</div><div class="llamado_cerrar llamado_btn" onclick="toogle_llamado(this)"></div><div class="llamado_size llamado_btn" onclick="size_llamado(this)"></div></div><div class="noti_content llamado_content" id="map'+id+'"></div></div>';
    $('.noti_align').prepend(t);
    maps.push(initMap('map'+id, -33.439797, -70.616939, 16));
    maps_id.push(id);
    
}
function initMap(variable, lat, lng, zoom = 8){
    
    return new google.maps.Map(document.getElementById(variable), { center: { lat: lat, lng: lng }, zoom: zoom, disableDefaultUI: true } );

}