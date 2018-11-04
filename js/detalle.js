$(document).ready(function(){
    render_items(carro);
});

function create_item(item){
    
    var producto = get_producto(item.id_pro);
    
    var Div = document.createElement('div');
    Div.className = 'producto';
    
    var Dnombre = document.createElement('div');
    Dnombre.className = 'nombre';
    Dnombre.innerHTML = producto.nombre;
    Div.appendChild(Dnombre);
    
    if(item.preguntas){
        for(var j=0, jlen=item.preguntas.length; j<jlen; j++){
            for(var k=0, klen=item.preguntas[j].valores.length; k<klen; k++){
                
                var Dpregunta = document.createElement('div');
                Dpregunta.className = 'pregunta';
                Dpregunta.innerHTML = item.preguntas[j].valores[k].nombre+": ";
                
                
                Dpregunta.innerHTML += item.preguntas[j].valores[k].seleccionados.join('/');
                
                
                Div.appendChild(Dpregunta);
                
            }
        }
    }

    return Div;
    
}

function render_items(carro){
    for(var i=0, ilen=carro.length; i<ilen; i++){
        $('#list_product').append(create_item(carro[i]));
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
