$(document).ready(function() {

    $("main#spapp > section").width($(document).width());

    var app = $.spapp({pageNotFound : 'error_404'});

    app.route({
        view: "login",
        load: "../login.html",
        onCreate: ()=>{
            console.log('yaaas')}
    });
    app.route({
        view: "register",
        load: "../register.html",
    });

    app.route({
        view: "home",
        load: "../home.html",
    });


    app.run();

});