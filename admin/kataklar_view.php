<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kataklar ro'yxati</title>
    <link rel="stylesheet" href="../assets/css/katak_view_style.css">
    <style>
        .money-info {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 14px;
        margin-top: 10px;
        }
        .positive{border-left:4px solid #16a34a}
        .accent{border-left:4px solid #0ea5e9}
        .negative{border-left:4px solid #ef4444}
        .neutral{border-left:4px solid #7c3aed}
        @media(max-width:420px){.info-value{font-size:18px}.info-item{padding:12px;min-height:72px}}
    </style>
</head>
<body>

<section id="kataklist" class="content-section">
    <div class="section-header">
        <h2 class="section-title">🏠 Kataklar ro'yxati</h2>
        <button class="btn btn-primary" onclick="showModal('katakModal')">
            ➕ Yangi katak yaratish
        </button>
    </div>
    
    <div id="katakviewcn">
        
    </div>
</section>

<!-- Yangi katak qo'shish modali -->
<div id="katakModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('katakModal')">&times;</span>
        <h2>🏠 Yangi katak yaratish</h2>
        <form id="katakForm" onsubmit="return addKatak(event)">
            <div class="form-group">
                <label>Katak nomi:</label>
                <input type="text" id="katak_nomi" required placeholder="Masalan: Katak #5">
            </div>
            <div class="form-group">
                <label>Sig'imi (maksimal jo'jalar soni):</label>
                <input type="text" id="katak_sigimi" required min="1" placeholder="Masalan: 500">
            </div>
            <div class="form-group">
                <label>Izoh:</label>
                <textarea id="katak_izoh" rows="3" placeholder="Katak haqida qo'shimcha ma'lumot..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">✅ Katak yaratish</button>
        </form>
    </div>
</div>

<!-- Tahrirlash modali -->
<div id="editKatakModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editKatakModal')">&times;</span>
        <h2>✏️ Katakni tahrirlash</h2>
        <form id="editKatakForm" onsubmit="return updateKatak(event)">
            <input type="hidden" id="edit_katak_id">
            <div class="form-group">
                <label>Katak nomi:</label>
                <input type="text" id="edit_katak_nomi" required placeholder="Masalan: Katak #5">
            </div>
            <div class="form-group">
                <label>Sig'imi (maksimal jo'jalar soni):</label>
                <input type="number" id="edit_katak_sigimi" required min="1" placeholder="Masalan: 500">
            </div>
            <div class="form-group">
                <label>Izoh:</label>
                <textarea id="edit_katak_izoh" rows="3" placeholder="Katak haqida qo'shimcha ma'lumot..."></textarea>
            </div>
            <div class="form-group checkbox-group">
                <input type="checkbox" id="edit_katak_status">
                <label for="edit_katak_status">Faol katak</label>
            </div>
            <button type="submit" class="btn btn-primary">💾 O'zgarishlarni saqlash</button>
        </form>
    </div>
</div>

<!-- O'chirish tasdiqlash modali -->
<div id="confirmDeleteModal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <span class="close" onclick="closeModal('confirmDeleteModal')">&times;</span>
        <h2>🗑️ Katakni o'chirish</h2>
        <p></p> <!-- Bu yerda savol ko'rsatiladi -->
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeModal('confirmDeleteModal')">❌ Bekor qilish</button>
            <button class="btn btn-danger" onclick="confirmDelete()">✅ Ha, o'chirish</button>
        </div>
    </div>
</div>

<!-- Jo'ja tarixi modali -->
<div id="jojaTarixiModal" class="modal">
    <div class="modal-content modal-large">
        <span class="close" onclick="closeModal('jojaTarixiModal')">&times;</span>
        <h2 id="jojaTarixiTitle">📊 Jo'ja tarixi</h2>
        <div class="table-container">
            <table class="data-table" id="jojaTarixiTable">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Sana</th>
                        <th>Jo'ja soni</th>
                        <th>Narxi (so'm)</th>
                        <th>Jami summa</th>
                        <th>Izoh</th>
                    </tr>
                </thead>
                <tbody id="jojaTarixiTableBody">
                    <!-- Ma'lumotlar JavaScript orqali yuklanadi -->
                </tbody>
            </table>
            <div id="jojaTarixiLoading" class="loading">Ma'lumotlar yuklanmoqda...</div>
            <div id="jojaTarixiEmpty" class="empty-message" style="display: none;">
                Hech qanday jo'ja tarixi topilmadi
            </div>
        </div>
    </div>
</div>

<!-- Yem berish tarixi modali -->
<div id="yemTarixiModal" class="modal">
    <div class="modal-content modal-large">
        <span class="close" onclick="closeModal('yemTarixiModal')">&times;</span>
        <h2 id="yemTarixiTitle">🌾 Yem berish tarixi</h2>
        <div class="table-container">
            <table class="data-table" id="yemTarixiTable">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Sana</th>
                        <th>Yem turi</th>
                        <th>Miqdori (kg)</th>
                        <th>Narxi (so'm/kg)</th>
                        <th>Jami summa</th>
                        <th>Izoh</th>
                    </tr>
                </thead>
                <tbody id="yemTarixiTableBody">
                    <!-- Ma'lumotlar JavaScript orqali yuklanadi -->
                </tbody>
            </table>
            <div id="yemTarixiLoading" class="loading">Ma'lumotlar yuklanmoqda...</div>
            <div id="yemTarixiEmpty" class="empty-message" style="display: none;">
                Hech qanday yem berish tarixi topilmadi
            </div>
        </div>
    </div>
</div>
<!-- O'lgan jo'jalar tarixi modali -->
<div id="olganTarixiModal" class="modal">
    <div class="modal-content modal-large">
        <span class="close" onclick="closeModal('olganTarixiModal')">&times;</span>
        <h2 id="olganTarixiTitle">💀 O'lgan jo'jalar tarixi</h2>
        <div class="table-container">
            <table class="data-table" id="olganTarixiTable">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Sana</th>
                        <th>O'lgan jo'jalar soni</th>
                        <th>Narxi (so'm)</th>
                        <th>Summa (so'm)</th>
                        <th>Izoh</th>
                    </tr>
                </thead>
                <tbody id="olganTarixiTableBody">
                    <!-- Ma'lumotlar JavaScript orqali yuklanadi -->
                </tbody>
            </table>
            <div id="olganTarixiLoading" class="loading">Ma'lumotlar yuklanmoqda...</div>
            <div id="olganTarixiEmpty" class="empty-message" style="display: none;">
                Hech qanday o'lgan jo'jalar tarixi topilmadi
            </div>
        </div>
    </div>
</div>
<script src="https://unpkg.com/imask"></script>

<script>
    const capacityInput = document.getElementById('katak_sigimi');
    IMask(capacityInput, {
        mask: Number,
        min: 0,
        max: 1000000,
        thousandsSeparator: ' '
    });

    // Asosiy funksiyalar
    function addKatak(event) {
        event.preventDefault();        
        const nomi = document.getElementById("katak_nomi").value;
        const sigimi = document.getElementById("katak_sigimi").value;
        const izoh = document.getElementById("katak_izoh").value;

        const formData = new FormData();
        formData.append("katak_nomi", nomi);
        formData.append("katak_sigimi", sigimi);
        formData.append("katak_izoh", izoh);
        
        fetch("../kataklar/insert_katak.php", {
            method: "POST",
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === "success") {
                showAlert("✅ " + data.message, 'success');
                document.getElementById("katakForm").reset();
                closeModal("katakModal");
                loadKatakView();
            } else {
                showAlert("❌ " + data.message, 'error');
            }
        })
        .catch(error => {
            console.error("Xatolik:", error);
            showAlert("❌ So'rov yuborishda xatolik yuz berdi: " + error.message, 'error');
        });
    }

    function showModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
        document.body.style.overflow = 'hidden'; // Scroll ni to'xtatish
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.body.style.overflow = 'auto'; // Scroll ni qaytarish
    }

    // Delete funksiyalari
    let deleteModal = document.getElementById('confirmDeleteModal');
    let currentKatakId = null;

    function deleteKatak(button) {
        const katakCard = button.closest('.katak-card');
        currentKatakId = parseInt(katakCard.dataset.id); 
        const katakNomi = katakCard.querySelector('.katak-title').textContent;
        
        deleteModal.querySelector('.modal-content p').textContent = 
            `Rostan ham "${katakNomi}" katakni o'chirmoqchimisiz?`;
        
        deleteModal.style.display = 'block';
    }

    function confirmDelete() {
        if (!currentKatakId) {
            showAlert('Katak tanlanmagan', 'error');
            return;
        }
        const formData = new FormData();
        formData.append('id', currentKatakId);
        fetch('../kataklar/katak_delete.php', {
            method: 'POST',
            body: formData 
        })
        .then(response => {
            if (!response.ok) throw new Error('Server javob bermadi');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const element = document.querySelector(`.katak-card[data-id="${currentKatakId}"]`);
                if (element) {
                    element.style.animation = 'fadeOut 0.5s ease-out';
                    setTimeout(() => element.remove(), 500);
                }
                deleteModal.style.display = 'none';
                showAlert(data.message, 'success');
            } else {
                throw new Error(data.message || 'Noma\'lum xatolik yuz berdi');
            }
        })
        .catch(error => {
            console.error('Xatolik:', error);
            showAlert('O\'chirishda xatolik: ' + error.message, 'error');
        });
    }

    // Edit funksiyalari
    function editKatak(button) {
        const katakCard = button.closest('.katak-card');
        const katakId = katakCard.dataset.id;
        const katakTitle = katakCard.querySelector('.katak-title').textContent;
        const katakComment = katakCard.querySelector('p').textContent;
        
        // Sig'imni olish
        let katakCapacity;
        if (katakCard.classList.contains('inactive')) {
            katakCapacity = katakCard.querySelector('.info-item:nth-child(3) .info-value').textContent;
        } else {
            const titleParts = katakTitle.split('/');
            katakCapacity = titleParts[1] || '';
        }
        
        document.getElementById('edit_katak_id').value = katakId;
        document.getElementById('edit_katak_nomi').value = katakTitle.split('/')[0];
        document.getElementById('edit_katak_sigimi').value = katakCapacity;
        document.getElementById('edit_katak_izoh').value = katakComment;
        document.getElementById('edit_katak_status').checked = !katakCard.classList.contains('inactive');
        
        showModal('editKatakModal');
    }

    function updateKatak(event) {
        event.preventDefault();
        
        const katakId = document.getElementById('edit_katak_id').value;
        const formData = new FormData();
        formData.append('id', katakId);
        formData.append('nomi', document.getElementById('edit_katak_nomi').value);
        formData.append('sigimi', document.getElementById('edit_katak_sigimi').value);
        formData.append('izoh', document.getElementById('edit_katak_izoh').value);
        formData.append('status', document.getElementById('edit_katak_status').checked ? 'active' : 'inactive');
        
        fetch('../kataklar/katak_update.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error('Server javob bermadi');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                closeModal('editKatakModal');
                showAlert('Katak muvaffaqiyatli yangilandi', 'success');
                loadKatakView();
            } else {
                showAlert('Katakni yangilashda xatolik: ' + (data.message || 'Noma\'lum xatolik'), 'error');
            }
        })
        .catch(error => {
            console.error('Update xatolik:', error);
            showAlert('Server xatosi: ' + error.message, 'error');
        });
    }

    function showAlert(message, type = 'success') {
        const existingAlerts = document.querySelectorAll('.alert-modal');
        existingAlerts.forEach(alert => alert.remove());

        const alertModal = document.createElement('div');
        alertModal.className = `alert-modal alert-${type}`;
        alertModal.innerHTML = `
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 20px;">${type === 'success' ? '✅' : '❌'}</span>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" style="margin-left: auto; background: none; border: none; color: white; font-size: 18px; cursor: pointer;">×</button>
            </div>
        `;
        document.body.appendChild(alertModal);
        setTimeout(() => {
            if (alertModal.parentNode) {
                alertModal.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => alertModal.remove(), 300);
            }
        }, 5000);
    }

    function showJojaTarixi(katakId, katakNomi) {
        document.getElementById('jojaTarixiTitle').textContent = `📊 Jo'ja tarixi - ${katakNomi}`;
        document.getElementById('jojaTarixiLoading').style.display = 'block';
        document.getElementById('jojaTarixiEmpty').style.display = 'none';
        document.getElementById('jojaTarixiTableBody').innerHTML = '';

        showModal('jojaTarixiModal');

        fetch('../api/get_joja_tarixi.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ katak_id: katakId })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('jojaTarixiLoading').style.display = 'none';

            if (data.success && data.data.length > 0) {
                let html = '';
                let totalJoja = 0;
                let totalSumma = 0;

                data.data.forEach((item, index) => {
                    const jamiSumma = item.summa;
                    totalJoja += parseInt(item.soni);
                    totalSumma += parseFloat(jamiSumma);

                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${formatDate(item.sana)}</td>
                            <td><strong>${item.soni}</strong></td>
                            <td>${formatMoney(item.narxi)}</td>
                            <td><strong>${formatMoney(jamiSumma)}</strong></td>
                            <td>${item.izoh || '-'}</td>
                        </tr>
                    `;
                });

                html += `
                    <tr style="background-color: #e3f2fd; font-weight: bold;">
                        <td colspan="2">JAMI:</td>
                        <td><strong>${totalJoja}</strong></td>
                        <td>-</td>
                        <td><strong>${formatMoney(totalSumma)}</strong></td>
                        <td>-</td>
                    </tr>
                `;

                document.getElementById('jojaTarixiTableBody').innerHTML = html;
            } else {
                document.getElementById('jojaTarixiEmpty').style.display = 'block';
            }
        })
        .catch(error => {
            document.getElementById('jojaTarixiLoading').style.display = 'none';
            document.getElementById('jojaTarixiEmpty').style.display = 'block';
            console.error('Xatolik:', error);
            showAlert('Ma\'lumotlarni yuklashda xatolik yuz berdi', 'error');
        });
    }


    function showYemTarixi(katakId, katakNomi) {
        document.getElementById('yemTarixiTitle').textContent = `🌾 Yem berish tarixi - ${katakNomi}`;
        document.getElementById('yemTarixiLoading').style.display = 'block';
        document.getElementById('yemTarixiEmpty').style.display = 'none';
        document.getElementById('yemTarixiTableBody').innerHTML = '';
        
        showModal('yemTarixiModal');
        fetch('../api/get_yem_tarixi.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ katak_id: katakId })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('yemTarixiLoading').style.display = 'none';
            
            if (data.success && data.data.length > 0) {
                let html = '';
                let totalMiqdor = 0;
                let totalSumma = 0;
                
                data.data.forEach((item, index) => {
                    const jamiSumma = item.umumiy_summa;
                    totalMiqdor += parseFloat(item.miqdori);
                    totalSumma += parseFloat(jamiSumma);
                    
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${formatDate(item.sana)}</td>
                            <td><span style="background: #28a745; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px;">${item.mahsulot_nomi}</span></td>
                            <td><strong>${item.miqdori} kg</strong></td>
                            <td>${formatMoney(item.narxi)}</td>
                            <td><strong>${formatMoney(jamiSumma)}</strong></td>
                            <td>${item.izoh || '-'}</td>
                        </tr>
                    `;
                });
                
                // JAMI qator
                html += `
                    <tr style="background-color: #d4edda; font-weight: bold;">
                        <td colspan="3">JAMI:</td>
                        <td><strong>${totalMiqdor.toFixed(1)} kg</strong></td>
                        <td>-</td>
                        <td><strong>${formatMoney(totalSumma)}</strong></td>
                        <td>-</td>
                    </tr>
                `;
                
                document.getElementById('yemTarixiTableBody').innerHTML = html;
            } else {
                document.getElementById('yemTarixiEmpty').style.display = 'block';
            }
        })
        .catch(error => {
            document.getElementById('yemTarixiLoading').style.display = 'none';
            document.getElementById('yemTarixiEmpty').style.display = 'block';
            console.error('Xatolik:', error);
            showAlert('Ma\'lumotlarni yuklashda xatolik yuz berdi', 'error');
        });
    }

    function showOlganTarixi(katakId, katakNomi) {
        document.getElementById('olganTarixiTitle').textContent = `💀 O'lgan jo'jalar tarixi - ${katakNomi}`;
        document.getElementById('olganTarixiLoading').style.display = 'block';
        document.getElementById('olganTarixiEmpty').style.display = 'none';
        document.getElementById('olganTarixiTableBody').innerHTML = '';
        
        showModal('olganTarixiModal');
        
        fetch('../api/get_olgan_tarixi.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ katak_id: katakId })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('olganTarixiLoading').style.display = 'none';
            
            if (data.success && data.data.length > 0) {
                let html = '';
                let totalOlgan = 0;
                let totalSumma = 0;
                
                data.data.forEach((item, index) => {
                    const soni = parseInt(item.soni);
                    const narxi = parseFloat(item.narxi || 0);
                    const summa = soni * narxi;

                    totalOlgan += soni;
                    totalSumma += summa;

                    let sababColor = '#dc3545';
                    if (item.sababi && item.sababi.toLowerCase().includes('kasallik')) {
                        sababColor = '#fd7e14';
                    } else if (item.sababi && item.sababi.toLowerCase().includes('stress')) {
                        sababColor = '#6f42c1';
                    }

                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${formatDate(item.sana)}</td>
                            <td><strong style="color: #dc3545;">${soni}</strong></td>
                            <td>${narxi.toFixed(0)} so'm</td>
                            <td><strong style="color: green;">${summa.toFixed(0)} so'm</strong></td>
                            <td>${item.izoh || '-'}</td>
                        </tr>
                    `;
                });

                html += `
                    <tr style="background-color: #f8d7da; font-weight: bold;">
                        <td colspan="2">JAMI O'LGAN:</td>
                        <td><strong style="color: #dc3545;">${totalOlgan}</strong></td>
                        <td colspan="1">-</td>
                        <td><strong style="color: green;">${totalSumma.toFixed(0)} so'm</strong></td>
                        <td colspan="1">-</td>
                    </tr>
                `;
                document.getElementById('olganTarixiTableBody').innerHTML = html;
            } else {
                document.getElementById('olganTarixiEmpty').style.display = 'block';
            }
        })
        .catch(error => {
            document.getElementById('olganTarixiLoading').style.display = 'none';
            document.getElementById('olganTarixiEmpty').style.display = 'block';
            console.error('Xatolik:', error);
            showAlert('Ma\'lumotlarni yuklashda xatolik yuz berdi', 'error');
        });
    }
    // Yordamchi funksiyalar
    function formatDate(dateString) {
        const date = new Date(dateString);
        const options = {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            weekday: 'short'
        };
        return date.toLocaleDateString('uz-UZ', options);
    }

    function formatMoney(amount) {
        if (!amount || amount == 0) return '0 so\'m';
        return new Intl.NumberFormat('uz-UZ').format(amount) + ' so\'m';
    }

    // Modal tashqarisiga bosilganda yopish
    window.onclick = function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    }

    // Keyboard event listener
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const openModals = document.querySelectorAll('.modal[style*="block"]');
            openModals.forEach(modal => {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            });
        }
    });

    // Animatsiya uchun CSS qo'shish
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.9); }
        }
        
        @keyframes slideOutRight {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(100px); }
        }
        
        .data-table tr:hover {
            transform: scale(1.02);
            transition: transform 0.2s ease;
        }
        
        .btn:active {
            transform: scale(0.95);
        }
        
        .katak-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .loading {
            background: linear-gradient(45deg, #007bff, #28a745);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    `;
    document.head.appendChild(style);
    
</script>
</body>
</html>