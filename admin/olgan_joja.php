<?php
include_once '../config.php';
$db = new Database();
$kataklar = $db->get_data_by_table_all('kataklar');

?>
<section id="olganjoja" class="content-section">
    <div class="section-header">
        <h2 class="section-title">💀 O'lgan jo'jalarni ayirish</h2>
        <div style="margin-top: 1rem;">
            <button id="toggleOlganViewBtn" class="btn btn-outline-success">📋 Jadval ko‘rinishini ko‘rsatish</button>
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
    
    <?php
        include_once '../config.php';
        $db = new Database();
        $query_for_oj = "SELECT oj.*, k.katak_nomi AS katak_nomi
            FROM olgan_jojalar oj
            JOIN kataklar k ON oj.katak_id = k.id ORDER BY sana DESC";
        $fetch = $db->query($query_for_oj);
        
    ?>
    <div class="table-container shadow p-3 mb-4 bg-white rounded" id="olganTableSection" style="display: none;">
        <h3 class="table-title mb-3">
            <i class="fas fa-skull-crossbones me-2 text-danger"></i>O'lgan jo'jalar ro'yxati
        </h3>

        <div class="table-responsive">
            <table id="olganJojalarTable" class="table table-bordered table-hover align-middle text-center">
                <thead class="table-secondary">
                    <tr>
                        <th><i class="fas fa-home me-1"></i>Katak nomi</th>
                        <th><i class="fas fa-minus-circle me-1"></i>Soni</th>
                        <th><i class="fas fa-calendar-times me-1"></i>O'lgan sana</th>
                        <th><i class="fas fa-comment-dots me-1"></i>Izoh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($olgan_joja = mysqli_fetch_assoc($fetch)) { ?>                    
                        <tr>
                            <td>
                                <span class="badge bg-dark"><?= htmlspecialchars($olgan_joja['katak_nomi']) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-danger"><?= htmlspecialchars($olgan_joja['soni']) ?> dona</span>
                            </td>
                            <td data-order="<?= $olgan_joja['sana'] ?>">
                                <?= date('d.m.Y', strtotime($olgan_joja['sana'])) ?>
                            </td>
                            <td><?= htmlspecialchars($olgan_joja['izoh']) ?></td>                        
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
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
    $(document).ready(function () {
        $('#olganJojalarTable').DataTable({
            responsive: true,
            order: [[2, 'desc']], // O'lgan sana bo'yicha kamayish
            language: {
                search: "Qidiruv:",
                lengthMenu: "Har sahifada _MENU_ ta yozuv ko‘rsatiladi",
                info: "Jami _TOTAL_ tadan _START_–_END_ ko‘rsatilmoqda",
                paginate: {
                    first: "Birinchi",
                    last: "Oxirgi",
                    next: "Keyingi",
                    previous: "Oldingi"
                },
                zeroRecords: "Hech narsa topilmadi",
                infoEmpty: "Ma’lumot yo‘q",
                infoFiltered: "(umumiy _MAX_ yozuvdan filtrlandi)"
            }
        });
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