<?php
    include_once '../config.php';
    $db = new Database();
    $taminotchilar = $db->get_data_by_table_all('taminotchilar');
    $tolov_birliklari = $db->get_data_by_table_all('tolov_birligi');
?>  
<section id="pulberish" class="content-section">
    <div class="section-header">
        <h2 class="section-title">💸 Taminotchiga pul berish</h2>
    </div>
    
    <form id="pulBerishForm">
        <div class="form-grid">
            <div class="form-group">
                <label>Taminotchini tanlang:</label>
                <select id="ber_mijoz" required>
                    <option value="">Taminotchini tanlang</option>
                    <?php foreach ($taminotchilar as $taminotchi): ?>
                        <option value="<?= $taminotchi['id'] ?>">
                            <?= $taminotchi['fio'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Berilgan miqdor (so'm):</label>
                <input type="number" id="ber_summasi" required min="0" step="0.01" placeholder="Masalan: 3000000">
            </div>
            <div class="form-group">
                <label>Berilgan sana:</label>
                <input type="date" id="ber_sana" required>
            </div>
            <div class="form-group">
                <label>To'lov usuli:</label>
                <select id="ber_tolov" required>
                    <option value="">To'lov usulini tanlang</option>
                    <?php foreach ($tolov_birliklari as $tolov_birlik): ?>
                        <option value="<?= $tolov_birlik['id'] ?>">
                            <?= $tolov_birlik['nomi'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Izoh:</label>
            <textarea id="ber_izoh" rows="3" placeholder="Qo'shimcha ma'lumotlar..."></textarea>
        </div>
        <button type="submit" class="btn btn-danger">💸 Pul berishni qayd qilish</button>
    </form>
</section>
<script>
    $('#pulBerishForm').on('submit', function (event) {
        event.preventDefault();

        const formData = {
            taminotchi_id: $('#ber_mijoz').val(),
            summa: $('#ber_summasi').val(),
            sana: $('#ber_sana').val(),
            tolov_usuli: $('#ber_tolov').val(),
            izoh: $('#ber_izoh').val().trim()
        };

        $.ajax({
            url: '../form_insert_data/pul_berish_insert.php', 
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (result) {
                if (result.success) {
                    showAlert(result.message, 'success');
                    $('#pulBerishForm')[0].reset();
                } else {
                    showAlert(result.message, 'error');
                    $('#pulBerishForm')[0].reset();
                }
            },
            error: function (xhr, status, error) {
                console.error('Server xatosi:', error);
                swal({
                    title: "Xatolik!",
                    text: "Server bilan bog'lanishda xatolik yuz berdi.",
                    icon: "error",
                    button: "OK",
                });
            }
        });
    });
</script>