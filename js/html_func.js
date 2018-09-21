// HTML PROMOS //
function html_promos(obj){
    
    var aux = html.promos[0];
    aux = aux.replace("##id_prm##", obj.id_prm);
    aux = aux.replace("##nombre##", obj.nombre);
    aux = aux.replace("##descripcion##", "Buena Nelson");
    aux = aux.replace("##style##", ""+Math.floor((Math.random() * 3) + 1)+"");
    return aux;
    
}
function html_categorias(obj){
    
    var aux = html.categorias[0];
    aux = aux.replace("##id_cae##", obj.id_cae);
    aux = aux.replace("##nombre##", obj.nombre);
    aux = aux.replace("##descripcion##", "Buena Nelson");
    //aux = aux.replace("##style##", ""+Math.floor((Math.random() * 3) + 1)+"");
    return aux;
    
}
function html_productos(id_pro){
    
    var producto = get_producto(id_pro);
    var aux = html.productos[0];
    
    aux = aux.replace("##id_pro##", producto.id_pro);
    aux = aux.replace("##nombre##", producto.nombre);
    aux = aux.replace("##descripcion##", "Descripcion del Producto");
    //aux = aux.replace("##style##", ""+Math.floor((Math.random() * 3) + 1)+"");
    return aux;
    
}
function html_crear_categoria(obj){
    
    var aux = html.crear_categoria[0];
    aux = aux.replace("##id_cae##", obj.id_cae);
    aux = aux.replace("##nombre##", obj.nombre);
    return aux;

}
function html_crear_promociones(obj){
    
    var aux = html.crear_promocion[0];
    aux = aux.replace("##id_prm##", obj.id_prm);
    aux = aux.replace("##nombre##", obj.nombre);
    return aux;
    
}
