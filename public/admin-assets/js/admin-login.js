/**
 * EduCore School Management System
 * Admin Authentication & Login Interface Module
 */
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('toggle-password-btn');
    const eyeIcon = document.getElementById('eye-icon');
    const eyeSlashIcon = document.getElementById('eye-slash-icon');
    const quickFillBtn = document.getElementById('btn-quick-fill');
    const loginInput = document.getElementById('login') || document.getElementById('email');
    const loginForm = document.getElementById('admin-login-form');
    const submitBtn = document.getElementById('btn-submit-login');
    const submitBtnText = document.getElementById('btn-submit-text');
    const submitBtnSpinner = document.getElementById('btn-submit-spinner');

    // 1. Password Visibility Toggle
    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            togglePasswordBtn.setAttribute('aria-pressed', isPassword ? 'true' : 'false');

            if (eyeIcon && eyeSlashIcon) {
                if (isPassword) {
                    eyeIcon.classList.add('hidden');
                    eyeSlashIcon.classList.remove('hidden');
                } else {
                    eyeIcon.classList.remove('hidden');
                    eyeSlashIcon.classList.add('hidden');
                }
            }
        });
    }

    // 2. Demo & Local Quick Fill Helper
    if (quickFillBtn && loginInput && passwordInput) {
        quickFillBtn.addEventListener('click', function () {
            loginInput.value = 'admin';
            passwordInput.value = 'password';

            loginInput.dispatchEvent(new Event('input', { bubbles: true }));
            passwordInput.dispatchEvent(new Event('input', { bubbles: true }));

            // Visual feedback on the quick fill chip
            const originalHtml = quickFillBtn.innerHTML;
            quickFillBtn.innerHTML = `
                <svg class="w-3.5 h-3.5 text-emerald-600 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Credentials Loaded</span>
            `;
            quickFillBtn.classList.add('bg-emerald-50', 'text-emerald-800', 'border-emerald-200');

            setTimeout(() => {
                quickFillBtn.innerHTML = originalHtml;
                quickFillBtn.classList.remove('bg-emerald-50', 'text-emerald-800', 'border-emerald-200');
            }, 1800);
        });
    }

    // 3. Clean Submit Button Feedback
    if (loginForm && submitBtn) {
        loginForm.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
            if (submitBtnText) {
                submitBtnText.textContent = 'Signing in...';
            }
            if (submitBtnSpinner) {
                submitBtnSpinner.classList.remove('hidden');
            }
        });
    }
});
