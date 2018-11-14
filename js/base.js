$(document).ready(function(){
    crear_pagina();
    var carro = JSON.parse(localStorage.getItem("carro")) || [];
    $('.cantcart_num').html(carro.length);
});
var menu = 0;
var modal = 0;
var paso = 1;
var history = [];
var dir = 0;
var catalogo = 0;
var debug = 0;
var estados

// INICIO BACK BUTTON //
history.replaceState(null, document.title, location.pathname);
history.pushState(null, document.title, location.pathname);
window.addEventListener("popstate", function() {

        if(!backurl()){
            history.back();
        }

    history.replaceState(null, document.title, location.pathname);
    history.pushState(null, document.title, location.pathname);
    
}, false);



function borrar_back(){
    localStorage.setItem("back", null);
}
function backurl(){
    
    var history = JSON.parse(window.localStorage.getItem("back")) || [];
    var len = history.length;

    if(len == 0){
        return false;
    }
    if(len == 1){
        history = null;
        hide_modal();
        hidemenu();
    }
    if(len > 1){
        history.pop();
        volver(history[len - 2]);
    }
    localStorage.setItem("back", JSON.stringify(history));
    return true;
}
function add_history(name, id){
    var history = JSON.parse(window.localStorage.getItem("back")) || [{func_name: 'hide_modal'}];
    history.push({func_name: name, id: id});
    localStorage.setItem("back", JSON.stringify(history));
}
function volver(accion){
    
    if(accion.func_name == "open_categoria"){
        window[accion.func_name](accion.id);
    }
    if(accion.func_name == "hide_modal"){
        hide_modal();
    }
    if(accion.func_name == "open_carro"){
        open_carro();
    }
    if(accion.func_name == "open_promocion"){
        window[accion.func_name](accion.id);
    }
    
}
function ver_history(){
    console.log(JSON.parse(window.localStorage.getItem("back")) || []);
}
function set_debug(n){ debug=n; }

// FIN BACK BUTTON //

// CLICK OUT //
$(document).click(function (e) {
    if($(e.target).hasClass('cont_modals')){
        hide_modal();
        add_history('hide_modal', 0);
    }
    if($(e.target).hasClass('close')){
        hide_modal();
        add_history('hide_modal', 0);
    }
});
function close_pedido(){
    $('.modal_carro').find('h1').html("Haz tu Pedido");
    $('.modal_carro').find('h2').html("Verifica que esten todos tu productos");
    $('.modal_carro').find('.carro_inicio').html("");
    $('.modal_carro').find('.carro_inicio').show();
    $('.modal_carro').find('.carro_direccion').hide();
    $('.modal_carro').find('.carro_final').hide();
    $('.modal_carro').find('.carro_seguimiento').hide();
    show_acciones();
    return_direccion();
}
// SHOW HIDE MODAL //
function hide_modal(){
    modal = 0;
    $('.modals').hide();
    $('.modals .cont_modals').find('.modal').each(function(){
        $(this).hide();
        if($(this).hasClass('modal_carta') || $(this).hasClass('modal_productos_promo') || $(this).hasClass('modal_pregunta_productos') || $(this).hasClass('modal_pagina')){
            $(this).find('h1').html("");
            $(this).find('h2').html("");
            $(this).find('.info_modal').html("");
        }
        if($(this).hasClass('modal_carro')){
            close_pedido();
        }
        if($(this).hasClass('modal_productos_promo')){
            
        }
        if($(this).hasClass('modal_pregunta_productos')){
            
        }
    });
}
function show_modal(clase){
    $('.modals, .'+clase).show();
    modal = 1;
}
// GET CARRO //
function get_pedido(){
    return JSON.parse(localStorage.getItem("pedido")) || { id_ped: 0, despacho: null };
}
function set_pedido(pedido){
    localStorage.setItem("pedido", JSON.stringify(pedido));
}
function get_carro(){
    return JSON.parse(localStorage.getItem("carro")) || [];
}
function get_promos(){
    return JSON.parse(localStorage.getItem("carro_promos")) || [];
}
function get_carro_limpio(){
    var carro = JSON.parse(localStorage.getItem("carro")) || [];
    for(var i=0, ilen=carro.length; i<ilen; i++){
        delete carro[i].promo;
    }
    return carro;
}
function ver_carro(){
    var carro = {};
    carro.carro = JSON.parse(localStorage.getItem("carro")) || [];
    carro.promos = JSON.parse(localStorage.getItem("carro_promos")) || [];
}
// OPEN CARRO //
function open_carro(){
    
    var pedido = get_pedido();
    if(pedido.id_ped == 0){
        show_modal('paso_01');
        add_history('open_carro', 0);
        process_carro();
    }
    if(pedido.id_ped > 0){
        show_modal('paso_04');
        add_history('open_carro', 0);
        open_socket(pedido);
    }
    
}

// INICIO MENU IZQUIERDA //
function showmenu(){
    $('.menu_left').animate({
        left: "0px"
    }, 200, function(){
        menu = 1;
    });
}
function hidemenu(){
    $('.menu_left').animate({
        left: "-220px"
    }, 200, function(){
        menu = 0;
    });
}
function tooglemenu(){
    if(menu == 0)
        showmenu();
    if(menu == 1)
        hidemenu();
}
function ver_pagina(id){
    
    for(var i=0, ilen=data.paginas.length; i<ilen; i++){
        if(data.paginas[i].id_pag == id){
            $('.modal_pagina .titulo h1').html(data.paginas[i].titulo);
            $('.modal_pagina .titulo h2').html(data.paginas[i].subtitulo);
            $('.modal_pagina .info_modal').html(data.paginas[i].html);
            show_modal('modal_pagina');
        }
    }
    
    
}
// INICIO CREAR PAGINA //
function crear_pagina(){
    
    var categorias = data.catalogos[catalogo].categorias;
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].parent_id == 0 && categorias[i].ocultar == 0){
            $('.cont_contenido').append(html_home_categorias(categorias[i]));  
        }
    }
    var paginas = data.paginas;
    for(var i=0, ilen=paginas.length; i<ilen; i++){
        $('.lista_paginas').append(html_paginas(paginas[i])); 
    }
    

}

// INICIO ABRIR CATEGORIA //
function open_categoria(id){
    
    if(debug == 1){ console.log("open_categoria-id:"+id) }
    
    show_modal('modal_carta');
    add_history('open_categoria', id);
    var categorias = data.catalogos[catalogo].categorias;
    var cats = [];

    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].id_cae == id){
            $('.modal_carta .titulo h1').html(categorias[i].nombre);
            $('.modal_carta .titulo h2').html(categorias[i].descripcion_sub);
            for(var j=0, jlen=categorias.length; j<jlen; j++){
                if(categorias[i].id_cae == categorias[j].parent_id){
                    cats.push(categorias[j]);
                }
            }
            
            if(cats.length == 0){ imprimir_productos_modal(id) }
            if(cats.length > 0){ imprimir_categoria_modal(cats) }
        }
    }
    
}
function imprimir_productos_modal(id){
    
    if(debug == 1){ console.log("imprimir_productos_modal-id:"+id) }
    
    var categoria = get_categoria(id);
    $('.modal_carta .info_modal').html('');

    if(categoria.productos && categoria.tipo == 0){
        var html = create_element_class('lista_productos');
        var productos = categoria.productos;
        for(var j=0, jlen=productos.length; j<jlen; j++){
            html.append(create_html_producto(productos[j], categoria.detalle_prods));
        }
    }
    if(categoria.tipo == 1){
        var html = imprimir_promo_modal(categoria);
    }

    $('.modal_carta .info_modal').append(html);
    
}
function imprimir_categoria_modal(categorias){
    
    if(debug == 1){ console.log("imprimir_categoria_modal") }
    $('.modal_carta .info_modal').html('');
    
    var html = create_element_class('lista_categorias');
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].tipo == 0){
            html.appendChild(create_html_categorias(categorias[i]));
        }
        if(categorias[i].tipo == 1){
            html.appendChild(create_html_promocion(categorias[i]));
        }
    }
    
    $('.modal_carta .info_modal').append(html);
    
}


// GET CATEGORIAS - PRODUCTOS - PROMOS - PREGUNTAS //
function get_producto(id_pro){
    var productos = data.catalogos[catalogo].productos;
    for(var i=0, ilen=productos.length; i<ilen; i++){
        if(productos[i].id_pro == id_pro){
            return productos[i];
        }
    }
}
function get_categoria(id_cae){
    var categorias = data.catalogos[catalogo].categorias;
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].id_cae == id_cae){
            return categorias[i];
        }
    }
}
function get_promocion(id_cae){
    var promociones = data.catalogos[catalogo].categorias;
    for(var i=0, ilen=promociones.length; i<ilen; i++){
        if(promociones[i].id_cae == id_cae){
            return promociones[i];
        }
    }
}
function get_productos_categoria(id_cae){
    
    var categorias = get_categoria(id_cae);
    var productos = [];
    if(categorias.productos){
        for(var i=0, ilen=categorias.productos.length; i<ilen; i++){
            productos.push(parseInt(categorias.productos[i]));
        }
    }
    return productos;
    
}
function get_preguntas(id_pre){
    for(var i=0, ilen=data.catalogos[catalogo].preguntas.length; i<ilen; i++){
        if(id_pre == data.catalogos[catalogo].preguntas[i].id_pre){
            return data.catalogos[catalogo].preguntas[i];
        }
    }
    return null;
}

function get_cats(tipo){
    var categorias = data.catalogos[catalogo].categorias;
    var aux = [];
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].tipo == tipo){
            aux.push(categorias[i]);
            
        }
    }
    return aux;
}
function get_categorias(){
    return get_cats(0);
}
function get_promociones(){
    return get_cats(1);
}

// ADD PRODUCTOS Y PROMOCION //
function add_carro_producto(id_pro){
    
    var carro = JSON.parse(localStorage.getItem("carro")) || [];
    carro.push({id_pro: parseInt(id_pro)});
    localStorage.setItem("carro", JSON.stringify(carro));
    if(!carro_daemon()){
        hide_modal();
    }
    
}
function add_carro_promocion(id_cae){
    
    var carro = JSON.parse(localStorage.getItem("carro")) || [];
    var promo = get_categoria(id_cae);
    if(promo.categorias){
        for(var i=0, ilen=promo.categorias.length; i<ilen; i++){
            carro.push({id_cae: parseInt(promo.categorias[i].id_cae), cantidad: parseInt(promo.categorias[i].cantidad)});
        }
    }
    if(promo.productos){
        for(var i=0, ilen=promo.productos.length; i<ilen; i++){
            for(var j=0, jlen=promo.productos[i].cantidad; j<jlen; j++){
                carro.push({id_pro: parseInt(promo.productos[i].id_pro)});
            }
        }
    }
    localStorage.setItem("carro", JSON.stringify(carro));
    if(!carro_daemon()){
        hide_modal();
    }
    
}

// BORRAR CARRO//
function borrar_carro(){
    localStorage.setItem("carro", null);
    localStorage.setItem("carro_promos", null);
}
function delete_pro_carro(i){
    console.log(i);
    var carro = get_carro();
    carro.splice(i, 1);
    localStorage.setItem("carro", JSON.stringify(carro));
    process_carro();

}
function delete_pre_pro_carro(i){
    var carro = get_carro();
    delete carro[i].preguntas;
    localStorage.setItem("carro", JSON.stringify(carro));
    process_carro();
}

// PROCESS CARRO //
function process_carro(){
    
    if(debug == 1){ console.log("PROCESS CARRO") }
    
    if(!carro_daemon()){
        
        $('.paso_01 .info_modal').html('');
        
        var total = 0;
        var info = process_new_promos();
        var carro = info.carro;
        var carro_promos = info.carro_promos;
        var count = 0;
        var promocion, producto, promo_detalle, process_carro_promo, promo_info;
        
        var html = create_element_class('process_carro');
        
        for(var i=0, ilen=carro_promos.length; i<ilen; i++){
            
            promocion = get_categoria(carro_promos[i].id_cae);
            total = total + parseInt(promocion.precio);
            
            process_carro_promo = create_element_class('process_carro_promo');
            
            promo_detalle = create_element_class('promo_detalle');
            promo_info = create_element_class_inner('promo_info', promocion.nombre);
            process_carro_promo.appendChild(promo_info);
            
            for(var j=0, jlen=carro.length; j<jlen; j++){
                if(carro[j].promo == i){
                    count++;
                    producto = get_producto(carro[j].id_pro);
                    promo_detalle.appendChild(promo_carros(producto, j));
                }
            }
            process_carro_promo.appendChild(promo_detalle);
            
            html.appendChild(process_carro_promo);

        }
        
        var process_carro_restantes = create_element_class('process_carro_restantes');
        for(var j=0, jlen=carro.length; j<jlen; j++){
            if(carro[j].promo === undefined){
                count++;
                producto = get_producto(carro[j].id_pro);
                process_carro_restantes.appendChild(promo_restantes(producto, j));
                total = total + parseInt(producto.precio);
            }
        }
        
        html.appendChild(process_carro_restantes);
        
        var pedido = get_pedido();
        pedido.total = total;
        set_pedido(pedido);
        
        var precio = create_element_class_inner('process_carro_precio_carta', 'total: $'+total);
        html.appendChild(precio);
        
        $('.cantcart_num').html(count);
        $('.paso_01 .info_modal').append(html);
    }
}
function carro_daemon(){
    
    var carro = get_carro();
    var item_carro = [];
    for(var i=0, ilen=carro.length; i<ilen; i++){
        item_carro = carro[i];
        if(!item_carro.id_pro){
            seleccionar_productos_categoria_promo(i);
            return true;
        }
        if(item_carro.id_pro){
            if(seleccionar_productos(i)){
                return true;
            }
        }
    }
    var pedido = get_pedido();
    if(pedido.id_ped > 0){
        
        //MOSTRAR SEGUIMIENTO
        $('.modal_carro .titulo h1').html("PEDIDO");
        $('.modal_carro .titulo h2').html("Tengo un pedido abierto");
        
        $('.carro_inicio').hide();
        $('.carro_direccion').hide();
        $('.carro_final').hide();
        $('.carro_seguimiento').show();
        hide_acciones();
        
        $('.modal_carro .carro_seguimiento .pedido .pedido_name').html("PEDIDO #"+pedido.id_ped+" - "+pedido.total);
        
        return true;
        
    }
    
    return false;

}
function hide_acciones(){
    $('.modal_carro .acciones').hide();
}
function show_acciones(){
    $('.modal_carro .acciones').show();
}
function process_new_promos(){
    
    var carro = get_carro_limpio();
    var promos = process_promo();
    
    var carro_promos = [];
    var ocupados = [];
    var pos = 0;
    var repeat = true;

    for(var i=0, ilen=promos.length; i<ilen; i++){
        repeat = true;
        while(repeat){
            
            ocupados = [];
            for(var j=0, jlen=promos[i].productos.length; j<jlen; j++){
                for(var k=0, klen=carro.length; k<klen; k++){
                    if(promos[i].productos[j].id_pro.indexOf(carro[k].id_pro) != -1 && ocupados.indexOf(k) == -1){
                        if(carro[k].promo === undefined){
                            ocupados.push(k);
                            break;
                        }
                    }
                }
            }
            
            if(ocupados.length == promos[i].productos.length){
                pos = carro_promos.length;
                carro_promos.push({ id_cae: promos[i].id_cae });
                for(var k=0, klen=carro.length; k<klen; k++){
                    if(ocupados.indexOf(k) != -1){
                        carro[k].promo = pos;
                    }
                }
                repeat = true;
            }else{
                repeat = false;        
            }
        }
    }
    
    var aux = {};
    aux.carro = carro;
    aux.carro_promos = carro_promos;
    set_carro(carro, carro_promos);
    return aux;
    
}
function set_carro(carro, carro_promos){
    localStorage.setItem("carro", JSON.stringify(carro));
    localStorage.setItem("carro_promos", JSON.stringify(carro_promos));
}
function process_promo(){
    
    var promos = get_promociones();
    
    var productos = [];
    var aux = [];
    var cantidad = 0;
    
    for(var i=0, ilen=promos.length; i<ilen; i++){
        productos = [];
        cantidad = 0;
        if(promos[i].categorias){
            for(var j=0; j<promos[i].categorias.length; j++){
                for(var k=0, klen=promos[i].categorias[j].cantidad; k<klen; k++){
                    productos.push({id_pro: get_productos_categoria(promos[i].categorias[j].id_cae)});
                    cantidad++;
                }
            }
        }
        if(promos[i].productos){
            for(var j=0; j<promos[i].productos.length; j++){
                for(var k=0, klen=promos[i].productos[j].cantidad; k<klen; k++){
                    productos.push({id_pro: [parseInt(promos[i].productos[j].id_pro)]});
                    cantidad++;
                }
            }
        }
        if(productos.length > 0){
            aux.push({id_cae: promos[i].id_cae, nombre: promos[i].nombre, productos: productos, cantidad: cantidad});
        }
    }
    aux.sort(function (a, b){
        return (b.cantidad - a.cantidad)
    })
    return aux;
    
}

// SELECCIONAR PRODUCTOS DE CATEGORIA EN PROMO //
function seleccionar_productos_categoria_promo(i){
    
    var carros = get_carro();
    var id_cae = carros[i].id_cae;
    var cantidad = carros[i].cantidad;
    var producto = {};
    var categoria = get_categoria(id_cae);
    
    $('.modals, .modal_productos_promo').show();
    $('.modal_productos_promo .info_modal').html('');
    $('.modal_productos_promo .titulo h1').html(categoria.nombre);
    $('.modal_productos_promo .titulo h2').html('Debe seleccionar '+cantidad+' productos');
    modal = 1;
    
    var html = html_seleccionar_productos_categoria_promo(categoria, i, cantidad);
    $('.modal_productos_promo .info_modal').append(html);
    
}

// SELECCIONAR PREGUNTA DE PRODUCTOS//
function seleccionar_productos(i){
    
    var carros = get_carro();
    var producto = get_producto(carros[i].id_pro);
    var aux = false;
    var valores = {};
    
    if(producto.preguntas){
        if(carros[i].preguntas){
            
            for(var k=0, klen=carros[i].preguntas.length; k<klen; k++){
                for(var j=0, jlen=carros[i].preguntas[k].valores.length; j<jlen; j++){
                    
                    valores = carros[i].preguntas[k].valores[j];

                    if(valores.seleccionados){
                        if(valores.seleccionados.length < valores.cantidad){
                            mostrar_pregunta(i);
                            return true;
                        }                            
                    }else{
                        mostrar_pregunta(i);
                        return true;
                    }
                    
                }
            }
            
        }else{
            
            carros[i].preguntas = [];
            for(var k=0, klen=producto.preguntas.length; k<klen; k++){
                carros[i].preguntas.push(get_preguntas(producto.preguntas[k]));
            }
            localStorage.setItem("carro", JSON.stringify(carros));
            mostrar_pregunta(i);
            return true;
            
        }
    }

    return aux;
    
}
function mostrar_pregunta(i){

    var carro = get_carro();
    var producto = get_producto(carro[i].id_pro);
    
    $('.modals, .modal_pregunta_productos').show();
    $('.modal_pregunta_productos .info_modal').html('');
    $('.modal_pregunta_productos .titulo h1').html(carro[i].preguntas[0].nombre);
    $('.modal_pregunta_productos .titulo h2').html(producto.nombre);
    modal = 1;
    
    var html = html_preguntas_producto(i);
    $('.modal_pregunta_productos .info_modal').append(html);
    

}

// FUNCIONES EN INDEX //
function select_pregunta(that){
    
    if($(that).hasClass('selected')){
        $(that).removeClass('selected');
    }else{
        $(that).addClass('selected');
    }
}

// CONFIRMAR PRODUCTOS PROMOS //
function confirmar_productos_promo(that){
    
    var count = 0;
    var arr = [];
    var parent = $(that).parents('.modal_productos_promo');
    var cantidad = parent.find('.pro_cat_promo').attr('data-cantidad');
    var carro_pos = parent.find('.pro_cat_promo').attr('data-pos');
    
    parent.find('.pro_cat_item').each(function(){
        count = count + parseInt($(this).find('.select_promo').val());
        arr.push({id_pro: parseInt($(this).find('.select_promo').attr('id')), cantidad: parseInt($(this).find('.select_promo').val())});
    });
    
    if(count == cantidad){
        var carro = get_carro();
        carro.splice(carro_pos, 1);
        for(var i=0, ilen=arr.length; i<ilen; i++){
            for(var j=0, jlen=arr[i].cantidad; j<jlen; j++){
                carro.push({id_pro: parseInt(arr[i].id_pro)});
            }
        }
        localStorage.setItem("carro", JSON.stringify(carro));
        if(!carro_daemon()){
            hide_modal();
        }
    }else{
        var diff = cantidad - count;
        if(diff == 1){
            alert("FALTA 1 PRODUCTO");
        }
        if(diff > 1){
            alert("FALTA "+diff+" PRODUCTOS");
        }
        if(diff == -1){
            alert("SOBRA 1 PRODUCTO");
        }
        if(diff < -1){
            alert("SOBRA "+Math.abs(diff)+" PRODUCTOS");
        }
    }
    
    
}
function nuevo_pedido(){
    borrar_carro();
    set_pedido(null);
    hide_modal();
}

// CONFIRMAR PREGUNTAS PRODUCTO //
function confirmar_pregunta_productos(that){

    var carros = get_carro();
    var parent = $(that).parents('.modal_pregunta_productos');
    var pregunta = parent.find('.s_pregunta');
    var i = pregunta.attr('data-pos');
    var k = 0;
    var m = 0;
    var n = 0;
    var count = 0;
    var cant = 0;
    var valores = [];
    var diff = 0;
    
    var preguntas = pregunta.find('.e_pregunta');
    preguntas.each(function(){
        k = $(this).attr('data-pos');
        $(this).find('.v_pregunta').each(function(){
            m = $(this).attr('data-pos');
            cant = $(this).attr('data-cant');
            count = 0;
            valores = [];
            $(this).find('.n_pregunta').each(function(){
                if($(this).hasClass('selected')){
                    count++;
                    valores.push($(this).html().trim());
                }
            });
            diff = cant - count;
            if(diff < 0){
                alert("HA SELECCIONADO "+Math.abs(diff)+" OPCIONES MAS");
            }
            if(diff > 0){
                alert("FALTA SELECCIONAR "+diff+" OPCIONES");
            }
            if(diff == 0){
                carros[i].preguntas[k].valores[m].seleccionados = valores;
                localStorage.setItem("carro", JSON.stringify(carros));
                if(!carro_daemon()){
                    hide_modal();
                }
            }
        });
    });
    
}

var map;
var map_init = 0;
var maps = [];

function init_map_local(id, lat, lng){
    
    var map_local = new google.maps.Map(document.getElementById('lmap-'+id), {
        center: {lat: lat, lng: lng},
        zoom: 15,
        mapTypeId: 'roadmap',
        disableDefaultUI: true
    });
    
    var myLatLng = { lat: lat, lng: lng };
    
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map_local
    });
    
}

function detalle_pedido(that){
    var dll = $(that).parent().find('.detalle_pedido');
    if(dll.is(':visible')){
        dll.hide();
    }else{
        dll.show();
    }
}

function return_direccion(){
    
    $('.cont_direccion .direccion_opciones').show();
    $('.cont_direccion .direccion_op1').hide();
    $('.cont_direccion .direccion_op2').hide();
    
}


var formatNumber = {
    separador: ".", // separador para los miles
    sepDecimal: ',', // separador para los decimales
    formatear:function (num){
        num +='';
        var splitStr = num.split('.');
        var splitLeft = splitStr[0];
        var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
        var regx = /(\d+)(\d{3})/;
        while (regx.test(splitLeft)) {
            splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
        }
        return this.simbol + splitLeft +splitRight;
    },
    new: function(num, simbol){
        this.simbol = simbol ||'';
        return this.formatear(num);
    }
}