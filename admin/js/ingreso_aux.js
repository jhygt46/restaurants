function bien(msg){
                
    $('.msg').html(msg);
    $('.msg').css("color", "#666");    
    $('#user').css("border-color", "#ccc");
    $('#pass').css("border-color", "#ccc");
    $('#user').css("background-color", "#fcfcfc");
    $('#pass').css("background-color", "#fcfcfc");

}
function mal(msg){   
    
    $('#pass').val("");
    $('.msg').html(msg);
    $('.msg').css("color", "#E34A25");
    $('#user').css("border-color", "#E34A25");
    $('#pass').css("border-color", "#E34A25");
    $('#user').css("background-color", "#FCEFEB");
    $('#pass').css("background-color", "#FCEFEB");
    login1();
    login2();
    login3();
    login2();
    login3();
    login2();
    login3();
    login4();
    
}
function login1(){
    $(".login").animate({
        'padding-left': '+=15px'
    }, 200);
}
function login2(){
    $(".login").animate({
        'padding-left': '-=30px'
    }, 200);
}
function login3(){
    $(".login").animate({
        'padding-left': '+=30px'
    }, 200);
}
function login4(){
    $(".login").animate({
        'padding-left': '-=15px'
    }, 200);
}