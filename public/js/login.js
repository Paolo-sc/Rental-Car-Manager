function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye');

    if (eyeIcon && passwordInput) {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.src = '/img/icon-eye-off.svg';
            eyeIcon.alt = 'Nascondi password';
        } else {
            passwordInput.type = 'password';
            eyeIcon.src = '/img/icon-eye.svg';
            eyeIcon.alt = 'Mostra password';
        }
    }
}