// Navigation
        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => section.classList.remove('active'));
            
            // Show selected section
            document.getElementById(sectionId).classList.add('active');
            
            // Update active nav button
            const navButtons = document.querySelectorAll('.nav-btn');
            navButtons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        }

        // Modal functions
        function showModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Alert function
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

        // Form submissions
        function addKatak(event) {
            event.preventDefault();
            showAlert('Yangi katak muvaffaqiyatli yaratildi!');
            closeModal('katakModal');
            document.getElementById('katakForm').reset();
        }

        function addJoja(event) {
            event.preventDefault();
            showAlert('Jo\'jalar muvaffaqiyatli qo\'shildi!');
            document.getElementById('jojaForm').reset();
        }

        function addYem(event) {
            event.preventDefault();
            showAlert('Yem berish muvaffaqiyatli qayd qilindi!');
            document.getElementById('yemForm').reset();
        }

        function addOlganJoja(event) {
            event.preventDefault();
            showAlert('O\'lgan jo\'jalar muvaffaqiyatli ayirildi!', 'error');
            document.getElementById('olganJojaForm').reset();
        }

        function addGoshtTopshirish(event) {
            event.preventDefault();
            showAlert('Go\'sht topshirish muvaffaqiyatli qayd qilindi!');
            document.getElementById('goshtTopshirishForm').reset();
        }

        function addHarajat(event) {
            event.preventDefault();
            showAlert('Harajat muvaffaqiyatli qo\'shildi!', 'error');
            document.getElementById('harajatForm').reset();
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