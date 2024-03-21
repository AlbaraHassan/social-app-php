$(document).ready(() => {
    $('.signup-form').submit((event) => {
        event.preventDefault();

        let email = $('#email').val().trim();
        let password = $('#password').val().trim();
        let username = $('#username').val().trim();
        let passwordConfirmation = $('#repeat').val().trim();

        let formData = {
            email,
            password,
            username
        };

        if (!validateForm({...formData, passwordConfirmation})) {
            return
        }

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
                console.error({xhr, status, error});
                const errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'Unknown error';
                $('#error-message').text(errorMessage).show();
                $('#success-message').hide();
            }
        });
    })

    const validateForm = ({email, password, username, passwordConfirmation}) => {
        if (!email || !password || !username || !passwordConfirmation) {
            console.log('yyaasass')
            if (!email) {
                $('#email-error-message').text('Email is required').show();
            } else {
                $('#email-error-message').text('').hide();
            }

            if (!username) {
                $('#username-error-message').text('Username is required').show();
            } else {
                $('#username-error-message').text('').hide();
            }

            if (!password) {
                $('#password-error-message').text('Password is required').show();
            } else {
                $('#password-error-message').text('').hide();
            }
            if (!passwordConfirmation) {
                $('#repeat-error-message').text('Repeat the password').show();
            } else {
                $('#repeat-error-message').text('').hide();
            }
            return false;
        }

        if (passwordConfirmation !== password && passwordConfirmation && password) {
            $('#repeat-error-message').text('Password Confirmation should match').show();
            return false;
        } else {
            $('#repeat-error-message').text('').hide();

        }

        if (password.length < 6) {
            $('#password-error-message').text('Password must be at least 6 characters long').show();
            return false;
        } else {
            $('#password-error-message').text('').hide();
        }

        return true
    }
})