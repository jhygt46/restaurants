$(document).ready(function(){

    $('#entrar').click(function(){
        
        $(this).prop( "disabled", true );
        
        var id = $('#id').val();
        var code = $('#code').val();
        var pass1 = $('#pass1').val();
        var pass2 = $('#pass2').val();
        
        $.ajax({
            url: "http://www.fireapp.cl/admin/ajax/login_back.php",
            type: "POST",
            data: "accion=resetpass&id="+id+"&code="+code+"&pass1="+pass1+"&pass2="+pass2,
            success: function(data){
                
                if(data.op == 1){
                    
                    $('.msg').html("Se ha creado su Contrase&ntilde;a con Exito");
                    $('#pass1').val("");
                    $('#pass2').val("");
                    setTimeout(function() {
                        $(location).attr('href',"http://www.fireapp.cl/admin/index.php?user="+data.user);
                    }, 2000);
                    
                }
                if(data.op == 2){
                    $('.msg').html(data.msg);
                    $(this).prop( "disabled", false );
                }

            },
            error: function(e){
                console.log(e);
                $(this).prop( "disabled", false );
            }
        });

    });

});
