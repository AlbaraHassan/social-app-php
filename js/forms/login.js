$(document).ready(() => {
    $('.login-form').submit((event) => {
        event.preventDefault();

        let email = $('#email').val().trim();
        let password = $('#password').val().trim();


        let formData = {
            email,
            password
        };

        if(!validateForm(formData)){
            return
        }

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
    })


    const validateForm = ({email, password}) => {
        if (!email || !password) {
            if (!email) {
                $('#email-error-message').text('Email is required').show();
            } else {
                $('#email-error-message').text('').hide();

            }

            if (!password) {
                $('#password-error-message').text('Password is required').show()
            } else {
                $('#password-error-message').text('').hide();
            }
            return false
        } else {
            $('#email-error-message').text('').hide();
            $('#password-error-message').text('').hide();
        }
        return true
    }
})