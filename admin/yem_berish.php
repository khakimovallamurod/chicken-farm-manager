<?php
include_once '../config.php';
$db = new Database();
$kataklar = $db->get_data_by_table_all('kataklar');
$mahsulotlar = $db->get_data_by_table_all('mahsulotlar', "WHERE categoriya_id = 2"); 
?>

<section id="yem" class="content-section">
    <div class="section-header">
        <h2 class="section-title">🌾 Yem berish</h2>
        <div style="margin-top: 1rem;">
            <button id="toggleYemViewBtn"  class="expense-btn">📋 Jadval ko‘rinishini ko‘rsatish</button>
        </div>
    </div>

    <div id="yemFormSection">
        <form id="yemForm" onsubmit="addYem(event)">
            <div class="form-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 20px;">
                <div class="form-group">
                    <label>Katak tanlang:</label>
                    <select id="yem_katak_id" required>
                        <option value="">Katakni tanlang</option>
                        <?php foreach ($kataklar as $katak): ?>
                            <option value="<?= $katak['id'] ?>">
                                <?= $katak['katak_nomi'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Sana:</label>
                    <input type="date" id="yem_sana" required>
                </div>
                <div class="form-group">
                    <label>Yem turi:</label>
                    <select id="yem_turi" required>
                        <option value="">Yem turini tanlang</option>
                        <?php foreach ($mahsulotlar as $mahsulot): ?>
                            <option value="<?= $mahsulot['id'] ?>">
                                <?= $mahsulot['nomi'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Yem miqdori (kg):</label>
                    <input type="text" id="yem_miqdori" required min="0" placeholder="Masalan: 50">
                </div>
            </div>
            <div class="form-group">
                <label>Izoh:</label>
                <textarea id="yem_izoh" rows="3" placeholder="Yem turi, sifati haqida..."></textarea>
            </div>
            <button type="submit" class="btn btn-success">🌾 Yem berish</button>
        </form>
    </div>

    <!-- JADVAL KO‘RINISHI -->
    <div class="table-container" id="yemTableSection" style="display: none;"style="display: none;">
        <h3 class="table-title">
            <i class="fas fa-list-alt me-2"></i>Yem berish ro'yxati
        </h3>
        <div class="filter-section">
            <div class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label for="min-date" class="form-label">
                        <i class="fas fa-calendar-alt me-1"></i>Boshlanish sanasi
                    </label>
                    <input type="date"  id="startDate_yem" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="max-date" class="form-label">
                        <i class="fas fa-calendar-check me-1"></i>Tugash sanasi
                    </label>
                    <input type="date" id="endDate_yem"  class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <button id="filterByDate_yem" class="btn-professional btn-info">🔍 Filterlash</button>
                    <button id="clearFilter_yem" class="btn-professional btn-secondary">❌ Tozalash</button>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <div id="yemberishcn">

            </div>
        </div>
    </div>
</section>

<script>
    const capacityInput_yem_miqdori = document.getElementById('yem_miqdori');
    IMask(capacityInput_yem_miqdori, {
        mask: Number,
        min: 0,
        max: 10000000,
        thousandsSeparator: ' '
    });
    const toggleYemBtn = document.getElementById('toggleYemViewBtn');
    const yemFormSection = document.getElementById('yemFormSection');
    const yemTableSection = document.getElementById('yemTableSection');

    toggleYemBtn.addEventListener('click', () => {
        const isFormVisible = yemFormSection.style.display !== 'none';

        yemFormSection.style.display = isFormVisible ? 'none' : 'block';
        yemTableSection.style.display = isFormVisible ? 'block' : 'none';

        toggleYemBtn.innerHTML = isFormVisible 
            ? '➕ Forma ko‘rinishini ko‘rsatish' 
            : '📋 Jadval ko‘rinishini ko‘rsatish';
    });
    
    function addYem(event) {
        event.preventDefault();

        const katakId = $('#yem_katak_id').val();
        const sana = $('#yem_sana').val();
        const yemTuri = $('#yem_turi').val();
        const miqdori = $('#yem_miqdori').val();
        const izoh = $('#yem_izoh').val();

        if (!katakId || !sana || !yemTuri || !miqdori) {
            showAlert("❗ Barcha majburiy maydonlarni to'ldiring!", "error");
            return;
        }
        if (miqdori <= 0) {
            showAlert("❗ Yem miqdori 0 dan katta bo'lishi kerak!", "error");
            return;
        }
        const data = {
            katak_id: katakId,
            sana: sana,
            yem_turi: yemTuri,
            miqdori: miqdori,
            izoh: izoh
        };

        $.ajax({
            url: '../form_insert_data/yem_berish.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            dataType: 'json',

            success: function(result) {
                if (result.success) {
                    showAlert(result.message || "✅ Yem muvaffaqiyatli qo‘shildi!", 'success');
                    $('#yemForm')[0].reset();
                    document.getElementById('yem_sana').valueAsDate = new Date();
                    loadYemBerish();
                } else {
                    showAlert(result.message || "❗ Ma'lumot qo‘shishda xatolik yuz berdi!", 'error');
                    $('#yemForm')[0].reset();
                }
            },
            error: function(xhr, status, error) {
                showAlert("❌ Server xatosi: Ma'lumotlar bazasiga qo'shishda muammo yuz berdi!", "error");
            }
        });
    }
    function showAlert(message, type) {
        const iconType = type === 'success' ? 'success' : type === 'error' ? 'error' : 'info';
        const title = type === 'success' ? 'Muvaffaqiyat!' : type === 'error' ? 'Xatolik!' : 'Ma\'lumot';
        
        swal({
            title: title,
            text: message,
            icon: iconType,
            button: "OK"
        });
    }
</script>