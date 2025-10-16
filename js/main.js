// Энгийн JavaScript функцүүд

// Modal харуулах
function showModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

// Modal нуух
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Modal гадна дарахад хаах
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}

// Форм validation
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Alert автоматаар арилгах
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000); // 5 секундын дараа арилна
    });
});

// Loading State Management
const LoadingState = {
    overlay: null,

    // Create loading overlay element
    createOverlay: function() {
        if (!this.overlay) {
            this.overlay = document.createElement('div');
            this.overlay.className = 'loading-overlay';
            this.overlay.setAttribute('role', 'status');
            this.overlay.setAttribute('aria-live', 'polite');
            this.overlay.innerHTML = `
                <div class="spinner" aria-hidden="true"></div>
                <div class="loading-text">Уншиж байна...</div>
            `;
            document.body.appendChild(this.overlay);
        }
        return this.overlay;
    },

    // Show loading overlay
    show: function(message = 'Уншиж байна...') {
        const overlay = this.createOverlay();
        const textElement = overlay.querySelector('.loading-text');
        if (textElement) {
            textElement.textContent = message;
        }
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    },

    // Hide loading overlay
    hide: function() {
        if (this.overlay) {
            this.overlay.style.display = 'none';
            document.body.style.overflow = '';
        }
    },

    // Add loading state to button
    buttonLoading: function(button, loading = true) {
        if (loading) {
            button.disabled = true;
            button.classList.add('btn-loading');
            button.setAttribute('aria-busy', 'true');
            button.dataset.originalText = button.textContent;
        } else {
            button.disabled = false;
            button.classList.remove('btn-loading');
            button.setAttribute('aria-busy', 'false');
            if (button.dataset.originalText) {
                button.textContent = button.dataset.originalText;
            }
        }
    }
};

// Auto-handle form submissions with loading states
document.addEventListener('DOMContentLoaded', function() {
    // Add loading state to forms with data-loading attribute
    const formsWithLoading = document.querySelectorAll('form[data-loading]');

    formsWithLoading.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
            const loadingMessage = form.getAttribute('data-loading') || 'Уншиж байна...';

            if (submitButton) {
                LoadingState.buttonLoading(submitButton, true);
            }

            // Show overlay if specified
            if (form.hasAttribute('data-loading-overlay')) {
                LoadingState.show(loadingMessage);
            }
        });
    });
});