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
        <button id="toggleViewBtn" class="expense-btn">📋 Jadval ko‘rinishini ko‘rsatish</button>
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

<script>
    
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
    
</script>
