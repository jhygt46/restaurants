// HTML PROMOS //

function html_crear_categoria(obj){
    
    var Div = document.createElement('div');
    Div.className = 'botones_principales color_back_02';
    Div.innerHTML = obj.nombre;
    Div.onclick = function(){ open_categoria(obj.id_cae) };
    
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
function create_html_producto(id){
    
    var aux = get_producto(id);
    
    var Div = document.createElement('div');
    Div.className = 'categoria';
    Div.onclick = function(){ add_carro_producto(aux.id_pro) };
    
    var Nombre = document.createElement('div');
    Nombre.className = 'nombre';
    Nombre.innerHTML = aux.nombre;
    Div.appendChild(Nombre);
    
    var Descripcion = document.createElement('div');
    Descripcion.className = 'descripcion';
    //Descripcion.innerHTML = obj.descripcion;
    Descripcion.innerHTML = 'descripcion de la promocion';
    Div.appendChild(Descripcion);
    
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
    Descripcion.innerHTML = 'descripcion de la promocion';
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
    Precio.innerHTML = producto.precio;
    Acciones.appendChild(Precio);
    
    var Accion = document.createElement('div');
    Accion.className = 'accion';
    Accion.innerHTML = 'X';
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
    Precio.innerHTML = producto.precio;
    Acciones.appendChild(Precio);
    
    var Accion = document.createElement('div');
    Accion.className = 'accion';
    Accion.innerHTML = 'X';
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