$(document).ready(() => {
    const token = localStorage.getItem('token');
    if (token) {
        if (!location.href.includes('thread')) {
            location.replace('#home');
        }
        const user = JSON.parse(atob(token.split('.')[1]))
        localStorage.setItem('user', JSON.stringify(user))
        $('#user-email').text(user.name);
    } else if ((!location.href.includes('login') && !location.href.includes('register')) && !token) {
        location.replace('#login');
    }
});
