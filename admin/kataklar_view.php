<section id="kataklist" class="content-section">
    <div class="section-header">
        <h2 class="section-title">🏠 Kataklar ro'yxati</h2>
        <button class="btn btn-primary" onclick="showModal('katakModal')">
            ➕ Yangi katak yaratish
        </button>
    </div>
    
    <div class="kataklar-grid">
        <?php 
            include_once '../config.php';
            $db = new Database();
            $query = "SELECT 
                            k.*,
                            COALESCE(j.umumiy_joja, 0) AS umumiy_joja,
                            COALESCE(o.olgan_joja, 0) AS olgan_joja,
                            (COALESCE(j.umumiy_joja, 0) - COALESCE(o.olgan_joja, 0)) AS joja_soni
                        FROM 
                            kataklar k
                        LEFT JOIN (
                            SELECT katak_id, SUM(soni) AS umumiy_joja
                            FROM joja
                            GROUP BY katak_id
                        ) j ON k.id = j.katak_id
                        LEFT JOIN (
                            SELECT katak_id, SUM(soni) AS olgan_joja
                            FROM olgan_jojalar
                            GROUP BY katak_id
                        ) o ON k.id = o.katak_id
                        ORDER BY 
                            CASE WHEN k.status = 'active' THEN 0 ELSE 1 END,
                            k.created_at DESC;
                        ";
                    
            $kataklar = $db->query($query);
            while ($katak = mysqli_fetch_assoc($kataklar)) {
                $today = new DateTime();
                $old_date = new DateTime($katak['created_at']);
                $interval = $today->diff($old_date);
                $days = $interval->days;
                if ($katak['status'] == 'active') {
        ?>
            <div class="katak-card" data-id="<?=$katak['id']?>">
                <div class="katak-header">
                    <div class="katak-title"><?=$katak['katak_nomi']?>/<?=$katak['sigimi']?></div>
                    <span class="katak-status status-active">Faol</span>
                </div>
                <div class="katak-info">
                    <div class="info-item">
                        <div class="info-value"><?=$katak['umumiy_joja']?></div>
                        <div class="info-label">Jo'jalar</div>
                    </div>
                    <div class="info-item">
                        <div class="info-value"><?=$katak['joja_soni']?></div>
                        <div class="info-label">Qolgan jo'jalar</div>
                    </div>
                    <div class="info-item">
                        <div class="info-value"><?=$katak['olgan_joja']?></div>
                        <div class="info-label">O'lgan jojalar</div>
                    </div>
                    <div class="info-item">
                        <div class="info-value"><?=$days?></div>
                        <div class="info-label">Kun</div>
                    </div>
                    
                </div>
                <p><?=$katak['izoh']?></p>
                <div class="katak-actions">
                    <button class="btn btn-danger" onclick="deleteKatak(this)">O'chirish</button>
                    <button class="btn btn-warning" onclick="editKatak(this)">Tahrirlash</button>
                </div>
            </div>
            <?php } else { ?>
                <!-- Faol emas kataklar -->
                <div class="katak-card inactive" data-id="<?=$katak['id']?>">
                    <div class="katak-header">
                        <div class="katak-title"><?=$katak['katak_nomi']?></div>
                        <span class="katak-status status-inactive">Faol emas</span>
                    </div>
                    <div class="katak-info">
                        <div class="info-item">
                            <div class="info-value"><?=$katak['joja_soni']?></div>
                            <div class="info-label">Jo'jalar</div>
                        </div>  
                        <div class="info-item">
                            <div class="info-value"><?=$days?></div>
                            <div class="info-label">Kun</div>
                        </div>          
                        <div class="info-item">
                            <div class="info-value"><?=$katak['sigimi']?></div>
                            <div class="info-label">Sig'im</div>
                        </div>
                    </div>
                    <p><?=$katak['izoh']?></p>
                    <div class="katak-actions">
                        <button class="btn btn-danger" onclick="deleteKatak(this)">O'chirish</button>
                        <button class="btn btn-warning" onclick="editKatak(this)">Tahrirlash</button>
                    </div>
                </div>
        <?php } }?>
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
                <input type="number" id="katak_sigimi" required min="1" placeholder="Masalan: 500">
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
<script>
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
                showAlert("✅ " + data.message);
                document.getElementById("katakForm").reset();
                closeModal("katakModal");
            } else {
                showAlert("❌ " + data.message);
            }
        })
        .catch(error => {
            console.error("Xatolik:", error);
            showAlert("❌ So'rov yuborishda xatolik yuz berdi: " + error.message);
        });
    }
    function showModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

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
            showAlertModal('error', 'Katak tanlanmagan');
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
                if (element) element.remove();
                deleteModal.style.display = 'none';
                showAlertModal('success', data.message);
            } else {
                throw new Error(data.message || 'Noma\'lum xatolik yuz berdi');
            }
        })
        .catch(error => {
            console.error('Xatolik:', error);
            showAlertModal('error', 'O\'chirishda xatolik: ' + error.message);
        });
    }

    function showAlertModal(type, message) {
        const alertModal = document.createElement('div');
        alertModal.className = 'modal';
        alertModal.style.display = 'block';
        alertModal.innerHTML = `
            <div class="modal-content" style="max-width: 400px; text-align: center;">
                <div class="alert-icon" style="font-size: 2em; margin-bottom: 10px;">
                    ${type === 'success' ? '✅' : '❌'}
                </div>
                <p style="margin-bottom: 20px;">${message}</p>
                <button class="btn btn-primary" onclick="this.closest('.modal').remove()">OK</button>
            </div>
        `;
        document.body.appendChild(alertModal);
    }
    // Tahrirlash funksiyasi
    function editKatak(button) {
        const katakCard = button.closest('.katak-card');
        const katakId = katakCard.dataset.id;
        const katakTitle = katakCard.querySelector('.katak-title').textContent;
        const katakCapacity = katakCard.querySelector('.info-item:nth-child(3) .info-value').textContent;
        const katakComment = katakCard.querySelector('p').textContent;
        
        document.getElementById('edit_katak_id').value = katakId;
        document.getElementById('edit_katak_nomi').value = katakTitle;
        document.getElementById('edit_katak_sigimi').value = katakCapacity;
        document.getElementById('edit_katak_izoh').value = katakComment;
        showModal('editKatakModal');
    }
    // Katakni yangilash funksiyasi
    function updateKatak(event) {
        event.preventDefault();
        
        const katakId = document.getElementById('edit_katak_id').value;
        const formData = new FormData();
        formData.append('id', katakId);
        formData.append('nomi', document.getElementById('edit_katak_nomi').value);
        formData.append('sigimi', document.getElementById('edit_katak_sigimi').value);
        formData.append('izoh', document.getElementById('edit_katak_izoh').value);
        
        fetch('../kataklar/katak_update.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Update response status:', response.status);
            if (!response.ok) throw new Error('Server javob bermadi');
            return response.json();
        })
        .then(data => {
            console.log('Update server javobi:', data);
            if (data.success) {
                closeModal('editKatakModal');
                updateKatakInDOM(katakId, {
                    nomi: document.getElementById('edit_katak_nomi').value,
                    sigimi: document.getElementById('edit_katak_sigimi').value,
                    izoh: document.getElementById('edit_katak_izoh').value,
                    status: document.getElementById('edit_katak_status').checked ? 'active' : 'inactive'
                });
                
                showAlertModal('success', 'Katak muvaffaqiyatli yangilandi');
            } else {
                showAlertModal('error', 'Katakni yangilashda xatolik: ' + (data.message || 'Noma\'lum xatolik'));
            }
        })
        .catch(error => {
            console.error('Update xatolik:', error);
            showAlertModal('error', 'Server xatosi: ' + error.message);
        });
    }

    // Katakni DOMda yangilash funksiyasi
    function updateKatakInDOM(katakId, data) {
        const katakCard = document.querySelector(`.katak-card[data-id="${katakId}"]`);
        if (!katakCard) return;
        
        // Nom yangilash
        katakCard.querySelector('.katak-title').textContent = data.nomi;
        
        // Status yangilash
        const statusElement = katakCard.querySelector('.katak-status');
        if (data.status === 'active') {
            statusElement.textContent = 'Faol';
            statusElement.className = 'katak-status status-active';
            katakCard.classList.remove('inactive');
        } else {
            statusElement.textContent = 'Faol emas';
            statusElement.className = 'katak-status status-inactive';
            katakCard.classList.add('inactive');
        }
        
        // Sig'im yangilash
        katakCard.querySelector('.info-item:nth-child(3) .info-value').textContent = data.sigimi;
        
        // Izoh yangilash
        katakCard.querySelector('p').textContent = data.izoh || '';
    }
</script>