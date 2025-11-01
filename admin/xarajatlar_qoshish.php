<?php
    include_once '../config.php';
    $db = new Database();
    $tolov_birliklari = $db->get_data_by_table_all('tolov_birligi');
    $harajat_turlari = $db->get_data_by_table_all('harajat_turlari');
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
<section id="harajat" class="content-section">
    <div class="section-header">
        <h2 class="section-title">💸 Harajatlar</h2>
    </div>
    <div style="margin-bottom: 1rem;">
        <button id="toggleHarajatViewBtn" class="expense-btn">📝 Harajatlar ro'yxatini ko'rish</button>
    </div>
    <form id="harajatForm">
        <div class="form-grid">
            <div class="form-group">
                <label>Harajat turi:</label>
                <select id="harajat_turi" required>
                    <option value="">Harajat turini tanlang</option>
                    <?php foreach ($harajat_turlari as $harajat_tur): ?>
                        <option value="<?= $harajat_tur['id'] ?>">
                            <?= htmlspecialchars($harajat_tur['nomi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>To'lov usuli:</label>
                <select id="sotish_tolov" required>
                    <option value="">To'lov usulini tanlang</option>
                    <?php foreach ($tolov_birliklari as $tolov_birlik): ?>
                        <option value="<?= $tolov_birlik['id'] ?>">
                            <?= htmlspecialchars($tolov_birlik['nomi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Miqdor (so'm):</label>
                <input type="text" id="harajat_miqdor" required min="0" step="0.01" placeholder="Masalan: 500000">
            </div>
            <div class="form-group">
                <label>Sana:</label>
                <input type="date" id="harajat_sana" required>
            </div>
        </div>
        <div class="form-group">
            <label>Izoh:</label>
            <textarea id="harajat_izoh" rows="3" placeholder="Harajat tafsilotlari..."></textarea>
        </div>
        <button type="submit" class="btn btn-danger">💸 Harajat qo'shish</button>
    </form>
    <div class="table-container" id="harajatTableContainer" style="display:none;">
        <h3>So'nggi harajatlar</h3>
        <div class="filter-section">
            <div class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label for="min-date" class="form-label">
                        <i class="fas fa-calendar-alt me-1"></i>Boshlanish sanasi
                    </label>
                    <input type="date"  id="startDate_xarajat" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="max-date" class="form-label">
                        <i class="fas fa-calendar-check me-1"></i>Tugash sanasi
                    </label>
                    <input type="date" id="endDate_xarajat"  class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <button id="filterByDate_xarajat" class="btn-professional btn-info">🔍 Filterlash</button>
                    <button id="clearFilter_xarajat" class="btn-professional btn-secondary">❌ Tozalash</button>
                </div>
            </div>
        </div>
        <div id="xarajatqoshishcn">

        </div>
    </div>
</section>

<script>
    const capacityInput_harajat_miqdor = document.getElementById('harajat_miqdor');
    IMask(capacityInput_harajat_miqdor, {
        mask: Number,
        min: 0,
        max: 1000000000000,
        thousandsSeparator: ' '
    });
    document.getElementById('toggleHarajatViewBtn').addEventListener('click', function() {
        const form = document.getElementById('harajatForm');
        const tableContainer = document.getElementById('harajatTableContainer');

        if (form.style.display === 'none') {
            form.style.display = 'block';
            tableContainer.style.display = 'none';
            this.innerText = "📋 Harajatlar ro'yxatini ko'rish";
        } else {
            form.style.display = 'none';
            tableContainer.style.display = 'block';
            this.innerText = '📝 Harajat formasini ko‘rsatish';
        }
    });
   
    $('#harajatForm').on('submit', function (event) {
        event.preventDefault();

        const harajat_turi = $('#harajat_turi').val();
        const sotish_tolov = $('#sotish_tolov').val();
        const harajat_miqdor = $('#harajat_miqdor').val();
        const harajat_sana = $('#harajat_sana').val();
        const harajat_izoh = $('#harajat_izoh').val();

        const data = {
            turi: harajat_turi,
            tolov: sotish_tolov,
            miqdor: harajat_miqdor,
            sana: harajat_sana,
            izoh: harajat_izoh
        };

        $.ajax({
            url: '../form_insert_data/insert_xarajat.php',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    $('#harajatForm')[0].reset();
                    loadXarajatQoshish();
                    closeModal('harajatModal'); 
                    
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function (xhr, status, error) {
                showAlert("Xatolik yuz berdi. Iltimos qayta urinib ko'ring.", 'error');
            }
        });
    });
</script>
