$(document).ready(() => {
    const token = localStorage.getItem('token');
    if (token) {
        if(!location.href.includes('index')){
            location.replace('/index.html');
        }
        const user = JSON.parse(atob(token.split('.')[1]))
        localStorage.setItem('user', JSON.stringify(user))
        $('#user-email').text(user.name);
        $('#success-message').show();
    } else if (!location.href.includes('login') && !token) {
        location.replace('/login.html');
    }

    $('.login-form').submit((event) => {
        event.preventDefault();
        let formData = {
            email: $('#email').val(),
            password: $('#password').val()
        };
        $.ajax({
            type: 'POST',
            url: '/api/login',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: (response) => {
                localStorage.setItem('token', response.token);
                location.replace('/index.html');
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
