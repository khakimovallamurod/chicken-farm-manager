
<?php
    include_once '../config.php';
    $db = new Database();
    $kataklar = $db->get_data_by_table_all('kataklar');
    $mahsulotlar = $db->get_data_by_table_all('mahsulotlar', "WHERE categoriya_id = 1");
    
?>
<section id="joja" class="content-section">
    <div class="section-header">
        <h2 class="section-title">🐥 Jo'ja qo'shish</h2>
    </div>
    <form id="jojaForm" onsubmit="addJoja(event)">
        <div class="form-grid">
            <div class="form-group">
                <label>Katak tanlang:</label>
                <select id="joja_katak_id" required>
                    <option value="">Katakni tanlang</option>
                    <?php foreach ($kataklar as $katak): ?>
                        <option value="<?= $katak['id'] ?>">
                            <?= $katak['katak_nomi'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Mahsulotni tanlang:</label>
                <select id="joja_kategoriya" required>
                    <option value="">Tanlang</option>
                    <?php foreach ($mahsulotlar as $mahsulot): ?>
                        <option value="<?= $mahsulot['id'] ?>">
                            <?= $mahsulot['nomi'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Jo'jalar soni:</label>
                <input type="number" id="joja_soni" required min="1" placeholder="Masalan: 100">
            </div>
            <div class="form-group">
                <label>Sana:</label>
                <input type="date" id="joja_sana" required>
            </div>
        </div>
        <div class="form-group">
            <label>Izoh:</label>
            <textarea id="joja_izoh" rows="3" placeholder="Qo'shimcha ma'lumotlar..."></textarea>
        </div>
        <button type="submit" class="btn btn-success">🐥 Jo'ja qo'shish</button>
    </form>
</section>
<script src="../js/jquery-3.6.0.min.js"></script>
<script src="../js/sweetalert.min.js"></script>
<script>
    function addJoja(event) {
        event.preventDefault();
        const katakId = $('#joja_katak_id').val();
        const kategoriya = $('#joja_kategoriya').val();
        const soni = $('#joja_soni').val();
        const sana = $('#joja_sana').val();
        const izoh = $('#joja_izoh').val();

        const data = {
            katak_id: katakId,
            kategoriya: kategoriya,
            soni: soni,
            sana: sana,
            izoh: izoh
        };
        $.ajax({
            url: '../form_insert_data/joja_add.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            dataType: 'json',
            
            success: function(result) {
                if (result.success) {
                    showAlert(result.message, 'success');
                    $('#jojaForm')[0].reset();
                } else {
                    showAlert(result.message, 'error');
                    $('#jojaForm')[0].reset();
                }
            },
            error: function(xhr, status, error) {
                swal({
                    title: "Xatolik",
                    text: "Ma'lumotlar bazasiga qo'shishda xatolik yuz berdi!",
                    icon: "error"
                });
            }
        });
    }
</script>

