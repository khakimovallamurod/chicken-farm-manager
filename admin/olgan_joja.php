<?php
include_once '../config.php';
$db = new Database();
$kataklar = $db->get_data_by_table_all('kataklar');

?>
<section id="olganjoja" class="content-section">
    <div class="section-header">
        <h2 class="section-title">💀 O'lgan jo'jalarni ayirish</h2>
        <div style="margin-top: 1rem;">
            <button id="toggleOlganViewBtn" class="expense-btn">📋 Jadval ko‘rinishini ko‘rsatish</button>
        </div>
    </div>
    <div id="olganFormSection">
        <form id="olganJojaForm" onsubmit="addOlganJoja(event)">
            <div class="form-grid">
                <div class="form-group">
                    <label>Katak tanlang:</label>
                    <select id="olgan_katak_id" required>
                        <option value="">Katakni tanlang</option>
                        <?php foreach ($kataklar as $katak): ?>
                            <option value="<?= $katak['id'] ?>">
                                <?= $katak['katak_nomi'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>O'lgan jo'jalar soni:</label>
                    <input type="number" id="olgan_soni" required min="1" placeholder="Masalan: 5">
                </div>
                <div class="form-group">
                    <label>Sana:</label>
                    <input type="date" id="olgan_sana" required>
                </div>
            </div>
            <div class="form-group">
                <label>Sabab/Izoh:</label>
                <textarea id="olgan_izoh" rows="3" placeholder="O'lim sababi..."></textarea>
            </div>
            <button type="submit" class="btn btn-danger">💀 O'lgan jo'jani ayirish</button>
        </form>
    </div>
    
    
    <div class="table-container shadow p-3 mb-4 bg-white rounded" id="olganTableSection" style="display: none;">
        <h3 class="table-title mb-3">
            <i class="fas fa-skull-crossbones me-2 text-danger"></i>O'lgan jo'jalar ro'yxati
        </h3>
        <div class="filter-section">
            <div class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label for="min-date" class="form-label">
                        <i class="fas fa-calendar-alt me-1"></i>Boshlanish sanasi
                    </label>
                    <input type="date"  id="startDate_kill" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="max-date" class="form-label">
                        <i class="fas fa-calendar-check me-1"></i>Tugash sanasi
                    </label>
                    <input type="date" id="endDate_kill"  class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <button id="filterByDate_kill" class="btn-professional btn-info">🔍 Filterlash</button>
                    <button id="clearFilter_kill" class="btn-professional btn-secondary">❌ Tozalash</button>
                </div>
            </div>
        </div>                    
        <div class="table-responsive">
            <div id='olganjojacn'>

            </div>
        </div>
    </div>
</section>
<script src="../js/jquery-3.6.0.min.js"></script>
<script src="../js/sweetalert.min.js"></script>
<script>
    const toggleOlganBtn = document.getElementById('toggleOlganViewBtn');
    const olganFormSection = document.getElementById('olganFormSection');
    const olganTableSection = document.getElementById('olganTableSection');

    toggleOlganBtn.addEventListener('click', () => {
        const isFormVisible = olganFormSection.style.display !== 'none';

        olganFormSection.style.display = isFormVisible ? 'none' : 'block';
        olganTableSection.style.display = isFormVisible ? 'block' : 'none';

        toggleOlganBtn.innerHTML = isFormVisible 
            ? '➕ Forma ko‘rinishini ko‘rsatish' 
            : '📋 Jadval ko‘rinishini ko‘rsatish';
    });
    
    function addOlganJoja(event) {
        event.preventDefault();
        const katakId = $('#olgan_katak_id').val();
        const soni = $('#olgan_soni').val();
        const sana = $('#olgan_sana').val();
        const izoh = $('#olgan_izoh').val();

        const data = {
            katak_id: katakId,
            soni: soni,
            sana: sana,
            izoh: izoh
        };
        $.ajax({
            url: '../form_insert_data/olgan_jojalar.php',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    $('#olganJojaForm')[0].reset();
                    loadOlganJoja();
                } else {
                    showAlert(response.message, 'error');
                    $('#olganJojaForm')[0].reset();
                }
            },
            error: function() {
                swal("Error", "Server bilan bog'lanishda xatolik yuz berdi.", "error");
            }
        });
    }
</script>