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

        // Get email and password from the form
        let email = $('#email').val().trim();
        let password = $('#password').val().trim();

        if (email === '' || password === '') {
            $('#error-message').text('Email and password are required').show();
            return;
        }

        let formData = {
            email: email,
            password: password
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

        let email = $('#email').val().trim();
        let password = $('#password').val().trim();
        let username = $('#username').val().trim();
        let passwordConfirmation = $('#repeat').val().trim();

        if (email === '' || password === '' || username === '' || passwordConfirmation === '') {
            $('#error-message').text('Email, password, and username are required').show();
            return;
        }

        else if(passwordConfirmation !== password){
            $('#error-message').text('Password Confirmation should match').show();
            return;
        }

        if (password.length < 6) {
            $('#error-message').text('Password must be at least 6 characters long').show();
            return;
        }

        let formData = {
            email: email,
            password: password,
            username: username
        };

        $.ajax({
            type: 'POST',
            url: '/api/auth/signup',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: (response) => {
                // Store token in localStorage
                localStorage.setItem('token', response.token);
                // Redirect to home page
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
