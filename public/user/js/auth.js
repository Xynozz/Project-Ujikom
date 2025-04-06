document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle
    const togglePassword = document.querySelector('.toggle-password');
    const passwordInput = document.querySelector('#password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('bx-hide');
            this.classList.toggle('bx-show');
        });
    }

    // Form validation
    const authForm = document.querySelector('.auth-form');
    if (authForm) {
        authForm.addEventListener('submit', function(e) {
            const email = document.querySelector('#email').value;
            const password = document.querySelector('#password').value;

            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
            }
        });
    }
});