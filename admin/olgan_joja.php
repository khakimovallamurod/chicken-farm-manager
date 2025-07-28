<?php
include_once '../config.php';
$db = new Database();
$kataklar = $db->get_data_by_table_all('kataklar');

?>
<section id="olganjoja" class="content-section">
    <div class="section-header">
        <h2 class="section-title">💀 O'lgan jo'jalarni ayirish</h2>
    </div>
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
    <?php
        include_once '../config.php';
        $db = new Database();
        $query_for_oj = "SELECT oj.*, k.katak_nomi AS katak_nomi
            FROM olgan_jojalar oj
            JOIN kataklar k ON oj.katak_id = k.id ORDER BY sana DESC";
        $fetch = $db->query($query_for_oj);
        
    ?>
    <div class="table-container">
        <h3>O'lgan jo'jalar ro'yxati</h3>
        <table>
            <thead>
                <tr>
                    <th>Katak nomi</th>
                    <th>Soni</th>
                    <th>O'lgan sana</th>
                    <th>Izoh</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($olgan_joja = mysqli_fetch_assoc($fetch)) {?>                    
                    <tr>
                        <td><?=$olgan_joja['katak_nomi']?></td>
                        <td><?=$olgan_joja['soni']?></td>
                        <td><?=$olgan_joja['sana']?></td>
                        <td><?=$olgan_joja['izoh']?></td>                        
                    </tr>
                <?}?>
            </tbody>
        </table>
    </div>
</section>
<script src="../js/jquery-3.6.0.min.js"></script>
<script src="../js/sweetalert.min.js"></script>
<script>
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