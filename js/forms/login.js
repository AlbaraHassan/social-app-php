$(document).ready(() => {

    $('#login-form').validate({
        errorElement:'small',
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true
            },
        },
        messages: {
            email: 'Email is required',
            password: 'Password is required',
        },
        submitHandler: (form, event) => {
            event.preventDefault();
            console.log({form})
            let email = $('#email').val().trim();
            let password = $('#password').val().trim();


            let formData = {
                email,
                password
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
                    console.error({xhr, status, error});
                    const errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'Unknown error';
                    $('#error-message').text(errorMessage).show();
                    $('#success-message').hide();
                }
            });
        }

    })
})