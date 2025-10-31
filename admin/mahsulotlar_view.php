<?php
    include_once '../config.php';
    $db = new Database();
    $categoriyalar = $db->get_data_by_table_all('categoriya');
    $birliklar = $db->get_data_by_table_all('birliklar');
?>
<style>
    .expense-btn {
        background: #14d63eff;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(102, 126, 234, 0.25);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .expense-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.35);
    }
    
</style>
<section id="mahsulotlar" class="content-section">
    <div class="section-header">
        <h2 class="section-title">📦 Mahsulotlar katalogi</h2>
        <button class="btn btn-primary" onclick="showModal('mahsulotModal')">
            ➕ Yangi mahsulot qo'shish
        </button>
    </div>
    <div style="margin-bottom: 1rem;">
        <button id="toggleMahsulotViewBtn" class="expense-btn">📋 Jadval ko‘rinishini ko‘rsatish</button>
    </div>
    <div id="mahsulotlarcn">
        
    </div>
    
</section>
<!-- Mahsulot qo'shish modali -->
<div id="mahsulotModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('mahsulotModal')">&times;</span>
        <h2>📦 Yangi mahsulot qo'shish</h2>
        <form id="mahsulotForm">
            <div class="form-grid">
                <div class="form-group">
                    <label>Mahsulot nomi:</label>
                    <input type="text" id="mahsulot_nomi" required placeholder="Masalan: Broiler go'shti">
                </div>
                <div class="form-group">
                    <label>Mahsulotni tanlang:</label>
                    <select id="mahsulot_kategoriya" required>
                        <option value="">Tanlang</option>
                        <?php foreach ($categoriyalar as $kategoriya): ?>
                            <option value="<?= $kategoriya['id'] ?>">
                                <?= $kategoriya['nomi'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>O'lchov birligi:</label>
                    <select id="mahsulot_birlik" required>
                        <option value="">Tanlang</option>
                        <?php foreach ($birliklar as $birlik): ?>
                            <option value="<?= $birlik['id'] ?>">
                                <?= $birlik['nomi'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Narxi (so'm):</label>
                    <input type="number" id="mahsulot_narxi" required min="0" placeholder="Masalan: 28000">
                </div>
            </div>
            <div class="form-group">
                <label>Mahsulot haqida:</label>
                <textarea id="mahsulot_tavsif" rows="3" placeholder="Mahsulot tavsifi..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">✅ Mahsulot qo'shish</button>
        </form>
    </div>
</div>
<!-- ✏️ Mahsulot tahrirlash modali -->
<div id="editMahsulotModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>✏️ Mahsulotni tahrirlash</h3>
            <button class="close" onclick="closeModal('editMahsulotModal')">&times;</button>
        </div>

        <form id="editMahsulotForm" onsubmit="updateMahsulot(event)">
            <input type="hidden" id=" ">

            <div class="form-grid">
                <div class="form-group">
                    <label>Mahsulot nomi:</label>
                    <input type="text" id="edit_mahsulot_nomi" required>
                </div>
                <div class="form-group">
                    <label>Kategoriya:</label>
                    <select id="edit_mahsulot_kategoriya" required>
                        <?php foreach ($categoriyalar as $kategoriya): ?>
                            <option value="<?= $kategoriya['id'] ?>"><?= $kategoriya['nomi'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>O‘lchov birligi:</label>
                    <select id="edit_mahsulot_birlik" required>
                        <?php foreach ($birliklar as $birlik): ?>
                            <option value="<?= $birlik['id'] ?>"><?= $birlik['nomi'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Narxi (so‘m):</label>
                    <input type="number" id="edit_mahsulot_narxi" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label>Tavsif:</label>
                <textarea id="edit_mahsulot_tavsif" rows="3" placeholder="Mahsulot tavsifi..."></textarea>
            </div>

            <button type="submit" class="btn btn-success">💾 Saqlash</button>
        </form>
    </div>
</div>

<!-- ❌ O‘chirishni tasdiqlash modali -->
<div id="deleteConfirmModal" class="modal">
    <div class="modal-content" style="text-align:center;">
        <h3>⚠️ Mahsulotni o‘chirishni istaysizmi?</h3>
        <p>Bu amalni bekor qilib bo‘lmaydi.</p>
        <div style="margin-top:20px;">
            <button class="btn btn-danger" id="confirmDeleteBtn">Ha, o‘chir</button>
            <button class="btn btn-secondary" onclick="closeModal('deleteConfirmModal')">Yo‘q</button>
        </div>
    </div>
</div>

<script>
    document.getElementById('toggleMahsulotViewBtn').addEventListener('click', function() {
        const grid = document.getElementById('gridView');
        const table = document.getElementById('tableView');
        
        if (grid.style.display === 'none') {
            grid.style.display = 'grid';
            table.style.display = 'none';
            this.innerText = '📋 Jadval ko‘rinishini ko‘rsatish';
        } else {
            grid.style.display = 'none';
            table.style.display = 'block';
            this.innerText = '📦 Katak ko‘rinishini ko‘rsatish';
        }
    });
    $('#mahsulotForm').on('submit', function (event) {
        event.preventDefault(); 
        const mahsulot_nomi = $('#mahsulot_nomi').val();
        const mahsulot_kategoriya = $('#mahsulot_kategoriya').val();
        const mahsulot_birlik = $('#mahsulot_birlik').val();
        const mahsulot_narxi = $('#mahsulot_narxi').val();
        const mahsulot_tavsif = $('#mahsulot_tavsif').val();
        $.ajax({
            url: '../form_insert_data/insert_mahsulot.php', 
            type: 'POST',
            dataType: 'json', 
            data: {
                nomi: mahsulot_nomi,
                kategoriya: mahsulot_kategoriya,
                birlik: mahsulot_birlik,
                narxi: mahsulot_narxi,
                tavsif: mahsulot_tavsif
            },
            success: function (response) {
                if (response.status === 'success') {
                    showAlert("✅ Mahsulot qo'shildi!", "success");
                    $('#mahsulotForm')[0].reset(); 
                    
                    closeModal('mahsulotModal');

                    loadMahsulotlar();
                } else {
                    showAlert("❗ Xatolik: " + (response.message || "Ma'lumotlar bazasiga qo'shilmadi!"), "error");
                    
                    $('#mahsulotForm')[0].reset();
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX xatosi:", error);
                swal("❌ Serverda xatolik", "Iltimos, qaytadan urinib ko'ring", "error");
            }
        });
    });

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
  
    function editMahsulot(mahsulotId) {
        const modal = document.getElementById('editMahsulotModal');
        modal.style.display = 'flex';

        fetch('../api/get_mahsulotlar.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(mahsulotId)
        })
        .then(response => response.json())
        .then(data => {
            if(data.status) {
                const m = data.mahsulot;
                document.getElementById('editMahsulotForm').dataset.id = mahsulotId;
                document.getElementById('edit_mahsulot_nomi').value = m.nomi;
                document.getElementById('edit_mahsulot_kategoriya').value = m.categoriya_id;
                document.getElementById('edit_mahsulot_birlik').value = m.birlik_id;
                document.getElementById('edit_mahsulot_narxi').value = m.narxi;
                document.getElementById('edit_mahsulot_tavsif').value = m.tavsif || '';
            } else {
                alert('Mahsulot topilmadi!');
                closeModal('editMahsulotModal');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Xatolik yuz berdi!');
            closeModal('editMahsulotModal');
        });
    }

    /* Mahsulotni yangilash */
    function updateMahsulot(event) {
        event.preventDefault();
        const form = document.getElementById('editMahsulotForm');
        const mahsulotId = form.dataset.id;

        const formData = new URLSearchParams();
        formData.append('id', mahsulotId);
        formData.append('nomi', document.getElementById('edit_mahsulot_nomi').value);
        formData.append('kategoriya_id', document.getElementById('edit_mahsulot_kategoriya').value);
        formData.append('birlik_id', document.getElementById('edit_mahsulot_birlik').value);
        formData.append('narxi', document.getElementById('edit_mahsulot_narxi').value);
        formData.append('tavsif', document.getElementById('edit_mahsulot_tavsif').value);

        fetch('../api/update_mahsulot.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData.toString()
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showAlert("✅ Mahsulot update qilindi!", "success");
                loadMahsulotlar();
                closeModal('editMahsulotModal');
                // Jadvalni yangilash uchun sahifani qayta yuklash yoki AJAX bilan yangilash
            } else {
                showAlert("✅ Mahsulot update qilinmadi!", "error");
                closeModal('editMahsulotModal');
            }
        })
        .catch(err => {
            showAlert("❌ Server bilan bog‘lanishda xatolik!", "error");
        });
    }
    // Modalni ko'rsatish va tasdiqlash tugmasi eventini biriktirish
    function updateStock(id) {
        const modal = document.getElementById('deleteConfirmModal');
        modal.style.display = 'flex';
        const confirmBtn = document.getElementById('confirmDeleteBtn');

        // Avvalgi onclick ni olib tashlash
        confirmBtn.onclick = null;

        confirmBtn.onclick = function () {
            deleteMahsulot(id);
            closeModal('deleteConfirmModal');
        };
    }

    // Mahsulotni o‘chirish
    function deleteMahsulot(id) {

        fetch('../api/delete_mahsulot.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(id)
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                showAlert("✅ Mahsulot o'chirildi!", "success");
                loadMahsulotlar(); // Jadvalni yangilash
            } else {
                showAlert("❌ Mahsulot o'chirilmadi!", "error");
            }
        })
        .catch(err => {
            showAlert('❌ Server bilan bog‘lanishda xatolik yuz berdi.', 'error');
        });
    }

</script>
