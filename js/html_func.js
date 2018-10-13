// HTML PROMOS //

function html_crear_categoria(obj){
    
    var Div = document.createElement('div');
    Div.className = 'botones_principales color_back_02';
    
    Div.style.backgroundImage = 'url("/images/categorias/'+obj.image+'")';
    Div.onclick = function(){ open_categoria(obj.id_cae) };
    
    var Divnombre = document.createElement('div');
    Divnombre.innerHTML = obj.nombre;
    Div.appendChild(Divnombre);
    
    var Divdescripcion = document.createElement('div');
    Divdescripcion.innerHTML = obj.descripcion;
    Div.appendChild(Divdescripcion);
    
    return Div;

}
function html_crear_promociones(obj){
    
    var Div = document.createElement('div');
    Div.className = 'botones_principales color_back_02';
    Div.innerHTML = obj.nombre;
    Div.onclick = function(){ open_promocion(obj.id_cae) };
    
    return Div;
    
}
function create_element_class(clase){
    var Div = document.createElement('div');
    Div.className = clase;
    return Div;
}
function create_element_class_value(clase, value){
    var Div = document.createElement('div');
    Div.className = clase;
    Div.innerHTML = value;
    return Div;
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
            pro_cat_item_nombre.innerHTML = producto.nombre;
            pro_cat_item.appendChild(pro_cat_item_nombre);

            html.appendChild(pro_cat_item);
            
        }
        
    }
    return html;
    
}
function html_preguntas_producto(carro, i){
    
    var html = document.createElement('div');
    html.className = 's_pregunta';
    html.setAttribute('data-pos', i);
    
    for(var k=0, klen=carro.preguntas.length; k<klen; k++){
        
        var e_pregunta = document.createElement('div');
        e_pregunta.className = 'e_pregunta';
        e_pregunta.setAttribute('data-pos', k);
        
        var pregunta_titulo = document.createElement('div');
        pregunta_titulo.className = 'pregunta_titulo';
        pregunta_titulo.innerHTML = carro.preguntas[k].nombre;
        e_pregunta.appendChild(pregunta_titulo);
        
        for(var m=0, mlen=carro.preguntas[k].valores.length; m<mlen; m++){
            
            var titulo_v_pregunta = document.createElement('div');
            titulo_v_pregunta.className = 'titulo_v_pregunta';
            titulo_v_pregunta.innerHTML = 'Seleccionar '+carro.preguntas[k].valores[m].cantidad;
            
            var v_pregunta = document.createElement('div');
            v_pregunta.className = 'v_pregunta';
            v_pregunta.setAttribute('data-pos', m);
            v_pregunta.setAttribute('data-cant', carro.preguntas[k].valores[m].cantidad);

            for(var n=0, nlen=carro.preguntas[k].valores[m].valores.length; n<nlen; n++){
                var n_pregunta = document.createElement('div');
                n_pregunta.className = 'n_pregunta';
                n_pregunta.innerHTML = carro.preguntas[k].valores[m].valores[n];
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
function html_paginas(){
    
    var li, id;
    var html = [];
    
    for(var i=0, ilen=data.paginas.length; i<ilen; i++){
        id = data.paginas[i].id_pag;
        li = document.createElement('LI');
        li.onclick = function(){ ver_pagina(id) };
        li.innerHTML = data.paginas[i].nombre;
        html.push(li);
        
    }
    return html;
    
}
function imprimir_promo_modal(categoria){
    
    var html = document.createElement('div');
    html.className = 'lista_promociones';
    html.onclick = function(){ add_carro_promocion(categoria.id_cae) };
            
    if(categoria.categorias){
        
        var catDiv = document.createElement('div');
        catDiv.className = 'promocion_categoria';
        
        var cattitDiv = document.createElement('div');
        cattitDiv.className = 'pro_titulo';
        cattitDiv.innerHTML = 'Elije:';
        
        catDiv.appendChild(cattitDiv);
        var itemDiv;

        for(var j=0, jlen=categoria.categorias.length; j<jlen; j++){
            
            itemDiv = document.createElement('div');
            itemDiv.className = 'item_pro_cat clearfix';
            
            var cantDiv = document.createElement('div');
            cantDiv.className = 'item_pro_cat_cant';
            cantDiv.innerHTML = categoria.categorias[j].cantidad;
            itemDiv.appendChild(cantDiv);
            
            var nomDiv = document.createElement('div');
            nomDiv.className = 'item_pro_cat_nom';
            nomDiv.innerHTML = get_categoria(categoria.categorias[j].id_cae).nombre;
            itemDiv.appendChild(nomDiv);
            catDiv.appendChild(itemDiv);
            
        }
        
        html.appendChild(catDiv);
    }
    if(categoria.productos){
        
        var proDiv = document.createElement('div');
        proDiv.className = 'promocion_producto';
        
        var protitDiv = document.createElement('div');
        protitDiv.className = 'pro_titulo';
        protitDiv.innerHTML = 'Productos';
        
        proDiv.appendChild(protitDiv);
        var itemDiv;
        
        for(var j=0, jlen=categoria.productos.length; j<jlen; j++){
            
            itemDiv = document.createElement('div');
            itemDiv.className = 'item_pro_pro';
            itemDiv.innerHTML = get_producto(categoria.productos[j].id_pro).nombre;
            proDiv.appendChild(itemDiv);
            
        }
        
        html.appendChild(proDiv);
    }

    //html += '<div class="promo_precio">$9.990</div></div>';
    return html;
    
}
function create_html_categorias(obj){
    
    var Div = document.createElement('div');
    Div.className = 'categoria';
    Div.onclick = function(){ open_categoria(obj.id_cae) };
    
    var Nombre = document.createElement('div');
    Nombre.className = 'nombre';
    Nombre.innerHTML = obj.nombre;
    Div.appendChild(Nombre);
    
    var Descripcion = document.createElement('div');
    Descripcion.className = 'descripcion';
    //Descripcion.innerHTML = obj.descripcion;
    Descripcion.innerHTML = 'descripcion de la categoria';
    Div.appendChild(Descripcion);
    
    if(obj.mostrar_prods == 1){
        
        var listado = document.createElement('div');
        listado.className = 'listado';
        var producto;
        var aux;
        
        if(obj.productos){
            for(var i=0, ilen=obj.productos.length; i<ilen; i++){

                aux = get_producto(obj.productos[i]);
                producto = document.createElement('div');
                producto.className = 'prod_item';
                producto.innerHTML = aux.nombre;
                producto.onclick = function(){ add_carro_producto(aux.id_pro) };

                listado.appendChild(producto);

            }
        }
        Div.appendChild(listado);
    }
    
    return Div;
    
}
function view_product(that){
    that.parents('.categoria').find('.info').show();
}
function create_html_producto(id){
    
    var aux = get_producto(id);

    var Div = document.createElement('div');
    Div.className = 'categoria';
    //Div.onclick = function(){ add_carro_producto(aux.id_pro) };
    
    var Nombre = document.createElement('div');
    Nombre.className = 'nombre';
    Nombre.innerHTML = aux.numero + '.- ' + aux.nombre;
    Nombre.onclick = function(){ view_product(this) };
    Div.appendChild(Nombre);
    
    var Info = document.createElement('div');
    Info.className = 'info';
    Info.innerHTML = 'info';
    Div.appendChild(Info);
    
    /*
    var Descripcion = document.createElement('div');
    Descripcion.className = 'descripcion';
    //Descripcion.innerHTML = obj.descripcion;
    Descripcion.innerHTML = 'descripcion de la promocion';
    Div.appendChild(Descripcion);
    */
    return Div;
    
}
function create_html_promocion(obj){
    
    var Div = document.createElement('div');
    Div.className = 'categoria';
    Div.onclick = function(){ open_categoria(obj.id_cae) };
    
    var Nombre = document.createElement('div');
    Nombre.className = 'nombre';
    Nombre.innerHTML = obj.nombre;
    Div.appendChild(Nombre);
    
    var Descripcion = document.createElement('div');
    Descripcion.className = 'descripcion';
    //Descripcion.innerHTML = obj.descripcion;
    Descripcion.innerHTML = obj.descripcion;
    Div.appendChild(Descripcion);
    
    if(obj.mostrar_prods == 1){
        
        var listado = document.createElement('div');
        listado.className = 'listado';
        var producto;
        var cat;
        var aux;
        if(obj.categorias){
            for(var i=0, ilen=obj.categorias.length; i<ilen; i++){
                aux = get_categoria(obj.categorias[i].id_cae);
                cat = document.createElement('div');
                cat.className = 'prod_item';
                cat.innerHTML = obj.categorias[i].cantidad + " " +aux.nombre;
                listado.appendChild(cat);
            }
        }
        if(obj.productos){
            for(var i=0, ilen=obj.productos.length; i<ilen; i++){
                aux = get_producto(obj.productos[i].id_pro);
                producto = document.createElement('div');
                producto.className = 'prod_item';
                producto.innerHTML = obj.productos[i].cantidad + " " +aux.nombre;
                listado.appendChild(producto);
            }
        }
        
        Div.appendChild(listado);
        
    }
    
    return Div;
    
}


function promo_carros(producto, j){
    
    var Div = document.createElement('div');
    Div.className = 'promo_detalle_item clearfix';
    
    var Nombre = document.createElement('div');
    Nombre.className = 'promo_detalle_nombre';
    Nombre.innerHTML = producto.nombre;
    Div.appendChild(Nombre);
    
    var Acciones = document.createElement('div');
    Acciones.className = 'promo_detalle_acciones clearfix';

    var Precio = document.createElement('div');
    Precio.className = 'precio';
    Precio.innerHTML = 3000;
    Acciones.appendChild(Precio);
    
    var Accion = document.createElement('div');
    Accion.className = 'accion';
    Accion.innerHTML = 'DEL';
    Accion.onclick = function(){ delete_pro_carro(j) };
    Acciones.appendChild(Accion);
    
    Div.appendChild(Acciones);
    return Div;
    
}

function promo_restantes(producto, j){
    
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
    Precio.innerHTML = 3000;
    Acciones.appendChild(Precio);
    
    var Accion = document.createElement('div');
    Accion.className = 'accion';
    Accion.innerHTML = 'DEL';
    Accion.onclick = function(){ delete_pro_carro(j) };
    Acciones.appendChild(Accion);
    
    Div.appendChild(Acciones);
    return Div;
    
}

function promo_nombre(promocion){
    
    var Div = document.createElement('div');
    Div.className = 'process_carro_promo';
    
    var Nombre = document.createElement('div');
    Nombre.className = 'promo_info';
    Nombre.innerHTML = promocion.nombre;
    Div.appendChild(Nombre);            
    
    return Div;
    
}