$(document).ready(function(){
    set_pedidos(pedidos);
    socket_init();
    listar_pedidos();
    modificar_horas();
});
var seleccionado = 0;
var categoria = 0;
var catalogo = 0;
var crear_nuevo = 0;
var estados = ['Enviado', 'Recepcionado', 'Preparando', 'Empaque', 'Despacho'];
var tiempos = { retiro: 1500, despacho: 3600 };
var time = new Date().getTime();

function actualizar_seleccionado(){}
function add_carro_producto(id_pro){

    var pedidos = get_pedidos();
    var pedido = pedidos[seleccionado];
    
    var producto = get_producto(id_pro);
    var item_carro = { id_pro: parseInt(id_pro) };
    
    if(producto.preguntas){
        item_carro.preguntas = [];
        for(var k=0, klen=producto.preguntas.length; k<klen; k++){
            item_carro.preguntas.push(get_preguntas(producto.preguntas[k]));
        }
    }
    
    pedido.carro.push(item_carro);
    set_pedidos(pedidos);
    guardar_pedido(seleccionado);
    mostrar_pregunta(pedido.carro.length - 1);
    listar_pedidos();

}
function add_carro_promocion(id_cae){
    
    var pedidos = get_pedidos();
    var promo = get_categoria(id_cae);
    var tiene_cats = -1;
    
    pedidos[seleccionado].promos.push({ id_cae: id_cae });
    var num_promo = pedidos[seleccionado].promos.length - 1;

    if(promo.categorias){
        for(var i=0, ilen=promo.categorias.length; i<ilen; i++){
            pedidos[seleccionado].carro.push({id_cae: parseInt(promo.categorias[i].id_cae), cantidad: parseInt(promo.categorias[i].cantidad), promo: num_promo });
            tiene_cats = pedidos[seleccionado].carro.length - 1;
        }
    }
    if(promo.productos){
        for(var i=0, ilen=promo.productos.length; i<ilen; i++){
            for(var j=0, jlen=promo.productos[i].cantidad; j<jlen; j++){
                var producto = get_producto(promo.productos[i].id_pro);
                var item_carro = { id_pro: parseInt(promo.productos[i].id_pro), promo: num_promo };
                if(producto.preguntas){
                    item_carro.preguntas = [];
                    for(var k=0, klen=producto.preguntas.length; k<klen; k++){
                        item_carro.preguntas.push(get_preguntas(producto.preguntas[k]));
                    }
                }
                pedidos[seleccionado].carro.push(item_carro);
            }
        }
    }
    set_pedidos(pedidos);
    guardar_pedido(seleccionado);
    listar_pedidos();
    categorias_base(0);
    if(tiene_cats >= 0){
        seleccionar_productos_categoria_promo(tiene_cats);
    }
    
}
function get_preguntas(id_pre){
    for(var i=0, ilen=data.catalogos[catalogo].preguntas.length; i<ilen; i++){
        if(id_pre == data.catalogos[catalogo].preguntas[i].id_pre){
            return data.catalogos[catalogo].preguntas[i];
        }
    }
    return null;
}
function open_categoria(id){

    if(cats_or_prods(id)){
        categorias_base(id);
    }else{
        open_productos(id);
    }

}
function cats_or_prods(id){
    
    var categorias = data.catalogos[catalogo].categorias;
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].parent_id == id){
            return true;
        }
    }
    return false;
}
function open_productos(id){
    
    $('.lista_productos').html('');    
    var categoria = get_categoria(id);
    if(categoria.productos){
        for(var j=0, jlen=categoria.productos.length; j<jlen; j++){
            $('.lista_productos').append(html_home_productos(get_producto(categoria.productos[j])));
        }
    }
    
}
function html_home_productos(obj){
    
    var Div = create_element_class('producto');
    Div.onclick = function(){ add_carro_producto(obj.id_pro); };
    var Divnombre = create_element_class_inner('nombre', obj.nombre);
    Div.appendChild(Divnombre);
    return Div;
    
}
function categoria_padre(){
    if(categoria != 0){
        var cat = get_categoria(categoria);
        open_categoria(cat.parent_id);
    }else{
        open_categoria(0);
    }
}
function modificar_horas(){
    
    var pedidos = get_pedidos_false();
    if(pedidos){
        for(var i=0, ilen=pedidos.length; i<ilen; i++){

            var fecha_ahora = Math.round(new Date().getTime()/1000);
            var time = (pedidos[i].despacho == 1) ? tiempos.despacho : tiempos.retiro ;
            var diff = Math.round((pedidos[i].fecha + time - fecha_ahora)/60);
            if(diff < 0){ diff = 0; }
            $('.lista_pedidos').find('.pedido').eq(i).find('.t_tiempo').find('.t_nombre').html(diff);

        }
    }
    setTimeout(modificar_horas, 6000);
    
}
function socket_init(){
    
    var socket = io.connect('http://35.196.220.197:80', { 'forceNew': true });
    socket.on('local-'+local_code, function(id_ped) {
        agregar_pedido(id_ped);
    });
    socket.on('connect', function() {
        $('.alert_socket').hide();
    });
    socket.on('disconnect', function() {
        $('.alert_socket').show();
    });
    
}
function agregar_pedido(id){
    
    console.log("AGREGAR PEDIDO");
    
    var send = { id_ped: id };
    $.ajax({
        url: "ajax/get_pedido.php",
        type: "POST",
        data: send,
        success: function(data){
            var info = JSON.parse(data);
            //console.log(info);
            nuevo(info[0]);
        }, error: function(e){
            console.log(e);
        }
    });
    
}
function set_pedido(index, that){
    
    seleccionado = index;
    var count = 0;
    categorias_base(0);
    $(that).parents('.lista_pedidos').find('.pedido').each(function(){
        if(count == index){
            $(this).addClass('seleccionado');
        }else{
            $(this).removeClass('seleccionado');
        }
        count++;
    });
    
}
function categorias_base(n){
    
    $('.lista_categorias').html('');
    $('.lista_productos').html('');
    categoria = n;
    var categorias = data.catalogos[catalogo].categorias;
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].parent_id == n && categorias[i].ocultar == 0){
            $('.lista_categorias').append(html_home_categorias(categorias[i]));  
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
function get_producto(id_pro){
    var productos = data.catalogos[catalogo].productos;
    for(var i=0, ilen=productos.length; i<ilen; i++){
        if(productos[i].id_pro == id_pro){
            return productos[i];
        }
    }
}
function listar_pedidos(){
    $('.lista_pedidos').html('');
    var pedidos = JSON.parse(localStorage.getItem("pedidos")) || false;
    if(pedidos){
        for(var i=0, ilen=pedidos.length; i<ilen; i++){
            console.log(pedidos[i]);
            if(pedidos[i].eliminado == 0 && pedidos[i].ocultar == 0){
                $('.lista_pedidos').append(html_home_pedidos(pedidos[i], i));
            }
        }
    }
}
function create_element_class(clase){
    var Div = document.createElement('div');
    Div.className = clase;
    return Div;
}
function create_element_class_inner(clase, value){
    var Div = document.createElement('div');
    Div.className = clase;
    Div.innerHTML = value;
    return Div;
}
function html_home_pedidos(obj, index){
    
    var total = 0;
    var pro, cat, carro, promos;
    
    if(obj.carro){
        obj.carro.forEach(function(carro_item, index){
            if(carro_item.id_pro && carro_item.promo === undefined){
                pro = get_producto(carro_item.id_pro);
                total = total + parseInt(pro.precio);
            }
        });
    }

    if(obj.promos){
        obj.promos.forEach(function(promo_item, index){
            cat = get_categoria(promo_item.id_cae);
            total = total + parseInt(cat.precio);
        });
    }
    
    if(seleccionado == index){
        categorias_base(index);
        var Div = create_element_class('pedido seleccionado');
    }else{
        var Div = create_element_class('pedido');
    }
    
    var p_num = create_element_class_inner('p_num', 'Pedido #'+obj.id_ped);
    var p_estado = create_element_class_inner('p_estado', 'Abierto');
    var p_precio = create_element_class_inner('p_precio', formatNumber.new(parseInt(total), "$"));
    var p_cont = create_element_class('p_cont');
    p_cont.onclick = function(){ set_pedido(index, this) };
    
    var btn_mod = create_element_class('btn_mod');
    btn_mod.onclick = function(){ ver_pedido(index, this) };
    
    var btn_open = create_element_class('btn_open');
    btn_open.onclick = function(){ ver_comanda(index) };
    
    var btn_carro = create_element_class('btn_carro');
    btn_carro.onclick = function(){ ver_detalle_carro(index, this) };
    
    Div.appendChild(p_cont);
    Div.appendChild(p_num);
    Div.appendChild(p_estado);
    Div.appendChild(p_precio);
    Div.appendChild(btn_mod);
    Div.appendChild(btn_open);
    Div.appendChild(btn_carro);
    
    if(obj.tipo == 1){
        
        var estado = create_element_class('p_opciones');
        var anterior = create_element_class('p_anterior');
        anterior.onclick = function(){ cambiar_estado(index, -1, this) };
        var nombre = create_element_class_inner('p_nombre', estados[obj.estado]);
        var siguiente = create_element_class('p_siguiente');
        siguiente.onclick = function(){ cambiar_estado(index, 1, this) };

        estado.appendChild(anterior);
        estado.appendChild(nombre);
        estado.appendChild(siguiente);

        var t_tiempo = create_element_class('t_tiempo');
        var t_anterior = create_element_class('t_anterior');
        t_anterior.onclick = function(){ cambiar_hora(index, -1, this) };
        var t_nombre = create_element_class_inner('t_nombre', '');
        var t_siguiente = create_element_class('t_siguiente');
        t_siguiente.onclick = function(){ cambiar_hora(index, 1, this) };

        t_tiempo.appendChild(t_anterior);
        t_tiempo.appendChild(t_nombre);
        t_tiempo.appendChild(t_siguiente);
        
        Div.appendChild(estado);
        Div.appendChild(t_tiempo);
        
    }

    return Div;
    
}
function mostrar_pregunta(i){
    
    var pedidos = get_pedidos();
    var pedido = pedidos[seleccionado];
    
    var producto = get_producto(pedido.carro[i].id_pro);

    $('.pop_up').show();
    $('.p4').show();

    $('.p4 .n_title').html(producto.nombre);
    $('.p4 .data_info').html(html_preguntas_producto(i));

}
function seleccionar_productos_categoria_promo(i){
    
    var pedidos = get_pedidos();
    var pedido = pedidos[seleccionado];
    
    var id_cae = pedido.carro[i].id_cae;
    var cantidad = pedido.carro[i].cantidad;
    var categoria = get_categoria(id_cae);
    
    $('.pop_up').show();
    $('.p3').show();
    
    $('.p3 .n_title').html(categoria.nombre);
    $('.p3 .n_stitle').html('Debe seleccionar '+cantidad+' productos');
    $('.p3 .data_info').html(html_seleccionar_productos_categoria_promo(categoria, i, cantidad));
    
}
function confirmar_productos_promo(that){
    
    var info = $(that).parents('.nuevo_pedido').find('.data_info');
    
    var count = 0;
    var arr = [];
    var parent = $(that).parents('.nuevo_pedido');
    console.log(parent);
    var cantidad = parent.find('.pro_cat_promo').attr('data-cantidad');
    var carro_pos = parent.find('.pro_cat_promo').attr('data-pos');
    var producto;
    var item_carro;
    
    parent.find('.pro_cat_item').each(function(){
        count = count + parseInt($(this).find('.select_promo').val());
        arr.push({id_pro: parseInt($(this).find('.select_promo').attr('id')), cantidad: parseInt($(this).find('.select_promo').val())});
    });
    
    if(count == cantidad){
        
        var pedidos = get_pedidos();
        var aux_promo = pedidos[seleccionado].carro[carro_pos].promo;
        pedidos[seleccionado].carro.splice(carro_pos, 1);
        for(var i=0, ilen=arr.length; i<ilen; i++){
            for(var j=0, jlen=arr[i].cantidad; j<jlen; j++){
                
                producto = get_producto(arr[i].id_pro);
                item_carro = { id_pro: parseInt(arr[i].id_pro), promo: aux_promo };
                
                if(producto.preguntas){
                    item_carro.preguntas = [];
                    for(var k=0, klen=producto.preguntas.length; k<klen; k++){
                        item_carro.preguntas.push(get_preguntas(producto.preguntas[k]));
                    }
                }
                pedidos[seleccionado].carro.push(item_carro);
                
            }
        }
        set_pedidos(pedidos);
        if(proceso(pedidos[seleccionado])){
            
            $('.pop_up').hide();
            $('.p3').hide();
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
function select_pregunta(that){
    
    var parent = $(that).parent();
    var cantidad = parent.attr('data-cant');
    var seleccionadas = parent.find('.selected').length;
    var diff = cantidad - seleccionadas;

    if($(that).hasClass('selected')){
        $(that).removeClass('selected');
    }
    if(cantidad == 1 && !$(that).hasClass('selected')){
        parent.find('.selected').eq(0).removeClass('selected');
        $(that).addClass('selected');
    }
    if(cantidad > 1 && !$(that).hasClass('selected') && diff > 0){
        $(that).addClass('selected');
    }
    
}
function confirmar_pregunta_productos(that){

    var parent = $(that).parents('.p4');
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
                var pedidos = get_pedidos();
                pedidos[seleccionado].carro[i].preguntas[k].valores[m].seleccionados = valores;
                set_pedidos(pedidos);
                $('.pop_up').hide();
                $('.p4').hide();
                console.log(tiene_pregunta(pedidos[seleccionado].carro));
                //ver_detalle_carro(seleccionado, null);
            }
        });
    });
    
}
function html_preguntas_producto(i){
    
    var pedidos = get_pedidos();
    var pedido = pedidos[seleccionado];
    var carro = pedido.carro;
    
    var html = document.createElement('div');
    html.className = 's_pregunta';
    html.setAttribute('data-pos', i);
    
    for(var k=0, klen=carro[i].preguntas.length; k<klen; k++){
        
        var e_pregunta = document.createElement('div');
        e_pregunta.className = 'e_pregunta';
        e_pregunta.setAttribute('data-pos', k);
        
        var pregunta_titulo = document.createElement('div');
        pregunta_titulo.className = 'pregunta_titulo';
        pregunta_titulo.innerHTML = carro[i].preguntas[k].nombre;
        e_pregunta.appendChild(pregunta_titulo);
        
        
        for(var m=0, mlen=carro[i].preguntas[k].valores.length; m<mlen; m++){
            
            var titulo_v_pregunta = document.createElement('div');
            titulo_v_pregunta.className = 'titulo_v_pregunta';
            titulo_v_pregunta.innerHTML = carro[i].preguntas[k].valores[m].nombre;
                        
            var v_pregunta = document.createElement('div');
            v_pregunta.className = 'v_pregunta';
            v_pregunta.setAttribute('data-pos', m);
            v_pregunta.setAttribute('data-cant', carro[i].preguntas[k].valores[m].cantidad);

            for(var n=0, nlen=carro[i].preguntas[k].valores[m].valores.length; n<nlen; n++){
                
                var n_pregunta = document.createElement('div');
                if(carro[i].preguntas[k].valores[m].seleccionados){
                    if(carro[i].preguntas[k].valores[m].seleccionados.indexOf(carro[i].preguntas[k].valores[m].valores[n]) != -1){
                        n_pregunta.className = 'n_pregunta selected';
                    }else{
                        n_pregunta.className = 'n_pregunta';
                    }
                }else{
                    n_pregunta.className = 'n_pregunta';
                }
                n_pregunta.innerHTML = carro[i].preguntas[k].valores[m].valores[n];
                n_pregunta.onclick = function(){ select_pregunta(this) };
                v_pregunta.appendChild(n_pregunta);
                
            }
            
            e_pregunta.appendChild(titulo_v_pregunta);
            e_pregunta.appendChild(v_pregunta);
            
        }
        html.appendChild(e_pregunta);
        
    }
    return html;
    
}
function html_seleccionar_productos_categoria_promo(categoria, i, cantidad){
    
    var producto;
    var pro_cat_item, pro_cat_item_select, pro_cat_item_nombre, select, option;
    
    
    if(categoria.productos){
        
        var html = document.createElement('div');
        html.className = 'pro_cat_promo';
        html.setAttribute('data-pos', i);
        html.setAttribute('data-cantidad', cantidad);
        
        for(var i=0, ilen=categoria.productos.length; i<ilen; i++){
            
            producto = get_producto(categoria.productos[i]);
            
            pro_cat_item = document.createElement('div');
            pro_cat_item.className = 'pro_cat_item clearfix';
            
            pro_cat_item_select = document.createElement('div');
            pro_cat_item_select.className = 'pro_cat_item_select';
            
            select = document.createElement("select");
            select.id = categoria.productos[i];
            select.className = 'select_promo';
            
            for(var j=0; j<=cantidad; j++){
                option = document.createElement("option");
                option.value = j;
                option.text = j;
                select.appendChild(option);
            }
            
            pro_cat_item_select.appendChild(select);
            pro_cat_item.appendChild(pro_cat_item_select);
            
            pro_cat_item_nombre = document.createElement('div');
            pro_cat_item_nombre.className = 'pro_cat_item_nombre';
            pro_cat_item_nombre.innerHTML = producto.numero + '.- ' + producto.nombre;
            pro_cat_item.appendChild(pro_cat_item_nombre);

            html.appendChild(pro_cat_item);
            
        }
        
    }
    return html;
    
}
function proceso(pedido){

    for(var i=0, ilen=pedido.carro.length; i<ilen; i++){
        if(!pedido.carro[i].id_pro){
            seleccionar_productos_categoria_promo(i);
            return false;
        }
    }
    return true;
    
}
function delete_pro_carro(i){
    var pedidos = get_pedidos();
    pedidos[seleccionado].carro.splice(i, 1);
    set_pedidos(pedidos);
    guardar_pedido(seleccionado);
    ver_detalle_carro(seleccionado, null);
    listar_pedidos();
}
function delete_promo(that){
    var pedidos = get_pedidos();
    var i = $(that).attr('promo-pos');
    pedidos[seleccionado].promos.splice(i, 1);
    var carro = pedidos[seleccionado].carro;
    pedidos[seleccionado].carro = [];
    
    for(var j=0; j < carro.length; j++){
        if(!carro[j].hasOwnProperty('promo') && carro[j].promo != i){
           pedidos[seleccionado].carro.push(carro[j]); 
        }
    }
    set_pedidos(pedidos);
    guardar_pedido(seleccionado);
    ver_detalle_carro(seleccionado, null);
    listar_pedidos();
}
function ver_detalle_carro(index, that){
    
    if(that !== null){ set_pedido(index, that) }
    
    var pedidos = get_pedidos();
    var pedido = pedidos[index];
    var total = 0;
    var html = create_element_class('process_carro');
    
    if(proceso(pedido)){
        
        $('.p2 .n_title').html("Listado de Productos");
        var promo, process_carro_promo, promo_detalle, promo_info, promo_precio, promo_delete, count, producto;
        
        for(var i=0, ilen=pedido.promos.length; i<ilen; i++){

            promo = get_categoria(pedido.promos[i].id_cae);
            total = total + parseInt(promo.precio);
            
            process_carro_promo = create_element_class('process_carro_promo');
            
            promo_detalle = create_element_class('promo_detalle');
            promo_info = create_element_class_inner('promo_info', promo.nombre);
            promo_precio = create_element_class_inner('promo_precio', formatNumber.new(parseInt(promo.precio), "$"));
            promo_delete = create_element_class_inner('promo_delete material-icons', 'close');
            promo_delete.setAttribute('promo-pos', i);
            promo_delete.onclick = function(){ delete_promo(this) };
            
            process_carro_promo.appendChild(promo_info);
            process_carro_promo.appendChild(promo_precio);
            process_carro_promo.appendChild(promo_delete);
            
            for(var j=0, jlen=pedido.carro.length; j<jlen; j++){
                if(pedido.carro[j].promo == i){
                    count++;
                    producto = get_producto(pedido.carro[j].id_pro);
                    promo_detalle.appendChild(promo_carros(producto, j));
                }
            }
            
            process_carro_promo.appendChild(promo_detalle);
            html.appendChild(process_carro_promo);
            
        }
        

        var restantes = false;
        var process_carro_restantes = create_element_class('process_carro_restantes');
        
        for(var i=0, ilen=pedido.carro.length; i<ilen; i++){
            if(!pedido.carro[i].hasOwnProperty('promo')){
                var pro = get_producto(pedido.carro[i].id_pro);
                process_carro_restantes.appendChild(promo_restantes(pro, i, tiene_pregunta(pedido.carro[i])));
                total = total + parseInt(pro.precio);
                restantes = true;
            }
        }
        
        if(restantes){ 
            html.appendChild(process_carro_restantes);
        }
        
        $('.p2 .data_info').html(html);
        
        $('.pop_up').show();
        $('.p2').show();
        
    }

}
function promo_restantes(producto, j, tiene_pregunta){
    
    console.log(producto);
    
    var Div = document.createElement('div');
    Div.className = 'restantes_detalle_item clearfix';
    
    var Nombre = document.createElement('div');
    Nombre.className = 'restantes_detalle_nombre';
    Nombre.innerHTML = producto.nombre;
    Div.appendChild(Nombre);
    
    var Acciones = document.createElement('div');
    Acciones.className = 'restantes_detalle_acciones clearfix';

    var Precio = document.createElement('div');
    Precio.className = 'precio';
    Precio.innerHTML = formatNumber.new(parseInt(producto.precio), "$");
    Acciones.appendChild(Precio);
    
    var pedidos = get_pedidos();
    var carro = pedidos[seleccionado].carro[j];
    
    if(carro.preguntas !== undefined){
    
        var Pregunta = document.createElement('div');
        Pregunta.className = 'pregunta material-icons';
        if(!tiene_pregunta){
            Pregunta.innerHTML = 'more_horiz';
        }else{
            Pregunta.innerHTML = 'help_outline';
        }
        Pregunta.onclick = function(){ mostrar_pregunta(j) };
        Acciones.appendChild(Pregunta);
    
    }else{
        
        var Espacio = document.createElement('div');
        Espacio.className = 'espacio';
        Acciones.appendChild(Espacio);
        
    }
    
    var Accion = document.createElement('div');
    Accion.className = 'accion material-icons';
    Accion.innerHTML = 'close';
    Accion.onclick = function(){ delete_pro_carro(j) };
    Acciones.appendChild(Accion);
    
    Div.appendChild(Acciones);
    return Div;

    
}
function promo_carros(producto, j){
    
    var Div = document.createElement('div');
    Div.className = 'promo_detalle_item clearfix';
    
    var Nombre = document.createElement('div');
    Nombre.className = 'promo_detalle_nombre';
    Nombre.innerHTML = producto.numero + '.- ' + producto.nombre;
    Div.appendChild(Nombre);
    
    var Acciones = document.createElement('div');
    Acciones.className = 'promo_detalle_acciones clearfix';
    
    var pedidos = get_pedidos();
    var carro = pedidos[seleccionado].carro[j];
    
    if(carro.preguntas){
        
        var Accion = document.createElement('div');
        Accion.className = 'accion material-icons';
        Accion.onclick = function(){ mostrar_pregunta(j) };
        
        if(tiene_pregunta(carro)){
            Accion.innerHTML = 'help_outline';
        }else{
            Accion.innerHTML = 'more_horiz';
        }
        
        Acciones.appendChild(Accion);
        
    }
    
    Div.appendChild(Acciones);
    return Div;
    
}
function tiene_pregunta(carro){
    
    if(carro.preguntas){
        for(var k=0, klen=carro.preguntas.length; k<klen; k++){
            for(var j=0, jlen=carro.preguntas[k].valores.length; j<jlen; j++){
                var valores = carro.preguntas[k].valores[j];
                if(valores.seleccionados){
                    if(valores.seleccionados.length < valores.cantidad){
                        return true;
                    }
                }else{
                    return true;
                }
            }
        }
    }
    return false;
}
function ver_comanda(index){
    
    var pedidos = get_pedidos();
    var pedido = pedidos[index];
    
    if(proceso(pedido)){
    
        var code = pedido.pedido_code;
        window.open("/detalle.php?code="+code, 'Imprimir Ctrl+P').focus();
    
    }
}
function html_home_categorias(obj){
    
    var Div = create_element_class('categoria');
    if(obj.tipo == 0){
        Div.onclick = function(){ open_categoria(obj.id_cae) };
    }
    if(obj.tipo == 1){
        Div.onclick = function(){ add_carro_promocion(obj.id_cae) };
    }
    var Divnombre = create_element_class_inner('nombre', obj.nombre);
    Div.appendChild(Divnombre);
    return Div;
    
}
function np_close(that){
    
    $('.pop_up').hide();
    $(that).parent().hide();
    
}
function ver_pedido(index, that){
    
    // MOSTRAR INDEX
    var pedidos, pedido;
    if(index >= 0){
        
        set_pedido(index, that);
        pedidos = get_pedidos();
        pedido = pedidos[index];
        if(pedido.id_ped == 0){
            $('.p1 .n_title').html("PEDIDO AUN NO GUARDADO");
        }
        if(pedido.id_ped > 0){
            $('.p1 .n_title').html("Pedido #"+pedido.id_ped);
        }
        crear_nuevo = 0;
        
    }
    if(index == -1){
        
        pedido = pedido_obj();
        $('.p1 .n_title').html("Ingresar Nuevo Pedido");
        crear_nuevo = 1;
    
    }
    
    $('#nombre').val(pedido.nombre);
    $('#telefono').val(pedido.telefono);
    
    if(pedido.despacho == 0){
        $('#despacho option[value=0]').attr('selected', 'selected');
        $('.t_despacho').hide();
        $('.t_repartidor').hide();
    }
    if(pedido.despacho == 1){
        $('#despacho option[value=1]').attr('selected', 'selected');
        $('.t_despacho').show();
        $('.t_repartidor').show();
    }
    
    if(pedido.pre_wasabi == 1){ $('#pre_wasabi').attr('checked', 'checked') }else{ $('#pre_wasabi').attr('checked', '') }
    if(pedido.pre_gengibre == 1){ $('#pre_gengibre').attr('checked', 'checked') }else{ $('#pre_gengibre').attr('checked', '') }
    if(pedido.pre_embarazadas == 1){ $('#pre_embarazadas').attr('checked', 'checked') }else{ $('#pre_embarazadas').attr('checked', '') }
    if(pedido.pre_soya == 1){ $('#pre_soya').attr('checked', 'checked') }else{ $('#pre_soya').attr('checked', '') }
    if(pedido.pre_teriyaki == 1){ $('#pre_teriyaki').attr('checked', 'checked') }else{ $('#pre_teriyaki').attr('checked', '') }
    $('#pre_palitos option[value='+pedido.pre_palitos+']').attr('selected', 'selected');
    $('#id_mot option[value='+pedido.id_mot+']').attr('selected', 'selected');
    
    $('.pop_up').show();
    $('.p1').show();
    
}
function nuevo_pedido(){
    
    $('#nombre').val('');
    $('.pop_up').show();
    $('.p1').show();
    
}
function done_pedido(){

    if(crear_nuevo == 0){
        
        console.log("MODIFICAR EXISTENTE");
        var pedidos = get_pedidos();
        pedidos[seleccionado].nombre = $('#nombre').val();
        pedidos[seleccionado].telefono = $('#telefono').val();
        pedidos[seleccionado].despacho = $('#despacho').val();
        pedidos[seleccionado].direccion = $('#direccion').val();
        pedidos[seleccionado].depto = $('#depto').val();
        
        pedidos[seleccionado].pre_wasabi = ($('#pre_wasabi').is(':checked')) ? 1 : 0 ;
        pedidos[seleccionado].pre_gengibre = ($('#pre_gengibre').is(':checked')) ? 1 : 0 ;
        pedidos[seleccionado].pre_embarazadas = ($('#pre_embarazadas').is(':checked')) ? 1 : 0 ;
        pedidos[seleccionado].pre_soya = ($('#pre_soya').is(':checked')) ? 1 : 0 ;
        pedidos[seleccionado].pre_teriyaki = ($('#pre_teriyaki').is(':checked')) ? 1 : 0 ;
        pedidos[seleccionado].pre_palitos = $('#pre_palitos').val();
        
        pedidos[seleccionado].id_mot = $('#id_mot').val();
        
        set_pedidos(pedidos);
        guardar_pedido(seleccionado);
        
    }
    if(crear_nuevo == 1){
        
        console.log("NUEVO");
        
        var obj = pedido_obj();
        obj.id_ped = 0;
                
        obj.nombre = $('#nombre').val();
        obj.telefono = $('#telefono').val();
        obj.despacho = $('#despacho').val();
        
        obj.direccion = $('#direccion').val();
        obj.depto = $('#depto').val();
        
        obj.pre_wasabi = ($('#pre_wasabi').is(':checked')) ? 1 : 0 ;
        obj.pre_gengibre = ($('#pre_gengibre').is(':checked')) ? 1 : 0 ;
        obj.pre_embarazadas = ($('#pre_embarazadas').is(':checked')) ? 1 : 0 ;
        obj.pre_soya = ($('#pre_soya').is(':checked')) ? 1 : 0 ;
        obj.pre_teriyaki = ($('#pre_teriyaki').is(':checked')) ? 1 : 0 ;
        obj.pre_palitos = $('#pre_palitos').val();
        
        obj.id_mot = $('#id_mot').val();
        
        add_pedido(obj);
        guardar_pedido(0);
        
    }
    
    $('.p1').hide();
    $('.pop_up').hide();
    
}
function pedido_obj(){
    return {
        id_ped: 0,
        pedido_code: '', 
        tipo: 0,
        estado: 0,
        fecha: Math.round(new Date().getTime()/1000),
        despacho: null,
        carro: [],  
        promos: [], 
        pre_wasabi: 0,
        pre_gengibre: 0,
        pre_embarazadas: 0,
        pre_palitos: 0,
        pre_soya: 0,
        pre_teriyaki: 0,
        id_mot: 0,
        verificado: 0,
        nombre: '',
        telefono: '',
        direccion: '',
        calle: '',
        num: '',
        depto: '',
        lat: 0,
        lng: 0,
        costo: 0,
        total: 0,
        eliminado: 0,
        ocultar: 0
    };
}
function nuevo(data){

    var obj = pedido_obj();
    obj.id_ped = data.id_ped;
    obj.pedido_code = data.pedido_code;
    obj.tipo = 1;
    obj.estado = 0;
    obj.despacho = data.despacho;
    obj.carro = data.carro;
    obj.promos = data.promos;
    obj.pre_wasabi = data.pre_wasabi;
    obj.pre_gengibre = data.pre_gengibre;
    obj.pre_embarazadas = data.pre_embarazadas;
    obj.pre_palitos = data.pre_palitos;
    obj.pre_soya = data.pre_soya;
    obj.pre_teriyaki = data.pre_teriyaki;
    obj.id_mot = data.id_mot;
    obj.verificado = data.verificado;
    
    obj.nombre = data.nombre;
    obj.telefono = data.telefono;

    obj.direccion = data.direccion;
    obj.calle = data.calle;
    obj.num = data.num;
    obj.depto = data.depto;
    obj.lat = data.lat;
    obj.lng = data.lng;

    add_pedido(obj);
    listar_pedidos();
    
}
function add_pedido(obj){
        
    var aux = [];
    aux.push(obj);
    if(pedidos !== null){
        for(var i=0, ilen=pedidos.length; i<ilen; i++){
            aux.push(pedidos[i]);
        }
    }
    seleccionado = 0;
    set_pedidos(aux);
    
}
function set_pedidos(pedidos){
    localStorage.setItem("pedidos", JSON.stringify(pedidos));
}
function get_pedidos_false(){
    return JSON.parse(localStorage.getItem("pedidos")) || false;
}
function get_pedidos(){
    return JSON.parse(localStorage.getItem("pedidos")) || get_pedido_blank();
}
function get_pedido_blank(){
    return [pedido_obj()];
}
function guardar_pedido(index){
        
    var pedidos = get_pedidos();
    var send = { pedido: JSON.stringify(pedidos[index]) };
    
    $.ajax({
        url: "ajax/set_pedido.php",
        type: "POST",
        data: send,
        success: function(data){
            
            var info = JSON.parse(data);
            if(pedidos[index].id_ped == 0){
                pedidos[index].id_ped = info.id_ped;
                pedidos[index].pedido_code = info.pedido_code;
                set_pedidos(pedidos);
            }
            listar_pedidos();
            
        }, error: function(e){
            console.log(e);
        }
    });
    
}
function change_despacho(that){
    var value = $(that).val();
    var t_despacho = $(that).parents('.data_info').find('.t_despacho');
    var t_repartidor = $(that).parents('.data_info').find('.t_repartidor');
    
    if(value == 0){
        t_despacho.hide();
        t_repartidor.hide();
    }
    if(value == 1){
        t_despacho.show();
        t_repartidor.show();
    }
}
function cambiar_estado(index, n, that){

    var pedidos = get_pedidos();
    var aux = parseInt(pedidos[index].estado) + n;

    if(aux >= 0 && aux < estados.length){
        
        pedidos[index].estado = aux;
        var Div = $(that).parents('.p_opciones').find('.p_nombre').html(estados[aux]);
        set_pedidos(pedidos);
        
        var data = { accion: 0, estado: estados[aux] };
        
        var send = { pedido_code: pedidos[index].pedido_code, estado: JSON.stringify(data) };
        $.ajax({
            url: "http://35.196.220.197/cambiar_estado",
            type: "POST",
            data: send,
            success: function(data){
                
            }, error: function(e){
                console.log(e);
            }
        });

    }

}
function cambiar_hora(index, n, that){
    
    var pedidos = get_pedidos();
    pedidos[index].fecha = pedidos[index].fecha + n*60;
    var fecha_ahora = Math.round(new Date().getTime()/1000);
    var tiempo = (pedidos[index].despacho == 1) ? tiempos.despacho : tiempos.retiro ;
    var diff = Math.round((pedidos[index].fecha + tiempo - fecha_ahora)/60);
    $(that).parents('.t_tiempo').find('.t_nombre').html(diff);
    set_pedidos(pedidos);
    
    var data = { accion: 1, fecha: pedidos[index].fecha };
        
    var send = { pedido_code: pedidos[index].pedido_code, estado: JSON.stringify(data) };
    $.ajax({
        url: "http://35.196.220.197/cambiar_estado",
        type: "POST",
        data: send,
        success: function(data){

        }, error: function(e){
            console.log(e);
        }
    });
    
}
function eliminar_pedido(){
    var pedidos = get_pedidos();
    pedidos[seleccionado].eliminado = 1;
    set_pedidos(pedidos);
    listar_pedidos();
    $('.p1').hide();
    $('.pop_up').hide();
}
function ocultar_pedido(){
    var pedidos = get_pedidos();
    pedidos[seleccionado].ocultar = 1;
    set_pedidos(pedidos);
    listar_pedidos();
    $('.p1').hide();
    $('.pop_up').hide();
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