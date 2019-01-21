$(document).ready(function(){
    
    render_items(carro);
    
    var total_process = 0;
    for(var i=0, ilen=carro.length; i<ilen; i++){
        if(!carro[i].hasOwnProperty('promo')){
            total_process = total_process + parseInt(get_producto(carro[i].id_pro).precio);
            console.log("prod: "+get_producto(carro[i].id_pro).precio);
        }
    }
    for(var i=0, ilen=promos.length; i<ilen; i++){
        total_process = total_process + parseInt(get_categoria(promos[i].id_cae).precio);
        console.log("cate: "+get_categoria(promos[i].id_cae).precio);
    }
    
    
    console.log(carro);
    console.log(promos);
    
    console.log(costo);
    console.log(total_process);
    console.log(total);
    
   
    var diff = costo + total_process - total; 
    if(diff > 0){
        $('.verificar').append('<div>ERROR: TOTAL ENVIADO ES MENOR: ($'+diff+')</div>')
    }
    
    
});

function create_item(item){
    
    var producto = get_producto(item.id_pro);
    
    var Div = document.createElement('div');
    Div.className = 'producto';
    
    var Dnombre = document.createElement('div');
    Dnombre.className = 'nombre';
    Dnombre.innerHTML = producto.numero+".- "+producto.nombre;
    Div.appendChild(Dnombre);
    
    if(item.preguntas){
        for(var j=0, jlen=item.preguntas.length; j<jlen; j++){
            for(var k=0, klen=item.preguntas[j].valores.length; k<klen; k++){
                var Dpregunta = document.createElement('div');
                Dpregunta.className = 'pregunta';
                Dpregunta.innerHTML = item.preguntas[j].valores[k].nombre+": ";
                if(item.preguntas[j].valores[k].seleccionados !== undefined){
                    Dpregunta.innerHTML += item.preguntas[j].valores[k].seleccionados.join('/');
                }else{
                    Dpregunta.innerHTML += '<b>No Definido</b>';
                }
                Div.appendChild(Dpregunta);
            }
        }
    }

    return Div;
    
}

function render_items(carro){
    for(var i=0, ilen=carro.length; i<ilen; i++){
        if(!carro[i].hasOwnProperty('promo')){
            $('.lista_de_productos').append(create_item(carro[i]));
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
function get_categoria(id_cae){
    var categorias = data.catalogos[catalogo].categorias;
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].id_cae == id_cae){
            return categorias[i];
        }
    }
}