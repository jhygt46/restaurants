$(document).ready(function(){
    render_items(carro);
});


function render_items(carro){
    
    var html = "";
    for(var i=0, ilen=carro.length; i<ilen; i++){
    
        var producto = get_producto(carro[i].id_pro);
        html += "<div>"+producto.nombre+"</div>";
    
    }

    $('#list_product').html(html);
    
}

function get_producto(id_pro){
    var productos = data.catalogos[catalogo].productos;
    for(var i=0, ilen=productos.length; i<ilen; i++){
        if(productos[i].id_pro == id_pro){
            return productos[i];
        }
    }
}
