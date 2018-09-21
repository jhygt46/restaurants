function process_multiple_promos(arr_id_prm, rest){

    var promos = [];
    var aux = {};
    var precio = 0;
    
    localStorage.setItem("carro_promos", null);
    
    for(var i=0, ilen=arr_id_prm.length; i<ilen; i++){
        aux = process_promos(arr_id_prm[i], rest);
        rest = aux.restantes;
        while(aux.is){
            precio = precio + aux.precio;
            promos.push({ id_prm: arr_id_prm[i], nombre: aux.nombre });
            aux = process_promos(arr_id_prm[i], rest);
            rest = aux.restantes;
        }
    }
    
    var rest = info_restantes(rest);
    return { promos: { precio: precio, promociones: promos}, restantes: { precio: rest.precio, restantes: rest.restantes} };

}
function is_promo(promo){
    
    var ocupados = [];
    var carro = get_carro();
    
    for(var j=0, jlen=promo.productos.length; j<jlen; j++){
        for(var k=0, klen=carro.length; k<klen; k++){
            if(promo.productos[j].id_pro.indexOf(carro[k].id_pro) != -1 && ocupados.indexOf(k) == -1){
                ocupados.push(k);
                break;
            }
        }
    }

    if(ocupados.length == promo.productos.length){
        return true;
    }else{
        return false;
    }
    
}
function process_promos(id_prm, restantes){
    
    var ocupados = [];
    var carro = restantes;
    var promos = process_promo();
    var precio_produtos = 0;
    var aux = {is: false, id_prm: 0, nombre: '', precio: 0, restantes: []};
    for(var i=0, ilen=promos.length; i<ilen; i++){
        if(promos[i].id_prm == id_prm){
            aux.precio = 2900;
            aux.id_prm = promos[i].id_prm;
            aux.nombre = promos[i].nombre;
            for(var j=0, jlen=promos[i].productos.length; j<jlen; j++){
                for(var k=0, klen=carro.length; k<klen; k++){
                    if(promos[i].productos[j].id_pro.indexOf(carro[k].id_pro) != -1 && ocupados.indexOf(k) == -1){
                        ocupados.push(k);
                        precio_produtos = precio_produtos + 990;
                        break;
                    }
                }
            }
            if(ocupados.length == promos[i].productos.length){
                aux.is = true;
                
                var carro_promos = get_carro_promos();
                var pos = carro_promos.length;
                carro_promos.push({id_prm: id_prm});
                localStorage.setItem("carro_promos", JSON.stringify(carro_promos));
                
                for(var k=0, klen=carro.length; k<klen; k++){
                    if(ocupados.indexOf(k) == -1){
                        aux.restantes.push({id_pro: carro[k].id_pro, pos: k });
                    }
                    if(ocupados.indexOf(k) != -1){
                        carro[k].promo = pos;
                    }
                }
                
                localStorage.setItem("carro", JSON.stringify(carro));
                if(precio_produtos < aux.precio){
                    aux.precio = precio_produtos;
                    aux.alert = 1;
                }
            }else{
                aux.restantes = restantes;
            }
        }
    }
    return aux;
    
}
function display_carro(info){
    
    $('.modals, .modal_carro').show();
    modal = 1;
    
    $('.modal_carro .carro_inicio').html("");
    var precio = 0;
    var elementos = false;
    
    if(info.promos){
        precio = precio + info.promos.precio;
        for(var i=0, ilen=info.promos.promociones.length; i<ilen; i++){
            $('.modal_carro .carro_inicio').append('<div>'+info.promos.promociones[i].nombre+'</div>');
            elementos = true;
        }
    }
    if(info.restantes){
        precio = precio + info.restantes.precio;
        for(var i=0, ilen=info.restantes.restantes.length; i<ilen; i++){
            $('.modal_carro .carro_inicio').append(html_productos_carro(info.restantes.restantes[i].id_pro));
            elementos = true;
        }
    }
    
    if(!elementos){
        //console.log("CARRO VACIO");
    }
    if(elementos){
        //console.log("CARRO CON PRODUCTOS");
    }
    
    
    
}
function info_restantes(rest){

    var producto = {};
    var restantes = [];
    var precio = 0;
    
    for(var i=0, ilen=rest.length; i<ilen; i++){
        producto = get_producto(rest[i].id_pro);
        precio = precio + 990;
        restantes.push({id_pro: producto.id_pro, nombre: producto.nombre});
    }
    return { precio: precio, restantes: restantes }
    
}