function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye');
    const passwordConfirmInput = document.getElementById('password_confirmation');

    // Toggle visibility for password confirmation if it exists
    if (eyeIcon && passwordConfirmInput) {
        if (passwordConfirmInput.type === 'password') {
            passwordConfirmInput.type = 'text';
            eyeIcon.src = '/img/icon-eye-off.svg';
            eyeIcon.alt = 'Nascondi password';
        } else {
            passwordConfirmInput.type = 'password';
            eyeIcon.src = '/img/icon-eye.svg';
            eyeIcon.alt = 'Mostra password';
        }
    }

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