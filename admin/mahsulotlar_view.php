<?php
    include_once '../config.php';
    $db = new Database();
    $query = "SELECT
        m.id,
        m.nomi,
        k.nomi AS categoriya_nomi,
        b.nomi AS birlik_nomi,
        m.narxi,
        COALESCE(mz.soni, 0) AS soni,
        m.tavsif,
        m.created_at               
    FROM mahsulotlar m
    LEFT JOIN categoriya k ON m.categoriya_id = k.id
    LEFT JOIN birliklar b ON m.birlik_id = b.id
    LEFT JOIN mahsulot_zahirasi mz ON m.id = mz.mahsulot_id;";

    $mahsulotlar = $db->query($query);
    $categoriyalar = $db->get_data_by_table_all('categoriya');
    $birliklar = $db->get_data_by_table_all('birliklar');
?>
<style>
    .expense-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    <div id="gridView" class="kataklar-grid">
        <?php foreach ($mahsulotlar as $mahsulot): ?>
        <div class="katak-card">
            <div class="katak-header">
                <div class="katak-title"><?= $mahsulot['nomi'] ?></div>
                <span class="katak-status status-active"><?= $mahsulot['categoriya_nomi'] ?></span>
            </div>
            <div class="katak-info">
                <div class="info-item">
                    <div class="info-value"><?= $mahsulot['soni'] ?></div>
                    <div class="info-label"><?= $mahsulot['birlik_nomi'] ?></div>
                </div>
                <div class="info-item">
                    <div class="info-value"><?= $mahsulot['narxi'] ?></div>
                    <div class="info-label">so'm/<?= $mahsulot['birlik_nomi'] ?></div>
                </div>
            </div>
            <p><?= $mahsulot['tavsif'] ?></p>
            <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                <button class="btn btn-primary" style="font-size: 0.8rem;" onclick="editMahsulot(1)">✏️ Tahrirlash</button>
                <button class="btn btn-warning" style="font-size: 0.8rem;" onclick="updateStock(1)">📊 Qoldiq</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div id="tableView" style="display: none;">
        <table id="mahsulotlarTable" class="display">
            <thead>
                <tr>
                    <th>Nomi</th>
                    <th>Categoriya</th>
                    <th>Soni</th>
                    <th>Birlik</th>
                    <th>Narxi</th>
                    <th>Tavsif</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mahsulotlar as $m): ?>
                <tr>
                    <td><?= $m['nomi'] ?></td>
                    <td><?= $m['categoriya_nomi'] ?></td>
                    <td><?= $m['soni'] ?></td>
                    <td><?= $m['birlik_nomi'] ?></td>
                    <td><?= $m['narxi'] ?></td>
                    <td><?= $m['tavsif'] ?></td>
                    <td>
                        <button class="btn btn-primary" onclick="editMahsulot(1)">✏️</button>
                        <button class="btn btn-warning" onclick="updateStock(1)">📊</button>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
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
<script src="../js/jquery-3.6.0.min.js"></script>
<script src="../js/sweetalert.min.js"></script>
<script>
    document.getElementById('toggleViewBtn').addEventListener('click', function () {
        const gridView = document.getElementById('gridView');
        const tableView = document.getElementById('tableView');

        if (gridView.style.display === 'none') {
            gridView.style.display = 'grid';
            tableView.style.display = 'none';
            this.innerText = '📋 Jadval ko‘rinishini ko‘rsatish';
        } else {
            gridView.style.display = 'none';
            tableView.style.display = 'block';
            this.innerText = '📦 Katak ko‘rinishini ko‘rsatish';
        }
    });
    $(document).ready(function() {
        $('#mahsulotlarTable').DataTable();
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
                } else {
                    showAlert("❗ Xatolik: " + (response.message || "Ma'lumotlar bazasiga qo'shilmadi!"), "error");
                    closeModal('mahsulotModal');
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
