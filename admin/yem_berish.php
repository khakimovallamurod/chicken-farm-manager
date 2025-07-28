<?php
include_once '../config.php';
$db = new Database();
$kataklar = $db->get_data_by_table_all('kataklar');
$mahsulotlar = $db->get_data_by_table_all('mahsulotlar', "WHERE categoriya_id = 2"); 
?>
<section id="yem" class="content-section">
    <div class="section-header">
        <h2 class="section-title">🌾 Yem berish</h2>
    </div>
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
                <input type="number" id="yem_miqdori" required min="1" placeholder="Masalan: 50">
            </div>
        </div>                   
        <div class="form-group">
            <label>Izoh:</label>
            <textarea id="yem_izoh" rows="3" placeholder="Yem turi, sifati haqida..."></textarea>
        </div>

        <button type="submit" class="btn btn-success">🌾 Yem berish</button>
    </form>
</section>
<script src="../js/jquery-3.6.0.min.js"></script>
<script src="../js/sweetalert.min.js"></script>

<script>
    function addYem(event) {
        event.preventDefault();
        const katakId = $('#yem_katak_id').val();
        const sana = $('#yem_sana').val();
        const yemTuri = $('#yem_turi').val();
        const miqdori = $('#yem_miqdori').val();
        const izoh = $('#yem_izoh').val();

        const data = {
            katak_id: katakId,
            sana: sana,
            yem_turi: yemTuri,
            miqdori: miqdori,
            izoh: izoh
        };
        console.log(data);
        $.ajax({
            url: '../form_insert_data/yem_berish.php',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: function(result) {
                if (result.success) {
                    showAlert(result.message, 'success');
                    $('#yemForm')[0].reset();
                } else {
                    swal({
                        title: "Xatolik",
                        text: result.message,
                        icon: "error"
                    });
                    $('#yemForm')[0].reset();
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
