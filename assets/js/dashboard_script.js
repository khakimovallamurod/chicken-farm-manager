// Navigation
function showSection(sectionId) {
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => section.classList.remove('active'));
    document.getElementById(sectionId).classList.add('active');
    
    const navButtons = document.querySelectorAll('.nav-btn');
    navButtons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
}

function showModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function showAlert(message, type = 'success') {
    const alertContainer = document.getElementById('alertContainer');
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    
    alertContainer.appendChild(alert);
    
    setTimeout(() => {
        alert.remove();
    }, 3000);
}

function addGoshtTopshirish(event) {
    event.preventDefault();
    showAlert('Go\'sht topshirish muvaffaqiyatli qayd qilindi!');
    document.getElementById('goshtTopshirishForm').reset();
}

// Ta'minotchi functions
function contactSupplier(id) {
    showAlert(`Ta'minotchi #${id} bilan bog'lanish oynasi ochilmoqda...`, 'success');
}

function newOrder(id) {
    showAlert(`Ta'minotchi #${id}dan buyurtma berish oynasi ochilmoqda...`, 'success');
}
function addGoshtSotish(event) {
    event.preventDefault();
    showAlert('Go\'sht sotish muvaffaqiyatli qayd qilindi!');
    document.getElementById('goshtSotishForm').reset();
}

function addPulOlish(event) {
    event.preventDefault();
    showAlert('Pul olish muvaffaqiyatli qayd qilindi!');
    document.getElementById('pulOlishForm').reset();
}

function generateReport() {
    const reportType = document.getElementById('report_type').value;
    if (reportType) {
        showAlert(`${reportType.charAt(0).toUpperCase() + reportType.slice(1)} hisobot yaratilmoqda...`);
    }
}

function logout() {
    if (confirm('Tizimdan chiqishni xohlaysizmi?')) {
        showAlert('Tizimdan muvaffaqiyatli chiqdingiz!');
        // Redirect to login page
    }
}

// Set today's date as default for all date inputs
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        if (!input.value) {
            input.value = today;
        }
    });
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}
