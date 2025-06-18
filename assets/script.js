document.addEventListener('DOMContentLoaded', function () {
    // Enhanced form handling
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    if (loginForm && loginBtn) {
        loginForm.addEventListener('submit', function (e) {
            loginBtn.classList.add('loading');
            loginBtn.disabled = true;
        });
    }

    // Auto-focus on username field when page loads
    const usernameInput = document.getElementById('username');
    if (usernameInput) {
        usernameInput.focus();
    }

    // Enhanced input interactions
    document.querySelectorAll('.form-group input').forEach(input => {
        input.addEventListener('focus', function () {
            this.parentElement.parentElement.style.transform = 'translateY(-2px)';
            this.parentElement.parentElement.style.transition = 'transform 0.3s ease';
        });

        input.addEventListener('blur', function () {
            this.parentElement.parentElement.style.transform = 'translateY(0)';
        });

        // Real-time validation feedback
        input.addEventListener('input', function () {
            if (this.validity.valid) {
                this.style.borderColor = '#28a745';
            } else if (this.value.length > 0) {
                this.style.borderColor = '#dc3545';
            } else {
                this.style.borderColor = '#e1e5e9';
            }
        });
    });
});