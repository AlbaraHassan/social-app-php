$(document).ready(() => {
    $('#signup-form').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            username: {
                required: true,
                minlength: 4
            },
            password: {
                required: true,
                minlength: 6
            },
            repeat: {
                required: true,
                equalTo: '#password',
            }
        },
        messages: {
            email: {
                required: 'Email is required',
                email: 'Email is not valid'
            },
            username: {
                required: 'Username is required',
                minlength: 'User name must have at least 4 characters'
            },
            password: {
                required: 'Password is required',
                minlength: 'Password must have at least 6 characters'
            },
            repeat: {
                required: "Please repeat the password",
                equalTo: "Passwords do not match"
            }
        },
        submitHandler: (form, event) => {
            event.preventDefault();
            let email = $('#email').val().trim();
            let password = $('#password').val().trim();
            let username = $('#username').val().trim();

            let formData = {
                email,
                password,
                username
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
                    console.error({xhr, status, error});
                    const errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'Unknown error';
                    $('#error-message').text(errorMessage).show();
                    $('#success-message').hide();
                }
            });
        }
    })
})