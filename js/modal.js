// ========================================
// Modal System - MyStore
// ========================================

/**
 * Modal харуулах
 * @param {string} modalId - Modal-ийн ID
 */
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Scrolling унтраах
    }
}

/**
 * Modal хаах
 * @param {string} modalId - Modal-ийн ID
 */
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Scrolling дахин асаах
    }
}

/**
 * Modal гадна дарахад хаах
 */
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

/**
 * ESC товч дарахад хаах
 */
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (modal.style.display === 'block') {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    }
});

/**
 * Confirmation modal
 * @param {string} message - Харуулах мессеж
 * @param {function} callback - Батлах дарсан үед дуудах функц
 */
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Export функцүүд (хэрэв ES6 module ашиглавал)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { showModal, closeModal, confirmAction };
}