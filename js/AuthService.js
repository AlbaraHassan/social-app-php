$(document).ready(() => {
    const token = localStorage.getItem('token');
    if (token) {
        if(!location.href.includes('index')){
            location.replace('#home');
        }
        const user = JSON.parse(atob(token.split('.')[1]))
        localStorage.setItem('user', JSON.stringify(user))
        $('#user-email').text(user.name);
        $('#success-message').show();
    } else if ((!location.href.includes('login') && !location.href.includes('register')) && !token) {
        location.replace('#login');
    }

    $('.login-form').submit((event) => {
        event.preventDefault();
        let formData = {
            email: $('#email').val(),
            password: $('#password').val()
        };
        $.ajax({
            type: 'POST',
            url: '/api/auth/login',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: (response) => {
                localStorage.setItem('token', response.token);
                location.replace('#home');
            },
            error: (xhr, status, error) => {
                console.error({ xhr, status, error });
                const errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'Unknown error';
                $('#error-message').text(errorMessage).show();
                $('#success-message').hide();
            }
        });
    });

    $('.signup-form').submit((event) => {
        event.preventDefault();
        let formData = {
            email: $('#email').val(),
            password: $('#password').val(),
            username: $('#username').val(),
        };
        $.ajax({
            type: 'POST',
            url: '/api/auth/signup',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: (response) => {
                localStorage.setItem('token', response.token);
                location.replace('#home');
            },
            error: (xhr, status, error) => {
                console.error({ xhr, status, error });
                const errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'Unknown error';
                $('#error-message').text(errorMessage).show();
                $('#success-message').hide();
            }
        });
    });

});
