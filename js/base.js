$(document).ready(function(){
    crear_pagina();
    var carro = JSON.parse(localStorage.getItem("carro")) || [];
    $('.cantcart_num').html(carro.length);
});
var menu = 0;
var modal = 0;
var paso = 1;

// INICIO BACK BUTTON //
history.replaceState(null, document.title, location.pathname);
history.pushState(null, document.title, location.pathname);
window.addEventListener("popstate", function() {

    if(modal == 1){ 
        hide_modal();
    }else{
        if(menu == 1){ 
            hidemenu() 
        }else{
            history.back();
        }
    }
    history.replaceState(null, document.title, location.pathname);
    history.pushState(null, document.title, location.pathname);
    
}, false);
// FIN BACK BUTTON //

// CLICK OUT //
$(document).click(function (e) {
    if($(e.target).hasClass('cont_modals')){
        hide_modal();
    }
    if($(e.target).hasClass('close')){
        hide_modal();
    }
});

// SHOW HIDE MODAL //
function hide_modal(){
    modal = 0;
    $('.modals').hide();
    $('.modals .cont_modals').find('.modal').each(function(){
        $(this).hide();
        if($(this).hasClass('modal_carta') || $(this).hasClass('modal_productos_promo') || $(this).hasClass('modal_pregunta_productos')){
            $(this).find('h1').html("");
            $(this).find('h2').html("");
            $(this).find('.info_modal').html("");
        }
        if($(this).hasClass('modal_carro')){
            
            $(this).find('.carro_inicio').html("");
            $(this).find('.carro_inicio').show();
            $(this).find('.carro_direccion').hide();
            $(this).find('.carro_final').hide();
            paso = 1;
            
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
function get_carro(){
    return JSON.parse(localStorage.getItem("carro")) || [];
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
    console.log(carro);
}
// OPEN CARRO //
function open_carro(){
    show_modal('modal_carro');
    process_carro();
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

// INICIO CREAR PAGINA //
function crear_pagina(){
    
    var categorias = data.categorias;
    var promociones = data.promociones;
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        $('.cont_contenido').append(html_crear_categoria(categorias[i]));
    }
    for(var i=0, ilen=promociones.length; i<ilen; i++){
        $('.cont_contenido').append(html_crear_promociones(promociones[i]));
    }
    
}

// INICIO ABRIR CATEGORIA //
function open_categoria(id){
    
    show_modal('modal_carta');
    var categorias = data.categorias;
    var cats = [];
    
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].id_cae == id){
            $('.modal_carta .titulo h1').html(categorias[i].nombre);
            $('.modal_carta .titulo h2').html('Descripcion buena nelson');
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
    
    var categorias = data.categorias;
    var html = '';
    $('.modal_carta .info_modal').html('');
    
    html += '<div class="lista_productos">';
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].id_cae == id && categorias[i].productos){
            var productos = categorias[i].productos;
            for(var j=0, jlen=productos.length; j<jlen; j++){
                html += html_productos(productos[j]);
            }
        }
    }
    html += '</div>';
    $('.modal_carta .info_modal').append(html);
    
}
function imprimir_categoria_modal(categorias){
    
    var html = '';
    $('.modal_carta .info_modal').html('');
    
    html += '<div class="lista_categorias">';
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        html += html_categorias(categorias[i]);
    }
    html += '</div>';
    $('.modal_carta .info_modal').append(html);
    
}

// INICIO ABRIR PROMOCIONES //
function open_promocion(id){
    
    show_modal('modal_carta');
    var promociones = data.promociones;
    var promos = [];
    
    for(var i=0, ilen=promociones.length; i<ilen; i++){
        if(promociones[i].id_prm == id){
            $('.modal_carta .titulo h1').html(promociones[i].nombre);
            $('.modal_carta .titulo h2').html('Descripcion buena nelson');
            for(var j=0, jlen=promociones.length; j<jlen; j++){
                if(promociones[i].id_prm == promociones[j].parent_id){
                    promos.push(promociones[j]);
                }
            }
            if(promos.length == 0){ imprimir_promo_modal(id) }
            if(promos.length > 0){ imprimir_promos_modal(promos) }
        }
    }
}
function imprimir_promo_modal(id){
    
    $('.modal_carta .info_modal').html("");
    
    var promociones = data.promociones;
    for(var i=0, ilen=promociones.length; i<ilen; i++){
        
        if(promociones[i].id_prm == id){
            
            var promo = promociones[i];
            var html = '<div class="lista_promociones" onclick="add_carro_promocion('+promo.id_prm+')">';
            
            if(promo.categorias){
                html += '<div class="promocion_categoria"><div class="pro_titulo">Elije:</div>';
                for(var j=0, jlen=promo.categorias.length; j<jlen; j++){
                    html += '<div class="item_pro_cat"><span>'+promo.categorias[j].cantidad+'</span><span>'+get_categoria(promo.categorias[j].id_cae).nombre+'</span></div>';
                }
                html += '</div>';
            }
            if(promo.productos){
                html += '<div class="promocion_producto"><div class="pro_titulo">Productos</div>';
                for(var j=0, jlen=promo.productos.length; j<jlen; j++){
                    html += '<div class="item_pro_pro">'+get_producto(promo.productos[j].id_pro).nombre+'</div>';
                }
                html += '</div>';
                
            }
            html += '<div class="promo_precio">$9.990</div></div>';
            $('.modal_carta .info_modal').append(html);
            
        }
    }
    
}
function imprimir_promos_modal(promociones){
    
    var html = '';
    $('.modal_carta .info_modal').html('');
    
    html += '<div class="lista_promociones">';
    for(var i=0, ilen=promociones.length; i<ilen; i++){
        html += html_promos(promociones[i]);
    }
    html += '</div>';
    $('.modal_carta .info_modal').append(html);
}

// GET CATEGORIAS - PRODUCTOS - PROMOS - PREGUNTAS //
function get_producto(id_pro){
    var productos = data.productos;
    for(var i=0, ilen=productos.length; i<ilen; i++){
        if(productos[i].id_pro == id_pro){
            return productos[i];
        }
    }
}
function get_categoria(id_cae){
    var categorias = data.categorias;
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].id_cae == id_cae){
            return categorias[i];
        }
    }
}
function get_promocion(id_prm){
    var promociones = data.promociones;
    for(var i=0, ilen=promociones.length; i<ilen; i++){
        if(promociones[i].id_prm == id_prm){
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
    for(var i=0, ilen=data.preguntas.length; i<ilen; i++){
        if(id_pre == data.preguntas[i].id_pre){
            return data.preguntas[i];
        }
    }
    return null;
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
function add_carro_promocion(id_prm){
    
    var carro = JSON.parse(localStorage.getItem("carro")) || [];
    var promo = get_promocion(id_prm);
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
    var carros = get_carro();
    carros.splice(i, 1);
    localStorage.setItem("carro", JSON.stringify(carros));
    process_carro();

}
function delete_pre_pro_carro(i){
    var carros = get_carro();
    delete carros[i].preguntas;
    localStorage.setItem("carro", JSON.stringify(carros));
    process_carro();
}

// PROCESS CARRO //
function process_carro(){
    
    if(!carro_daemon()){
        
        var info = process_new_promos();
        var carro = info.carro;
        var carro_promos = info.carro_promos;
        var promocion = {};
        var producto = {};
        var count = 0;
        var html = "<div class='process_carro'>";
        
        for(var i=0, ilen=carro_promos.length; i<ilen; i++){
            promocion = get_promocion(carro_promos[i].id_prm);
            html += "<div class='process_carro_promo'><div class='promo_info'>"+promocion.nombre+"</div><div class='promo_detalle'>";
            for(var j=0, jlen=carro.length; j<jlen; j++){
                if(carro[j].promo == i){
                    count++;
                    producto = get_producto(carro[j].id_pro);
                    html += "<div class='promo_detalle_item clearfix'><div class='promo_detalle_nombre'>"+producto.nombre+"</div><div class='promo_detalle_acciones clearfix'><div class='precio'>$1.990</div><div class='accion' onclick='delete_pro_carro("+j+")'>3</div></div></div>";
                }
            }
            html += "</div></div>";
        }
        html += "<div class='process_carro_restantes'>";
        for(var j=0, jlen=carro.length; j<jlen; j++){
            if(carro[j].promo === undefined){
                producto = get_producto(carro[j].id_pro);
                html += "<div class='restantes_detalle_item clearfix'><div class='restantes_detalle_nombre'>"+producto.nombre+"</div><div class='restantes_detalle_acciones clearfix'><div class='precio'>$1.990</div><div class='accion' onclick='delete_pro_carro("+j+")'>3</div></div></div>";
                count++;
            }
        }
        html += "</div></div>";

        $('.cantcart_num').html(count);
        $('.modal_carro .carro_inicio').html(html);
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
    return false;

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
                carro_promos.push({ id_prm: promos[i].id_prm });
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
    return aux;
    
}
function process_promo(){
    
    var promos = data.promociones;
    var productos = [];
    var aux = [];
    var cantidad = 0;
    
    for(var i=0, ilen=promos.length; i<ilen; i++){
        productos = [];
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
            aux.push({id_prm: promos[i].id_prm, nombre: promos[i].nombre, productos: productos, cantidad: cantidad});
        }
    }
    aux.sort(function(a, b){return a.cantidad - b.cantidad});
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
    modal = 1;
    var html = "<div class='pro_cat_promo' data-pos='"+i+"' data-cantidad='"+cantidad+"'>";
    
    $('.modal_productos_promo .titulo h1').html(categoria.nombre);
    $('.modal_productos_promo .titulo h2').html('Debe seleccionar '+cantidad+' productos');
    
    if(categoria.productos){
        for(var i=0, ilen=categoria.productos.length; i<ilen; i++){
            producto = get_producto(categoria.productos[i]);
            html += "<div class='pro_cat_item clearfix'><div class='pro_cat_item_select'><select id='"+categoria.productos[i]+"' class='select_promo'>";
            for(var j=0; j<=cantidad; j++){
                html += "<option value='"+j+"'>"+j+"</option>";
            }
            html += "</select></div><div class='pro_cat_item_nombre'>"+producto.nombre+"</div></div>";
        }
    }
    
    html += "</div>";
    $('.modal_productos_promo .info_modal').html(html);
    
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

    var carros = get_carro();
    var producto = get_producto(carros[i].id_pro);
    
    var html = "<div class='s_pregunta' data-pos='"+i+"'>";

    $('.modals, .modal_pregunta_productos').show();
    modal = 1;
    $('.modal_pregunta_productos .titulo h1').html(producto.nombre);
    $('.modal_pregunta_productos .titulo h2').html('Configurar Producto');
    
    for(var k=0, klen=carros[i].preguntas.length; k<klen; k++){
        html += "<div class='e_pregunta' data-pos='"+k+"'><div class='pregunta_titulo'>"+carros[i].preguntas[k].nombre+"</div>"; 
        for(var m=0, mlen=carros[i].preguntas[k].valores.length; m<mlen; m++){

            html += "<div class='titulo_v_pregunta'>Seleccionar "+carros[i].preguntas[k].valores[m].cantidad+"</div>";
            html += "<div class='v_pregunta' data-pos='"+m+"' data-cant='"+carros[i].preguntas[k].valores[m].cantidad+"'>"; 
            for(var n=0, nlen=carros[i].preguntas[k].valores[m].valores.length; n<nlen; n++){
                html += "<div onclick='select_pregunta(this)' class='n_pregunta'>"+carros[i].preguntas[k].valores[m].valores[n]+"</div>";
            }
            html += "</div>"; 
            
        }
        html += "</div>";
    }
    
    html += "</div>";
    $('.modal_pregunta_productos .info_modal').html(html);

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

// CONFIRMAR PEDIDO //
function confirmar_pedido(){
    
    var modales = $('.modal_carro .cont_info').find('.info_modal');
    var precio = $('.modal_carro .cont_info .precio');

    if(paso == 1){
        modales.eq(0).hide();
        modales.eq(1).show();
        precio.hide();
    }
    if(paso == 2){
        modales.eq(1).hide();
        modales.eq(2).show();
        precio.hide();
    }
    paso++;
    
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