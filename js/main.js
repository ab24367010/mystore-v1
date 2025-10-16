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