
carro.forEach(function(item_carro){
                
    var producto = get_producto(item_carro.id_pro);
    console.log(producto);

    var html = document.createElement('div');
    html.className = 'prod_item';
    html.innerHTML = "1.- Buena Nelson";

    $('#list_product').append(html);

});
function get_producto(id_pro){
    var productos = data.catalogos[catalogo].productos;
    for(var i=0, ilen=productos.length; i<ilen; i++){
        if(productos[i].id_pro == id_pro){
            return productos[i];
        }
    }
}


console.log("BUENA");
console.log(carro);